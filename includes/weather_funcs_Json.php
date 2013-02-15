<?php
	function getJson($lat_max, $lat_min, $lon_max, $lon_min, $maximum, $days){
		require('DB_Connection.php');
		require('/home/blueghos/db.php');
		require('paths.php');
		if ($days == 0){
			$filename = $dir.'weather_data/search_'.$lat_min.'_'.$lat_max.'_'.$lon_min.'_'.$lon_max.'.json';
			if (file_exists($filename)){
				if ((filectime($filename) + (30*60)) < time() ){/*CHECK SEARCH CACHE*/
					//continue
				}else
					return file_get_contents($filename);
			}
		}else{
			$filename = $dir.'weather_data/search_'.$lat_min.'_'.$lat_max.'_'.$lon_min.'_'.$lon_max.'_days.json';
			if (file_exists($filename)){
				if ( (filectime($filename) + (12*60*60)) < time() ){/*CHECK SEARCH CACHE*/
					//continue
				}else
					return file_get_contents($filename);
			}
		}
		$locs	= array();
		$dbase   	= 'blueghos_bbc';
		$Database 	= new DB_Connection();
		$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
		$sql 	= 'SELECT `id` FROM `weather_locs` WHERE `lat` >= '.$lat_min.' AND `lat` <= '.$lat_max.' AND `lon` >= '.$lon_min.' AND `lon` <= '.$lon_max.' LIMIT 0, 1000';
		$wl 	= $Database->DB_search($sql);
		//echo "<h1>SQL 1 = ".$sql."</h1>";
		if ($Database->DB_num_results($wl) < $maximum){
 			while ( $w = $Database->DB_array($wl) ){
				$locs[]		= $w['id'];
			}
		}else{/*REFINE SEARCH*/
			$sql 	= 'SELECT `id` FROM `weather_locs` WHERE `level` = 2 AND `lat` >= '.$lat_min.' AND `lat` <= '.$lat_max.' AND `lon` >= '.$lon_min.' AND `lon` <= '.$lon_max.' LIMIT 0, 1000';
			$wl 	= $Database->DB_search($sql);
			//echo "<h1>SQL 2 = ".$sql."</h1>";
			while ( $w = $Database->DB_array($wl) ){
				$locs[]		= $w['id'];
			}
		}
		$xml = array();
		for ($i=0; $i < count($locs); $i++){
			if ($days == 0){
				//$filename = $dir.'weather_data/'.$locs[$i].'.xml';
				$xml[] = $dir.'weather_data/'.$locs[$i].'.json';
			}else{
				//$filename = $dir.'weather_data/'.$locs[$i].'_days.xml';
				$xml[] = $dir.'weather_data/'.$locs[$i].'_days.json';
			}
		}
		//echo "<h1>XML COUNT =  ".count($xml)."</h1>";
		$output = "{\"locations\":[";
		for ($i=0; $i < count($xml); $i++){
			$output .= file_get_contents($xml[$i]).",";
		}
		$output = substr($output, 0, (strlen($output)-1));
		$output .= "]}";
		if ($days == 0){
			write_header($dir.'weather_data/search_'.$lat_min.'_'.$lat_max.'_'.$lon_min.'_'.$lon_max.'.json', $output);
		}else{
			write_header($dir.'weather_data/search_'.$lat_min.'_'.$lat_max.'_'.$lon_min.'_'.$lon_max.'_days.json', $output);
		}
		return $output;
	}
		
	function write_header($file,$text){
   		if (!$handle = fopen($file, 'w')) {
      		echo "Cannot Open File ($file)";
			exit;
   		}

   		if (!fwrite($handle, $text)) {
      		echo "Cannot write to file ($file)";
      		exit;
   		}

   		//echo "Success, wrote ($text) to file ($file)";

   		fclose($handle);
	}
	
	header('Content-type: application/x-json'); 
	echo getJson($_GET['lat_max'], $_GET['lat_min'], $_GET['lon_max'], $_GET['lon_min'], $_GET['maximum'], $_GET['day']);

?>