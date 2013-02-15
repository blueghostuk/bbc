<?php
	include('includes/paths.php');
	
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
	
	if ($_GET['day'])
	{
		$day = $_GET['day'];
	}
	else
	{
		$day = 0;
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="/styles/style6.css" />
<title>BlueGhost UK Weather, with weather.com&reg; &amp; backstage.bbc.co.uk</title><!--
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?php echo $googleKey;?>" type="text/javascript"></script>
<script src="http://www.google.com/uds/api?file=uds.js&amp;v=0.1&amp;key=<?php echo $googleSearchKey;?>" type="text/javascript"></script>-->
<script type="text/javascript" src="http://www.google.com/jsapi?key=<?php echo $googleApisKey;?>"></script>
<script type="text/javascript">
//<![CDATA[

	
	var weather 	= <?php echo $weather;?>;
	var rail 		= <?php echo $rail;?>;
	var traffic 	= <?php echo $traffic;?>;
	
	var ldb_visible		= <?php echo $show_ldb;?>;
	
	var m_visible		= <?php echo $show_mw;?>;
	
	var day 			= <?php echo $day;?>;
	
	var weatherId		= <?php echo $partnerID;?>;
	
	var localSearch;
	var directionsSearch;
	
	function GetPermalink()
	{
		document.location.href = 'http://bbc.blueghost.co.uk/site6.php?' + GenPermalink();
	}
		
	function CreateWeatherMarker(point, img, size, name, temp, time, links ,id) 
	{
		var img_url = "<?php echo $img_url;?>weather/"+size+"x"+size+"/"+img+".png";
		var icon	= makeIcon(img_url, size, size);
  		var marker 	= new GMarker(point,icon);
		marker.title = name;
		var title 	= 'Weather For '+name+'<img width="64" height="64" src="<?php echo $img_url;?>weather/64x64/'+img+'.png" alt="Weather img" />';
		var text	= temp+'&deg;C @ '+time+'<br />';
		text		+='<strong>Featured on Weather.com</strong><br />';
		var linkst	='<a href="<?php echo $bbc_s;?>'+name+'" target="_blank">More @ BBC Weather</a>';
		
		var html = '<strong>'+name+'</strong><img src="<?php echo $img_url;?>weather/64x64/'+img+'.png" alt="Weather img" /><br /><span style="font-size:small;">'+temp+'&deg;C @ '+time+'</span>';
		html += '<br /><a href="<?php echo $bbc_s;?>'+name+'" target="_blank">More @ BBC Weather</a>';
		html += '<br /><br /><strong>Featured on weather.com&reg;:</strong>';
		
		for (var i=0; i < links.length; i++)
		{
			var a_text	= '<br />'+links[i];
			text += a_text;
			html += a_text;
		}
				
  		GEvent.addListener(marker, "click", function() 
		{
			ShowMapClickText(html);
			last_open = id; //refers to mkrs[]
  		});
		
		GEvent.addListener(marker, "mouseover", function() 
		{
			ShowMapClickText(html);
  		});
		
		GEvent.addListener(marker, "mouseout", function() 
		{
			//HideMapClickText();
  		});
		
		if ( (first_run) && (id == <?php echo $marker_id;?>) )
		{
			GEvent.trigger(marker, "click");
			first_run = false;
		}
		return marker;
	}
	
	function CreateLDBMarker(point, name, code, id) 
	{
  		var marker = new GMarker(point, ldbICon);
		//marker.setTooltip(name);
		var request = GXmlHttp.create();
		var links		='<div class="weather_link"><a href="<?php echo $ldb_site;?>'+code+'">Live Data from National Rail</a>';
		
		GEvent.addListener(marker, "click", function() 
		{
			map.setCenter(point, 12);
			loadLDB(marker, name, code);
			last_open = id; //refers to rail_markers
  		});
		
		GEvent.addListener(marker, "mouseover", function() 
		{
			ShowMapClickText('<strong>Click Icon to see Live Departures & Arrivals For '+name+'</strong>');
  		});
		
		GEvent.addListener(marker, "mouseout", function() 
		{
			//HideMapClickText();
  		});
		
		if ( (first_run) && (id == <?php echo $marker_id;?>) )
		{
			GEvent.trigger(marker, "click");
			first_run = false;
		}
		return marker;		
	}

	function CreateTrafficCountyMarker(html, title, lat, lon, id, id2)
	{
		var point = new GLatLng(lat,lon);
		var marker = new GMarker(point,tICon);
		var id = county_markers.length;
		var info_html 	= document.createElement("div");
		info_html.id = 'traffic_'+html+'';
		info_html.className = 'weather_link';
		info_html.innerHTML = '<a>'+title+'</a><br/><a href="javascript:goToTraffic(\''+html+'\', '+lat+', '+lon+', '+id2+', \''+title+'\', '+id+');" title="See Delays in '+title+'">Show Delays</a><br/><a href="javascript:zoomOut();" title="Zoom Out">Zoom Out</a>';
		county_markers.push(info_html);
		var title 	= "Traffic Warnings for "+title;
		var text	= 'Last Updated @ <?php echo date("D d/m H:i:s", filectime($t_dir."shropshire_tpeg_copy.xml"));?>';
		
		GEvent.addListener(marker, "click", function() 
		{
    		marker.openInfoWindow(county_markers[id]);
			last_open = id2; //refers to t_markers
  		});
		
		GEvent.addListener(marker, "mouseover", function() 
		{
			ShowMapClickText('<strong>Click Icon to see '+title+'</strong>');
  		});
		
		GEvent.addListener(marker, "mouseout", function() 
		{
			HideMapClickText();
  		});
		
		GEvent.addListener(marker, "onmouseover", 
			function()
			{
				ShowMapClickText(title);
			}
		);
		return marker;
	}
	
	function OnLoad()
	{
		if (GBrowserIsCompatible()) 
		{
			
			loc = new GLatLng(<?php echo $lat;?>, <?php echo $lon;?>);
			cen	= new GLatLng(<?php echo $lat;?>, <?php echo $lon;?>);
			map = new GMap2(GetElementById("weatherMap"));
			map.addControl(new GLargeMapControl());
			map.addControl(new GScaleControl());
			map.addControl(new BlueGhostBBCControl());
			map.addControl(new BlueGhostStatusControl());
			map.addControl(new BlueGhostCopyrightControl());
			map.addControl(new BlueGhostSearchControl());
			
			//new 2.5.8 features
			map.enableContinuousZoom();
			map.enableDoubleClickZoom();
			
			//setup global map events
			GlobalEvents();
			
			if (<?php echo $zoom;?>)
			{
				var p = new GLatLng(<?php echo $lv;?>, <?php echo $lnv;?>);
				map.setCenter(p, <?php echo $zl;?>);
			}
			else
			{
				map.setCenter(cen, 6);
			}
			
			if (<?php echo $markers;?>)
					first_run = true;
					
			if (weather)
			{
				SetupWeather();
			}
			
			if (rail)
			{
				SetupRail();
			}
			
			if (traffic)
			{
				SetupTraffic();
			}
			
			SetupGoolgeAjaxSearch();
			
			SetupGoogleDirectionsSearch();
			
			reload();
		}//end iscompat
		
		
	}//end onLoad
	
	// now using Google AJAX APIs
	
	google.load("maps", "2.x");
	google.load("search", "1.x");
	google.load("jquery", "1.4");
	
	google.setOnLoadCallback(OnLoad);
//]]>
</script>

<script src="/javascript/functions6.js" type="text/javascript"></script>
<script src="/javascript/common6.js" type="text/javascript"></script>
<script src="/javascript/controls6.js" type="text/javascript"></script>
</head>
<body onUnload="UnLoad()">
	<div id="weatherMap"></div>
	<!--Access Keys-->
	<a id="ak_w" accesskey="w" href="javascript:GoTo('weather');" title="Show Weather"></a>
	<a id="ak_t" accesskey="t" href="javascript:GoTo('traffic');" title="Show Road Traffic"></a>
	<a id="ak_r" accesskey="r" href="javascript:GoTo('rail');" title="Show Rail"></a>
	<a id="ak_p" accesskey="p" href="javascript:GetPermalink();" title="Permalink"></a>
	<a id="ak_h" accesskey="h" href="javascript:openHelp();" title="Help"></a>
	<a id="ak_a" accesskey="a" href="javascript:openAbout();" title="About"></a>
</body>
</html>