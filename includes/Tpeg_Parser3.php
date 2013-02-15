<?php
	require('XML_Parser.php');
	require('paths.php');
	class Tpeg_Parser3 extends XML_Parser{
		var $current;

		var $alerts;
		var	$tpeg;
		var $origin;
		var $summary;
		var $loc;
		var $loc_c;
		var $rtm;
		var $type;
		var $icon;
		
		var $kml_output;
		var $geoRss_output;
		
		var $jsonOutput = array();
		
		function getJson(){
			return json_encode($this->jsonOutput);
		}

		function setType($tp){
			$this->type = $tp;
		}
		
		function setIcon($ic){
			$this->icon = $ic;
		}
		
		function getKML(){
			return $this->kml_output;
		}
		
		function getGeoRss(){
			return $this->geoRss_output;
		}

		function parseFile(){
    		global $count, $channel, $alerts, $DBC, $current, $loc_c, $type;
    		$flag 		= "";
    		$count 		= 0;
			$loc_c		= 0;
    
    		$channel = array();

    		$alerts = array();
			$current = array();
	
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
					//die("XML parser error: " .xml_error_string(xml_get_error_code($xp)));
      			}
    		}

    		// destroy parser
    		xml_parser_free($xp);
		
			$this->output.= "<traffic_data>";
	
			for ($i=0; $i < $count; $i++){
				$start 	= eregi_replace("T", " ", $alerts[$i][0]["start"]); //remove T with space
				$start	= substr($start, 0 , (strlen($start)-2)); //remove +0
				$start	= strtotime($start); //convert
				$start	= date("D jS M @ H:i", $start); //write
		
				$end 	= eregi_replace("T", " ", $alerts[$i][0]["stop"]); //remove T with space
				$end	= substr($end, 0 , (strlen($end)-2)); //remove +0
				$end	= strtotime($end); //convert
				$end	= date("D jS M @ H:i", $end); //write
				
				$img_inc = '';
				$thisJson = array();
				if (isset($this->icon)){
					$thisJson["operatorImage"] = "http://bbc.blueghost.co.uk/images/bbc/'.$this->icon.'.jpg";
					$img_inc = '<img src="http://bbc.blueghost.co.uk/images/bbc/'.$this->icon.'.jpg" alt="Operator Image" />';
				}
				
				$this->output.= "<t_alert src=\"".$alerts[$i][0]["provider"]."\" summary=\"".$alerts[$i][0]["summary"]."\" lat=\"".$alerts[$i][0]["lat"]."\" lon=\"".$alerts[$i][0]["lon"]."\" start=\"".$start."\" stop=\"".$end."\"";
				$thisJson["src"] 		= $alerts[$i][0]["provider"];
				$thisJson["summary"] 	= $alerts[$i][0]["summary"] ;
				$thisJson["lat"] 		= $alerts[$i][0]["lat"];
				$thisJson["lon"] 		= $alerts[$i][0]["lon"] ;
				$thisJson["start"] 		= $start;
				$thisJson["stop"] 		= $end;
				
				//get second location if there
				if (count($alerts[$i]) > 1){
					$this->output.= " sec_lat=\"".$alerts[$i][1]["lat"]."\" sec_lon=\"".$alerts[$i][1]["lon"]."\"";
					$thisJson["secondLocation"] = array("lat" => $alerts[$i][1]["lat"], "lon" => $alerts[$i][1]["lon"]);
				}
				$sl = strlen($alerts[$i][0]["sf"]);
				if ($sl >0){
					$this->output.= " severity=\"".substr($alerts[$i][0]["sf"], 0, ($sl-1)) ."\"";
					$thisJson["severity"] 		= substr($alerts[$i][0]["sf"], 0, ($sl-1));
				}else{
					$this->output.= " severity=\"-1\"";
					$thisJson["severity"] 		= "-1";
				}
				$this->output.= " type=\"".$this->type."\" />";
				$thisJson["type"] 		= $this->type;
				$this->jsonOutput[] = $thisJson;
				
				// geo Rss stuff
				$this->geoRss_output 	.= "\n\t<item>"
										."\n\t\t<title>".substr($alerts[$i][0]["summary"],0, 20)."</title>"
										."\n\t\t<description>".$alerts[$i][0]["summary"]."</description>"
										."\n\t\t<link>http://bbc.blueghost.co.uk/</link>";
				// check for 2 points, if so use the second as an endpoint
				if (count($alerts[$i]) > 1)
				{
					$this->geoRss_output.="\n\t\t<georss:line>".$alerts[$i][0]["lat"]." ".$alerts[$i][0]["lon"]." ".$alerts[$i][1]["lat"]." ".$alerts[$i][1]["lon"]."</georss:line>"
										."\n\t</item>\n";
				}
				else
				{
					$this->geoRss_output.="\n\t\t<georss:point>".$alerts[$i][0]["lat"]." ".$alerts[$i][0]["lon"]."</georss:point>"
										."\n\t</item>\n";
				}
				
				//kml stuff
				if ($this->type == 'tube'){
					$a_type = 'Tube Delays';
					$img_inc = '<img src="http://bbc.blueghost.co.uk/images/tube.png" alt="Road Incident" />';
				}
				if ($this->type == 'rail'){
					$a_type = 'Rail Delays';
				}
				if ($this->type == 'road'){
					$a_type = 'Road Delays';
					$img_inc = '<img src="http://bbc.blueghost.co.uk/images/road.png" alt="Road Incident" />';
				}
				if ($this->type == 'train'){
					$a_type = 'Train Delays';
				}
				$this->kml_output .="\n<Placemark>\n\t<name>".$a_type."</name>\n\t<description><![CDATA[".$img_inc.$alerts[$i][0]["summary"];
				$this->kml_output .="<br /><strong>Start Time:</strong>".$start."<br />";
				$this->kml_output .="<strong>End Time:</strong>".$end."]]></description>";
				if (isset($this->icon)){
					$this->kml_output .="\n\t<styleUrl>#".$this->icon."</styleUrl>";
				}else{
					if ($sl >0)
						$this->kml_output .= "\n\t<styleUrl>#severity".substr($alerts[$i][0]["sf"], 0, ($sl-1)) ."</styleUrl>";
					else
						$this->kml_output .="\n\t<styleUrl>#severity-1</styleUrl>";
				}
				$this->kml_output .="\n\t<visibility>0</visibility>";
				
				/*draw line if 2 points*/
				if (count($alerts[$i]) > 1){
					$this->kml_output.= "\n\t<LineString>\n\t\t<extrude>0</extrude>\n\t\t<tessellate>1</tessellate>";
					$this->kml_output.= "\n\t\t<altitudeMode>clampToGround</altitudeMode>";
					$this->kml_output.= "\n\t\t<coordinates>";
					//start point
					$this->kml_output.= "\n\t\t\t".$alerts[$i][0]["lon"].",".$alerts[$i][0]["lat"].",0 ";
					//end point
					$this->kml_output.= $alerts[$i][1]["lon"].",".$alerts[$i][1]["lat"].",0";
					$this->kml_output.= "\n\t\t</coordinates>";
					$this->kml_output.= "\n\t</LineString>";
				}else{/*draw point*/
					$this->kml_output .="\n\t<Point>\n\t\t<coordinates>".$alerts[$i][0]["lon"].",".$alerts[$i][0]["lat"].",0</coordinates>\n\t</Point>";
				}
				
				$this->kml_output .="\n</Placemark>";
			}
	
			$this->output.= "</traffic_data>";
		
    		if ($this->getOutput() == null)
    			return false;
   	 		else
    			return true;
		}

  		// opening tag handler
		function elementBegin($parser, $name, $attributes){
    		global $currentTag, $count, $alerts, $tpeg, $origin, $summary, $loc, $loc_c, $rtm;
			$currentTag = strtolower($name);
			$name = strtolower($name);
			switch($name){
				case 	'tpeg_message': //start new msg
					//echo"entered tpeg_message\n";
					//echo'<h4>'.print_r($attributes).'</h4>';
					$tpeg		= true;
					break;
				case 	'originator': //entering originator
					//echo"entered originator\n";
					//echo'<h4>ATTRIBS:'.print_r($attributes).'</h4>';
					$origin		= true;
					$alerts[$count][$loc_c]["provider"]	= $attributes[strtoupper("originator_name")];
					//echo "wrote ".$attributes[strtoupper("originator_name")]." to alerts[".$count."][\"provider\"]\n";
				break;
				case 	'summary': //summary
				//echo"entered summary\n";
					$summary	= true;
				break;
				case	strtolower('WGS84'): //locdata
					//echo"entered WGS84\n";
					//echo'<h4>ATTRIBS:'.print_r($attributes).'</h4>';
					$loc		= true;
					$alerts[$count][$loc_c]["lat"]	= $attributes[strtoupper("latitude")];
					$alerts[$count][$loc_c]["lon"]	= $attributes[strtoupper("longitude")];
					//go to next loc (reset in close)
					$loc_c++;
					break;
				case	'road_traffic_message': //road_traffic_message
					//echo"entered rtm\n";
					//echo'<h4>ATTRIBS:'.print_r($attributes).'</h4>';
					$rtm = true;
					$alerts[$count][$loc_c]["start"]	= $attributes[strtoupper("start_time")];
					$alerts[$count][$loc_c]["stop"]	= $attributes[strtoupper("stop_time")];
					$alerts[$count][$loc_c]["sf"]	= $attributes[strtoupper("severity_factor")];
				break;
				case	'public_transport_information': //train_info
					$rtm = true;
					$alerts[$count][$loc_c]["start"]	= $attributes[strtoupper("start_time")];
					$alerts[$count][$loc_c]["stop"]	= $attributes[strtoupper("stop_time")];
					$alerts[$count][$loc_c]["sf"]	= $attributes[strtoupper("severity_factor")];
				break;
				default:
					//echo 'Entered with '.$name.'</br>';
					//ignore
				break;
			}
		}

		function characterData($parser, $data){
			global $currentTag, $alerts, $count, $summary, $loc_c;
    		$data = trim(htmlspecialchars($data));
			if ($summary){
				$alerts[$count][$loc_c]["summary"]	=	$data;
				//echo 'Wrote summary: value[\''.$currentTag.'\'] = '.$data.'</br>';
			}else{
				//echo 'DIDNT Wrote summary: value[\''.$currentTag.'\'] = '.$data.'</br>';
			}
		}

 		// closing tag handler
		function elementEnd($parser, $name){
    		global $currentTag, $count, $tpeg, $origin, $summary, $loc, $loc_c, $rtm;
			$currentTag = "";
    		// set flag if exiting <channel> or <item> block
			switch(strtolower($name)){
				case 'tpeg_message': //current conditions
					$tpeg		= false;
					$count++;
					//reset loc_c
					$loc_c = 0;
					//echo 'Exited tpeg</br>';
					break;
				case 'originator': //entering dayf
					$origin		= false;
					//echo 'Exited originator</br>';
					break;
				case 'summary': //new day
					$summary	= false;
					//echo 'Exited summary</br>';
					break;
				case 'WGS84': //end loc
					$loc		= false;
					$loc_c++;
					//never called
					//echo 'Exited WGS84</br>';
					break;
				case	'road_traffic_message': //road_traffic_message
					$rtm = false;
					break;
				case	'public_transport_information': //train info
					$rtm = false;
					break;
				default:
					//nothing
					//echo 'EXITED WITH :'.$name.' , or in lowerwcase : '.strtolower($name).'<br />';
					break;
			}
		}
  
	} //end class
?>