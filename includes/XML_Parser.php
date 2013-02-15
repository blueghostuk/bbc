<?php

	class XML_Parser{
		
		var $file;
  		var $output;
		
		var $currentTag;
  		var $flag;
  		var $count;
		var $channel;
		
		function XML_Parser($source){
    		$this->setSource($source);
    		$this->output = null;
  		}
	
		function setSource($source){
    		$this->file = $source;
			unset($this->output);
  		}

  		function getSource(){
    		return $this->file;
  		}
	
		function getOutput(){
   	 		return $this->output;
  		}
	}

?>