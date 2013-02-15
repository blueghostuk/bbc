	
	var	img_url 		= 'http://bbc.blueghost.co.uk/images/';
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
	
	function setPopUps(){
		var url = "http://bbc.blueghost.co.uk/cookie_set.php?op=icons&value=";
		var ompu = document.getElementById('ompu');
		var tbpu = document.getElementById('pu');
		if (show_pop_ups){
			ompu.innerHTML = "Show On Map Pop Ups";
			tbpu.innerHTML = "Show On Map Pop Ups";
			show_pop_ups = false;
			url += "1";
		}else{
			ompu.innerHTML = "Hide On Map Pop Ups";
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
	
	
	function placeCopyLogo(logo, wMarker, img, size_x, size_y, html, move, moveend, clicker, reloader, zoom){
		if (!logo){
			logo	= new GIcon();
			logo.image = img_url+img;
			logo.shadow = img_url+'weather/trans.png';
			logo.iconSize = new GSize(size_x, size_y);
			logo.shadowSize = new GSize(1, 1);
			logo.iconAnchor = new GPoint((size_x/2), 0);
			logo.infoWindowAnchor = new GPoint(-5, -5);
		}
		
		var bounds = map.getBoundsLatLng();
		var width = bounds.maxX - bounds.minX;
		var height = bounds.maxY - bounds.minY;
		if (widescreen)
			var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.87));
		else
			var point = new GPoint(bounds.maxX-(width/2), bounds.maxY-(height*0.87));
		wMarker = new GMarker(point,logo);
		map.addOverlay(wMarker);
		
		GEvent.clearListeners(map, "move");
		GEvent.clearListeners(map, "moveend");
		GEvent.clearListeners(map, "zoom");
		GEvent.clearListeners(wMarker, "click");
		
		if (clicker){
			GEvent.addListener(wMarker, "click", function() {
				wMarker.openInfoWindowHtml(html);
			});
		}
		
		if (move){
			GEvent.addListener(map, "move", function() {
				wMarker = logoEventHandler(wMarker, logo, false, clicker, html);
  			});
		}
		
		if (moveend){
			GEvent.addListener(map, "moveend", function(){
				wMarker = logoEventHandler(wMarker, logo, reloader, clicker, html);
			});
		}
		
		if (zoom){
			GEvent.addListener(map, "zoom", function() {
				wMarker = logoEventHandler(wMarker, logo, reloader, clicker, html);
  			});
		}
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
		var tf	= document.getElementById('desc_footer');
		tf.innerHTML = '<hr class="divider" /><strong>Options</strong><hr class="divider" /><a href="javascript:zoomOut();">Reset Viewpoint</a><a id="ompu" href="javascript:setPopUps();">'+ompu+'</a><hr class="divider" /><div id="footer_text"></div><div id="traffic_links"></div>';
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
		if (sidebar)
			document.location.href= 'http://bbc.blueghost.co.uk/'+val+'_sb.php';
		else
			document.location.href= 'http://bbc.blueghost.co.uk/'+val+'.php';
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
			var marker = createTrafficCountyMarker(markers[i].getAttribute("location"), markers[i].getAttribute("title"), lat, lon, t_markers.length);
			map.addOverlay(marker);
			t_markers.push(marker);
		}
		var lo	= document.getElementById("loading");
		lo.innerHTML = "Loaded Traffic Data";
	}
	
	function doLDBXML(request){
		ldb_indicator 	= rail_markers.length;
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