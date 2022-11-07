window.TT_OVERLAY_INSTANCES = [];

//Public static method used to directly get a new instance of an overlay
//if same ID was provided the same instance will be returned.
window.getTTOverlay = function(_id, _options, _win, _doc)
{
	var inst = {};
	//
	//
	if (_id && window.TT_OVERLAY_INSTANCES[_id]) {
		inst = window.TT_OVERLAY_INSTANCES[_id];
		if (_options)
			inst.setOptions(_options, false);
	} else {
		inst = new TTOverlay(_id, _options, _win, _doc);
		window.TT_OVERLAY_INSTANCES[inst.ID] = inst;
	}
	//
	return inst;
}

//Fast access to to show a simple overlay
window.showTTOverlay = function(image, text, opt)
{
	opt = opt || {default: true};
	if(image) opt.image = image;
	if(text) opt.text = text;
	//
	if(opt.default && !opt.image && !opt.text) opt = null;
	//
	return window.getTTOverlay(null, opt).show();
}

//Fast access to hide a public overlay
window.hideTTOverlay = function(_id, elt)
{
	return window.getTTOverlay(_id, null).hide(elt, null);
}

/**
 * Overlay component used to open a full screen or area specific overlay to show a message, loading or progress bar
 * 
 * @param _id
 * @param _options
 * @param _win
 * @param _doc
 * 
 * @author Fares Zgheib
 * 
 */
