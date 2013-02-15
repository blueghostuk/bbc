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
	
//db connection
	$dbase   	= 'blueghos_bbc';
	$Database 	= new DB_Connection();
	$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
	
//travel stuff
	//update locations xml list
	$filename 	= $dir.'travel_data/locations.xml';
	$output 	= "<traffic_data>";
	$sql 		= 'SELECT `lat`, `lon`, `filename`, `display_name` FROM `bbc_travel` ORDER BY `display_name` ASC LIMIT 0, 1000';
	$wl 		= $Database->DB_search($sql);
	$jscript	= "<html>
					<head>
						<link rel=\"stylesheet\" type=\"text/css\" href=\"../stylelist.css\" />
					</head>
					<body>
					<div id=\"tLinks\">\n";
	$jscript	.= "\n<a id=\"0\"href=\"javascript:parent.goToTraffic('motorways', '0', '0', 0, 'UK Motorways');\">Motorways</a>\n";
	$counter	= 1;
	while ( $w = $Database->DB_array($wl) ){
		$location 	= eregi_replace(" ", "", $w['filename']);
		$output 	.= "<t_alert lat=\"".$w['lat']."\" lon=\"".$w['lon']."\" location=\"".strtolower($location)."\" title =\"".$w['display_name']."\" />\n";
		$jscript	.= "<a id=\"".$counter."\" href=\"javascript:parent.goToTraffic('".strtolower($location)."', ".$w['lat'].", ".$w['lon'].", ".$counter.", '".$w['filename']."');\">".$w['display_name']."</a>\n";
		$counter++;
	}
	$jscript	.= "</div></body></html>";
	$output 	.= "</traffic_data>";
	write_header($filename, $output);
	unset($output);
	$filename = $dir.'travel_data/locations.html';
	write_header($filename, $jscript);
	
	//update each location xml
	$wl 	= $Database->DB_search($sql);
	while ( $w = $Database->DB_array($wl) ){
		$location = eregi_replace(" ", "", $w['filename']);
		$location = strtolower($location);
		$filename = $dir.'travel_data/'.$location.'_tpeg_copy.xml';
		//$url = "http://www.bbc.co.uk/travelnews/tpeg/en/local/rtm/".$location."_tpeg.xml";
		//copy($url, $dir.'travel_data/'.$location.'_tpeg_copy.xml');
		//$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/'.$location.'_tpeg_copy.xml'));
		//$output = eregi_replace("&loc3_", "", $output);
		//write_header($dir.'travel_data/'.$location.'_tpeg_copy.xml', $output);
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
		
//motorways
	$location = 'motorways';
	$filename = $dir.'travel_data/'.$location.'_tpeg_copy.xml';
	//$url = "http://www.bbc.co.uk/travelnews/tpeg/en/local/rtm/".$location."_tpeg.xml";
	//copy($url, $dir.'travel_data/'.$location.'_tpeg_copy.xml');
	//$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/'.$location.'_tpeg_copy.xml'));
	//$output = eregi_replace("&loc3_", "", $output);
	//write_header($dir.'travel_data/'.$location.'_tpeg_copy.xml', $output);
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
	//kml - surround with county/area
		$kml_travel .= "<Folder><name>Motorways</name>";
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
		//$url = "http://www.bbc.co.uk/travelnews/tpeg/en/pti/".$tocs[$i]."_tpeg.xml";
		//copy($url, $dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml');
		//$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml'));
		//$output = eregi_replace("&loc3_", "", $output);
		//write_header($dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml', $output);
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
		//kml rail
		$kml_rail .= $parser->getKML();
		if (!$err)
			write_header($filename, $result);
		else{
			$tocs[$i] = "na";
		}
	}
	
//add pti feed: http://www.bbc.co.uk/travelnews/xml/tpegml_en/pti.xml
//shows general delays
	$filename = $dir.'travel_data/pti_tpeg_copy.xml';
	//$url = "http://www.bbc.co.uk/travelnews/xml/tpegml_en/pti.xml";
	//copy($url, $dir.'travel_data/pti_tpeg_copy.xml');
	//$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/pti_tpeg_copy.xml'));
	//$output = eregi_replace("&loc3_", "", $output);
	//write_header($dir.'travel_data/pti_tpeg_copy.xml', $output);
	$url = $dir.'travel_data/pti_tpeg_copy.xml';
	$parser = new Tpeg_Parser($url);
	$parser->setType("train");
	$err = false;
	if (!$parser->parseFile()){
    	$err = true;
  	}
  	$result = $parser->getOutput();
  	if ($result == null){
    	$err = true;
  	}
	//kml rail
	$kml_trains = $parser->getKML();
	
	if (!$err)
		write_header($filename, $result);
	else{
		//$tocs[$i] = "na";
	}
	
