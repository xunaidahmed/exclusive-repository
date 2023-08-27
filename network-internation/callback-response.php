<?php

    /**
     * Hooks :: Payment Gateway :: Callback Order Response
     * 
     * **/
    function callbackResposne(Request $request, $orderId = 0)
    {
        if( !$request->ref ) {
            return view('payment-accepted-waiting', compact('orderId'));
        }

        saveLogs( ((array) $request), 'network_callback_reponse', 'network_callback_payment_response_logs');

        $access_token = getAccessToken()->access_token;
        saveLogs( ((array) $request), $access_token, 'network_callback_tokens_logs');

        $outlets_headers = [
            'Authorization: Bearer '.$access_token
        ];

        $api_url_outlet_ref     = config('configs.sandbox.outlet_ref');
        $api_url_outlets        = config('configs.sandbox.outlets');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url_outlets. '/' .$api_url_outlet_ref."/orders/".$request->ref);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $outlets_headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        saveLogs( ((array) $result), $access_token, 'network_callback_outLets_logs');

        /*if( !isset($result->merchantDefinedData, $result->_embedded) ) {
            return ['error' => 'Your payment has rejected request. Security header is not valid'];
        }*/

        $shopperId          = $result->merchantDefinedData->shopperId;
        $orderId            = $result->merchantDefinedData->orderId;
        $paidAmount         = $result->amount->value/100;
        $callback_status    = $result->_embedded->payment[0]->state;
        $auth_error_codes   = config('gateway-settings.errorCodes');

        // dump($network_collection);
        // dd($result);

        //For "Save Card :: Functionality"
        if($result->merchantAttributes->isCardSaved == "true")
        {
            if(isset($result->_embedded->payment[0]->savedCard) && $result->_embedded->payment[0]->savedCard)
            {
                $savedCard = $result->_embedded->payment[0]->savedCard;
                $getSaveCard = SaveCard::where(['user_id' => $shopperId, 'maskedPan' => $result->_embedded->payment[0]->savedCard->maskedPan]);

                if($getSaveCard->count() == 0)
                {
                    $resultData = [
                        'user_id'           =>   $shopperId,
                        'maskedPan'         =>   $savedCard->maskedPan,
                        'expiry'            =>   $savedCard->expiry,
                        'cardholderName'    =>   $savedCard->cardholderName,
                        'scheme'            =>   $savedCard->scheme,
                        'cardToken'         =>   $savedCard->cardToken,
                        'recaptureCsc'      =>   $savedCard->recaptureCsc
                    ];
                    $saveCard = SaveCard::create($resultData);
                }
            }
        }

        $merchantDefinedData = ['orderId' => $orderId, 'shopperId' => $shopperId];
        $userFCM = UserFCM::where(['user_id'=>$shopperId])->pluck('fcm_token')->all();
        $fresult = [$result, $merchantDefinedData, $userFCM];
        
        $order = Order::find($orderId);
        saveLogs( $order, $shopperId , 'network_callback_order_logs');

        //Desktop Notificaiton
        $orderNotification = ['order_id'  =>  $orderId, 'created_at'   => \Carbon\Carbon::now()->format('Y-m-d H:i:s')];
        event(new \App\Events\NewOrder($orderNotification));

        //For "Purchased"
        if( ($callback_status == "PURCHASED") && isset($result->_embedded->payment[0]->authResponse) )
        {
            $callback_auth      = $result->_embedded->payment[0]->authResponse;
            $callback_msg       = $callback_auth->resultMessage ?? 'Payment Successful';

            $network_collection = [
                'status'    => $callback_status,
                'message'   => $callback_msg,
            ];

            saveLogs( $network_collection, $shopperId , 'network_callback_larafirebase_window');
            
            Larafirebase::fromArray(["title" => "PURCHASED", "body" => $callback_msg, "callback_response" => $network_collection])->withPriority('normal')->sendNotification($userFCM);
            Larafirebase::fromArray(["title" => "Order Successful!", "body" => "Thank you for choosing."])->withPriority('normal')->sendNotification($userFCM);

            $payAmount          = number_format($paidAmount, 2);
            $dueAmount          = number_format(0, 2);
            $orders_discount    = number_format($order->discount, 2);

            $order->payment_status  =   Order::PAID;
            $order->payment_type    =   Order::PAYMENT_CREDIT_CARD;
            $order->order_ref       =   $request->ref;
            $order->paid_amount     =   $payAmount;
            $order->save();
        }

        //For Payment Failed
        else
        {
            $order->payment_status  =   Order::REJECTED;
            $order->payment_type    =   Order::PAYMENT_CREDIT_CARD;
            $order->order_ref       =   $request->ref;
            $order->save();

            $failed_status    = $result->_embedded->payment[0]->state ?? 'FAILED';
            $failed_code      = ((int) $result->_embedded->payment[0]->authResponse->resultCode ?? '404');
            $faild_message    = 'Payment Gateway is unreachable at the moment. Please try again';

            $network_collection = [
                'code'      => $failed_code,
                'status'    => $failed_status,
                'message'   => '['.$failed_code . '] '. $auth_error_codes[$failed_code] ?? $faild_message,
            ];

            if( isset($result->_embedded->payment[0], $result->_embedded->payment[0]->authResponse, $result->_embedded->payment[0]->authResponse->resultMessage) )
            {
                $auth_response      = $result->_embedded->payment[0]->authResponse;
                $failed_code        = ((int) $auth_response->resultCode ?? '404');

                $network_collection = [
                    'code'      => $failed_code,
                    'status'    => $failed_status,
                    'message'   => '['.$failed_code . '] '.  ($auth_response->resultMessage ?? $auth_error_codes[$failed_code] ?? $faild_message),
                ];
            }

            Larafirebase::fromArray(["title" => "FAILED", "body" => $network_collection['message'], "callback_response" => $network_collection])->withPriority('normal')->sendNotification($userFCM);
        }
        
        return view('payment-accepted-waiting', compact('orderId'));
    }

    /**
     * Hooks 2 :: Payment Gateway :: store Cards :: Callback Order Response
     * 
     * **/
    function callbackSaveCardResponse(Request $request, $order_id = 0)
    {
        $input      = $request->all();
        $orderId    = $input['order_id'];
        $customerId = $input['customer_id'];

        saveLogs( ((array) $input), $orderId, 'network_callback_savecard_response');

        $stoedCard = [
            'maskedPan'     =>  $input['selectedCard']['maskedPan'],
            'expiry'        =>  $input['selectedCard']['expiry'],
            'cardholderName'=>  $input['selectedCard']['cardholderName'],
            'scheme'        =>  $input['selectedCard']['scheme'],
            'cardToken'     =>  $input['selectedCard']['cardToken'],
            'recaptureCsc'  =>  $input['selectedCard']['recaptureCsc']
        ];
        $curl_response = curl_savecard_response($input['url'], $stoedCard);

        saveLogs( ((array) $curl_response), $orderId, 'network_callback_savecard_api_response');

        $merchantDefinedData    = ['orderId' => $orderId, 'shopperId' => $customerId ];
        $userFCM                = UserFCM::where(['user_id' => $customerId])->pluck('fcm_token')->all();
        $order                  = Order::find($orderId);

        if($curl_response['data']->state == "PURCHASED")
        {
            if( $order )
            {
                $order->payment_status  =   1;
                $order->payment_type    =   2;
                $order->order_ref       =   $request->ref;
                $order->save();
            }

            $network_status = $curl_response['data']->state ?? 'PURCHASED';
            $network_auth   = $curl_response['data']->authResponse;

            $network_collection = [
                'status'    => $network_status,
                'message'   => $network_auth->resultMessage ?? 'Payment Successful',
            ];
         
            Larafirebase::fromArray(["title" => "PURCHASED", "body" => $network_collection['message'], "callback_response" => $network_collection])->withPriority('normal')->sendNotification($userFCM);
            Larafirebase::fromArray(["title" => "Order Successful!", "body" => "Thank you for choosing."])->withPriority('normal')->sendNotification($userFCM);

            //Desktop Notificaiton
            $orderNotification = ['order_id' => $orderId, 'created_at'   => \Carbon\Carbon::now()->format('Y-m-d H:i:s')];
            event(new \App\Events\NewOrder($orderNotification));
            
            $payAmount          = number_format($order->paid_amount, 2);
            $dueAmount          = number_format(0, 2);
            $orders_discount    = number_format($order->discount, 2);
        }
        else
        {
            $order->payment_status  =   Order::REJECTED;
            $order->payment_type    =   Order::PAYMENT_CREDIT_CARD;
            $order->order_ref       =   $request->ref;
            $order->save();

            $network_status     = $returnGateway['data']->state ?? 'FAILED';
            $network_auth       = $returnGateway['data']->authResponse;
            $failed_code        = ((int) $network_auth->resultCode ?? '404');
            $faild_message      = 'Payment Gateway is unreachable at the moment. Please try again';
            $auth_error_codes   = config('gateway-settings.errorCodes');

            $network_collection = [
                'code'      => $failed_code,
                'status'    => $network_status,
                'message'   => '['.$failed_code . '] '. $auth_error_codes[$failed_code] ?? $faild_message,
            ];

            Larafirebase::fromArray(["title" => "FAILED", "body" => $network_collection['message'], "callback_response" => $network_collection])->withPriority('normal')->sendNotification($userFCM);
        }

        return view('payment-accepted-waiting', compact('orderId'));
    }

    /**
     * Hooks :: Payment Gateway :: Cancel Order Response
     * 
     * **/
    function callbackCancelResponse(Request $request, $order_id = 0)
    {
        $access_token = getAccessToken()->access_token;
        saveLogs( ((array) $request), $access_token, 'network_callback_cancelUrl_tokens_logs');

        $outlets_headers = [
            'Authorization: Bearer '.$access_token
        ];

        $api_url_outlet_ref     = config('configs.sandbox.outlet_ref');
        $api_url_outlets        = config('configs.sandbox.outlets');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url_outlets .'/'.$api_url_outlet_ref."/orders/".$request->ref);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $outlets_headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        saveLogs( ((array) $result), $access_token, 'network_callback_cancelUrl_outLets_logs');

        $shopperId          = $result->merchantDefinedData->shopperId;
        $orderId            = $result->merchantDefinedData->orderId;

        $network_collection = [
            'code'      => '401',
            'status'    => 'FAILED',
            'message'   => 'Your order was cancelled',
        ];

        $userFCM = UserFCM::where(['user_id'=>$shopperId])->pluck('fcm_token')->all();
        Larafirebase::fromArray(["title" => "FAILED", "body" => $network_collection['message'], "callback_response" => $network_collection])->withPriority('normal')->sendNotification($userFCM);

        return view('payment-accepted-waiting', compact('orderId'));
    }

    /**
     * Close App :: Payment Gateway
     * 
     * **/
    function closeWindowNotify(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        $network_collection = [
            'code'      => '401',
            'status'    => 'FAILED',
            'message'   => 'Your payment is in process, we will let you know when it is completed from the bank',
        ];

        $fcm_tokens = UserFCM::where(['user_id' => $order->customer_id])->pluck('fcm_token')->all();

        Larafirebase::fromArray(["title" => "FAILED", "body" => $network_collection['message'], "callback_response" => $network_collection])->withPriority('normal')->sendNotification($fcm_tokens);
        exit();
    }