/*
common6.js
Copyright 2005-7 Michael Pritchard
http://bbc.blueghost.co.uk/site4.php
http://www.blueghost.co.uk
Updated: 16 May 2009
		
*/

var img_url = "http://bbc.blueghost.co.uk/images/";
var main_site = "http://bbc.blueghost.co.uk/";
var weekday = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
var month = new Array("Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec");
var county_markers = [];
var loc;
var cen;
var map;
var mkrs = [];
var wICon;
var tICon;
var ldbICon;
var tPlace;
var tLat;
var tLon;
var traffic_setup = false;
var t_markers = [];
var rail_setup = false;
var rail_markers = [];
var traffic_markers = [];
var traffic_data = [];
var traffic_start = [];
var traffic_end = [];
var ldb_loaded = false;
var v = 0;
var last_open = -1;
var first_run = false;
var mw;
var ldb_indicator = -1;
var g_search_markers = [];
var g_s_m_html = [];
var menuHidden = true;
var menuHiddenSC = true;

function SetMapType(type) {
    map.setMapType(type);
}

function GlobalEvents() {
    GEvent.addListener(map, "moveend",
			function() {
			    if (weather) {
			        reload();
			    }
			}
		);
}

function UnLoad() {
    for (var i = 0; i < county_markers.length; i++)
        county_markers[i] = null;
    county_markers = null;
    for (var i = 0; i < mkrs.length; i++)
        mkrs[i] = null;
    mkrs = null;
    for (var i = 0; i < t_markers.length; i++)
        t_markers[i] = null;
    t_markers = null;
    for (var i = 0; i < rail_markers.length; i++)
        rail_markers[i] = null;
    rail_markers = null;
    for (var i = 0; i < traffic_markers.length; i++)
        traffic_markers[i] = null;
    traffic_markers = null;
    for (var i = 0; i < traffic_data.length; i++)
        traffic_data[i] = null;
    traffic_data = null;
    for (var i = 0; i < traffic_start.length; i++)
        traffic_start[i] = null;
    traffic_start = null;
    for (var i = 0; i < traffic_end.length; i++)
        traffic_end[i] = null;
    traffic_end = null;
    GUnload();
}

function reload() {
    UpdateDisplay();
    if (weather) {
        ShowLoadingMessage("Loading Weather Data...");
        ReloadWeather();
    }
}

var lastWeatherSearch = "";
var lastWeatherResult;

function ReloadWeather() {

    var bounds = map.getBounds();
    var maxY = bounds.getNorthEast().lat();
    var minY = bounds.getSouthWest().lat();
    var maxX = bounds.getNorthEast().lng();
    var minX = bounds.getSouthWest().lng();

    var status = "Loading Weather Data ...";
    if (day > 1 && lastWeatherSearch == ("" + maxY + minY + maxX + minX)) {
        showWeatherJsonResults(lastWeatherResult);
    } else {
        lastWeatherSearch = "" + maxY + minY + maxX + minX;
        doJsonRequest("weatherSearch", { lat_max: maxY, lat_min: minY, lon_max: maxX, lon_min: minX, maximum: "100", day: day }, showWeatherJsonResults, status);
    }
}

function showWeatherJsonResults(results) {
    lastWeatherResult = results;
    var tmp_mkrs = [];
    var link_text = [];

    for (var z = 0; z < results.locations.length; z++) {
        var markers = results.locations[z].days;
        var links = results.locations[z].links;
        for (var i = 0; i < links.length; i++) {
            link_text.push('<a href="' + links[i].href + '" target="_blank">' + links[i].title + '</a>');
        }
        for (var i = 0; i < markers.length; i++) {
            var places = 0;
            if (markers[i].rel == day) {/*only add if relevant day*/
                var lin = [];
                var j2 = (places * 3) + 3;
                for (var j = (places * 3); j < j2; j++) {
                    lin.push(link_text[j]);
                }
                var point = new GLatLng(markers[i].lat, markers[i].lon);
                var marker = CreateWeatherMarker(
													 point,
													 markers[i].icon,
													 '64',
													 markers[i].name,
													 markers[i].temp,
													 markers[i].time,
													 lin,
													 tmp_mkrs.length
													 );
                tmp_mkrs.push(marker);
                places++;
            }
        }
    }
    var tmp_2 = [];
    for (var i = 0; i < mkrs.length; i++) {
        var found = false
        for (var j = 0; j < tmp_mkrs.length; j++) {
            if (mkrs[i] == tmp_mkrs[j]) {
                found = true;
                tmp_mkrs[j] = null;
                tmp_2.push(mkrs[i]);
                j = tmp_mkrs.length;
            }
        }
        if (!found) {
            map.removeOverlay(mkrs[i]);
        }
    }
    //add any more new markers
    for (var k = 0; k < tmp_mkrs.length; k++) {
        if (tmp_mkrs[k] != null) {
            map.addOverlay(tmp_mkrs[k]);
            tmp_2.push(tmp_mkrs[k]);
        }
    }
    mkrs = tmp_2;
    ShowMessage("Loaded Weather Data");
}


