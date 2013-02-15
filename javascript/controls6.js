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
    var topContainer = document.createElement("div");
    topContainer.id = 'BlueGhostBBCControl';

    var detailContainer = document.createElement("div");

    var minMaxTimeDiv = document.createElement("div");
    topContainer.appendChild(minMaxTimeDiv);
    var minMaxContentText = document.createTextNode("-");
    minMaxTimeDiv.appendChild(minMaxContentText);
    minMaxTimeDiv.className = 'itemHover';
    minMaxTimeDiv.title = 'Click to Minimise the menu';

    topContainer.appendChild(detailContainer);

    GEvent.addDomListener(minMaxTimeDiv, "mouseover", function() {
        if (!menuHidden) {
            minMaxContentText.nodeValue = "+ » Maximise";
        } else {
            minMaxContentText.nodeValue = "- » Minimise";
        }
    });
    GEvent.addDomListener(minMaxTimeDiv, "mouseout", function() {
        if (!menuHidden) {
            minMaxContentText.nodeValue = "+";
        } else {
            minMaxContentText.nodeValue = "-";
        }
    });
    GEvent.addDomListener(minMaxTimeDiv, "click", function() {
        if (menuHidden) {
            minMaxContentText.nodeValue = "+";
            minMaxTimeDiv.title = 'Click to Maximise the menu';
            $(detailContainer).hide("fold", {}, 1000);
        } else {
            minMaxContentText.nodeValue = "-";
            minMaxTimeDiv.title = 'Click to Minimise the menu';
            $(detailContainer).show("fold", {}, 1000);
        }
        menuHidden = !menuHidden;
    });


    var weatherDiv = document.createElement("div");
    weatherDiv.id = 'wc';
    weatherDiv.title = 'Click Here to Load the Weather Data Overlay';
    this.setButtonStyle_(weatherDiv);
    detailContainer.appendChild(weatherDiv);
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
    detailContainer.appendChild(trafficDiv);
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
    detailContainer.appendChild(trainDiv);
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
    permalinkDiv.id = 'pl';
    permalinkDiv.title = 'Click Here to Get a Permanent Link to this page';
    this.setButtonStyle_(permalinkDiv);
    detailContainer.appendChild(permalinkDiv);
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
    googleEarthDiv.id = 'ge';
    googleEarthDiv.title = 'Click Here to Get this data in Google Earth';
    this.setButtonStyle_(googleEarthDiv);
    detailContainer.appendChild(googleEarthDiv);
    googleEarthDiv.innerHTML = '<img src="images/google_earth_feed.gif" width="93" height="17" alt="Google Earth" title="Click here to get this data in Google Earth" />';
    GEvent.addDomListener(googleEarthDiv, "click", function() {
        document.location.href = 'http://bbc.blueghost.co.uk/earth/';
    });
    GEvent.addDomListener(googleEarthDiv, "mouseover", function() {
        SetStyle(googleEarthDiv, 'itemHover');
    });
    GEvent.addDomListener(googleEarthDiv, "mouseout", function() {
        SetStyle(googleEarthDiv, 'item');
    });

    var helpDiv = document.createElement("div");
    helpDiv.id = 'hl';
    helpDiv.title = 'Click Here for Help (new pop-up window)';
    this.setButtonStyle_(helpDiv);
    detailContainer.appendChild(helpDiv);
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
    aboutDiv.id = 'abl';
    aboutDiv.title = 'Click Here for the about page(new window)';
    this.setButtonStyle_(aboutDiv);
    detailContainer.appendChild(aboutDiv);
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
    mapDiv.id = 'normalMap';
    this.setButtonStyle_(mapDiv);
    detailContainer.appendChild(mapDiv);
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
    satDiv.id = 'satMap';
    this.setButtonStyle_(satDiv);
    detailContainer.appendChild(satDiv);
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
    hybridDiv.id = 'hybridMap';
    this.setButtonStyle_(hybridDiv);
    detailContainer.appendChild(hybridDiv);
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

    map.getContainer().appendChild(topContainer);
    return topContainer;
}

BlueGhostBBCControl.prototype.getDefaultPosition = function() {
    return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(2, 2));
}

BlueGhostBBCControl.prototype.printable = function() {
    return false;
}

BlueGhostBBCControl.prototype.selectable = function() {
    return false;
}

BlueGhostBBCControl.prototype.setButtonStyle_ = function(button) {
    button.className = 'item';
}

function HideShowButton(button, hide) {
    if (hide)
        button.className = 'item';
    else
        button.className = 'hidden';
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

BlueGhostStatusControl.prototype.printable = function() {
    return false;
}

BlueGhostStatusControl.prototype.selectable = function() {
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
    cit.appendChild(document.createTextNode("Site Code Copyright Michael Pritchard 2005-8"));
    container.appendChild(cit);

    map.getContainer().appendChild(container);
    return container;
}

BlueGhostCopyrightControl.prototype.getDefaultPosition = function() {
    return new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(2, 35));
}

BlueGhostCopyrightControl.prototype.printable = function() {
    return false;
}

BlueGhostCopyrightControl.prototype.selectable = function() {
    return false;
}

// ADDITION TO Ver 6
// search control
function BlueGhostSearchControl() {
}
BlueGhostSearchControl.prototype = new GControl();

