<?php

	function ReadXml($file)
	{
		require('includes/paths.php');
		$file = $dir.'travel_data/'.$file.'_tpeg_copy.xml';
		
		if (file_exists($file)) 
		{
			$xml = simplexml_load_file($file);
		 	echo $xml->asXML();
		   	foreach($xml->t_alert as $t_alert)
		   	{
		   		echo '<strong>Summary:</strong>&nbsp;'.$t_alert['summary'];
				echo '<br /><em>Start: '.$t_alert['start'].' -> End: '.$t_alert['stop'].'</em><br /><hr />';
		   	}
		} 
		else 
		{
		   	exit('Failed to open '.$file);
		}
	}
	
	function ShowHeader()
	{
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html><head>';
		echo '<title>BlueGhostUK Mobile Traffic</title></head><body>';
		echo '<strong id="top"><a href="mobile_traffic.php">BlueGhostUK Mobile Traffic</a></strong><br /><hr /><em>supported by <a href="http://backstage.bbc.co.uk">http://backstage.bbc.co.uk</a></em><br /><hr />';
	}
	
	function ShowFooter()
	{
		echo '<hr /><a href="#top">Top</a>';
		echo '<hr /><em>supported by <a href="http://backstage.bbc.co.uk">http://backstage.bbc.co.uk</a></em><br /><hr />';
		echo '</body></html>';
	}	
	
	function ShowListing()
	{
		require('includes/paths.php');
		$files = scandir($dir.'travel_data/');
		foreach($files as $file)
		{
			if ($file != "." && $file != "..")
			{
				$parseFile = ereg_replace("_tpeg_copy.xml", "", $file);
				echo '<a href="mobile_traffic.php?region='.$parseFile.'">'.$parseFile.'</a><br />';
			}
		}
	}	
	
	ShowHeader();
	if (!isset($_REQUEST['region']))
	{
		ShowListing();
	}
	else
	{
		ReadXml($_REQUEST['region']);
	}
	ShowFooter();
	
?>