function TTOverlay(_id, _options, _win, _doc)
{
	var objThis = this;
	objThis.name = "TT_OVERLAY";
	objThis.win = _win ? _win : self;
	objThis.doc = _doc ? _doc : objThis.win.document;
	objThis.JQ = objThis.win.$;
	objThis.ttUtilsInst = objThis.win.ttUtilsInst ? objThis.win.ttUtilsInst : new TTUtils(objThis.win, objThis.doc);
	//
	//
	if (!objThis.JQ)
		throw "JQuery library is required for this component";
	if (!objThis.JQ.LoadingOverlay)
		throw "Loading Overlay JS library is missing";
	if (!objThis.ttUtilsInst)
		throw "TTUtils JS library is missing";
	//
	objThis.ID = _id;
	objThis.DISABLE_BACKGROUND_CLS = "disable-ttoverlay-background";
	//
	//
	objThis.show 			= _show;
	objThis.hide 			= _hide;
	objThis.resize 			= _resize;
	objThis.showProgress 	= _showProgress;
	objThis.showText 		= _showText;
	objThis.showCustom 		= _showCustom;
	objThis.updateProgress 	= _updateProgress;
	objThis.updateText 		= _updateText;
	objThis.setOptions 		= _setOptions;
	//
	//
	objThis.options = null;
	objThis.defaultOptions = {
			// Background
			background              : "rgba(255, 255, 255, 0.8)"        // String
			,backgroundClass        : ""        // String/Boolean
			,disableBackground      : false     // Boolean
			// Image
			,image                   : "/assets/common/img/preloader-transparent.gif"                // String/Boolean
//			,image                   : "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1000 1000'><circle r='80' cx='500' cy='90'/><circle r='80' cx='500' cy='910'/><circle r='80' cx='90' cy='500'/><circle r='80' cx='910' cy='500'/><circle r='80' cx='212' cy='212'/><circle r='80' cx='788' cy='212'/><circle r='80' cx='212' cy='788'/><circle r='80' cx='788' cy='788'/></svg>"                // String/Boolean
			,imageAnimation          : "2000ms rotate_right"             // String/Boolean   //rotate_right | rotate_left | fadein | pulse
			,imageAutoResize         : true                              // Boolean
			,imageResizeFactor       : 0.5                                 // Float
			,imageColor              : "#202020"                         // String/Array/Boolean
			,imageClass              : ""                                // String/Boolean
			,imageOrder              : 1                                 // Integer
			// Font Awesome
			,fontawesome             : ""                                // String/Boolean
			,fontawesomeAnimation    : ""                                // String/Boolean
			,fontawesomeAutoResize   : true                              // Boolean
			,fontawesomeResizeFactor : 1                                 // Float
			,fontawesomeColor        : "#202020"                         // String/Boolean
			,fontawesomeOrder        : 2                                 // Integer
			// Custom
			,custom                  : ""                                // String/DOM Element/jQuery Object/Boolean
			,customAnimation         : ""                                // String/Boolean
			,customAutoResize        : true                              // Boolean
			,customResizeFactor      : 1                                 // Float
			,customOrder             : 3                                 // Integer
			// Text
			,text                    : ""                                // String/Boolean
			,textAnimation           : ""                                // String/Boolean
			,textAutoResize          : true                              // Boolean
			,textResizeFactor        : 0.5                               // Float
			,textColor               : "#202020"                         // String/Boolean
			,textClass               : ""                                // String/Boolean
			,textOrder               : 4                                 // Integer
			// Progress
			,progress                : false                             // Boolean
			,progressAutoResize      : true                              // Boolean
			,progressResizeFactor    : 0.25                              // Float
			,progressColor           : "#a0a0a0"                         // String/Boolean
			,progressClass           : ""                                // String/Boolean
			,progressOrder           : 5                                 // Integer
			,progressFixedPosition   : ""                                // String/Boolean     "top", "bottom", "top 20px", "10% top", "5rem bottom", "bottom 2vh"
			,progressSpeed           : 200                               // Integer
			,progressMin             : 0                                 // Float
			,progressMax             : 100                               // Float
			// Sizing
			,size                    : 50                                // Float/String/Boolean
			,minSize                 : 20                                // Integer/String
			,maxSize                 : 120                               // Integer/String
			// Misc
			,direction               : "column"                          // String    column | row
			,fade                    : [400, 200]                        // Array/Boolean/Integer/String
			,resizeInterval          : 50                                // Integer
			,zIndex                  : 2147483647                        // Integer
	};

	//
	// PUBLIC METHODS
	//
	function _show(_opts, hideAfterMs, eltSelector)
	{
		_opts = _opts || objThis.options;
		//
		_loadOverlay(eltSelector, "show", _opts, hideAfterMs);
	}

	function _hide(eltSelector, force)
	{
		force = (force!=false);
		_loadOverlay(eltSelector, "hide", force);
	}
	
	function _resize()
	{
		_loadOverlay(eltSelector, "resize");
	}

	function _showProgress(eltSelector, _opts)
	{
		_opts = _opts || objThis.options || objThis.defaultOptions;
		_opts.image = "";
		_opts.progress = true;
		//
		//
		_loadOverlay(eltSelector, "progress", _opts);
	}

	function _showText(text, eltSelector, _opts)
	{
		_opts = _opts || objThis.options || objThis.defaultOptions;
		_opts.image = "";
		_opts.text = text;
		//
		//
		_loadOverlay(eltSelector, "show", _opts);
	}
	
	function _showCustom(jqElt, eltSelector, _opts)
	{
		_opts = _opts || objThis.options || objThis.defaultOptions;
		_opts.image = "";
		_opts.custom = jqElt;
		//
		_loadOverlay(eltSelector, "show", _opts);
	}

	function _updateProgress(count, eltSelector)
	{
		_loadOverlay(eltSelector, "progress", count);
	}

	function _updateText(text, eltSelector)
	{
		_loadOverlay(eltSelector, "text", text);
	}

	function _setOptions(options, replace)
	{
		if (options && typeof (options == "object")) {
			if (replace)
				objThis.options = options;
			else
				objThis.options = objThis.ttUtilsInst.extendObject(options, objThis.defaultOptions);
		}
	}


	//
	// PRIVATE METHODS
	//
	function _loadOverlay(eltSelector, action, _opts, hideAfterMs)
	{
		var elt = objThis.JQ;
		if(!eltSelector && objThis.ID && objThis.ID!="") 
		{
			if(objThis.ID.indexOf(".") != 0)
				eltSelector = "#" + objThis.ID;
			else eltSelector = objThis.ID;
		}else if(!eltSelector)
			{
				eltSelector = "body";
			}
		if(eltSelector) elt = objThis.JQ(eltSelector);
		//
		if(_opts && typeof(_opts)=='object')
		{
			if(_opts.disableBackground) _opts.backgroundClass = objThis.DISABLE_BACKGROUND_CLS;
			_opts = objThis.ttUtilsInst.extendObject(_opts, objThis.defaultOptions);
		}
		//
		elt.LoadingOverlay(action, _opts);
		//
		if(hideAfterMs && hideAfterMs > 0) 
			objThis.win.setTimeout(function(){
				objThis.hide(eltSelector);
			}, hideAfterMs);
	}

	//
	//
	//
	function _init()
	{
		objThis.options = objThis.ttUtilsInst.extendObject(_options, objThis.defaultOptions) || objThis.defaultOptions;
	}
	//
	_init();

}
//
$(document).ready(function()
{
	if (!window.ttOverlayInst)
		window.ttOverlayInst = new TTOverlay(null, null, window, document)
});
//