function ReloadRail() {
    if (rail_setup) {
        if (ldb_indicator != -1) {
            for (var i = 0; i < rail_markers.length && i < ldb_indicator; i++)
                map.addOverlay(rail_markers[i]);
        }
        else {
            for (var i = 0; i < rail_markers.length; i++)
                map.addOverlay(rail_markers[i]);
        }
        ShowMessage('Loaded Rail Data');
        if (ldb_visible)
            doLDB();
    }
    else {
        var url = main_site + 'travel_data/railways.xml';
        var status = 'Loading Rail Data ...';
        doXMLHTTPRequest(ReloadRailXML, url, status);
        rail_setup = true;
    }
}

function ReloadTraffic() {
    var index = -1;
    for (var i = 0; i < traffic_data.length; i++) {
        if (traffic_data[i] == tPlace) {
            index = i;
            break;
        }
    }
    if (index != -1) {
        for (var start = traffic_start[index]; start < traffic_end[index]; start++)
            map.addOverlay(traffic_markers[start]);
        ShowMessage('Loaded Traffic Data');
    }
    else {
        var status = 'Loading Traffic Data ...';
        doJsonRequest('trafficSearch', { area: tPlace }, ReloadTrafficJson, status);
    }
}

var trafficDirections;

function CreateDirectionalTrafficLink(result, tLat, tLon, length) {
	var icon_url = img_url + result.severity + '.png';
    var icon = makeIcon(icon_url, 19, 40);
	
	var point = new GLatLng(result.lat, result.lon);
	var marker = new GMarker(point, icon);

	html = '<strong>Traffic Alert</strong><br /><br />' + result.summary;
	html += '<br /><br /><strong>Source:' + result.src + '</strong>';
	html += '<br /><strong>Started @</strong> ' + result.start;
	html += '<br /><strong>End @</strong> ' + result.stop;
	html += '<br /><br /><strong>Options</strong>';
	html += '<br /><a href="javascript:zoomTo(' + result.lat + ',' + result.lon + ', 3);">Zoom In</a>';
	html += '<br /><a href="javascript:zoomTo(' + tLat + ',' + tLon + ', 7);">Zoom Out to Area</a>';
	html += '<br /><a href="javascript:zoomOut();">Zoom Out Fully</a>';

	GEvent.addListener(marker, "click",
		function() {
			map.setCenter(point, 15);
			ShowMapClickText(result.summary);
		}
		);
	GEvent.addListener(marker, "mouseover",
		function() {
			ShowMapClickText(result.summary + "<br /><br /><strong>Click Marker to Zoom In</strong>");
			if (result.secondLocation){
				trafficDirections.load("from:" + result.lat + " " + result.lon + " to: " + result.secondLocation.lat + " " + result.secondLocation.lon,
								 { "travelMode" : G_TRAVEL_MODE_DRIVING, "getPolyline" : true, "getSteps" : false });
			}
		}
		);
	return marker;
}

function ReloadTrafficJson(results) {
    traffic_data.push(tPlace);
    traffic_start.push(traffic_markers.length);
    for (var i = 0; i < results.length; i++) {
        var marker = CreateDirectionalTrafficLink(results[i], tLat, tLon, traffic_markers.length);
        map.addOverlay(marker);
        traffic_markers.push(marker);
    }
    traffic_end.push(traffic_markers.length);
    ShowMessage('Loaded Traffic Data');
}


