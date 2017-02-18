<?php 
function get_ip_address()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

function get_header($header_key, $value_key){
    $cookies = getallheaders();
    if($header_key){
        $cookies = $cookies[$header_key];
        if($value_key){
            $value_arr = explode(';',$cookies);
            foreach($value_arr as $val){
                $sub_value = explode('=',$val);
                if($value_key==$sub_value[0]) $cookies=$sub_value[1];
            }
        }
    }
    return $cookies;
}

?>