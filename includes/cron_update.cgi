#!/usr/bin/php -c /home/blueghos/public_html/bbc/includes/php.ini

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
		copy($url, $dir.'travel_data/'.$location.'_tpeg_copy.xml');
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
	
//echo'<h1>Travel Stuff</h1>';
		
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

//echo'<h1>Motorways</h1>';
	
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
		else{
			$parser->setType("rail");
			$parser->setIcon($tocs[$i]);
		}
		
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
	
//echo'<h1>Railways</h1>';
	
//add pti feed: http://www.bbc.co.uk/travelnews/xml/tpegml_en/pti.xml
//shows general delays
	$filename = $dir.'travel_data/pti_tpeg_copy.xml';
	$url = "http://www.bbc.co.uk/travelnews/tpeg/en/pti/pti_tpeg.xml";
	copy($url, $dir.'travel_data/pti_tpeg_copy.xml');
	$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/pti_tpeg_copy.xml'));
	$output = eregi_replace("&loc3_", "", $output);
	write_header($dir.'travel_data/pti_tpeg_copy.xml', $output);
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
//echo'<h1>Rail Complete</h1>';

//ldb data for kml
	$sql 	= 'SELECT `name`,`code`,`lat`,`lon` FROM `ldb_stations` ORDER BY `name` ASC LIMIT 0, 3000';
	$ldb 	= $Database->DB_search($sql);
	while ( $l = $Database->DB_array($ldb) ){
		$kml_ldb .= "\n<Placemark>\n\t<name>".$l['name']."</name>\n";
		$kml_ldb .= "\n\t<description><![CDATA[<a href=\"".$ldb_site.$l['code']."\" title=\"Go to this station\">Click here to see train running times</a>]]></description>\n";
		$kml_ldb .= "\n\t<visibility>0</visibility>";
		$kml_ldb .= "\n\t<Point>\n\t\t<coordinates>".$l['lon'].",".$l['lat'].",0</coordinates>\n\t</Point>";
		$kml_ldb .= "\n\t<styleUrl>#railStation</styleUrl>";
		$kml_ldb .= "\n</Placemark>";
	}
//echo'<h1>LDB Data</h1>';
	
//weather stuff - in cron_weather_*.php


//google earth kml
//styles
$kml_styles = '
<Style id="railStation">
	<IconStyle>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/rail.png</href>
			<w>64</w>
			<h>64</h>
		</Icon>
	</IconStyle>
</Style>
<Style id="arrivatrainswales">
	<IconStyle>
		<scale>0.75</scale>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/bbc/arrivatrainswales.jpg</href>
			<w>100</w>
			<h>32</h>
		</Icon>
	</IconStyle>
	<LineStyle>
   		<color>ff898700</color>
   		<width>4</width>
	</LineStyle>
</Style>
<Style id="centraltrains">
	<IconStyle>
		<scale>0.75</scale>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/bbc/centraltrains.jpg</href>
			<w>100</w>
			<h>34</h>
		</Icon>
	</IconStyle>
	<LineStyle>
   		<color>ff1B9D50</color>
   		<width>4</width>
	</LineStyle>
</Style>
<Style id="virgintrains">
	<IconStyle>
		<scale>0.75</scale>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/bbc/virgintrains.jpg</href>
			<w>100</w>
			<h>34</h>
		</Icon>
	</IconStyle>
	<LineStyle>
   		<color>ff0000cc</color>
   		<width>4</width>
	</LineStyle>
</Style>
<Style id="severity-1">
	<IconStyle>
		<scale>0.75</scale>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/-1.png</href>
			<w>19</w>
			<h>40</h>
		</Icon>
	</IconStyle>
	<LineStyle>
   		<color>ffcccccc</color>
   		<width>4</width>
	</LineStyle>
</Style>
<Style id="severity0">
	<IconStyle>
		<scale>0.75</scale>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/0.png</href>
			<w>19</w>
			<h>40</h>
		</Icon>
	</IconStyle>
	<LineStyle>
   		<color>ff999999</color>
   		<width>4</width>
	</LineStyle>
</Style>
<Style id="severity1">
	<IconStyle>
		<scale>0.75</scale>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/1.png</href>
			<w>19</w>
			<h>40</h>
		</Icon>
	</IconStyle>
	<LineStyle>
   		<color>ff66cc99</color>
   		<width>4</width>
	</LineStyle>
