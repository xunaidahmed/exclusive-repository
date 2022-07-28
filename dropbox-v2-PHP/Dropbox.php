<?php
/**
 * Dropbox class
 *
 * @author Junaid Ahmed <xunaidahmed@live.com>
 * @version 1.1.1
 * @copyright Copyright (c), Junaid Ahmed. All rights reserved.
 * @license PSR-4 License
 */

require "Arr.php";

class Dropbox
{
	const API_URL = 'https://api.dropboxapi.com/2'; //url for the dropbox-api

	const API_AUTH_URL = 'https://www.dropbox.com'; //url for the dropbox

	const API_CONTENT_URL = 'https://content.dropboxapi.com/2'; //url for the dropbox-content

	private $API_DEBUG = false;  // internal constant to enable/disable debugging

	private $API_CLIENT_ID; //client id dropbox

	private $API_CLIENT_SECRET; //client secret dropbox

	private $API_CLIENT_ACCESS_TOKEN; //client access token dropbox

	private $API_ACCESS_TOKEN = null; //Dropbox Access token

	private $CALLBACK_URL = '';

	/*
	 * Set API Authority
	 * - - - - - - - - - - - -
	 *
	 * @param (string) $client_id
	 * @param (string) $client_secret
	 * @param (string) $client_access_token
	 **/
	public function __construct( $client_id, $client_secret, $client_access_token )
	{
		$this->API_CLIENT_ID           = $client_id;
		$this->API_CLIENT_SECRET       = $client_secret;
		$this->API_CLIENT_ACCESS_TOKEN = $client_access_token;
	}

	/*
	 * Set the Callback URL
	 * - - - - - - - - - - - -
	 *
	 **/
	public function isDebug( $is_bool = false )
	{
		$this->API_DEBUG = $is_bool;
	}

	/*
	 * Set the Callback URL
	 * - - - - - - - - - - - -
	 *
	 **/
	public function setCallbackUrl( $url )
	{
		$this->CALLBACK_URL = $url;
	}

	/*
	 * Get the Callback URL
	 * - - - - - - - - - - - -
	 *
	 **/
	public function getCallbackUrl()
	{
		return $this->CALLBACK_URL;
	}

	/*
	 * Set the Access Token
	 * - - - - - - - - - - - -
	 *
	 **/
	public function setAccessToken()
	{

	}

	/*
	 * Access Token store
	 * - - - - - - - - - - - -
	 *
	 **/
	public function getAccessToken()
	{
		return [];
	}

	/**
	 * API CURL "API Dropbox" Calling
	 * @call //api.dropboxapi.com
	 */
	public function doOAuthCall($url, $params = [], $expectJSON = true )
	{
		$api_url = self::API_URL . '/'. ltrim($url, '/');

		if ( $this->API_DEBUG ) {
			Arr::dd( $api_url, false, 'api_url' );
		}

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		if( is_array($params) && count($params) || $params ) {
			$params = (is_array($params) ? json_encode($params) : $params);

			if ( $this->API_DEBUG ) {
				Arr::dd( $params, false, 'params' );
			}

			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}

		curl_setopt($ch, CURLOPT_POST, 1);

		$headers = array(
			"Authorization: Bearer " . $this->API_CLIENT_ACCESS_TOKEN,
			"Content-Type: application/json"
		);

		if ( $this->API_DEBUG ) {
			Arr::dd( $headers, false, 'headers' );
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);

		if( $this->API_DEBUG ) {
			Arr::dd($result, false, 'doOAuthCall');
		}

		return json_decode($result, $expectJSON);
	}

    /**
     * Get Access Token
     * This method corresponds to Obtaining an Unauthorized Request Token in the OAuth Core 1.0 specification.
     * @links https://www.dropbox.com/developers/documentation/http/documentation
     * @url '/oauth2/token'
     *
     * @return array
     */
    public function oAuthRequestToken()
    {
		//code ...
    }

	/**
	 * Create Folder
	 *
	 * sends a request to get OAuth request token and secret, builds the request_token_url
	 * Step 1: call for request
	 * @link https://www.dropbox.com/developers/documentation/http/documentation#files-create_folder
	 * @url /files/create_folder
	 */
	public function createFolder($folder_name)
	{
		$storage_url = '/files/create_folder_v2';
		$params = ['path' => '/' . ltrim($folder_name, '/')];

		$create_storage = $this->doOAuthCall($storage_url, $params );

		if( isset($create_storage['metadata']) && count($create_storage['metadata']) ) {
			return [
				'name' => $create_storage['metadata']['name'],
				'path' => $create_storage['metadata']['path_display'],
			];
		}

		return $create_storage;
	}

