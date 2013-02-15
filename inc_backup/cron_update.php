<?php
//include stuff
	require('DB_Connection.php');
	require('/home/blueghos/db.php');
	require('paths.php');
	require('Tpeg_Parser.php');
//db connection
	$dbase   	= 'blueghos_bbc';
	$Database 	= new DB_Connection();
	$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
//file ops
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
//travel stuff
	//update locations xml list
	$filename = $dir.'travel_data/locations.xml';
	$output 	= "<traffic_data>";
	$sql 	= 'SELECT `lat`, `lon`, `filename` FROM `bbc_travel` LIMIT 0, 1000';
	$wl 	= $Database->DB_search($sql);
	while ( $w = $Database->DB_array($wl) ){
		$location = eregi_replace(" ", "", $w['filename']);
		$output .= "<t_alert lat=\"".$w['lat']."\" lon=\"".$w['lon']."\" location=\"".strtolower($location)."\" title =\"".$w['filename']."\" />\n";
	}
	$output .= "</traffic_data>";
	write_header($filename, $output);
	unset($output);
	//update each location xml
	$wl 	= $Database->DB_search($sql);
	while ( $w = $Database->DB_array($wl) ){
		$location = eregi_replace(" ", "", $w['filename']);
		$location = strtolower($location);
		$filename = $dir.'travel_data/'.$location.'_tpeg_copy.xml';
		$url = "http://www.bbc.co.uk/travelnews/tpeg/en/local/rtm/".$location."_tpeg.xml";
		copy($url, $dir.'travel_data/'.$location.'_tpeg_copy.xml');
		$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/'.$location.'_tpeg_copy.xml'));
		$output = eregi_replace("&loc3_", "", $output);
		write_header($dir.'travel_data/'.$location.'_tpeg_copy.xml', $output);
		$url = $dir.'travel_data/'.$location.'_tpeg_copy.xml';
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
			$location = "null";
		$output = "<traffic_data>";
		if ($location != "null"){
			$fgc	= file_get_contents($filename);
			$fgc_1 	= substr($fgc,0,2);
			if ( $fgc_1 != "<?" )
				$output .= $fgc;
			else
				unlink($filename);
		}
		$output .= "</traffic_data>";
		unset($output);
	}//loop to next county
	
//motorways
	$location = 'motorways';
	$filename = $dir.'travel_data/'.$location.'_tpeg_copy.xml';
	$url = "http://www.bbc.co.uk/travelnews/tpeg/en/local/rtm/".$location."_tpeg.xml";
	copy($url, $dir.'travel_data/'.$location.'_tpeg_copy.xml');
	$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/'.$location.'_tpeg_copy.xml'));
	$output = eregi_replace("&loc3_", "", $output);
	write_header($dir.'travel_data/'.$location.'_tpeg_copy.xml', $output);
	$url = $dir.'travel_data/'.$location.'_tpeg_copy.xml';
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
		$location = "null";
	$output = "<traffic_data>";
	if ($location != "null"){
		$fgc	= file_get_contents($filename);
		$fgc_1 	= substr($fgc,0,2);
		if ( $fgc_1 != "<?" )
			$output .= $fgc;
		else
			unlink($filename);
	}
	$output .= "</traffic_data>";
	unset($output);
	
//rail stuff
	$rail = true;
	$tocs	= array();
	include('tocs.php');
		
	for ($i = 0; $i < count($tocs); $i++){
		$filename = $dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml';
		$url = "http://www.bbc.co.uk/travelnews/tpeg/en/pti/".$tocs[$i]."_tpeg.xml";
		copy($url, $dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml');
		$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml'));
		$output = eregi_replace("&loc3_", "", $output);
		write_header($dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml', $output);
		$url = $dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml';
		$parser = new Tpeg_Parser($url);
		if ($tocs[$i] == "tube")
			$parser->setType("tube");
		else
			$parser->setType("rail");
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
		else{
			$tocs[$i] = "na";
		}
	}
		
	$output = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>";
	$output .= "<traffic_data>";
	for ($i = 0; $i < count($tocs); $i++){
		if ($tocs[$i] != "na"){
			$filename = $dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml';
			$fgc	= file_get_contents($filename);
			$fgc_1 	= substr($fgc,0,2);
			if ( $fgc_1 != "<?" ){
				$fgc	= eregi_replace("<traffic_data>", "", $fgc);
				$fgc	= eregi_replace("</traffic_data>", "", $fgc);
				if (strlen($fgc) > 0)
					$output .= $fgc;
				else
					unlink($filename);
			}else
				unlink($filename);
		}
	}
	$output .= "</traffic_data>";
	$filename = $dir.'travel_data/railways.xml';
	write_header($filename, $output);
	unset($output);
//weather stuff
?>