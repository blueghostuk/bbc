<?php
	require('paths.php');
	$filename = $dir.'weather_data/';
	
	function getDirFiles($dirPath){
		if ($handle = opendir($dirPath)){
    		while (false !== ($file = readdir($handle))){
        		if ($file != "." && $file != "..")
            		$filesArr[] = trim($file);   
			}    
			closedir($handle);
		} 
    	return $filesArr;   
   	}
	
	$files 		= getDirFiles($filename);
	//$days_xml	= array();
	//$cc_xml		= array();
	$s_days		= array();
	$s_cc		= array();
	foreach ($files as $file){
		if (eregi("search", $file)){
			if (eregi("_days", $file))
				$s_days[]	= $file;
			else
				$s_cc[]		= $file;
		}/*elseif(eregi("_days", $file))
			$days_xml[]		= $file;
		else
			$cc_xml[]		= $file;*/
	}
	
	/*echo "<h1>Search Days Files:".count($s_days)."</h1>";
	$i = 0;*/
	foreach($s_days as $file){
		if ((filectime($filename.$file) + (12*30*60)) < time() ){/*CHECK SEARCH CACHE*/
			unlink($filename.$file);
			/*echo "<p>".$file."</p>";
			$i++;*/
		}
	}
	/*echo "<h1>Search Days Files OUT OF DATE".$i."</h1>";
	$i = 0;
	echo "<h1>Search Files:".count($s_cc)."</h1>";*/
	foreach($s_cc as $file){
		if ((filectime($filename.$file) + (30*60)) < time() ){/*CHECK SEARCH CACHE*/
			unlink($filename.$file);
			/*echo "<p>".$file."</p>";
			$i++;*/
		}
	}
	/*echo "<h1>Search Files OUT OF DATE".$i."</h1>";
	$i = 0;
	echo "<h1>Days Files:".count($days_xml)."</h1>";
	foreach($days_xml as $file){
		if ((filectime($filename.$file) + (12*30*60)) < time() ){/*CHECK SEARCH CACHE*/
			/*echo "<p>".$file."</p>";
			$i++;
		}
	}
	echo "<h1>Days Files OUT OF DATE".$i."</h1>";
	$i = 0;
	echo "<h1>CC Files:".count($cc_xml)."</h1>";
	foreach($cc_xml as $file){
		if ((filectime($filename.$file) + (30*60)) < time() ){/*CHECK SEARCH CACHE*/
			/*echo "<p>".$file."</p>";
			$i++;
		}
	}
	echo "<h1>CC Files OUT OF DATE".$i."</h1>";
	$i = 0;*/
?>