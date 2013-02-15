<?php
	function getXML(){
		require('Tpeg_Parser.php');
		require('paths.php');
		$locs	= array();
		//$images	= array();
		
		include('locs.php');
		for ($i = 0; $i < count($locs); $i++){
			if ($locs[$i] != "motorways"){
				$filename = $dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml';
				if (!file_exists($filename)  || ( (filectime($filename) + (60*60)) < time() )){ /*60 mins interval*/
					$url = "http://www.bbc.co.uk/travelnews/tpeg/en/regions/rtm/".$locs[$i]."_tpeg.xml";
					copy($url, $dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml');
					$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml'));
					$output = eregi_replace("&loc3_", "", $output);
					write_header($dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml', $output);
					$url = $dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml';
					$parser = new Tpeg_Parser($url);
					$parser->setType("road");
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
					else
						$locs[$i] = "null";
				}
			}
		}
		$filename = $dir.'travel_data/motorways_tpeg_copy.xml';
		if (!file_exists($filename)  || ( (filectime($filename) + (60*60)) < time() )){ /*60 mins interval*/
			$url = "http://www.bbc.co.uk/travelnews/tpeg/en/local/rtm/motorways_tpeg.xml";
			copy($url, $dir.'travel_data/motorways_tpeg_copy.xml');
			$output = eregi_replace("&rtm31_", "", file_get_contents($dir.'travel_data/motorways_tpeg_copy.xml'));
			$output = eregi_replace("&loc3_", "", $output);
			write_header($dir.'travel_data/motorways_tpeg_copy.xml', $output);
			$url = $dir.'travel_data/motorways_tpeg_copy.xml';
			$parser = new Tpeg_Parser($url);
			$parser->setType("road");
			if (!$parser->parseFile()){
    				$err = true;
  			}
  			$result = $parser->getOutput();
  			if ($result == null){
    			$err = true;
  			}
			if (!$err)
				write_header($filename, $result);
			else
				$locs[$i] = "null";
		}
		
		$output = "<traffic_data>";
		for ($i = 0; $i < count($locs); $i++){
			if ($locs[$i] != "null"){
				$filename = $dir.'travel_data/'.$locs[$i].'_tpeg_copy.xml';
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