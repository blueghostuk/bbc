<?php
	function getXML($lat_max, $lat_min, $lon_max, $lon_min, $maximum){
		require('DB_Connection.php');
		require('/home/blueghos/db.php');
		require('Tpeg_Parser.php');
		require('paths.php');
		$locs	= array();
		$dbase   	= 'blueghos_bbc';
		$Database 	= new DB_Connection();
		$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
		$sql 	= 'SELECT `filename` FROM `bbc_travel` WHERE `lat` >= '.$lat_min.' AND `lat` <= '.$lat_max.' AND `lon` >= '.$lon_min.' AND `lon` <= '.$lon_max.' LIMIT 0, 1000';
		$wl 	= $Database->DB_search($sql);
		//echo "<h1>SQL 1 = ".$sql."</h1>";
		
		//echo "<h1>FOUND ".$Database->DB_num_results($wl)." RESULTS</h1>";
		if ($Database->DB_num_results($wl) <= $maximum){
 			while ( $w = $Database->DB_array($wl) ){
				$locs[]		= strtolower(eregi_replace(" ", "", $w['filename']));
				//echo "<h1>FOUND RES ".$w['filename']."</h1>";
			}
		}/*else{/*REFINE SEARCH*//*
			$sql 	= 'SELECT `id` FROM `weather_locs` WHERE `level` = 2 AND `lat` >= '.$lat_min.' AND `lat` <= '.$lat_max.' AND `lon` >= '.$lon_min.' AND `lon` <= '.$lon_max.' LIMIT 0, 1000';
			$wl 	= $Database->DB_search($sql);
			//echo "<h1>SQL 2 = ".$sql."</h1>";
			while ( $w = $Database->DB_array($wl) ){
				$locs[]		= $w['id'];
			}
		}*/
		for ($i = 0; $i < count($locs); $i++){
			if ($locs[$i] != "motorways"){
				$filename = $dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml';
				//if (!file_exists($filename)  || ( (filectime($filename) + (60*60)) < time() )){ /*60 mins interval*/
					$url = "http://www.bbc.co.uk/travelnews/tpeg/en/local/rtm/".$locs[$i]."_tpeg.xml";
					copy($url, $dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml');
					$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml'));
					$output = eregi_replace("&loc3_", "", $output);
					write_header($dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml', $output);
					$url = $dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml';
					$parser = new Tpeg_Parser($url);
					$parser->setType("road");
					$err = false;
					if (!$parser->parseFile()){
    					$err = true;
  					}
  					$result = $parser->getOutput();
  					if ($result == null){
    					$err = true;
  					}
					if (!$err)
						write_header($filename, $result);
					else
						$locs[$i] = "null";
				//}
			}
		}
		$filename = $dir.'travel_data/motorways_tpeg_copy.xml';
		if (!file_exists($filename)  || ( (filectime($filename) + (60*60)) < time() )){ /*60 mins interval*/
			$url = "http://www.bbc.co.uk/travelnews/tpeg/en/local/rtm/motorways_tpeg.xml";
			copy($url, $dir.'travel_data/motorways_tpeg_copy.xml');
			$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/motorways_tpeg_copy.xml'));
			$output = eregi_replace("&loc3_", "", $output);
			write_header($dir.'travel_data/motorways_tpeg_copy.xml', $output);
			$url = $dir.'travel_data/motorways_tpeg_copy.xml';
			$parser = new Tpeg_Parser($url);
			$parser->setType("road");
			if (!$parser->parseFile()){
    				$err = true;
  			}
  			$result = $parser->getOutput();
  			if ($result == null){
    			$err = true;
  			}
			if (!$err)
				write_header($filename, $result);
			else
				$locs[$i] = "null";
		}
		$locs[]	= "motorways";
		$output = "<traffic_data>";
		for ($i = 0; $i < count($locs); $i++){
			if ($locs[$i] != "null"){
				$filename = $dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml';
				$fgc	= file_get_contents($filename);
				$fgc_1 	= substr($fgc,0,2);
				if ( $fgc_1 != "<?" )
					$output .= $fgc;
				else
					unlink($filename);
			}
		}
		$output .= "</traffic_data>";
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
	
	header('Content-type: text/xml'); 
	echo getXML($_GET['lat_max'], $_GET['lat_min'], $_GET['lon_max'], $_GET['lon_min'], $_GET['maximum']);

?>