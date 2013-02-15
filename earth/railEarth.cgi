#!/usr/bin/php -c /home/blueghos/public_html/bbc/includes/php.ini
<?php
	require('../includes/DB_Connection.php');
	require('../includes/paths.php');
	require('/home/blueghos/db.php');
	//db connection
	$dbase   	= 'blueghos_bbc';
	$Database 	= new DB_Connection();
	$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
	
	$coords = preg_split('/,|\s/', $_REQUEST['BBOX']);
	$minLon = $coords[0];
	$minLat = $coords[1];
	$maxLon = $coords[2];
	$maxLat = $coords[3];
	
	$sql = "SELECT * FROM `ldb_stations` WHERE `lat` >= ".$minLat." AND `lat` <= ".$maxLat." AND `lon` >= ".$minLon." AND `lon` <= ".$maxLon." LIMIT 0, 1000";
	$ldb 	= $Database->DB_search($sql);
	$num 	= $Database->DB_num_results($ldb);
	echo '<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://earth.google.com/kml/2.0">
<Document>';
	echo '<Style id="railStation">
	<IconStyle>
		<Icon>
			<href>http://www.blueghost.pwp.blueyonder.co.uk/bg_site/rail.png</href>
			<w>64</w>
			<h>64</h>
		</Icon>
	</IconStyle>
</Style>';
	if ($num > 100)
	{
		$userlon = (($coords[2] - $coords[0])/2) + $coords[0];
		$userlat = (($coords[3] - $coords[1])/2) + $coords[1];
		$kml_ldb .= "\n<Placemark>\n\t<name>Too Many Results, Zoom in</name>\n";
		$kml_ldb .= "\n\t<visibility>1</visibility>";
		$kml_ldb .= "\n\t<Point>\n\t\t<coordinates>" . $userlon . "," . $userlat . ",0</coordinates>\n\t</Point>";
		$kml_ldb .= "\n\t<styleUrl>#railStation</styleUrl>";
		$kml_ldb .= "\n</Placemark>";
	}
	else
	{
		while ( $l = $Database->DB_array($ldb) ){
			$kml_ldb .= "\n<Placemark>\n\t<name>".$l['name']."</name>\n";
			$kml_ldb .= "\n\t<description><![CDATA[<ul><li><a href=\"".$ldb_site.$l['code']."\" title=\"Go to this station Live Arrivals &amp; Departures Page\">Click here to see train running times</a></li><li><a href=\"http://www.nationalrail.co.uk/stations/index.html?a=findStation&station_query=".$l['code']."\" title=\"See this stations facilities\">Station Facilities</a></li><li><a href=\"http://traintaxi.nationalrail.co.uk/?crs=".$l['code']."\" title=\"Local Taxi Firms\">Local Taxis</a></li></ul>]]></description>\n";
			$kml_ldb .= "\n\t<visibility>1</visibility>";
			$kml_ldb .= "\n\t<Point>\n\t\t<coordinates>".$l['lon'].",".$l['lat'].",0</coordinates>\n\t</Point>";
			$kml_ldb .= "\n\t<styleUrl>#railStation</styleUrl>";
			$kml_ldb .= "\n</Placemark>";
		}
	}
	echo $kml_ldb;
	echo '</Document>
</kml>';
?>