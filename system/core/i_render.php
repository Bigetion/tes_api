<?php

if (!defined('INDEX'))
    exit('No direct script access allowed');

class Render {

    function json($data) {
        header('Content-Type: application/json');
		echo json_encode($data);
    }

	function json_post(){
		$_POST = json_decode(file_get_contents('php://input'), true);
	}

}

?>
