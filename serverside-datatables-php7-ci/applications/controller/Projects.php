<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Mdl_projects');
    }
    
    public function manage()
    {
        $data['content']   = 'manage';
        $this->load->view('Template/template',$data);
    }

    private function _dataSearch($options)
    {
        $order = $options[ 'order' ];
        $options[ 'orderByDirection' ] = $order[ 0 ][ 'dir' ];

        switch ( $order[ 0 ][ 'column' ] )
        {
            case 1;
                $options[ 'orderBy' ] = 'title';
            break;

            case 2;
                $options[ 'orderBy' ] = 'title_ur';
            break;

            case 3;
                $options[ 'orderBy' ] = 'is_enable';
            break;

            case 4;
                $options[ 'orderBy' ] = 'created_date';
            break;
        }

        return $options;
    }

    //Ajax Request
    public function ajaxPostManage()
    {  
        $sEcho          = intval( $this->input->post( 'draw' ) );
        $dataSearch     = $this->_dataSearch( $this->input->post() );
        $result         = $this->Mdl_projects->getSearch( $dataSearch );
        // dd($result, false);
        
        $data           = [];
        $iTotalRecords  = $result[ 'total' ];

        if( count($result['dataset']) )
        {
            foreach( $result['dataset'] as $key => $value )
            {
                $data[] = array(
                    ($key+1),
                    $value->title,
                    $value->title_ur,
                    ($value->is_enable == 1 ? 'Active' : 'De-Active' ),
                    $value->created_date,
                    $this->_action( $value->id )
                );
            }
        }

        //output to json format
        echo json_encode(array(
            "draw"              => $sEcho,
            "recordsTotal"      => $iTotalRecords,
            "recordsFiltered"   => $iTotalRecords,
            "data"              => $data,
        ));
        exit();
    }

    private function _action( $id )
    {
        $params = encrypt($id);

        return '<center>
            <div class="tools">
                <a href="'.base_url('Projects/action/'. $params ).'" title="Edit" class="edit_button">
                    <i class="fa fa-pencil"></i>
                </a> &nbsp;&nbsp;
                <a href="javascript:void(0);" title="Delete" data-id="'.$params.'" class="delete text-danger">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </center>';
    }
}
