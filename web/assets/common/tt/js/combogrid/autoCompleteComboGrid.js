/**
* Author Fares Zgheib
* 
*/
function AutoCompleteComboGrid(id, fzOpts, oWin)
{
	var selector = "#" + id;
	//
	var objThis = this;
	this.name = "AutoCompleteComboGrid";
	this.isAutoCompleteComboGrid = true;
	//
	this.ownerWin = oWin || window;
	this.ownerDoc = this.ownerWin.document;
	this.JQ = this.ownerWin.$;
	this.autoCompleteActionType = "getAutoComplete";
	//
	objThis.typing = null;
	//
	this.idProperty = null;
	this.labelProperty = null;
	this.dbProperty = null;
	this.selector = selector;
	//this.inputParentId = null;
	//
	this.domId = id;
	this.inputIdPrefix = "id_";
	this.inputValuePrefix = "value_";
	this.inputId = this.inputIdPrefix + this.domId;
	this.inputLabel = this.domId;
	this.inputIdDom = null;
	this.inputLabelDom = null;
	//
	this.sendParamAsPost = true;
	//
	this.options = {};
	this.defOptions = {
			url : null,
			colModel: null,
			sidx: "",
			sord: "",
			type : "post",
			datatype : "json",
			appendTo: "body",
			autoFocus: true,
			autoChoose : false,
			addClass:null,
			addId:null,
			minLength: 0,
			draggable:true,
			rememberDrag: false,
			rowsArray: [5,10,20,30,50,100],
			resetButton: false,
			resetFields: null,
			searchButton: false,
			searchIcon:false,
			okIcon:false,
			alternate: true,
			delayChoose : 300,
			delay: 300,
			allowFlip: true,
			rows : 10,
			munit: "px",
			position: {
				my: "left top",
				at: "left bottom",
				collision: "none"
			},
			columnLabel: null,
			parameter: null,
			autoCompleteInst: objThis,
			debug : false,
			i18n: false,
			replaceNull: true,
			showOn: false,
			width:null,
			actions:
			{
				onAdd: null,
				onModify: null
			},
			open: function(inst){
				//After opening
			},
			select: function(event, ui) {
				var selPropId = ui.item ? _unescapeHTML(ui.item[objThis.idProperty]) : "";
				var selPropVal = ui.item ? _unescapeHTML(ui.item[objThis.labelProperty]) : "";
				//
				if(!selPropId && objThis.idProperty.indexOf(".")<0)
				{
					objThis.idProperty = objThis.JQ(objThis.selector).attr("idProperty");
				}else if(!selPropId && objThis.idProperty.indexOf(".")>0)
				{
					var prop = objThis.idProperty.split(".");
					prop = prop[prop.length-1];
					selPropId = ui.item ? _unescapeHTML(ui.item[prop]) : "";
				}
				if(!selPropVal && objThis.labelProperty.indexOf(".")>0)
				{
					var prop = objThis.labelProperty.split(".");
					prop = prop[prop.length-1];
					selPropVal = ui.item ? _unescapeHTML(ui.item[prop]) : "";
				}
				//
				var inputId = objThis.inputIdDom.get(0);
				var drpInpt = objThis.inputLabelDom.get(0);
				//
				//var valObj = {id:selPropId , value:selPropVal};
				//
				var isMultiValues = ("true"==objThis.inputLabelDom.attr("isMultiValues"));
				if (objThis.inputLabelDom.attr("mode") != "lookup") {
					if(isMultiValues)
					{
						objThis.inputLabelDom.val("");
					}else
					{
						if(null!=selPropId) {
							objThis.inputIdDom.val(selPropId);
						}

						objThis.inputLabelDom.val(selPropVal);

						objThis.setValue({
											id : selPropId,
											value : selPropVal
										}, false);
					}
					//
					//Dependencies check and update should be done here in case available
					//
				} else {
					if(isMultiValues)
					{
						//Set multi values from here if available
					}else
					{
						objThis.inputLabelDom.val(selPropVal);
						objThis.setValue({
											id : selPropId,
											value : selPropVal
										}, true);
						}
				}
				objThis.hideDropDownSearch();
				if(drpInpt.readOnly == false && drpInpt.disabled == false) 
				{
					objThis.JQ(drpInpt).focus();
					objThis.JQ(drpInpt).select();
				}
				//
				objThis._attachOnDDSelectEvent(drpInpt, selPropId, selPropVal, ui);
				//
				return false;
			}
	};
	
	objThis._attachOnDDSelectEvent=function(drpInpt, selPropId, selPropVal, ui)
	{
		if(!ui) ui = {};
		//
		var onDropDownSelect = objThis.JQ(drpInpt).attr("onDropDownSelect");

		objThis.JQ(drpInpt).attr("lastvalue", selPropId);
		if(onDropDownSelect && drpInpt.mode != "lookup") 
		{
			if(!selPropVal) selPropVal = "";
			onDropDownSelect = (onDropDownSelect.indexOf("(") > -1) ? onDropDownSelect : onDropDownSelect + "('" + selPropId + "','" + selPropVal.split("'").join("\\'") + "', drpInpt, ui.item)";
			eval("objThis.ownerWin." + onDropDownSelect);
		}
	}
	
	
	//
	function _prepareVars()
	{
		var selector = this.selector;
		//
		//var inputTdId = objThis.JQ(selector).attr("inputId");
		//objThis.inputParentId = objThis.JQ(objThis.ownerDoc.getElementById(inputTdId));
		//
		if(!objThis.idProperty) objThis.idProperty = "id";
		if(!objThis.labelProperty) objThis.labelProperty = "name";
	}
	function _extendOptions(opts)
	{
		for(var k in objThis.defOptions)
		{
			objThis.options[k] = objThis.defOptions[k];
		}
		if(opts)
		{
			for(var k in opts)
			{
				objThis.options[k] = opts[k];
			}
		}
		//
                console.log(objThis.options);
		return objThis.options;
	}
	//
	function _prepareInputs()
	{
		objThis.inputLabelDom = objThis.JQ(selector);
		//
		var inputId = objThis.inputId;
		var inputName = objThis.inputLabelDom.attr("name");
		objThis.inputLabelDom.attr("name", objThis.inputValuePrefix + inputName);
		objThis.inputLabelDom.attr("autocomplete", "off");

		var inputIdValue = (objThis.inputLabelDom.attr("data-value-id") ? objThis.inputLabelDom.attr("data-value-id") : "");
		if(!inputIdValue || inputIdValue == "")
			inputIdValue = (objThis.options.value ? objThis.options.value.id : "");

		objThis.inputLabelDom.after("<input id='"+inputId+"' name='"+inputName+"' type='hidden' value='"+ inputIdValue +"' />");
		//
		//
		objThis.inputIdDom = objThis.JQ("#" + objThis.inputId);
		//
		//
		//

		objThis.inputLabelDom.on("keydown", function( event ) {
			//
			var excludedClearKeys = [9,13,37,39];
			var closingKeys = [27];
			var excludedOpenKeys = [9,13,16,17,18,20,27,38,40,46];
			//
			if(excludedClearKeys.indexOf(event.keyCode) < 0)
			{
				objThis.inputIdDom.val("");
			}
			//
			if(excludedOpenKeys.indexOf(event.keyCode) < 0)
				objThis.openDropDownList(true);
			//
			if(closingKeys.indexOf(event.keyCode) > -1)
				objThis.hideDropDownSearch();
			//
		});
		objThis.inputLabelDom.on("blur",function( event ) {
			//if(!objThis.JQ(".cg-autocomplete.combogrid").is(":visible"))
			{
				if(objThis.inputIdDom.val()=="") objThis.inputLabelDom.val("");
				if(objThis.inputLabelDom.val()=="")
				{
					objThis.inputIdDom.val("");

					if(objThis.inputLabelDom.attr("lastvalue") && objThis.inputLabelDom.attr("lastvalue") != undefined && objThis.inputLabelDom.attr("lastvalue") != "") objThis._attachOnDDSelectEvent(objThis.inputLabelDom, "", "", null);
				}
			}
		});
		objThis.inputLabelDom.on("change", function( event ) {
			if(!objThis.JQ(".cg-autocomplete.combogrid").is(":visible"))
			{
				if(objThis.inputIdDom.val()=="") objThis.inputLabelDom.val("");
				if(objThis.inputLabelDom.val()=="") 
				{
					objThis.inputIdDom.val("");
					if(objThis.inputLabelDom.attr("lastvalue") && objThis.inputLabelDom.attr("lastvalue") != undefined && objThis.inputLabelDom.attr("lastvalue") != "") objThis._attachOnDDSelectEvent(objThis.inputLabelDom, "", "", null);
				}
			}
		});

	}

	//
	this.init=function(opts)
	{
		//
                console.log(opts);
		_prepareVars();
		_extendOptions(opts);
		//
		_prepareInputs();
		//
		if(objThis.options.parameter && typeof(objThis.options.parameter)=="string") objThis.options.parameter = objThis.options.parameter.split(",");
		//
		objThis.options.resetFields = ["#" + objThis.inputId, "#" + objThis.inputLabel];
		//
		//
		if(objThis.options['idProperty']) objThis.idProperty = objThis.options['idProperty'];
		if(objThis.options['labelProperty']) objThis.labelProperty = objThis.options['labelProperty'];
		//
		if(objThis.options['colModel'])
		{
			if( typeof(objThis.options['colModel']) == "string")
				eval("objThis.options['colModel'] = " + objThis.options['colModel']);
			//
			objThis.JQ(objThis.options['colModel']).each(function(index, item)
			{
				if(item.isIdProperty==true) objThis.idProperty = item.columnName;
				else if(item.isProperty==true) objThis.labelProperty = item.columnName;

				objThis.dbProperty = item.dbName;
			});
		}
		//
		try
		{
			objThis.JQ(objThis.selector).combogrid(objThis.options);
		}catch(e)
		{
			console.error("Error in Form ComboGrid.");
			console.error(e);
		}
	}

	//
	this.init(fzOpts);
	//
	//

	this.keyDownHander = function(ev, input)
	{
		if(ev)
		{
			var key = ev.keyCode;
			if(key == 27){}
		}
	}
	this.hideDropDownSearch=function()
	{
		objThis.inputLabelDom.combogrid("close");
		objThis.JQ(".cg-autocomplete.combogrid").hide().html("");
	}

	this.openDropDownList=function(includeCriteria)
	{
		objThis.ownerWin.clearTimeout( objThis.typing );

		objThis.typing = objThis.ownerWin.setTimeout(function() {
			_openDropDownList(includeCriteria);
		}, objThis.options.delay );
	}

	function _openDropDownList(includeCriteria)
	{
		var criteria = objThis.inputLabelDom.val();
		//
		if(!includeCriteria) objThis.inputLabelDom.val("");
		//
		//objThis.inputLabelDom.combogrid("search");
		objThis.inputLabelDom.trigger('focus.combogrid');
		objThis.inputLabelDom.trigger("search.combogrid");
		objThis.inputLabelDom.trigger('focus.combogrid');
		//
		if(!includeCriteria) objThis.inputLabelDom.val(criteria);
		//objThis.inputLabelDom.select();
		//
		//
	}
	//
	this.setValue=function(valueObj)
	{
		valueObj = valueObj || {id: "", value: ""};
		//
		objThis.inputIdDom.val(valueObj.id);
		objThis.inputLabelDom.val(valueObj.value);
	}
	//
	function _unescapeHTML(p_string)
	{
		if ((typeof p_string === "string") && (new RegExp(/&amp;|&lt;|&gt;|&quot;|&#39;/).test(p_string)))
		{
			return p_string.replace(/&amp;/g, "&").replace(/&lt/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, "\"").replace(/&#39;/g, "'");
		}
		return p_string;
	}
}
