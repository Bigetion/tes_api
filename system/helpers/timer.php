<?php

function get_process_time($message) {
    $finish_time = get_time();
    return str_replace('::time::', round(($finish_time - start_time), 2), $message);
}

function time_to_ago ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}

function format_date($timestamp) {

    $date = date('d/m/Y', $timestamp);

    if($date == date('d/m/Y')) {
      $date = 'Today at';
    } 
    else if($date == date('d/m/Y',now() - (24 * 60 * 60))) {
      $date = 'Yesterday at';
    }
	
    return $date." ".date('H:i', $timestamp);
}
?>