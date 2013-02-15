<?php
	header('Content-type: text/xml'); 
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	
	require('includes/DB_Connection.php');
	require('/home/blueghos/db.php');
	$dbase   	= 'blueghos_bbc';
	$Database 	= new DB_Connection();
	$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
	
	switch($_REQUEST["queryMode"])
	{
		case 'location':
			 if (!$_REQUEST["latMin"] || !$_REQUEST["latMax"] || !$_REQUEST["lonMin"] || !$_REQUEST["lonMax"])
			{
				die("No query sent");
			}
			$sql = "SELECT * FROM `ldb_stations` WHERE `lat` >= ".$_REQUEST["latMin"]." AND `lat` <= ".$_REQUEST["latMax"]." AND `lon` >= ".$_REQUEST["lonMin"]." AND `lon` <= ".$_REQUEST["lonMax"]." LIMIT 0, 1000";
			$results = $Database->DB_search($sql);
			$res_text = "";
			while ( $res = $Database->DB_array($results) )
			{
				$res_text .="\t<result code=\"".$res['code']."\" lat=\"".$res['lat']."\" lon=\"".$res['lon']."\" l_text=\"".$res['name']."\" />\n";
			}
			$url = 'queryMode=location&amp;latMin=' . $_REQUEST["latMin"] . '&amp;latMax=' . $_REQUEST["latMax"] . '&amp;lonMin=' . $_REQUEST["lonMin"] . '&amp;lonMax=' . $_REQUEST["lonMax"];
		break;
		case 'name':
		default:
			if (!$_REQUEST["query"])
				die("No query sent");
			$sql = "SELECT * FROM `ldb_stations` WHERE `name` LIKE '%".strtolower($_REQUEST["query"])."%' OR `code` LIKE '%".strtolower($_REQUEST["query"])."%' LIMIT 0, 25";
			$results = $Database->DB_search($sql);
			$res_text = "";
			while ( $res = $Database->DB_array($results) )
			{
				$res_text .="\t<result code=\"".$res['code']."\" lat=\"".$res['lat']."\" lon=\"".$res['lon']."\" l_text=\"".$res['name']."\" />\n";
			}
			$url = 'queryMode=name&amp;query=' . $_REQUEST["query"];
		break;
	}
	
?>
<rail_response>
	<disclamimer>
		<provider>Michael Pritchard (http://www.blueghost.co.uk)</provider>
		<original_source>Jonathan Brown - http://bbs.keyhole.com/ubb/showflat.php/Cat/0/Number/148131/page/0/fpart/3/vc/1</original_source>
	</disclamimer>
	<request_details>
		<original_url>http://bbc.blueghost.co.uk/railGeocoder.php?<?php echo $url;?></original_url>
		<remote_addr><?php echo $_SERVER['REMOTE_ADDR'];?></remote_addr>
		<referer><?php echo $_SERVER['HTTP_REFERER'];?></referer>
		<queryMode><?php echo $_REQUEST["queryMode"];?></queryMode>
	</request_details>
	<results>
		<?php echo $res_text;?>
	</results>
</rail_response>