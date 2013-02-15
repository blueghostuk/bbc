<?php
	$time = "2005-07-05T19:36:43+0";
	$time 	= eregi_replace("T", " ", $time); //remove T with space
	$time	= substr($time, 0 , (strlen($time)-2)); //remove +0
	$st	= strtotime($time);
	$n	= time()+$st;
	echo "TIME = ".$time.", converted = ".$st.", dated = ".date("D jS M @ H:i", $st).",  plus = ".date("D jS M @ H:i", $n)."";
?>