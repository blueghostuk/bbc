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
<link rel="stylesheet" type="text/css" href="style23.css" />
<title>BlueGhost UK Weather, with weather.com&reg; &amp; backstage.bbc.co.uk</title>
<script src="http://maps.google.com/maps?file=api&amp;v=1&amp;key=<?php echo $googleKey;?>" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
	var county_markers = [];
	var loc;
	var cen;
	var map;
	var mkrs 		= [];
	var wICon;
	var tICon;
	var ldbICon;
	var tPlace;
	var tLat;
	var tLon;
	var wMarker;
	var widescreen 	= false;
	var day 		= 0;
	var weekday 	= new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	var month 		= new Array("Jan","Feb","Mar","Apr","May","June","July", "Aug", "Sept", "Oct", "Nov", "Dec");
	var weather 	= <?php echo $weather;?>;
	var rail 		= <?php echo $rail;?>;
	var traffic 	= <?php echo $traffic;?>;
	var traffic_setup	= false;
	var t_markers 	= [];
	var lastXMLdoc;
	var lastWUrl;
	var rail_loaded = false;
	var rail_setup	= false;
	var rail_markers = [];
	var traffic_markers = [];
	var traffic_data	= [];
	var traffic_start	= [];
	var traffic_end		= [];
	var show_pop_ups	= <?php echo $pop_ups;?>;
	var ldb_visible		= <?php echo $show_ldb;?>;
	var ldb_loaded		= false;
	var v				= 0;
	var last_open		= -1;
	var first_run		= false;
	var mw;
	var ldb_indicator	= -1;
	var m_visible		= <?php echo $show_mw;?>;
	
	function unLoad(){
		for (var i=0; i < county_markers.length; i++)
			county_markers[i] = null;
		county_markers=null;
		for (var i=0; i < mkrs .length; i++)
			mkrs [i] = null;
		mkrs =null;
		for (var i=0; i < t_markers.length; i++)
			t_markers[i] = null;
		t_markers=null;
		for (var i=0; i < rail_markers.length; i++)
			rail_markers[i] = null;
		rail_markers=null;
		for (var i=0; i < traffic_markers.length; i++)
			traffic_markers[i] = null;
		traffic_markers=null;
		for (var i=0; i < traffic_data.length; i++)
			traffic_data[i] = null;
		traffic_data=null;
		for (var i=0; i < traffic_start.length; i++)
			traffic_start[i] = null;
		traffic_start=null;
		for (var i=0; i < traffic_end.length; i++)
			traffic_end[i] = null;
		traffic_end=null;
	}
	
	function genPermalink(){
		var type	=	"";
		if (rail){
			if (ldb_visible){
				type 	= 	"rail&ldb=y";
				if (last_open != -1)
					type 	+=	"&marker="+last_open;
			}else
				type 	= 	"rail";
			
		}
		if (weather){
			type	= "weather";
			if (last_open != -1)
				type 	+=	"&marker="+last_open;
		}
		if (traffic){
			type	= "traffic";
			if (m_visible)
				type	+= "&mw=y";
		}
		return p_link = 'type='+type+'&lon='+map.getCenterLatLng().x+'&lat='+map.getCenterLatLng().y+'&zl='+map.getZoomLevel();
	}
	
	function getPermalink(){
		document.location.href = 'http://bbc.blueghost.co.uk/site23.php?' + genPermalink();
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
		var html = '<strong>'+name+'</strong><img src="<?php echo $img_url;?>weather/64x64/'+img+'.png" alt="Weather img" /><br/><span style="font-size:small;">'+temp+'�C @ '+time+'</span>';
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
		var links		='<div class="weather_link"><a href="http://www.livedepartureboards.co.uk/ldb/summary.aspx?T='+code+'">Live Data from National Rail</a>';
		
		GEvent.addListener(marker, "click", function() {
			map.centerAndZoom(point, 5);
			loadLDB(marker, name, code);
			//if (show_pop_ups)
				//marker.openInfoWindowHtml(html);
			last_open = id; //refers to rail_markers
  		});
		//alert("Given id = " + id + " , wanted id = " + <?php echo $marker_id;?>);
		if ( (first_run) && (id == <?php echo $marker_id;?>) ){
			GEvent.trigger(marker, "click");
			first_run = false;
		}
		return marker;		
	}
	
	function loadLDB(marker, name, code){
		var request = GXmlHttp.create();
		var url		= "<?php echo $site_url;?>includes/rail.php?ldb="+code+"";
		var lo	= document.getElementById("loading");
		lo.innerHTML = "Loading LDBData ...";// from "+url+" ...";
		request.open("GET", url	, true);
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				if (request.status == 200) {
					var xmlDoc 	= request.responseXML;
					if (xmlDoc.documentElement){
						//alert("loaded");
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
						//alert("Function not available in this Web Browser");
						lo.innerHTML = "LDB Function not fully supported in this Web Browser";
						xmlDoc = request.responseText;
						marker.openInfoWindowHtml(xmlDoc);
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
		//place TWC logo
		wICon	= new GIcon();
		wICon.image = "<?php echo $img_url;?>weather/TWClogo_64px.png";
		wICon.shadow = "<?php echo $img_url;?>weather/trans.png";
		wICon.iconSize = new GSize(64, 64);
		wICon.shadowSize = new GSize(1, 1);
		wICon.iconAnchor = new GPoint(32, 0);
		wICon.infoWindowAnchor = new GPoint(-5, -5);
		
		var bounds = map.getBoundsLatLng();
		var width = bounds.maxX - bounds.minX;
		var height = bounds.maxY - bounds.minY;
		if (widescreen)
			var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.85));
		else
			var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.85));
		wMarker = new GMarker(point,wICon);
		map.addOverlay(wMarker);
		
		GEvent.addListener(wMarker, "click", function() {
			var l_text = "<div class=\"weather_link\">Weather data provided by <br /><a href=\"http://www.weather.com/?prod=xoap&par=<?php echo $partnerID;?>\" title=\"Go to weather.com\" target=\"_blank\">weather.com &reg;</a></div>";
    		wMarker.openInfoWindowHtml(l_text);
		});
	
		/*GEvent.addListener(map, "move", function() {
    		map.removeOverlay(wMarker);
			var bounds = map.getBoundsLatLng();
			var width = bounds.maxX - bounds.minX;
			var height = bounds.maxY - bounds.minY;
			if (widescreen)
				var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.85));
			else
				var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.85));
			wMarker = new GMarker(point,wICon);
			map.addOverlay(wMarker);
			GEvent.addListener(wMarker, "click", function() {
				var l_text = "<div class=\"weather_link\">Weather data provided by <br /><a href=\"http://www.weather.com/?prod=xoap&par=<?php echo $partnerID;?>\" title=\"Go to weather.com\" target=\"_blank\">weather.com &reg;</a></div>";
    			wMarker.openInfoWindowHtml(l_text);
			});
  		});*/
		
		GEvent.addListener(map, "moveend", function(){
			map.removeOverlay(wMarker);
			var bounds = map.getBoundsLatLng();
			var width = bounds.maxX - bounds.minX;
			var height = bounds.maxY - bounds.minY;
			if (widescreen)
				var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.85));
			else
				var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.85));
			wMarker = new GMarker(point,wICon);
			map.addOverlay(wMarker);
			GEvent.addListener(wMarker, "click", function() {
				var l_text = "<div class=\"weather_link\">Weather data provided by <br /><a href=\"http://www.weather.com/?prod=xoap&par=<?php echo $partnerID;?>\" title=\"Go to weather.com\" target=\"_blank\">weather.com &reg;</a></div>";
    			wMarker.openInfoWindowHtml(l_text);
			});
			reload();
		});
		
		GEvent.addListener(map, "zoom", function() {
			reload();
			map.removeOverlay(wMarker);
			var bounds = map.getBoundsLatLng();
			var width = bounds.maxX - bounds.minX;
			var height = bounds.maxY - bounds.minY;
			if (widescreen)
				var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.85));
			else
				var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.85));
			wMarker = new GMarker(point,wICon);
			map.addOverlay(wMarker);
			GEvent.addListener(wMarker, "click", function() {
				var l_text = "<div class=\"weather_link\">Weather data provided by <br /><a href=\"http://www.weather.com/?prod=xoap&par=<?php echo $partnerID;?>\" title=\"Go to weather.com\" target=\"_blank\">weather.com &reg;</a></div>";
    			wMarker.openInfoWindowHtml(l_text);
			});
  		});
	}
	
	function setupTraffic(){
		
		//place backstage logo
		wICon	= new GIcon();
		wICon.image = "<?php echo $img_url;?>bbc_v2.png";
		wICon.shadow = "<?php echo $img_url;?>weather/trans.png";
		wICon.iconSize = new GSize(350, 30);
		wICon.shadowSize = new GSize(1, 1);
		wICon.iconAnchor = new GPoint(0, 0);
		wICon.infoWindowAnchor = new GPoint(175, -5);
		
		//add backstage logo
		var bounds = map.getBoundsLatLng();
		var width = bounds.maxX - bounds.minX;
		var height = bounds.maxY - bounds.minY;
		if (widescreen)
			var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
		else
			var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
		wMarker = new GMarker(point,wICon);
		map.addOverlay(wMarker);
		
		//add each county
		if (traffic_setup){
			for(var i = 0; i< t_markers.length; i++)
				map.addOverlay(t_markers[i]);
		}else{
			//icon for each county
			tICon	= new GIcon();
			tICon.image = "<?php echo $img_url;?>mm_20_white.png";
			tICon.shadow = "<?php echo $img_url;?>mm_20_shadow.png";
			tICon.iconSize = new GSize(12, 20);
			tICon.shadowSize = new GSize(22, 20);
			tICon.iconAnchor = new GPoint(0, 0);
			tICon.infoWindowAnchor = new GPoint(-5, -5);
			
    		var request = GXmlHttp.create();
			var url		= "<?php echo $site_url;?>travel_data/locations.xml";
			var lo	= document.getElementById("loading");
			lo.innerHTML = "Loading Traffic Data ...";//from "+url+" ...";
			request.open("GET", url	, true);
			request.onreadystatechange = function() {
				if (request.readyState == 4) {
					if (request.status == 200) {
						var xmlDoc 	= request.responseXML;
						var markers = xmlDoc.documentElement.getElementsByTagName("t_alert");
						for (var i=0; i< markers.length ; i++){
							var lat = markers[i].getAttribute("lat");
							var lon = markers[i].getAttribute("lon");
							var marker = createTrafficCountyMarker(markers[i].getAttribute("location"), markers[i].getAttribute("title"), lat, lon, t_markers.length);
							map.addOverlay(marker);
							t_markers.push(marker);
						}
						var lo	= document.getElementById("loading");
						lo.innerHTML = "Loaded Traffic Data";
					}else{
						var lo	= document.getElementById("loading");
						lo.innerHTML = lo.innerHTML + ' - ERROR: ' + request.status;
					}
				}
			}
			
			//set footer
			var id = county_markers.length;
			mw 	= document.createElement("div");
			mw.id = 'traffic_motorways';
			mw.className = 'weather_link';
			mw.innerHTML = '<a id="m_link" href="javascript:goToTraffic(\'motorways\', \'0\', \'0\', '+id+', \'UK Motorways\');" title="See Delays on the Motorways">Show Motorway Delays</a>';
			county_markers.push(mw);
			
			traffic_setup = true;
			request.send(null);
		}
		
		//onmove for backstage logo
		//GEvent.addListener(map, "move", function() {
		GEvent.addListener(map, "moveend", function() {
    		map.removeOverlay(wMarker);
			var bounds = map.getBoundsLatLng();
			var width = bounds.maxX - bounds.minX;
			var height = bounds.maxY - bounds.minY;
			if (widescreen)
				var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
			else
				var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
			wMarker = new GMarker(point,wICon);
			map.addOverlay(wMarker);
			GEvent.addListener(wMarker, "click", function() {
				var l_text = "<div class=\"weather_link\">supported by <br /><a href=\"http://backstage.bbc.co.uk\" target=\"_blank\">backstage.bbc.co.uk</a></div>";
    			wMarker.openInfoWindowHtml(l_text);
			});
  		});
		
		//onzoom for backstage logo
		GEvent.addListener(map, "zoom", function() {
			map.removeOverlay(wMarker);
			var bounds = map.getBoundsLatLng();
			var width = bounds.maxX - bounds.minX;
			var height = bounds.maxY - bounds.minY;
			if (widescreen)
				var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
			else
				var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
			wMarker = new GMarker(point,wICon);
			map.addOverlay(wMarker);
			GEvent.addListener(wMarker, "click", function() {
				var l_text = "<div class=\"weather_link\">supported by <br /><a href=\"http://backstage.bbc.co.uk\" target=\"_blank\">backstage.bbc.co.uk</a></div>";
    			wMarker.openInfoWindowHtml(l_text);
			});
  		});
		
		//onclick for backstage logo
		GEvent.addListener(wMarker, "click", function() {
			var l_text = "<div class=\"weather_link\">supported by <br /><a href=\"http://backstage.bbc.co.uk\" target=\"_blank\">backstage.bbc.co.uk</a></div>";
    		wMarker.openInfoWindowHtml(l_text);
		});
	}
	
	function setupRail(){
		//place backstage logo
		wICon	= new GIcon();
		wICon.image = "<?php echo $img_url;?>bbc_v2.png";
		wICon.shadow = "<?php echo $img_url;?>weather/trans.png";
		wICon.iconSize = new GSize(350, 30);
		wICon.shadowSize = new GSize(1, 1);
		wICon.iconAnchor = new GPoint(0, 0);
		wICon.infoWindowAnchor = new GPoint(-5, -5);
		
		//ldb_marker
		if (!ldbICon){
			ldbICon	= new GIcon();
			ldbICon.image = "<?php echo $img_url;?>mm_20_green.png";
			ldbICon.shadow = "<?php echo $img_url;?>mm_20_shadow.png";
			ldbICon.iconSize = new GSize(12, 20);
			ldbICon.shadowSize = new GSize(22, 20);
			ldbICon.iconAnchor = new GPoint(0, 0);
			ldbICon.infoWindowAnchor = new GPoint(-5, -5);
		}
		
		var bounds = map.getBoundsLatLng();
		var width = bounds.maxX - bounds.minX;
		var height = bounds.maxY - bounds.minY;
		if (widescreen)
			var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
		else
			var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
		wMarker = new GMarker(point,wICon);
		map.addOverlay(wMarker);
			
		GEvent.addListener(wMarker, "click", function() {
			var l_text = "<div class=\"weather_link\">supported by <br /><a href=\"http://backstage.bbc.co.uk\" target=\"_blank\">backstage.bbc.co.uk</a></div>";
    		wMarker.openInfoWindowHtml(l_text);
		});
	
		//GEvent.addListener(map, "move", function() {
		GEvent.addListener(map, "moveend", function() {
    		map.removeOverlay(wMarker);
			var bounds = map.getBoundsLatLng();
			var width = bounds.maxX - bounds.minX;
			var height = bounds.maxY - bounds.minY;
			if (widescreen)
				var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
			else
				var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
			wMarker = new GMarker(point,wICon);
			map.addOverlay(wMarker);
			GEvent.addListener(wMarker, "click", function() {
				var l_text = "<div class=\"weather_link\">supported by <br /><a href=\"http://backstage.bbc.co.uk\" target=\"_blank\">backstage.bbc.co.uk</a></div>";
    			wMarker.openInfoWindowHtml(l_text);
			});
  		});
		
		GEvent.addListener(map, "zoom", function() {
			map.removeOverlay(wMarker);
			var bounds = map.getBoundsLatLng();
			var width = bounds.maxX - bounds.minX;
			var height = bounds.maxY - bounds.minY;
			if (widescreen)
				var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
			else
				var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
			wMarker = new GMarker(point,wICon);
			map.addOverlay(wMarker);
			GEvent.addListener(wMarker, "click", function() {
				var l_text = "<div class=\"weather_link\">supported by <br /><a href=\"http://backstage.bbc.co.uk\" target=\"_blank\">backstage.bbc.co.uk</a></div>";
    			wMarker.openInfoWindowHtml(l_text);
			});
  		});
		
		reloadRail(); //call only needed here
	}
	
	function reload(){
	
		updateDisplay();
	
		var lo	= document.getElementById("loading");
		
		if (weather){
			lo.innerHTML = "Loading Weather Data...";
			reloadWeather();
		}
		/*if (rail){
			lo.innerHTML = "Loading Rail Data...";
			reloadRail();
		}*/
	}
	
	function zoomOut(){
		map.centerAndZoom(cen, 11);
	}
	
	function zoomTo(lat, lon, size){
		if (lat == 0 && lon == 0){
			map.centerAndZoom(cen, 11);
		}else{
			var p = new GPoint(lon,lat);
			map.centerAndZoom(p, size);
		}
	}
	
	function reloadWeather(){
		var tmp_mkrs	= [];
		var link_text	= [];
		var research	= true;
    	var request = GXmlHttp.create();
		var bounds = map.getBoundsLatLng();
		if (day == 0)
			var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=75&day=0";
		else
			var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=75&day=1";
		if (lastWUrl == url){/*don't need to fetch again*/
			research = false;
		}else
			lastWUrl = url;
			
		var lo	= document.getElementById("loading");
		lo.innerHTML = "Loading Weather Data ...";//+url+" ...";
		if (research){
			request.open("GET", url	, true);
			request.onreadystatechange = function() {
				if (request.readyState == 4) {
				if (request.status == 200) {
					lastXMLdoc = request.responseXML;
					var markers = lastXMLdoc.documentElement.getElementsByTagName("day");
					var links = lastXMLdoc.documentElement.getElementsByTagName("link");
					for (var i = 0; i < links.length; i++) {
						link_text.push('<a href="'+links[i].getAttribute("href")+'" target="_blank">'+links[i].getAttribute("title")+'</a>');
					}
					for (var i = 0; i < markers.length; i++) {
						var places = 0;
						if (markers[i].getAttribute("rel") == day){/*only add if relevant day*/
							var lin = [];
							var j2	= (places*3)+3;
							for (var j = (places*3); j < j2; j++) {
								lin.push(link_text[j]);
							}
							var lat = markers[i].getAttribute("lat");
							var lon = markers[i].getAttribute("lon");
							var name= markers[i].getAttribute("name");
							var point = new GPoint(lon,lat);
							var marker = createWeatherMarker(point, markers[i].getAttribute("icon"), '64', name, markers[i].getAttribute("temp"), markers[i].getAttribute("time"), lin, tmp_mkrs.length );
							tmp_mkrs.push(marker);
							places++;
						}
					}
					var tmp_2 = [];
					for (var i = 0; i < mkrs.length ; i++){
						var found = false
						for (var j=0; j < tmp_mkrs.length ; j++){
							if ( mkrs[i] == tmp_mkrs[j] ){
								found = true;
								tmp_mkrs[j] = null;
								tmp_2.push(mkrs[i]);
								j = tmp_mkrs.length;
							}
						}
						if (!found){
							map.removeOverlay(mkrs[i]);
						}
					}
					//add any more new markers
					for (var k = 0; k < tmp_mkrs.length; k++){
						if (tmp_mkrs[k] != null){
							map.addOverlay(tmp_mkrs[k]);
							tmp_2.push(tmp_mkrs[k]);
						}
					}
					mkrs	= tmp_2;
					var lo	= document.getElementById("loading");
					lo.innerHTML = "Loaded Weather Data";
				}}
			}
			request.send(null);
		}else{ /*use same xmldoc*/
			lo.innerHTML = "Loading Cached Data ...";
			var markers = lastXMLdoc.documentElement.getElementsByTagName("day");
			var links = lastXMLdoc.documentElement.getElementsByTagName("link");
			for (var i = 0; i < links.length; i++) {
				link_text.push('<a href="'+links[i].getAttribute("href")+'" target="_blank">'+links[i].getAttribute("title")+'</a>');
			}
			for (var i = 0; i < markers.length; i++) {
				var places = 0;
				if (markers[i].getAttribute("rel") == day){/*only add if relevant day*/
					var lin = [];
					var j2	= (places*3)+3;
					for (var j = (places*3); j < j2; j++) {
						lin.push(link_text[j]);
					}
					var lat = markers[i].getAttribute("lat");
					var lon = markers[i].getAttribute("lon");
					var name= markers[i].getAttribute("name");
					var point = new GPoint(lon,lat);
					var marker = createWeatherMarker(point, markers[i].getAttribute("icon"), '64', name, markers[i].getAttribute("temp"), markers[i].getAttribute("time"), lin, tmp_mkrs.length );
					tmp_mkrs.push(marker);
					places++;
				}
			}
			var tmp_2 = [];
			for (var i = 0; i < mkrs.length ; i++){
				var found = false
				for (var j=0; j < tmp_mkrs.length ; j++){
					if ( mkrs[i] == tmp_mkrs[j] ){
						found = true;
						tmp_mkrs[j] = null;
						tmp_2.push(mkrs[i]);
						j = tmp_mkrs.length;
					}
				}
				if (!found){
					map.removeOverlay(mkrs[i]);
				}
			}
			//add any more new markers
			for (var k = 0; k < tmp_mkrs.length; k++){
				if (tmp_mkrs[k] != null){
					map.addOverlay(tmp_mkrs[k]);
					tmp_2.push(tmp_mkrs[k]);
				}
			}
			mkrs	= tmp_2;
			var lo	= document.getElementById("loading");
			lo.innerHTML = "Loaded Weather Data";
		}
	}
	
	function reloadRail(){
		/*if (rail_loaded){
			var lo	= document.getElementById("loading");
			lo.innerHTML = "Loaded Rail Data";
		}else{*/
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
				//alert("got 3");
    			var request = GXmlHttp.create();
				var url		= "<?php echo $site_url;?>travel_data/railways.xml";
				var lo	= document.getElementById("loading");
				lo.innerHTML = "Loading Rail Data ...";//from "+url+" ...";
				request.open("GET", url	, true);
				request.onreadystatechange = function() {
					if (request.readyState == 4) {
						if (request.status == 200) {
							var xmlDoc 	= request.responseXML;
							var markers = xmlDoc.documentElement.getElementsByTagName("t_alert");
							for (var i=0; i< markers.length ; i++){
								var lat = markers[i].getAttribute("lat");
								var lon = markers[i].getAttribute("lon");
								var point = new GPoint(lon,lat);
								var logo = markers[i].getAttribute("severity");
								var marker = createRailMarker(point, markers[i].getAttribute("summary"), markers[i].getAttribute("src"), markers[i].getAttribute("type"), markers[i].getAttribute("start"), markers[i].getAttribute("stop"), logo, rail_markers.length);
								map.addOverlay(marker);
								rail_markers.push(marker);
							}
							var lo	= document.getElementById("loading");
							lo.innerHTML = "Loaded Rail Data";
						}else{
							var lo	= document.getElementById("loading");
							lo.innerHTML = lo.innerHTML + ' - ERROR: ' + request.status;
						}
						if (ldb_visible)
							doLDB();
					}
				};
				request.send(null);
				rail_loaded = true;
				rail_setup	= true;
			}
		//}
	}
	
	function showHideLDB(){
		if (ldb_visible){
			ldb_visible = false;
			var val = "Show";
		}else{
			ldb_visible = true;
			var val = "Hide";
		}
		var ldb = document.getElementById('rail_ldb_changer');
		ldb.innerHTML = val + " Live Departure Boards";
		doLDB();
	}
	
	function doLDB(){
		if (ldb_visible){
			if (ldb_loaded){
				for (var i=ldb_indicator; i< rail_markers.length ; i++)
					map.addOverlay(rail_markers[i]);
				var lo	= document.getElementById("loading");
				lo.innerHTML = "Loaded LDB Data";
			}else{
				var ldb_request = GXmlHttp.create();
				var url		= "<?php echo $site_url;?>ldb_data/stations.xml";
				var lo	= document.getElementById("loading");
				lo.innerHTML = "Loading LDB Data ...";//from "+url+" ...";
				ldb_request.open("GET", url	, true);
				ldb_request.onreadystatechange = function() {
					if (ldb_request.readyState == 4) {
						if (ldb_request.status == 200) {
							ldb_indicator = rail_markers.length;
							var xmlDoc 	= ldb_request.responseXML;
							var markers = xmlDoc.documentElement.getElementsByTagName("station");
							for (var i=0; i< markers.length ; i++){
								var lat = markers[i].getAttribute("lat");
								var lon = markers[i].getAttribute("lon");
								var point = new GPoint(lon,lat);
								var marker = createLDBMarker(point, markers[i].getAttribute("name"), markers[i].getAttribute("code"), i);
								map.addOverlay(marker);
								rail_markers.push(marker);
							}
						}
					}
				};
				ldb_request.send(null);
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
    		var request = GXmlHttp.create();
			var url	= "<?php echo $site_url;?>includes/travel_new.php?area="+tPlace; //get xml direct now
			var lo	= document.getElementById("loading");
			lo.innerHTML = "Loading Traffic Data ...";// from "+url+" ...";
			traffic_data.push(tPlace);
			traffic_start.push(traffic_markers.length);
			request.open("GET", url	, true);
			request.onreadystatechange = function() {
				if (request.readyState == 4) {
				if (request.status == 200) {
					var xmlDoc = request.responseXML;
					var markers = xmlDoc.documentElement.getElementsByTagName("t_alert");
					for (var i = 0; i < markers.length; i++) {
						var places = 0;
						var lat = markers[i].getAttribute("lat");
						var lon = markers[i].getAttribute("lon");
						var logo = markers[i].getAttribute("severity");
						var marker = createTrafficMarker(lat, lon , markers[i].getAttribute("summary"), markers[i].getAttribute("src"), markers[i].getAttribute("type"), markers[i].getAttribute("start"), markers[i].getAttribute("stop"), logo, tLat, tLon);
						map.addOverlay(marker);
						traffic_markers.push(marker);
					}
					traffic_end.push(traffic_markers.length);
					var lo	= document.getElementById("loading");
					lo.innerHTML = "Loaded Traffic Data";
				}
			}};
			request.send(null);
		}
	}
	
	/**
	 * Resets all traffic markers to say "Show Delays"
	 */
	function resetTraffic(){
		//not yet implemented
	}	
	
	/**
	 * Changes Map to display chosen dataset
	 */
	function goTo(val){
		if (traffic)
			resetTraffic();
			
		map.clearOverlays();
		switch (val){
			case 'traffic':
				if (!traffic){
					traffic			= true;
					rail			= false;
					weather			= false;
					setupTraffic();
				}
			break;
			case 'weather':
				if (!weather){
					weather			= true;
					rail			= false;
					traffic			= false;
					setupWeather();
				}
			break;
			case 'rail':
				if (!rail){
					rail			= true;
					weather			= false;
					traffic			= false;
					setupRail();
				}
			break;
		}
		reload();
	}
	
	function updateDisplay(){
		var wc 	= document.getElementById('wc');
		var tc 	= document.getElementById('tc');
		var rc 	= document.getElementById('rc');
		var s	= document.getElementById('static');
		if (traffic){
			var nav = 'Displaying Current Delays on the National Road Network';
			if (mw)
				nav += '&nbsp;|&nbsp;'+mw.innerHTML;
			
			wc.className 	= 'choice';
			tc.className 	= 'choiceS';
			rc.className 	= 'choice';
		}
		if (weather){
			var today = new Date();
			today.setDate(today.getDate()+day);
			var nav = '<a href="javascript:if (day >0){previousDay();}else{alert(\'No Past Data Available\');}" title="Show Weather for the Previous Day">Previous</a> | <a href="javascript:if(day < 9){nextDay();}else{alert(\'No More Data Available\');}"  title="Show Weather for the Next Day">Next Day</a>';
			nav +=' Weather for: '+weekday[today.getDay()]+' '+today.getDate()+' '+month[today.getMonth()]+' '+today.getFullYear();
			wc.className 	= 'choiceS';
			tc.className 	= 'choice';
			rc.className 	= 'choice';
		}
		if (rail){
			var nav = 'Displaying Current Delays on the National Rail Network &nbsp;|&nbsp;';
			if (ldb_visible){
				nav += '<a id="rail_ldb_changer" href="javascript:showHideLDB();" title="Hide Live Data for Stations">Hide Live Departure Boards</a>&nbsp;|&nbsp;<a href="http://pritch.blueghost.co.uk/wordpress/?page_id=19" target="_blank" title="Add Your Station">Add Your Station</a>';
			}else{
				nav += '<a id="rail_ldb_changer" href="javascript:showHideLDB();" title="Show Live Data for Stations">Show Live Departure Boards</a>&nbsp;|&nbsp;<a href="http://pritch.blueghost.co.uk/wordpress/?page_id=19" target="_blank" title="Add Your Station">Add Your Station</a>';
			}
			wc.className 	= 'choice';
			tc.className 	= 'choice';
			rc.className 	= 'choiceS';
		}
		s.innerHTML		= nav;
		if (traffic){
			if (m_visible)
				goToTraffic('motorways', 0, 0, 0, 'UK Motorways');
		}
	}
	
	function nextDay(){
		day++;
		reload();
	}
	
	function previousDay(){
		day--;
		reload();
	}
	
	function highlight(id, cl){
		var e = document.getElementById(id);
		switch (e.innerHTML.toLowerCase()){
			case 	'weather':
				if (!weather)
					e.className = cl;
			break;
			case	'traffic':
				if (!traffic)
					e.className = cl;
			break;
			case	'rail':
				if (!rail)
					e.className = cl;
			break
			default:
				e.className = cl;
			break;
		}
	}
	
	function unhighlight(id, cl){
		var e = document.getElementById(id);
		var e = document.getElementById(id);
		switch (e.innerHTML.toLowerCase()){
			case 	'weather':
				if (!weather)
					e.className = cl;
			break;
			case	'traffic':
				if (!traffic)
					e.className = cl;
			break;
			case	'rail':
				if (!rail)
					e.className = cl;
			break
			default:
				e.className = cl;
			break;
		}
	}
	
	function openHelp(){
		window.open('http://bbc.blueghost.co.uk/help.php','helpWindow','width=600,height=600,scrollbars=yes');
	}
	
	function openAbout(){
		window.open('http://bbc.blueghost.co.uk/about.html','aboutWindow');
	}
	
	function goToSidebar(){
		document.location.href	= 'http://bbc.blueghost.co.uk/site31.php?'+genPermalink();
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
	<div id="up" class="choiceU" 	title="Click here to load the latest version of this site" onmouseover="javascript:highlight('up', 'choiceHU');" 	onmouseout="unhighlight('up', 'choiceU');" 	onclick="javascript:document.location.href='http://bbc.blueghost.co.uk';">Update Available</div>
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