// bbc.blueghost.co.uk Control
// shows menu options
function BlueGhostBBCControl() {
}
BlueGhostBBCControl.prototype = new GControl();

// Creates a one DIV for each of the buttons and places them in a container
// DIV which is returned as our control element. We add the control to
// to the map container and return the element for the map class to
// position properly.
BlueGhostBBCControl.prototype.initialize = function(map) {
	var container = document.createElement("div");
	container.id = 'BlueGhostBBCControl';

	  var weatherDiv = document.createElement("div");
	  weatherDiv.id = 'wc';
	  weatherDiv.title = 'Click Here to Load the Weather Data Overlay';
	  this.setButtonStyle_(weatherDiv);
	  container.appendChild(weatherDiv);
	  weatherDiv.appendChild(document.createTextNode("Weather"));
	  GEvent.addDomListener(weatherDiv, "click", function() {
		GoTo('weather');
	  });
	  GEvent.addDomListener(weatherDiv, "mouseover", function() {
		SetStyle(weatherDiv, 'itemHover');
	  });
	  GEvent.addDomListener(weatherDiv, "mouseout", function() {
		SetStyle(weatherDiv, 'item');
	  });
	  
	
	  var trafficDiv = document.createElement("div");
	  trafficDiv.id = 'tc';
	  trafficDiv.title = 'Click Here to Load the Traffic Data Overlay';
	  this.setButtonStyle_(trafficDiv);
	  container.appendChild(trafficDiv);
	  trafficDiv.appendChild(document.createTextNode("Traffic"));
	  GEvent.addDomListener(trafficDiv, "click", function() {
		GoTo('traffic')
	  });
	  GEvent.addDomListener(trafficDiv, "mouseover", function() {
		SetStyle(trafficDiv, 'itemHover');
	  });
	  GEvent.addDomListener(trafficDiv, "mouseout", function() {
		SetStyle(trafficDiv, 'item');
	  });
	  
	  var trainDiv = document.createElement("div");
	  trainDiv.id = 'rc';
	  trainDiv.title = 'Click Here to Load the Train Data Overlay';
	  this.setButtonStyle_(trainDiv);
	  container.appendChild(trainDiv);
	  trainDiv.appendChild(document.createTextNode("Rail"));
	  GEvent.addDomListener(trainDiv, "click", function() {
		GoTo('rail')
	  });
	  GEvent.addDomListener(trainDiv, "mouseover", function() {
		SetStyle(trainDiv, 'itemHover');
	  });
	  GEvent.addDomListener(trainDiv, "mouseout", function() {
		SetStyle(trainDiv, 'item');
	  });
	  
	  var permalinkDiv = document.createElement("div");
	  permalinkDiv.id = 'wc';
	  permalinkDiv.title = 'Click Here to Get a Permanent Link to this page';
	  this.setButtonStyle_(permalinkDiv);
	  container.appendChild(permalinkDiv);
	  permalinkDiv.appendChild(document.createTextNode("Permalink"));
	  GEvent.addDomListener(permalinkDiv, "click", function() {
		GetPermalink();
	  });
	  GEvent.addDomListener(permalinkDiv, "mouseover", function() {
		SetStyle(permalinkDiv, 'itemHover');
	  });
	  GEvent.addDomListener(permalinkDiv, "mouseout", function() {
		SetStyle(permalinkDiv, 'item');
	  });
	  
	  var googleEarthDiv = document.createElement("div");
	  googleEarthDiv.id = 'wc';
	  googleEarthDiv.title = 'Click Here to Get this data in Google Earth';
	  this.setButtonStyle_(googleEarthDiv);
	  container.appendChild(googleEarthDiv);
	  googleEarthDiv.innerHTML = '<img src="images/google_earth_feed.gif" width="93" height="17" alt="Google Earth" title="Click here to get this data in Google Earth" />';
	  GEvent.addDomListener(googleEarthDiv, "click", function() {
		document.location.href='http://bbc.blueghost.co.uk/earth/';
	  });
	  GEvent.addDomListener(googleEarthDiv, "mouseover", function() {
		SetStyle(googleEarthDiv, 'itemHover');
	  });
	  GEvent.addDomListener(googleEarthDiv, "mouseout", function() {
		SetStyle(googleEarthDiv, 'item');
	  });
	  
	  var searchDiv = document.createElement("div");
	  searchDiv.id = 'sl';
	  searchDiv.title = 'Click Here to Search';
	  this.setButtonStyle_(searchDiv);
	  container.appendChild(searchDiv);
	  searchDiv.appendChild(document.createTextNode("Search"));
	  GEvent.addDomListener(searchDiv, "click", function() {
		ShowSearchBox();
	  });
	  GEvent.addDomListener(searchDiv, "mouseover", function() {
		SetStyle(searchDiv, 'itemHover');
	  });
	  GEvent.addDomListener(searchDiv, "mouseout", function() {
		SetStyle(searchDiv, 'item');
	  });
	  
	  var helpDiv = document.createElement("div");
	  helpDiv.id = 'hl';
	  helpDiv.title = 'Click Here for Help (new pop-up window)';
	  this.setButtonStyle_(helpDiv);
	  container.appendChild(helpDiv);
	  helpDiv.appendChild(document.createTextNode("Help"));
	  GEvent.addDomListener(helpDiv, "click", function() {
		OpenHelp();
	  });
	  GEvent.addDomListener(helpDiv, "mouseover", function() {
		SetStyle(helpDiv, 'itemHover');
	  });
	  GEvent.addDomListener(helpDiv, "mouseout", function() {
		SetStyle(helpDiv, 'item');
	  });
	  
	  var aboutDiv = document.createElement("div");
	  aboutDiv.id = 'hl';
	  aboutDiv.title = 'Click Here for the about page(new window)';
	  this.setButtonStyle_(aboutDiv);
	  container.appendChild(aboutDiv);
	  aboutDiv.appendChild(document.createTextNode("About"));
	  GEvent.addDomListener(aboutDiv, "click", function() {
		OpenAbout();
	  });
	  GEvent.addDomListener(aboutDiv, "mouseover", function() {
		SetStyle(aboutDiv, 'itemHover');
	  });
	  GEvent.addDomListener(aboutDiv, "mouseout", function() {
		SetStyle(aboutDiv, 'item');
	  });
	  
	  var mapDiv = document.createElement("div");
	  mapDiv.id = 'hl';
	  this.setButtonStyle_(mapDiv);
	  container.appendChild(mapDiv);
	  mapDiv.appendChild(document.createTextNode("MAP"));
	  GEvent.addDomListener(mapDiv, "click", function() {
		SetMapType(G_NORMAL_MAP);
	  });
	  GEvent.addDomListener(mapDiv, "mouseover", function() {
		SetStyle(mapDiv, 'itemHover');
	  });
	  GEvent.addDomListener(mapDiv, "mouseout", function() {
		SetStyle(mapDiv, 'item');
	  });
	  
	  var satDiv = document.createElement("div");
	  satDiv.id = 'hl';
	  this.setButtonStyle_(satDiv);
	  container.appendChild(satDiv);
	  satDiv.appendChild(document.createTextNode("SATELLITE"));
	  GEvent.addDomListener(satDiv, "click", function() {
		SetMapType(G_SATELLITE_MAP);
	  });
	  GEvent.addDomListener(satDiv, "mouseover", function() {
		SetStyle(satDiv, 'itemHover');
	  });
	  GEvent.addDomListener(satDiv, "mouseout", function() {
		SetStyle(satDiv, 'item');
	  });
	  
	  var hybridDiv = document.createElement("div");
	  hybridDiv.id = 'hl';
	  this.setButtonStyle_(hybridDiv);
	  container.appendChild(hybridDiv);
	  hybridDiv.appendChild(document.createTextNode("HYBRID"));
	  GEvent.addDomListener(hybridDiv, "click", function() {
		SetMapType(G_HYBRID_MAP);
	  });
	  GEvent.addDomListener(hybridDiv, "mouseover", function() {
		SetStyle(hybridDiv, 'itemHover');
	  });
	  GEvent.addDomListener(hybridDiv, "mouseout", function() {
		SetStyle(hybridDiv, 'item');
	  });
	
	  map.getContainer().appendChild(container);
	  return container;
}

