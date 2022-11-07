window.TT_MODAL_INSTANCES = {};
window.getTTModal = function (_id, _options, _win, _doc)
{
    var inst = {};
    //
    if (_id && window.TT_MODAL_INSTANCES[_id])
    {
	inst = window.TT_MODAL_INSTANCES[_id];
	if (_options)
	    inst.setOptions(_options, false);
    } else
    {
	inst = new TTModal(_id, _options, _win, _doc);
	window.TT_MODAL_INSTANCES[inst.ID] = inst;
    }
    //
    return inst;
}

window.showTTLoader = function (_id)
{
	_id = _id || "tt_modal_loader";
	//
	var ttModal = window.getTTModal(_id, {width:"100px", height: "100px", url: null, title: "", content: "<div class='ttmodal_loader' style='width: 100%; height: 100%;'>&nbsp;</div>", footer: "", keyboard: false, backdrop: 'static', buttons: null, cls: null, attr: null});
	ttModal.show();
	//
	return ttModal;
}

window.hideTTLoader = function (_id)
{
	_id = _id || "tt_modal_loader";
	//
	var ttModal = window.getTTModal(_id);
	ttModal.hide();
	//
	return ttModal;
}

/**
 * Model component
 * 
 * @param _id
 * @param _options
 * @param _win
 * @param _doc
 * 
 * @author Fares Zgheib
 * 
 */
