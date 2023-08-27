<?php

if (! function_exists('getAccessToken'))
{
    function getAccessToken()
    {
        $api_url_key    = config('configs.sandbox.api_key');
        $api_url_token  = config('configs.sandbox.token_url');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url_token );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "accept: application/vnd.ni-identity.v1+json",
            "authorization: Basic ". $api_url_key,
            "content-type: application/vnd.ni-identity.v1+json"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);

        $output         = json_decode(curl_exec($ch));
        $access_token   = $output->access_token;

        return $output;
    }
}

if (! function_exists('curl_savecard_response'))
{
    function curl_savecard_response($savedCardUrl, $stoedCard)
    {
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $savedCardUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer ".getAccessToken()->access_token,
            "Content-Type: application/vnd.ni-payment.v2+json",
            "Accept: application/vnd.ni-payment.v2+json"
        ));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($stoedCard));
        $response = curl_exec($curl);

        return array(
            'code' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
            'data' => json_decode($response),
        );
    }
}

/* log contacts sync */
if (! function_exists('saveLogs'))
{
    function saveLogs( $logdata, $user_id = 0 , $filename = 'default_logs')
    {
        $logdata = is_array($logdata) ? json_encode($logdata) : $logdata;

        //Write action to txt log
        $log = "User: " . $_SERVER['REMOTE_ADDR'] . ' - ' . Carbon::now()->format( "F j, Y, g:i a" ) . PHP_EOL .
        "User-ID: " . $user_id . PHP_EOL .
        "LOGS: " . $logdata . PHP_EOL .
        "-------------------------" . PHP_EOL;

        $file_name     = storage_path('logs') . '/'.$filename.'_' . Carbon::now()->format( "j.n.Y" ) . '.txt';

        file_put_contents( $file_name, $log, FILE_APPEND );
    }
}