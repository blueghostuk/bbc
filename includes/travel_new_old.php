<?php
	function getXML($location){
		require('Tpeg_Parser.php');
		require('paths.php');
		$filename = $dir.'travel_data/'.$location.'_tpeg_copy.xml';
		if (!file_exists($filename)  || ( (filectime($filename) + (60*60)) < time() )){ /*60 mins interval*/
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
		}
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
	
	function getCounties(){
		require('DB_Connection.php');
		require('/home/blueghos/db.php');
		require('paths.php');
		$filename = $dir.'travel_data/locations.xml';
		if (!file_exists($filename)  || ( (filectime($filename) + (60*60)) < time() )){
			$output 	= "<traffic_data>";
			$dbase   	= 'blueghos_bbc';
			$Database 	= new DB_Connection();
			$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
			$sql 	= 'SELECT `lat`, `lon`, `filename` FROM `bbc_travel` LIMIT 0, 1000';
			$wl 	= $Database->DB_search($sql);
			while ( $w = $Database->DB_array($wl) ){
				$location = eregi_replace(" ", "", $w['filename']);
				$output .= "<t_alert lat=\"".$w['lat']."\" lon=\"".$w['lon']."\" location=\"".strtolower($location)."\" title =\"".$w['filename']."\" />\n";
			}
			$output .= "</traffic_data>";
			write_header($filename, $output);
			return $output;
		}else{
			return file_get_contents($filename);
		}
	}
	
	header('Content-type: text/xml'); 
	if ($_GET['area'])
		echo getXML($_GET['area']);
	else
		echo getCounties();

?>