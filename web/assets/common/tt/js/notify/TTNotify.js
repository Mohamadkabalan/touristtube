window.TT_NOTIFY_INSTANCES = [];
window.getTTNotify = function(_id, _options, _settings, _win, _doc)
{
	var inst = {};
	//
	if (_id && window.TT_NOTIFY_INSTANCES[_id]) {
		inst = window.TT_NOTIFY_INSTANCES[_id];
		if (_options)
			inst.setOptions(_options, false);
		if (_settings)
			inst.setSettings(_settings, false);
	} else {
		inst = new TTNotify(_id, _options, _settings, _win, _doc);
		window.TT_NOTIFY_INSTANCES[inst.ID] = inst;
	}
	//
	return inst;
}

window.showTTNotify = function(title, message, type, settings)
{
	settings = settings || {};
	//
	settings.type = type;
	//
	return window.getTTNotify(null, {
	    title : title,
	    message : message
	}, settings).show();
}

function TTNotify(_id, _options, _settings, _win, _doc)
{
	var objThis = this;
	objThis.name = "TT_NOTIFY";
	objThis.win = _win ? _win : self;
	objThis.doc = _doc ? _doc : objThis.win.document;
	objThis.JQ = objThis.win.$;
	objThis.ttUtilsInst = objThis.win.ttUtilsInst ? objThis.win.ttUtilsInst : new TTUtils(objThis.win, objThis.doc);
	//
	//
	if (!objThis.JQ)
		throw "JQuery library is required for this component";
	if (!objThis.JQ.notify)
		throw "Bootstrap Notify JS library is missing";
	if (!objThis.ttUtilsInst)
		throw "TTUtils JS library is missing";
	//
	objThis.UNIQUE_ID = objThis.ttUtilsInst.generateUniqueId();
	objThis.ID = _id || objThis.UNIQUE_ID;
	//
	objThis.NATIVE_ELT = null;
	//
	objThis.DEFAULT_ALERTS_TITLE = "Touristtube";
	objThis.NOTIFY_ID_PREFIX = "tt_notify_";
	objThis.NOTIFY_ID = objThis.NOTIFY_ID_PREFIX + objThis.ID;
	//
	//
	//
	objThis.setOptions = _setOptions;
	objThis.setSettings = _setSettings;
	//
	objThis.show = show;
	objThis.close = close;
	objThis.updateTitle = updateTitle;
	objThis.updateMessage = updateMessage;
	objThis.updateIcon = updateIcon;
	objThis.updateType = updateType;
	objThis.updateProgress = updateProgress;
	objThis.updateUrl = updateUrl;
	objThis.updateTarget = updateTarget;
	//
	//
	//
	objThis.options = null;
	objThis.defaultOptions = {
	    icon : 'glyphicon glyphicon-warning-sign',
	    title : objThis.DEFAULT_ALERTS_TITLE,
	    message : '',
	    url : null,
	    target : '_blank'
	};
	objThis.settings = null;
	objThis.defaultSettings =
	        {
	            element : 'body',
	            // settings
	            position : null,
	            type : "info",
	            allow_dismiss : true,
	            newest_on_top : true,
	            showProgressbar : false,
	            placement : {
	                from : "top",
	                align : "center"
	            },
	            offset : 5,
	            spacing : 10,
	            z_index : 1031,
	            delay : 5000,
	            timer : 1000,
	            url_target : '_blank',
	            mouse_over : null,
	            animate : {
	                enter : 'animated fadeInLeft',
	                exit : 'animated fadeOutRight'
	            },
	            onShow : null,
	            onShown : null,
	            onClose : null,
	            onClosed : null,
	            icon_type : 'class',
	            template : '<div id="'
	                    + objThis.NOTIFY_ID
	                    + '_'
	                    + objThis.ttUtilsInst.generateUniqueId()
	                    + '" data-notify="container" class="tt-notify-container col-xs-11 col-sm-3 alert alert-{0}" role="alert">'
	                    + '<div style="width: 100%; height: 3px;"><button type="button" aria-hidden="true" class="close glyphicon glyphicon-remove" data-notify="dismiss"></button></div>'
	                    + '<span data-notify="icon"></span> '
	                    + '<span data-notify="title"><b>{1}</b></span> '
	                    + '<span data-notify="message">{2}</span>'
	                    + '<div class="progress" data-notify="progressbar">'
	                    + '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>'
	                    + '</div>' + '<a href="{3}" target="{4}" data-notify="url"></a>' + '</div>'
	        };
	//
	// PUBLIC METHODS
	//
	function show(_opts, _sett)
	{
		_opts = _opts || objThis.options;
		_sett = _sett || objThis.settings;
		//
		_sett.type = _getTypeCls(_sett.type);
		//
		objThis.NATIVE_ELT = objThis.JQ.notify(_opts, _sett);
	}

	function close()
	{
		if (objThis.NATIVE_ELT)
			objThis.NATIVE_ELT.close();
	}

	function updateTitle(newValue)
	{
		_update('title', newValue);
	}

	function updateMessage(newValue)
	{
		_update('message', newValue);
	}

	function updateIcon(newValue)
	{
		_update('icon', newValue);
	}

	function updateType(newValue)
	{
		_update('type', newValue);
	}

	function updateProgress(newValue)
	{
		_update('progress', newValue);
	}

	function updateUrl(newValue)
	{
		_update('url', newValue);
	}

	function updateTarget(newValue)
	{
		_update('target', newValue);
	}

	//
	// PRIVATE METHODS
	//
	function _setOptions(options, replace)
	{
		if (options && typeof (options == "object")) {
			if (replace)
				objThis.options = options;
			else
				objThis.options = objThis.ttUtilsInst.extendObject(options, objThis.defaultOptions);
		}
	}

	function _setSettings(settings, replace)
	{
		if (settings && typeof (settings == "object")) {
			if (replace)
				objThis.settings = settings;
			else
				objThis.settings = objThis.ttUtilsInst.extendObject(settings, objThis.defaultSettings);
		}
	}

	function _update(_target, _value)
	{
		if (objThis.NATIVE_ELT)
			objThis.NATIVE_ELT.update(_target, _value);
	}

	function _getTypeCls(type)
	{
		var cls = "";
		if (type)
			type = type.toLowerCase();
		switch (type)
		{
			case 'warning':
				cls = "warning";
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
			case 'default':
			case 'update':
			case 'modify':
			case 'info':
			default:
				cls = "info";
		}
		//
		return cls;
	}

	function _init()
	{
		objThis.options = objThis.ttUtilsInst.extendObject(_options, objThis.defaultOptions);
		objThis.settings = objThis.ttUtilsInst.extendObject(_settings, objThis.defaultSettings);
	}
	//
	_init();
}
