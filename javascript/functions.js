	/*
		functions.js
		Copyright 2005-8 Michael Pritchard
		http://bbc.blueghost.co.uk/site4.php
		http://www.blueghost.co.uk
		Updated: June 2008
	*/
	
	function ShowLoadingMessage(msg)
	{
		var pre = '<img src="http://bbc.blueghost.co.uk/images/animated_loading.gif" alt="Loading" />&nbsp;';
		ShowMessage(pre + msg);
	}	
	
	function ShowErrorMessage(msg)
	{
		ShowMessage('An error occured during ' + msg);
	}
	
	function ShowMessage(msg)
	{
		var lo			= GetElementById("statusBarText");
		lo.innerHTML 	= msg;
	}
	
	function ShowCopyText(msg)
	{
		var lo			= GetElementById("copyrightInfoText");
		lo.innerHTML 	= msg;
	}
	
	function ShowMapClickTextWithStyle(html, style)
	{
		var info		= GetElementById("messageBox");
		info.className 	= style; //'informationMessage';
		var toolbar		= '<div align="right"><a onclick="HideMapClickText();" href="#" title="Close Information Box">X</a></div>';
		info.innerHTML 	= toolbar + html;	
	}
	
	function ShowMapClickText(html)
	{
		ShowMapClickTextWithStyle(html, 'informationMessage');
	}
	
	function HideMapClickText()
	{
		//var info		= document.getElementById("messageBox");
		//info.innerHTML = '';
		//info.className 	= 'hidden';
		HideElement(GetElementById("messageBox"));
	}
	
	function ShowExtraInfoBox(html)
	{
		var info		= GetElementById("extraInfoBox");
		info.className 	= 'extraInfoText';
		var toolbar		= '<div align="right"><a onclick="HideExtraInfoBox();" href="#" title="Close Extra Links Box">X</a></div>';
		info.innerHTML 	= toolbar + html;	
	}
	
	function HideExtraInfoBox()
	{
		var info		= GetElementById("extraInfoBox");
		info.innerHTML = '';
		info.className 	= 'hidden';
	}
	
	function ShowSearchBox()
	{
		var info		= GetElementById("searchBox");
		info.className 	= 'extraInfoText';
		var toolbar		= '<div align="right"><a onclick="HideSearchBox();" href="#" title="Close Search Box">X</a></div>';
		var searchHTML	= '<strong>Search Options</strong><br /><br />';
		searchHTML		+= '<input name="search_form_box" id="search_form_box" type="text" onKeyUp="javascript:doSearch(this.value);" onBlur="javascript:redo(this);" onFocus="javascript:clearS(this);" value="Enter Search Query" size="20" maxlength="50" style="display:inline; height:16px; font-size:x-small; font-family:Arial, Helvetica, sans-serif; font-weight: normal;">';
		searchHTML		+= '<div id="searchResults"></div>';
		info.innerHTML 	= toolbar + searchHTML;	
	}
	
	function HideSearchBox()
	{
		HideElement(GetElementById("searchBox"));
	}
	
	function GetElementById(id)
	{
		return document.getElementById(id);
	}
	
	var HIDDEN_STYLE = 'hidden';
	
	function HideElement(element)
	{
		element.innerHTML = '';
		SetStyle(element, HIDDEN_STYLE);
	}
	
	function SetStyle(element, style)
	{
		element.className = style;	
	}
	
	function SetElementInnerHtmlById(id, text)
	{
		GetElementById(id).innerHTML = text;
	}
	
	function SetElementInnerHtml(element, text)
	{
		element.innerHTML = text;
	}