<?php
	require('XML_Parser.php');
	class Weather_Parser3 extends XML_Parser{
		var $lc;

		var $days;

		var	$currentcc;
		var $current;
		var $links;
		var $lnks;
		var	$day;
		var $part;
		var $d_val;
		var $night;
		var $d_day;
		var $d_date;
		var $loc;
		var $loc_lat;
		var $loc_lon;
		var $loc_name;

		var $base_url;
		
		var $jsonOutput = array( "days" => array(), "links" => array() );
		
		function getJson(){
			return json_encode($this->jsonOutput);
		}
		
		function getLat(){
			return $this->$loc_lat;
		}

		function getLon(){
			return $this->$loc_lon;
		}
  
		function parseFile(){
    		global $currentTag, $flag, $count, $channel, $days, $base_url, $DBC, $current, $mdv, $links, $lc, $loc_lat, $loc_lon, $loc_name;
    		$currentTag = "";
   		 	$flag 		= "";
    		$count 		= 0;
			$lc			= 0;
    
    		$channel = array();

    		$days = array();
			$current = array();
			$links	 = array();
	
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
					die("XML parser error: " .xml_error_string(xml_get_error_code($xp)));
      			}
   		 	}

    		// destroy parser
    		xml_parser_free($xp);

			$dates 		= array();
			$images		= array();
			$descs		= array();
			$maxes		= array();
			$mins		= array();
			$dates[0] 	= "Current";
			$images[0]	= $current["icon"];
			$descs[0]	= $current["t"];
			$temps		= $current["tmp"];
			$maxes[0]	= "0";
			$mins[0]	= "0";
	
			$c= 1;
			foreach ($days as $day){
				$today				= $day["t"]." ".$day["dt"];
				$dates[$c]			= $today;
				$maxes[$c]			= $day["hi"];
				$mins[$c]			= $day["low"];
				$images[$c]			= $day["day_icon"];
				$descs[$c]			= $day["day_t"];
				$c++;
    		}
			$i= 0;
			
			$jsonData = array();
			$loc_name = eregi_replace(", United Kingdom", "", $loc_name);
			//$this->output			.= "<weather>\n";
			//$this->output			.= "\t<info lat=\"".$loc_lat."\" lon=\"".$loc_lon."\" name=\"".$loc_name."\" />\n";	
			$this->output 	.= '<weather_location><days>';
			for ($i=0; $i < $c; $i++){
				if ($c < 2){
					$this->output	.= "\t<day rel=\"".$i."\" lat=\"".$loc_lat."\" lon=\"".$loc_lon."\" name=\"".$loc_name."\"";
					$this->output	.= " time=\"".date("D jS M @ H:i",time())."\" temp=\"".$temps."\"";
					$this->output	.= " icon=\"".$images[$i]."\" title=\"".$descs[$i]."\" alt=\"".$descs[$i]."\" />\n";
					
					$this->jsonOutput["days"][] = array("rel" => $i, "lat" => $loc_lat, "lon" => $loc_lon, "name" => $loc_name, "time" => date("D jS M @ H:i",time()),
						"temp" => $temps, "icon" => $images[$i], "title" => $descs[$i], "alt" => $descs[$i]);
				}else{
					//if ($i != 0){
						//if ($i != 1){
							$this->output	.= "\t<day rel=\"".($i-1)."\" lat=\"".$loc_lat."\" lon=\"".$loc_lon."\" name=\"".$loc_name."\"";
							$this->output	.= " time=\"".$dates[$i]."\" temp=\"Min:".$mins[$i].", Max:".$maxes[$i]."\"";
							$this->output	.= " icon=\"".$images[$i]."\" title=\"".$descs[$i]."\" alt=\"".$descs[$i]."\" />\n";
							
							$this->jsonOutput["days"][] = array("rel" => ($i-1), "lat" => $loc_lat, "lon" => $loc_lon, "name" => $loc_name, "time" => $dates[$i],
								"temp" => "Min:".$mins[$i].", Max:".$maxes[$i], "icon" => $images[$i], "title" => $descs[$i], "alt" => $descs[$i]);
						//}
					//}
				}
			}
			$this->output 	.= '</days><links>';
			foreach ($links as $link){
				if ($link["l"] != "par=xoap")
					$this->output	.= "\t<link href=\"".$link["l"]."par=1009003705\" title=\"".$link["t"]."\" />\n";
					
					$this->jsonOutput["links"][] = array("href" => $link["l"], "title" => $link["t"]);
			}
			$this->output 	.= '</links></weather_location>';
			//$this->output			.= "</weather>";
    		if ($this->getOutput() == null)
    			return false;
    		else
    			return true;
		}

 		// opening tag handler
		function elementBegin($parser, $name, $attributes){
    		global $currentTag, $flag, $currentcc, $day, $part, $d_val, $night, $d_day, $d_date, $lnks, $loc;
    		$currentTag = strtolower($name);
			$name = strtolower($name);
			switch($name){
				case 'cc': //current conditions
					$currentcc	= true;
					//echo 'Entered CurrentCC</br>';
				break;
				case 'dayf': //entering dayf
					$dayf		= true;
					//echo 'Entered dayf</br>';
				break;
				case 'day': //new day
					$day		= true;
					//echo'<h2>DAY ATTRIBS:</h2>';
					//echo'<h4>'.print_r($attributes).'</h4>';
					$d_day 		= $attributes[strtoupper("t")];
					$d_date		= $attributes[strtoupper("dt")];
					//echo 'Entered Day</br>';
				break;
				case 'part': //part of a day
					$part		= true;
					//echo'<h2>PART ATTRIBS:</h2>';
					//echo'<h4>'.print_r($attributes).'</h4>';
					switch ($attributes[strtoupper("p")]){
						case 'd':
							$d_val 	= true;
							$night	= false;
						break;
						case 'n':
							$night 	= true;
							$d_val	= false;
						break;
					}
					//echo 'Entered Part</br>';
				break;
				case 'bar':
					$currentcc		= false;
					//echo 'BAR exited currentcc<br />';
				break;
				case 'wind':
					$part			= false;
					//echo 'WIND exited part</br />';
					break;
				case 'link':
					$lnks			= true;
					//echo 'Entered Link</br>';
					break;
				case 'loc':
					$loc			= true;
					break;
				default:
					//echo 'Entered with'.$name.'</br>';
					//ignore
				break;
			}
		}

		function characterData($parser, $data){
			global $currentTag, $flag, $days, $channel, $count, $current, $currentcc, $day, $part, $d_val, $night, $d_day, $d_date, $lnks, $lc, $links, $loc, $loc_lat, $loc_lon, $loc_name;
    		$data = trim(htmlspecialchars($data));
			if ($currentcc){
					switch ($currentTag){
						//values:
						//	'tmp'	=	Current temp 
						//	't'		=	Desc.
						//	'icon'	=	Icon number
						case 	'tmp':
						case	't':
						case	'icon':
							//echo 'Wrote CurrentCC: value[\''.$currentTag.'\'] = '.$data.'</br>';
							$current[strtolower($currentTag)]	=	$data;
						break;
						default:
							//don't add - save memory
							//$current[strtolower($currentTag)]	=	$data;
							//echo $currentTag.'</br>';
						break;
					}
			}
			//if ($dayf){
					//switch ($currentTag){
		
						//default:
							//ignore
						//break;
					//}
			//}
			if ($day){
				if ($part){
					if ($d_val){
						switch ($currentTag){
							//values
							//	'icon'	=	Icon Value
							//	't'		=	Desc.
							case 	'icon':
							case	't':
								$days[$count]["day_".strtolower($currentTag)]	=	$data;
								//echo 'Wrote PartD: value[\''.$currentTag.'\'] = '.$data.'</br>';
							break;
							default:
								//do nothing
								//echo 'IN d_VAL = '.$currentTag.'</br>';
							break;
						}
					}elseif($night){
						switch ($currentTag){
							//values
							//	'icon'	=	Icon Value
							//	't'		=	Desc.
							case 	'icon':
							case	't':
								$days[$count]["night_".strtolower($currentTag)]	=	$data;
								//echo 'Wrote partN: value[\''.$currentTag.'\'] = '.$data.'</br>';
							break;
							default:
								//do nothing
								//echo 'IN NIGHT = '.$currentTag.'</br>';
							break;
						}
					}else{
						//echo'NOT DAY OR NIGHT<br/>';
					}
				}else{
					$days[$count]["t"]		= $d_day;
					$days[$count]["dt"]		= $d_date;
					switch ($currentTag){
						//values
						//	'hi'	=	Hi TMP
						//	'low'	=	Low TMP
						case 'hi':
							$days[$count][strtolower($currentTag)]		= $data;
							//echo 'Wrote day: value[\''.$currentTag.'\'] = '.$data.'</br>';
						break;
						case 'low':
							$days[$count][strtolower($currentTag)]		= $data;
							//echo 'Wrote day: value[\''.$currentTag.'\'] = '.$data.'</br>';
						break;
						default:
							//ignore
							//echo $currentTag.'</br>';
						break;
					}
				}
			}
			if ($lnks){
				switch ($currentTag){
					//values
					// 'l'	=	Link
					//	't' =	Text
					case 'l':
					case 't':
						$links[$lc][strtolower($currentTag)]	= $data;
						//echo 'Wrote Link: ['.$lc.']value[\''.$currentTag.'\'] = '.$data.'</br>';
					break;
					default:
						//ignore
					break;
				}		
			}
			if ($loc){
				switch ($currentTag){
					//values
					// 'lat'	=	Lattitude
					//	'lon' 	=	Longitude
					case 'lat':
						$loc_lat = $data;
						//echo 'Wrote Link: $loc_lat[\''.$currentTag.'\'] = '.$data.'</br>';
					break;
					case 'lon':
						$loc_lon = $data;
						//echo 'Wrote Link: $loc_lon[\''.$currentTag.'\'] = '.$data.'</br>';
					break;
					case 'dnam':
						$loc_name	= $data;
						//echo 'Wrote Link: $loc_name[\''.$currentTag.'\'] = '.$data.'</br>';
						break;
					default:
						//ignore
					break;
				}		
			}
		}

  		// closing tag handler
		function elementEnd($parser, $name){
    		global $currentTag, $count, $flag, $currentcc, $day, $part, $d_val, $night, $d_day, $d_date, $lnks, $lc, $loc;
    		$currentTag = "";
    
    		// set flag if exiting <channel> or <item> block
			switch(strtolower($name)){
				case 'cc': //current conditions
					$currentcc	= false;
					//echo 'Exited CurrentCC</br>';
					//$count++;
				break;
				case 'dayf': //entering dayf
					$dayf		= false;
					//echo 'Exited Dayf</br>';
				break;
				case 'day': //new day
					$day		= false;
					$count++;
					//echo 'Exited Day</br>';
				break;
				case 'part':
					$part		= false;
					$d_val		= false;
					$night		= false;
					//echo 'Exited Part</br>';
				break;
				case 'link':
					$lnks		= false;
					$lc++;
					//echo 'Exited Link</br>';
				case 'loc':
					$loc			= false;
					//echo 'Exited Loc</br>';
					break;
				default:
					//nothing
					//echo 'EXITED WITH :'.$name.' , or in lowerwcase : '.strtolower($name).'<br />';
					break;
			}
		}
  
	} //end class

?>