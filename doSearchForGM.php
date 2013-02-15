<?php
	/**
	 * Script for greasemonkey addon
	 */
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
		/*not yet*/
		$res_text .="\t<result id=\"0\" fname=\"0\" lat=\"0\" lon=\"0\" l_text=\"Rail Searching not yet available\" />\n";
	}
	
?>
<results>
	<?php echo $res_text;?>
</results>