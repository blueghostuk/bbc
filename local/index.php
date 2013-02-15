<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>BBC Local</title>
<?php
	$lnv = -2.7342;
	$lv = 52.6253;
	$zl = 9;
	include('../includes/paths.php');
	if ($_GET['marker'] || $_GET['marker'] == "0"){
		$marker_id = $_GET['marker'];
		$markers = "true";
	}else{
		$marker_id = 0;
		$markers = "false";
	}
?>
<link href="styles/style.css" rel="stylesheet" type="text/css" />
<script src="http://maps.google.com/maps?file=api&amp;v=1&amp;key=<?php echo $googleKey;?>" type="text/javascript"></script>
<script src="javascript/common.js" type="text/javascript"></script>
<script src="javascript/gxmarker.js" type="text/javascript"></script>
<script src="http://www.bbc.co.uk/radio/aod/radioplayer.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA

	var weather 	= false;
	var rail 		= false;
	var traffic 	= true;
	
	var show_pop_ups	= true;
	var ldb_visible		= true;
	
	var m_visible		= true;
	
	function createWeatherMarker(point, img, size, name, temp, time, links, id) {
		var icon = new GIcon();
		icon.image 		= "<?php echo $img_url;?>weather/"+size+"x"+size+"/"+img+".png";
		icon.shadow 	= "<?php echo $img_url;?>weather/trans.png";
		icon.iconSize 	= new GSize(size, size);
		icon.shadowSize = new GSize(1, 1);
		icon.iconAnchor = new GPoint(32, -5);
		icon.infoWindowAnchor = new GPoint(32, -5);
  		var marker 	= new GxMarker(point,icon);
		marker.setTooltip(name);
		var html = '<strong>'+name+'</strong><img class="weather" src="<?php echo $img_url;?>weather/64x64/'+img+'.png" alt="Weather img" /><br/><span style="font-size:small;">'+temp+'°C @ '+time+'</span>';
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
  		var marker = new GxMarker(point, ldbICon);
		marker.setTooltip(name);
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
		//var lo			= document.getElementById("loading");
		//lo.innerHTML 	= 'Loading LDBData ...';// from "+url+" ...";
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
							//lo.innerHTML = "LDB Function not fully supported in this Web Browser";
							marker.openInfoWindowHtml(xmlDoc);
						}
					}
				}else{
					//lo.innerHTML = "ERROR loading LDB Data";
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
  		var marker = new GxMarker(point,icon);
		marker.setTooltip(html.substring(0, 30)+' ...<span style="markerSmall">Click for more</span>');
		var info_html = '<div style="width: 200px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:x-small;"><img style="float:left" src="<?php echo $img_url;?>'+type+'.png" alt="img" />'+html+'</div><strong>Source:'+src+'</strong>';
  		info_html += '<div>Started @ '+start+'</div><div>End @ '+stp+'</div>';
		info_html += '<div class="weather_link"><a href="javascript:zoomTo('+lat+','+lon+', 3);">Zoom In</a></div>';
		info_html += '<div class="weather_link"><a href="javascript:zoomTo('+alat+','+alon+', 9);">Zoom Out to Area</a></div>';
		info_html += '<div class="weather_link"><a href="javascript:zoomOut();">Zoom Out Fully</a></div>';
		GEvent.addListener(marker, "click", function() {
    		marker.openInfoWindowHtml(info_html);
  		});
		return marker;
	}
	
	function createTrafficCountyMarker(html, title, lat, lon){
		var point = new GPoint(lon,lat);
		var marker = new GxMarker(point,tICon);
		marker.setTooltip(title);
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
		loadLinks();
		if (GBrowserIsCompatible()) {
			/*DETECT RES */
			if (screen.width == '1280' && screen.height == '800')
				widescreen = true;
			if (screen.width == '1280' && screen.height == '768')
				widescreen = true;
			
			loc = new GPoint(<?php echo $lon;?>,<?php echo $lat;?>);
			cen	= new GPoint(<?php echo $lon;?>,<?php echo $lat;?>);
			map = new GMap(document.getElementById("weather_map"));
			map.addControl(new GSmallZoomControl());
			
			//if (<?php echo $zoom;?>){
				var p = new GPoint(<?php echo $lnv;?>,<?php echo $lv;?>);
				map.centerAndZoom(p, <?php echo $zl;?>);
			//}else{
				//map.centerAndZoom(cen, 11);
			//}
			
			//if (<?php echo $markers;?>)
					//first_run = true;
					
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
			//var m_link = document.getElementById('m_link');
			//m_link.innerHTML 	=  'Hide Motorway Delays';
			//m_link.href			=  'javascript:removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');';
			//m_link.title		=	'Hide Delays in '+title;
			//county_markers[id].innerHTML 	=  '<a id="m_link" href="javascript:removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');" title="Hide Delays in '+title+'">Hide Motorway Delays</a>';
			//map.centerAndZoom(cen, 11);
			m_visible = true;
		}else{
			county_markers[id].innerHTML =  '<a>'+title+'</a><br/><a href="javascript:removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');" title="Hide Delays in '+title+'">Hide Delays</a><br/><a href="javascript:zoomOut();" title="Show Delays in '+title+'">Zoom Out</a>';
			var point = new GPoint(lon,lat);
			map.centerAndZoom(point, 9);
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
	
	function showHide(link, box, style){
		box = document.getElementById(box);
		if (box.className == 'hidden'){
			box.className = style;
			link.innerHTML = '-';
		}else{
			box.className = 'hidden';
			link.innerHTML = '+';
		}
	}
	
	var links = [];
	var link_titles = [];
	
	function loadLinks(){
		var l = document.getElementsByTagName('a');
		for (var i = 0; i < l.length; i++){
			links.push(l[i].href);
			link_titles.push(l[i].innerHTML);
		}
	}
	
	function doLocalSearch(text){
		var r_box = document.getElementById('searchResults');
		var results = [];
		r_box.innerHTML = '';
		for (var i = 0; i < link_titles.length; i++){
			if (link_titles[i].toLowerCase().indexOf(text) !=-1){
				var result = document.createElement('a');
				result.href= links[i];
				result.innerHTML = link_titles[i];
				results.push(result);
			}
		}
		if (results.length > 0){
			for(var i = 0; i < results.length; i++){
				r_box.innerHTML += '<a target="_blank" href="'+results[i].href+'">'+results[i].innerHTML+'</a><br />';
			}
		}else{
			r_box.innerHTML = 'No Results Found';
		}
	}
	
	
//]]>
</script>
</head>
<?php
	include('../includes/io.php');
	include('../includes/paths.php');
	require('../includes/Rdf_Parser2.php');
?>
<body onload="onLoad()" onunload="unLoad()">
<div id="header">
	BBC Local | Shropshire
</div>
<div id="content">
	<div class="box">
		<div class="box_title"><a href="http://news.bbc.co.uk/1/hi/england/shropshire/" target="_blank">BBC Shropshire News</a></div>
		<div class="box_nav"><a onclick="javascript:showHide(this, 'bbc_news', 'box_data');">-</a></div>
		<div class="box_data" id="bbc_news">
		<?php
			$url 	= 'http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/england/shropshire/rss.xml';
			$file	= $dir.'local/data/shropshire/bbc_shropshire_news.html';
			$text	= getFile($url, $file);
			$last	= 'Last Updated @ '.date("D d/m/y H:i:s", filectime($file));
			echo $text;
		?>
		</div>
		<div class="box_title">BBC Shropshire Sport</div>
		<div class="box_nav"><a onclick="javascript:showHide(this, 'bbc_sport', 'box_data');">-</a></div>
		<div class="box_data" id="bbc_sport">
		<?php
			$url 	= 'http://newsrss.bbc.co.uk/rss/sportonline_world_edition/england/shropshire/rss.xml';
			$file	= $dir.'local/data/shropshire/bbc_shropshire_sport.html';
			$text	= getFile($url, $file);
			$last	= 'Last Updated @ '.date("D d/m/y H:i:s", filectime($file));
			echo $text;
		?>
		</div>
	</div>
	<div class="box">
		<div class="box_title">Shropshire Star News</div>
		<div class="box_nav"><a onclick="javascript:showHide(this, 'star_news', 'box_data');">-</a></div>
		<div class="box_data" id="star_news">
		<?php
			$url 	= 'http://www.shropshirestar.com/rss/ss_news.php';
			$file	= $dir.'local/data/shropshire/shropshire_star_news.html';
			$text	= getFile($url, $file);
			$last	= 'Last Updated @ '.date("D d/m/y H:i:s", filectime($file));
			echo $text;
		?>
		</div>
		<div class="box_title">Shropshire Star Sport</div>
		<div class="box_nav"><a onclick="javascript:showHide(this, 'star_sport', 'box_data');">-</a></div>
		<div class="box_data" id="star_sport">
		<?php
			$url 	= 'http://www.shropshirestar.com/rss/ss_sport.php';
			$file	= $dir.'local/data/shropshire/shropshire_star_sport.html';
			$text	= getFile($url, $file);
			$last	= 'Last Updated @ '.date("D d/m/y H:i:s", filectime($file));
			echo $text;
		?>
		</div>
	</div>
	<div class="box_map">
		<div class="box_title">Map</div>
		<div class="box_nav">
			<div class="choiceS" id="tc" onmouseover="javascript:highlight('tc', 'choiceH');" onfocus="javascript:this.onmouseover;" onmouseout="unhighlight('tc', 'choice');" onblur="javascript:this.onmouseout;" onclick="javascript:goTo('traffic');">Traffic</div>
			<div class="choice" id="wc" onmouseover="highlight('wc', 'choiceH');" onfocus="javascript:this.onmouseover;" onmouseout="unhighlight('wc', 'choice');" onblur="javascript:this.onmouseout;" onclick="javascript:goTo('weather');">Weather</div>
			<div class="choice" id="rc" onmouseover="javascript:highlight('rc', 'choiceH');" onfocus="javascript:this.onmouseover;" onmouseout="unhighlight('rc', 'choice');" onblur="javascript:this.onmouseout;" onclick="javascript:goTo('rail');">Rail</div>
			<a onclick="javascript:showHide(this, 'weather_map', '');">-</a>
		</div>
		<div id="weather_map"></div>
	</div>
	<div class="box">
		<div class="box_title">Page Search</div>
		<div class="box_nav"><a onclick="javascript:showHide(this, 'page_search', 'search_box');">-</a></div>
		<div class="search_box" id="page_search">
			Enter Search Terms: <input type="text" size="20" maxlength="100" onkeyup="javascript:doLocalSearch(this.value);" onblur="javascript:redo(this);" onfocus="javascript:clearS(this);" value="Enter Search Query" />
			<div class="sResults" id="searchResults"></div>
		</div>
		<div class="box_title">Local Radio</div>
		<div class="box_nav"><a onclick="javascript:showHide(this, 'bbc_radio', 'box_radio');">-</a></div>
		<div class="box_radio" id="bbc_radio">
			BBC Radio Shropshire: 96 FM / DAB / <a href="http://www.bbc.co.uk/radio/aod/shropshire.shtml" target="aod" onclick="aodpopup('http://www.bbc.co.uk/radio/aod/shropshire.shtml');return false;">Online</a><br />
			BBC Radio WM: 95.6 FM / DAB / <a href="http://www.bbc.co.uk/radio/aod/wm.shtml" target="aod" onclick="aodpopup('http://www.bbc.co.uk/radio/aod/wm.shtml');return false;">Online</a><br />
			<a href="http://www.bbc.co.uk/mediaselector/check/localtv/console/shropshire?bbram=1&amp;nbram=1&amp;bbwm=1&amp;nbwm=1&amp;redirect=console.shtml&amp;media_path=/localtv/media/&amp;media_dir=shropshire&amp;media_file=index&amp;story_head=Welcome%20to%20BBC%20Shropshire%20TV" onclick="window.open(this.href,'console','width=705,height=587,toolbar=0,location=0,status=0,menubar=0,scrollbars=0,resizable=0,top=100,left=100');return false;">BBC Local TV:Shropshire</a>
		</div>
		<div class="box_title">TV Now &amp; Next</div>
		<div class="box_nav"><span class="powered">from</span> <a class="powered" href="http://tv.blueghost.co.uk" target="_blank">tv.blueghost.co.uk</a>&nbsp;<a onclick="javascript:showHide(this, 'bbc_tv', 'box_data');">-</a></div>
		<div class="box_data" id="bbc_tv">
			<?php
				$url 	= 'http://tv.blueghost.co.uk/feeds/rss/nnp/1+2+32+33+30';
				$file	= $dir.'local/data/shropshire/nn.html';
				$text	= getFile($url, $file);
				$last	= 'Last Updated @ '.date("D d/m/y H:i:s", filectime($file));
				echo $text;
			?>
		</div>
	</div>
</div>
<div id="footer">
	supported by backstage.bbc.co.uk
</div>
</body>
</html>
