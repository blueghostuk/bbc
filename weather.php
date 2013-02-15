<?php
	include('includes/paths.php');
	//center locs
	$lat		= 54.31652;//52.7; //to be set from xml
	$lon		= -2.37305;//-2.52; //to be set from xml
	
	if ($_GET['mw']){
		$show_mw	= "true";
	}else{
		$show_mw	= "false";
	}
	
	if ($_GET['marker'] || $_GET['marker'] == "0"){
		$marker_id = $_GET['marker'];
		$markers = "true";
	}else{
		$marker_id = 0;
		$markers = "false";
	}
	
	if ($_GET['lat']){
		$zoom	= "true";
		$lv		= $_GET['lat'];
		$lnv	= $_GET['lon'];
		$zl		= $_GET['zl'];
	}else{
		$zoom	= "false";
		$lv		= 1;
		$lnv	= 1;
		$zl		= 1;
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="style23.css" />
<title>BlueGhost UK Weather, with weather.com&reg; &amp; backstage.bbc.co.uk</title>
<script src="http://maps.google.com/maps?file=api&amp;v=1&amp;key=<?php echo $googleKey;?>" type="text/javascript"></script>
<script src="javascript/common_single.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA

	var weather 	= true;
	var traffic		= false;
	var rail		= false;
	var sidebar		= false;
			
	function getPermalink(){
		document.location.href = 'http://bbc.blueghost.co.uk/weather.php?' + genPermalink();
	}

	function createWeatherMarker(point, img, size, name, temp, time, links, id) {
		var icon = new GIcon();
		icon.image 		= "<?php echo $img_url;?>weather/"+size+"x"+size+"/"+img+".png";
		icon.shadow 	= "<?php echo $img_url;?>weather/trans.png";
		icon.iconSize 	= new GSize(size, size);
		icon.shadowSize = new GSize(1, 1);
		icon.iconAnchor = new GPoint(32, -5);
		icon.infoWindowAnchor = new GPoint(32, -5);
  		var marker = new GMarker(point,icon);
		var html = '<strong>'+name+'</strong><img src="<?php echo $img_url;?>weather/64x64/'+img+'.png" alt="Weather img" /><br/><span style="font-size:small;">'+temp+'°C @ '+time+'</span>';
		html += '<div class="weather_link"><a href="<?php echo $bbc_s;?>'+name+'" target="_blank">More @ BBC Weather</a>';
		html += '<div class="weather_link">Featured on weather.com&reg;</div>';
		for (var i=0; i < links.length; i++){
			html += '<div class="weather_link" style="text-align:left;">'+links[i]+'</div>';
		}
  		GEvent.addListener(marker, "click", function() {
			marker.openInfoWindowHtml(html);
    		last_open = id; //refers to mkrs[]
  		});
		if ( (first_run) && (id == <?php echo $marker_id;?>) ){
			GEvent.trigger(marker, "click");
			first_run = false;
		}
		return marker;
	}
	
	function onLoad(){
		if (GBrowserIsCompatible()) {
			/*DETECT RES */
			if (screen.width == '1280' && screen.height == '800')
				widescreen = true;
			if (screen.width == '1280' && screen.height == '768')
				widescreen = true;
			
			loc = new GPoint(<?php echo $lon;?>,<?php echo $lat;?>);
			cen	= new GPoint(<?php echo $lon;?>,<?php echo $lat;?>);
			map = new GMap(document.getElementById("weather_map"));
			map.addControl(new GLargeMapControl());
			map.addControl(new GMapTypeControl());
			map.addControl(new GScaleControl());
			
			if (<?php echo $zoom;?>){
				var p = new GPoint(<?php echo $lnv;?>,<?php echo $lv;?>);
				map.centerAndZoom(p, <?php echo $zl;?>);
			}else{
				map.centerAndZoom(cen, 11);
			}
			
			if (<?php echo $markers;?>)
				first_run = true;
			setupWeather();
			
			reload();
		}//end iscompat
	}//end onLoad
	
	function setupWeather(){
		var img 	= 'weather/TWClogo_64px.png';
		var size_x 	= 64;
		var size_y 	= size_x;
		var html 	= '<div class="weather_link">Weather data provided by<br /><a href="http://www.weather.com/?prod=xoap&par=<?php echo $partnerID;?>" title="Go to weather.com" target="_blank">weather.com &reg;</a></div>';
		var move	= false;
		var moveend	= true;
		var clicker	= true;
		var reloader= true;
		var zoom	= true;
		placeCopyLogo(wICon, wMarker, img, size_x, size_y, html, move, moveend, clicker, reloader, zoom);
	}
	
	function reload(){
	
		updateDisplay();
	
		var lo	= document.getElementById("loading");
		
		lo.innerHTML = "Loading Weather Data...";
		reloadWeather();
	}
	
	function reloadWeather(){
		var research	= true;
    	var bounds = map.getBoundsLatLng();
		if (day == 0)
			var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=100&day=0";
		else
			var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=100&day=1";

		if (lastWUrl == url){/*don't need to fetch again*/
			research = false;
		}else
			lastWUrl = url;
			
		if (research){
			var status	= 'Loading Weather Data ...';
			doXMLHTTPRequest(reloadWeatherXML, url, status);
		}else{ /*use same xmldoc*/
			var lo	= document.getElementById("loading");
			lo.innerHTML = 'Loading Cached Weather Data ...';
			reloadWeatherXML(lastXMLdoc);
		}
	}
		
	function goToSidebar(){
		document.location.href	= 'http://bbc.blueghost.co.uk/weather_sb.php?'+genPermalink();
	}

//]]>
</script>
</head>
<body onload="onLoad()" onunload="unLoad()">
<div id="selector">
	<div id="wc" class="choiceS"	title="Click to Show Weather Overlay" 					onmouseover="javascript:highlight('wc', 'choiceH');" 	onmouseout="unhighlight('wc', 'choice');" 	onclick="javascript:goTo('weather');">Weather</div>
	<div id="rc" class="choice"  	title="Click to Show Rail Overlay" 						onmouseover="javascript:highlight('rc', 'choiceH');" 	onmouseout="unhighlight('rc', 'choice');" 	onclick="javascript:goTo('rail');">Rail</div>
	<div id="tc" class="choice"  	title="Click to Show Road Traffic Overlay" 				onmouseover="javascript:highlight('tc', 'choiceH');" 	onmouseout="unhighlight('tc', 'choice');" 	onclick="javascript:goTo('traffic');">Traffic</div>
	<div id="pc" class="choiceP" 	title="Click to get a Permanant Link to this map view" 	onmouseover="javascript:highlight('pc', 'choiceHP');" 	onmouseout="unhighlight('pc', 'choiceP');" 	onclick="javascript:getPermalink();">Permalink</div>
	<div id="rv" class="choiceP"	title="Reset the Viewpoint back to the original position" onmouseover="javascript:highlight('rv', 'choiceHP');"	onmouseout="unhighlight('rv', 'choiceP');" 	onclick="javascript:zoomOut();">Reset Viewpoint</div>
	<div id="sb" class="choiceP" 	title="Click to Add a Sidebar to the map" 				onmouseover="javascript:highlight('sb', 'choiceHP');" 	onmouseout="unhighlight('sb', 'choiceP');" 	onclick="javascript:goToSidebar();">Add Sidebar</div>
	<div id="hc" class="choiceR" 	title="Click to see help" 								onmouseover="javascript:highlight('hc', 'choiceHR');" 	onmouseout="unhighlight('hc', 'choiceR');" 	onclick="javascript:openHelp();">Help</div>
	<div id="ac" class="choiceR" 	title="Click to see detail about this site" 			onmouseover="javascript:highlight('ac', 'choiceHR');" 	onmouseout="unhighlight('ac', 'choiceR');" 	onclick="javascript:openAbout();">About</div>
</div>
<div id="sub_selector">
	<div id="static">Some Text</div>
	<div id="loading">Loading ABC...</div>
</div>
<div id="weather_map"></div>
</body>
</html>