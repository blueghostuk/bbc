<?php
	header('Content-type: application/x-json'); 
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	require('includes/DB_Connection.php');
	require('/home/blueghos/db.php');
	$dbase   	= 'blueghos_bbc';
	$Database 	= new DB_Connection();
	$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
	
	if ($_REQUEST['mode'] == 'weather'){
		$sql = "SELECT `lat`, `lon`, `placename` AS `l_text` FROM `weather_locs` WHERE `placename` REGEXP '^".strtolower($_REQUEST["query"])."' LIMIT 0, 10";
		$results = $Database->DB_search($sql);
		$resultsArray = array();
		while ( $res = $Database->DB_arrayAssoc($results) ){
			$resultsArray[] = $res;
		}
		echo json_encode($resultsArray);
		return;
	}
	
	if ($_REQUEST['mode'] == 'traffic'){
		$sql = "SELECT `id`, LOWER(`filename`) AS `fname`, `lat`, `lon`, `display_name` AS `l_text` FROM `bbc_travel` WHERE `filename` REGEXP '^".strtolower($_REQUEST["query"])."' LIMIT 0, 10";
		$results = $Database->DB_search($sql);
		$resultsArray = array();
		while ( $res = $Database->DB_arrayAssoc($results) ){
			$resultsArray[] = $res;
		}
		echo json_encode($resultsArray);
		return;
	}
	
	if ($_REQUEST['mode'] == 'rail'){
		$sql = "SELECT `code`, `lat`, `lon`, `name` AS `l_text` FROM `ldb_stations` WHERE `name` LIKE '%".strtolower($_REQUEST["query"])."%' OR `code` LIKE '%".strtolower($_REQUEST["query"])."%' LIMIT 0, 10";
		$results = $Database->DB_search($sql);
		$resultsArray = array();
		while ( $res = $Database->DB_arrayAssoc($results) ){
			$resultsArray[] = $res;
		}
		echo json_encode($resultsArray);
		return;
	}
?>