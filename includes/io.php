<?php
function write_header($file,$text)
{
   	if (!$handle = fopen($file, 'w')) 
	{
   		echo "Cannot Open File ($file)";
		exit;
   	}

   	if (fwrite($handle, $text) === FALSE) 
	{
    	echo "Cannot write to file ($file)";
      	exit;
   	}

   	//echo "Success, wrote ($text) to file ($file)<br />";

   	fclose($handle);
}

function getFile($url, $filename)
{
	if (!file_exists($filename)  || ( (filectime($filename) + (30*60)) < time() ))
	{/*30 mins interval*/
		$output = "";
		$parser = new RDF_Parser2($url);
		if (!$parser->parseFile()){
			$output="No News";
  		}
  		$result = $parser->getOutput();
  		if ($result == null){
    		$output = "No News";
  		}else{
			$output = $result;
		}
		write_header($filename, $output);
		return $output;
	}
	return file_get_contents($filename);
}
?>