	/**
	 * API CURL "Content API Dropbox" Calling
	 * @call //content.dropboxapi.com
	 * @url https://content.dropboxapi.com/2/files/upload
	 */
	public function doContentUpload( $folder, $file )
	{
		$storage_file = $this->_uploadFile($file); //create temp

		if( ! $storage_file ) {
			return "Invalid File";
		}

		$arg_headers = [
			'path' => '/'. ltrim($folder, '/') . '/' . $storage_file['file_name'],
			'mode' => 'add'
		];

		$headers = array(
			'Authorization: Bearer ' . $this->API_CLIENT_ACCESS_TOKEN,
			'Content-Type: application/octet-stream',
			'Dropbox-API-Arg: '. json_encode($arg_headers)
		);

		$fp = fopen($storage_file['path'], 'rb');
		$fs = filesize($storage_file['path']);

		$ch = curl_init('https://content.dropboxapi.com/2/files/upload');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_PUT, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_INFILE, $fp);
		curl_setopt($ch, CURLOPT_INFILESIZE, $fs);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);

		curl_close($ch);
		fclose($fp);

		Arr::deleteDir($storage_file['full_path']); //delete temp

		return json_encode($response, true);
	}

	/**
	 * List of Folders
	 *
	 * @link https://www.dropbox.com/developers/documentation/http/documentation#files-list_folder
	 * @url /files/list_folder
	 */
	public function getStorageLists($columns = [])
	{
		$storage = '/files/list_folder';
		$params  = [
			'path' => ''
		];

		$folders    = [];
		$lists      = $this->doOAuthCall($storage, $params );
		//Arr::dd($lists);

		if( isset($lists['entries']) && count($lists['entries']))
		{
			$folders = $lists['entries'];

			if( is_array($columns) && count($columns) ) {
				$folders = $this->_folderListByColumns($folders, $columns);
			}
		}

		return $folders;
	}

	/**
	 * Get Share Links
	 *
	 * @link https://www.dropbox.com/developers/documentation/http/documentation#sharing-list_shared_links
	 * @url /sharing/list_shared_links
	 */
	public function getShareLinks($filename, $file_type )
	{
		$storage = '/sharing/list_shared_links';
		$parameters = array('path' => '/' . ltrim($filename, '/'));

		$links = $this->doOAuthCall($storage, $parameters );

		if ( isset( $links['links'] ) && count( $links['links'] ) ) {
			return $links['links'][0]['url']??null;
		}

		return null;
	}

	/*
	 * Add Share Links
	 *
	 * @url https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings
	 * @links /sharing/create_shared_link_with_settings
	 * @param $folder
	 * @param $file
	 * **/
	public function doShareLinks( $folder, $file )
	{
		$storage = '/sharing/create_shared_link_with_settings';
		$params  = ["path" => '/'. ltrim($folder, '/') . '/' . ltrim($file, '/') , "settings" => ['requested_visibility' => 'public']];

		$this->doOAuthCall($storage, $params );

		return $folder;
	}

	/**
	 * List of Folder & Files via Storage
	 *
	 * @link https://www.dropbox.com/developers/documentation/http/documentation#files-list_folder
	 * @url /files/list_folder
	 */
	public function getListByStorage($storage, $columns = [])
	{
		$storage_disk = '/files/list_folder';
		$params       = [ 'path' => '/' . ltrim( $storage, '/' ) ];

		$folders = [];
		$lists   = $this->doOAuthCall( $storage_disk, $params );
		//Arr::dd($lists);

		if( isset($lists['entries']) && count($lists['entries']))
		{
			$folders = $lists['entries'];

			if( is_array($columns) && count($columns) ) {
				$folders = $this->_folderListByColumns($folders, $columns);
			}
		}

		return $folders;
	}

	/*
	 * Download File
	 * @url https://api.dropboxapi.com/2/files/get_temporary_link
	 * @links /files/get_temporary_link
	 * **/
	public function downloadFile($storage, $file_name)
	{
		$storage_disk = '/files/get_temporary_link';
		$params       = [ 'path' => '/' . ltrim( $storage, '/' ) ];
		$download_link= '';

		$links = $this->doOAuthCall( $storage_disk, $params );

		if( isset($links['link']) && $links['link'] ) {
			$download_link = $links['link'];
		}

		return $download_link;
	}

	public function getFileUrl($storage, $file_name)
	{

	}

	private function _folderListByColumns( $arr, $columns)
	{
		return array_map(function($v) use ($columns) {

			$arr_columns    = array_keys($v);
			$diff_columns   = array_diff($arr_columns, $columns);

			foreach($diff_columns as $c) {
				unset($v[$c]);
			}

			return $v;
		}, $arr);
	}

	public static function backToStorage( $now_storage )
	{
		$storage_disk = array_filter(explode('/', $now_storage));
		$storage_disk = (count($storage_disk) == 1 ? '' : $storage_disk );

		if( is_array($storage_disk) && count($storage_disk) ){
			array_pop($storage_disk);
		}

		$storage_disk = '/' . (is_array($storage_disk) && count($storage_disk) ? implode('/', $storage_disk) : '');

		return $storage_disk;
	}

	public static function isCheckFolder( $filename )
	{
		$explode = explode('.', $filename);

		return (bool) !(count($explode) == 2);
	}

	private function _uploadFile( $file )
	{
		$target_dir = '_tmp';
		@mkdir ( '_tmp', 0777);

		$file_name = basename($file["name"]);

		$upload = move_uploaded_file($file['tmp_name'],$target_dir . "/". $file_name);

		if( $upload ) {
			return [
				'file_name' => $file_name,
				'path'      => $target_dir . "/" . $file_name,
				'full_path' => $target_dir,
			];
		}

		return false;
	}
}
