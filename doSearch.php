<?php
	header('Content-type: text/xml'); 
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	require('includes/DB_Connection.php');
	require('/home/blueghos/db.php');
	$dbase   	= 'blueghos_bbc';
	$Database 	= new DB_Connection();
	$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
	
	if ($_REQUEST['mode'] == 'weather'){
		$sql = "SELECT `lat`,`lon`,`placename` FROM `weather_locs` WHERE `placename` REGEXP '^".strtolower($_REQUEST["query"])."' LIMIT 0, 10";
		$results = $Database->DB_search($sql);
		$res_text = "";
		while ( $res = $Database->DB_array($results) ){
			$res_text .="\t<result lat=\"".$res['lat']."\" lon=\"".$res['lon']."\" l_text=\"".$res['placename']."\" />\n";
		}
	}
	
	if ($_REQUEST['mode'] == 'traffic'){
		$sql = "SELECT * FROM `bbc_travel` WHERE `filename` REGEXP '^".strtolower($_REQUEST["query"])."' LIMIT 0, 10";
		$results = $Database->DB_search($sql);
		$res_text = "";
		while ( $res = $Database->DB_array($results) ){
			$res_text .="\t<result id=\"".$res['id']."\" fname=\"".strtolower($res['filename'])."\" lat=\"".$res['lat']."\" lon=\"".$res['lon']."\" l_text=\"".$res['display_name']."\" />\n";
		}
	}
	
	if ($_REQUEST['mode'] == 'rail'){
		$sql = "SELECT * FROM `ldb_stations` WHERE `name` LIKE '%".strtolower($_REQUEST["query"])."%' OR `code` LIKE '%".strtolower($_REQUEST["query"])."%' LIMIT 0, 10";
		$results = $Database->DB_search($sql);
		$res_text = "";
		while ( $res = $Database->DB_array($results) ){
			$res_text .="\t<result code=\"".$res['code']."\" lat=\"".$res['lat']."\" lon=\"".$res['lon']."\" l_text=\"".$res['name']."\" />\n";
		}
	}
	
?>
<results>
	<?php echo $res_text;?>
</results>