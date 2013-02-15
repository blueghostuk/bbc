<?php
	include('includes/paths.php');
	
	//set cookies
	setcookie ("type", 	"non-sidebar", 	time()+ (365 * 86400));
	
	if ($_GET['type']){
		switch($_GET['type']){
			case 'rail':
				$rail 		= "true";
				$traffic 	= "false";
				$weather	= "false";
			break;
			case 'traffic':
				$rail 		= "false";
				$traffic 	= "true";
				$weather	= "false";
			break;
			case 'weather':
			default:
				$rail 		= "false";
				$traffic 	= "false";
				$weather	= "true";
			break;
		}
	}else{
		$rail 		= "false";
		$traffic 	= "false";
		$weather	= "true";
	}
	
	if ($_GET['ldb']){
		$show_ldb	= "true";
	}else{
		$show_ldb	= "false";
	}
	
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
<link rel="stylesheet" type="text/css" href="style23.css" />
<title>BlueGhost UK Weather, with weather.com&reg; &amp; backstage.bbc.co.uk</title>
<script src="http://maps.google.com/maps?file=api&amp;v=1&amp;key=<?php echo $googleKey;?>" type="text/javascript"></script>
<script src="javascript/common.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA

	var weather 	= <?php echo $weather;?>;
	var rail 		= <?php echo $rail;?>;
	var traffic 	= <?php echo $traffic;?>;
	
	var show_pop_ups	= <?php echo $pop_ups;?>;
	var ldb_visible		= <?php echo $show_ldb;?>;
	
	var m_visible		= <?php echo $show_mw;?>;
		
	function getPermalink(){
		document.location.href = 'http://bbc.blueghost.co.uk/site24.php?' + genPermalink();
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
	
	function createRailMarker(point, html, src, type, start, stp,  image) {
		var icon = new GIcon();
		icon.image 		= "<?php echo $img_url;?>"+image+".png";
		icon.shadow 	= "<?php echo $img_url;?>weather/trans.png";
		icon.iconSize 	= new GSize(19, 40);
		icon.shadowSize = new GSize(1, 1);
		icon.iconAnchor = new GPoint(6, 20);
		icon.infoWindowAnchor = new GPoint(5, 1);
  		var marker = new GMarker(point,icon);
		var info_html = '<div style="width: 200px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small;"><img style="float:left" src="<?php echo $img_url;?>'+type+'.png" alt="img" />'+html+'</div><strong>Source:'+src+'</strong>';
  		info_html += '<div>Started @ '+start+'</div><div>End @ '+stp+'</div>';
		info_html += '<div class="weather_link"><a href="javascript:zoomOut();">Zoom Out</a>';
		GEvent.addListener(marker, "click", function() {
			map.centerAndZoom(point, 2);
    		marker.openInfoWindowHtml(info_html);
  		});
		return marker;
	}
	
	function createLDBMarker(point, name, code, id) {
  		var marker = new GMarker(point, ldbICon);
		var request = GXmlHttp.create();
		var links		='<div class="weather_link"><a href="<?php echo $ldb_site;?>'+code+'">Live Data from National Rail</a>';
		
		GEvent.addListener(marker, "click", function() {
			map.centerAndZoom(point, 5);
			loadLDB(marker, name, code);
			//if (show_pop_ups)
				//marker.openInfoWindowHtml(html);
			last_open = id; //refers to rail_markers
  		});
		
		if ( (first_run) && (id == <?php echo $marker_id;?>) ){
			GEvent.trigger(marker, "click");
			first_run = false;
		}
		return marker;		
	}
	
	function loadLDB(marker, name, code){
		var request 	= GXmlHttp.create();
		var url			= '<?php echo $site_url;?>includes/rail.php?ldb='+code;
		var lo			= document.getElementById("loading");
		lo.innerHTML 	= 'Loading LDBData ...';// from "+url+" ...";
		request.open("GET", url	, true);
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				if (request.status == 200) {
					var xmlDoc 	= request.responseXML;
					if (xmlDoc.documentElement){
						var data = xmlDoc.documentElement.getElementsByTagName('table');
						var divs = xmlDoc.documentElement.getElementsByTagName('div');
						var a_d	= -1;
						for (var i=0; i< divs.length; i++){
							if (divs[i].className == "maint_message"){
								a_d	= i;
								i = divs.length;
							}
						}
						var st_title  	= '<td colspan="7" title="Data Source" class="st_name">'+name+'</td></tr>';
						var copy_text  	= '<td colspan="7" title="Data Source" class="copy_text"><a href="http://www.livedepartureboards.co.uk/virgintrains/summary.aspx?T='+code+'" target="_blank">Data from Virgin Trains Live Departure Boards</a></td></tr>';
						var copy_2		= '<td colspan="7" title="Data Source" class="copy_text"><a href="http://www.atoc.org" target="_blank">&copy; Association of Train Operating Companies 2004</a></td></tr>';
						if (a_d != -1){
							var a_t = '<tr><td colspan="7" title="Problems" class="alert">'+divs[a_d].innerHTML+'</td></tr>';
							marker.openInfoWindowHtml("<table class=\"ldb_tbl\">"+st_title+a_t+data[0].innerHTML+copy_text+copy_2+"</table>");
						}else
							marker.openInfoWindowHtml("<table class=\"ldb_tbl\">"+st_title+data[0].innerHTML+copy_text+copy_2+"</table>");
						lo.innerHTML = "LDB Data Loaded";
					}else{
						xmlDoc = GXml.parse(request.responseText);
						if (xmlDoc.documentElement){
							var data = xmlDoc.documentElement.getElementsByTagName('table');
							var divs = xmlDoc.documentElement.getElementsByTagName('div');
							var a_d	= -1;
							for (var i=0; i< divs.length; i++){
								if (divs[i].className == "maint_message"){
									a_d	= i;
									i = divs.length;
								}
							}
							var st_title  	= '<td colspan="7" title="Data Source" class="st_name">'+name+'</td></tr>';
							var copy_text  	= '<td colspan="7" title="Data Source" class="copy_text"><a href="http://www.livedepartureboards.co.uk/virgintrains/summary.aspx?T='+code+'" target="_blank">Data from Virgin Trains Live Departure Boards</a></td></tr>';
							var copy_2		= '<td colspan="7" title="Data Source" class="copy_text"><a href="http://www.atoc.org" target="_blank">&copy; Association of Train Operating Companies 2004</a></td></tr>';
							if (a_d != -1){
								var a_t = '<tr><td colspan="7" title="Problems" class="alert">'+divs[a_d].innerHTML+'</td></tr>';
								marker.openInfoWindowHtml("<table class=\"ldb_tbl\">"+st_title+a_t+data[0].innerHTML+copy_text+copy_2+"</table>");
							}else
								marker.openInfoWindowHtml("<table class=\"ldb_tbl\">"+st_title+data[0].innerHTML+copy_text+copy_2+"</table>");
							lo.innerHTML = "LDB Data Loaded";
						}else{
							xmlDoc = request.responseText;
							lo.innerHTML = "LDB Function not fully supported in this Web Browser";
							marker.openInfoWindowHtml(xmlDoc);
						}
					}
				}else{
					lo.innerHTML = "ERROR loading LDB Data";
				}
			}
		}
		request.send(null);
	}
	
	function createTrafficMarker(lat, lon, html, src, type, start, stp,  image, alat, alon) {
		var icon = new GIcon();
		icon.image 		= "<?php echo $img_url;?>"+image+".png";
		icon.shadow 	= "<?php echo $img_url;?>weather/trans.png";
		icon.iconSize 	= new GSize(19, 40);
		icon.shadowSize = new GSize(1, 1);
		icon.iconAnchor = new GPoint(6, 20);
		icon.infoWindowAnchor = new GPoint(5, 1);
		var point = new GPoint(lon, lat);
  		var marker = new GMarker(point,icon);
		var info_html = '<div style="width: 200px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small;"><img style="float:left" src="<?php echo $img_url;?>'+type+'.png" alt="img" />'+html+'</div><strong>Source:'+src+'</strong>';
  		info_html += '<div>Started @ '+start+'</div><div>End @ '+stp+'</div>';
		info_html += '<div class="weather_link"><a href="javascript:zoomTo('+lat+','+lon+', 3);">Zoom In</a></div>';
		info_html += '<div class="weather_link"><a href="javascript:zoomTo('+alat+','+alon+', 7);">Zoom Out to Area</a></div>';
		info_html += '<div class="weather_link"><a href="javascript:zoomOut();">Zoom Out Fully</a></div>';
		GEvent.addListener(marker, "click", function() {
    		marker.openInfoWindowHtml(info_html);
  		});
		return marker;
	}
	
	function createTrafficCountyMarker(html, title, lat, lon){
		var point = new GPoint(lon,lat);
		var marker = new GMarker(point,tICon);
		var id = county_markers.length;
		var info_html 	= document.createElement("div");
		info_html.id = 'traffic_'+html+'';
		info_html.className = 'weather_link';
		info_html.innerHTML = '<a>'+title+'</a><br/><a href="javascript:goToTraffic(\''+html+'\', '+lat+', '+lon+', '+id+', \''+title+'\');" title="See Delays in '+title+'">Show Delays</a><br/><a href="javascript:zoomOut();" title="Show Delays in '+title+'">Zoom Out</a>';
		county_markers.push(info_html);
		GEvent.addListener(marker, "click", function() {
    		marker.openInfoWindow(county_markers[id]);
  		});
		return marker;
	}
	
	function onLoad(){
		alert("An Update to this site is now available\n" +
			"Please go to http://bbc.blueghost.co.uk");
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
					
			if (weather){
				setupWeather();
			}
			
			if (rail){
				setupRail();
			}
			
			if (traffic){
				setupTraffic();
			}
			
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
	
	function setupTraffic(){
		
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
		}else{
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
			
			traffic_setup = true;
		}
	}
	
	function setupRail(){
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
		
		if (!ldbICon){
			//ldb_marker
			ldbICon	= new GIcon();
			ldbICon.image = "<?php echo $img_url;?>mm_20_green.png";
			ldbICon.shadow = "<?php echo $img_url;?>mm_20_shadow.png";
			ldbICon.iconSize = new GSize(12, 20);
			ldbICon.shadowSize = new GSize(22, 20);
			ldbICon.iconAnchor = new GPoint(0, 0);
			ldbICon.infoWindowAnchor = new GPoint(-5, -5);
		}		
		
		reloadRail(); //call only needed here
	}
	
	/*reload() moved to common.js*/
	
	/*reloadWeather() moved to common.js*/
	
	/*reloadRail() moved to common.js*/	
	
	/*doLDB() moved to common.js*/	
	
	function goToTraffic(loc, lat, lon, id, title){
		if (id == 0){/*MOTORWAYS*/
			var m_link = document.getElementById('m_link');
			m_link.innerHTML 	=  'Hide Motorway Delays';
			m_link.href			=  'javascript:removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');';
			m_link.title		=	'Hide Delays in '+title;
			county_markers[id].innerHTML 	=  '<a id="m_link" href="javascript:removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');" title="Hide Delays in '+title+'">Hide Motorway Delays</a>';
			//map.centerAndZoom(cen, 11);
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
			county_markers[id].innerHTML 	=  '<a id="m_link" href="javascript:goToTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');" title="Show Delays in '+title+'">Show Motorway Delays</a>';
			m_visible = false;
		}else{
			county_markers[id].innerHTML =  '<a>'+title+'</a><br/><a href="javascript:goToTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');" title="Show Delays in '+title+'">Show Delays</a><br/><a href="javascript:zoomOut();" title="Zoom Out">Zoom Out</a>';
		}
	}
	
	/*reloadTraffic() moved to common.js*/
		
	function goToSidebar(){
		document.location.href	= sb_site+genPermalink();
	}

