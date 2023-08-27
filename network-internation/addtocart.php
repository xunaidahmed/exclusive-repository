<?php

    function createOrder($request, $orderDetails, $customer, $customerAddress)
    {
        $capture_total_amount = (round($orderDetails->paid_amount, 2) * 100);

        $postData = new \StdClass();
        $postData->action = "PURCHASE";

        $postData->amount = new \StdClass();
        $postData->amount = ["currencyCode" => "USD", "value" => $capture_total_amount];
        $postData->merchantOrderReference = time();
        $postData->emailAddress = $customer->email;
        $postData->billingAddress = ["firstName" => $customer->first_name, 'lastName' => $customer->last_name, 'address1' => $customerAddress->full_address, 'city' => 'Dubai', 'countryCode' => 'ARE'];

        saveLogs( ((array) $postData), $customer->id , 'network_order_logs');

        $isCardSaved            = ((bool) ($request['is_card_saved'] ?? 0));
        $getSaveCard            = SaveCard::where(['user_id' => $customer->id]);
        $checkUserCardsExists   = $getSaveCard->count() > 0 ? true : false;

        $callback_url   = force_https('/api/paymentRedirectUrl');
        $cancel_url     = force_https('/api/cancelUrl');

        $merchant_attr = [
            "redirectUrl"           => $callback_url,
            "cancelUrl"             => $cancel_url,
            "skipConfirmationPage"  => true,
            "skip3DS"               => false,
            "isCardSaved"           => $isCardSaved
        ];

        if($checkUserCardsExists == true) {
            $merchant_attr['skip3DS'] = true;
        }

        $postData->merchantAttributes   = $merchant_attr;
        $postData->merchantDefinedData  = ["orderId" => $orderDetails->id, "shopperId" => $orderDetails->customer_id];

        $token = getAccessToken()->access_token;

        if( is_null($token) ) {
            return ['error' => 'There was an issue setting up your call access token'];
        }

        $json   = json_encode($postData);

        $outlets_headers = array(
            "Authorization: Bearer ".$token,
            "Content-Type: application/vnd.ni-payment.v2+json",
            "Accept: application/vnd.ni-payment.v2+json"
        );

        $api_url_outlet_ref     = config('configs.sandbox.outlet_ref');
        $api_url_outlets        = config('configs.sandbox.outlets');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url_outlets . '/' .$api_url_outlet_ref."/orders");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $outlets_headers );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $output = json_decode(curl_exec($ch), true);
        curl_close ($ch);


        if( isset($output['errors']) && $output['errors'] ) {
            return ['error' => 'Error: ' . $output['errors'][0]['errorCode'] ];
        }

        saveLogs( ((array) $output), $customer->id , 'network_order_outleft_log');

        $output = collect($output);

        $embed_payments     = $output['_embedded'] ?? [];
        $detail_payments    = $embed_payments['payment'][0] ?? [];

        if( !(count($embed_payments) && count($detail_payments)) ) {
            return ['error' => 'Gateway has rejected request. SaveCard option disabled'];
        }

        $links_payment   = $detail_payments['_links'] ?? [];
        if( !count($links_payment) ) {
            return ['error' => 'Gateway links has rejected request. Security header is not valid'];
        }

        $new_url_card   = $output['_links']['payment']['href'];
        $apple_pay_url  = $links_payment['payment:apple_pay']['href'] ?? '';

        if( !(isset($output['reference']) && $output['reference'])) {
            return ['error' => 'Gateway reference order problem rejected request. Security header is not valid'];
        }

        $order_reference = $output['reference'];

        if( $checkUserCardsExists == true )
        {
            if( isset($links_payment['payment:saved-card']) && $links_payment['payment:saved-card']['href']){
                $savedCardUrl = $links_payment['payment:saved-card']['href'];
            }
            else {
                $savedCardUrl = $links_payment['payment']['href'];
            }

            $cardData       =   $getSaveCard->get()->toArray();
            $returnUrl      =   [
                "apple_pay_url" => $apple_pay_url,
                "url"           => $savedCardUrl, "new_card_url" => $new_url_card, "saved" => true, "stored_cards" => $cardData,
                'order_id'      => $orderDetails->id, 'customer_id' => $customer->id, 'order_ref' => $order_reference, 'output' => $output
            ];
        }
        else {
            $returnUrl = ["apple_pay_url" => $apple_pay_url, "url" => $new_url_card, "saved" => false, 'output' => $output];
        }

        $return['data'] = $returnUrl;

        return $return;
    }