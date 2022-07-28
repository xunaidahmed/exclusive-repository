<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_projects extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->table = TABLE_PROJECTS;
    }


    public function getSearch( $options )
    {
        $where_filters                  = null;
        $options['start']               = $options['start'] ?: 0;
        $options['length']              = $options['length'] ?: 25;
        $options['orderBy']             = $options['orderBy'] ?: 'id';
        $options['orderByDirection']    = $options['orderByDirection'] ?: 'desc';

        $where_filters                  = "is_enable <> 2 ";

        //Advance searching filters
        if( isset($options['title']) && $options['title'] )
        {
            $where_filters .= "AND title LIKE '%" . $options['title'] . "%'";
        }

        if( isset($options['title_ur']) && $options['title_ur'] )
        {
            $where_filters .= " AND title_ur LIKE '%" . $options['title_ur'] . "%'";
        }

        if( isset($options['is_enable']) && $options['is_enable'] != "" )
        {
            $where_filters .= " AND is_enable = '". $options['is_enable'] . "'";
        }

        if( isset($options['created_date']) && $options['created_date'] ){

            $where_filters .= ' AND DATE_FORMAT(created_date, "%Y-%m-%d") = "'.$options['created_date'].'")';
        }

        //Count Result
        $result_count = $this->db
        ->from($this->table)
        ->where($where_filters)
        ->get()
        ->num_rows();

        $result = $this->db
        ->from($this->table)
        ->where($where_filters)
        ->order_by($options['orderBy'], $options['orderByDirection'] )
        ->limit( $options['length'] )->offset( $options['start'] )
        ->select('
            id,
            title,
            title_ur,
            is_enable,
            DATE_FORMAT(created_date, "%Y-%m-%d") as created_date
        ')
        ->get();

        return [ 'total' => $result_count, 'dataset' => $result->result() ];
    }
}