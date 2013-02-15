<?php
	include('includes/paths.php');
	//center locs
	$lat		= 54.31652;//52.7; //to be set from xml
	$lon		= -2.37305;//-2.52; //to be set from xml
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
<link rel="stylesheet" type="text/css" href="style33.css" />
<title>BlueGhost UK Weather, with weather.com&reg; &amp; backstage.bbc.co.uk</title>
<script src="http://maps.google.com/maps?file=api&amp;v=1&amp;key=<?php echo $googleKey;?>" type="text/javascript"></script>
<script src="javascript/common_cricket.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
	
	var weather 	= <?php echo $weather;?>;
	var rail 		= <?php echo $rail;?>;
	var traffic 	= <?php echo $traffic;?>;
	
	var show_pop_ups	= <?php echo $pop_ups;?>;
	var ldb_visible		= <?php echo $show_ldb;?>;
	
	var m_visible		= <?php echo $show_mw;?>;
	
	function getPermalink(){
		document.location.href = 'http://bbc.blueghost.co.uk/cricket.php?' + genPermalink();
	}
		
	function createWeatherMarker(point, img, size, name, temp, time, links ,id) {
		var img_url = "<?php echo $img_url;?>weather/"+size+"x"+size+"/"+img+".png";
		var icon	= makeIcon(img_url, size, size);
  		var marker 	= new GMarker(point,icon);
		var title 	= 'Weather For '+name+'<img width="64" height="64" src="<?php echo $img_url;?>weather/64x64/'+img+'.png" alt="Weather img" />';
		var text	= temp+'&deg;C @ '+time+'<br />';
		text		+='<strong>Featured on Weather.com</strong><br />';
		var linkst	='<a href="<?php echo $bbc_s;?>'+name+'" target="_blank">More @ BBC Weather</a>';
		
		var html = '<strong>'+name+'</strong><img src="<?php echo $img_url;?>weather/64x64/'+img+'.png" alt="Weather img" /><br/><span style="font-size:small;">'+temp+'&deg;C @ '+time+'</span>';
		html += '<div class="weather_link"><a href="<?php echo $bbc_s;?>'+name+'" target="_blank">More @ BBC Weather</a>';
		html += '<div class="weather_link">Featured on weather.com&reg;</div>';
		
		for (var i=0; i < links.length; i++){
			var a_text	= '<div class="weather_link" style="text-align:left;">'+links[i]+'</div>';
			text += a_text;
			html += a_text;
		}
				
  		GEvent.addListener(marker, "click", function() {
    		setDesc(title, text);
			setRoadLinks(linkst);
			if (show_pop_ups)
				marker.openInfoWindowHtml(html);
			last_open = id; //refers to mkrs[]
  		});
		if ( (first_run) && (id == <?php echo $marker_id;?>) ){
			GEvent.trigger(marker, "click");
			first_run = false;
		}
		return marker;
	}
	
	function createRailMarker(point, html, src, type, start, stp,  image, id) {
		var img_url = "<?php echo $img_url;?>"+image+".png";
		var icon	= makeIcon(img_url, 19, 40);
  		var marker = new GMarker(point,icon);
		var title 	= '<img style="float:left" src="<?php echo $img_url;?>'+type+'.png" alt="img" />';
		var text	= html+'<a href="http://bbc.co.uk/travel" target="_blank" title="Go to the '+src+' website">Source:'+src+'</a>';
		text		+='<div>Started @ '+start+'</div><div>End @ '+stp+'</div>';
		var links		='<div class="weather_link"><a href="javascript:zoomOut();">Zoom Out</a>';
		
		var html = '<div style="width: 200px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small;"><img style="float:left" src="<?php echo $img_url;?>'+type+'.png" alt="img" />'+html+'</div><strong>Source:'+src+'</strong>';
  		html += '<div>Started @ '+start+'</div><div>End @ '+stp+'</div>';
		html += links;
		
		GEvent.addListener(marker, "click", function() {
			map.centerAndZoom(point, 2);
    		setDesc(title, text);
			setRoadLinks(links);
			if (show_pop_ups)
				marker.openInfoWindowHtml(html);
			//last_open = id; //refers to rail_markers - MAY CHANGE DURING RELOADS HENCE AVOID
  		});
		return marker;
	}
	
	function createLDBMarker(point, name, code, id) {
  		var marker = new GMarker(point, ldbICon);
		var request = GXmlHttp.create();
		var links		='<div class="weather_link"><a href="http://www.livedepartureboards.co.uk/ldb/summary.aspx?T='+code+'">Live Data from National Rail</a>';
		
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
						var copy_text  	= '<td colspan="7" title="Data Source" class="copy_text"><a href="http://www.livedepartureboards.co.uk/ldb/summary.aspx?T='+code+'" target="_blank">Data from National Rail Live Departure Boards</a></td></tr>';
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
							var copy_text  	= '<td colspan="7" title="Data Source" class="copy_text"><a href="http://www.livedepartureboards.co.uk/ldb/summary.aspx?T='+code+'" target="_blank">Data from National Rail Live Departure Boards</a></td></tr>';
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
	
	function createTrafficMarker(lat, lon, html, src, type, start, stp,  image, alat, alon, id) {
		var img_url = "<?php echo $img_url;?>"+image+".png";
		var icon	= makeIcon(img_url, 19, 40);
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
		var lk	= document.getElementById('cLinks');
		lk.style.display	= "none";
		resetRoadLinks();
		var title	= "Weather Data";
		var text	= "Weather Data provided by weather.com &reg;";
		setDesc(title, text);
		
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
		var lk	= document.getElementById('cLinks');
		lk.style.display	= "";
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
			//setFooterElement(mw);
		}else{
			//icon for each county
			if (!tICon){
				var img_url 	= "<?php echo $img_url;?>mm_20_white.png";
				var img_shadow	= "<?php echo $img_url;?>mm_20_shadow.png";
				tICon	= makeIcon(img_url, 12, 20);
				tICon.shadow = img_shadow;
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
			//setFooterElement(mw);*/
			
			traffic_setup = true;
		}
	}
	
	function setupRail(){
		var lk	= document.getElementById('cLinks');
		lk.style.display	= "none";
		resetRoadLinks();
		var title	= "Rail Data";
		var text	= 'Rail Data supported by <a href="http://backstage.bbc.co.uk" target="_blank" title="Go to the BBC Backstage Website">backstage.bbc.co.uk</a>';
		text		+='<br />Live Departure Board Info from <a href="http://www.nationalrail.co.uk/ldb/" target="_blank" title="Go to National Rail LDB Site">National Rail</a> and <a href="http://www.atoc.org/" target="_blank" title="Go to the The Association of Train Operating Companies Website">&copy; <dfn title="Association of Train Operating Companies">ATOC</dfn> 2005</a>';
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
		
		if (!ldbICon){
			var img_url 	= "<?php echo $img_url;?>mm_20_green.png";
			var img_shadow	= "<?php echo $img_url;?>mm_20_shadow.png";
			ldbICon	= makeIcon(img_url, 12, 20);
			ldbICon.shadow = img_shadow;
			//ldb_marker
		}		
		
		reloadRail(); //call only needed here
	}
	
	function reload(){
		
		updateDisplay();
		
		var lo	= document.getElementById("loading");
		
		if (weather){
			lo.innerHTML = "Loading Weather Data...";
			reloadWeather();
			resetFooter();
		}
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
	
	function reloadRail(){
		//ldb options
		/*var info_html 	= document.createElement("div");
		info_html.id = 'rail_ldb';
		info_html.className = 'weather_link';
		if (ldb_visible){
			info_html.innerHTML = '<a id="rail_ldb_changer" style="display:block;" href="javascript:showHideLDB();" title="Hide Live Data for Stations">Hide Live Departure Boards</a><a style="display:block;" href="http://pritch.blueghost.co.uk/wordpress/?page_id=19" target="_blank" title="Add Your Station">Add Your Station</a>';
		}else{
			info_html.innerHTML = '<a id="rail_ldb_changer" style="display:block;" href="javascript:showHideLDB();" title="Show Live Data for Stations">Show Live Departure Boards</a><a style="display:block;" href="http://pritch.blueghost.co.uk/wordpress/?page_id=19" target="_blank" title="Add Your Station">Add Your Station</a>';
		}
		setFooterElement(info_html);*/
		if (rail_setup){
			if (ldb_indicator != -1){
				for (var i=0; i< rail_markers.length && i < ldb_indicator; i++)
					map.addOverlay(rail_markers[i]);
			}else{
				for (var i=0; i< rail_markers.length; i++)
					map.addOverlay(rail_markers[i]);
			}
			var lo	= document.getElementById("loading");
			lo.innerHTML = "Loaded Rail Data";
			if (ldb_visible)
				doLDB();
		}else{
			var url		= "<?php echo $site_url;?>travel_data/railways.xml";
			var status	= 'Loading Rail Data ...';
			doXMLHTTPRequest(reloadRailXML, url, status);
			rail_setup	= true;
		}
	}
	
	function doLDB(){
		if (ldb_visible){
			if (ldb_loaded){
				for (var i=ldb_indicator; i< rail_markers.length ; i++)
					map.addOverlay(rail_markers[i]);
				var lo	= document.getElementById("loading");
				lo.innerHTML = "Loaded LDB Data";
			}else{
				var url		= "<?php echo $site_url;?>ldb_data/stations.xml";
				var status	= 'Loading LDB Data ...';
				doXMLHTTPRequest(doLDBXML, url, status);
				ldb_loaded = true;
			}
		}else{
			if (ldb_indicator != -1){
				for (var i=ldb_indicator; i< rail_markers.length ; i++)
					map.removeOverlay(rail_markers[i]);
			}
		}
	}
		
	
	function goToTraffic(loc, lat, lon, id, title){
		var iframe	= this.frames.frames["cLinksFrame"].document.getElementById(id); 
		iframe.href	= 'javascript:parent.removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');'
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
			
		var iframe	= frames['cLinksFrame'];
		var iframe	= this.frames.frames["cLinksFrame"].document.getElementById(id); 
		iframe.href	= 'javascript:parent.goToTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');';
		
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
		document.location.href	= 'http://bbc.blueghost.co.uk/site24.php?'+genPermalink();
	}
	
