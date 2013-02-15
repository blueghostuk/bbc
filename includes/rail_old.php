<?php

	function getXML(){
		$rail = true;
		require('Tpeg_Parser.php');
		require('paths.php');
		$tocs	= array();
		//$images	= array();
		
		include('tocs.php');
		
		for ($i = 0; $i < count($tocs); $i++){
			$filename = $dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml';
			if (!file_exists($filename)  || ( (filectime($filename) + (30*60)) < time() )){ /*30 mins interval*/
				$url = "http://www.bbc.co.uk/travelnews/tpeg/en/pti/".$tocs[$i]."_tpeg.xml";
				copy($url, $dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml');
				$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml'));
				$output = eregi_replace("&loc3_", "", $output);
				write_header($dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml', $output);
				$url = $dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml';
				$parser = new Tpeg_Parser($url);
				if ($tocs[$i] == "tube")
					$parser->setType("tube");
				else
					$parser->setType("rail");
				$err = false;
				if (!$parser->parseFile()){
    				$err = true;
  				}
  				$result = $parser->getOutput();
  				if ($result == null){
    				$err = true;
  				}
				if (!$err)
					write_header($filename, $result);
				else{
					$tocs[$i] = "na";
				}
			}
		}
		$output = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>";
		$output .= "<traffic_data>";
		for ($i = 0; $i < count($tocs); $i++){
			if ($tocs[$i] != "na"){
				$filename = $dir.'travel_data/'.$tocs[$i].'_tpeg_copy.xml';
				$fgc	= file_get_contents($filename);
				$fgc_1 	= substr($fgc,0,2);
				if ( $fgc_1 != "<?" )
					$output .= $fgc;
				else
					unlink($filename);
			}
		}
		$output .= "</traffic_data>";
		return $output;
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
	echo getXML();

?>