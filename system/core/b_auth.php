<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Auth {
    function permission() {
        $request_payload = json_decode(file_get_contents('php://input'), true);
        if(empty($request_payload)){
            
        }
        
        if(!isset($request_payload['jwt'])){
            show_error('Authentication', 'Token undefined');
        }
    }
}

?>