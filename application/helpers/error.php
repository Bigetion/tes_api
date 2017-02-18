<?php

function show_error($header = 'Page Not Found', $message = 'The page your requested was not found') {
    $data['error_message'] = $header.' : '.$message;

    if(strtolower($header)=='authentication') $data['require_login'] = true;
	
	header('Content-Type: application/json');
	echo json_encode($data);
    exit();
}

?>