// Creates a one DIV for each of the buttons and places them in a container
// DIV which is returned as our control element. We add the control to
// to the map container and return the element for the map class to
// position properly.
BlueGhostSearchControl.prototype.initialize = function(map) {
    var topContainer = document.createElement("div");
    topContainer.id = 'BlueGhostSearchControl';

    var detailContainer = document.createElement("div");

    var minMaxTimeDivSC = document.createElement("div");
    topContainer.appendChild(minMaxTimeDivSC);
    var minMaxContentTextSC = document.createTextNode("-");
    minMaxTimeDivSC.appendChild(minMaxContentTextSC);
    minMaxTimeDivSC.className = 'itemHover';
    minMaxTimeDivSC.title = 'Click to Minimise the menu';

    topContainer.appendChild(detailContainer);

    GEvent.addDomListener(minMaxTimeDivSC, "mouseover", function() {
        if (!menuHiddenSC) {
            minMaxContentTextSC.nodeValue = "+ » Maximise";
        }
        else {
            minMaxContentTextSC.nodeValue = "- » Minimise";
        }
    });
    GEvent.addDomListener(minMaxTimeDivSC, "mouseout", function() {
        if (!menuHiddenSC) {
            minMaxContentTextSC.nodeValue = "+";
        }
        else {
            minMaxContentTextSC.nodeValue = "-";
        }
    });
    GEvent.addDomListener(minMaxTimeDivSC, "click", function() {
        if (menuHiddenSC) {
            minMaxContentTextSC.nodeValue = "+";
            minMaxTimeDivSC.title = 'Click to Maximise the menu';
            $(detailContainer).hide("fold", {}, 1000);
        }
        else {
            minMaxContentTextSC.nodeValue = "-";
            minMaxTimeDivSC.title = 'Click to Minimise the menu';
            $(detailContainer).show("fold", {}, 1000);
        }
        menuHiddenSC = !menuHiddenSC;
    });

    var searchDiv = document.createElement("div");
    searchDiv.id = 'sl';
    searchDiv.title = 'Click here to search this Layer';
    this.setButtonStyle_(searchDiv);
    detailContainer.appendChild(searchDiv);
    searchDiv.appendChild(document.createTextNode("Search Layer"));
    GEvent.addDomListener(searchDiv, "click", function() {
        ShowSearchBox();
    });
    GEvent.addDomListener(searchDiv, "mouseover", function() {
        SetStyle(searchDiv, 'itemHover');
    });
    GEvent.addDomListener(searchDiv, "mouseout", function() {
        SetStyle(searchDiv, 'item');
    });

    ///
    /// START Google Local Search
    ///
    var googleSearchTitleDiv = document.createElement("div");
    googleSearchTitleDiv.id = 'searchControlMainDiv';
    googleSearchTitleDiv.title = 'Enter Town/City/Post Code and press \'Enter\'';
    googleSearchTitleDiv.className = 'itemHover';
    detailContainer.appendChild(googleSearchTitleDiv);
    googleSearchTitleDiv.appendChild(document.createTextNode("Town Search"));

    var googleSearchControl = document.createElement("div");
    googleSearchControl.id = 'searchcontrol';
    googleSearchControl.className = 'item';
    googleSearchControl.title = 'Enter Town/City/Post Code and press \'Enter\'';
    googleSearchControl.innerHTML = '<form onsubmit="return DoGoogleLocalSearch();"><input title="Enter Town/City/Post Code and press \'Enter\'" type="text" id="googleLocalSearchText" onFocus="if (this.value == \'Enter Town/City/Post Code\') this.value= \'\';" onBlur="if (this.value == \'\') this.value = \'Enter Town/City/Post Code\';" value="Enter Town/City/Post Code" /></form><hr />';

    var googleSearchResults = document.createElement("div");
    googleSearchResults.id = 'searchControlResults';
    googleSearchControl.appendChild(googleSearchResults);
    detailContainer.appendChild(googleSearchControl);
    ///
    /// END Google Local Search
    ///

    ///
    /// START Google Directions Search
    ///
    var googleDirectionsTitleDiv = document.createElement("div");
    googleDirectionsTitleDiv.id = 'directionsControlMainDiv';
    googleDirectionsTitleDiv.title = 'Enter Directions Query and press \'Enter\'';
    googleDirectionsTitleDiv.className = 'itemHover';
    detailContainer.appendChild(googleDirectionsTitleDiv);
    googleDirectionsTitleDiv.appendChild(document.createTextNode("Directions Search"));

    var googleDirectionsControl = document.createElement("div");
    googleDirectionsControl.id = 'directionsControl';
    googleDirectionsControl.className = 'item';
    googleDirectionsControl.title = 'Enter Directions Query and press \'Enter\'';
    googleDirectionsControl.innerHTML = '<form action="javascript:DoGoogleDirectionsSearch();"><input title="Enter Directions Query and press \'Enter\'" type="text" id="googleDirectionsSearchText" onFocus="if (this.value == \'Enter Query\') this.value= \'\';" onBlur="if (this.value == \'\') this.value = \'Enter Query\';" value="Enter Query" /></form><hr />';

    var googleDirectionsResults = document.createElement("div");
    googleDirectionsResults.id = 'directionsControlResults';
    googleDirectionsControl.appendChild(googleDirectionsResults);
    detailContainer.appendChild(googleDirectionsControl);
    ///
    /// END Google Directions Search
    ///

    map.getContainer().appendChild(topContainer);
    return topContainer;
}

BlueGhostSearchControl.prototype.getDefaultPosition = function() {
    return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(164, 2));
}

BlueGhostSearchControl.prototype.printable = function() {
    return false;
}

BlueGhostSearchControl.prototype.selectable = function() {
    return false;
}

BlueGhostSearchControl.prototype.setButtonStyle_ = function(button) {
    button.className = 'item';
}