<?php
	function getXML($location){
		require('paths.php');
		$filename = $dir.'travel_data/'.$location.'.json';
		/*$output = "<traffic_data>";
		if ($location != "null"){
			$fgc	= file_get_contents($filename);
			$fgc_1 	= substr($fgc,0,2);
			if ( $fgc_1 != "<?" )
				$output .= $fgc;
			else
				unlink($filename);
		}
		$output .= "</traffic_data>";
		return $output;*/
		return file_get_contents($filename);
	}
	
	function getCounties(){
		//require('DB_Connection.php');
		//require('/home/blueghos/db.php');
		require('paths.php');
		$filename = $dir.'travel_data/locations.xml';
		return file_get_contents($filename);
	}
	
	//header('Content-type: text/xml'); 
	if ($_GET['area']){
		header('Content-type: application/x-json'); 
		echo getXML($_GET['area']);
	}else{
		header('Content-type: text/xml'); 
		echo getCounties();
	}

?>