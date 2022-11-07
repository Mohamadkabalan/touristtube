/**
 * Form component
 * 
 * 
 * @param id
 * @param _win
 * @param _doc
 * 
 * @author Fares Zgheib
 * 
 */
function TTFormValidator(_selector, _options, _win, _doc)
{
	var objThis = this;
	objThis.name = "TT_FORMVALIDATOR";
	objThis.win = _win ? _win : self;
	objThis.doc = _doc ? _doc : objThis.win.document;
	objThis.JQ = objThis.win.$;
	objThis.ttUtilsInst = objThis.win.ttUtilsInst ? objThis.win.ttUtilsInst : new TTUtils(objThis.win, objThis.doc);
	//
	_selector = _selector || "form.formValidator";
	//
	//
	if (!_selector || _selector.split(" ").join("") == "")
		throw "Provided selector cannot be empty or null";
	if (!objThis.JQ)
		throw "JQuery library is required for this component";
	if (!objThis.JQ.fn.validator)
		throw "AutoComplete JS library is missing";
	if (!objThis.ttUtilsInst)
		throw "TTUtils JS library is missing";
	//
	objThis.options = _options;
	objThis.defaultOptions = {
	    disableSubmitOnEnter : true,
	    messageInputCls : "tt_validation_message",
	    triggerOn : 'change', // input change blur
	    inputSelector : 'input, textarea, select',
	    defaultInValideMsg : 'Something wrong with your entered data.',
	    msgPosition : "right", // top, left, right, bottom
	    onBeforeSubmit : null
	};
	objThis.selector = _selector;
	objThis.selectorElt = objThis.JQ(objThis.selector);
	//
	//
	//
	// PUBLIC METHODS
	//
	//
	objThis.initValidation = initValidation;
	objThis.addCustomRule = addCustomRule;
	objThis.validateElt = validateElt;
	objThis.validate = validate;
    objThis.addValidationMessage=addValidationMessage;
	objThis.removeValidationMessage=removeValidationMessage;
	//
	//
	function initValidation()
	{
		//
		objThis.JQ("input[type=number], input.number").on("keypress", function(evt){
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			var input = evt.target;
			var inputVal = input.value;

			if (charCode == 8 || charCode == 37 || charCode == 38 || charCode == 39 || charCode == 40 || charCode == 32 || (inputVal.indexOf(".") == -1 && charCode == 46))
				return true;

			if (charCode > 31 && (charCode < 48 || charCode > 57))
			{
				evt.preventDefault();
				return false;
			}

			//
			return true;
		});
		//
		var inputs = objThis.selectorElt.find(objThis.options.inputSelector);
		//
		$.fn.validator.setDefaults({
		    trigger : objThis.options.triggerOn,
		    success : function(e)
		    {
//			    console.log(e.type, e.value, e.rule);
			    _removeInputMessageBox($(this));
		    },
		    error : function(e)
		    {
//			    console.log(e.type, e.value, e.rule);
			    _addInputMessageBox($(this), e.message);
		    }
		});
		//
		inputs.validator();
		//
	}

	function updateValidator()
	{
		objThis.selectorElt.validator('update');
	}

	function addCustomRule(selector, ruleName, message, callback)
	{
		ruleName = ruleName || ("customRule_" + selector);
		//
		$(selector).validator('addRule', ruleName, {
		    message : message || objThis.options.defaultInValideMsg,
		    validator : function(value)
		    {
			    if (typeof (callback) == "string")
				    return eval(callback + "(\"" + value + "\")");
			    else
				    return callback(value);
		    }
		});
		//
		$(selector).validator('update');
	}

	function addValidationMessage(inputSelector, message)
	{
	    _addInputMessageBox(objThis.JQ(inputSelector), message);
	}

	function removeValidationMessage(inputSelector)
	{
	    _removeInputMessageBox(objThis.JQ(inputSelector));
	}

	function validateElt(elt)
	{
		var isValid = false;
		if(elt) isValid = $(elt).validator('isValid');
		//
		return isValid;
	}

	function validate()
	{
		var inputs = objThis.selectorElt.find(objThis.options.inputSelector);
		//
		var isValid = true;
		for (var i = 0; i < inputs.length; i++) {
		    if($(inputs[i]).is(":visible")){
				if( ($(inputs[i]).hasClass("required") || $(inputs[i]).attr("required")) || $(inputs[i]).val() != "")
				{
					if($(inputs[i]).attr("type") == "number" || $(inputs[i]).hasClass("number"))
					{
						//
						isValid = !isNaN($(inputs[i]).val());
						if(!isValid) 
						{
							var inputType = $(inputs[i]).attr("type");
							$(inputs[i]).attr("type", "number");
							$(inputs[i]).validator('update');
							//
							$(inputs[i]).validator('isValid');
							//
							$(inputs[i]).attr("type", inputType);
				    		$(inputs[i]).validator('update');
							//
							break;
						}
					}
				    isValid = $(inputs[i]).validator('isValid');
				    //isValid = $(inputs[i]).valid();
				    //
				    //
				    if (!isValid) break;		    
				}
		    }
		}
		//
//		console.log("Validation for:[" + objThis.selector + "] Validation=[" + isValid + "]");
		return isValid;
	}
	//
	// PRIVATE METHODS
	//
	function _getInputMessageBox(jqInput, createIfNotExists)
	{
		var tagName = "div";
		var selector = "next";
		var appender = "after";
		switch (objThis.options.msgPosition)
		{
			case 'top':
				selector = "prev";
				appender = "before";
				tagName = "div";
			break;
			case 'bottom':
				selector = "next";
				appender = "after";
				tagName = "div";
			break;
			case 'left':
				appender = "before";
				selector = "prev";
				tagName = "span";
			break;
			case 'right':
				appender = "after";
				selector = "next";
				tagName = "span";
			break;
		}
		//
		var elt = jqInput.next("." + objThis.options.messageInputCls);
		if ((!elt || elt.length == 0) && createIfNotExists)
			eval("jqInput." + appender + "(\"<" + tagName + " class='" + objThis.options.messageInputCls + "'>\")");
		// jqInput.after("<" + tagName + " class='" + objThis.options.messageInputCls + "'>");
		//
		// elt = jqInput.next("." + objThis.options.messageInputCls);
		eval("elt = jqInput." + selector + "('." + objThis.options.messageInputCls + "')");
		//
		return elt;
	}

	function _addInputMessageBox(jqInput, content)
	{
		var elt = _getInputMessageBox(jqInput, true);
		//
		elt.html(content);
	}

	function _removeInputMessageBox(jqInput)
	{
		_getInputMessageBox(jqInput, false).remove();
	}

	function _onBeforeSubmit()
	{
		if (objThis.onBeforeSubmit) {
			if (typeof (objThis.onBeforeSubmit) == "string") {
				if (objThis.onBeforeSubmit.indexOf("(") < 0)
					objThis.onBeforeSubmit += "()";
				return eval("objThis.win." + objThis.onBeforeSubmit);
			} else
				return objThis.onBeforeSubmit();
		} else
			return objThis.validate();
	}

	function _initStyles()
	{
		objThis.ttUtilsInst.addStylsheet("stylesheet_tt_form_validation",
		        ".tt_validation_message{font-size: 12px; color: #db3a69; font-family: arial, tahoma; display: inline-block;}",
		        false, true);
		objThis.ttUtilsInst.addStylsheet("stylesheet_tt_form_validation",
		        ".tt_required_label{font-weight: bold;}",
		        true, false);
	}

	function _disableSubmitOnEnter()
	{
		objThis.selectorElt.on('keyup keypress', function(e)
		{
			var keyCode = e.keyCode || e.which;
			if (keyCode === 13) {
				e.preventDefault();
				return false;
			}
		});
	}

	function _initLabels()
	{
		var inputs = objThis.selectorElt.find(objThis.options.inputSelector);
		inputs.each(function(index, elt)
		{
			elt = $(elt);
			if (elt.attr("label") && elt.attr("required")) {
				objThis.selectorElt.find(elt.attr("label")).addClass("tt_required_label");
			}
		});
	}

	function _init()
	{
		objThis.options = objThis.ttUtilsInst.extendObject(objThis.options, objThis.defaultOptions);
		//
		if (objThis.selectorElt.prop("tagName").toLowerCase() == "form") {
			objThis.selectorElt.on("submit", function(event)
			{
				//event.preventDefault();
				return _onBeforeSubmit()
			});
		}
		//
		_initStyles();
		//
		objThis.initValidation();
		//
		_initLabels();
		//
		if (objThis.options.disableSubmitOnEnter)
			_disableSubmitOnEnter();
	}
	//
	_init();
}