function GenPermalink() {
    var type = "";
    if (rail) {
        if (ldb_visible) {
            type = "rail&ldb=y";
            if (last_open != -1)
                type += "&marker=" + last_open;
        }
        else
            type = "rail";
    }
    if (weather) {
        type = "weather";
        if (last_open != -1)
            type += "&marker=" + last_open;
        type += "&day=" + day;
    }
    if (traffic) {
        type = "traffic";
        if (m_visible)
            type += "&mw=y";
    }
    return p_link = 'type=' + type + '&lon=' + map.getCenter().lng() + '&lat=' + map.getCenter().lat() + '&zl=' + map.getZoom();
}

function makeIcon(img, size_x, size_y) {
    var mi = new GIcon();
    mi.image = img;
    mi.shadow = img_url + 'weather/trans.png';
    mi.iconSize = new GSize(size_x, size_y);
    mi.shadowSize = new GSize(1, 1);
    mi.iconAnchor = new GLatLng((size_y / 2), (size_x / 2));
    mi.infoWindowAnchor = new GLatLng(0, (size_x / 2));
    return mi;
}

function doXMLHTTPRequest(f_name, url, status) {
    var request = GXmlHttp.create();
    ShowLoadingMessage(status);
    request.open("GET", url, true);
    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            if (request.status == 200) {
                f_name(request);
            }
            else {
                if (request.status == 500) {
                    ShowErrorMessage(status);
                }
            }
        }
    };
    request.send(null);
}

function zoomOut() {
    map.setCenter(cen, 6);
}

function zoomTo(lat, lon, size) {
    if (lat == 0 && lon == 0) {
        map.setCenter(cen, 6);
    }
    else {
        var p = new GLatLng(lat, lon);
        map.setCenter(p, 17 - size);
    }
}

function showHideLDB() {
    if (ldb_visible) {
        ldb_visible = false;
        var val = "Show";
    }
    else {
        ldb_visible = true;
        var val = "Hide";
    }
    var ldb = GetElementById('rail_ldb_changer');
    ldb.innerHTML = val + " Live Departure Boards";
    doLDB();
}

function doLDB() {
    if (ldb_visible) {
        if (ldb_loaded) {
            for (var i = ldb_indicator; i < rail_markers.length; i++)
                map.addOverlay(rail_markers[i]);
            ShowMessage('Loaded LDB Data');
        }
        else {
            var url = main_site + "ldb_data/stations.xml";
            var status = 'Loading LDB Data ...';
            doXMLHTTPRequest(doLDBXML, url, status);
            ldb_loaded = true;
        }
    }
    else {
        if (ldb_indicator != -1) {
            for (var i = ldb_indicator; i < rail_markers.length; i++)
                map.removeOverlay(rail_markers[i]);
        }
    }
}

function nextDay() {
    day++;
    reload();
}

function previousDay() {
    day--;
    reload();
}

/**
* Resets all traffic markers to say "Show Delays"
*/
function ResetTraffic() {
    //not yet implemented	
}

function GoTo(val) {
    if (traffic)
        ResetTraffic();
    var cont = false;

    switch (val) {
        case 'traffic':
            if (!traffic) {
                map.clearOverlays();
                traffic = true;
                rail = false;
                weather = false;
                SetupTraffic();
                cont = true;
            }
            break;
        case 'weather':
            if (!weather) {
                map.clearOverlays();
                weather = true;
                rail = false;
                traffic = false;
                SetupWeather();
                cont = true;
            }
            else {
                map.clearOverlays();
                weather = true;
                rail = false;
                traffic = false;
                cont = true;
            }
            break;
        case 'rail':
            if (!rail) {
                map.clearOverlays();
                rail = true;
                weather = false;
                traffic = false;
                SetupRail();
                cont = true;
            }
            break;
    }
    if (cont) {
        reload();
    }
}

