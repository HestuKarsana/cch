<?php

function totalDays($start, $end){
	$time2 		= strtotime($end); // or your date as well
	$time1 		= strtotime($start);
	$datediff 	= $time2 - $time1;

	$days 		= round($datediff / (60 * 60 * 24));
	return $days;
}



function custom_number_format($n, $precision = 3) {
    if ($n < 1000) {
        // Anything less than a million
        $n_format = number_format($n);
	} else if ($n < 1000000) {
        // Anything less than a billion
        $n_format = number_format($n / 1000, $precision) . 'K';
    } else if ($n < 1000000000) {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, $precision) . 'M';
    } else {
        // At least a billion
        $n_format = number_format($n / 1000000000, $precision) . 'B';
    }

    return $n_format;
}

function number_format_short( $n, $precision = 1 ) {
	if ($n < 900) {
		// 0 - 900
		$n_format = number_format($n, $precision);
		$suffix = '';
	} else if ($n < 900000) {
		// 0.9k-850k
		$n_format = number_format($n / 1000, $precision);
		$suffix = 'K';
	} else if ($n < 900000000) {
		// 0.9m-850m
		$n_format = number_format($n / 1000000, $precision);
		$suffix = 'M';
	} else if ($n < 900000000000) {
		// 0.9b-850b
		$n_format = number_format($n / 1000000000, $precision);
		$suffix = 'B';
	} else {
		// 0.9t+
		$n_format = number_format($n / 1000000000000, $precision);
		$suffix = 'T';
	}
  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
	if ( $precision > 0 ) {
		$dotzero = '.' . str_repeat( '0', $precision );
		$n_format = str_replace( $dotzero, '', $n_format );
	}
	return $n_format . $suffix;
}
	
function generateInitialName($string){
	#$string		= preg_split("/[\s,_-]+/", $string);
	$words 		= explode(" ",$string);
	$acronym 	= "";
	if(count($words) > 1){
		foreach ($words as $w){
		  $acronym .= $w[0];
		}
	}else{
		$acronym .= $string;
	}
	
	return substr($acronym,0,2);
	
}

function bgAvatar(){
	return $bgAvatar = array(
        '#f44336',
        '#E91E63',
        '#9C27B0',
        '#673AB7',
        '#3F51B5',
        '#2196F3',
        '#03A9F4',
        '#00BCD4',
        '#009688',
        '#4CAF50',
        '#8BC34A',
        '#CDDC39',
        '#FFC107',
        '#FF9800',
        '#FF5722',
    );
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
	
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function SetDateFormatFromID($date,$format){
	$x 		= explode('/',$date);
	$new 	= date($format,mktime (0,0,0,intval($x[1]),intval($x[0]),intval($x[2])));
	return $new;
}

function SetDateTimeFormatFromID($date,$format){
	$date_array 	= explode(" ",trim($date));
	$d	 = explode('/',$date_array[0]);
	$t	 = explode(':',$date_array[1]);
	$detik	= (count($t) == 3) ? $t[2] : 0;
	$new = date($format,mktime (intval($t[0]),intval($t[1]),intval($detik),intval($d[1]),intval($d[0]),intval($d[2])));
	return $new;
}

function getPriorityText($priority){
	if($priority == 5){
		$value	= '-';
	}else if($priority == 4){
		$value	= 'Low';
	}else if($priority == 3){
		$value	= 'Medium';
	}else if($priority == 2){
		$value	= 'High';
	}else if($priority == 1){
		$value	= 'Urgent';
	}else{
		$value  = '-';
	}
	
	return $value;
}

function phpinfo_array() {
	ob_start();
	phpinfo();
	$info_arr = array();
	$info_lines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));
	$cat = "General";
	foreach($info_lines as $line) {
		// new cat?
		preg_match("~<h2>(.*)</h2>~", $line, $title) ? $cat = $title[1] : null;
		if(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
			$info_arr[$cat][$val[1]] = $val[2];
		} elseif(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
			$info_arr[$cat][$val[1]] = array("local" => $val[2], "master" => $val[3]);
		}
	}
	return $info_arr;
}

function myprint_r($my_array) {
	if (is_array($my_array)) {
		echo "<table class='table' style='font-size:9px'>";
			foreach ($my_array as $k => $v) {
				echo '<tr><td valign="top" style="width:40px;background-color:#F0F0F0;">';
				echo '<strong>' . $k . '</strong></td><td>';
				myprint_r($v);
				echo "</td></tr>";
		}
		echo "</table>";
		return;
	}
	echo $my_array;
}

function valid_email($email){
	return preg_match('/^[A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{2,4}$/', $email);
}

