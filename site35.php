<?php
	include('includes/paths.php');
	
	//set cookies
	setcookie ("type", 	"sidebar", 	time()+ (365 * 86400));
	
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
<link rel="stylesheet" type="text/css" href="style34.css" />
<title>BlueGhost UK Weather, with weather.com&reg; &amp; backstage.bbc.co.uk</title>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $googleKey;?>" type="text/javascript"></script>
<script src="javascript/common2.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
	
	var weather 	= <?php echo $weather;?>;
	var rail 		= <?php echo $rail;?>;
	var traffic 	= <?php echo $traffic;?>;
	
	var show_pop_ups	= <?php echo $pop_ups;?>;
	var ldb_visible		= <?php echo $show_ldb;?>;
	
	var m_visible		= <?php echo $show_mw;?>;
	
	function getPermalink(){
		document.location.href = 'http://bbc.blueghost.co.uk/site34.php?' + genPermalink();
	}
		
	function createWeatherMarker(point, img, size, name, temp, time, links ,id) {
		var img_url = "<?php echo $img_url;?>weather/"+size+"x"+size+"/"+img+".png";
		var icon	= makeIcon(img_url, size, size);
  		var marker 	= new GMarker(point,icon);
		//marker.setTooltip(name);
		var title 	= 'Weather For '+name+'<img width="64" height="64" src="<?php echo $img_url;?>weather/64x64/'+img+'.png" alt="Weather img" />';
		var text	= temp+'&deg;C @ '+time+'<br />';
		text		+='<strong>Featured on Weather.com</strong><br />';
		var linkst	='<a href="<?php echo $bbc_s;?>'+name+'" target="_blank">More @ BBC Weather</a>';
		
		var html = '<div style="white-space: nowrap;"><strong>'+name+'</strong><img src="<?php echo $img_url;?>weather/64x64/'+img+'.png" alt="Weather img" /><br/><span style="font-size:small;">'+temp+'&deg;C @ '+time+'</span>';
		html += '<div class="weather_link"><a href="<?php echo $bbc_s;?>'+name+'" target="_blank">More @ BBC Weather</a>';
		html += '<div class="weather_link">Featured on weather.com&reg;</div></div>';
		
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
		//marker.setTooltip(title);
		var title 	= '<img style="float:left" src="<?php echo $img_url;?>'+type+'.png" alt="img" />';
		var text	= html+'<a href="http://bbc.co.uk/travel" target="_blank" title="Go to the '+src+' website">Source:'+src+'</a>';
		text		+='<div>Started @ '+start+'</div><div>End @ '+stp+'</div>';
		var links		='<div class="weather_link" style="white-space: nowrap;"><a href="javascript:zoomOut();">Zoom Out</a></div';
		
		var html = '<div style="white-space: nowrap; width: 200px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small;"><img style="float:left" src="<?php echo $img_url;?>'+type+'.png" alt="img" />'+html+'<br /><strong>Source:'+src+'</strong>';
  		html += '<div style="white-space: nowrap;">Started @ '+start+'</div><div>End @ '+stp+'</div>';
		html += links;
		html += '</div>';
		
		GEvent.addListener(marker, "click", function() {
			map.setCenter(point, 15);
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
		//marker.setTooltip(name);
		var request = GXmlHttp.create();
		var links		='<div class="weather_link"><a href="<?php echo $ldb_site;?>'+code+'">Live Data from National Rail</a>';
		
		GEvent.addListener(marker, "click", function() {
			map.setCenter(point, 12);
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
							marker.openInfoWindowHtml("<div class=\"ldb_tbl\"><table class=\"ldb_tbl\" style=\"white-space: nowrap;\">"+st_title+a_t+data[0].innerHTML+copy_text+copy_2+"</table></div>");
						}else
							marker.openInfoWindowHtml("<div class=\"ldb_tbl\"><table class=\"ldb_tbl\">"+st_title+data[0].innerHTML+copy_text+copy_2+"</table></div>");
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
								marker.openInfoWindowHtml("<div class=\"ldb_tbl\"><table class=\"ldb_tbl\">"+st_title+a_t+data[0].innerHTML+copy_text+copy_2+"</table></div>");
							}else
								marker.openInfoWindowHtml("<div class=\"ldb_tbl\"><table class=\"ldb_tbl\">"+st_title+data[0].innerHTML+copy_text+copy_2+"</table></div>");
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
		var point = new GLatLng(lat, lon);
  		var marker = new GMarker(point,icon);
		//marker.setTooltip(html.substring(0, 30)+' ...<span style="markerSmall">Click for more</span>');
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
	
	
	function createTrafficCountyMarker(html, title, lat, lon, id, id2){
		var point = new GLatLng(lat,lon);
		var marker = new GMarker(point,tICon);
		//marker.setTooltip(title);
		var id = county_markers.length;
		var info_html 	= document.createElement("div");
		info_html.id = 'traffic_'+html+'';
		info_html.className = 'weather_link';
		info_html.innerHTML = '<a>'+title+'</a><br/><a href="javascript:goToTraffic(\''+html+'\', '+lat+', '+lon+', '+id2+', \''+title+'\', '+id+');" title="See Delays in '+title+'">Show Delays</a><br/><a href="javascript:zoomOut();" title="Zoom Out">Zoom Out</a>';
		county_markers.push(info_html);
		var title 	= "Traffic Warnings for "+title;
		var text	= 'Last Updated @ <?php echo date("D d/m H:i:s", filectime($t_dir."shropshire_tpeg_copy.xml"));?>';
		
		GEvent.addListener(marker, "click", function() {
			//if (show_pop_ups)
    			marker.openInfoWindow(county_markers[id]);
			setDesc(title, text);
			last_open = id2; //refers to t_markers
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
			
			loc = new GLatLng(<?php echo $lat;?>, <?php echo $lon;?>);
			cen	= new GLatLng(<?php echo $lat;?>, <?php echo $lon;?>);
			map = new GMap2(document.getElementById("weather_map"));
			map.addControl(new GLargeMapControl());
			map.addControl(new GMapTypeControl());
			map.addControl(new GScaleControl());
			
			if (<?php echo $zoom;?>){
				var p = new GLatLng(<?php echo $lv;?>, <?php echo $lnv;?>);
				map.setCenter(p, 17-<?php echo $zl;?>);
			}else{
				map.setCenter(cen, 6);
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
	
	/*reload() moved to common.js*/
	
	/*reloadWeather() moved to common.js*/
	
	/*reloadRail() moved to common.js*/	
	
	/*doLDB() moved to common.js*/
	
	function goToTraffic(loc, lat, lon, id, title, id2){
		if (!id2)
			id2 = id;
		var iframe	= /*this.frames.frames["cLinksFrame"].*/document.getElementById(id);
		var href = 'javascript:parent.removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\');';
		if (iframe.href == href){
			removeTraffic(loc, lat, lon, id, title);
		}else{
			iframe.href	= href;
			if (id == 0){/*MOTORWAYS*/
				var m_link = document.getElementById('m_link');
				m_link.innerHTML 	=  'Hide Motorway Delays';
				m_link.href			=  'javascript:removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\', '+id2+');';
				m_link.title		=	'Hide Delays in '+title;
				county_markers[id2].innerHTML =  '<a id="m_link" href="javascript:removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\', '+id2+');" title="Hide Delays in '+title+'">Hide Motorway Delays</a>';
				m_visible = true;
			}else{
				county_markers[id2].innerHTML =  '<a>'+title+'</a><br/><a href="javascript:removeTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\', '+id2+');" title="Hide Delays in '+title+'">Hide Delays</a><br/><a href="javascript:zoomOut();" title="Show Delays in '+title+'">Zoom Out</a>';
				var point = new GLatLng(lat,lon);
				map.setCenter(point, 10);
			}
			tPlace 	= loc;
			tLat	= lat;
			tLon	= lon;
			reloadTraffic();
		}
	}
	
	function removeTraffic(loc, lat, lon, id, title, id2){
		if (!id2)
			id2 = id;
		var index = 0;
		for (var i = 0; i < traffic_data.length; i++){
			if (traffic_data[i] == loc){
				index = i;
				i = traffic_data.length + 1;
			}
		}
		for (var start = traffic_start[index]; start < traffic_end[index]; start++)
			map.removeOverlay(traffic_markers[start]);
			
		//var iframe	= frames['cLinksFrame'];
		var iframe	= /*this.frames.frames["cLinksFrame"].*/document.getElementById(id); 
		iframe.href	= 'javascript:parent.goToTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\', '+id2+');';
		
		if (id == 0){/*MOTORWAYS*/
			var m_link = document.getElementById('m_link');
			m_link.innerHTML 	=  'Show Motorway Delays';
			m_link.href			=  'javascript:goToTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\', '+id2+');';
			m_link.title		=	'Show Delays in '+title;
			county_markers[id2].innerHTML =  '<a id="m_link" href="javascript:goToTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\', '+id2+');" title="Show Delays in '+title+'">Show Motorway Delays</a>';
			m_visible = false;
		}else{
			county_markers[id2].innerHTML =  '<a>'+title+'</a><br/><a href="javascript:goToTraffic(\''+loc+'\', '+lat+', '+lon+', '+id+', \''+title+'\', '+id2+');" title="Show Delays in '+title+'">Show Delays</a><br/><a href="javascript:zoomOut();" title="Zoom Out">Zoom Out</a>';
		}
		resetRoadLinks();
	}
	
	/*reloadTraffic() moved to common.js*/
		
	function goToSidebar(){
		document.location.href	= ns_site+genPermalink();
	}
//]]>
</script>
</head>
<body onLoad="onLoad()" onUnload="unLoad()">
<div id="selector">
	<div id="wc" class="choiceS"	title="Click to Show Weather Overlay" 					onmouseover="javascript:highlight('wc', 'choiceH');" 	onmouseout="unhighlight('wc', 'choice');" 	onclick="javascript:goTo('weather');">Weather</div>
	<div id="rc" class="choice"  	title="Click to Show Rail Overlay" 						onmouseover="javascript:highlight('rc', 'choiceH');" 	onmouseout="unhighlight('rc', 'choice');" 	onclick="javascript:goTo('rail');">Rail</div>
	<div id="tc" class="choice"  	title="Click to Show Road Traffic Overlay" 				onmouseover="javascript:highlight('tc', 'choiceH');" 	onmouseout="unhighlight('tc', 'choice');" 	onclick="javascript:goTo('traffic');">Traffic</div>
	<div id="pc" class="choiceP" 	title="Click to get a Permanant Link to this map view" 	onmouseover="javascript:highlight('pc', 'choiceHP');" 	onmouseout="unhighlight('pc', 'choiceP');" 	onclick="javascript:getPermalink();">Permalink</div>
	<div id="rv" class="choiceP"	title="Reset the Viewpoint back to the original position" onMouseOver="javascript:highlight('rv', 'choiceHP');"	onmouseout="unhighlight('rv', 'choiceP');" 	onclick="javascript:zoomOut();">Reset Viewpoint</div>
	<div id="pu" class="choiceP"	title="Show On Screen Pop-Ups" 							onmouseover="javascript:highlight('pu', 'choiceHP');"	onmouseout="unhighlight('pu', 'choiceP');" 	onclick="javascript:setPopUps();"><?php echo $ompu;?></div>
	<div id="sb" class="choiceP" 	title="Click to Remove the Sidebar to the map" 			onmouseover="javascript:highlight('sb', 'choiceHP');" 	onmouseout="unhighlight('sb', 'choiceP');" 	onclick="javascript:goToSidebar();">Remove Sidebar</div>
	<div id="up" class="choiceU" 	title="Click here to get this data in Google Earth" onMouseOver="javascript:highlight('up', 'choiceHU');" onMouseOut="unhighlight('up', 'choiceU');" onClick="javascript:document.location.href='http://bbc.blueghost.co.uk/earth/';"><img src="images/google_earth_feed.gif" width="93" height="17" alt="Google Earth" title="Click here to get this data in Google Earth" /></div>
	<div id="hc" class="choiceR" 	title="Click to see help" 								onmouseover="javascript:highlight('hc', 'choiceHR');" 	onmouseout="unhighlight('hc', 'choiceR');" 	onclick="javascript:openHelp();">Help</div>
	<div id="ac" class="choiceR" 	title="Click to see detail about this site" 			onmouseover="javascript:highlight('ac', 'choiceHR');" 	onmouseout="unhighlight('ac', 'choiceR');" 	onclick="javascript:openAbout();">About</div>
	<div id="sf" class="choiceR" 	title="Search">
	  <input name="search_form_box" id="search_form_box" type="text" onKeyUp="javascript:doSearch(this.value);" onBlur="javascript:redo(this);" onFocus="javascript:clearS(this);" value="Enter Search Query" size="20" maxlength="50" style="display:inline; height:16px; font-size:x-small; font-family:Arial, Helvetica, sans-serif; font-weight: normal;">
	</div>
</div>
<div id="sub_selector">
	<div id="static">Loading Site...</div>
	<div id="loading">Loading Site...</div>
</div>
<div id="weather_map"></div>
<div id="desc_box">
	<div id="desc_title"></div>
	<div id="desc_text"></div>
	<div class="hidden" id="searchResults">Search Results</div>
	<div id="desc_footer">
		<div id="footer_text"></div>
		<div id="traffic_links"></div>
	</div>
	<div id="cLinks" style="display:none; ">
		<hr class="divider" />
		<?php 
			echo file_get_contents('travel_data/locations.html');
		?>
		<!--<iframe id="cLinksFrame" name="cLinksFrame" src="travel_data/locations.html" height="40%" width="80%" frameborder="0"></iframe>-->
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