function UpdateDisplay() {
    var nav = '<strong>Map Options</strong><br /><br />';
    if (traffic) {
        if (mw)
            nav += mw.innerHTML;
    }
    if (weather) {
        var today = new Date();
        today.setDate(today.getDate() + day);
        nav += '<a href="javascript:if (day >0){previousDay();}else{alert(\'No Past Data Available\');}" title="Show Weather for the Previous Day">Previous</a> | <a href="javascript:if(day < 4){nextDay();}else{alert(\'No More Data Available\');}"  title="Show Weather for the Next Day">Next Day</a>';
        nav += '<br />Weather for: ' + weekday[today.getDay()] + ' ' + today.getDate() + ' ' + month[today.getMonth()] + ' ' + today.getFullYear();
    }
    if (rail) {
        if (ldb_visible) {
            nav += '<a id="rail_ldb_changer" href="javascript:showHideLDB();" title="Hide Live Data for Stations">Hide Live Departure Boards</a>';
        }
        else {
            nav += '<a id="rail_ldb_changer" href="javascript:showHideLDB();" title="Show Live Data for Stations">Show Live Departure Boards</a>';
        }
    }
    ShowExtraInfoBox(nav);
}

function OpenHelp() {
    window.open(main_site + 'help.php', 'helpWindow', 'width=600,height=600,scrollbars=yes');
}

function OpenAbout() {
    window.open(main_site + 'about.html', 'aboutWindow');
}

function ReloadRailXML(request) {
    var xmlDoc = request.responseXML;
    var markers = xmlDoc.documentElement.getElementsByTagName("t_alert");
    for (var i = 0; i < markers.length; i++) {
        var lat = markers[i].getAttribute("lat");
        var lon = markers[i].getAttribute("lon");
        var point = new GLatLng(lat, lon);
        var logo = markers[i].getAttribute("severity");
        var marker = createRailMarker(
										   point,
										   markers[i].getAttribute("summary"),
										   markers[i].getAttribute("src"),
										   markers[i].getAttribute("type"),
										   markers[i].getAttribute("start"),
										   markers[i].getAttribute("stop"),
										   logo,
										   rail_markers.length
										   );
        marker.title = markers[i].getAttribute("summary");
        map.addOverlay(marker);
        rail_markers.push(marker);
    }
    ShowMessage('Loaded Rail Data');
    if (ldb_visible)
        doLDB();
}

function SetupTrafficXML(request) {
    var xmlDoc = request.responseXML;
    var markers = xmlDoc.documentElement.getElementsByTagName("t_alert");
    for (var i = 0; i < markers.length; i++) {
        var lat = markers[i].getAttribute("lat");
        var lon = markers[i].getAttribute("lon");
        var id = markers[i].getAttribute("id");
        var marker = CreateTrafficCountyMarker(
												   markers[i].getAttribute("location"),
												   markers[i].getAttribute("title"),
												   lat,
												   lon,
												   t_markers.length,
												   id
												   );
        map.addOverlay(marker);
        t_markers.push(marker);
    }
    ShowMessage('Loaded Traffic Data');
}

function doLDBXML(request) {
    ldb_indicator = rail_markers.length;
    var xmlDoc = request.responseXML;
    var markers = xmlDoc.documentElement.getElementsByTagName("station");
    for (var i = 0; i < markers.length; i++) {
        var lat = markers[i].getAttribute("lat");
        var lon = markers[i].getAttribute("lon");
        var point = new GLatLng(lat, lon);
        var marker = CreateLDBMarker(point,
										  markers[i].getAttribute("name"),
										  markers[i].getAttribute("code"),
										  i
										  );
        map.addOverlay(marker);
        rail_markers.push(marker);
    }
    ShowMessage('Loaded LDB Stations');
}

function doSearch(query) {
    if (query.length > 0) {
        var modeValue = "weather";
        var statusMsg = "Searching Weather Locations";
        if (!weather) {
            if (rail) {
                modeValue = "rail";
                statusMsg = "Searching Railway Stations";
            } else {
                if (traffic) {
                    modeValue = "travel";
                    statusMsg = "Searching Traffic Locations";
                }
            }
        }
        doJsonRequest("doSearchJson.php", { mode: modeValue, query: query }, showJsonSearchResults, statusMsg);
    }
}

function doJsonRequest(base_url, params, callback, statusMsg) {
    // TODO
    // setup error handling to ShowErrorMessage(statusMsg)
    ShowLoadingMessage(statusMsg);
    $.getJSON(base_url, params, callback);
}

