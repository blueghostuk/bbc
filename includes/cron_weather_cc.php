<?php
//allow longer execution for this
	ini_set('max_execution_time', 86400);
//include stuff
	require('DB_Connection.php');
	require('/home/blueghos/db.php');
	require('io.php');
	require('paths.php');
	require('Weather_Parser3.php');
//db connection
	$dbase   	= 'blueghos_bbc';
	$Database 	= new DB_Connection();
	$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
//weather stuff
	//update each loc's cc
	$sql 	= 'SELECT `id` FROM `weather_locs` LIMIT 0, 1000';
	$wl 	= $Database->DB_search($sql);
	while ( $w = $Database->DB_array($wl) ){
		$url = 'http://xoap.weather.com/weather/local/'
				. $w['id']
				.'?cc=*&prod=xoap&par='
				.$partnerID
				.'&key='
				.$keyID
				.'&unit=m'
				.'&link=xoap';
		$filename = $dir.'weather_data/'.$w['id'].'.xml';
		$parser = new Weather_Parser3($url);
		if (!$parser->parseFile()){
    		echo"No Weather";
  		}
  		$result = $parser->getOutput();
  		if ($result == null){
    		echo "No News";
  		}
		write_header($dir.'weather_data/'.$w['id'].'.xml', $result);
		write_header($dir.'weather_data/'.$w['id'].'.json', $parser->getJson());
		echo 'Wrote to '.$filename.'<br />';
	}
	
	$files_dir = $dir.'weather_data';
	// delete files older than 5 days old
		$timestamp = time() - ( 5 * 24 * 60 * 60 );
		$handle = opendir($files_dir);
		while (false !== ($file = readdir($handle))) 
		{
			if ($file != "." && $file != "..") 
			{
				if (filemtime($files_dir.'/'.$file) < $timestamp)
				{
					echo ' Deleted '.$files_dir.'/'.$file;
					unlink($files_dir.'/'.$file);
				}
				else
				{
					echo 'DIDNT delete '.$files_dir.'/'.$file;
				}
			}
		}
		closedir($handle);
?>