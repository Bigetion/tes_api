<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Auth {
    function permission() {
        $request_payload = json_decode(file_get_contents('php://input'), true);
        if(empty($request_payload)){
            
        }

        if(get_header('Authorization')){
            $authorization_header = get_header('Authorization');
            $bearer_header_list = explode('Bearer',get_header('Authorization'));
            $bearer_pos = stripos($authorization_header, 'bearer ');
            if ($authorization_header !== false && ($bearer_pos !== false)) {
                $authorization_header = substr($authorization_header,7);
                define('Bearer',$authorization_header);

                $db = & load_class('DB');
                $jwt = & load_class('JWT');
                try{
                    $jwt_payload = $jwt->decode(Bearer, base64_decode(secret_key));
                    $payload = json_decode(json_encode($jwt_payload), true);
                    $username = $payload['data']['user'];

                    $data = $db->query("select * from users where username = '$username'")->fetchAll();
                    $id_role = $data[0]['id_role'];

                    if($id_role!=1){
                        show_error('Authentication','Please login to access this page');
                    }
                }
                catch(Exception $ex){
                    show_error('Authentication','JWT Error');
                }
                // header('Content-Type: application/json');
                // echo json_encode(array('Bearer' => $authorization_header));
                // exit();
            }else{
                // header('Content-Type: application/json');
                // echo json_encode(array('count' => '0'));
                // exit();
                define('Bearer',false);
            }
        }else{
            // header('Content-Type: application/json');
            // echo json_encode(array('header'=>'0'));
            // exit();
            define('Bearer',false);
        }

        if(Bearer == false){
            show_error('Authentication', 'Bearer undefined');
        }
    }
}

?>