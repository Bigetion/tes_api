<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Login extends Main {
    function index(){
        $json_data = $this->render->json_post();
        $user = strtolower($json_data['username']);
        $password = $json_data['password'];

        if (empty($user)|| empty($password))
            $this->set->error_message("Username atau password harus diisi.!");
            
        $data = $this->db->select("users","*",["username"=>$user]);
        if (count($data) == 0)
            $this->set->error_message('Username dan Password salah..!');
        else {
            if(!password_verify($password,$data[0]["password"])) $this->set->error_message("Username dan password tidak cocok.!");
            else{
                // $_SESSION[base_url.'login'] = "aktif";
				// $_SESSION[base_url.'user'] = strtolower($_POST['username']);
				// $_SESSION[base_url.'loginhash'] = password_hash(session_id()."aktif",1);
				// $_SESSION[base_url.'userhash'] = password_hash(session_id().strtolower($_POST['username']),1);

                try{
                    $payload = array(
                        'jti'       => bin2hex(random_bytes(5)),
                        'iat'       => time(),
                        'nbf'       => time() + 10,
                        'exp'       => time() + 7210,
                        'iss'       => get_header('origin'),
                        'data'      => array(
                                    'user'  => strtolower($user),
                                    )
                    );
                    $jwtTokenEncode = $this->jwt->encode($payload, base64_decode(secret_key));
                    $jwtTokenDecode = $this->jwt->decode($jwtTokenEncode);

                    $token['jwt'] = $jwtTokenEncode;
                    $token['jwtTokenDecode'] = $jwtTokenDecode;
                    
                    $this->set->success_message(true, $token);
                }
                catch(Exception $ex){
                    $this->set->error_message(true, $ex);
                }
            }
        }
    }
}

?>