function showJsonSearchResults(results) {
    var resultText = "";
    if (results.length <= 0) {
        resultText = "<strong>No Results Found</strong><br/><br/><a href=\"javascript:newSearch();\">Clear Search Results</a>";
    } else {
        if (rail) {
            for (var i = 0; i < results.length; i++) {
                resultText += "<br /><a href=\"javascript:ShowLiveDeparturesFromSearch('" + results[i].code + "', '" + results[i].l_text + "', " + results[i].lat + ", " + results[i].lon + ");\">" + results[i].l_text + " ( " + results[i].code + " )</a>";
                //implement adding ldb marker
            }
        } else {
            for (var i = 0; i < results.length; i++) {
                if (!traffic) {
                    resultText += "<br /><a href=\"javascript:zoomTo(" + results[i].lat + ", " + results[i].lon + ", 4);\">" + results[i].l_text + "</a>";
                } else {
                    resultText += "<br /><a href=\"javascript:goToTraffic('" + results[i].fname + "', " + results[i].lat + ", " + results[i].lon + ", " + results[i].id + ", '" + results[i].l_text + "');\">" + results[i].l_text + "</a>";
                }
            }
        }
        resultText += "<br/><br/><a href=\"javascript:newSearch();\">Clear Search Results</a>";
    }
    var addonText = 'Weather Locations';
    if (rail) {
        addonText = 'Railway Stations';
    }
    if (traffic) {
        addonText = 'Traffic Areas';
    }
    resultText = "<hr class=\"divider\"><strong>Search Results</strong><br />" + resultText;
    $("#searchResults").html(resultText);
    $("#searchResults").toggleClass("searchShow");
    ShowMessage('Search of ' + addonText + ' Completed');
}

function ShowLiveDeparturesFromSearch(code, l_text, lat, lon) {
    var point = new GLatLng(lat, lon);
    var i = rail_markers.length;
    var marker = CreateLDBMarker(point,
										  l_text,
										  code,
										  i
										  );
    map.addOverlay(marker);
    rail_markers.push(marker);
    zoomTo(lat, lon, 2);
}

function clearS(obj) {
    if (obj.value == "Enter Search Query")
        obj.value = "";
}

function redo(obj) {
    if (obj.value == "") {
        obj.value = "Enter Search Query";
        var sb = GetElementById('searchResults');
        sb.className = 'hidden';
    }
}

function newSearch() {
    var sbt = GetElementById('search_form_box');
    sbt.value = "Enter Search Query";
    var sb = GetElementById('searchResults');
    sb.innerHTML = '';
}

/* CREATE MARKER METHODS */

function createRailMarker(point, html, src, type, start, stp, image, id) {
    var icon_url = img_url + image + ".png";
    var icon = makeIcon(icon_url, 19, 40);
    var marker = new GMarker(point, icon);

    html = '<strong>Rail Alert</strong><br /><br />' + html;
    html += '<br /><br /><strong>Source:' + src + '</strong>';
    html += '<br /><strong>Started @</strong> ' + start;
    html += '<br /><strong>End @</strong> ' + stp;
    html += '<br /><br /><strong>Options</strong>';
    html += '<br /><a href="javascript:zoomOut();">Zoom Out</a>';

    GEvent.addListener(marker, "click",
		function() {
		    map.setCenter(point, 15);
		    ShowMapClickText(html);
		}
		);
    GEvent.addListener(marker, "mouseover",
		function() {
		    ShowMapClickText(html + "<br /><br /><strong>Click Marker to Zoom In</strong>");
		}
		);
    return marker;
}

/* RELOAD TRAFFIC STUFF */

