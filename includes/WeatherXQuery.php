<?php
	require('paths.php');
	$xml_file = $dir . 'weather_data/uk.opml';
	
	$dom = new DomDocument();
	$dom->load($xml_file);
	
	$xp = new domxpath($dom);
	$titles = $xp->query(".//outline[@text='Telford']/@url");
	foreach ($titles as $node) {
    	print $node->textContent . "\n";
	}  

?>