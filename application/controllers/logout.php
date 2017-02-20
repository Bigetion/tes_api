<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Logout extends Main {
    function index(){
        session_destroy();
        $this->set->success_message(true);
    }
}

?>