function TTModal(_id, _options, _win, _doc)
{
    var objThis = this;
    objThis.name = "TT_MODAL";
    objThis.win = _win ? _win : self;
    objThis.doc = _doc ? _doc : objThis.win.document;
    objThis.JQ = objThis.win.$;
    objThis.ttUtilsInst = objThis.win.ttUtilsInst ? objThis.win.ttUtilsInst : new TTUtils(objThis.win, objThis.doc);
    //
    //
    if (!_id || _id.split(" ").join("") == "")
	throw "Provided ID cannot be empty or null";
    if (!objThis.JQ)
	throw "JQuery library is required for this component";
    if (!objThis.JQ.fn.modal)
	throw "Bootstrap Modal JS library is missing";
    if (!objThis.ttUtilsInst)
	throw "TTUtils JS library is missing";
    //
    objThis.UNIQUE_ID = objThis.ttUtilsInst.generateUniqueId();
    objThis.ID = _id || objThis.UNIQUE_ID;
    objThis.MODAL_ID_PREFIX = "ttModal_";
    objThis.MODAL_ID = (objThis.JQ("#" + objThis.ID).length > 0 ? objThis.ID : objThis.MODAL_ID_PREFIX + objThis.ID);
    //
    objThis.selector = ("#" + objThis.MODAL_ID);
    objThis.selectorElt = objThis.JQ(objThis.selector);
    //
    objThis.DEFAULT_ALERTS_TITLE = "Tourist Tube";
    objThis.BTN_ID_PREFIX = "btn_";
    objThis.DYNAMIC_MODAL = (!objThis.selectorElt || objThis.selectorElt.length <= 0);
    objThis.ALL_CENTER_VERTICAL = false;
    //
    objThis.options = null;
    objThis.defaultOptions = {
	show: false,
	title: null,
	content: null,
	footer: null,
	url: null, // {href: null, inFrame: true, params: null},
	buttons: null, // {buttons:[{id:'', value: '', type:'default|close|validate|danger|info|warning', cls: '',
					// attr:[{'key': 'value'}], action:function(btn){}}, closeModal: false]}
	centerVertical: true,
	autoResizeHeight: true,
	backdrop: true,	// static
	keyboard: true,
	focus: true,
	onShow: function (modalElt) {},
	onBeforeHide: function (modalElt) {
	    return true;
	},
	onHide: function (modalElt) {}
    };
    //
    //
    objThis.show = show;
    objThis.setOptions = _setOptions;
    objThis.hide = hide;
    objThis.toggle = toggle;
    objThis.destroy = destroy;
    objThis.confirm = confirm;
    objThis.alert = alert;
    objThis.confirmNO = confirmNO;
    objThis.confirmDelete = confirmDelete;
    objThis.getIframe = getIframe;
    //
    //
    // PUBLIC METHODS
    //
    function show(options)
    {
	_setOptions(options);
	//
	var modalElt = _getElement(true);
	//
	objThis.options.show = true;
	_renderModal();
	//
	//
	/*
	 * if (objThis.options.autoResizeHeight) _setModalMaxHeight(modalElt); else _setModalDimensions();
	 */
	if (objThis.options.centerVertical)
	    _setCssForVerticalCenter();
	//
	// Trigger Event Before Modal is opened from inline HTML binding >>>>>> not via JS
	modalElt.off('show.bs.modal');
	modalElt.on('show.bs.modal', function () {
	    //
	    /*
		 * if (objThis.options.autoResizeHeight) _setModalMaxHeight(this); else _setModalDimensions();
		 */
	    //
	});
	// Trigger Event after Modal is opened
	modalElt.off('shown.bs.modal');
	modalElt.on('shown.bs.modal', function () {
		
	    objThis.JQ(this).addClass("show");
	    // To auto focus first input in the opened Modal
	    objThis.JQ('input').first().trigger('focus');
	    //
	    if (objThis.options.autoResizeHeight)
		objThis.JQ(objThis.win).off("resize", _onResize);
	    //
	    //
	    if (objThis.options.autoResizeHeight)
			_setModalMaxHeight(this);
		    else
			_setModalDimensions();
	    //
	    //
	    setTimeout(function(){_getElement().find('.modal-dialog').show();}, 300);
	    //
	    if (objThis.options.onShow)
		return objThis.options.onShow(modalElt);
	});
	// Trigger Event before Modal is closed
	modalElt.off('hide.bs.modal');
	modalElt.on('hide.bs.modal', function () {
	    //
	    if (objThis.options.onBeforeHide)
		return objThis.options.onBeforeHide(modalElt);
	    else
		return true;
	});
	// Trigger Event after Modal is closed
	modalElt.off('hidden.bs.modal');
	modalElt.on('hidden.bs.modal', function () {
	    if (objThis.options.autoResizeHeight)
		objThis.JQ(objThis.win).off("resize", _onResize);
	    objThis.JQ(this).removeClass("show");
	    //
	    if (objThis.options.onHide)
		objThis.options.onHide(modalElt);
	    //
	    if (objThis.DYNAMIC_MODAL)
		objThis.selectorElt.remove();
	});
	//
    }

    function hide()
    {
	_getElement().modal("hide");
    }

    function destroy()
    {
    	if(objThis.win.TT_MODAL_INSTANCES && objThis.win.TT_MODAL_INSTANCES[objThis.ID])
    		delete objThis.win.TT_MODAL_INSTANCES[objThis.ID];
	_getElement().modal("dispose");
    }

    function toggle()
    {
	_getElement().modal("toggle");
    }

    function confirm(message, callBack, title, customButtons)
    {
	var buttons = _prepareAlertsButtons(customButtons);
	//
	objThis.show({title: title || objThis.DEFAULT_ALERTS_TITLE, content: message || "", buttons: [
		{id: 'ok', value: buttons.ok.value, type: buttons.ok.type, action: callBack, closeModal: true, cls: buttons.ok.cls},
		{id: 'cancel', value: buttons.cancel.value, type: buttons.cancel.type, action: callBack, closeModal: true, cls: buttons.cancel.cls}
	    ]
	});
    }

    function alert(message, callBack, title, customButtons)
    {
	if (!customButtons)
	    customButtons = {ok: {type: "default"}};
	else if (customButtons.ok && !customButtons.ok.type)
	    customButtons.ok.type = "default";
	var buttons = _prepareAlertsButtons(customButtons);
	//
	objThis.show({title: title || objThis.DEFAULT_ALERTS_TITLE, content: message || "", buttons: [
		{id: 'ok', value: buttons.ok.value, type: buttons.ok.type, action: callBack, closeModal: true, cls: buttons.ok.cls}
	    ]
	});
    }

    function confirmNO(message, callBack, title, customButtons)
    {
	var buttons = _prepareAlertsButtons(customButtons);
	//
	objThis.show({title: title || objThis.DEFAULT_ALERTS_TITLE, content: message || "", buttons: [
		{id: 'ok', value: buttons.ok.value, type: buttons.ok.type, action: callBack, closeModal: true, cls: buttons.ok.cls},
		{id: 'no', value: buttons.no.value, type: buttons.no.type, action: callBack, closeModal: true, cls: buttons.no.cls},
		{id: 'cancel', value: buttons.cancel.value, type: buttons.cancel.type, action: callBack, closeModal: true, cls: buttons.cancel.cls}
	    ]
	});
    }

    function confirmDelete(message, callBack, title, customButtons)
    {
	var buttons = _prepareAlertsButtons(customButtons);
	//
	objThis.show({title: title || objThis.DEFAULT_ALERTS_TITLE, content: message || "", buttons: [
		{id: 'delete', value: buttons.delete.value, type: buttons.delete.type, action: callBack, closeModal: true, cls: buttons.delete.cls},
		{id: 'cancel', value: buttons.cancel.value, type: buttons.cancel.type, action: callBack, closeModal: true, cls: buttons.cancel.cls}
	    ]
	});
    }

    function getIframe()
    {
	return _getElement().find(".modal-body iframe#" + objThis.ID + "_frame");
    }
    //
    // PRIVATE METHODS
    //
    function _getElement(forceUpdate)
    {
	if (objThis.DYNAMIC_MODAL || objThis.selectorElt == null)
	{
	    if (objThis.JQ(objThis.selector).length > 0)
		objThis.selectorElt = objThis.JQ(objThis.selector);
	    else if (forceUpdate || objThis.selectorElt == null || objThis.selectorElt.length <= 0)
		objThis.selectorElt = _getTemplate();
	}
	//
	return objThis.selectorElt;
    }

    function _renderModal()
    {
    _getElement().find('.modal-dialog').hide();

	_getElement().modal(objThis.options);
	_adjustModalContent();
    }

    function _adjustModalContent()
    {
	// to readjust the modal’s position in case a scrollbar appears
	_getElement().modal('handleUpdate');
    }

    function _setCssForVerticalCenter()
    {
	var selector = (objThis.ALL_CENTER_VERTICAL ? "" : ("#" + objThis.MODAL_ID));
	var styleId = (objThis.ALL_CENTER_VERTICAL ? "ttmodal" : objThis.MODAL_ID);
	//
	objThis.ttUtilsInst.addStylsheet("stylesheet_" + styleId, ".modal" + selector + "{text-align:center; padding: 0 !important;}", false, true);
	objThis.ttUtilsInst.addStylsheet("stylesheet_" + styleId, ".modal" + selector + ":before{content:''; display:inline-block; height:100%; vertical-align:middle; margin-right:-4px;}", true, false);
	objThis.ttUtilsInst.addStylsheet("stylesheet_" + styleId, selector + " .modal-dialog{display:inline-block; text-align:left; vertical-align:middle;}", true, false);
    }

    function _initStyleSheet()
    {
	var styleId = "ttmodal_loader";
	//
	objThis.ttUtilsInst.addStylsheet("stylesheet_" + styleId, "#" + objThis.MODAL_ID + " .modal-header .close{margin-top: -30px; margin-right: -7px;} .ttmodal_loader{background-image: url(data:image/gif;base64,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); background-repeat: no-repeat; background-position: center; background-size: auto 2em;}", false, true);
    }

    function _setModalMaxHeight(element) {

	setTimeout(function(){
	//
	_setModalDimensions();
	//
	element = element || _getElement();
	//
	this.$element = objThis.JQ(element);
	this.$content = this.$element.find('.modal-content');
	var borderWidth = ((isNaN(this.$content.outerHeight()) || this.$content.outerHeight() == "") ? 0 : this.$content.outerHeight()) - ((isNaN(this.$content.innerHeight()) || this.$content.innerHeight() == "") ? 0 : this.$content.innerHeight());
	var dialogMargin = objThis.JQ(objThis.win).width() < 768 ? 20 : 60;
	var contentHeight = objThis.JQ(objThis.win).height() - (dialogMargin + borderWidth);
	var headerHeight = this.$element.find('.modal-header').outerHeight() || 0;
	var footerHeight = this.$element.find('.modal-footer').outerHeight() || 0;

	if(footerHeight>0) footerHeight = Math.min(footerHeight, 120);
	if(headerHeight>0) headerHeight = Math.min(headerHeight, 120);
	var maxHeight = contentHeight - (headerHeight + footerHeight);

	this.$content.css({
	    'overflow': 'hidden'
	});

	this.$element
		.find('.modal-body').css({
	    'max-height': maxHeight,
	    'overflow-y': 'auto'
	});

	this.$element
		.find('.modal-dialog, .modal-content').css({
	    'max-height': contentHeight,
	    // 'overflow-y': 'auto'
	});
	//
	},500)
    }

    function _setModalDimensions()
    {
	var modal = _getElement().find('.modal-dialog, .modal-content');
	if (objThis.options.height)
	    modal.css({
		'max-height': objThis.options.height,
		'height': objThis.options.height
	    });
	//
	if (objThis.options.width)
	    modal.css({
		'max-width': objThis.options.width,
		'width': objThis.options.width
	    });
	//
	//
	var modalBody = _getElement().find('.modal-body');
	var footerHeight = _getElement().find('.modal-footer').outerHeight() || 0;
	if(footerHeight>0) footerHeight = Math.max(footerHeight, 120);
	if (objThis.options.height)
	{
		var height = objThis.options.height + "";
		height = parseInt(height.split("px").join("")) - (footerHeight);

	    modalBody.css({
		'max-height': height,
		'height': height
	    });
	}
    }

    function _onResize()
    {
	if ($('.modal.show').length != 0) {
	    _setModalMaxHeight(objThis.JQ('.modal.show'));
	}
    }

    function _setOptions(options, replace)
    {
	if (options && typeof (options == "object"))
	{
	    if (replace)
		objThis.options = options;
	    else
		objThis.options = objThis.ttUtilsInst.extendObject(options, objThis.defaultOptions);
	}
    }

    //
    // TEMPLATE
    //
    function _getHeader()
    {
	var header = '	  <div class="modal-header">'
		+ '		<h5 class="modal-title" id="ttModal' + objThis.UNIQUE_ID + 'Label">' + objThis.options.title + '</h5>'
		+ '		<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
		+ '		  <span aria-hidden="true">&times;</span>'
		+ '		</button>'
		+ '	  </div>';
	//
	return header;
    }

    function _getBody()
    {
	var body = '<div class="modal-body">';
	if (objThis.options.content)
	{
	    body += objThis.options.content;
	}
	if (objThis.options.url && objThis.options.url.href && !objThis.options.url.inFrame)
	    body += "<div class='modal-url-body'></div>";

	//
	if (objThis.options.url && objThis.options.url.inFrame)
	    body += '		<iframe id="'+objThis.ID+'_frame" type="text/html" width="100%" height="100%" src="' + objThis.options.url.href + '" frameborder="0" allowfullscreen=""></iframe>';
	//
	body += '	  </div>';
	//
	return body;
    }

    function _getFooter()
    {
	var footer = '	  <div class="modal-footer">'
		+ '		<span>' + (objThis.options.footer || '') + '</span>'
		+ '		{modal_buttons}'
		// +' <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->'
		// +' <button type="button" class="btn btn-light" onclick="ttModal.hide()">Close JS</button>'
		// +' <button type="button" class="btn btn-primary">Send message</button>'
		+ '	  </div>';
	//
	return footer;
    }

    function _getTypeCls(type)
    {
	var cls = "";
	switch (type.toLowerCase())
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
	    case 'danger':
		cls = "danger";
		break;
	    case 'validate':
	    case 'success':
		cls = "success";
		break;
	    case 'cancel':
	    case 'close':
		cls = "secondary";
		break;
	    case 'default':
	    default:
		cls = "primary";
	}
	//
	return cls;
    }

    function _getButton(button)
    {
	// {buttons:[{id:'', value: '', type:'default|close|validate|danger|info|warning', cls: '', attr:[{'key': 'value'}],
	// action:function(btn){}}]}
	var result = {html: "", action: {}};
	var html = "";
	if (button && typeof (button) == "object")
	{
	    html += "<button type='button' ";
	    if (button.id)
		html += "id='" + objThis.BTN_ID_PREFIX + button.id + "' ";
	    html += "class='btn btn-" + _getTypeCls(button.type) + (button.cls ? (" " + button.cls) : "") + "' ";
	    if (button.attr)
	    {
		for (var i = 0; i < button.attr.length; i++)
		{
		    for (att in button.attr[i])
			html += att + "='" + button.attr[i][att] + "' ";
		}
	    }
	    //
	    if(button.closeModal && (!button.action || "" == button.action))
    	{
	    	button.action = function(){
    		    objThis.hide();
	    	};
    	}
	    //
	    if (button.action && "" != button.action)
	    {
		// if(typeof(button.action)=="string")
		// html += "onclick='" + button.action + "(this);' ";
		// else
		result.action[button.id] = button;
	    }
	    html += ">";
	    html += button.value;
	    html += "</button>";
	}
	//
	result.html = html;
	//
	return result;
    }

    function _getButtons(buttons)
    {
	var result = {html: "", actions: []};
	buttons = buttons || objThis.options.buttons;
	//
	if (buttons && buttons.length > 0)
	{
	    var butStr = "";
	    //
	    for (var but in buttons)
	    {
		var btn = _getButton(buttons[but]);
		butStr += btn.html;
		if (btn.action)
		    result.actions.push(btn.action);
	    }
	    //
	    result.html = butStr;
	}
	//
	return result;
    }

    function _prepareAlertsButtons(customButtons)
    {
	var buttons = {
	    ok: {value: "OK", type: "validate"}
	    , cancel: {value: "cancel", type: "cancel"}
	    , close: {value: "close", type: "close"}
	    , delete: {value: "delete", type: "danger"}
	    , no: {value: "NO", type: "cancel"}
	};
	if (customButtons)
	    buttons = objThis.ttUtilsInst.extendObject(customButtons, buttons);
	//
	return buttons;
    }

    function _getTemplate(options)
    {
	options = options || objThis.options;
	//
	if (!options)
	    return null;
	//
	var template = '<div class="modal fade" id="' + objThis.MODAL_ID + '" tabindex="-1" role="dialog" aria-labelledby="ttModal' + objThis.UNIQUE_ID + 'Label" aria-hidden="true">'
		+ '  <div class="modal-dialog modal-dialog-centered" role="document">'
		+ '	<div class="modal-content">';

	if (options.title)
	    template += _getHeader();
	if (options.content || (objThis.options.url && objThis.options.url.href))
	    template += _getBody();
	if (options.footer || options.buttons)
	    template += _getFooter();

	template += '	</div>'
		+ '  </div>'
		+ '</div>';
	//
	var buttons = _getButtons(options.buttons);
	template = template.split('{modal_buttons}').join(buttons.html);
	//
	var tempDom = objThis.JQ(template);
	//
	if (objThis.options.url && objThis.options.url.href && !objThis.options.url.inFrame)
	{
		var url = objThis.options.url.href;
	    setTimeout(function () {

	    tempDom.find(".modal-header").addClass("ttmodal_loader");

		tempDom.find(".modal-body .modal-url-body").load(url, function (responseText, textStatus, jqXHR) {
			tempDom.find(".modal-header").removeClass("ttmodal_loader");
		    // confirm("TWIG LOAD COMPLETED");
		    /*
			 * var dom = objThis.JQ("<div>"); dom.html(responseText); // dom.find("link").each(function() { var cssLink =
			 * objThis.JQ(this).attr('href'); objThis.JQ("head").append("<link href='" + cssLink + "' rel='stylesheet'
			 * />"); }); dom.find("script").each(function() { var jsLink = objThis.JQ(this).attr('src');
			 * objThis.JQ("head").append("<script type='text/javascript' src='" + jsLink + "'></script>"); }); //
			 * dom.remove();
			 */
		});
	    }, 500);
	}
	//
	//
	if (buttons.actions)
	{
	    for (var i = 0; i < buttons.actions.length; i++)
	    {
		for (var btn in buttons.actions[i])
		{
		    tempDom.find("button#" + objThis.BTN_ID_PREFIX + btn).on("click", _getCallbackFunction(buttons.actions[i][btn].action, btn, buttons.actions[i][btn]));
		}
	    }
	}
	//
	return tempDom;
    }
    function _getCallbackFunction(callback, btnId, btn)
    {
	var method = null;
	var closeModal = (btn.closeModal == true);
	//
	if (callback && typeof (callback) == "string")
	{
	    method = function () {
		if (closeModal)
		    objThis.hide();
		eval(callback + "('" + btnId + "');");
	    };
	} else
	    method = function () {
		if (closeModal)
		    objThis.hide();
		if(callback && callback!="") callback(btnId);
	    };
	//
	return method;
    }
    //
    //
    //
    function _init()
    {
	objThis.options = objThis.ttUtilsInst.extendObject(_options, objThis.defaultOptions);
	//
	// if(objThis.selector) objThis.selectorElt = _getElement();
	//
	_initStyleSheet();
	//
	var show = objThis.options.show;
	objThis.options.show = true;
	//
	if (show)
	    objThis.show(objThis.options);
	//
    }
    //
    _init();
}
//
// $(document).ready(function(){if(!window.ttModalInst) window.ttModalInst = new TTModal(null, window, document);});
//