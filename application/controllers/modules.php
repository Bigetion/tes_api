<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Modules extends Main {

    function __construct() {
        $this->auth->permission();
    }
	
	function remove_module(){
		$module = segment(4);	
		$this->dir->remove_dir("project/".$module);
		redirect('app',false);
	}
	
	function remove_controller(){
		$module = segment(4);
		$controller = segment(5);
		
		$this->dir->delete_file("project/".$module."/controllers/".$controller.".php");
		$this->dir->remove_dir("project/".$module."/views/".$controller);
		
		redirect('app',false);
	}
}

?>