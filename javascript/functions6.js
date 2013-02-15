/*
functions6.js
Copyright 2005-8 Michael Pritchard
http://bbc.blueghost.co.uk/site4.php
Updated: June 2008
*/

var loadingHtml = '<img src="http://bbc.blueghost.co.uk/images/animated_loading.gif" alt="Loading" />&nbsp;';
var extraInfoToolbar = '<div align="right"><a onclick="HideExtraInfoBox();" href="#" title="Close Extra Links Box">X</a></div>';
var closeInfoToolbar = '<div align="right"><a onclick="HideMapClickText();" href="#" title="Close Information Box">X</a></div>';
var searchHTML = '<div align="right"><a onclick="HideSearchBox();" href="#" title="Close Search Box">X</a></div>' +
						'<strong>Search Options</strong><br /><br />' +
						'<input name="search_form_box" id="search_form_box" type="text" onKeyUp="javascript:doSearch(this.value);" onBlur="javascript:redo(this);" onFocus="javascript:clearS(this);" value="Enter Search Query" size="20" maxlength="50" style="display:inline; height:16px; font-size:x-small; font-family:Arial, Helvetica, sans-serif; font-weight: normal;">' +
						'<div id="searchResults"></div>';
var HIDDEN_STYLE = 'hidden';

function ShowLoadingMessage(msg) {
    ShowMessage(loadingHtml + msg);
}

function ShowErrorMessage(msg) {
    ShowMessage('An error occured during ' + msg);
}

function ShowMessage(msg) {
    $("#statusBarText").html(msg);
}

function ShowCopyText(msg) {
    $("#copyrightInfoText").html(msg);
}

function ShowMapClickTextWithStyle(html, style) {
    SetStyleById("messageBox", style);
    $("#messageBox").html(closeInfoToolbar + html);
}

function ShowMapClickText(html) {
    ShowMapClickTextWithStyle(html, "informationMessage");
}

function HideMapClickText() {
    HideElement(GetElementById("messageBox"));
}

function ShowExtraInfoBox(html) {
    SetStyleById("extraInfoBox", "extraInfoText");
    $("#extraInfoBox").html(extraInfoToolbar + html);
}

function HideExtraInfoBox() {
    HideElement(GetElementById("extraInfoBox"));
}

function ShowSearchBox() {
    SetStyleById("searchBox", "extraInfoText");
    $("#searchBox").html(searchHTML);
}

function HideSearchBox() {
    HideElement(GetElementById("searchBox"));
}

function GetElementById(id) {
    return $("#"+id); //document.getElementById(id);
}

function HideElement(element) {
    $(element).html('');
    SetStyle(element, HIDDEN_STYLE);
}

function SetStyleById(elementId, style) {
    GetElementById(elementId).className = style;
}

function SetStyle(element, style) {
    element.className = style;
}

function SetElementInnerHtmlById(id, text) {
    $("#" + id).html(text);
}

function SetElementInnerHtml(element, text) {
    $(element).html(text);
}