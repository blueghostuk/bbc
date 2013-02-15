<?php

	function getXML($location){
		require('includes/paths.php');
		$filename = $dir.'travel_data/'.$location.'_geoRss.rss';
		return file_get_contents($filename);
	}
	
	function getCounties(){
		require('includes/DB_Connection.php');
		require('/home/blueghos/db.php');
		require('includes/paths.php');
		$filename = $dir.'travel_data/locations.rss';
		return file_get_contents($filename);
	}
	
	header('Content-type: application/rss+xml'); 
	if ($_GET['location'])
		echo getXML($_GET['location']);
	else
		echo getCounties();
?>