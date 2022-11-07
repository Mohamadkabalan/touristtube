/**
 * Version 1.0
 * 
 * @param id(required)
 *            Dom target id
 * @param options
 *            configuration object, mainly used to send the client params
 * @param win(optional)
 *            the window holder of the HTML doms where to display the viewer
 * @param doc(optional)
 *            the document holder of the HTML doms where to display the viewer
 * 
 */

window.TT_TOUR_ENV = "dev";
//
//
window.TT_VARS = {"dev": {
						"TT_HOST_URL": (window.location.protocol + "//" + window.location.host + "/") || "https://www-tt.touristtube.com/",
						"TT_TOUR_XML_URL": "https://utildev01-gcp.touristtube.com:8765/media/360-photos-vr/vtourConfig/"
					},
				"prod": {
						"TT_HOST_URL": "https://www.touristtube.com/",
						"TT_TOUR_XML_URL": "https://api_gw_service.touristtube.com:8765/media/360-photos-vr/vtourConfig/"
// "TT_TOUR_XML_URL": "http://78.41.239.36:8765/media/360-photos-vr/vtourConfig/"
					},
};
//
//
//
window.TT_TOUR_360_URL = "360-photos-vr/";
window.TT_TOUR_JS_URL = "assets/vendor/vtour/latest/tour.js";
window.TT_TOUR_SWF_URL = "assets/vendor/vtour/latest/tour.swf";
window.TT_TOUR_THUMB_SUFFIX = "_360";
//
window.TT_FB_APP_ID = "1045138925510219";
//
window.TT_TOUR_HEADER = [
	/*
	 * {"tag": "meta", attributes:{name: "description", content: "your meta description content goes here"}} ,
	 */
	// {"tag": "META", attributes:{NAME: "ROBOTS", CONTENT: "INDEX, FOLLOW"}}
];
//
function TTTour(_id, _options, _win, _doc)
{
	var objThis = this;
	objThis.name = "TT_TOUR";
	objThis.win = _win ? _win : self;
	objThis.doc = _doc ? _doc : objThis.win.document;
	objThis.JQ = objThis.win.$;
	//
	//
	if(!_id || _id.split(" ").join("")=="") throw "Provided DOM selector cannot be empty or null";
	if(!objThis.JQ) throw "JQuery library is required for this component";
	//
	objThis.ID = _id;
	objThis.ID_CONTENT = objThis.ID + "_content";
	objThis.selectorElt = objThis.JQ("#" + objThis.ID);
	objThis.LIB_INSTANCE = null;
	//
	var TOUR_HOST_URL = objThis.win.TT_VARS[objThis.win.TT_TOUR_ENV]["TT_HOST_URL"];
	var TOUR_XML_URL = objThis.win.TT_VARS[objThis.win.TT_TOUR_ENV]["TT_TOUR_XML_URL"];
	var TT_TOUR_360_URL = objThis.win.TT_TOUR_360_URL;				// "360-photos/"
	var TOUR_JS_URL = TOUR_HOST_URL + objThis.win.TT_TOUR_JS_URL; 	// ".../engine/tour.js";
	var TOUR_SWF_URL = TOUR_HOST_URL + objThis.win.TT_TOUR_SWF_URL; // ".../engine/tour.swf";
	var TT_TOUR_THUMB_URL = TOUR_HOST_URL + TT_TOUR_360_URL;
	var TT_TOUR_PANORAMA_URL = TOUR_HOST_URL + TT_TOUR_360_URL;
	var TT_TOUR_THUMB_SUFFIX = objThis.win.TT_TOUR_THUMB_SUFFIX;
	var TT_TOUR_HEADER = objThis.win.TT_TOUR_HEADER;
	//
	var POPUP_JS_LIB_URL = "https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.js";
	var POPUP_CSS_LIB_URL = "https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.css";
	//
	//
	var TOUR_CLIENT_ID_PARAM_NAME = "id";
	//
	objThis.options = null;
	objThis.defaultOptions = {
		target: objThis.ID_CONTENT
		,id: objThis.ID_CONTENT + "_viewer"
		,params:{
			id: null,	// client id
			name: null,	// client name
			type: null,	// client type hotels, thingstodo, . . .
			countrycode: null,	// client country code ISO3 Exp: LBN. . .
			group: null,	// section id (Exp: 1 -> rooms, 2 -> amenities, . . .
			groupName: null, // section id (Exp: rooms, amenities, . . .
			division: null,	// division id, specific part of the section (Exp: 1 -> bathroom, 2 -> spa, . . .)
			divisionName: null,	// division name, specific part of the section (Exp: bathroom, spa, . . .)
			subdivision: null,	// sub division id, specific part of the section (Exp: bathroom, spa, . . .)
			info: null,
			social: null,
			showInfo: false,
			showThumbs: null,
			closeThumbs: null
		}
		,ttGetXMLAjax: true
		,usePositionAbsolute: true
		,thumbExtension: ".jpg"
		,thumbName: "360_Preview"
		,panoramaThumbName: "360_sphere"
		,autoload: true
		,showThumbnail: true
		,cache: false
		,consolelog : false
		,html5:"prefer"
		,mobilescale:1.0
		,passQueryParameters:true
		,wmode: null

		,loadPopupLibrary: true

		,fullTour:{
			active: false,
			linkOrigin: null,
			linkText: "Full Tour",
			openTarget: "_blank", // "popup",
			linkPosition: {valign: "top", halign: "right"},
			url: null,
			allowShare: false,
			width: "100%",
			height: "95%",
		}

		,onerror: function(err)
		{
			console.log("TTVTour: error creating Instance");
			console.log(err);
		}

		,onready: function(krpano)
		{
			console.log("tt vtour is ready");
			objThis.LIB_INSTANCE = krpano;
		}
	};
	//
	//
	objThis.COUNT_AJAX_XML = 0;
	//
	objThis.FULL_TOUR_URL_PATTERN = {
		"hotels": "360-photos-vr/hotel-details-{name}-{id}",
		"thingstodo": "360-photos-vr/things_to_do-{name}-{id}",
		"restaurants": "360-photos-vr/360-restaurant-{name}-{city}-{id}"
	}
	objThis.FULL_TOUR_URL = null;
	objThis.loadEngine = _instantiate;
	/*
	 * @param subDivisionId @param divisionId @param entityId
	 */
	objThis.shareTour = _showSocialShare;
	//
	//
	function _loadJS()
	{
		objThis.JQ.ajaxSetup({
		  cache: objThis.options.cache
		});
		//
		if( (!objThis.win.embedpano) )
		{
			if(!objThis.win.TT_VIEWER_LOADED && !objThis.win.TT_VIEWER_LOADING)
			{
				objThis.win.TT_VIEWER_LOADING = true;
				objThis.win.TT_VIEWER_LOADED = true;
				//
				objThis.JQ.getScript(TOUR_JS_URL).done(function( data, textStatus, jqxhr ) {
					// console.log( data ); // Data returned
					// console.log( textStatus ); // Success
					// console.log( jqxhr.status ); // 200
					console.log( "TT Tour: Load was performed." );
					//
					objThis.win.TT_VIEWER_LOADING = false;
					//
					if(!objThis.win.embedpano)
					{
						console.error("Touristtube 360 Tour library is not properly loaded.");
					}else
					{
						if(objThis.options.autoload) _instantiate();
					}
					//
					//
					_instantiateFullTourModal();
					//
					//
				}).fail(function(){
					objThis.win.TT_VIEWER_LOADED = false;
					objThis.win.TT_VIEWER_LOADING = false;
					console.error("Touristtube failed loading 360 Tour library.");
				});
			}else if(objThis.win.TT_VIEWER_LOADING) objThis.win.setTimeout(_loadJS, 500);
		}else if(objThis.options.autoload) _instantiate();
	}
	//
	function _instantiateFullTourModal()
	{
		var fullToorOptions = objThis.options.fullTour;
		//
		var fullTourURL = fullToorOptions.url || objThis.FULL_TOUR_URL;
		//
		//
		if(fullToorOptions.active || fullToorOptions.linkOrigin)
		{
			//
			//
			var linkOrigin = fullToorOptions.linkOrigin || "a#ttvtour_" + objThis.ID;
			if(objThis.JQ(linkOrigin).length <= 0)
			{
				objThis.JQ('head').append('<style id="tt_vtour_styles" rel="stylesheet" type="text/css" ></style>');
				objThis.JQ('head').find("#tt_vtour_styles").first()
					.append(".ttvtour{font-size: 14px;font-family: tahoma, arial;color: #434343;text-transform: uppercase;font-weight: bold;}")
					.append(".tt_vtour_info_container.hide{display: none !important; } .tt_vtour_info_container{position: absolute;z-index: 30002;background-color: black;line-height: 24px;min-height: 27px;min-width: 190px;max-width: 190px;display: block;color: white;padding: 5px 10px;font-size: 14px;opacity: 0.8;cursor:default; } .tt_vtour_info_container p {margin: 0;padding: 5px 0px 0px 5px;font-weight: bolder;text-transform: uppercase;font-size: larger; } .tt_vtour_info_container ul{margin: 0; padding: 6px 20px; list-style-type: square; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; }")
				;

				var elt = '<div class="vtour-link-container" style="width: ' + objThis.selectorElt.width() + 'px; text-align: ' + (fullToorOptions.linkPosition.valign || "right") + '; clear: both;">'
									+ '<a id="ttvtour_' + objThis.ID + '" target="_blank" href="javascript:void(0);" >' + fullToorOptions.linkText + '</a></div>';
				//
				if(fullToorOptions.linkPosition.valign=="top")
					objThis.selectorElt.before(elt);
				else objThis.selectorElt.after(elt);
				//
				linkOrigin = "a#ttvtour_" + objThis.ID;
			}
			//
			objThis.JQ(linkOrigin).addClass("ttvtour");
			//
			//
			if(fullToorOptions.openTarget != "popup")
			{
				objThis.JQ(linkOrigin).attr("href", fullTourURL);
				objThis.JQ(linkOrigin).prop("href", fullTourURL);
			}else{
				if(objThis.options.loadPopupLibrary && !objThis.TT_POPUP_LIBRARY_LOADED)
				{
					objThis.JQ('head').append('<link rel="stylesheet" href="' + POPUP_CSS_LIB_URL + '" type="text/css" />');
					objThis.JQ.getScript(POPUP_JS_LIB_URL).done(function(){
						objThis.TT_POPUP_LIBRARY_LOADED = true;
						_instantiateFullTour(linkOrigin, fullTourURL);
					});
				}else _instantiateFullTour(linkOrigin, fullTourURL);
			}
		}
	}
	function _instantiateFullTour(linkOrigin, fullTourURL)
	{
		var fullToorOptions = objThis.options.fullTour;
		// var linkOrigin = fullToorOptions.linkOrigin || "a#ttvtour_" + objThis.ID;
		//
		var modalName = "touristtube_360_vtour";
			modalName += encodeURIComponent(objThis.options.params.name.split(" ").join("_") || "");
		if(objThis.options.params.name && objThis.options.params.name!="")
		if(objThis.options.params.id && objThis.options.params.id!="")
			modalName += "_" + objThis.options.params.id;
		if(objThis.options.params.subdivision || objThis.options.params.division)
			modalName += "_" + (objThis.options.params.subdivision || objThis.options.params.division)
		//
		objThis.JQ(linkOrigin).attr("data-fancybox", "" /* modalName */); // commented to avoid grouping
		objThis.JQ(linkOrigin).attr("data-src", fullTourURL);
		objThis.JQ(linkOrigin).attr("data-type", "iframe");
		objThis.JQ(linkOrigin).attr("data-options", '{"iframe" : {"css" : {"width" : "'+ fullToorOptions.width + '", "height" : "' + fullToorOptions.height + '"}}}');

		// objThis.JQ(linkOrigin).attr("data-modal", "false");
		//
		// THE BELOW CODE WAS USED WITH THE FANCYBOX VERSION 3.2.5
		//
		return;
		//
		// THE BELOW CODE WAS USED WITH THE FANCYBOX VERSION 3.2.5
		//
		var fbwidth = fullToorOptions.width;
		var fbheight = fullToorOptions.height;
		//
		/*
		 * var fbwidth = Math.ceil((objThis.JQ(document).width() * 90) / 100); var fbheight =
		 * Math.ceil((objThis.JQ(document).height() * 90) / 100); // if(fullToorOptions.width) {
		 * if(fullToorOptions.width.indexOf("%")) { var perc = parseFloat(fullToorOptions.width.split("%").join(""));
		 * fbwidth = Math.ceil((objThis.JQ(document).width() * perc) / 100); }else fbwidth = fullToorOptions.width; }
		 * if(fullToorOptions.height) { if(fullToorOptions.height.indexOf("%")) { var perc =
		 * parseFloat(fullToorOptions.height.split("%").join("")); fbheight = Math.ceil((objThis.JQ(document).height() *
		 * perc) / 100); }else fbheight = fullToorOptions.height; }
		 */
		//
		//
		var popButtons = [
				// 'slideShow',
				'fullScreen',
				// 'thumbs',
				// 'share',
				// 'download',
				// 'zoom',
				'close'
			];
		if(fullToorOptions.allowShare) popButtons.unshift('share');
		//
		objThis.JQ(linkOrigin).fancybox({
			type			: 	'iframe',
			title			:	modalName,
			transitionIn	:	'elastic',
			transitionOut	:	'elastic',
			speedIn			:	600, 
			speedOut		:	200, 
			overlayShow		:	true

			,closeBtn   	: 	true
			// ,fitToView : true

			,padding 		: 	15
			,margin  		: 	0

			,width     		: 	fbwidth
			,height    		: 	fbheight

			,protect 		: 	true
			,fullScreen 	: {
				autoStart : false
			}

			,modal 			: false
			,iframe 		: {
				preload: true
			}

			,buttons : popButtons

			,beforeLoad : function(obj, popupObj)
			{
				setTimeout(function(){
					if(popupObj && popupObj.$slide && popupObj.$slide[0])
					{
						var content = popupObj.$slide[0].childNodes.item(0);
						objThis.JQ(content).width(fbwidth);
						objThis.JQ(content).height(fbheight);
						//
						objThis.JQ(content).addClass("tt_vtour_popup_modal");
					}else
					{
					    objThis.JQ(".fancybox-opened").width(fbwidth);
					    objThis.JQ(".fancybox-opened").height(fbheight);
					    //
					    objThis.JQ(".fancybox-opened .fancybox-skin").width(fbwidth);
					    objThis.JQ(".fancybox-opened .fancybox-skin").height(fbheight);
					    //
					    objThis.JQ(".fancybox-opened").css("top", "2%"); 
					    objThis.JQ(".fancybox-opened").addClass("tt_vtour_popup_modal");
					}
				}, 500);
			}

			,afterLoad : function(obj, popupObj)
			{
			    if(!popupObj || !popupObj.$slide)
			    {
					setTimeout(function(){
					    objThis.JQ(".fancybox-opened").css("top", "2%"); 
					    objThis.JQ(".fancybox-opened").addClass("tt_vtour_popup_modal");
					}, 500);
			    }
			}
		});
	}
	
	/**
	 * Instantiate the VTOUR library instance
	 * 
	 */
	function _instantiate()
	{
		_laodMainThumbnail();
		//
		objThis.win.removepano(objThis.options.id);
		//
		//
		var dom = objThis.JQ("#" + objThis.options.target);
		if(objThis.options.usePositionAbsolute)
		{
		    dom.css("position", "absolute");
		    dom.css("z-index", "9999");
		    //
		    dom.height("98%");
		    dom.width("99%");
		}else
		{
		    dom.height("100%");
		    dom.width("100%");
		}
		//
		if(objThis.options.ttGetXMLAjax)
		{
			_instantiateXML();
		}else
			{
				objThis.win.embedpano(objThis.options);
				// objThis.win.embedpano({swf: TOUR_SWF_URL, xml: TOUR_XML_URL, target:"pano", html5:"prefer",
				// mobilescale:1.0, passQueryParameters:true, wmode: null});
			}
	}

	/**
	 * Used when manual XML Ajax call is requested in order to feed and create the krpano instance with XML string
	 * 
	 */
	function _instantiateXML()
	{
		console.log("TTVTour initialize settings");
		objThis.COUNT_AJAX_XML++;
		//
		if(objThis.COUNT_AJAX_XML <= 3)
		{
			var xmlURL = objThis.options.xml;

			objThis.JQ.ajax(xmlURL,{
										timeout: 30000
										,async: true
										,dataType: "text"
										,method: "GET"
										,success: function( data, textStatus, jqXHR ){
											//
											console.log("TTVTour: Ajax ["+textStatus+"]");
											//
											var opt = objThis.options;
											opt.xml = "";
											objThis.win.embedpano(opt);
											//
											var xmlstring = escape(data);
											//
											objThis.LIB_INSTANCE.call("loadxml(" + xmlstring + ", null, null, BLEND(0.5));");
											objThis.COUNT_AJAX_XML = 0;
											//
										}
										,error: function( jqXHR, textStatus, errorThrown ){
											//
											console.log("TTVTour ERROR: Ajax failed to retrieve XML config [" + xmlURL + "]");
											console.log("TTVTour ERROR: Ajax status=[" + textStatus +"]");
											console.log("TTVTour ERROR: Ajax Error=[" + errorThrown +"]");
											objThis.win.setTimeout(_instantiateXML, 1000);
											//
										}
									}
			);

			/*
			 * objThis.JQ.get(xmlURL, null, function( data ) { // var opt = objThis.options; opt.xml = "";
			 * objThis.win.embedpano(opt); // var xmlstring = escape(data); // objThis.LIB_INSTANCE.call("loadxml(" +
			 * xmlstring + ", null, null, BLEND(0.5));"); objThis.COUNT_AJAX_XML = 0; // }, "text").fail(function() { //
			 * console.log("TTVTour: Ajax failed to retrieve XML config [" + xmlURL + "]");
			 * objThis.win.setTimeout(_instantiateXML, 1000); // }).always(function() { });
			 */
		}
	}

	function _prepareQueryParams(skipFirstParamSeparator)
	{
		var params = "";
		if(objThis.options.params)
		{
			for(var pname in objThis.options.params)
			{
				if(!objThis.options.params[pname]) continue;
				//
				params += pname;
				params += "=";
				params += encodeURIComponent(objThis.options.params[pname]);
				params += "&";
			}
		}
		//
		if(!skipFirstParamSeparator) params = "?" + params;
		//
		return params;
	}

	function _preparePathVariables(subDivisionId, divisionId, entityId)
	{
		if(subDivisionId==undefined || subDivisionId=="") subDivisionId = null;
		if(divisionId==undefined || divisionId=="") divisionId = null;
		if(entityId==undefined || entityId=="") entityId = null;

		var params = [];
		if(objThis.options.params)
		{
			var paramObj = objThis.options.params;
			//
			params.push(paramObj.type);
			params.push(paramObj.name);
			params.push(paramObj.countrycode);
			params.push(paramObj.id);
			if(paramObj.group)
				params.push(paramObj.group);
			if(divisionId || paramObj.division) 
				params.push(divisionId || paramObj.division);
			if(subDivisionId || paramObj.subdivision)
				params.push(subDivisionId || paramObj.subdivision);
			//
		}
		//
		params = params.join("/");
		//
		return params; 
	}

	function _laodMainThumbnail()
	{
		if(objThis.options.showThumbnail)
		{
			// TODO Get URL image to load from TT REST API
			objThis.selectorElt.css({overflow: "hidden"});
			//
			objThis.selectorElt.find("img.touristtube_360_thumb").remove();
			//
			objThis.selectorElt.prepend("<img src='" + TT_TOUR_THUMB_URL + "' onerror='this.style.display=\"none\";' class='touristtube_360_thumb' style='max-width: 100%; max-height: 100%;'/>");
			//
			if(!objThis.options.autoload)
			{
				objThis.selectorElt.find("img.touristtube_360_thumb").on("click", function(){
					objThis.loadEngine();
				});
			}
		}
		//
		objThis.selectorElt.find("#" + objThis.ID_CONTENT).remove();
		objThis.selectorElt.prepend("<div id='" + objThis.ID_CONTENT + "' ></div>");
	}

	function _initHeaders(headerTags)
	{
		if( (TT_TOUR_HEADER && !objThis.win.HEADER_INITIALIZED) || headerTags)
		{
			headerTags = headerTags || TT_TOUR_HEADER;
			for(var i = 0 ; i < headerTags.length; i++)
			{
				var tag = objThis.JQ("<" + headerTags[i].tag + "></" + headerTags[i].tag + ">");
				var oldTag = objThis.JQ("head " + headerTags[i].tag + "[name='" + headerTags[i].attributes.name + "']");
				if(oldTag.length == 0) oldTag = null;
				else tag = null;
				//
				//
				for(var attribute in headerTags[i].attributes)
				{
					if(oldTag)
					{
						if(attribute.toLowerCase() != "name") oldTag.attr(attribute, oldTag.attr(attribute) + " " + headerTags[i].attributes[attribute]);
					}else
					{
						tag.attr(attribute, headerTags[i].attributes[attribute]);
					}
				}
				//
				if(tag) objThis.JQ("head").prepend(tag);
			}
			//
			objThis.win.HEADER_INITIALIZED = true;
		}
	}

	function _initFullTourURL()
	{
		var pattern = objThis.FULL_TOUR_URL_PATTERN[objThis.options.params.type];
		if(pattern)
		{
			if(objThis.options.params)
			{
				for(var p in objThis.options.params)
				{
					var name = objThis.options.params[p];
					if(name && typeof(name)=="string") name = name.split("-").join("+");
					pattern = pattern.split("{" + p + "}").join(encodeURIComponent(name));
				}
			}
			objThis.FULL_TOUR_URL = TOUR_HOST_URL + pattern;
		}
	}

	function _getPanoramaUrl(subDivisionId, divisionId, entityId)
	{
		var pathVars = _preparePathVariables(subDivisionId, divisionId, entityId);
		var queryParams = _prepareQueryParams();
		var entityName = encodeURIComponent(objThis.options.params.name.split(" ").join("-"));
		//
		var TT_PANORAMA_PATH = null;
		if(objThis.win.TT_PANORAMA_PATH) TT_PANORAMA_PATH = objThis.win.TT_PANORAMA_PATH;
		else if(objThis.options.params.subdivision || subDivisionId) TT_PANORAMA_PATH = pathVars;
		//
		if(TT_PANORAMA_PATH)
		{
			TT_PANORAMA_PATH = TT_TOUR_PANORAMA_URL + TT_PANORAMA_PATH + "/" + objThis.options.panoramaThumbName + objThis.options.thumbExtension + "/" + entityName + objThis.options.thumbExtension;
			TT_PANORAMA_PATH += queryParams;
		}
		//
		return TT_PANORAMA_PATH;
	}

	function _initPopupImports()
	{
		objThis.JQ('head').append('<link rel="stylesheet" href="' + TOUR_HOST_URL + 'assets/common/tt/js/vtour/css/socialshare.css" type="text/css" />');
		if(objThis.options.loadPopupLibrary && !objThis.TT_POPUP_LIBRARY_LOADED)
		{
			objThis.JQ('head').append('<link rel="stylesheet" href="' + POPUP_CSS_LIB_URL + '" type="text/css" />');
			objThis.JQ.getScript(POPUP_JS_LIB_URL).done(function(){
				objThis.TT_POPUP_LIBRARY_LOADED = true;
			});
		}
	}

	function _showSocialShare(subDivisionId, divisionId, entityId)
	{
		var TT_PANORAMA_PATH = _getPanoramaUrl(subDivisionId, divisionId, entityId);
		//
		if(TT_PANORAMA_PATH)
		{
			var html = '<div class="tt-vtour-social-share">'
						// + '<link rel="stylesheet" href="' + TOUR_HOST_URL +
						// 'assets/common/tt/js/vtour/css/socialshare.css">'
						+ '<div class="sharedata_popup" id="sharedata_popupid">'
						+ '	<div class="share_title">share this 360 tour</div>'
						+ '	<div class="left">'
						+ '		<div class="share_social">'
						+ '			<label>social</label>'
						// + ' <a
						// href="https://www.facebook.com/dialog/feed?app_id='+TT_FB_APP_ID+'&display=popup&caption=An%20example%20caption&link='
						// + TT_PANORAMA_PATH + '&redirect_uri=" target="_blank" rel="nofollow" title="facebook"
						// class="fb-share sharecommun"></a>'
						+ '			<a href="https://www.facebook.com/dialog/share?app_id='+TT_FB_APP_ID+'&title=tt&display=page&href='+TT_PANORAMA_PATH+'&redirect_uri=" target="_blank" rel="nofollow" title="facebook" class="fb-share sharecommun"></a>'

						// + ' <a
						// href="http://www.facebook.com/sharer.php?s=100&p[title]=tt_test_title&p[description]=tt_test_description&p[caption]=tt_test_caption&p[message]=tt_test_message&p[summary]=tt_360_summary&p[url]='+TT_PANORAMA_PATH+'"
						// target="_blank" rel="nofollow" title="facebook" class="fb-share sharecommun"></a>'
						+ '			<a href="https://plus.google.com/share?url='+TT_PANORAMA_PATH+'" target="_blank" rel="nofollow" title="google+" class="g-share sharecommun"></a>'
						+ '			<a href="http://www.twitter.com/intent/tweet?url='+TT_PANORAMA_PATH+'" target="_blank" rel="nofollow" title="twitter" class="twt-share sharecommun"></a>'
						+ '			<a href="https://pinterest.com/pin/create/bookmarklet/?media='+TT_PANORAMA_PATH+'&url='+TT_PANORAMA_PATH+'" target="_blank" rel="nofollow" title="pinterest" class="print-share sharecommun"></a>'
						+ '		</div>'
						+ '	</div>'
						+ '	</div>'
						+ '</div>';
			//
			//
			if(objThis.options.loadPopupLibrary) objThis.JQ.fancybox.open(objThis.JQ(html), {type: 'iframe'});
			else objThis.JQ.fancybox(objThis.JQ(html));
		}
	}

	function _initCallBacks()
	{
		// SCENE LOAD CALLBACK, USED TO MARK WHICH SCENE IS LOADED, CALLED FROM 360 ENGINE
		objThis.win.ON_TT_VTOUR_SCENE_STARTED = function(subDivisionId, divisionId)
		{
			objThis.win.TT_VTOUR_LOADED_SCENE = {subDivisionId: subDivisionId, divisionId: divisionId};
		}
		//
		//
		//
		objThis.win.TT_VTOUR_INFO_ACTION = function(){
			var instance = _getVtourInstance();
			console.log("TT VTour Info callback");
		}
		//
		//
		objThis.win.TT_VTOUR_SHARE_ACTION = function(){
			var instance = _getVtourInstance();
			console.log("TT VTour Share callback");
			//
			var TT_VTOUR_LOADED_SCENE = objThis.win.TT_VTOUR_LOADED_SCENE || {};
			if(instance) instance.shareTour(TT_VTOUR_LOADED_SCENE.subDivisionId, TT_VTOUR_LOADED_SCENE.divisionId);
		}
	}

	function _getVtourInstance()
	{
		return eval("objThis.win.TT_VTOUR_INSTANCE_" + objThis.ID);
	}

	function _init()
	{
		//
		eval("objThis.win.TT_VTOUR_INSTANCE_" + objThis.ID + " = objThis;");
		//
		objThis.options = objThis.defaultOptions;
		objThis.JQ.extend( true, objThis.options, _options);
		//
		if(objThis.options.params.name==null) objThis.options.params.name = "";
		objThis.options.params.name = objThis.options.params.name.toString();
		//
		var queryParams = _prepareQueryParams();
		var pathVars = _preparePathVariables();
		objThis.options.xml = TOUR_XML_URL + pathVars + queryParams;
		//
		objThis.options.swf = TOUR_SWF_URL + queryParams;
		//
		//
		_initPopupImports();
		//
		_initFullTourURL();
		//
		var entityName = encodeURIComponent(objThis.options.params.name.split(" ").join("-"));
		// PREPARE THUMB URL SEO FRIENDLY

		TT_TOUR_THUMB_URL += pathVars + objThis.options.thumbName + objThis.options.thumbExtension + "/" + entityName + TT_TOUR_THUMB_SUFFIX + objThis.options.thumbExtension; // objThis.options.params.type
																																												// +
																																												// "/"
																																												// +
																																												// entityName
																																												// +
																																												// "/"
																																												// +
																																												// objThis.options.params.countrycode
																																												// +
																																												// "/"
																																												// +
																																												// entityName
																																												// +
																																												// TT_TOUR_THUMB_SUFFIX
																																												// +
																																												// objThis.options.thumbExtension;
		TT_TOUR_THUMB_URL += queryParams;
		//
		//
		_initCallBacks();
		//
		//
		_initHeaders()
		//
		_laodMainThumbnail();
		_loadJS();
		//
		//
		_prepareTTInfo();
	}
	//
	function _prepareTTInfo()
	{
		var INFO_CONTAINER_ID = "#" + objThis.ID + "_tt_vtour_info_container";
		//
		if(!objThis.win.hideInfoPopup)
		{
			objThis.win.hideInfoPopup = function()
			{
				$(INFO_CONTAINER_ID).addClass("hide");
			}
		}
		//
		if(!objThis.win.rePositionInfoPopup)
		{
			objThis.win.rePositionInfoPopup = function()
			{
				if($(INFO_CONTAINER_ID).length > 0 && !$(INFO_CONTAINER_ID).hasClass("hide"))
				{
					objThis.win.hideInfoPopup();
					setTimeout(objThis.win.ttInfoAction, 500);
				}
			}
		}
		//
		if(!objThis.win.ttInfoAction)
		{
			objThis.win.ttInfoAction = function()
			{
				var viewerContainer  = $("#" + objThis.ID_CONTENT + "_viewer");

				if($(INFO_CONTAINER_ID).length == 0)
				{
					var infoContent = $("<div>").attr("id", INFO_CONTAINER_ID.split("#").join("")).addClass('tt_vtour_info_container hide');
					if(objThis.win.TT_VTOUR_INFO) infoContent.html(objThis.win.TT_VTOUR_INFO);
					viewerContainer.append(infoContent);
				}
				var viewerContainerElt = $(viewerContainer.find("> div").get(0));
				viewerContainerElt = $(viewerContainerElt.find("> div").get(1));
			    var indx = ($(viewerContainerElt.find("> div").get(3)).find("> div").length == 1 ? 4 : 3);
			    viewerContainerElt = $(viewerContainerElt.find("> div").get(indx));
				viewerContainerElt = $(viewerContainerElt.find("> div").get(2));
				viewerContainerElt = $(viewerContainerElt.find("> div").get(0));

				viewerContainerElt = $(viewerContainerElt.find("> div").get(6));

				var parentElt  = viewerContainerElt.parent().parent();
				var parentTop = parentElt.position().top;
				var parentLeft = viewerContainerElt.parent().parent().parent().position().left;
				var left = viewerContainerElt.position().left;
				var width = viewerContainerElt.width();

				$(INFO_CONTAINER_ID).css({"left": 0, "top": 0});

				$(INFO_CONTAINER_ID).toggleClass("hide");

				var top = parentTop - ($(INFO_CONTAINER_ID).height()) - 11;
				if(parentElt.position().top >= viewerContainer.height()) top -= 20;
				//
				if(parentTop > viewerContainer.height())
					top -= ((parentTop - viewerContainer.height()) + 4);
				//
				left += (parentLeft - ($(INFO_CONTAINER_ID).width() / 2) + (width / 2));

				if(left < parentElt.position().left) left = parentElt.position().left;

				var cssOpts = {"top": top, "left": left};

				$(INFO_CONTAINER_ID).css(cssOpts);
				//
				$(window).on("resize", function(){
					objThis.win.rePositionInfoPopup();
				});
				//
				$("#pano").on("mousedown", function(evt){
					hideInfoPopup();
				});
				//
				viewerContainerElt.on("click", function(evt){
					evt.stopPropagation();
				});
				//
				$(INFO_CONTAINER_ID).on("mousedown", function(evt){
					evt.stopPropagation();
				});
				//
			    $(document).on("touchstart", function(evt){
	                hideInfoPopup();
			    });
			}
		}
	}
	//
	_init();
}