</Style>
<Style id="severity2">
	<IconStyle>
		<scale>0.75</scale>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/2.png</href>
			<w>19</w>
			<h>40</h>
		</Icon>
	</IconStyle>
	<LineStyle>
   		<color>ff00ff00</color>
   		<width>4</width>
	</LineStyle>
</Style>
<Style id="severity3">
	<IconStyle>
		<scale>0.75</scale>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/3.png</href>
			<w>19</w>
			<h>40</h>
		</Icon>
	</IconStyle>
	<LineStyle>
   		<color>ff00ffff</color>
   		<width>4</width>
	</LineStyle>
</Style>
<Style id="severity4">
	<IconStyle>
		<scale>0.75</scale>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/4.png</href>
			<w>19</w>
			<h>40</h>
		</Icon>
	</IconStyle>
	<LineStyle>
   		<color>ff0000ff</color>
   		<width>4</width>
	</LineStyle>
</Style>
<Style id="severity5">
	<IconStyle>
		<scale>0.75</scale>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/5.png</href>
			<w>19</w>
			<h>40</h>
		</Icon>
	</IconStyle>
	<LineStyle>
   		<color>ffffffff</color>
   		<width>4</width>
	</LineStyle>
</Style>
';
$kml_header = '<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://earth.google.com/kml/2.0">
<Document>
'.$kml_styles.'
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
	$kml_rail = '
		<Folder>
			<name>Rail Data</name>
			<Folder>
				<name>General Delays</name>
					'.$kml_rail.'
			</Folder>
			<Folder>
				<name>Specific Train Delays</name>
					'.$kml_trains.'
			</Folder>
		</Folder>';
	
	$kml_stations='<Folder>
				<name>Live Departure Boards</name>
				<Placemark>
					<description>
						<![CDATA[
						<img src="http://www.blueghost.pwp.blueyonder.co.uk/bg_site/bbc/virgintrains.jpg" alt="Virgin Logo" />
						Thanks to Virgin Trains for keeping the old ad-free and easy to understand version of the LDB Tables<br />
						<a href="http://www.virgintrains.co.uk/">Virgin Trains</a>
						]]>
					</description>
					<name>Thanks Virgin Trains</name>
      				<visibility>0</visibility>
      				<open>0</open>
    			</Placemark>
				<Placemark>
					<description>
						<![CDATA[
						Thanks to Jonathan Brown for the list of all UK train stations<br />
						<a href="http://bbs.keyhole.com/ubb/showflat.php/Cat/0/Number/148131/page/0/fpart/3/vc/1">See the Google Earth Forum for more</a>
						]]>
					</description>
					<name>Thanks Jonathan Brown</name>
      				<visibility>0</visibility>
      				<open>0</open>
    			</Placemark>
				'.$kml_ldb.'
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
</Document>
</kml>';

//write kml	- don't anymore
//$filename = $dir.'earth/data.kml';
$kml_file = $kml_header.$kml_rail.$kml_travel.$kml_footer;
//write_header($filename, $kml_file);

//write complete kmz zip file
$zipfile = new zipfile(); 
$zipfile -> add_file($kml_file, "data.kml");
$filename = $dir.'earth/data.kmz';
write_header($filename, $zipfile -> file());
//echo'<h1>Earth KML</h1>';

//write rail kmz
$kml_file = $kml_header.$kml_rail.$kml_footer;
$zipfile = new zipfile(); 
$zipfile -> add_file($kml_file, "rail.kml");
$filename = $dir.'earth/rail.kmz';
write_header($filename, $zipfile -> file());
//echo'<h1>Rail KML</h1>';

$kml_file = $kml_header.$kml_stations.$kml_footer;
$zipfile = new zipfile(); 
$zipfile -> add_file($kml_file, "stations.kml");
$filename = $dir.'earth/stations.kmz';
write_header($filename, $zipfile -> file());
//echo'<h1>Live Departures KML</h1>';

//write rail kmz
$kml_file = $kml_header.$kml_travel.$kml_footer;
$zipfile = new zipfile(); 
$zipfile -> add_file($kml_file, "travel.kml");
$filename = $dir.'earth/travel.kmz';
write_header($filename, $zipfile -> file());
//echo'<h1>Travel KML</h1>';
?>