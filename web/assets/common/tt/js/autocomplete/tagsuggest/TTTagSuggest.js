/**
 * Autocomplete Tag component
 * 
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
function TTTagSuggest(_selector, _options, _win, _doc)
{
	var objThis = this;
	objThis.name = "TT_AUTOCOMPLETE_TAG";
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
	if (!objThis.JQ.fn.tagSuggest)
		throw "AutoComplete JS library is missing";
	if (!objThis.ttUtilsInst)
		throw "TTUtils JS library is missing";
	//
	//
	objThis.selector = _selector;
	objThis.selectorElt = null;

	//
	// PUBLIC METHODS
	//
	objThis.addToSelection = addToSelection;
	objThis.clear = clear;
	objThis.collapse = collapse;
	objThis.disable = disable;
	objThis.empty = empty;
	objThis.enable = enable;
	objThis.expand = expand;
	objThis.isDisabled = isDisabled;
	objThis.isValid = isValid;
	objThis.getDataUrlParams = getDataUrlParams;
	objThis.getName = getName;
	objThis.getSelectedItems = getSelectedItems;
	objThis.getRawValue = getRawValue;
	objThis.getValue = getValue;
	objThis.removeFromSelection = removeFromSelection;
	objThis.setData = setData;
	objThis.setName = setName;
	objThis.setValue = setValue;
	objThis.setDataUrlParams = setDataUrlParams;
	//
	//
	objThis.options = _options;
	objThis.defaultOptions = {

		/**
		 * @options {function} value
		 *          <p>
		 *          JS method called on Item selected
		 *          If true is returned, the value will be not selected
		 *           
		 *          </p>
		 *          Defaults to <code>null</code>.
		 */
	    onSelectItem : null,

	    /**
	     * @options {function} value
	     *          <p>
	     *          JS method called on render combo box
	     *          </p>
	     *          Defaults to <code>null</code>.
	     */
	    onRenderComboItems : null,

	    /**
		 * @options {String/Object/Array} value
		 *          <p>
		 *          initial value for the field
		 *          </p>
		 *          Defaults to <code>null</code>.
		 */
	    value : null,

	    /**
		 * @options {String} valueField
		 *          <p>
		 *          name of JSON object property that represents its underlying value
		 *          </p>
		 *          Defaults to <code>id</code>.
		 */
	    valueField : 'id',

	    /**
		 * @options {String} name
		 *          <p>
		 *          The name used as a form element.
		 *          </p>
		 *          Defaults to 'null'
		 */
	    name : null,

	    /**
		 * @cfg {Boolean} allowFreeEntries
		 *      <p>
		 *      Restricts or allows the user to validate typed entries.
		 *      </p>
		 *      Defaults to <code>true</code>.
		 */
	    allowFreeEntries : false,

	    /**
		 * @options {String} displayField
		 *          <p>
		 *          name of JSON object property displayed in the combo list
		 *          </p>
		 *          Defaults to <code>name</code>.
		 */
	    displayField : 'value',

	    /**
		 * @options {Boolean} disabled
		 *          <p>
		 *          Start the component in a disabled state.
		 *          </p>
		 *          Defaults to <code>false</code>.
		 */
	    disabled : false,

	    /**
		 * @options {Boolean} editable
		 *          <p>
		 *          Set to false if you only want mouse interaction. In that case the combo will automatically expand on
		 *          focus.
		 *          </p>
		 *          Defaults to <code>true</code>.
		 */
	    editable : true,

	    /**
		 * @options {String} groupBy
		 *          <p>
		 *          JSON property by which the list should be grouped
		 *          </p>
		 *          Defaults to null
		 */
	    groupBy : null,

	    /**
		 * @options {Integer} maxDropHeight (in px)
		 *          <p>
		 *          Once expanded, the combo's height will take as much room as the # of available results. In case
		 *          there are too many results displayed, this will fix the drop down height.
		 *          </p>
		 *          Defaults to 290 px.
		 */
	    maxDropHeight : 200,

	    /**
		 * @options {Integer} maxSuggestions
		 *          <p>
		 *          The maximum number of results displayed in the combo drop down at once.
		 *          </p>
		 *          Defaults to null.
		 */
	    maxSuggestions : 100,

	    /**
		 * @options {String} method
		 *          <p>
		 *          The method used by the ajax request.
		 *          </p>
		 *          Defaults to 'POST'
		 */
	    method : 'GET',

	    /**
		 * @options {Integer} minChars
		 *          <p>
		 *          The minimum number of characters the user must type before the combo expands and offers suggestions.
		 *          Defaults to <code>0</code>.
		 */
	    minChars : 0,

	    /**
		 * @options {Boolean} required
		 *          <p>
		 *          Whether or not this field should be required
		 *          </p>
		 *          Defaults to false
		 */
	    required : false,

	    /**
		 * @options {Boolean} resultAsString
		 *          <p>
		 *          Set to true to render selection as comma separated string
		 *          </p>
		 *          Defaults to <code>false</code>.
		 */
	    resultAsString : false,

	    /**
		 * @options {String} resultsField
		 *          <p>
		 *          Name of JSON object property that represents the list of suggested objets
		 *          </p>
		 *          Defaults to <code>results</code>
		 */
	    resultsField : 'results',

	    /**
		 * @options {String} selectionPosition
		 *          <p>
		 *          Where the selected items will be displayed. Only 'right', 'bottom' and 'inner' are valid values
		 *          </p>
		 *          Defaults to <code>'inner'</code>, meaning the selected items will appear within the input box
		 *          itself.
		 */
	    selectionPosition : 'inner',

	    /**
		 * @options {Boolean} strictSuggest
		 *          <p>
		 *          If set to true, suggestions will have to start by user input (and not simply contain it as a
		 *          substring)
		 *          </p>
		 *          Defaults to <code>false</code>.
		 */
	    strictSuggest : false,

	    /**
		 * @options {Integer} typeDelay
		 *          <p>
		 *          Amount (in ms) between keyboard registers.
		 *          </p>
		 * 
		 * Defaults to <code>400</code>
		 */
	    typeDelay : 400,

	    /**
		 * @options {String} cls
		 *          <p>
		 *          A custom CSS class to apply to the field's underlying element.
		 *          </p>
		 *          Defaults to <code>''</code>.
		 */
	    cls : '',

	    /**
		 * @options {Array / String / Function} data JSON Data source used to populate the combo box. 3 options are
		 *          available here:<br/>
		 *          <p>
		 *          <u>No Data Source (default)</u><br/> When left null, the combo box will not suggest anything. It
		 *          can still enable the user to enter multiple entries if allowFreeEntries is * set to true (default).
		 *          </p>
		 *          <p>
		 *          <u>Static Source</u><br/> You can pass an array of JSON objects, an array of strings or even a
		 *          single CSV string as the data source.<br/>For ex. data: [* {id:0,name:"Paris"}, {id: 1, name: "New
		 *          York"}]<br/> You can also pass any json object with the results property containing the json array.
		 *          </p>
		 *          <p>
		 *          <u>Url</u><br/> You can pass the url from which the component will fetch its JSON data.<br/>Data
		 *          will be fetched using a POST ajax request that will * include the entered text as 'query' parameter.
		 *          The results fetched from the server can be: <br/> - an array of JSON objects (ex:
		 *          [{id:...,name:...},{...}])<br/> - a string containing an array of JSON objects ready to be parsed
		 *          (ex: "[{id:...,name:...},{...}]")<br/> - a JSON object whose data will be contained in the results
		 *          property (ex: {results: [{id:...,name:...},{...}]
		 *          </p>
		 *          <p>
		 *          <u>Function</u><br/> You can pass a function which returns an array of JSON objects (ex:
		 *          [{id:...,name:...},{...}])<br/> The function can return the JSON data or it can use the first
		 *          argument as function to handle the data.<br/> Only one (callback function or return value) is
		 *          needed for the function to succeed.<br/> See the following example:<br/> function (response) { var
		 *          myjson = [{name: 'test', id: 1}]; response(myjson); return myjson; }
		 *          </p>
		 *          Defaults to <b>null</b>
		 */
	    data : null,

	    /**
		 * @options {Object} dataParams
		 *          <p>
		 *          Additional parameters to the ajax call
		 *          </p>
		 *          Defaults to <code>{}</code>
		 */
	    dataUrlParams : {},

	    /**
		 * @options {String} emptyText
		 *          <p>
		 *          The default placeholder text when nothing has been entered
		 *          </p>
		 *          Defaults to <code>'Type or click here'</code> or just <code>'Click here'</code> if not editable.
		 */
	    emptyText : function()
	    {
		    return objThis.options.editable ? 'Type or click here' : 'Click here';
	    },

	    /**
		 * @options {String} emptyTextCls
		 *          <p>
		 *          A custom CSS class to style the empty text
		 *          </p>
		 *          Defaults to <code>'tag-empty-text'</code>.
		 */
	    emptyTextCls : 'tag-empty-text',

	    /**
		 * @options {Boolean} expanded
		 *          <p>
		 *          Set starting state for combo.
		 *          </p>
		 *          Defaults to <code>false</code>.
		 */
	    expanded : false,

	    /**
		 * @options {Boolean} expandOnFocus
		 *          <p>
		 *          Automatically expands combo on focus.
		 *          </p>
		 *          Defaults to <code>false</code>.
		 */
	    expandOnFocus : function()
	    {
		    return objThis.options.editable ? false : true;
	    },

	    /**
		 * @options {Boolean} hideTrigger
		 *          <p>
		 *          Set to true to hide the trigger on the right
		 *          </p>
		 *          Defaults to <code>false</code>.
		 */
	    hideTrigger : false,

	    /**
		 * @options {Boolean} highlight
		 *          <p>
		 *          Set to true to highlight search input within displayed suggestions
		 *          </p>
		 *          Defaults to <code>true</code>.
		 */
	    highlight : true,

	    /**
		 * @options {String} id
		 *          <p>
		 *          A custom ID for this component
		 *          </p>
		 *          Defaults to 'tag-ctn-{n}' with n positive integer
		 */
	    id : function()
	    {
		    return 'tag-ctn-' + $('div[id^="tag-ctn"]').length;
	    },

	    /**
		 * @options {String} infoMsgCls
		 *          <p>
		 *          A class that is added to the info message appearing on the top-right part of the component
		 *          </p>
		 *          Defaults to ''
		 */
	    infoMsgCls : '',

	    /**
		 * @options {Object} inputCfg
		 *          <p>
		 *          Additional parameters passed out to the INPUT tag. Enables usage of AngularJS's custom tags for ex.
		 *          </p>
		 *          Defaults to <code>{}</code>
		 */
	    inputCfg : {},

	    /**
		 * @options {String} invalidCls
		 *          <p>
		 *          The class that is applied to show that the field is invalid
		 *          </p>
		 *          Defaults to tag-ctn-invalid
		 */
	    invalidCls : 'tag-ctn-invalid',

	    /**
		 * @options {Boolean} matchCase
		 *          <p>
		 *          Set to true to filter data results according to case. Useless if the data is fetched remotely
		 *          </p>
		 *          Defaults to <code>false</code>.
		 */
	    matchCase : false,

	    /**
		 * @options {Integer} maxEntryLength
		 *          <p>
		 *          Defines how long the user free entry can be. Set to null for no limit.
		 *          </p>
		 *          Defaults to null.
		 */
	    maxEntryLength : null,

	    /**
		 * @options {String} maxEntryRenderer
		 *          <p>
		 *          A function that defines the helper text when the max entry length has been surpassed.
		 *          </p>
		 *          Defaults to
		 *          <code>function(v){return 'Please reduce your entry by ' + v + ' character' + (v > 1 ? 's':'');}</code>
		 */
	    maxEntryRenderer : function(v)
	    {
		    return 'Please reduce your entry by ' + v + ' character' + (v > 1 ? 's' : '');
	    },

	    /**
		 * @options {Integer} maxSelection
		 *          <p>
		 *          The maximum number of items the user can select if multiple selection is allowed. Set to null to
		 *          remove the limit.
		 *          </p>
		 *          Defaults to 10.
		 */
	    maxSelection : null,

	    /**
		 * @options {Function} maxSelectionRenderer
		 *          <p>
		 *          A function that defines the helper text when the max selection amount has been reached. The function
		 *          has a single parameter which is the number of selected elements.
		 *          </p>
		 *          Defaults to
		 *          <code>function(v){return 'You cannot choose more than ' + v + ' item' + (v > 1 ? 's':'');}</code>
		 */
	    maxSelectionRenderer : function(v)
	    {
		    return 'You cannot choose more than ' + v + ' item' + (v > 1 ? 's' : '');
	    },

	    /**
		 * @options {Function} minCharsRenderer
		 *          <p>
		 *          A function that defines the helper text when not enough letters are set. The function has a single
		 *          parameter which is the difference between the required amount of letters and the current one.
		 *          </p>
		 *          Defaults to
		 *          <code>function(v){return 'Please type ' + v + ' more character' + (v > 1 ? 's':'');}</code>
		 */
	    minCharsRenderer : function(v)
	    {
		    return 'Please type ' + v + ' more character' + (v > 1 ? 's' : '');
	    },

	    /**
		 * @options {String} noSuggestionText
		 *          <p>
		 *          The text displayed when there are no suggestions.
		 *          </p>
		 *          Defaults to 'No suggestions"
		 */
	    noSuggestionText : 'No suggestions',

	    /**
		 * @options {Boolean} preselectSingleSuggestion
		 *          <p>
		 *          If a single suggestion comes out, it is preselected.
		 *          </p>
		 *          Defaults to <code>true</code>.
		 */
	    preselectSingleSuggestion : true,

	    /**
		 * @options (function) renderer
		 *          <p>
		 *          A function used to define how the items will be presented in the combo
		 *          </p>
		 *          Defaults to <code>null</code>.
		 */
	    renderer : null,

	    /**
		 * @options {String} selectionCls
		 *          <p>
		 *          A custom CSS class to add to a selected item
		 *          </p>
		 *          Defaults to <code>''</code>.
		 */
	    selectionCls : '',

	    /**
		 * @options (function) selectionRenderer
		 *          <p>
		 *          A function used to define how the items will be presented in the tag list
		 *          </p>
		 *          Defaults to <code>null</code>.
		 */
	    selectionRenderer : null,

	    /**
		 * @options {Boolean} selectionStacked
		 *          <p>
		 *          Set to true to stack the selectioned items when positioned on the bottom Requires the
		 *          selectionPosition to be set to 'bottom'
		 *          </p>
		 *          Defaults to <code>false</code>.
		 */
	    selectionStacked : false,

	    /**
		 * @options {String} sortDir
		 *          <p>
		 *          Direction used for sorting. Only 'asc' and 'desc' are valid values
		 *          </p>
		 *          Defaults to <code>'asc'</code>.
		 */
	    sortDir : 'asc',

	    /**
		 * @options {String} sortOrder
		 *          <p>
		 *          name of JSON object property for local result sorting. Leave null if you do not wish the results to
		 *          be ordered or if they are already ordered remotely.
		 *          </p>
		 * 
		 * Defaults to <code>null</code>.
		 */
	    sortOrder : null,

	    /**
		 * @options {String} style
		 *          <p>
		 *          Custom style added to the component container.
		 *          </p>
		 * 
		 * Defaults to <code>''</code>.
		 */
	    style : '',

	    /**
		 * @options {Boolean} toggleOnClick
		 *          <p>
		 *          If set to true, the combo will expand / collapse when clicked upon
		 *          </p>
		 *          Defaults to <code>false</code>.
		 */
	    toggleOnClick : false,

	    /**
		 * @options {Boolean} useTabKey
		 *          <p>
		 *          If set to true, tab won't blur the component but will be registered as the ENTER key
		 *          </p>
		 *          Defaults to <code>false</code>.
		 */
	    useTabKey : false,

	    /**
		 * @options {Boolean} useCommaKey
		 *          <p>
		 *          If set to true, using comma will validate the user's choice
		 *          </p>
		 *          Defaults to <code>true</code>.
		 */
	    useCommaKey : true,

	    /**
		 * @options {Boolean} useZebraStyle
		 *          <p>
		 *          Determines whether or not the results will be displayed with a zebra table style
		 *          </p>
		 *          Defaults to <code>true</code>.
		 */
	    useZebraStyle : true,

	    /**
		 * @options {Integer} width (in px)
		 *          <p>
		 *          Width of the component
		 *          </p>
		 *          Defaults to underlying element width.
		 */
	    width : function()
	    {
		    return $(this).width();
	    }
	};
	//
	//
	//

	/** ******** PUBLIC METHODS *********** */
	/**
	 * Add one or multiple json items to the current selection
	 * 
	 * @param items -
	 *            json object or array of json objects
	 * @param isSilent -
	 *            (optional) set to true to suppress 'selectionchange' event from being triggered
	 */
	function addToSelection(items, isSilent)
	{
		objThis.selectorElt.tagSuggest().addToSelection(items, isSilent);
	}
	/**
	 * Clears the current selection
	 * 
	 * @param isSilent -
	 *            (optional) set to true to suppress 'selectionchange' event from being triggered
	 */
	function clear(isSilent)
	{
		objThis.selectorElt.tagSuggest().clear(isSilent);
	}
	/**
	 * Collapse the drop down part of the combo
	 */
	function collapse()
	{
		objThis.selectorElt.tagSuggest().collapse();
	}
	/**
	 * Set the component in a disabled state.
	 */
	function disable()
	{
		objThis.selectorElt.tagSuggest().disable();
	}
	/**
	 * Empties out the combo user text
	 */
	function empty()
	{
		objThis.selectorElt.tagSuggest().empty();
	}
	/**
	 * Set the component in a enable state.
	 */
	function enable()
	{
		objThis.selectorElt.tagSuggest().enable();
	}
	/**
	 * Expand the drop drown part of the combo.
	 */
	function expand()
	{
		objThis.selectorElt.tagSuggest().expand();
	}
	/**
	 * Retrieve component enabled status
	 */
	function isDisabled()
	{
		return objThis.selectorElt.tagSuggest().isDisabled();
	}
	/**
	 * Checks whether the field is valid or not
	 * 
	 * @return {boolean}
	 */
	function isValid()
	{
		return objThis.selectorElt.tagSuggest().isValid();
	}
	/**
	 * Gets the data params for current ajax request
	 */
	function getDataUrlParams()
	{
		return objThis.selectorElt.tagSuggest().getDataUrlParams();
	}
	/**
	 * Gets the name given to the form input
	 */
	function getName()
	{
		return objThis.selectorElt.tagSuggest().getName();
	}
	/**
	 * Retrieve an array of selected json objects
	 * 
	 * @return {Array}
	 */
	function getSelectedItems()
	{
		return objThis.selectorElt.tagSuggest().getSelectedItems();
	}
	/**
	 * Retrieve the current text entered by the user
	 */
	function getRawValue()
	{
		return objThis.selectorElt.tagSuggest().getRawValue();
	}
	/**
	 * Retrieve an array of selected values
	 */
	function getValue()
	{
		return objThis.selectorElt.tagSuggest().getValue();
	}
	/**
	 * Remove one or multiples json items from the current selection
	 * 
	 * @param items -
	 *            json object or array of json objects
	 * @param isSilent -
	 *            (optional) set to true to suppress 'selectionchange' event from being triggered
	 */
	function removeFromSelection(items, isSilent)
	{
		return objThis.selectorElt.tagSuggest().removeFromSelection(items, isSilent);
	}
	/**
	 * Set up some combo data after it has been rendered
	 * 
	 * @param data
	 */
	function setData(data)
	{
		objThis.selectorElt.tagSuggest().setData(data);
	}
	/**
	 * Sets the name for the input field so it can be fetched in the form
	 * 
	 * @param name
	 */
	function setName(name)
	{
		objThis.selectorElt.tagSuggest().setName(name);
	}
	/**
	 * Sets a value for the combo box. Value must be a value or an array of value with data type matching valueField
	 * one.
	 * 
	 * @param data
	 */
	function setValue(data)
	{
		objThis.selectorElt.tagSuggest().setValue(data);
	}
	/**
	 * Sets data params for subsequent ajax requests
	 * 
	 * @param params
	 */
	function setDataUrlParams(params)
	{
		objThis.selectorElt.tagSuggest().setDataUrlParams(params);
	}
	//
	//
	//
	function _init()
	{
		objThis.options = objThis.ttUtilsInst.extendObject(objThis.options, objThis.defaultOptions);
		//
		objThis.selectorElt = objThis.JQ(objThis.selector);
		//
		if (!objThis.options.name)
			objThis.options.name = objThis.selectorElt.attr("name");
		//
		objThis.selectorElt.tagSuggest(objThis.options);
		//
		if(objThis.options.value) objThis.addToSelection(objThis.options.value, true);
	}
	//
	//
	//
	_init();
}
