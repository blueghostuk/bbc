<?php
	include('includes/Weather_Parser2.php');
	include('includes/paths.php');
	//center locs
	$lat		= 52.7; //to be set from xml
	$lon		= -2.52; //to be set from xml
	$locs		= array();
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<title>BlueGhost UK Weather, with weather.com&reg;</title>
<script src="http://maps.google.com/maps?file=api&v=1&key=<?php echo $googleKey;?>" type="text/javascript"></script>
<script type="text/javascript">

	function createMarker(point, img, size, name, temp, time, links) {
		var icon = new GIcon();
		icon.image 		= "<?php echo $site_url;?>images/weather/"+size+"x"+size+"/"+img+".png";
		icon.shadow 	= "<?php echo $site_url;?>images/weather/trans.png";
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
	
	var loc;
	var cen;
	var map;
	var mkrs = [];
	var wICon;
	var wMarker;
	var widescreen = false;
	var day = 0;
	var weekday = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	var month = new Array("Jan","Feb","Mar","Apr","May","June","July", "Aug", "Sept", "Oct", "Nov", "Dec");
	
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
	
			//place TWC logo
			wICon	= new GIcon();
			wICon.image = "<?php echo $site_url;?>images/weather/TWClogo_64px.png";
			wICon.shadow = "<?php echo $site_url;?>images/weather/trans.png";
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
		
			
		}//end iscompat
	}//end onLoad
	
	function reload(){
		//for (var i=0; i< mkrs.length; i++)
			//map.removeOverlay(mkrs[i]);
		var tmp_mkrs	= [];
		var link_text	= [];
    	var request = GXmlHttp.create();
		var bounds = map.getBoundsLatLng();
		var width = bounds.maxX - bounds.minX;
		var height = bounds.maxY - bounds.minY;
		if (day == 0)
			var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=50&day=0";
		else
			var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=50&day=1";
		/*DEBUGGING ONLY
		var lo	= document.getElementById("loading");
		lo.innerHTML = url;
		*/
		//alert("URL = "+url);
		request.open("GET", url	, true);
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				var xmlDoc = request.responseXML;
				alert(req.responseText.length);
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
						var marker = createMarker(point, markers[i].getAttribute("icon"), '64', name, markers[i].getAttribute("temp"), markers[i].getAttribute("time"), lin );
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
			}
		}
		request.send(null);
		
		var today = new Date();
		today.setDate(today.getDate()+day);
		var nav = '<a href="about.html" title="About Page">About</a> | <a href="javascript:if (day >0){previousDay();}">Previous</a> | <a href="javascript:if(day < 9){nextDay();}">Next Day</a>';
		var lo	= document.getElementById("nav_bar");
		lo.innerHTML = nav+' Weather for: '+weekday[today.getDay()]+' '+today.getDate()+' '+month[today.getMonth()]+' '+today.getFullYear();
		
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
<div id="nav_bar" class="nav_bar"><a href="javascript:if (day >0){previousDay();}">Previous</a> | <a href="javascript:if(day <9){nextDay();}">Next Day</a></div>
<div id="loading"></div>
<div id="weather_map" style="width:100%; height:95%;"></div>
</span>
</body>
</html>