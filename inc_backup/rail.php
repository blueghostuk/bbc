<?php
	function getXML(){
		require('paths.php');
		$filename = $dir.'travel_data/railways.xml';
		return file_get_contents($filename);
	}
	
	function getLDB($loc){
		require('paths.php');
		$filename = $dir.'ldb_data/'.$loc.'.html';
		if (!file_exists($filename)  || ( (filectime($filename) + (5*60)) < time() )){/*5 mins interval*/
			$url 	= "http://www.livedepartureboards.co.uk/ldb/summary.aspx?T=".$loc;
			$output = file_get_contents($url);
			/*
			$output = eregi_replace ("<!--([^-]*([^-]|-([^-]|-[^>])))*-->", "", $output);
			$output = eregi_replace ("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">", "", $output);
			$output = eregi_replace ("<html", "<!--<html>", $output);
			$output = eregi_replace ("</html>", "</html>-->", $output);
			$output = eregi_replace ("<table", "--><table", $output);
			$output = eregi_replace ("</table>", "</table><!--", $output);
			$output = eregi_replace ("<!--([^-]*([^-]|-([^-]|-[^>])))*-->", "", $output);
			*/
			$output = eregi_replace ("<P>", "<p>", $output);
			$output = eregi_replace ("</P>", "</p>", $output);
			$output = eregi_replace ("<a", "<a", $output);
			$output = eregi_replace ("</a>", "</a>", $output);
			$output = eregi_replace ("<table", "<table class=\"ldb_tbl\"", $output);
			$output = eregi_replace ("href=\"t", "href=\"http://www.livedepartureboards.co.uk/ldb/t", $output);
			$output = eregi_replace ("<a", "<a target=\"_blank\"", $output);
			//$output = "<html>".$output."</html>";
			write_header($filename, $output);
		}
		echo file_get_contents($filename);
	}
	
	function getLDBeta($loc){
		require('paths.php');
		$filename = $dir.'ldb_data/'.$loc.'.html';
		$output = file_get_contents($filename);
		$output = str_replace("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">", "", $output);
		$output = str_replace("<html", "<!--<html>", $output);
		$output = str_replace("</html>", "</html>-->", $output);
		$output = str_replace("<table", "--><table", $output);
		$output = str_replace("</table>", "</table><!--", $output);
		$output = str_replace("<!--([^-]*([^-]|-([^-]|-[^>])))*-->", "", $output);
		$output = preg_replace("@<!--[^>]*?>.*?-->@si", "", $output);
		//$output = str_replace("", "", $output);
		//$output = str_replace("", "", $output);
		echo "<xml>\n";
		//echo "<![CDATA[\n";
		echo $output;
		//echo "]]>\n";
		echo "</xml>";
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
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	if ($_GET['beta']){
		echo getLDBeta($_GET['ldb']);
		return;
	}
	if ($_GET['ldb']){
		//header('Content-type: text/html'); 
		echo getLDB($_GET['ldb']);
	}else{
		//header('Content-type: text/xml'); 
		echo getXML();
	}
?>