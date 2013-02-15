<?php
	include('includes/Weather_Parser2.php');
	include('includes/paths.php');
	//center locs
	$lat		= 52.7; //to be set from xml
	$lon		= -2.52; //to be set from xml
	$locs		= array();
	if ($_GET['unlimited'])
		$un = "true";
	else
		$un = "false";
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<title>BlueGhost UK Weather, with weather.com&reg; &amp; backstage.bbc.co.uk</title>
<script src="http://maps.google.com/maps?file=api&v=1&key=<?php echo $googleKey;?>" type="text/javascript"></script>
<script type="text/javascript">

	function createWeatherMarker(point, img, size, name, temp, time, links) {
		var icon = new GIcon();
		icon.image 		= "<?php echo $img_url;?>weather/"+size+"x"+size+"/"+img+".png";
		icon.shadow 	= "<?php echo $img_url;?>weather/trans.png";
		icon.iconSize 	= new GSize(size, size);
		icon.shadowSize = new GSize(1, 1);
		icon.iconAnchor = new GPoint(6, 20);
		icon.infoWindowAnchor = new GPoint(5, 1);
  		var marker = new GMarker(point,icon);
		var html = '<strong>'+name+'</strong><img src="<?php echo $url;?>images/weather/64x64/'+img+'.png" alt="Weather img" /><br/><span style="font-size:small;">'+temp+'°C @ '+time+'</span>';
		html += '<div class="weather_link"><a href="<?php echo $bbc_s;?>'+name+'" target="_blank">More @ BBC Weather</a>';
		html += '<div class="weather_link">Featured on weather.com&reg;</div>';
		for (var i=0; i < links.length; i++){
			html += '<div class="weather_link" style="text-align:left;">'+links[i]+'</div>';
		}
  		GEvent.addListener(marker, "click", function() {
    		marker.openInfoWindowHtml(html);
  		});
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
  		//if (type == "road"){
			info_html += '<div>Started @ '+start+'</div><div>End @ '+stp+'</div>';
		//}
		info_html += '<div class="weather_link"><a href="javascript:zoomOut();">Zoom Out</a>';
		GEvent.addListener(marker, "click", function() {
			dont_reload = true;
			map.centerAndZoom(point, 2);
    		marker.openInfoWindowHtml(info_html);
  		});
		return marker;
	}
	
	var loc;
	var cen;
	var map;
	var mkrs 		= [];
	var wICon;
	var wMarker;
	var widescreen 	= false;
	var day 		= 0;
	var weekday 	= new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	var month 		= new Array("Jan","Feb","Mar","Apr","May","June","July", "Aug", "Sept", "Oct", "Nov", "Dec");
	var weather 	= true;
	var rail 		= false;
	var traffic 	= false;
	var dont_reload = false;
	var unlimited	= <?php echo $un;?>;
	
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
			map.centerAndZoom(cen, 11);
			
			reload();
			
			if (weather)
				setupWeather();
			if (rail || traffic)
				setupRail();
			//if (traffic)
				//setupRail();//specific commands in there
		}//end iscompat
	}//end onLoad
	
	function setupWeather(){
		//place TWC logo
		wICon	= new GIcon();
		wICon.image = "<?php echo $img_url;?>weather/TWClogo_64px.png";
		wICon.shadow = "<?php echo $img_url;?>weather/trans.png";
		wICon.iconSize = new GSize(64, 64);
		wICon.shadowSize = new GSize(1, 1);
		wICon.iconAnchor = new GPoint(0, 0);
		wICon.infoWindowAnchor = new GPoint(-5, -5);
		var bounds = map.getBoundsLatLng();
		var width = bounds.maxX - bounds.minX;
		var height = bounds.maxY - bounds.minY;
		if (widescreen)
			var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.85));
		else
			var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.85));
		//var point = new GPoint(bounds.maxX-width*Math.random(), bounds.maxY-height*Math.random());
		wMarker = new GMarker(point,wICon);
		map.addOverlay(wMarker);
		
		GEvent.addListener(wMarker, "click", function() {
			var l_text = "<div class=\"weather_link\">Weather data provided by <a href=\"http://www.weather.com/?prod=xoap&par=<?php echo $partnerID;?>\" title=\"Go to weather.com\" target=\"_blank\">weather.com &reg;</a></div>";
    		wMarker.openInfoWindowHtml(l_text);
		});
	
		GEvent.addListener(map, "move", function() {
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
				var l_text = "<div class=\"weather_link\">Weather data provided by <a href=\"http://www.weather.com/?prod=xoap&par=<?php echo $partnerID;?>\" title=\"Go to weather.com\" target=\"_blank\">weather.com &reg;</a></div>";
    			wMarker.openInfoWindowHtml(l_text);
			});
  		});
		
		GEvent.addListener(map, "moveend", function(){
			if ( map.zoomLevel <= 9)
				reload();
		});
		GEvent.addListener(map, "zoom", function() {
			if ( map.zoomLevel <= 9)
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
				var l_text = "<div class=\"weather_link\">Weather data provided by <a href=\"http://www.weather.com/?prod=xoap&par=<?php echo $partnerID;?>\" title=\"Go to weather.com\" target=\"_blank\">weather.com &reg;</a></div>";
    			wMarker.openInfoWindowHtml(l_text);
			});
  		});
	}
	
	function setupRail(){
		//place backstage logo
		wICon	= new GIcon();
		wICon.image = "<?php echo $img_url;?>bbc.png";
		wICon.shadow = "<?php echo $img_url;?>weather/trans.png";
		wICon.iconSize = new GSize(400, 30);
		wICon.shadowSize = new GSize(1, 1);
		wICon.iconAnchor = new GPoint(0, 0);
		wICon.infoWindowAnchor = new GPoint(-5, -5);
		var bounds = map.getBoundsLatLng();
		var width = bounds.maxX - bounds.minX;
		var height = bounds.maxY - bounds.minY;
		if (widescreen)
			var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
		else
			var point = new GPoint(bounds.maxX-(width/1.5), bounds.maxY-(height*0.9));
		//var point = new GPoint(bounds.maxX-width*Math.random(), bounds.maxY-height*Math.random());
		wMarker = new GMarker(point,wICon);
		map.addOverlay(wMarker);
		
		GEvent.addListener(wMarker, "click", function() {
			//var w = window.open("http://backstage.bbc.co.uk");
			//if ( w == null){
				var l_text = "<div class=\"weather_link\">supported by <a href=\"http://backstage.bbc.co.uk\" target=\"_blank\">backstage.bbc.co.uk</a></div>";
    			wMarker.openInfoWindowHtml(l_text);
			//}
		});
	
		GEvent.addListener(map, "move", function() {
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
				//var w = window.open("http://backstage.bbc.co.uk");
				//if ( w == null){
					var l_text = "<div class=\"weather_link\">supported by <a href=\"http://backstage.bbc.co.uk\" target=\"_blank\">backstage.bbc.co.uk</a></div>";
    				wMarker.openInfoWindowHtml(l_text);
				//}
			});
  		});
		
		GEvent.addListener(map, "moveend", function(){
			if ( (map.zoomLevel <= 9) && traffic)
				reload();
		});
		
		GEvent.addListener(map, "zoom", function() {
			if (map.zoomLevel > 5)
				dont_reload = false;
			if ( (map.zoomLevel <= 9) && traffic)
				reload();
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
				//var w = window.open("http://backstage.bbc.co.uk");
				//if ( w == null){
					var l_text = "<div class=\"weather_link\">supported by <a href=\"http://backstage.bbc.co.uk\" target=\"_blank\">backstage.bbc.co.uk</a></div>";
    				wMarker.openInfoWindowHtml(l_text);
				//}
			});
  		});
	}
	
	function reload(){
	
		var lo	= document.getElementById("loading");
		lo.innerHTML = "Loading Data...";
		
		if (weather)
			reloadWeather();
		if (rail)
			reloadRail();
		if (traffic){
			if (!dont_reload)
				reloadTraffic();
		}
		
		if (weather){
			var today = new Date();
			today.setDate(today.getDate()+day);
			var nav = '<a href="about.html" title="About Page">About</a> | <a href="javascript:if (day >0){previousDay();}">Previous</a> | <a href="javascript:if(day < 9){nextDay();}">Next Day</a>';
			nav +=' Weather for: '+weekday[today.getDay()]+' '+today.getDate()+' '+month[today.getMonth()]+' '+today.getFullYear();
			nav += '&nbsp;&raquo;&nbsp;<select class="drop_down" onchange="javascript:goTo(this.options[this.selectedIndex].value);"><option value="weather" selected="true">Weather</option><option value="rail">Railways</option><option value="traffic">Roads</option></select>';
			
		}
		if (rail){
			var nav = '<a href="about.html" title="About Page">About</a> | Displaying Current Delays on the National Rail Network';
			nav += '&nbsp;&raquo;&nbsp;<select class="drop_down" onchange="javascript:goTo(this.options[this.selectedIndex].value);"><option value="weather">Weather</option><option value="rail" selected="true">Railways</option><option value="traffic">Roads</option></select>';
		}
		if (traffic){
			var nav = '<a href="about.html" title="About Page">About</a> | Displaying Current Delays on the National Road Network';
			nav += '&nbsp;&raquo;&nbsp;<select class="drop_down" onchange="javascript:goTo(this.options[this.selectedIndex].value);"><option value="weather">Weather</option><option value="rail">Railways</option><option value="traffic" selected="true">Roads</option></select>';
		}
		var lo	= document.getElementById("nav_bar");
		lo.innerHTML = nav;
		
	}
	
	function zoomOut(){
		map.centerAndZoom(cen, 11);
		dont_reload = false;
	}
	
	function reloadWeather(){
		//for (var i=0; i< mkrs.length; i++)
			//map.removeOverlay(mkrs[i]);
		var tmp_mkrs	= [];
		var link_text	= [];
    	var request = GXmlHttp.create();
		var bounds = map.getBoundsLatLng();
		//var width = bounds.maxX - bounds.minX;
		//var height = bounds.maxY - bounds.minY;
		if (unlimited){
			if (day == 0)
				var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=650&day=0";
			else
				var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=650&day=1";
		}else{
			if (day == 0)
				var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=50&day=0";
			else
				var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=50&day=1";
		
		}
		var lo	= document.getElementById("loading");
		lo.innerHTML = "Loading Data ...";//+url+" ...";
		/*DEBUGGING ONLY
		var lo	= document.getElementById("loading");
		lo.innerHTML = url;
		*/
		//alert("URL = "+url);
		request.open("GET", url	, true);
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
			if (request.status == 200) {
				var xmlDoc = request.responseXML;
				var markers = xmlDoc.documentElement.getElementsByTagName("day");
				var links = xmlDoc.documentElement.getElementsByTagName("link");
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
						var marker = createWeatherMarker(point, markers[i].getAttribute("icon"), '64', name, markers[i].getAttribute("temp"), markers[i].getAttribute("time"), lin );
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
				lo.innerHTML = "";
			}}
		}
		request.send(null);
	}
	
	function reloadRail(){
		//for (var i=0; i< mkrs.length; i++)
			//map.removeOverlay(mkrs[i]);
		var tmp_mkrs	= [];
		var link_text	= [];
    	var request = GXmlHttp.create();
		//var bounds = map.getBoundsLatLng();
		//var width = bounds.maxX - bounds.minX;
		//var height = bounds.maxY - bounds.minY;
		//if (day == 0)
			//var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=50&day=0";
		//else
			//var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=50&day=1";
		//if (rail)
			var url	= "http://bbc.blueghost.co.uk/includes/rail.php";
		//if (traffic)
			//var url	= "http://bbc.blueghost.co.uk/includes/travel.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX;
		//var url	= "http://bbc.blueghost.co.uk/includes/travel.php";
		var lo	= document.getElementById("loading");
		lo.innerHTML = "Loading Data ...";//from "+url+" ...";
		/*DEBUGGING ONLY
		var lo	= document.getElementById("loading");
		lo.innerHTML = url;
		*/
		//alert("URL = "+url);
		request.open("GET", url	, true);
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				if (request.status == 200) {
					var xmlDoc 	= request.responseXML;
					var markers = xmlDoc.documentElement.getElementsByTagName("t_alert");
					//alert("markers size = " + markers.length);
					for (var i=0; i< markers.length ; i++){
						var lat = markers[i].getAttribute("lat");
						var lon = markers[i].getAttribute("lon");
						var point = new GPoint(lon,lat);
						var logo = markers[i].getAttribute("severity");
						var marker = createRailMarker(point, markers[i].getAttribute("summary"), markers[i].getAttribute("src"), markers[i].getAttribute("type"), markers[i].getAttribute("start"), markers[i].getAttribute("stop"), logo);
						map.addOverlay(marker);
					}
					var lo	= document.getElementById("loading");
					lo.innerHTML = "";
				}else{
					var lo	= document.getElementById("loading");
					lo.innerHTML = lo.innerHTML + ' - ERROR: ' + request.status;
				}
			}
		}
		request.send(null);
	}
	
	function reloadTraffic(){
		//for (var i=0; i< mkrs.length; i++)
			//map.removeOverlay(mkrs[i]);
		var tmp_mkrs	= [];
		var link_text	= [];
    	var request = GXmlHttp.create();
		var bounds = map.getBoundsLatLng();
		//var width = bounds.maxX - bounds.minX;
		//var height = bounds.maxY - bounds.minY;
		if (unlimited){
			var url	= "http://bbc.blueghost.co.uk/includes/travel.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=650";
		}else{
			var url	= "http://bbc.blueghost.co.uk/includes/travel.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=5";
		}
		var lo	= document.getElementById("loading");
		lo.innerHTML = "Loading Data ...";// from "+url+" ...";
		/*DEBUGGING ONLY
		var lo	= document.getElementById("loading");
		lo.innerHTML = url;
		*/
		//alert("URL = "+url);
		request.open("GET", url	, true);
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
			if (request.status == 200) {
				var xmlDoc = request.responseXML;
				var markers = xmlDoc.documentElement.getElementsByTagName("t_alert");
				for (var i = 0; i < markers.length; i++) {
					var places = 0;
					//if (markers[i].getAttribute("rel") == day){/*only add if relevant day*/
						var lat = markers[i].getAttribute("lat");
						var lon = markers[i].getAttribute("lon");
						var point = new GPoint(lon,lat);
						var logo = markers[i].getAttribute("severity");
						var marker = createRailMarker(point, markers[i].getAttribute("summary"), markers[i].getAttribute("src"), markers[i].getAttribute("type"), markers[i].getAttribute("start"), markers[i].getAttribute("stop"), logo);
						tmp_mkrs.push(marker);
						places++;
					//}
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
				//lo.innerHTML = lo.innerHTML + " - DONE";
				lo.innerHTML = "";
			}}
		}
		request.send(null);
	}
	
	function goTo(val){
		map.clearOverlays();
		if (val == "weather"){
			weather = true;
			rail 	= false;
			traffic = false;
			setupWeather();
		}
		if (val == "rail"){
			weather = false;
			rail 	= true;
			traffic = false;
			setupRail();
		}
		if (val == "traffic"){
			weather = false;
			rail 	= false;
			traffic = true;
			setupRail();
		}
		reload();
	}
	
	function nextDay(){
		day++;
		reload();
	}
	
	function previousDay(){
		day--;
		reload();
	}

</script>
</head>
<body onload="onLoad()">
<div id="nav_bar" class="nav_bar"></div>
<div id="notice"><a href="http://bbc.blueghost.co.uk/" title="This page will redirect to the latest version of this website">UPDATE Available</a></div>
<div id="loading" class="nav_bar"></div>
<div id="weather_map" style="width:100%; height:95%;"></div>
</span>
</body>
</html>