BlueGhostBBCControl.prototype.getDefaultPosition = function() {
  	return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(2, 2));
}

BlueGhostBBCControl.prototype.printable = function()
{
	return false;	
}

BlueGhostBBCControl.prototype.selectable = function()
{
	return false;	
}

BlueGhostBBCControl.prototype.setButtonStyle_ = function(button) {
  	button.className = 'item';
}

//statusbar control
//also holds extrabar + searchbox
function BlueGhostStatusControl() {
}
BlueGhostStatusControl.prototype = new GControl();

BlueGhostStatusControl.prototype.initialize = function(map) {
	  var container = document.createElement("div");
	  container.id = 'statusBar';
	  
	  var searchBoxDiv = document.createElement("div");
	  searchBoxDiv.id = 'searchBox';
	  searchBoxDiv.className = 'hidden'; //searchText
	  container.appendChild(searchBoxDiv);
	
	  var messageBoxDiv = document.createElement("div");
	  messageBoxDiv.id = 'extraInfoBox';
	  messageBoxDiv.className = 'hidden'; //extraInfoText
	  container.appendChild(messageBoxDiv);
	  
	  var cit = document.createElement("div");
	  cit.id = 'statusBarText';
	  cit.className = 'statusBarText';
	  cit.innerHTML = "Loading Site ...";
	  container.appendChild(cit);
	
	  map.getContainer().appendChild(container);
	  return container;
}

BlueGhostStatusControl.prototype.getDefaultPosition = function() {
  	return new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(2, 15));
}

BlueGhostStatusControl.prototype.printable = function()
{
	return false;	
}

BlueGhostStatusControl.prototype.selectable = function()
{
	return false;
}

//copyright control
//also houses the information message box
function BlueGhostCopyrightControl() {
}
BlueGhostCopyrightControl.prototype = new GControl();

BlueGhostCopyrightControl.prototype.initialize = function(map) {
	  var container = document.createElement("div");
	  container.id = 'copyrightInfo';
	
	  var messageBoxDiv = document.createElement("div");
	  messageBoxDiv.id = 'messageBox';
	  messageBoxDiv.className = 'hidden'; //informationMessage
	  container.appendChild(messageBoxDiv);
	  
	  var cit = document.createElement("div");
	  cit.id = 'copyrightInfoText';
	  cit.className = 'copyrightInfo';
	  cit.appendChild(document.createTextNode("Site Code Copyright Michael Pritchard 2005-6"));
	  container.appendChild(cit);
	
	  map.getContainer().appendChild(container);
	  return container;
}

BlueGhostCopyrightControl.prototype.getDefaultPosition = function() {
  	return new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(2, 35));
}

BlueGhostCopyrightControl.prototype.printable = function()
{
	return false;	
}

BlueGhostCopyrightControl.prototype.selectable = function()
{
	return false;	
}