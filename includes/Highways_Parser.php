<?php
	require('XML_Parser.php');
	class Highways_Parser extends XML_Parser{
		var $image;
 		var $javaOP;
  		var $combo;
  		var $base_url;
		
  		var $kml_output;
		var $geoRss_output;		
		var $jsonOutput = array();
		
  		function setBase($url){
    		$this->base_url = $url;
 		}
		
		function getJson(){
			return json_encode($this->jsonOutput);
		}

  		function parseFile(){
    		global $currentTag, $flag, $count, $channel, $items, $base_url, $DBC;
    		$currentTag = "";
   			$flag = "";
    		$count = 0;
    
    		// this is an associative array of channel data with keys ("title","link","description")
    		$channel = array();

    		// this is an array of arrays, with each array element representing an <item>
    		// each outer array element is itself an associative array
    		// with keys ("title", "link", "description")
    		$items = array();

    		// create parser
    		$xp = xml_parser_create();

    		// set element handler
    		xml_set_object($xp, &$this);
    		xml_set_element_handler($xp, "elementBegin", "elementEnd");
    		xml_set_character_data_handler($xp, "characterData");
    
    		// read XML file
    		if (!($fp = fopen($this->file, "r"))){
      			die("Could not read $file");
    		}
    
    		// parse data
    		while ($xml = fread($fp, 4096)){
      			if (!xml_parse($xp, $xml, feof($fp))){
	  				$this->output = "Error Getting data";
					return false;
      			}
    		}

    		// destroy parser
    		xml_parser_free($xp);

    		// now iterate through $items[] array
    		foreach ($items as $item){
				$start	= date("D jS M @ H:i"); //write
				$end	= date("D jS M @ H:i"); //write
				
				$thisJson = array();				
				
				$url = $item["link"];
				$thisJson["src"] 		= $url;
				$thisJson["summary"] 	= "<strong>".$item['title']."</strong><br />".$item['description'];
				// e.g. http://www.trafficengland.co.uk/map.aspx?isTrafficAlert=true&amp;lat=53.54509682935&amp;lon=-0.641269185902918
				$urlQuery = htmlspecialchars_decode(parse_url($url, PHP_URL_QUERY));
				parse_str($urlQuery, $path_parts);
				$thisJson["lat"] 		= $path_parts['lat'];
				$thisJson["lon"] 		= $path_parts['lon'];
				$thisJson["start"] 		= $start;
				$thisJson["stop"] 		= $end;
				$thisJson["severity"]	= 2; // TEMP
				$this->jsonOutput[] = $thisJson;				
    		}
    		if ($this->getOutput() == null)
      			return false;
    		else
      			return true;
  		}

  		// opening tag handler
  		function elementBegin($parser, $name, $attributes){
    		global $currentTag, $flag;
    		$currentTag = $name;
    		// set flag if entering <channel> or <item> block
    		if ($name == "ITEM"){
      			$flag = 1;
    		}else if ($name == "CHANNEL"){
      			$flag = 2;
    		}
  		}

  		function characterData($parser, $data){
    		global $currentTag, $flag, $items, $channel, $count;
    		$data = trim(htmlspecialchars($data));
    		if ($currentTag == "TITLE" || $currentTag == "LINK" ||$currentTag == "DESCRIPTION"){
      			// add data to $channels[] or $items[] array
      			if ($flag == 1){
					$items[$count][strtolower($currentTag)] .= $data;
      			}else if ($flag == 2){
					$channel[strtolower($currentTag)] .= $data;
      			}
    		}
    
    		if ($currentTag == "URL"){
      			$this->image = $data;
    		}
    
  		}

  		// closing tag handler
  		function elementEnd($parser, $name){
    		global $currentTag, $count, $flag;
    		$currentTag = "";
    
    		// set flag if exiting <channel> or <item> block
    		if ($name == "ITEM"){
      			$count++;
      			$flag = 0;
    		}else if ($name == "CHANNEL"){
      			$flag = 0;
    		}
  		}
	}
?>