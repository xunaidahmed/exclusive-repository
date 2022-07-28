<?php defined('BASEPATH') OR exit('No direct script access allowed');

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

class SocketNotification extends Controller
{
    public function __construct(){
        // code ...
    }

    private function _eventSocketBroadCast()
    {
        $client = new Client(new Version2X(BROADCAST_URL));
        $client->initialize();
        $client->emit('socketNotify', ['type' => 'notification', 'text' => 'Successfully notify for admin']);
        $client->close();
    }

    public function socketBroadcastNotify()
    {
        // write the own functionality
    }

    public function notifyfire()
    {
        $this->_eventSocketBroadCast();

        sendResponse(FOL_RESPONSE_STATUS_SUCCESS, 'Successfully notify for admin', null);
    }
}