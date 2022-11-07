/**
 * Date Picker component 
 * 
 * If you have to use other plugin with the same namespace, just call the $.fn.datepicker.noConflict method to revert to it.
   $.fn.datepicker.noConflict(); 
   // Code that uses other plugin's "$().datepicker" can follow here.
 * 
 * @param _selector
 * @param _options
 * @param _win
 * @param _doc
 * @returns
 */
function TTDatePicker(_selector, _options, _win, _doc)
{
	var objThis = this;
	objThis.name = "TT_DATE_PICKER";
	objThis.win = _win ? _win : self;
	objThis.doc = _doc ? _doc : objThis.win.document;
	objThis.JQ = objThis.win.$;
	//
	//
	if(!_selector || _selector.split(" ").join("")=="") throw "Provided Selector cannot be empty or null";
	if(!objThis.JQ) throw "JQuery library is required for this component";
	if(!objThis.JQ.fn.datepicker) throw "Date picker JS library is missing";
	//
	//
	objThis.selector = _selector;
	objThis.selectorElt = null;
	objThis.options = null;
	objThis.defaultOptions = {
		autoShow: false,
		autoHide: true,
		autoPick: false,
		inline: false,
		container: null,	// used only with inline:true
		trigger: null, 		// Selector element used to trigger the datepicker
		language: 'en-US',
		format: 'dd/mm/yyyy',
		date: null, 		// new Date(2018, 1, 14) // Or '02/14/2018'
		startDate: null,	// all the dates before this date will be disabled
		endDate: null,		// all the dates after this date will be disabled
		startView: 0,		// 0: days , 1: months , 2: years
		weekStart: 1,		// 0: Sunday, 1: Monday, . . .
		yearFirst: false,	// Show year before month on the datepicker header
		yearSuffix: '', 	// A string suffix to the year number.
		days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		daysMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
		months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
		monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
		itemTag: 'li',		// A element tag for each item of years, months and
							// days
		mutedClass: 'muted',
		pickedClass: 'picked',
		disabledClass: 'disabled',
		highlightedClass: 'highlighted',
		highlightedClass: 'highlighted',
		// template: 'html...'
		offset: 10,			// The offset top or bottom of the datepicker from
							// the element.
		zIndex: 1,
		filter: null, 		// function(data){} Filter each date item. If return
							// a false value, the related date will be disabled.
		show: null,			// function(ev){...ev.preventDefault();} A shortcut
							// of the "show.datepicker" event
		hide: null,			// function(ev){...ev.preventDefault();} A shortcut
							// of the "hide.datepicker" event
		pick: null			// function(ev){...ev.preventDefault();} A shortcut
							// of the "pick.datepicker" event
	};
	//
	// METHODS
	//
	this.show=_show;
	this.hide=_hide;
	this.update=_update;
	this.pick=_pick;
	this.reset=_reset;
	//
	this.getMonthName = _getMonthName;
	this.getDayName = _getDayName;
	this.getDate = _getDate;
	this.setDate = _setDate;
	this.setStartDate = _setStartDate;
	this.setEndDate = _setEndDate;
	this.parseDate = _parseDate;
	this.formatDate = _formatDate;
	this.destroy = _destroy;
	//
	//
	//
	function _init()
	{
		objThis.options = objThis.defaultOptions;
		objThis.selectorElt = objThis.JQ(objThis.selector);
		if(null!=_options)
		{
			for(var opt in _options)
			{
				if(_options[opt]) objThis.options[opt] = _options[opt];
			}
		}
		//
		objThis.selectorElt.datepicker(objThis.options);
	}
	//
	//
	// Show the datepicker
	function _show()
	{
		objThis.selectorElt.datepicker('show');
	}
	// Hide the datepicker
	function _hide()
	{
		objThis.selectorElt.datepicker('hide');
	}
	// Update the datepicker with the value or text of the current element
	function _update()
	{
		objThis.selectorElt.datepicker('update');
	}
	// Pick the current date to the element
	function _pick()
	{
		objThis.selectorElt.datepicker('pick');
	}
	// Reset the datepicker
	function _reset()
	{
		objThis.selectorElt.datepicker('reset');
	}
	//

	/**
	 * Return month name of the given month index or of the selected date
	 * 
	 * @param monthIndex: Get the name of the given month index
	 * @param short: boolean to return the short name
	 */
	function _getMonthName(monthIndex, short)
	{
		return objThis.selectorElt.datepicker('getMonthName', monthIndex, short);
	}

	/**
	 * Return day name of the given day index or of the selected date
	 * 
	 * @param dayIndex: Get the name of the given day index
	 * @param short: boolean to return the short name
	 * @param shorter: boolean to return the shorter name
	 */
	function _getDayName(dayIndex, short, shorter)
	{
		return objThis.selectorElt.datepicker('getDayName', dayIndex, short, shorter);
	}

	/**
	 * Return the date from the selected date picker value
	 * 
	 * @param formatted: boolean to return the date as string, otherwise will return a Date object
	 */
	function _getDate(formatted)
	{
		return objThis.selectorElt.datepicker('getDate', formatted);
	}

	/**
	 * Set the date into the date picker input
	 * 
	 * @param date: date object / string 
	 */
	function _setDate(date)
	{
		return objThis.selectorElt.datepicker('setDate', date);
	}

	/**
	 * Set the start date of the given date picker instance 
	 * 
	 * @param date: date object / string 
	 */
	function _setStartDate(date)
	{
		return objThis.selectorElt.datepicker('setStartDate', date);
	}

	/**
	 * Set the end date of the given date picker instance 
	 * 
	 * @param date: date object / string 
	 */
	function _setEndDate(date)
	{
		return objThis.selectorElt.datepicker('setEndDate', date);
	}

	/**
	 * Format a date object to a string with the set date format
	 * 
	 * @param date: string 
	 */
	function _parseDate(date)
	{
		return objThis.selectorElt.datepicker('parseDate', date);
	}

	/**
	 * Parse a date string with the set date format 
	 * 
	 * @param date: Date object 
	 */
	function _formatDate(date)
	{
		return objThis.selectorElt.datepicker('formatDate', date);
	}

	function _destroy()
	{
		objThis.selectorElt.datepicker('destroy');
	}
	
	/**
	 * @param ev: String with the below options
	 * 		show.datepicker: This event fires when starts to show the datepicker.
	 * 		hide.datepicker: This event fires when starts to hide the datepicker.
	 * 		pick.datepicker: This event fires when start to pick a year, month or day.
	 * 
	 * @param callback: Method to be called when the given event trigger is captured
	 */
	function _on(ev, callback)
	{
		objThis.selectorElt.on(ev, function(e){
			// e.preventDefault(); // Prevent to make action exp: pick the date
			// e.date, e.view => '', 'year', 'month', 'day'
			callback(e);
		});
	}
	//
	//
	_init();
	//
}