function goToTraffic(loc, lat, lon, id, title, id2) {
    if (!id2)
        id2 = id;
    var iframe = county_markers[id2];
    var href = '<a>' + title + '</a><br><a href="javascript:removeTraffic(\'' + loc + '\', ' + lat + ', ' + lon + ', ' + id + ', \'' + title + '\', ' + id2 + ');" title="Hide Delays in ' + title + '">Hide Delays</a><br><a href="javascript:zoomOut();" title="Zoom Out">Zoom Out</a>';
    if (iframe != null && iframe.innerHTML == href) {
        removeTraffic(loc, lat, lon, id, title);
        return;
    }

    if (id == 0) {/*MOTORWAYS*/
        var m_link = GetElementById('m_link');
        m_link.innerHTML = 'Hide Motorway Delays';
        m_link.href = 'javascript:removeTraffic(\'' + loc + '\', ' + lat + ', ' + lon + ', ' + id + ', \'' + title + '\', ' + id2 + ');';
        m_link.title = 'Hide Delays in ' + title;
        m_visible = true;
    }
    else {
        county_markers[id2].innerHTML = '<a>' + title + '</a><br/><a href="javascript:removeTraffic(\'' + loc + '\', ' + lat + ', ' + lon + ', ' + id + ', \'' + title + '\', ' + id2 + ');" title="Hide Delays in ' + title + '">Hide Delays</a><br/><a href="javascript:zoomOut();" title="Zoom Out">Zoom Out</a>';
        var point = new GLatLng(lat, lon);
        map.setCenter(point, 10);
    }
    tPlace = loc;
    tLat = lat;
    tLon = lon;
    ReloadTraffic();
    /*}*/
}

function removeTraffic(loc, lat, lon, id, title, id2) {
    if (!id2)
        id2 = id;
    var index = 0;
    for (var i = 0; i < traffic_data.length; i++) {
        if (traffic_data[i] == loc) {
            index = i;
            i = traffic_data.length + 1;
        }
    }
    for (var start = traffic_start[index]; start < traffic_end[index]; start++)
        map.removeOverlay(traffic_markers[start]);

    if (id == 0) {/*MOTORWAYS*/
        var m_link = GetElementById('m_link');
        m_link.innerHTML = 'Show Motorway Delays';
        m_link.href = 'javascript:goToTraffic(\'' + loc + '\', ' + lat + ', ' + lon + ', ' + id + ', \'' + title + '\', ' + id2 + ');';
        m_link.title = 'Show Delays in ' + title;
        m_visible = false;
    }
    else {
        county_markers[id2].innerHTML = '<a>' + title + '</a><br/><a href="javascript:goToTraffic(\'' + loc + '\', ' + lat + ', ' + lon + ', ' + id + ', \'' + title + '\', ' + id2 + ');" title="Show Delays in ' + title + '">Show Delays</a><br/><a href="javascript:zoomOut();" title="Zoom Out">Zoom Out</a>';
    }
    //HideExtraInfoBox();
}

