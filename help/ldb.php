<?php
	$changeWidth 	= true;
	$x				= 850;
	$y				= 700;
	$title 		= "How to Load Live Departure Board Info";
	$content 	= "<h2>How to load</h2>
<p>
<ol>
	<li>Select the Railways Option from the top navigation bar <img src=\"images/ldb_guide/choice3.png\" width=\"479\" height=\"378\" style=\"display:block;\" /></li>
    <li>Select the &quot;<strong>Show Live Departures Board</strong>&quot; Link <img style=\"display:block;\" src=\"images/ldb_guide/load3.png\" width=\"348\" height=\"160\" /></li>
    <li>The Stations available will appear as green icons on the map. <a href=\"http://pritch.blueghost.co.uk/wordpress/?page_id=19\" target=\"_blank\"><strong>To see how to add your own click here </strong></a><img style=\"display:block;\" src=\"images/ldb_guide/loaded.png\" width=\"208\" height=\"177\" /></li>
    <li>Click on an icon, or use the zoom controls to zoom in - then click on a green icon marker: (Firefox Ouput Shown)<img style=\"display:block;\" src=\"images/ldb_guide/loaded2.png\" width=\"687\" height=\"276\" /></li>
    <li>The links open shown open up the relevant page in a new window from the national rail website </li>
    <li>You can then make a permanant link to that page, while the timetable is still shown click on &quot;<strong>Permalink</strong>&quot; at the top left of the webpage. The page will reload and the address bar will contain the exact url you can bookmark to show the page again with the marker loaded. e.g for Oakengates the url would be <a onclick=\"javascript:top.opener.location.href='http://bbc.blueghost.co.uk/site4.php?type=rail&ldb=y&marker=4&lon=-2.4276351928710938&lat=52.69303188011482&zl=5';\"><strong>Click here to see url</strong></a></li>
</ol>

</p>
<hr />
<h1>Firefox Vs IE</h1>
<hr />
<h2>This is no longer such an issue, although I still prefer Firefox</h2>
<hr />
<p>In Firefox, the Javascript implementation can load the html document of the national rail website and I can grab the 
table holding the data and the div holding any major delays at that station, hence it gives a nice clean output. Internet Explorer
doesn't allow me to load the html document as an object, and will only display the full HTML. I am working on fixing this soon either with PHP or with a Javascript solution.</p>
<p>Firefox Version:</p>
<p><img src=\"images/ldb_guide/eg.png\" width=\"740\" height=\"406\" /> </p>
<p>I.E Version:</p>
<p><img src=\"images/ldb_guide/eg_ie.png\" width=\"791\" height=\"591\" /></p>
<hr />";
	$links	= "<ul><li><a href=\"help.php\" title=\"Go back to the index\">Help Index</a></li></uk>";
?>