//write all data to one file	
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

//write pti file to rail file
	$filename = $dir.'travel_data/pti_tpeg_copy.xml';
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
		
//finish rail file
	$output .= "</traffic_data>";
	$filename = $dir.'travel_data/railways.xml';
	write_header($filename, $output);
	unset($output);

//ldb data for kml
	$sql 	= 'SELECT `name`,`code`,`lat`,`lon` FROM `ldb_stations` ORDER BY `name` ASC LIMIT 0, 1000';
	$ldb 	= $Database->DB_search($sql);
	while ( $l = $Database->DB_array($ldb) ){
		$kml_ldb .= "\n<Placemark>\n\t<name>".$l['name']."</name>\n";
		$kml_ldb .= "\n\t<description><![CDATA[<a href=\"".$ldb_site.$l['code']."\" title=\"Go to this station\">Click here to see train running times</a>]]></description>\n";
		$kml_ldb .= "\n\t<visibility>1</visibility>";
		$kml_ldb .= "\n\t<Point>\n\t\t<coordinates>".$l['lon'].",".$l['lat'].",0</coordinates>\n\t</Point>";
		$kml_ldb .= "\n</Placemark>";
	}
	
//weather stuff - in cron_weather_*.php

//google earth kml
$kml_header = '<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://earth.google.com/kml/2.0">
<Folder>
	<name>Blueghost.co.uk Overlays</name>
	<description>
	<![CDATA[
    <p>Visit <a href="http://bbc.blueghost.co.uk/earth/">http://bbc.blueghost.co.uk/earth/</a> for more information</p>
    ]]>
	</description>';
//kml_weather
	$kml_weather = '<Folder>
		<name>Weather Data</name>
		<description>Not available yet</description>
	</Folder>
	';
//kml_rail
	$kml_rail = '<Folder>
		<name>Rail Data</name>
			<Folder>
				<name>General Delays</name>
					'.$kml_rail.'
			</Folder>
			<Folder>
				<name>Specific Train Delays</name>
					'.$kml_trains.'
			</Folder>
			<Folder>
				<name>Live Departure Boards</name>
				<Placemark>
					<description>
						<![CDATA[
						Want to add your own station, then follow the instructions on this page:<br />
						<a href="http://pritch.blueghost.co.uk/wordpress/uk-train-stations-locations/">http://pritch.blueghost.co.uk/wordpress/uk-train-stations-locations/</a>
						]]>
					</description>
					<name>Want to add your own train station?</name>
      				<visibility>0</visibility>
      				<open>0</open>
    			</Placemark>
				'.$kml_ldb.'
			</Folder>
		</Folder>';

//kml_travel
	$kml_travel = '<Folder>
		<name>Travel Data</name>
		'.$kml_travel.'
	</Folder>';
	
	$kml_footer = '<ScreenOverlay>
      	<name>BBC Backstage</name>
	  	<description>
    		<![CDATA[
   			<p>This Script is supported by <a href="http://backstage.bbc.co.uk">http://backstage.bbc.co.uk</a></p>
   			]]>
		</description>
   		<visibility>1</visibility>
   		<open>1</open>
   		<Icon>
      		<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/bbc_v2.png</href>
   		</Icon>
   		<color>ffffffff</color>
   		<drawOrder>0</drawOrder>
   		<overlayXY x="1" y="1" xunits="fraction" yunits="fraction"/>
   		<screenXY x="1" y="1" xunits="fraction" yunits="fraction"/>
   		<rotationXY x="0" y="0" xunits="fraction" yunits="fraction"/>
   		<size x="0" y="0" xunits="fraction" yunits="fraction"/>
   		<rotation>0</rotation>
	</ScreenOverlay>
</Folder>
</kml>';

//write kml	- don't anymore
//$filename = $dir.'earth/data.kml';
$kml_file = $kml_header.$kml_rail.$kml_travel.$kml_footer;
//write_header($filename, $kml_file);

//write kimz zip file
$zipfile = new zipfile(); 
$zipfile -> add_file($kml_file, "data.kml");
$filename = $dir.'earth/data.kmz';
write_header($filename, $zipfile -> file());

//write rail kmz
$kml_file = $kml_header.$kml_rail.$kml_footer;
$zipfile = new zipfile(); 
$zipfile -> add_file($kml_file, "rail.kml");
$filename = $dir.'earth/rail.kmz';
write_header($filename, $zipfile -> file());

//write rail kmz
$kml_file = $kml_header.$kml_travel.$kml_footer;
$zipfile = new zipfile(); 
$zipfile -> add_file($kml_file, "travel.kml");
$filename = $dir.'earth/travel.kmz';
write_header($filename, $zipfile -> file());
?>