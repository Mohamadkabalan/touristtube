/**
 * Utils component
 * 
 * 
 * @param _win
 * @param _doc
 * 
 * @author Fares Zgheib
 * 
 */
function TTUtils(_win, _doc)
{
	var objThis = this;
	objThis.name = "TT_UTILS";
	objThis.win = _win ? _win : self;
	objThis.doc = _doc ? _doc : objThis.win.document;
	objThis.JQ = objThis.win.$;
	//
	//
	objThis.addStylsheetRule = _addStylsheetRule;
	objThis.extendObject = _extendObject;
	objThis.generateUniqueId = _generateUniqueId;
	objThis.getStylsheet = _getStylsheet;
	objThis.addStylsheet = _addStylsheet;
	objThis.removeStylsheet = _removeStylsheet;
	objThis.formToJsonObject = _formToJsonObject;
	objThis.formToJson = _formToJson;
	objThis.jsonToForm = _jsonToForm;
	objThis.setCookies = _setCookies;
	objThis.getCookies = _getCookies;
	objThis.deleteCookies = _deleteCookies;
	objThis.leftPad = _leftPad;
	objThis.template = _template;
    objThis.convertMinsToHrsMins = _convertMinsToHrsMins;
    objThis.getHostName = _getHostName;
	objThis.isExternalHost = _isExternalHost;
	objThis.truncateString = _truncateString;
	//
	//
	objThis.TT_COOKIES_PREFIX = "TT-";
	//
	
	/**
	 * Used to extend and override properties from a given object (could be used to clone objects)
	 * 
	 * @param source:
	 *            Source Object to get properties from
	 * @param destination:
	 *            The final object to be returned after extending the source object param
	 * 
	 */
	function _extendObject(source, extendFrom, recursive)
	{
		recursive = (recursive != false);
		source = source || {};
		//
		if (typeof (source) != "object")
			throw "Source param should be of type object";
		//
		if (null != source) {
			//
			for ( var elt in extendFrom) {
				if ((null == source[elt] || undefined == source[elt])) {
						source[elt] = extendFrom[elt];
				} else if (recursive && typeof (source[elt]) == "object")
					source[elt] = _extendObject(source[elt], extendFrom[elt]);
			}
		}
		//
		return source;
	}

	/**
	 * Get a style tag with a given ID
	 * 
	 * @param id
	 * 
	 */
	function _getStylsheet(id)
	{
		var style = null;
		if (id && "" != id)
			style = document.querySelector("style#" + id) || document.getElementById(id);
		//
		return style;
	}

	/**
	 * Allow to delete a given stylesheet tag from header by given ID
	 * 
	 * @param id
	 * 
	 */
	function _removeStylsheet(id)
	{
		var style = _getStylsheet(id);
		if (style)
			style.remove();
	}

	/**
	 * Allow to add stylesheet tag to header by given ID If the given ID already exists it will add the css rules to the
	 * existing tag
	 * 
	 * @param id
	 * @param css
	 * 
	 */
	function _addStylsheet(id, css, appendIfExists, replaceIfExists)
	{
		if (appendIfExists != true)
			appendIfExists = false;
		if (replaceIfExists != true)
			replaceIfExists = false;
		//
		if (replaceIfExists) {
			_removeStylsheet(id);
		}
		//
		var style = document.getElementById(id);
		var head = null;
		//
		if (null == style) {
			style = document.createElement('style');
			style.id = id;
			style.type = 'text/css';
			//
			head = document.head || document.getElementsByTagName('head')[0];
			//
		} else if (!appendIfExists && !replaceIfExists)
			return false;
		//
		//
		if (style.styleSheet) {
			style.styleSheet.cssText += css;
		} else {
			style.appendChild(document.createTextNode(css));
		}
		//
		if (null != head)
			head.appendChild(style);
		//
		return true;
	}

	/**
	 * Allow to add styling rules to the given document page (by default current loaded page)
	 * 
	 * @param selector:
	 *            a normal stylesheet selector class, it could be a list of selectors or simple string
	 * @param rules:
	 *            the class rules to be assigned for the given class names
	 * @param context:
	 *            The default context where to add given style rules
	 * 
	 */
	function _addStylsheetRule(selector, rules, contxt)
	{
		var context = contxt || document, stylesheet;

		if (typeof (context.styleSheets) == 'object') {
			if (context.styleSheets.length) {
				stylesheet = context.styleSheets[context.styleSheets.length - 1];
			}
			if (context.styleSheets.length) {
				if (context.createStyleSheet) {
					stylesheet = context.createStyleSheet();
				} else {
					context.getElementsByTagName('head')[0].appendChild(context.createElement('style'));
					stylesheet = context.styleSheets[context.styleSheets.length - 1];
				}
			}
			if (stylesheet.addRule) {
				for (var i = 0; i < selector.length; ++i) {
					stylesheet.addRule(selector[i], rules);
				}
			} else {
				if (typeof (selector) == "array")
					selector = selector.join(',');
				//
				stylesheet.insertRule(selector + '{' + rules + '}', stylesheet.cssRules.length);
			}
		}
	}

	function _generateUniqueId()
	{
		return new Date().getTime() + "_" + Math.round(Math.random() * 1000);
	}

	/**
	 * This method will convert and serialize Form HTML elements to JSON Objecy
	 * @param formSelector JQuery selector 
	 * 
	 * return JS array with each element of the given form as an object 
	*/
	function _formToJsonObject(formSelector)
	{
	    var form = (typeof(formSelector)=='object') ? formSelector : objThis.JQ(formSelector);
	    //
	    var o = {};
	    var a = form.serializeArray();
	    $.each(a, function () {
		var value = isNaN(this.value) ? this.value : parseFloat(this.value);

		if (o[this.name]) {
		    if (!o[this.name].push) {
			o[this.name] = [o[this.name]];
		    }
		    o[this.name].push(value || '');
		} else {
		    o[this.name] = value || '';
		}
	    });
	    return o;
	}

	/**
	 * This method will convert and serialize Form HTML elements to JSON Array of Objects
	 * @param formSelector JQuery selector 
	 * 
	 * return JS array with each element of the given form as an object 
	*/
	function _formToJson(formSelector)
	{
	 var jsonForm=[];
	 objThis.JQ("input[type!=submit][type!=button],textarea,select", objThis.JQ(formSelector)).each(function(index){
	   if(objThis.JQ(this).data("serializable")!="false")
	    {
	     var obj = {
	           id: objThis.JQ(this).attr("id"),
	           name: objThis.JQ(this).attr("name"),
	           value: this.value
	      };

        var attr = $(this).attr('checked');

        // For some browsers, `attr` is undefined; for others,
        // `attr` is false.  Check for both.
        if (typeof attr !== typeof undefined && attr !== false) {
          obj["checked"] = "checked";
        }

	      jsonForm.push(obj);
	    }
	 })
	 return jsonForm;
	}

	/**
	 * Used to auto fill form elements values as per given JSON array
	 * 
	 * @param jsArray JS array of objects usually created from formToJson Exp: [{id:, name:, value:}, . . .]
	 * 
	*/
	function _jsonToForm(jsArray)
	{
		if(jsArray){
			for(var elt in jsArray){
				elt = jsArray[elt];
				if(elt){
					if( objThis.JQ("#" + elt.id).length > 0) {
						if (elt.checked !== undefined) {
							objThis.JQ("#" + elt.id).attr('checked','checked').val(elt.value);
						}
						else {
							objThis.JQ("#" + elt.id).val(elt.value);
						}
					}
					else if(elt.name.indexOf('[]') == -1 && objThis.JQ("input[name=" + elt.name + "]").length > 0) {
						// ES6:: !elt.name.includes('[]') // includes is the fastest
						objThis.JQ("input[name=" + elt.name + "]").val(elt.value);
					}
				}
			}
		}
	}

	/**
	* Used to add cookies
	* 
	* @param key      The cookies key
	* @value              The cookie value of type string
	* @options           JS object for the cookies settings {expires: null, path: "/", secure: false, domain: null}
	*
	*/
	function _setCookies(key, value, options)
	{
		options = options || {};
		//
		key = objThis.TT_COOKIES_PREFIX + key;
		_deleteCookies(key);
		objThis.JQ.cookie(key, value, {
		   expires : options.expire,           // Expires in XXX days
	
		   path :  options.path || '/',          // The value of the path attribute of the cookie
		                                                      // (Default: path of page that created the cookie).
	
		   domain  : options.domain,   // The value of the domain attribute of the cookie
		                                                    // (Default: domain of page that created the cookie).
	
		   secure  : (options.secure == true)         // If set to true the secure attribute of the cookie
		                                                                  // will be set and the cookie transmission will
		                                                                 // require a secure protocol (defaults to false).
		});
	}


	/**
	* Used to get cookies values
	* 
	* @param key      The cookies key
	* @options           JS object for the cookies settings {path: "/", domain: null}
	*
	* return String
	* 
	*/
	function _getCookies(key, options)
	{
		options = options || {};

		return objThis.JQ.cookie(objThis.TT_COOKIES_PREFIX + key);
/*
		return objThis.JQ.cookie(objThis.TT_COOKIES_PREFIX + key, {
		   path :  options.path || '/',       // The value of the path attribute of the cookie
                                              // (Default: path of page that created the cookie).
	
		   domain  : options.domain,   	// The value of the domain attribute of the cookie
		   								// (Default: domain of page that created the cookie).
		});
*/
	}


	/**
	* Used to remove cookies by given key
	* 
	* @param key      The cookies key
	* 
	*/
	function _deleteCookies(key)
	{
		objThis.JQ.removeCookie(objThis.TT_COOKIES_PREFIX + key);
	}

	/**
	 * Add leading prefix to a given string respecting the given size limit 
	 * 
	 */
	function _leftPad(str, size, prefix) {
		if(size==null) size = 0;
		if(prefix=="") prefix = null;
		//
	    return ('' + str).padStart(size, prefix);
	}

	/**
	 * This method used to replace variables defined by #{var_name} in a given string or template
	 * 
	 * @param string containing the template to replace the variables inside  #{variable_name}
	 * @param object with the key value to be replaced in the given template  { key: value, key: value . . .} 
	 *
	 */
	function _template(str, obj) {
	    do {
	        var beforeReplace = str;
	        str = str.replace(/#{([^}]+)}/g, function(wholeMatch, key) {
	            var substitution = obj[$.trim(key)];
	            return (substitution === undefined ? wholeMatch : substitution);
	        });
	        var afterReplace = str !== beforeReplace;
	    } while (afterReplace);

	    return str;
	}

    /**
     * This method converts number of minutes into Hours and Minutes
     *
     * @param integer minutes
     *
     */
    function _convertMinsToHrsMins(minutes) {
        var h = Math.floor(minutes / 60);
        var m = minutes % 60;
        var r = '';
        if(h > 0){
            r += _leftPad(h, 2, "0") + 'h ';
        }
        if(m > 0){
            m = _leftPad(m, 2, "0"); // < 10 ? '0' + m : m;
            r += m + 'm';
        }else{
            r += _leftPad(0, 2, "0") + 'm';
        }
        return r;
    }

	/**
	 * This method return the host name (including sub domains) of a given URL
	 * If the URL param is not given, the current web host name will be returned 
	 * 
	 *  @param url
	 *  
	 */
	function _getHostName(url) {
		url = url || location;
	    var match = url.match(/:\/\/(www[0-9]?\.)?(.[^/:]+)/i);
	    if (match != null && match.length > 2 && typeof match[2] === 'string' && match[2].length > 0) {
	    	return match[2];
	    }
	    else {
	        return null;
	    }
	}

	/**
	 * Return true if the given URL is within the same web application host name
	 * 
	 * @param url
	 * 
	 */
	function _isExternalHost(url) {
	    var match = url.match(/^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/);
	    if (match != null && typeof match[1] === 'string' && match[1].length > 0 && match[1].toLowerCase() !== location.protocol)
	        return true;
	    if (match != null && typeof match[2] === 'string' && match[2].length > 0 && match[2].replace(new RegExp(':('+{'http:':80,'https:':443}[location.protocol]+')?$'),'')!== location.host) 
	    {
	        return true;
	    }
	    else {
	        return false;
	    }
	}

	/**
	 * This method will truncate a given text on the first space before the given text
	 * If the truncated text doesn't have space and will be greater than the given length we will force truncate the text if the given    forceTrimWord   params is set to true
	 * 
	 * str: original text to truncate
	 * len: max length to be returned
	 * append: text to be appended in case of text was truncated 
	 * forceTrimWord: Allow to truncate the text in a middle of a word in case no spaces available within the given length
	 *  
	 */
	function _truncateString(str, len, append, forceTrimWord)
	{
		forceTrimWord = (forceTrimWord!=false);
	   	var newLength;
	   	append = append || "";  //Optional: append a string to str after truncating. Defaults to an empty string if no value is given

	   	if (!forceTrimWord && str.indexOf(' ') > len)
	   	{
			return str;   //if the first word + the appended text is too long, the function returns the original String
	   	}

	   	str.length > len ? newLength = len : newLength = str.length; // if the length of original string and the appended string is greater than the max length, we need to truncate, otherwise, use the original string

		var tempString = str.substring(0, newLength);  //cut the string at the new length
		tempString = tempString.replace(/\s+\S*$/, ""); //find the last space that appears before the substringed text

	   	if (tempString != str && append.length > 0)
		{
			tempString = tempString + append;
		}

	   	return tempString;
	};
}
//
$(document).ready(function()
{
	if (!window.ttUtilsInst)
		window.ttUtilsInst = new TTUtils(window, document);
});
//