//]]>
</script>
</head>
<body onLoad="onLoad()" onUnload="unLoad()">
<div id="selector">
	<div id="wc" class="choiceS"	title="Click to Show Weather Overlay" 					onmouseover="highlight('wc', 'choiceH');"	onfocus="javascript:this.onmouseover;" 	onmouseout="unhighlight('wc', 'choice');" 	onblur="javascript:this.onmouseout;"	onclick="javascript:goTo('weather');">Weather</div>
	<div id="rc" class="choice"  	title="Click to Show Rail Overlay" 						onmouseover="javascript:highlight('rc', 'choiceH');" 	onfocus="javascript:this.onmouseover;"	onmouseout="unhighlight('rc', 'choice');" 	onblur="javascript:this.onmouseout;"	onclick="javascript:goTo('rail');">Rail</div>
	<div id="tc" class="choice"  	title="Click to Show Road Traffic Overlay" 				onmouseover="javascript:highlight('tc', 'choiceH');" 	onfocus="javascript:this.onmouseover;"	onmouseout="unhighlight('tc', 'choice');" 	onblur="javascript:this.onmouseout;"	onclick="javascript:goTo('traffic');">Traffic</div>
	<div id="pc" class="choiceP" 	title="Click to get a Permanant Link to this map view" 	onmouseover="javascript:highlight('pc', 'choiceHP');" 	onfocus="javascript:this.onmouseover;"	onmouseout="unhighlight('pc', 'choiceP');" 	onblur="unhighlight('pc', 'choiceP');"	onclick="javascript:getPermalink();">Permalink</div>
	<div id="rv" class="choiceP"	title="Reset the Viewpoint back to the original position" onMouseOver="javascript:highlight('rv', 'choiceHP');"	onfocus="javascript:this.onmouseover;"	onmouseout="unhighlight('rv', 'choiceP');" 	onblur="javascript:this.onmouseout;"	onclick="javascript:zoomOut();">Reset Viewpoint</div>
	<div id="sb" class="choiceP" 	title="Click to Add a Sidebar to the map" 				onmouseover="javascript:highlight('sb', 'choiceHP');" 	onfocus="javascript:this.onmouseover;"	onmouseout="unhighlight('sb', 'choiceP');" 	onblur="javascript:this.onmouseout;"	onclick="javascript:goToSidebar();">Add Sidebar</div>
	<div id="up" class="choiceU" 	title="Click here to load the latest version of this site" onMouseOver="javascript:highlight('up', 'choiceHU');" 	onmouseout="unhighlight('up', 'choiceU');" 	onclick="javascript:document.location.href='http://bbc.blueghost.co.uk';">Update Available</div>
	<div id="up" class="choiceU" 	title="Click here to get this data in Google Earth" onMouseOver="javascript:highlight('up', 'choiceHU');" onMouseOut="unhighlight('up', 'choiceU');" onClick="javascript:document.location.href='http://bbc.blueghost.co.uk/earth/';"><img src="images/google_earth_feed.gif" width="93" height="17" alt="Google Earth" title="Click here to get this data in Google Earth" /></div>
	<div id="hc" class="choiceR" 	title="Click to see help" 								onmouseover="javascript:highlight('hc', 'choiceHR');" 	onfocus="javascript:this.onmouseover;"	onmouseout="unhighlight('hc', 'choiceR');" 	onblur="javascript:this.onmouseout;"	onclick="javascript:openHelp();">Help</div>
	<div id="ac" class="choiceR" 	title="Click to see detail about this site" 			onmouseover="javascript:highlight('ac', 'choiceHR');" 	onfocus="javascript:this.onmouseover;"	onmouseout="unhighlight('ac', 'choiceR');" 	onblur="javascript:this.onmouseout;"	onclick="javascript:openAbout();">About</div>
</div>
<div id="sub_selector">
	<div id="static">Some Text</div>
	<div id="loading">Loading ABC...</div>
</div>
<div id="weather_map"></div>
<!--Access Keys-->
<a id="ak_w" accesskey="w" href="javascript:goTo('weather');" title="Show Weather"></a>
<a id="ak_t" accesskey="t" href="javascript:goTo('traffic');" title="Show Road Traffic"></a>
<a id="ak_r" accesskey="r" href="javascript:goTo('rail');" title="Show Rail"></a>
<a id="ak_p" accesskey="p" href="javascript:getPermalink();" title="Permalink"></a>
<a id="ak_reset" accesskey="x" href="javascript:zoomOut();" title="Reset Viewpoint"></a>
<a id="ak_h" accesskey="h" href="javascript:openHelp();" title="Help"></a>
<a id="ak_a" accesskey="a" href="javascript:openAbout();" title="About"></a>
</body>
</html>