function loadLDB(marker, name, code) {
    var request = GXmlHttp.create();
    var url = main_site + 'includes/rail.cgi?ldb=' + code;
    ShowLoadingMessage('Loading LDBData ...');
    var st_title = '<td colspan="7" title="Data Source" class="st_name"><strong>' + name + '</strong></td></tr>';
    var copy_text = '<td colspan="7" title="Data Source" class="copy_text"><a href="http://www.livedepartureboards.co.uk/ldb/summary.aspx?T=' + code + '" target="_blank">Data from National Rail Live Departure Boards (Virgin Trains Branded Site)</a></td></tr>';
    var close_box = '<div align="right"><a onclick="HideMapClickText();" href="#" title="Close Information Box">X</a></div>';
    request.open("GET", url, true);
    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            if (request.status == 200) {
                var xmlDoc = request.responseXML;
                if (xmlDoc.documentElement) {
                    var data = xmlDoc.documentElement.getElementsByTagName('table');
                    var divs = xmlDoc.documentElement.getElementsByTagName('div');
                    var a_d = -1;
                    for (var i = 0; i < divs.length; i++) {
                        if (divs[i].className == "maint_message") {
                            a_d = i;
                            i = divs.length;
                        }
                    }

                    if (a_d != -1) {
                        var a_t = '<tr><td colspan="7" title="Problems" class="alert">' + divs[a_d].innerHTML + '</td></tr>';
                        ShowMapClickTextWithStyle("<div class=\"ldb_tbl\"><table class=\"ldb_tbl\" style=\"white-space: nowrap;\">" + st_title + a_t + data[0].innerHTML + copy_text + "</table></div>" + close_box, 'LDBinformationMessage');
                    }
                    else
                        ShowMapClickTextWithStyle("<div class=\"ldb_tbl\"><table class=\"ldb_tbl\">" + st_title + data[0].innerHTML + copy_text + "</table></div>" + close_box, 'LDBinformationMessage');
                    ShowMessage('LDB Data Loaded');
                }
                else {
                    xmlDoc = GXml.parse(request.responseText);
                    if (xmlDoc.documentElement) {
                        var data = xmlDoc.documentElement.getElementsByTagName('table');
                        var divs = xmlDoc.documentElement.getElementsByTagName('div');
                        var a_d = -1;
                        for (var i = 0; i < divs.length; i++) {
                            if (divs[i].className == "maint_message") {
                                a_d = i;
                                i = divs.length;
                            }
                        }

                        if (a_d != -1) {
                            var a_t = '<tr><td colspan="7" title="Problems" class="alert">' + divs[a_d].innerHTML + '</td></tr>';
                            ShowMapClickTextWithStyle("<div class=\"ldb_tbl\"><table class=\"ldb_tbl\">" + st_title + a_t + data[0].innerHTML + copy_text + "</table></div>" + close_box, 'LDBinformationMessage');
                        }
                        else
                            ShowMapClickTextWithStyle("<div class=\"ldb_tbl\"><table class=\"ldb_tbl\">" + st_title + data[0].innerHTML + copy_text + "</table></div>" + close_box, 'LDBinformationMessage');
                        ShowMessage('LDB Data Loaded');
                    }
                    else {
                        xmlDoc = request.responseText;
                        ShowMessage('LDB Function not fully supported in this Web Browser');
                        ShowMapClickTextWithStyle(xmlDoc + close_box, 'LDBinformationMessage');
                        //marker.openInfoWindowHtml(xmlDoc);
                    }
                }
            }
            else {
                ShowMessage('ERROR loading LDB Data');
            }
        }
    }
    request.send(null);
}

function SetupTraffic() {
    HideExtraInfoBox();
    var html = 'supported by <a href="http://backstage.bbc.co.uk" target="_blank">backstage.bbc.co.uk</a>';
    ShowCopyText(html);

    //add each county
    if (traffic_setup) {
        for (var i = 0; i < t_markers.length; i++)
            map.addOverlay(t_markers[i]);
        //var info_html = GetElementById('traffic_motorways');
    }
    else {
        //icon for each county
        if (!tICon) {
            var icon_url = img_url + 'mm_20_white.png';
            var img_shadow = img_url + 'mm_20_shadow.png';
            tICon = makeIcon(icon_url, 12, 20);
            tICon.shadow = img_shadow;
        }
        var url = main_site + 'travel_data/locations.xml';
        var status = 'Loading Traffic Data ...';
        doXMLHTTPRequest(SetupTrafficXML, url, status);

        //set footer
        var id = county_markers.length;
        mw = document.createElement("div");
        mw.id = 'traffic_motorways';
        mw.className = 'weather_link';
        mw.innerHTML = '<a id="m_link" href="javascript:goToTraffic(\'motorways\', \'0\', \'0\', ' + id + ', \'UK Motorways\');" title="See Delays on the Motorways">Show Motorway Delays</a>';
        county_markers.push(mw);

        traffic_setup = true;
    }
}

function SetupRail() {
    HideExtraInfoBox();
    var html = 'supported by <a href="http://backstage.bbc.co.uk" target="_blank">backstage.bbc.co.uk</a>';
    ShowCopyText(html);

    if (!ldbICon) {
        var icon_url = img_url + 'mm_20_green.png';
        var img_shadow = img_url + 'mm_20_shadow.png';
        ldbICon = makeIcon(icon_url, 12, 20);
        ldbICon.shadow = img_shadow;
    }

    ReloadRail(); //call only needed here
}

function DoGoogleLocalSearch() {
    var sResults = GetElementById("searchControlResults");
    SetElementInnerHtml(sResults, '<img src="http://bbc.blueghost.co.uk/images/animated_loading.gif" alt="Loading" />&nbsp;');
    var lst = GetElementById("googleLocalSearchText");
    if (!lst.value) {
        SetElementInnerHtml(sResults, '&nbsp;');
        return false;
    }
    localSearch.execute(lst.value);
    return false;
}

