<?php
	function getXML($location){
		require('paths.php');
		$filename = $dir.'news_data/'.$location.'.xml';
		if (!file_exists($filename)  || ( (filectime($filename) + (60*60)) < time() )){ /*60 mins interval*/
			$url = "http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/england/".$location."/rss.xml";
			copy($url, $filename);
			$parser = new Rdf_Parser($url);
			$parser->setType("road");
			$err = false;
			if (!$parser->parseFile()){
    			$err = true;
  			}
  			$result = $parser->getOutput();
  			if ($result == null){
    			$err = true;
  			}
			if (!$err){
				write_header($filename, $result);
				return $result;
			}
		}
		return file_get_contents($filename);
	}
		
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
	
	header('Content-type: text/xml'); 
	echo getXML($_GET['loc']);

?>