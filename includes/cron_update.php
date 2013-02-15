<?php
//allow longer execution for this
	ini_set('max_execution_time', 86400);
	
//include stuff
	require('DB_Connection.php');
	require('/home/blueghos/db.php');
	require('paths.php');
	require('io.php');
	require('Tpeg_Parser3.php');
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
	$geoRssFilename = $dir.'travel_data/locations.rss';
	
	// xml declaration
	$geoRssHeader = '<?xml version="1.0" encoding="ISO-8859-1"?>';
	// rdf declaration
	$geoRssHeader 	.= "\n<rdf:RDF"
					. "\n\txmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\""
					. "\n\txmlns=\"http://purl.org/rss/1.0/\""
 					. "\n\txmlns:georss=\"http://www.georss.org/georss\""
					. "\n\txmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n";
	
	$geoRssOutput 	= $geoRssHeader;
	// channel name declarartion	
	$geoRssOutput 	.= "\n<channel rdf:about=\"http://bbc.blueghost.co.uk\">"
					. "\n\t<title>BlueGhost UK Traffic Listings</title>"
					. "\n\t<link>http://bbc.blueghost.co.uk</link>"
					. "\n\t<description>supported by backstage.bbc.co.uk. More info at http://bbc.blueghost.co.uk/about_geoRss.html</description>"
					. "\n\t<dc:language>en-gb</dc:language>"
					. "\n</channel>\n";
	
	$output 	= "<traffic_data>";
	$sql 		= 'SELECT * FROM `bbc_travel` LIMIT 0, 1000';
	$wl 		= $Database->DB_search($sql);
	
	$jscript	= "<div id=\"tLinks\">\n";
	$jscript	.= "\n<a id=\"0\"href=\"javascript:parent.goToTraffic('motorways', '0', '0', 0, 'UK Motorways');\">Motorways</a>\n";
	
	while ( $w = $Database->DB_array($wl) )
	{
		$location 	= eregi_replace(" ", "", $w['filename']);
		
		// custom xml output
		$output 	.= "<t_alert lat=\"".$w['lat']."\" lon=\"".$w['lon']."\" location=\"".strtolower($location)."\" title =\"".$w['display_name']."\" id=\"".$w['id']."\" />\n";
		
		// getRss output
		$geoRssOutput	.= "\n\t<item>"
						."\n\t\t<title>".$w['display_name']."</title>"
						."\n\t\t<description>Click Link Below to View Traffic News for ".$w['display_name']."</description>"
						."\n\t\t<link>http://maps.google.co.uk/maps?q=http://bbc.blueghost.co.uk/trafficGeoRss.php?location=".strtolower($location)."</link>"
						."\n\t\t<georss:point>".$w['lat']." ".$w['lon']."</georss:point>"
						."\n\t</item>\n";
	}
	
	$sql 		= 'SELECT * FROM `bbc_travel` ORDER BY `display_name` ASC LIMIT 0, 1000';
	$wl 		= $Database->DB_search($sql);
	while ( $w = $Database->DB_array($wl) )
	{
		$jscript	.= "<a id=\"".$w['id']."\" href=\"javascript:parent.goToTraffic('".strtolower($location)."', ".$w['lat'].", ".$w['lon'].", ".$w['id'].", '".$w['display_name']."');\">".$w['display_name']."</a>\n";
	}
	
	$jscript	.= "</div>";
	$output 	.= "</traffic_data>";
	$geoRssOutput .= "\n</rdf:RDF>";
	
	// write the custom xml
	write_header($filename, $output);
	unset($output);
	
	// write the html used in old version of site
	$filename = $dir.'travel_data/locations.html';
	write_header($filename, $jscript);
	
	// write geoRss index
	write_header($geoRssFilename, $geoRssOutput);
	
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
		$parser = new Tpeg_Parser3($url);
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
		
		// custom xml
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
		
		// geoRss - surround with surroundings
		$geoRssFilename = $dir.'travel_data/'.$location.'_geoRss.rss';
		
		// get the standard geo rss header
		$geoRssOutput = $geoRssHeader;
		
		$geoRssOutput 	.= "\n<channel rdf:about=\"http://bbc.blueghost.co.uk\">"
					. "\n\t<title>BlueGhost UK Traffic Listings - ".$location."</title>"
					. "\n\t<link>http://bbc.blueghost.co.uk</link>"
					. "\n\t<description>supported by backstage.bbc.co.uk</description>"
					. "\n\t<dc:language>en-gb</dc:language>"
					. "\n</channel>\n";
					
		$geoRssOutput .= $parser->getGeoRss();
		
		$geoRssOutput .= "\n</rdf:RDF>";
				
		write_header($geoRssFilename, $geoRssOutput);
		unset($geoRssOutput);
		
		//  write the JSON data
		$jsonFileName = $dir.'travel_data/'.$location.'.json';
		$jsonOutput .= $parser->getJson();
		write_header($jsonFileName, $jsonOutput);
		unset($jsonOutput);
		
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
	$parser = new Tpeg_Parser3($url);
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
	$jsonFileName = $dir.'travel_data/'.$location.'.json';
	$jsonOutput .= $parser->getJson();
	write_header($jsonFileName, $jsonOutput);
	unset($jsonOutput);

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
		$parser = new Tpeg_Parser3($url);
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
	$parser = new Tpeg_Parser3($url);
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
			<href>http://bbc.blueghost.co.uk/images/rail.png</href>
			<w>64</w>
			<h>64</h>
		</Icon>
	</IconStyle>
</Style>
<Style id="arrivatrainswales">
	<IconStyle>
		<scale>0.75</scale>
		<Icon>
			<href>http://bbc.blueghost.co.uk/images/bbc/arrivatrainswales.jpg</href>
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
			<href>http://bbc.blueghost.co.uk/images/bbc/centraltrains.jpg</href>
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
			<href>http://bbc.blueghost.co.uk/images/bbc/virgintrains.jpg</href>
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
			<href>http://bbc.blueghost.co.uk/images/-1.png</href>
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
			<href>http://bbc.blueghost.co.uk/images/0.png</href>
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
			<href>http://bbc.blueghost.co.uk/images/1.png</href>
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
			<href>http://bbc.blueghost.co.uk/images/2.png</href>
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
			<href>http://bbc.blueghost.co.uk/images/3.png</href>
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
			<href>http://bbc.blueghost.co.uk/images/4.png</href>
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
			<href>http://bbc.blueghost.co.uk/images/5.png</href>
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
						<img src="http://bbc.blueghost.co.uk/images/bbc/virgintrains.jpg" alt="Virgin Logo" />
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
      		<href>http://bbc.blueghost.co.uk/images/bbc_v2.png</href>
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