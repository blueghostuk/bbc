<?php
class Tpeg_Parser{
  
var $file;
var $output;

var $count;
var $channel;
var $current;
var $currentTag;

var $alerts;
var	$tpeg;
var $origin;
var $summary;
var $loc;
var $loc_c;
var $rtm;
var $type;

function setSource($source){
	$this->file = $source;
}

function Tpeg_Parser($source){
	$this->setSource($source);
	$this->output 	= null;
}

function getOutput(){
	return $this->output;
}

function setType($tp){
	$this->type = $tp;
}

function parseFile(){
    global $count, $channel, $alerts, $DBC, $current, $loc_c, $type;
    $flag 		= "";
    $count 		= 0;
	$loc_c		= 0;
    
    // this is an associative array of channel data with keys ("title","link","description")
    $channel = array();

    // this is an array of arrays, with each array element representing an <item>
    // each outer array element is itself an associative array
    // with keys ("title", "link", "description")
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
			die("XML parser error: " .xml_error_string(xml_get_error_code($xp)));
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
		
		$this->output.= "<t_alert src=\"".$alerts[$i][0]["provider"]."\" summary=\"".$alerts[$i][0]["summary"]."\" lat=\"".$alerts[$i][0]["lat"]."\" lon=\"".$alerts[$i][0]["lon"]."\" start=\"".$start."\" stop=\"".$end."\"";
		$sl = strlen($alerts[$i][0]["sf"]);
		if ($sl >0)
			$this->output.= " severity=\"".substr($alerts[$i][0]["sf"], 0, ($sl-1)) ."\"";
		else
			$this->output.= " severity=\"-1\"";
		$this->output.= " type=\"".$this->type."\" />";
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