/**
 * Buttons component
 * 
 * @param _selector
 * @param _options
 * @param _win
 * @param _doc
 * 
 * @author Fares Zgheib
 * 
 */
function TTButtons(_selector, _options, _win, _doc)
{
	var objThis = this;
	objThis.name = "TT_BUTTONS";
	objThis.win = _win ? _win : self;
	objThis.doc = _doc ? _doc : objThis.win.document;
	objThis.JQ = objThis.win.$;
	objThis.ttUtilsInst = objThis.win.ttUtilsInst ? objThis.win.ttUtilsInst : new TTUtils(objThis.win, objThis.doc);
	//
	//
	if (!_selector || _selector.split(" ").join("") == "")
		throw "Provided Selector cannot be empty or null";
	if (!objThis.JQ)
		throw "JQuery library is required for this component";
	if (!objThis.ttUtilsInst)
		throw "TTUtils JS library is missing";
	//
	//
	objThis.selector = _selector;
	objThis.selectorElt = null;
	objThis.options = _options;
	objThis.defaultOptions = {};
	//
	_TT_BUTBAR_CLS = "tt_buttons_bar";

	//
	// PRIVATE METHODS
	//
	function _initStyles()
	{
		objThis.selectorElt.addClass(_TT_BUTBAR_CLS);
		//
		objThis.ttUtilsInst
		        .addStylsheet(
		                "stylesheet_" + _TT_BUTBAR_CLS,
		                "."
		                        + _TT_BUTBAR_CLS
		                        + " {width: 100%; background-color: #f5f5f5; padding: 5px; display: inline-block; text-align: right; align-content: right;}",
		                false, true);
		objThis.ttUtilsInst.addStylsheet("stylesheet_" + _TT_BUTBAR_CLS, "." + _TT_BUTBAR_CLS + " .btn, ."
		        + _TT_BUTBAR_CLS + " button{cursor: pointer; display: inline-block; margin: 0px 3px;}", true, false);
	}

	function _getTypeCls(type)
	{
		var cls = "";
		if (type)
			type = type.toLowerCase();
		switch (type)
		{
			case 'light':
				cls = "light";
			break;
			case 'no':
			case 'warning':
				cls = "warning";
			break;
			case 'update':
			case 'modify':
			case 'info':
				cls = "info";
			break;
			case 'error':
			case 'delete':
			case 'danger':
				cls = "danger";
			break;
			case 'add':
			case 'validate':
			case 'success':
				cls = "success";
			break;
			case 'cancel':
			case 'close':
			case 'back':
				cls = "secondary";
			break;
			case 'default':
			default:
				cls = "primary";
		}
		//
		return cls;
	}

	function _createDDLink(elt)
	{
		var newElt =
		        objThis.JQ("<a class=\"" + (elt.attr('class') || "") + " dropdown-item\" href=\"#\">" + elt.html()
		                + "</a>");
		//
		objThis.JQ.each(elt.get(0).attributes, function(i)
		{

			var att = elt.get(0).attributes[i].localName;
			var attVal = elt.get(0).attributes[i].value;
			//
			if (att.indexOf("data-") < 0 && att != "class")
				newElt.attr(att, attVal);
		});
		return newElt;
	}

	function _createButton(btn)
	{
	    var html = btn.html();
	    var value = "";
	    if(!html || html=="") value = btn.val();
	    var tag = btn.prop("tagName"); //"button";
	    //
	    var newBtn = objThis.JQ("<"+tag+" type=\""+btn.data("role")+"\" class=\"" + (btn.attr('class') || "") + " btn btn-"
		                + _getTypeCls(btn.data("type")) + "\" value=\"" + value + "\" >" + html + "</"+tag+">");
	    //
	    objThis.JQ.each(btn.get(0).attributes, function(i)
	    {
		var att = btn.get(0).attributes[i].localName;
		var attVal = btn.get(0).attributes[i].value;
		//
		if (att.indexOf("data-") < 0 && att != "class") newBtn.attr(att, attVal);
	    });
	    //
	    return newBtn;
	}

	function _createGroup(group)
	{
		//
		var grpElets = group.find("a, button, span, div");
		// Create the main group
		var newGroup = objThis.JQ("<div id='" + (group.attr("id") || "") + "' class='btn-group' role='group'>");
		// ADD THE LABEL
		var id = (new Date()).getTime();
		newGroup
		        .append(objThis
		                .JQ("<button id='"
		                        + id
		                        + "' type=\"button\" class=\"btn btn-secondary dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">"
		                        + group.data("label") + "</button>"));
		newGroupDD = objThis.JQ("<div class='dropdown-menu' aria-labelledby='" + id + "'>");
		//
		grpElets.each(function(index, elt)
		{
			elt = objThis.JQ(elt);
			if (elt.data("role") == "button" || elt.attr("type") == "button")
				newGroupDD.append(_createButton(elt));
			else
				newGroupDD.append(_createDDLink(elt));
		});
		//
		newGroup.append(newGroupDD);
		return newGroup;
	}

	function _renderButtonBar()
	{
		objThis.selectorElt.each(function(index, barElt)
		{
			barElt = objThis.JQ(barElt);
			//
			var elts = barElt.find("> input[data-role], > button, > a, > div");
			//
			var eltRes = objThis.JQ("<div>");
			//	
			//
			elts.each(function(index, elt)
			{
				//
				elt = objThis.JQ(elt);
				//
				if (elt.data("role") == "group") {
					eltRes.append(_createGroup(elt));
				} else if (elt.data("role") == "button" || elt.data("role") == "submit") {
					eltRes.append(_createButton(elt));
				}
				//
				//
			});
			//
			if(eltRes.html()) 
			{
				barElt.html(eltRes.html());
				objThis.selectorElt.attr("tt_initialized", "true");
			}
		});
	}

	function _init()
	{
		objThis.selectorElt = objThis.JQ(objThis.selector + ":not([tt_initialized=true])");
		objThis.options = objThis.ttUtilsInst.extendObject(objThis.options, objThis.defaultOptions);
		//
		_initStyles();
		//
		_renderButtonBar();
		//
	}
	//
	_init();
}
