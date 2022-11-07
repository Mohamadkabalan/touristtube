/**
 * Autocomplete component
 * 
 * If you use it with jQuery UI library it also has plugin named autocomplete. In this case you can use plugin alias
 * devbridgeAutocomplete: $('.autocomplete').devbridgeAutocomplete({ ... });
 * 
 * RESPONSE STRUCTURE: [ { "value": "United Arab Emirates", "id": "AE" }, { "value": "United Kingdom", "id": "UK" }, {
 * "value": "United States", "id": "US" } ]
 * 
 * @param _selector
 * @param _options
 * @param _win
 * @param _doc
 * 
 * @author Fares Zgheib
 * 
 */
function TTAutoComplete(_selector, _options, _win, _doc)
{
	var objThis = this;
	objThis.name = "TT_AUTOCOMPLETE";
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
	if (!objThis.JQ.fn.devbridgeAutocomplete)
		throw "AutoComplete JS library is missing";
	if (!objThis.ttUtilsInst)
		throw "TTUtils JS library is missing";
	//
	//
	objThis.getValue = _getValue;
	objThis.setValue = _setValue;
	objThis.disable = _disable;
	objThis.enable = _enable;
	objThis.destroy = _destroy;
	objThis.setOptions = _setOptions;
	objThis.hideSuggestions = _hideSuggestions;
	objThis.clearSuggestions = _clearSuggestions;
	objThis.clearCache = _clearCache;
	//
	//
	objThis.selector = _selector;
	objThis.selectorElt = null;
	objThis.events = {
	    onSearchStart : null,
	    onHint : null,
	    onSearchComplete : null,
	    onSearchError : null,
	    onHide : null,
	    onSelect : null,
	    transformResult : null,
	    beforeRender : null,
	    formatResult : null,
	    formatGroup : null,
	    onInvalidateSelection : null
	};
	objThis.source = null;
	objThis.ajaxSettings = {
	    type : "GET",
	    dataType : "json",
	    queryName : "q",
	    params : null,
	    context : objThis.doc.body,
	    async : true,
	    crossDomain : true
	};

	objThis.options = null;
	objThis.defaultOptions =
	        {
	            placeHolder : "Type to start the search",
	            dataInputSuffix : "Code",
	            dataInputJsonSuffix : "Json",
	            mapping : null,
	            deferRequestBy : 500,
	            minChars : 3, // Minimum number of characters required to trigger autosuggest
	            noCache : false, // Boolean value indicating whether to cache suggestion results
	            delimiter : null, // String or RegExp, that splits input value and takes last part to as query for
	            // suggestions. Useful when for example you need to fill list of comma separated
	            // values.
	            triggerSelectOnValidInput : true, // Boolean value indicating if select should be triggered if it
	            // matches suggestion
	            preventBadQueries : true, // Boolean value indicating if it should prevent future Ajax requests for
	            // queries with the same root if no results were returned. E.g. if Jam
	            // returns no suggestions, it will not fire for any future query that starts
	            // with Jam
	            autoSelectFirst : true, // If set to true, first item will be selected when showing suggestions

	            groupBy : null, // property name of the suggestion data object, by which results should be grouped
	            preserveInput : false, // If true, input value stays the same when navigating over suggestions
	            showNoSuggestionNotice : false, // When no matching results, display a notification label
	            noSuggestionNotice : "No results", // Text or htmlString or Element or jQuery object for no matching
	            // results label
	            maxHeight : 300, // Maximum height of the suggestions container in pixels
	            width : 'auto', // Suggestions container width in pixels, e.g.: 300, flex for max suggestion size and
	            // auto takes input field width
	            zIndex : 9999, // for suggestions container
	            appendTo : null, // Container where suggestions will be appended. Default value document.body. Can be
	            // jQuery object, selector or HTML element. Make sure to set position: absolute or
	            // position: relative for that element
	            forceFixPosition : false, // Suggestions are automatically positioned when their container is appended
	            // to body (look at appendTo option), in other cases suggestions are
	            // rendered but no positioning is applied. Set this option to force auto
	            // positioning in other cases
	            orientation : "bottom", // Vertical orientation of the displayed suggestions, available values are auto,
	            // top, bottom. If set to auto, the suggestions will be orientated it the way
	            // that place them closer to middle of the view port
	            tabDisabled : true, // Set to true to leave the cursor in the input field after the user tabs to select
	            // a suggestion

	            // called before displaying the suggestions. You may manipulate suggestions DOM before it is displayed
	            beforeRender : function(container, suggestions)
	            {
		            _hideLoader();
		            //
		            if (objThis.events.beforeRender)
			            return objThis.events.beforeRender(container, suggestions);
		            // else return suggestions;
	            },
	            // custom function to format suggestion entry inside suggestions container
	            formatResult : function(suggestion, currentValue)
	            {
		            if (objThis.events.formatResult)
			            return objThis.events.formatResult(suggestion, currentValue);
		            else {
			            var display = suggestion.value;
			            //
			            if (suggestion.icon)
				            display =
				                    "<span style='padding: 5px 5px 0px 0px;height: 20px;display: inline-block;overflow: hidden;'><img src='"
				                            + suggestion.icon
				                            + "' style='max-height: 100%; max-width: 100%;' /></span>" + display;
			            //
			            return display;
		            }
	            },
	            // custom function to format group header
	            formatGroup : function(suggestion, category)
	            {
		            return category;
	            },
	            // called when input is altered after selection has been made. this is bound to input element
	            onInvalidateSelection : function(container, suggestions)
	            {
		            if (objThis.events.onInvalidateSelection)
			            return objThis.events.onInvalidateSelection(container, suggestions);
	            },
	            // called before Ajax request. this is bound to input element
	            onSearchStart : function(params)
	            {
		            _showLoader();
		            //
		            if (objThis.events.onSearchStart)
			            return objThis.events.onSearchStart(params);
		            else
			            return true;
	            },
	            // used to change input value to first suggestion automatically
	            onHint : function(container)
	            {
		            if (objThis.events.onHint)
			            return objThis.events.onHint(container);
	            },
	            // called after Ajax response is processed. this is bound to input element. suggestions is an array
	            // containing the results
	            onSearchComplete : function(query, suggestions)
	            {
		            _hideLoader();
		            //
		            if (objThis.events.onSearchComplete)
			            return objThis.events.onSearchComplete(query, suggestions);
	            },
	            // called if Ajax request fails. this is bound to input element
	            onSearchError : function(query, jqXHR, textStatus, errorThrown)
	            {
		            _hideLoader();
		            //
		            if (objThis.events.onSearchError)
			            return objThis.events.onSearchError(query, jqXHR, textStatus, errorThrown);
	            },
	            // called before container will be hidden
	            onHide : function(container)
	            {
		            _hideLoader();
		            //
		            if (objThis.events.onHide)
			            return objThis.events.onHide(container);
	            },
	            onSelect : function(suggestion)
	            {
		            //
		            var skip = false;
		            if (objThis.events.onSelect)
			            skip = objThis.events.onSelect(suggestion);
		            if (!skip) {
			            objThis.JQ("#" + objThis.selectorElt.attr("ddcodeinputid")).val(suggestion.id);
			            objThis.JQ("#" + objThis.selectorElt.attr("ddcodeinputjson")).val(JSON.stringify(suggestion));
			            objThis.selectorElt.attr("selected", true);
		            } else {
			            objThis.selectorElt.val("");
			            objThis.JQ("#" + objThis.selectorElt.attr("ddcodeinputid")).val("");
			            objThis.JQ("#" + objThis.selectorElt.attr("ddcodeinputjson")).val("");
			            objThis.selectorElt.removeAttr("selected");
		            }
		            //
		            objThis.selectorElt.on("focus", function(ev)
		            {
			            ev.stopImmediatePropagation();
			            ev.preventDefault();
			            ev.cancelBubble = true;
		            });
		            objThis.selectorElt.focus();
		            // objThis.selectorElt.select();
		            objThis.selectorElt.off("focus");
		            // objThis.hideSuggestions();
		            //
		            _hideLoader();
	            },
	            // called after the result of the query is ready. Converts the result into response.suggestions format
	            transformResult : function(response, originalQuery)
	            {
		            if (objThis.events.transformResult)
			            return objThis.events.transformResult(response, originalQuery);
	            },
	            // FOR LOCAL DATA ONLY: Number of maximum results to display for local lookup
	            lookupLimit : null,
	            // lookupFilter: function (suggestion, query, queryLowerCase) { var rec = suggestion.data + " " +
	            // suggestion.value; if(rec.toLowerCase().indexOf(queryLowerCase)>-1) return true; }, //filter function
	            // for local lookups. By default it does partial string match (case insensitive)
	            lookup : function(query, done)
	            {
		            // Do Ajax call or lookup locally, when done,
		            // call the callback and pass your results:
		            var result = {
			            suggestions : []
		            };
		            //
		            _showLoader();
		            //
		            if (typeof (objThis.source) == "object") {
			            result.suggestions = objThis.source;
			            //
			            if (objThis.options.mapping) {
				            for ( var sug in result.suggestions) {
					            result.suggestions[sug] = _getMappedFields(result.suggestions[sug]);
				            }
			            }
			            //
			            done(result);
			            //
			            _hideLoader();
			            //
		            } else if (typeof (objThis.source) == "string") {
			            for ( var param in objThis.options.params) {
				            var paramVal = objThis.options.params[param];
				            if (paramVal == null || paramVal == undefined) {
					            objThis.options.params[param] = objThis.win[param];
				            } else if (typeof (paramVal) == "object" && paramVal['varname'])
					            objThis.options.params[param] = objThis.win[paramVal['varname']];
			            }
			            //
			            objThis.ajaxSettings.data = objThis.options.params;
			            if (!objThis.ajaxSettings.data)
				            objThis.ajaxSettings.data = {};
			            //
			            objThis.ajaxSettings.data[objThis.ajaxSettings.queryName] = query.trim();
			            objThis.ajaxSettings.beforeSend = function(jqXHR, settings)
			            {
				            if (objThis._jqXHR)
					            objThis._jqXHR.abort();
				            objThis._jqXHR = jqXHR;
				            //
				            _showLoader();
			            };
			            //
			            $.ajax(objThis.source, objThis.ajaxSettings).done(function(data, textStatus, jqXHR)
			            {
				            objThis._jqXHR = null;
				            //
				            if (jqXHR.status == 200 && data != null && data != "") {
					            if (objThis.options.mapping) {
						            for ( var sug in data) {
							            data[sug] = _getMappedFields(data[sug]);
						            }
					            }
					            //
					            result.suggestions = data;
					            //
					            done(result);
					            //
					            _hideLoader();
					            //
				            }
			            }).fail(
			                    function(jqXHR, textStatus)
			                    {
				                    //
				                    _hideLoader();
				                    //
				                    objThis._jqXHR = null;
				                    //
				                    var errMsg =
				                            "ERROR: autocomplete AJAX search [" + objThis.source + "]..textStatus["
				                                    + textStatus + "]";
				                    console.error(errMsg);
				                    console.error(jqXHR.responseText || jqXHR.responseJSON);
				                    console.log(jqXHR);
				                    //
				                    objThis.options.onSearchError(query, jqXHR, textStatus, errMsg);
			                    });
		            }
		            //
	            }
	        };
	//
	function _disable()
	{
		objThis.selectorElt.autocomplete().disable();
		objThis.selectorElt.prop('disabled', true);
	}
	function _enable()
	{
		objThis.selectorElt.autocomplete().enable();
		objThis.selectorElt.prop('disabled', false);
		objThis.selectorElt.removeProp('disabled');
	}
	function _destroy()
	{
		objThis.selectorElt.autocomplete().dispose();
	}
	function _setOptions(opt)
	{
		_extendsOptions(opt);
		objThis.selectorElt.autocomplete().setOptions(objThis.options);
	}
	function _hideSuggestions()
	{
		objThis.selectorElt.autocomplete().hide();
	}
	function _clearSuggestions()
	{
		objThis.selectorElt.autocomplete().clear();
	}
	function _clearCache()
	{
		objThis.selectorElt.autocomplete().clearCache();
	}

	//
	function _getValue()
	{
		var result = {};
		//
		result.value = objThis.JQ("#" + objThis.selectorElt.attr("ddcodeinputid")).val();
		result.text = objThis.selectorElt.val();
		if (objThis.JQ("#" + objThis.selectorElt.attr("ddcodeinputjson")).val())
			result.data = JSON.parse(objThis.JQ("#" + objThis.selectorElt.attr("ddcodeinputjson")).val());
		//
		return result;
	}
	//
	function _setValue(obj)
	{
		objThis.JQ("#" + objThis.selectorElt.attr("ddcodeinputid")).val(obj.value);
		objThis.selectorElt.val(obj.text);
		if (obj.data && typeof (obj.data) == "object")
			obj.data = JSON.stringify(obj.data);
		else
			obj.data = "";
		//
		objThis.JQ("#" + objThis.selectorElt.attr("ddcodeinputjson")).val(obj.data);
	}
	//
	function _onBlur(ev)
	{
		_hideLoader();
		//
		if (!objThis.selectorElt.attr("selected") || objThis.selectorElt.attr("selected") == "false") {
			_setValue({
			    text : "",
			    value : "",
			    data : ""
			});
		}
	}
	//
	function _onKeydown(ev)
	{
		var skipKeys = [ 13, 9, 27, 39, 37, 38, 40 ];
		if (objThis.JQ.inArray(ev.keyCode, skipKeys) < 0) {
			objThis.selectorElt.removeAttr("selected");
			// _showLoader();
		}
	}
	//
	//
	function _appendStyleSheet()
	{
		var ddStyleSheetId = "ss_tt_autocomplete";
		var css = ".tt_dd {padding: 5px 25px 5px 15px; }";
		objThis.ttUtilsInst.addStylsheet(ddStyleSheetId, css, false, false);
		//
		css =
		        ".tt_dd.loader {"
		                + " background-image: url(data:image/gif;base64,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);"
		                + "	background-repeat: no-repeat;" 
		                + " background-position: right center;"
		                + " background-size: auto 2em;}";
		objThis.ttUtilsInst.addStylsheet(ddStyleSheetId + "_loader", css, false, false);
		//
		css =
		        ".tt_dd.search {"
		                + " background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAYAAADDPmHLAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAC0hJREFUeJztnX+MXFUVx885Myy71AGxs6TxH00ECjZAlEKJRQpGykIJKMqvxhBrwBUy771dQYyRdrotYpZl3XnzmiWLon8YaC3KDwN0WgWKxSgrVAWRFv7BmGBpyS6hWOl03jn+sW+aNy8z+6v73r1vuZ//7nl39nzfvO/e9+veMwAGg8FgMBgMBoPBYDAYDAaDwWAwGAwGg8FgMBgMBoPBYDAY5gOoWsBcUCwWqbOz80wROV9Ezmbm04joUwDQycwnEVEbAAAzVwHgPQA4gIj/AoA3EfEVERkdGxvb09fXxyr3QwWpNUB/f3+uo6PjShG5GhEvBYBPHOOfHAOAHcz8RDabfbJQKHwwBzK1J3UGGBoaWkZEtyHitQDQEUcOZj4EAFuJ6H7btkfjyKELqTCAiOCmTZtWisg6APhCkrmZeRcRbbAs6xlElCRzJ4H2BiiXy+cAwBAAXDJZP2Y+REQviMhLiLhXRN5g5n3ZbPbgu+++exAAIJ/P52q1Wo6IFonIYiJaLCJLEfFCmGI0YebfA0BvT0/PP+Zq33RAWwMMDg52ZLPZ9Yh4BwBQi27vAMBDzPzY+Pj4aF9fX3U2uYrFYtvChQuXAcA1zLyaiE5p1o+ZfSK6N5fLbVizZs2Hs8mlG1oawPO8s5h5MyIuadHlOWa+b3x8fEdfX19tLnMXi8VsPp/vEpE7AGBFsz7M/Go2m72xUCi8Npe5VaCdATzPWy0iP4PmQ/IziLjWsqw/JaGlVCpdiIgbEfHi6DZmPoSIaxzH2ZqElrjQxgAiguVyeS0i9kW3MfPbmUymp1Ao/DrpCzERQc/zbmDmnxDRoiZdfmhZ1o/TeoGohQFEBF3XLRGR3WTzFhHpdhzn/cSFhRgaGvo4ET0Q3H42ICJDtm3fnkYTKDfAJAf/sIjYtm3/VJcvNhgNbmXmEhEdF9mWShMoN4DruuuaDPvvi8hVjuM8r0TUFJTL5S8BwOMAkAvHReQux3F+pEbV7FBqgOCC76FwjJn3i8hlvb29f1Olazp4nvd5EakAQGc4jojXWZb1iCJZM0aZATzPO0tEXoTGq/33AeAi27b/rkjWjAhMsBMaR4L/isj5juP8U5GsGdHqAUusDA4OdjDzZmg8+IdF5Kq0HHwAAMuydgPAVwAg/ABqgYg87Lru8YpkzQglBgie8DU85BERW9dz/mTYtv0sAPSGY0R0jojcpUjSjEj8FBA8298NjebbYlnW6rRdQdcJ7g62AsDXQ+GaiJyj+6kg0RFARBAmXuwczcvMb4tId1oPPgAAIorv+7cw875QOIuIQ8pETZNMksny+fxlALA2Er7ZcZzdSeqIg+3bt3+4atWq/wDA10Lhz3R1df2hUqm8pUjWlCQ9AqyLhJ6xbTs1t0xTUSgUNgNAw3UMIhYVyZkWiRlgaGhoGUQmcyDi2jQP/VGCfYmOcCtKpdJ5KvRMh8QMQES3RULPJfVWL0ls297FzLvCMSL6jio9U5GIAfr7+3PRlyjMfF8SuVWAiAOR0PUDAwMLlIiZgkQM0NHRcSU0PvR5Z3x8fEcSuVXQ3t5eAYADodCCtra2Var0TEYiBhCRqyOhh+Z6Jo9OdHd3HxGRh8MxRLxKlZ7JiN0AxWKRRGRlOMbMj8WdVzXM/GgktDJ4DqIVsRugs7PzTCI6ud5m5kPj4+Pzeq49AAARvcjM4YmjnaVS6XRlgloQuwFE5PyGhEQvzHb2bppwHOcwALwQjmUymWWK5LQkCQOcHWm/FHdOXSCil8Pt6HehA7EbgJlPC7cRcW/cOXUhuq+IeKoqLa2I3QDBKt2jiMgbcefUBWbeG2l/WpGUliRxG9gwZSryxmxe4/t+dF87m3ZUSBKngJPC7Ww2ezDunLqQyWQa9jV8N6QLSZwC2sLt+kLNjwjRtQzaTRNTMiXMoA9JnAIa7vnz+XyuVd95yImR9mElKiYhiRHgvXCjVqt9ZAzg+37DvjLzuCotrUjCAOG3YtBigeW8JJPJRPf1QNOOCondAEE1rqOIyOK4c+oCES2OtN9SJKUlSYwAbzYkjHwp8xlmPiMSerNpR4UkMQK8Em6LyNK4c+oCIp4bCb2qRMgkJPEyaDTSXl4sFtta9Z8vuK57PDMvD8cQ8c+q9LQidgOMjY3tgYkijBMJiU4ICjLNdy4govZ6g5n3FwqFj94pICi/Gp3/d03ceVWDiOEFIkBE23WcAp/Ik0BmfiLSXl0sFrNJ5FbByMjIcQBwYzgmIr9VJGdSEjFANpt9Mii/OpGU6JR8Pt+VRG4VVKvVywEgX28z8wft7e1PK5TUkkQMEBRebiinFtThm3eICPq+f2c4RkRburu7D7X6jEqSXBl0fyS0olwuL2/aOcWUy+WLiCh69R/dd21IzAC2bY9Gl0yJyN06TpWeLSKCIrIxHGPmZ4NKIlqS6OtgItoQbiPixZ7n3ZCkhjgpl8vfIKIvhmNEtF6RnGmR6H9fUBNwBxF9uR5j5n0icmZvb+97k31Wd4aHh0+uVqt7IoWmn7ZtW8slYXUSHQGC++BeZvaPCiBaREQPpPlUICJYq9UeDB98Zj7i+/53VeqaDolWCAEAqFQq+6+44op2ADg6VCLiktHR0f3btm37S9J65oKFCxfaECkUBQB39/T0/EaFnpmgZEpYLpfbwMwNL0aYuRRU4EwVnuddGl3qLiK7x8bG7lGlaSYoG3Y3bdq0pFarjRLRCaHwQRFZ4TjOX1Xpmgme5y31ff85IvpYKHwQEZdalpWK9Q/KJoUWCoXXEHFNJJxDxO2u635OiagZ4HneUhHZFjn4AAA3peXgAyieFew4ztYmBRU7EfF513Un/Y0glXied2lQIjYf2XSnbduPK5A0a5RPC7dt+x4RidbTyyFixXXdW3W6Owh+1ML2ff8pAFgQ2XavZVmpK3ujxZcbfLGDiBi9kgYReYSZv636OcHw8PDJtVrtQQD4aosuG2zb1rokXDOUjwAAE88HbNu+vVl9XUS8FhFfD0rLJ27YwJw3VavVPdD64AMArHNdd31CsuYMLUaAMK7rXoeIP4fIEBvwPACstW17V5Ntc0pw4C8SkY3Rx7tTfK7PcZz1MUqbU7QzAACA67qfRcTNANC0oAIz70LEgfb29kp3d/eRucw9MjJyXLVavVxEvgcAF7bI/zYRfbLV30iTCbQ0AMDEpEpEXAsA3weAVrOHDojIw8z8KBG9GJRlmVUuALggmMZ1A7RYxs3MRwCgHxHvBoAfTFYGNi0m0NYAdYLRYAgAVk7R9X/M/EciehkR9zLzXt/39wVLtOurdE/0fT+XyWQWEdFiZj4DEc9l5uXhCZzNYOZtInJ7b2/v6yFt69NuAu0NUMd13UuCL7vpr3nGBTM/m8lkNlqWtbOFrlSbIDUGqFMqlc4Lau9eD80vFI8ZZv4AEX9FRMPTmcyRZhOkzgB1BgYGFrS1ta0KKnCuhGMsv8LM+4nod4j4RFtb21MzncOXVhOk1gBhRARLpdLpmUxmmYicjYinBgWZOoOyLPXKHIdhYrn6OwDwbxF5I1i6NmpZ1t5jnbefRhPMCwPoRNpMkPiEkPlOpVLZ2dXVhc1+cRxgYh5kV1cXViqVnckqa44xQAykyQTGADGRFhMYA8RIGkxgDBAzupvAGCABdDaBMUBC6GoCY4AE0dEExgAJo5sJjAEUoJMJjAEUoYsJjAEUooMJjAEUo9oE5m2gJkz1FhERv2VZ1i/mOq8W6wIMAI7jrBeRvlbbReTmOPIaA2jEZCZg5liKTBoDaEYrEyDiSBz5zDWAppTL5W8CwC3MLIg44jjOL1VrMhgMBoPBYDAYDAaDwWAwGAwGg8FgMBgMBoPBYDAYDAaDwWAwaMr/AXQt5rdaKg3EAAAAAElFTkSuQmCC');"
		                + "	background-repeat: no-repeat;" 
		                + " background-position: right center;"
		                + " background-size: auto 2em;}";
		objThis.ttUtilsInst.addStylsheet(ddStyleSheetId + "_search", css, false, false);
	}

	function _showLoader()
	{
		if (objThis.selectorElt) {
			objThis.selectorElt.removeClass("search");
			objThis.selectorElt.addClass("loader");
		}
	}

	function _hideLoader()
	{
		if (objThis.selectorElt) {
			objThis.selectorElt.removeClass("loader");
			objThis.selectorElt.addClass("search");
		}
	}

	function _getMappedFields(suggestion)
	{
		if (objThis.options.mapping) {
			if (objThis.options.mapping.value)
				suggestion.value = suggestion[objThis.options.mapping.value];
			if (objThis.options.mapping.id)
				suggestion.id = suggestion[objThis.options.mapping.id];
		}
		//
		return suggestion;
	}

	function _extendsOptions(opts)
	{
		if (null != opts) {
			if (opts.events) {
				objThis.events = opts.events;
				delete opts.events;
			}
			if (opts.source) {
				objThis.source = opts.source;
				delete opts.source;
			}
			if (opts.ajaxSettings) {
				objThis.ajaxSettings = opts.ajaxSettings;
				objThis.options.deferRequestBy = opts.ajaxSettings.delay || objThis.defaultOptions.deferRequestBy;
				delete opts.ajaxSettings;
			}
			//
			for ( var opt in opts) {
				if (null != opts[opt] && undefined != opts[opt])
					objThis.options[opt] = opts[opt];
			}
		}
		//
		return objThis.options;
	}
	function _init()
	{
		objThis.options = objThis.defaultOptions;
		objThis.selectorElt = objThis.JQ(objThis.selector);
		//
		objThis.selectorElt.addClass("tt_dd search");
		objThis.selectorElt.blur(_onBlur);
		objThis.selectorElt.keydown(_onKeydown);
		//
		_extendsOptions(_options);
		//
		if (!objThis.selectorElt.attr("placeholder") && objThis.options.placeHolder)
			objThis.selectorElt.attr("placeholder", objThis.options.placeHolder);
		//
		_appendStyleSheet();
		//
		var inputId = objThis.selectorElt.attr('id');
		var inputName = (objThis.selectorElt.attr('name') || inputId);
		inputId = (inputId || inputName);
		if (objThis.JQ("#" + (inputId + objThis.options.dataInputSuffix)).length <= 0)
			objThis.selectorElt.after("<input type='hidden' name='" + inputName + objThis.options.dataInputSuffix
			        + "' id='" + inputId + objThis.options.dataInputSuffix + "'></input>");
		objThis.selectorElt.attr("ddcodeinputid", inputId + objThis.options.dataInputSuffix);
		//
		if (objThis.JQ("#" + (inputId + objThis.options.dataInputJsonSuffix)).length <= 0)
			objThis.selectorElt.after("<input type='hidden' name='" + inputName + objThis.options.dataInputJsonSuffix
			        + "' id='" + inputId + objThis.options.dataInputJsonSuffix + "'></input>");
		objThis.selectorElt.attr("ddcodeinputjson", inputId + objThis.options.dataInputJsonSuffix);
		//
		//
		if (null != objThis.getValue() && "" != objThis.getValue())
			objThis.selectorElt.attr("selected", "");
		//
		objThis.selectorElt.devbridgeAutocomplete(objThis.options); // .autocomplete(...);
		//
	}
	//
	//
	//
	_init();
}