function SetupGoogleDirectionsSearch() {
    // create the search object
    directionsSearch = new GDirections(map, GetElementById('directionsControlResults'));

    // add event listeners to errors and load
    GEvent.addListener(directionsSearch, "error", OnDirectionsSearchError);
    GEvent.addListener(directionsSearch, "load", OnDirectionsSearchComplete);
	
	trafficDirections = new GDirections(map);
}

function SetupGoolgeAjaxSearch() {
    // Add in a full set of searchers
    localSearch = new GlocalSearch();

    // Set the Local Search center point
    localSearch.setCenterPoint("TF3 UK");
    localSearch.setAddressLookupMode(GlocalSearch.ADDRESS_LOOKUP_ENABLED);

    localSearch.setSearchCompleteCallback(this, OnSearchComplete);
}

function DoGoogleDirectionsSearch() {
    var sResults = GetElementById("directionsControlResults");
    SetElementInnerHtml(sResults, '<img src="http://bbc.blueghost.co.uk/images/animated_loading.gif" alt="Loading" />&nbsp;');
    var lst = GetElementById("googleDirectionsSearchText");
    if (!lst.value) {
        SetElementInnerHtml(sResults, '&nbsp;');
        return false;
    }
    var gOptions = {
        locale: 'en_GB'
    }
    directionsSearch.load(lst.value, gOptions);
    //return false;
}

function OnDirectionsSearchError() {
    SetElementInnerHtmlById('directionsControlResults', directionsSearch.getStatus().code);
}

function OnDirectionsSearchComplete() {
    SetElementInnerHtmlById('directionsControlResults', directionsSearch.getSummaryHtml());
}

function CreateSearchResultMarker(lng, lat, title) {
    var marker = new GMarker(new GPoint(parseFloat(lng), parseFloat(lat)), ldbICon);
    markerHTML = '<strong>' + title + '</strong><br />';
    markerHTML += '<a href="javascript:RemoveSearchMarker(' + g_search_markers.length + ');">Remove this Marker</a><br />';
    markerHTML += '<a href="javascript:zoomTo(' + lat + ', ' + lng + ', 3);">Zoom To This Point</a><br />';

    var gmid = g_s_m_html.length;
    g_s_m_html.push(markerHTML);

    GEvent.addListener(marker, "click", function() {
        marker.openInfoWindow(g_s_m_html[gmid]);
    });

    GEvent.addListener(marker, "mouseover", function() {
        ShowMapClickText(g_s_m_html[gmid]);
    });

    GEvent.addListener(marker, "onmouseover",
			function() {
			    ShowMapClickText(g_s_m_html[gmid]);
			}
		);
    g_search_markers.push(marker);
    map.addOverlay(marker);
    zoomTo(lat, lng, 5);
}

function RemoveSearchMarker(id) {
    map.closeInfoWindow();
    map.removeOverlay(g_search_markers[id]);
}

function ClearAjaxResults() {
    SetElementInnerHtmlById("searchControlResults", "&nbsp;");
}

function OnSearchComplete() {
    var sResults = GetElementById("searchControlResults");
    if (!localSearch.results) {
        SetElementInnerHtml(sResults, '&nbsp;');
        return;
    }

    if (localSearch.results.length == 0) {
        SetElementInnerHtml(sResults, '<strong>No Results</string><br /><hr />');
    }
    else {
        SetElementInnerHtml(sResults, '<strong>Search Results</string><br /><a href="javascript:ClearAjaxResults()">Clear Results</a><br /><hr />');
        for (var i = 0; i < localSearch.results.length; i++) {
            sResults.innerHTML += '<a title="Click Here to Add a marker to the map for ' + localSearch.results[i].title + '" href="javascript:CreateSearchResultMarker(' + localSearch.results[i].lng + ',' + localSearch.results[i].lat + ',\'' + localSearch.results[i].title + '\');">' + localSearch.results[i].title + '</a><br />';
        }
    }
    localSearch.clearAllResults();
}

function SetupWeather() {
    HideExtraInfoBox();
    var html = 'Weather data provided by <a href="http://www.weather.com/?prod=xoap&par=' + weatherId + '" title="Go to weather.com" target="_blank">weather.com &reg;</a>';
    ShowCopyText(html);
}