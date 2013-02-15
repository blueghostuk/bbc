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
	
	if ($_GET['ldb']){
		$show_ldb	= "true";
	}else{
		$show_ldb	= "false";
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
	
	var ldb_visible		= <?php echo $show_ldb;?>;
	var show_pop_ups	= <?php echo $pop_ups;?>;
	
	function getPermalink(){
		document.location.href = 'http://bbc.blueghost.co.uk/rail_sb.php?' + genPermalink();
	}
	
	function createRailMarker(point, html, src, type, start, stp,  image, id) {
		var icon = new GIcon();
		icon.image 		= "<?php echo $img_url;?>"+image+".png";
		icon.shadow 	= "<?php echo $img_url;?>weather/trans.png";
		icon.iconSize 	= new GSize(19, 40);
		icon.shadowSize = new GSize(1, 1);
		icon.iconAnchor = new GPoint(6, 20);
		icon.infoWindowAnchor = new GPoint(5, 1);
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

			setupRail();

			reload();
		}//end iscompat
	}//end onLoad
	
	function setupRail(){
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
	
	function reload(){
		updateDisplay();
	}

	function reloadRail(){
		//ldb options
		var info_html 	= document.createElement("div");
		info_html.id = 'rail_ldb';
		info_html.className = 'weather_link';
		if (ldb_visible){
			info_html.innerHTML = '<a id="rail_ldb_changer" style="display:block;" href="javascript:showHideLDB();" title="Hide Live Data for Stations">Hide Live Departure Boards</a><a style="display:block;" href="http://pritch.blueghost.co.uk/wordpress/?page_id=19" target="_blank" title="Add Your Station">Add Your Station</a>';
		}else{
			info_html.innerHTML = '<a id="rail_ldb_changer" style="display:block;" href="javascript:showHideLDB();" title="Show Live Data for Stations">Show Live Departure Boards</a><a style="display:block;" href="http://pritch.blueghost.co.uk/wordpress/?page_id=19" target="_blank" title="Add Your Station">Add Your Station</a>';
		}
		setFooterElement(info_html);
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

	function goToSidebar(){
		document.location.href	= 'http://bbc.blueghost.co.uk/rail.php?'+genPermalink();
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