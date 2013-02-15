<?php
	include('includes/paths.php');
	//center locs
	$lat		= 54.31652;//52.7; //to be set from xml
	$lon		= -2.37305;//-2.52; //to be set from xml
	
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
	
	if ($_GET['mw']){
		$show_mw	= "true";
	}else{
		$show_mw	= "false";
	}
	
	if ($_COOKIE['icons']){
		if ($_COOKIE['icons'] == "true")
			$pop_ups = "true";
		else
			$pop_ups = "false";
	}else
		$pop_ups = "false";
	
	if ($pop_ups == "true")
		$ompu = "Hide On Map Pop Ups";
	else
		$ompu = "Show On Map Pop Ups";
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="style32.css" />
<title>BlueGhost UK Weather, with weather.com&reg; &amp; backstage.bbc.co.uk</title>
<script src="http://maps.google.com/maps?file=api&amp;v=1&amp;key=<?php echo $googleKey;?>" type="text/javascript"></script>
<script src="javascript/common_single.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA

	var weather 	= false;
	var traffic		= false;
	var rail		= true;
	var sidebar		= true;
	
	var m_visible		= <?php echo $show_mw;?>;
	var show_pop_ups	= <?php echo $pop_ups;?>;
	
	function getPermalink(){
		document.location.href = 'http://bbc.blueghost.co.uk/site32.php?' + genPermalink();
	}
	
	function createTrafficMarker(lat, lon, html, src, type, start, stp,  image, alat, alon, id) {
		var icon = new GIcon();
		icon.image 		= "<?php echo $img_url;?>"+image+".png";
		icon.shadow 	= "<?php echo $img_url;?>weather/trans.png";
		icon.iconSize 	= new GSize(19, 40);
		icon.shadowSize = new GSize(1, 1);
		icon.iconAnchor = new GPoint(6, 20);
		icon.infoWindowAnchor = new GPoint(5, 1);
		var point = new GPoint(lon, lat);
  		var marker = new GMarker(point,icon);
		var title 	= '<img style="float:left" src="<?php echo $img_url;?>'+type+'.png" alt="img" />';
		var text	= html+'<a href="http://bbc.co.uk/travel" target="_blank" title="Go to the '+src+' website">Source:'+src+'</a>';
		text		+='<div>Started @ '+start+'</div><div>End @ '+stp+'</div>';
		var links	='<a href="javascript:zoomTo('+lat+','+lon+', 3);">Zoom In</a>';
		links		+='<a href="javascript:zoomTo('+alat+','+alon+', 7);">Zoom Out to Area</a>';
		links		+='<a href="javascript:zoomOut();">Zoom Out Fully</a>';
		
		var html = '<div style="width: 200px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small;"><img style="float:left" src="<?php echo $img_url;?>'+type+'.png" alt="img" />'+html+'</div><strong>Source:'+src+'</strong>';
  		html += '<div>Started @ '+start+'</div><div>End @ '+stp+'</div>';
		html += '<div class="weather_link"><a href="javascript:zoomTo('+lat+','+lon+', 3);">Zoom In</a></div>';
		html += '<div class="weather_link"><a href="javascript:zoomTo('+alat+','+alon+', 7);">Zoom Out to Area</a></div>';
		html += '<div class="weather_link"><a href="javascript:zoomOut();">Zoom Out Fully</a></div>';
		
		GEvent.addListener(marker, "click", function() {
    		setDesc(title, text);
			setRoadLinks(links);
			if (show_pop_ups)
				marker.openInfoWindowHtml(html);
			//last_open = id; //refers to t_markers - MAY CHANGE DURING RELOADS HENCE AVOID
  		});
		return marker;
	}
	
	
	function createTrafficCountyMarker(html, title, lat, lon, id){
		var point = new GPoint(lon,lat);
		var marker = new GMarker(point,tICon);
		var id = county_markers.length;
		var info_html 	= document.createElement("div");
		info_html.id = 'traffic_'+html+'';
		info_html.className = 'weather_link';
		info_html.innerHTML = '<a>'+title+'</a><br/><a href="javascript:goToTraffic(\''+html+'\', '+lat+', '+lon+', '+id+', \''+title+'\');" title="See Delays in '+title+'">Show Delays</a><br/><a href="javascript:zoomOut();" title="Zoom Out">Zoom Out</a>';
		county_markers.push(info_html);
		var title 	= "Traffic Warnings for "+title;
		var text	= 'Last Updated @ <?php echo date("D d/m H:i:s", filectime($t_dir."shropshire_tpeg_copy.xml"));?>';
		
		GEvent.addListener(marker, "click", function() {
			//if (show_pop_ups)
    			marker.openInfoWindow(county_markers[id]);
			setDesc(title, text);
			last_open = id; //refers to t_markers
  		});
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

			setupTraffic();
			reload();
		}//end iscompat
	}//end onLoad

	function setupTraffic(){
		resetRoadLinks();
		var title	= "Traffic Data";
		var text	= 'Traffic Data Data supported by <a href="http://backstage.bbc.co.uk" target="_blank" title="Go to the BBC Backstage Website">backstage.bbc.co.uk</a>';
		text		+= 'Locations List Last Updated @ <?php echo date("D d/m H:i:s", filectime($t_locs));?>';
		setDesc(title, text);
		
		var img 	= 'bbc_v2.png';
		var size_x 	= 350;
		var size_y 	= 30;
		var html 	= '<div class="weather_link">supported by<br /><a href="http://backstage.bbc.co.uk" target="_blank">backstage.bbc.co.uk</a></div>';
		var move	= false;
		var moveend	= true;
		var clicker	= true;
		var reloader= false;
		var zoom	= true;
		placeCopyLogo(wICon, wMarker, img, size_x, size_y, html, move, moveend, clicker, reloader);
		
		//add each county
		if (traffic_setup){
			for(var i = 0; i< t_markers.length; i++)
				map.addOverlay(t_markers[i]);
			//var info_html = document.getElementById('traffic_motorways');
			setFooterElement(mw);
		}else{
			//icon for each county
			if (!tICon){
				tICon	= new GIcon();
				tICon.image = "<?php echo $img_url;?>mm_20_white.png";
				tICon.shadow = "<?php echo $img_url;?>mm_20_shadow.png";
				tICon.iconSize = new GSize(12, 20);
				tICon.shadowSize = new GSize(22, 20);
				tICon.iconAnchor = new GPoint(0, 0);
				tICon.infoWindowAnchor = new GPoint(-5, -5);
			}
			var url		= '<?php echo $site_url;?>travel_data/locations.xml';
			var status	= 'Loading Traffic Data ...';
			doXMLHTTPRequest(setupTrafficXML, url, status);
			
			//set footer
			var id = county_markers.length;
			mw 	= document.createElement("div");
			mw.id = 'traffic_motorways';
			mw.className = 'weather_link';
			mw.innerHTML = '<a id="m_link" href="javascript:goToTraffic(\'motorways\', \'0\', \'0\', '+id+', \'UK Motorways\');" title="See Delays on the Motorways">Show Motorway Delays</a>';
			county_markers.push(mw);
			setFooterElement(mw);
			
			traffic_setup = true;
		}
	}
	
	function reload(){
		updateDisplay();
	}

	function goToTraffic(loc, lat, lon, id, title){
		if (id == 0){/*MOTORWAYS*/
			var m_link = document.getElementById('m_link');
			m_link.innerHTML 	=  'Hide Motorway Delays';
			m_link.href			=  'javascript:removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');';
			m_link.title		=	'Hide Delays in '+title;
			county_markers[id].innerHTML =  '<a id="m_link" href="javascript:removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');" title="Hide Delays in '+title+'">Hide Motorway Delays</a>';
			m_visible = true;
		}else{
			county_markers[id].innerHTML =  '<a>'+title+'</a><br/><a href="javascript:removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');" title="Hide Delays in '+title+'">Hide Delays</a><br/><a href="javascript:zoomOut();" title="Show Delays in '+title+'">Zoom Out</a>';
			var point = new GPoint(lon,lat);
			map.centerAndZoom(point, 7);
		}
		tPlace 	= loc;
		tLat	= lat;
		tLon	= lon;
		reloadTraffic();
	}
	
	function removeTraffic(loc, lat, lon, id, title){
		var index = 0;
		for (var i = 0; i < traffic_data.length; i++){
			if (traffic_data[i] == loc){
				index = i;
				i = traffic_data.length + 1;
			}
		}
		for (var start = traffic_start[index]; start < traffic_end[index]; start++)
			map.removeOverlay(traffic_markers[start]);
		if (id == 0){/*MOTORWAYS*/
			var m_link = document.getElementById('m_link');
			m_link.innerHTML 	=  'Show Motorway Delays';
			m_link.href			=  'javascript:goToTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');';
			m_link.title		=	'Show Delays in '+title;
			county_markers[id].innerHTML =  '<a id="m_link" href="javascript:goToTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');" title="Show Delays in '+title+'">Show Motorway Delays</a>';
			m_visible = false;
		}else{
			county_markers[id].innerHTML =  '<a>'+title+'</a><br/><a href="javascript:goToTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');" title="Show Delays in '+title+'">Show Delays</a><br/><a href="javascript:zoomOut();" title="Zoom Out">Zoom Out</a>';
		}
		resetRoadLinks();
	}
	function reloadTraffic(){
		var index = -1;
		for (var i = 0; i < traffic_data.length; i++){
			if (traffic_data[i] == tPlace){
				index = i;
				i = traffic_data.length + 1;
			}
		}
		if (index != -1){
			for (var start = traffic_start[index]; start < traffic_end[index]; start++)
				map.addOverlay(traffic_markers[start]);
			var lo	= document.getElementById("loading");
			lo.innerHTML = "Loaded Traffic Data";
		}else{
			var url	= "<?php echo $site_url;?>includes/travel_new.php?area="+tPlace;
			var status	= 'Loading Traffic Data ...';
			doXMLHTTPRequest(reloadTrafficXML, url, status);
		}
	}
	
	/**
	 * Resets all traffic markers to say "Show Delays"
	 */
	function resetTraffic(){
		//not yet implemented
	}	
		
	function goToSidebar(){
		document.location.href	= 'http://bbc.blueghost.co.uk/traffic.php?'+genPermalink();
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
	<div id="pu" class="choiceP"	title="Show On Screen Pop-Ups" 							onmouseover="javascript:highlight('pu', 'choiceHP');"	onmouseout="unhighlight('pu', 'choiceP');" 	onclick="javascript:setPopUps();"><?php echo $ompu;?></div>
	<div id="sb" class="choiceP" 	title="Click to Remove the Sidebar to the map" 			onmouseover="javascript:highlight('sb', 'choiceHP');" 	onmouseout="unhighlight('sb', 'choiceP');" 	onclick="javascript:goToSidebar();">Remove Sidebar</div>
	<div id="hc" class="choiceR" 	title="Click to see help" 								onmouseover="javascript:highlight('hc', 'choiceHR');" 	onmouseout="unhighlight('hc', 'choiceR');" 	onclick="javascript:openHelp();">Help</div>
	<div id="ac" class="choiceR" 	title="Click to see detail about this site" 			onmouseover="javascript:highlight('ac', 'choiceHR');" 	onmouseout="unhighlight('ac', 'choiceR');" 	onclick="javascript:openAbout();">About</div>
</div>
<div id="sub_selector">
	<div id="static">Some Text</div>
	<div id="loading">Loading ABC...</div>
</div>
<div id="weather_map"></div>
<div id="desc_box">
	<div id="desc_title"></div>
	<div id="desc_text"></div>
	<div id="desc_footer">
		<hr class="divider" />
		<strong>Options</strong><hr class="divider" />
		<a href="javascript:zoomOut();">Reset Viewpoint</a>
		<a id="ompu" href="javascript:setPopUps();" title="Uses Cookies to set Preferences"><?php echo $ompu;?></a>
		<hr class="divider" />
		<div id="footer_text"></div>
		<div id="traffic_links"></div>
	</div>
</div>
<!--<div id="cLinks">
	<img src=""
</div>-->
</body>
</html>