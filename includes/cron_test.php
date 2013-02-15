<?php
//allow longer execution for this
	ini_set('max_execution_time', 86400);
	
//include stuff
	require('DB_Connection.php');
	require('/home/blueghos/db.php');
	require('paths.php');
	require('io.php');
	require('Tpeg_Parser.php');
	require('zip.php');

//echo'<h1>Included Files</h1>';
	
//db connection
	$dbase   	= 'blueghos_bbc';
	$Database 	= new DB_Connection();
	$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
//echo'<h1>DB CONNECTTION</h1>';

//travel stuff
	//update locations xml list
	$filename 	= $dir.'travel_data/locations.xml';
	$output 	= "<traffic_data>";
	$sql 		= 'SELECT * FROM `bbc_travel` LIMIT 0, 1000';
	$wl 		= $Database->DB_search($sql);
	/*$jscript	= "<html>
					<head>
						<link rel=\"stylesheet\" type=\"text/css\" href=\"../stylelist.css\" />
					</head>
					<body>
					<div id=\"tLinks\">\n";*/
	$jscript	= "<div id=\"tLinks\">\n";
	$jscript	.= "\n<a id=\"0\"href=\"javascript:parent.goToTraffic('motorways', '0', '0', 0, 'UK Motorways');\">Motorways</a>\n";
	while ( $w = $Database->DB_array($wl) ){
		$location 	= eregi_replace(" ", "", $w['filename']);
		$output 	.= "<t_alert lat=\"".$w['lat']."\" lon=\"".$w['lon']."\" location=\"".strtolower($location)."\" title =\"".$w['display_name']."\" id=\"".$w['id']."\" />\n";
	}
	$sql 		= 'SELECT * FROM `bbc_travel` ORDER BY `display_name` ASC LIMIT 0, 1000';
	$wl 		= $Database->DB_search($sql);
	while ( $w = $Database->DB_array($wl) ){
		$jscript	.= "<a id=\"".$w['id']."\" href=\"javascript:parent.goToTraffic('".strtolower($location)."', ".$w['lat'].", ".$w['lon'].", ".$w['id'].", '".$w['display_name']."');\">".$w['display_name']."</a>\n";
	}
	//$jscript	.= "</div></body></html>";
	$jscript	.= "</div>";
	$output 	.= "</traffic_data>";
	write_header($filename, $output);
	unset($output);
	$filename = $dir.'travel_data/locations.html';
	write_header($filename, $jscript);
	
	//echo'<h1>Travel Stuff</h1>';

	//update each location xml
	$wl 	= $Database->DB_search($sql);
	while ( $w = $Database->DB_array($wl) ){
		$location = eregi_replace(" ", "", $w['filename']);
		$location = strtolower($location);
		$filename = $dir.'travel_data/'.$location.'_tpeg_copy.xml';
		$url = "http://www.bbc.co.uk/travelnews/tpeg/en/local/rtm/".$location."_tpeg.xml";
		if (!copy($url, $dir.'travel_data/'.$location.'_tpeg_copy.xml'))
		{
			echo 'failed to copy '.$url.' to  '.$dir.'travel_data/'.$location.'_tpeg_copy.xm';
			die();
		}
		$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/'.$location.'_tpeg_copy.xml'));
		$output = eregi_replace("&loc3_", "", $output);
		write_header($dir.'travel_data/'.$location.'_tpeg_copy.xml', $output);
		$url = $dir.'travel_data/'.$location.'_tpeg_copy.xml';
		echo'<h4>'.$url.'</h4>';
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
		
		//kml - surround with county/area
		$kml_travel .= "<Folder><name>".$w['display_name']."</name>";
		$kml_travel .= $parser->getKML();
		$kml_travel .= "</Folder>";
		
		if (!$err)
			write_header($filename, $result);
		else
			$location = "null";
		$output = "<traffic_data>";
		if ($location != "null"){
			$fgc	= file_get_contents($filename);
			$fgc_1 	= substr($fgc,0,2);
			if ( $fgc_1 != "<?" ){
				$output 	.= $fgc;
			}else
				unlink($filename);
		}
		$output .= "</traffic_data>";
		unset($output);
	}//loop to next county
	?>