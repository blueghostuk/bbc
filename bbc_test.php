<?php
	//include('includes/Weather_Parser2.php');
	include('includes/paths.php');
	//center locs
	$lat		= 52.7; //to be set from xml
	$lon		= -2.52; //to be set from xml
	$locs		= array();
	
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<title>BlueGhost UK Traffic info, with backstage.bbc.co.uk</title>
<script src="http://maps.google.com/maps?file=api&v=1&key=<?php echo $googleKey;?>" type="text/javascript"></script>
<script type="text/javascript">

	function createMarker(point, html, src, type, start, stp,  image) {
		var icon = new GIcon();
		icon.image 		= "<?php echo $site_url;?>images/"+image+".png";
		icon.shadow 	= "<?php echo $site_url;?>images/weather/trans.png";
		icon.iconSize 	= new GSize(19, 40);
		icon.shadowSize = new GSize(1, 1);
		icon.iconAnchor = new GPoint(6, 20);
		icon.infoWindowAnchor = new GPoint(5, 1);
  		var marker = new GMarker(point,icon);
		var info_html = '<div style="width: 200px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small;"><img style="float:left" src="<?php echo $site_url;?>images/'+type+'.png" alt="img" />'+html+'</div><strong>Source:'+src+'</strong>';
  		if (type == "road"){
			info_html += '<div>Started @ '+start+'</div><div>End @ '+stp+'</div>';
		}
		info_html += '<div class="weather_link"><a href="javascript:zoomOut();">Zoom Out</a>';
		GEvent.addListener(marker, "click", function() {
			map.centerAndZoom(point, 2);
    		marker.openInfoWindowHtml(info_html);
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
	
	function zoomOut(){
		map.centerAndZoom(cen, 11);
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
			map.centerAndZoom(cen, 11);
			
			reload();
	
			//place TWC logo
			wICon	= new GIcon();
			wICon.image = "<?php echo $site_url;?>images/bbc.png";
			wICon.shadow = "<?php echo $site_url;?>images/weather/trans.png";
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
			
			/*GEvent.addListener(map, "moveend", function(){
				//if ( map.zoomLevel <= 9)
					//reload();
			});*/
			
			GEvent.addListener(map, "zoom", function() {
				//if ( map.zoomLevel <= 9)
					//reload();
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
		//if (day == 0)
			//var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=50&day=0";
		//else
			//var url		= "http://bbc.blueghost.co.uk/includes/weather_funcs.php?lat_max="+bounds.maxY+"&lat_min="+bounds.minY+"&lon_max="+bounds.maxX+"&lon_min="+bounds.minX+"&maximum=50&day=1";
		var url	= "http://bbc.blueghost.co.uk/includes/rail.php";
		/*DEBUGGING ONLY
		var lo	= document.getElementById("loading");
		lo.innerHTML = url;
		*/
		//alert("URL = "+url);
		request.open("GET", url	, true);
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				var xmlDoc 	= request.responseXML;
				alert(request.responseXML);
				if (request.responseXML == null)
					alert("request.responseXML == null");
				else
					alert("request.responseXML == object");
				alert(xmlDoc.documentElement);
				if (xmlDoc.documentElement == null)
					alert("xmlDoc.documentElement == null");
				else
					alert("xmlDoc.documentElement == object");
				var markers = xmlDoc.documentElement.getElementsByTagName("t_alert");
				//alert("markers size = " + markers.length);
				for (var i=0; i< markers.length ; i++){
					var lat = markers[i].getAttribute("lat");
					var lon = markers[i].getAttribute("lon");
					var point = new GPoint(lon,lat);
					var logo = markers[i].getAttribute("severity");
					var marker = createMarker(point, markers[i].getAttribute("summary"), markers[i].getAttribute("src"), markers[i].getAttribute("type"), markers[i].getAttribute("start"), markers[i].getAttribute("stop"), logo);
					map.addOverlay(marker);
				}
			}	
		}
		request.send(null);
	}
	
</script>
</head>
<body onload="onLoad()">
<div id="nav_bar" class="nav_bar">Traffic Data is currently out of date from the BBC</div>
<div id="loading"></div>
<div id="weather_map" style="width:100%; height:95%;"></div>
</span>
</body>
</html>