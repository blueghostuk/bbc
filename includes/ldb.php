<?php

	function getLDB($loc){
		require('paths.php');
		$filename = $dir.'ldb_data/'.$loc.'.html';
		//if (!file_exists($filename)  || ( (filectime($filename) + (5*60)) < time() )){/*5 mins interval*/
			$url 	= "http://www.livedepartureboards.co.uk/ldb/summary.aspx?T=".$loc;
			//echo file_get_contents($url);
			$output = file_get_contents($url);
			$tblA 	= preg_split ("/<table.*?>/", $output);
			for($x=0; $x<count($tblA); $x++){
				echo "<h1>".$x."</h1>";
				echo $tblA[$x];
    			/*if(strstr($tblA[$x],"id=\"LDBboard\"")){
        			$resultTable = $x;
					$x	= count($tblA);
    			}*/
			}
			//echo $tblA[$resultTable];
			//write_header($filename, $tblA[$resultTable]);
		//}
		//echo file_get_contents($filename);
	}
	
	header('Content-type: text/html'); 
	if ($_GET['ldb'])
		echo getLDB($_GET['ldb']);
?>