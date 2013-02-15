	var ns_site			= 'http://bbc.blueghost.co.uk/site24.php?';
	var sb_site			= 'http://bbc.blueghost.co.uk/site34.php?';
	var img_url			= 'http://bbc.blueghost.co.uk/images/';
	var search_url		= 'http://bbc.blueghost.co.uk/doSearch.php';
	var main_site		= 'http://bbc.blueghost.co.uk/';
	var weekday 		= new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	var month 			= new Array("Jan","Feb","Mar","Apr","May","June","July", "Aug", "Sept", "Oct", "Nov", "Dec");
	var county_markers 	= [];
	var loc;
	var cen;
	var map;
	var mkrs 			= [];
	var wICon;
	var tICon;
	var ldbICon;
	var tPlace;
	var tLat;
	var tLon;
	var wMarker;
	var widescreen 		= false;
	var day 			= 0;
	var traffic_setup	= false;
	var t_markers 		= [];
	var lastXMLdoc;
	var lastWUrl;
	var rail_setup		= false;
	var rail_markers 	= [];
	var traffic_markers = [];
	var traffic_data	= [];
	var traffic_start	= [];
	var traffic_end		= [];
	var ldb_loaded		= false;
	var v				= 0;
	var last_open		= -1;
	var first_run		= false;
	var mw;
	var ldb_indicator	= -1;
	
	var m_move;
	var m_moveend;
	var m_zoom;
	
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
	
	function reload(){
	
		updateDisplay();
	
		var lo	= document.getElementById("loading");
		
		if (weather){
			lo.innerHTML = "Loading Weather Data...";
			reloadWeather();
		}
	}
	
	function reloadWeather(){
		var research	= true;
    	var bounds = map.getBoundsLatLng();
		if (day == 0)
			var url		= main_site+'includes/weather_funcs.php?lat_max='+bounds.maxY+'&lat_min='+bounds.minY+'&lon_max='+bounds.maxX+'&lon_min='+bounds.minX+'&maximum=100&day=0';
		else
			var url		=  main_site+'includes/weather_funcs.php?lat_max='+bounds.maxY+'&lat_min='+bounds.minY+'&lon_max='+bounds.maxX+'&lon_min='+bounds.minX+'&maximum=100&day=1';

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
			var url		= main_site+'travel_data/railways.xml';
			var status	= 'Loading Rail Data ...';
			doXMLHTTPRequest(reloadRailXML, url, status);
			rail_setup	= true;
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
			var url	= main_site+'includes/travel_new.php?area='+tPlace;
			var status	= 'Loading Traffic Data ...';
			doXMLHTTPRequest(reloadTrafficXML, url, status);
		}
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
	
	function setPopUps(){
		var url = main_site+"cookie_set.php?op=icons&value=";
		//var ompu = document.getElementById('ompu');
		var tbpu = document.getElementById('pu');
		if (show_pop_ups){
			//ompu.innerHTML = "Show On Map Pop Ups";
			tbpu.innerHTML = "Show On Map Pop Ups";
			show_pop_ups = false;
			url += "1";
		}else{
			//ompu.innerHTML = "Hide On Map Pop Ups";
			tbpu.innerHTML = "Hide On Map Pop Ups";
			show_pop_ups = true;
			url += "2";
		}
		var request = GXmlHttp.create();
		var lo	= document.getElementById("loading");
		lo.innerHTML = "Setting Preferences";//from "+url+" ...";
		request.open("GET", url	, true);
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				var lo	= document.getElementById("loading");
				if (request.status == 200) {
					lo.innerHTML = "Set Preference";
				}else{
					lo.innerHTML = "Error setting Preferece";
				}
			}
		}
		request.send(null);
	}
	
	function logoEventHandler(wMarker, logo, reloader, clicker, html){
		if (reloader)
			reload();
		map.removeOverlay(wMarker);
		var bounds = map.getBoundsLatLng();
		var width = bounds.maxX - bounds.minX;
		var height = bounds.maxY - bounds.minY;
		if (widescreen)
			var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.87));
		else
			var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.87));
		wMarker = new GMarker(point,logo);
		map.addOverlay(wMarker);
		if (clicker){
			GEvent.addListener(wMarker, "click", function() {
				wMarker.openInfoWindowHtml(html);
			});
		}
		return wMarker;
	}
	
	function makeIcon(img, size_x, size_y){
		var mi 	= new GIcon();
		mi.image = img;
		mi.shadow = img_url+'weather/trans.png';
		mi.iconSize = new GSize(size_x, size_y);
		mi.shadowSize = new GSize(1, 1);
		mi.iconAnchor = new GPoint((size_x/2), (size_y/2));
		mi.infoWindowAnchor = new GPoint((size_x/2), 0);
		return mi;
	}
	
	function placeCopyLogo(logo, wMarker, img, size_x, size_y, html, move, moveend, clicker, reloader, zoom){
		//alert('got10');
		if (!logo){
			logo	= makeIcon(img_url+img, size_x, size_y);
			logo.iconAnchor = new GPoint((size_x/2), 0); //center img
		}
		//alert('got11');
		var bounds = map.getBoundsLatLng();
		var width = bounds.maxX - bounds.minX;
		var height = bounds.maxY - bounds.minY;
		if (widescreen)
			var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.87));
		else
			var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.87));
		//alert('got12');
		wMarker = new GMarker(point,logo);
		map.addOverlay(wMarker);
		//alert('got13');
		if (m_move){
			//alert('got131');
			try{
				GEvent.removeListener(m_move);
			}catch(e){
				//alert('error occured: ' +e);
			}
			//alert('got132');
		}
		//alert('got133');
		if (m_moveend){
			//alert('got134');
			try{
				GEvent.removeListener(m_moveend);
			}catch(e){
				//alert('error occured: ' +e);
			}
			//alert('got135');
		}
		//alert('got136');
		if (m_zoom){
			//alert('got137');
			try{
				GEvent.removeListener(m_zoom);
			}catch(e){
				//alert('error occured: ' +e);
			}
			//alert('got138');
		}
		//alert('got14');
		/*
		GEvent.clearListeners(map, "move");
		GEvent.clearListeners(map, "moveend");
		GEvent.clearListeners(map, "zoom");*/
		GEvent.clearListeners(wMarker, "click");
		//alert('got15');
		if (clicker){
			GEvent.addListener(wMarker, "click", function() {
				wMarker.openInfoWindowHtml(html);
			});
		}
		//alert('got16');
		if (move){
			m_move		= GEvent.addListener(map, "move", function() {
				wMarker = logoEventHandler(wMarker, logo, false, clicker, html);
  			});
		}
		//alert('got17');
		if (moveend){
			m_moveend	= GEvent.addListener(map, "moveend", function(){
				wMarker = logoEventHandler(wMarker, logo, reloader, clicker, html);
			});
		}
		//alert('got18');
		if (zoom){
			m_zoom		= GEvent.addListener(map, "zoom", function() {
				wMarker = logoEventHandler(wMarker, logo, reloader, clicker, html);
  			});
		}
		//alert('got19');
	}
	
	function doXMLHTTPRequest(f_name, url, status){
		var request = GXmlHttp.create();
		var lo	= document.getElementById("loading");
		lo.innerHTML = status;
		request.open("GET", url	, true);
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				if (request.status == 200) {
					f_name(request);
				}
			}
		};
		request.send(null);
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
				var url		= main_site+"ldb_data/stations.xml";
				var status	= 'Loading LDB Data ...';
				//alert('calling doXMLHTTPRequest');
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
	
	/**
	 * Resets all traffic markers to say "Show Delays"
	 */
	function resetTraffic(){
		//not yet implemented
	}	
	
	function nextDay(){
		day++;
		reload();
	}
	
	function previousDay(){
		day--;
		reload();
	}
	
	function setDesc(title, text){
		var ti	= document.getElementById('desc_title');
		var te	= document.getElementById('desc_text');
		ti.innerHTML = title;
		te.innerHTML = text;
	}
	
	function resetFooter(){
		if (show_pop_ups)
			var ompu = "Hide On Map Pop Ups";
		else
			var ompu = "Show On Map Pop Ups";
	}
	
	function setFooterElement(ele){
		var tf 		= document.getElementById('footer_text');
		tf.innerHTML= ele.innerHTML;
	}
	
	function setRoadLinks(html){
		var road = document.getElementById("traffic_links");
		if (road != null) {
    		road.innerHTML = html;
		}else
			alert('not found road');
	}
	
	function resetRoadLinks(){
		var road = document.getElementById("traffic_links");
		if (road != null) {
    		road.innerHTML = "";
		}
	}
	
	function goTo(val){
		if (traffic)
			resetTraffic();
		var cont = false;
		map.clearOverlays();
		switch (val){
			case 'traffic':
				if (!traffic){
					traffic			= true;
					rail			= false;
					weather			= false;
					setupTraffic();
					cont			= true;
				}
			break;
			case 'weather':
				if (!weather){
					weather			= true;
					rail			= false;
					traffic			= false;
					setupWeather();
					cont			= true;
				}
			break;
			case 'rail':
				if (!rail){
					rail			= true;
					weather			= false;
					traffic			= false;
					setupRail();
					cont			= true;
				}
			break;
		}
		//alert('got here');
		if (cont)
			reload();
	}
	
	function updateDisplay(){
		var wc 	= document.getElementById('wc');
		var tc 	= document.getElementById('tc');
		var rc 	= document.getElementById('rc');
		var s	= document.getElementById('static');
		
		var aw	= document.getElementById('ak_w');
		var at	= document.getElementById('ak_t');
		var ar	= document.getElementById('ak_r');
		
		var rl	= "javascript:goTo('rail');";
		var wl	= "javascript:goTo('weather');";
		var tl	= "javascript:goTo('traffic');";
		var nl	= "#";
		if (traffic){
			var nav = 'Displaying Current Delays on the National Road Network';
			if (mw)
				nav += '&nbsp;|&nbsp;'+mw.innerHTML;
			if (at){
				at.href		= nl;
				aw.href		= wl;
				ar.href		= rl;
			}
			//wc.onclick		= wl;
			//tc.onclick		= nl;
			//rc.onclick		= rl;
			wc.className 	= 'choice';
			tc.className 	= 'choiceS';
			rc.className 	= 'choice';
		}
		if (weather){
			var today = new Date();
			today.setDate(today.getDate()+day);
			var nav = '<a href="javascript:if (day >0){previousDay();}else{alert(\'No Past Data Available\');}" title="Show Weather for the Previous Day">Previous</a> | <a href="javascript:if(day < 9){nextDay();}else{alert(\'No More Data Available\');}"  title="Show Weather for the Next Day">Next Day</a>';
			nav +=' Weather for: '+weekday[today.getDay()]+' '+today.getDate()+' '+month[today.getMonth()]+' '+today.getFullYear();
			if (at){
				at.href		= tl;
				aw.href		= nl;
				ar.href		= rl;
			}
			//wc.onclick		= nl;
			//tc.onclick		= tl;
			//rc.onclick		= rl;
			wc.className 	= 'choiceS';
			tc.className 	= 'choice';
			rc.className 	= 'choice';
		}
		if (rail){
			var nav = 'Displaying Current Delays on the National Rail Network &nbsp;|&nbsp;';
			if (ldb_visible){
				//nav += '<a id="rail_ldb_changer" title="Live Departure Boards Out Of Action">Live Departure Boards Out Of Action</a>&nbsp;|&nbsp;<a href="http://pritch.blueghost.co.uk/wordpress/?page_id=19" target="_blank" title="Add Your Station">Add Your Station</a>';
				nav += '<a id="rail_ldb_changer" href="javascript:showHideLDB();" title="Hide Live Data for Stations">Hide Live Departure Boards</a>&nbsp;|&nbsp;<a href="http://pritch.blueghost.co.uk/wordpress/?page_id=19" target="_blank" title="Add Your Station">Add Your Station</a>';
			}else{
				//nav += '<a id="rail_ldb_changer" title="Live Departure Boards Out Of Action">Live Departure Boards Out Of Action</a>&nbsp;|&nbsp;<a href="http://pritch.blueghost.co.uk/wordpress/?page_id=19" target="_blank" title="Add Your Station">Add Your Station</a>';
				nav += '<a id="rail_ldb_changer" href="javascript:showHideLDB();" title="Show Live Data for Stations">Show Live Departure Boards</a>&nbsp;|&nbsp;<a href="http://pritch.blueghost.co.uk/wordpress/?page_id=19" target="_blank" title="Add Your Station">Add Your Station</a>';
			}
			if (at){
				at.href		= tl;
				aw.href		= wl;
				ar.href		= nl;
			}
			//wc.onclick		= wl;
			//tc.onclick		= tl;
			//rc.onclick		= nl;
			wc.className 	= 'choice';
			tc.className 	= 'choice';
			rc.className 	= 'choiceS';
		}
		s.innerHTML		= nav;
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
		window.open(main_site+'help.php','helpWindow','width=600,height=600,scrollbars=yes');
	}
	
	function openAbout(){
		window.open(main_site+'about.html','aboutWindow');
	}
	
	function reloadRailXML(request){
		var xmlDoc 	= request.responseXML;
		var markers = xmlDoc.documentElement.getElementsByTagName("t_alert");
		for (var i=0; i< markers.length ; i++){
			var lat 	= markers[i].getAttribute("lat");
			var lon 	= markers[i].getAttribute("lon");
			var point 	= new GPoint(lon,lat);
			var logo 	= markers[i].getAttribute("severity");
			var marker 	= createRailMarker(point, markers[i].getAttribute("summary"), markers[i].getAttribute("src"), markers[i].getAttribute("type"), markers[i].getAttribute("start"), markers[i].getAttribute("stop"), logo, rail_markers.length);
			map.addOverlay(marker);
			rail_markers.push(marker);
		}
		var lo			= document.getElementById("loading");
		lo.innerHTML 	= 'Loaded Rail Data';
		if (ldb_visible)
			doLDB();
	}
	
	function reloadWeatherXML(request){
		var tmp_mkrs	= [];
		var link_text	= [];
		//see if new query or reuse
		if (request.responseXML)
			lastXMLdoc = request.responseXML;
		else
			lastXMLdoc = request;
			
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
		lo.innerHTML = 'Loaded Weather Data';
	}
	
	function setupTrafficXML(request){
		var xmlDoc 	= request.responseXML;
		var markers = xmlDoc.documentElement.getElementsByTagName("t_alert");
		for (var i=0; i< markers.length ; i++){
			var lat = markers[i].getAttribute("lat");
			var lon = markers[i].getAttribute("lon");
			var id = markers[i].getAttribute("id");
			var marker = createTrafficCountyMarker(markers[i].getAttribute("location"), markers[i].getAttribute("title"), lat, lon, t_markers.length, id);
			map.addOverlay(marker);
			t_markers.push(marker);
		}
		var lo	= document.getElementById("loading");
		lo.innerHTML = "Loaded Traffic Data";
	}
	
	function doLDBXML(request){
		ldb_indicator 	= rail_markers.length;
		//alert('called doLDBXML = '+ldb_indicator);
		var xmlDoc 		= request.responseXML;
		var markers 	= xmlDoc.documentElement.getElementsByTagName("station");
		for (var i=0; i< markers.length ; i++){
			var lat 	= markers[i].getAttribute("lat");
			var lon 	= markers[i].getAttribute("lon");
			var point 	= new GPoint(lon,lat);
			var marker 	= createLDBMarker(point, markers[i].getAttribute("name"), markers[i].getAttribute("code"), i);
			map.addOverlay(marker);
			rail_markers.push(marker);
		}
		var lo	= document.getElementById("loading");
		lo.innerHTML = "Loading LDB Stations";
	}
	
	function reloadTrafficXML(request){
		traffic_data.push(tPlace);
		traffic_start.push(traffic_markers.length);
		var xmlDoc 	= request.responseXML;
		var markers	= xmlDoc.documentElement.getElementsByTagName("t_alert");
		for (var i = 0; i < markers.length; i++) {
			var places = 0;
			var lat = markers[i].getAttribute("lat");
			var lon = markers[i].getAttribute("lon");
			var logo = markers[i].getAttribute("severity");
			var marker = createTrafficMarker(lat, lon , markers[i].getAttribute("summary"), markers[i].getAttribute("src"), markers[i].getAttribute("type"), markers[i].getAttribute("start"), markers[i].getAttribute("stop"), logo, tLat, tLon, traffic_markers.length);
			map.addOverlay(marker);
			traffic_markers.push(marker);
		}
		traffic_end.push(traffic_markers.length);
		var lo			= document.getElementById("loading");
		lo.innerHTML 	= 'Loaded Traffic Data';
	}
	
	function doSearch(query){
		var mode = 'weather';
		if (rail)
			mode = 'rail';
		if (traffic)
			mode = 'traffic';
		if (query.length > 0){
			var url = search_url+'?mode='+mode+'&query='+query;
			doXMLHTTPRequest(showSearchResults, url, "Searching");
		}
	}
	
	function showSearchResults(request){
		var xmlDoc 	= request.responseXML;
		var resultText = "";
		var results	= xmlDoc.documentElement.getElementsByTagName("result");
		for (var i = 0; i < results.length; i++) {
			var l_text = results[i].getAttribute("l_text");
			var lat  = results[i].getAttribute("lat");
			var lon  = results[i].getAttribute("lon");
			if (!traffic)
				resultText +="<br /><a href=\"javascript:zoomTo("+lat+", "+lon+", 4);\">"+l_text+"</a>";
			else{
				var fname = results[i].getAttribute("fname");
				var id = results[i].getAttribute("id");
				resultText +="<br /><a href=\"javascript:goToTraffic('"+fname+"', "+lat+", "+lon+", "+id+", '"+l_text+"');\">"+l_text+"</a>";
			}
		}
		resultText +="<br/><br/><a href=\"javascript:newSearch();\">Clear Search Results</a>";
		if (results.length <= 0){
			resultText = "<strong>No Results Found</strong>";
			resultText +="<br/><br/><a href=\"javascript:newSearch();\">Clear Search Results</a>";
		}
		resultText = "<hr class=\"divider\"><strong>Search Results</strong><br />"+resultText;
		var sb = document.getElementById('searchResults');
		sb.className = 'searchShow';
		sb.innerHTML = resultText;
	}
	
	function clearS(obj){
		if (obj.value == "Enter Search Query")
			obj.value= "";
	}
		
	function redo(obj){
		if (obj.value == ""){
			obj.value= "Enter Search Query";
			var sb = document.getElementById('searchResults');
			sb.className = 'hidden';
		}
	}
	
	function newSearch(){
		var sbt = document.getElementById('search_form_box');
		sbt.value= "Enter Search Query";
		var sb = document.getElementById('searchResults');
		sb.className = 'hidden';
	}