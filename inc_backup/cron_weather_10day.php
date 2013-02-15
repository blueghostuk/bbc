<?php
//allow longer execution for this
	ini_set('max_execution_time', 86400);
//include stuff
	require('DB_Connection.php');
	require('/home/blueghos/db.php');
	require('paths.php');
	require('Weather_Parser2.php');
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
//weather stuff
	//update each loc's cc
	$sql 	= 'SELECT `id` FROM `weather_locs` LIMIT 0, 1000';
	$wl 	= $Database->DB_search($sql);
	while ( $w = $Database->DB_array($wl) ){
		$url = 'http://xoap.weather.com/weather/local/'
				. $w['id']
				.'?prod=xoap&par='
				.$partnerID
				.'&key='
				.$keyID
				.'&dayf=10'
				.'&unit=m'
				.'&link=xoap';
		$filename = $dir.'weather_data/'.$w['id'].'_days.xml';
		$parser = new Weather_Parser2($url);
		if (!$parser->parseFile()){
    		echo"No Weather";
  		}
  		$result = $parser->getOutput();
  		if ($result == null){
    		echo "No News";
  		}
		write_header($dir.'weather_data/'.$w['id'].'_days.xml', $result);
		//echo "<h4>DONE ".$w['id']."</h4>";
	}
?>