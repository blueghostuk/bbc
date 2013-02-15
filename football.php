<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Football News</title>
<script src="javascript/common.js" type="text/javascript"></script>
<link href="football.css" rel="stylesheet" type="text/css" />
</head>
<?php
	require('includes/paths.php');
	require('includes/Rdf_Parser2.php');
	require('includes/football.php');
?>
<body>
<div id="selector">
	<div id="hp" class="choiceS">Home Page</div>
	<div id="hc" class="choiceR" 	title="Click to see help" 					onmouseover="javascript:highlight('hc', 'choiceHR');" 	onmouseout="unhighlight('hc', 'choiceR');" 	onclick="javascript:openHelp();">Help</div>
	<div id="ac" class="choiceR" 	title="Click to see detail about this site" onmouseover="javascript:highlight('ac', 'choiceHR');" 	onmouseout="unhighlight('ac', 'choiceR');" 	onclick="javascript:openAbout();">About</div>
</div>
<div id="sub_selector">
	<div id="static">Wolverhampton Wanderers</div>
	<div id="loading">
		<form action="football.php" method="post">
			<select id="league">
			</select>
			<select id="team">
			</select>
		</form>
	</div>
</div>
<div class="news_source">
<?php
	$url 	= 'http://newsrss.bbc.co.uk/rss/sportonline_uk_edition/football/teams/w/wolverhampton_wanderers/rss.xml';
	$file	= 'bbc_sport_wolverhampton_wanderers.html';
	$text	= getFile($url, $file);
	$last	= 'Last Updated @ '.date("D d/m/y H:i:s", filectime($dir.'football_data/'.$file));
?>
	<div class="news_source_title">
		<a href="http://news.bbc.co.uk/sport1/hi/football/teams/w/wolverhampton_wanderers/default.stm" target="_blank">BBC Sport Headlines</a>
		<?php echo $last;?>
	</div>
	<div class="news_source_data">
		<?php echo $text;?>
	</div>
</div>
<div class="news_source_right">
<?php
	$url	= 'http://news.google.co.uk/news?hl=en&ned=uk&q=wolverhampton+wanderers&ie=UTF-8&output=rss';
	$file	= 'google_wolverhampton_wanderers.html';
	$text	= getFile($url, $file);
	$last	= 'Last Updated @ '.date("D d/m/y H:i:s", filectime($dir.'football_data/'.$file));
?>
	<div class="news_source_title">
		<a href="http://news.google.co.uk/news?hl=en&ned=uk&q=wolverhampton+wanderers&ie=UTF-8" target="_blank">Google News UK</a>
		<?php echo $last;?>
	</div>
	<div class="news_source_data">
		<?php echo $text;?>
	</div>
</div>
<div id="footer">
	Powered by <a href="http://backstage.bbc.co.uk" target="_blank">backstage.bbc.co.uk</a> &amp; <a href="http://news.google.co.uk">Google News UK</a> <span class="search_term">with the search term "Wolverhampton Wanderers"</span>
	<br />
	Data Updated Every 30 minutes
</div>
</body>
</html>
