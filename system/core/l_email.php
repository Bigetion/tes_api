<?php

if (!defined('INDEX'))
    exit('No direct script access allowed');

class Email {
	// send email using PERL
	function send($config = array('to'=>'','subject'=>'','message'=>'','sender'=>'','password'=>'')){
		
		$to 		= $config['to'];
		$subject 	= $config['subject'];
		$message	= $config['message'];
		$sender		= $config['sender'];
		$password	= $config['password'];
		
		$currentDir = getcwd();
		chdir('system\lib\sendmail_v156');
		$send_email = shell_exec('sendEmail.exe -f '.$sender.' -t '.$to.' -u '.escapeshellarg($subject).' -m '.escapeshellarg($message).' -s smtp.gmail.com:587 -xu '.$sender.' -xp '.escapeshellarg($password).' -o message-content-type=html message-charset=utf-8 tls=yes');
		chdir($currentDir);
		if($send_email){
			return true;
		}else{
			return false;
		}
	}
}

?>