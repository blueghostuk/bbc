<?php

	require('includes/paths.php');
	require('includes/XML_Parser.php');
	require('includes/TV_Channel.php');
	require('includes/TV_Program.php');
	require('includes/TV_Related_Info.php');
	require('includes/TV_Genre.php');
	require('includes/TV_AVAttributes.php');
	require('includes/TV_Program_Schedule.php');
	require('includes/TV_ProgramParser.php');
	require('includes/TV_ScheduleParser.php');
	require('includes/TV_Outputter.php');
	require('includes/TV_View.php');
	require('/home/blueghos/db.php');
	require('includes/DB_Connection.php');
	require('includes/TV_DBConnection.php');
	require('includes/Bleb_ProgramParser.php');
	require('includes/Bleb_Program.php');
	require('includes/Bleb_AVAttributes.php');
	require('includes/Bleb_Program_Schedule.php');	
	require('includes/Bleb_Outputter.php');
	require('includes/Bleb_Channel.php');
	
	header('Content-type: text/xml');
	
	$dbase   	= 'blueghos_tv';
	$Database 	= new TV_DBConnection();
	$Database->DB_connect($db_host, $db_user, $db_pwd, $dbase);
	if (isset($_REQUEST['nnp'])){
		$total_op = '<?xml version="1.0" encoding="utf-8"?>';
		$date = date("Ymd", time());
		$token = explode("+", $_REQUEST['nnp']);
		$token = explode(" ", $_REQUEST['nnp']);
		//print_r($token);
		//echo 'total_op  <br />';
		echo $total_op;
		foreach ($token as $tok){
			//echo '<br />token '.$tok.' , ';
			$op = new TV_Outputter(null);
			$sChannel = $Database->getChannel($tok, $date);
			$nn = $sChannel->getNowAndNext(time());
			if ($op->parseMultipleNNForXML($nn['progs'], $nn['sched'], $sChannel->title)){
				//$opo = $op->getOutput();
				echo /*$total_op +=*/ $op->getOutput();
				//$total_op += $op->getOutput();
				//echo 'total_op  <br />';
				//echo $total_op;
			}else{
				/*$total_op += "\t\t<item>\n";
				$total_op += "\t\t\t<title>".$sChannel->title." - No Data</title>\n";
				$total_op += "\t\t\t<link>http://tv.blueghost.co.uk/channel/".$sChannel->id."</link>\n";
				$total_op += "\t\t</item>\n";*/
			}
		}
		//echo $total_op;
	}
	
?>