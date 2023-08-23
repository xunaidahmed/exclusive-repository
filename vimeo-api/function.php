<?php

function fromSeconds($seconds)
{
   	$hours = floor($seconds/3600);
   	$seconds -= $hours*3600;
   	$minutes = floor($seconds/60);
   	$seconds -= $minutes*60;

	$time_render = '';
	
	if( $hours ) {
		$time_render .= $hours . ':';
	}
	
	$time_render .= $minutes.':'.$seconds;
	
	return $time_render;
}

function getVimeoDuration($vimeo_id)
{
    $authorize_access_token = '78fd41a961ef5efe41c201c9e13c266e0';    

    try
    {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL             => "https://api.vimeo.com/videos/$vimeo_id",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "GET",
            CURLOPT_HTTPHEADER      => array(
                "authorization: Bearer {$authorize_access_token}", "cache-control: no-cache",
            )
        ));

        $res = curl_exec($ch);
        return json_decode($res, true);
    }
    catch (Exception $e) {     
        return [];
    }
}