//]]>
</script>
</head>
<body onLoad="onLoad()" onUnload="unLoad()">
<div id="selector">
	<div id="wc" class="choiceS">Cricket</div>
	<div id="rc" class="choice"  	title="Click to Show Rail Overlay" 						onmouseover="javascript:highlight('rc', 'choiceH');" 	onmouseout="unhighlight('rc', 'choice');" 	onclick="javascript:goTo('football');">Football</div>
	<div id="tc" class="choice"  	title="Click to Show Road Traffic Overlay" 				onmouseover="javascript:highlight('tc', 'choiceH');" 	onmouseout="unhighlight('tc', 'choice');" 	onclick="javascript:goTo('site33');">UK Data </div>
	<div id="pc" class="choiceP" 	title="Click to get a Permanant Link to this map view" 	onmouseover="javascript:highlight('pc', 'choiceHP');" 	onmouseout="unhighlight('pc', 'choiceP');" 	onclick="javascript:getPermalink();">Permalink</div>
	<div id="rv" class="choiceP"	title="Reset the Viewpoint back to the original position" onMouseOver="javascript:highlight('rv', 'choiceHP');"	onmouseout="unhighlight('rv', 'choiceP');" 	onclick="javascript:zoomOut();">Reset Viewpoint</div>
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
		<div id="footer_text"></div>
		<div id="traffic_links"></div>
	</div>
	<div id="cLinks" style="display::none; ">
		<hr class="divider" />
		<iframe id="cLinksFrame" name="cLinksFrame" src="travel_data/locations.html" height="40%" width="80%" frameborder="0"></iframe>
	</div>
</div>
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