<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');
class Feedback {
	
    function set($class,$message){
		$_SESSION[base_url.'feedback'] = $message;
		$_SESSION[base_url.'feedback_class'] = $class;
	}
	
	function get(){
		if($_SESSION[base_url.'feedback']!=""){
			echo '<div class="'.$_SESSION[base_url.'feedback_class'].'"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION[base_url.'feedback'].'</div>';  
		}
		$_SESSION[base_url.'feedback'] = "";
	}
}
?>