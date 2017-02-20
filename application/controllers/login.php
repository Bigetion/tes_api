<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Login extends Main {

    function index(){
        $this->render->json_post();
        $user = strtolower($_POST['username']);
        $password = $_POST['password'];

        if (empty($user)|| empty($password))
            $this->set->error_message("Username atau password harus diisi.!");
            
        $data = $this->db->select("users","*",["username"=>$user]);
        if (count($data) == 0)
            $this->set->error_message('Username dan Password salah..!');
        else {
            if(!password_verify($password,$data[0]["password"])) $this->set->error_message("Username dan password tidak cocok.!");
            else{
                $_SESSION[base_url.'login'] = "aktif";
				$_SESSION[base_url.'user'] = strtolower($_POST['username']);
				$_SESSION[base_url.'loginhash'] = password_hash(session_id()."aktif",1);
				$_SESSION[base_url.'userhash'] = password_hash(session_id().strtolower($_POST['username']),1);
                $this->set->success_message(true);
            }
        }
    }

    function logout(){
        session_destroy();
        $this->set->success_message(true);
    }

}

?>