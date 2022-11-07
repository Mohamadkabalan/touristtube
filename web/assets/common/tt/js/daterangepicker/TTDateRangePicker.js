/**
 * Date Range Picker component
 * 
 * 
 * 
 * @param _selector
 * @param _options
 * @param _win
 * @param _doc 
 *  
 * @author Fares Zgheib
 * 
 */
function TTDateRangePicker(_selector, _options, _win, _doc)
{
	var objThis = this;
	objThis.name = "TT_DATE_RANGE_PICKER";
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
	if (!objThis.JQ.fn.dateRangePicker)
		throw "Date Range Picker JS library is missing";
	if (!objThis.ttUtilsInst)
		throw "TTUtils JS library is missing";
	//
	//

	objThis.selector = _selector;
	objThis.selectorElt = null;
	objThis.options = _options;
	objThis.defaultOptions =
	        {
	            autoClose : true,
	            singleDate : false,
	            inputs : null, // {start: '#startDate',end: '#endDate'} to be activated when singleDate=false
	            format : 'MM/DD/YYYY',
	            separator : ' to ',
	            singleMonth : false, // 'auto',
	            showTopbar : true, // if single month is true this should be false
	            startDate : false,
	            endDate : false,
	            selectForward : false,
	            selectBackward : false,
	            minDays : 0,
	            maxDays : 0,
	            monthSelect : true,
	            yearSelect : true, // [1900, moment().get('year')]
	            showWeekNumbers : false,
	            language : "default", // 'auto',
	            startOfWeek : 'monday',// or sunday

	            inline : false,
	            container : 'body', // JQ selector and inline should be = true
	            alwaysOpen : false, // Usually used when it's inline and inside a specific container

	            getValue : function(forDisplay)
	            {
		            var inputs = _getInputs();
		            if (inputs) {
		            	if(inputs.start.val() && inputs.start.val()!="")
		            	{
			            	if(forDisplay==true)
			            		return inputs.start.val() + objThis.options.separator + inputs.end.val();
			            	else return inputs.startValue.val() + objThis.options.separator + inputs.endValue.val();
		            	}else return "";
		            } else {
		            	if(forDisplay)
		            		return objThis.selectorElt.val();
		            	else
		            		{
					            var inputValueSelector = "#" + objThis.selectorElt.attr("inputvalueid");
					            return objThis.JQ(inputValueSelector).val();
		            		}
		            }
	            },
	            setValue : function(full, start, end)
	            {
		            var inputs = _getInputs();
		            if (inputs) {
			            inputs.start.val( start );
			            inputs.end.val( end );
			            //
			            inputs.startValue.val( _getValueDate(start) );
			            inputs.endValue.val( _getValueDate(end) );
		            } else {
		            	var inputValueSelector = "#" + objThis.selectorElt.attr("inputvalueid");
		            	//
			            if (!objThis.selectorElt.attr('readonly') && !objThis.selectorElt.is(':disabled')
			                    && full != objThis.selectorElt.val()) {
				            objThis.selectorElt.val(full);
				            //
				            //Convert to value date
				            //
				            if (objThis.options.singleDate) {
				            	if(end.toLowerCase()=="Invalid date".toLowerCase())
				            		dateValue = _getValueDate(full);
				            	else if( new Date(_getValueDate(end)).getFullYear()==1970 )
				            		dateValue = objThis.JQ(inputValueSelector).val();
				            	else dateValue = _getValueDate(start);
					            //
					            _getDateRangePickerInst().setStart(new Date(dateValue));
				            } else {
				            	dateValue = "";
					            if (full && full != "") {
						            var startDate = _getValueDate(start);
						            var endDate = _getValueDate(end);
						            //
						            dateValue = startDate + objThis.options.separator + endDate;
					            }
				            }
				            //
				            objThis.JQ(inputValueSelector).val(dateValue);
				            //
			            }
		            }
	            },
	            beforeShowDay : function(t)
	            {
		            // IN ORDER TO DISABLE SPECIFIC DATES AND SHOW SPECIFIC HOVER TOOLTIPS

		            // var valid = !(t.getDay() == 0 || t.getDay() == 6); //disable saturday and sunday
		            var _class = '';
		            var _tooltip = '';
		            // var _tooltip = valid ? '' : 'weekends are disabled';
		            // return [valid,_class,_tooltip];

		            return [ true, _class, _tooltip ];
	            },
	            time : {
		            enabled : false
	            },
	            showShortcuts : false,
	            shortcuts : {
	            // 'prev-days': [1,3,5,7],
	            // 'next-days': [3,5,7],
	            // 'prev' : ['week','month','year'],
	            // 'next' : ['week','month','year']
	            },
	            customShortcuts : [],
	            lookBehind : false,
	            batchMode : false,
	            duration : 200,
	            stickyMonths : false,
	            dayDivAttrs : [],
	            dayTdAttrs : [],
	            applyBtnClass : '',
	            hoveringTooltip : function(days, startTime, hoveringTime)
	            {
		            return days > 1 ? days + ' ' + _translate('days') : '';
	            },
	            swapTime : false,
	            getWeekNumber : function(date) // date will be the first day of a week
	            {
		            return moment(date).format('w');
	            },
	            events : {
	                /**
					 * function(event, obj) { This event will be triggered when first date is selected
					 * 
					 * obj will be something like this: { date1: (Date object of the earlier date) } }
					 */
	                "first-date-selected" : null,
	                /**
					 * function(event,obj) { This event will be triggered when second date is selected obj will be
					 * something like this: { date1: (Date object of the earlier date), date2: (Date object of the later
					 * date), value: "2013-06-05 to 2013-06-07" } }
					 */
	                "change" : null,
	                /**
					 * function(event,obj) { This event will be triggered when user clicks on the apply button }
					 */
	                "apply" : null,
	                /**
					 * This event will be triggered before date range picker close animation
					 */
	                "close" : null,
	                /**
					 * This event will be triggered after date range picker close animation
					 */
	                "closed" : null,
	                /**
					 * This event will be triggered before date range picker open animation
					 */
	                "open" : null,
	                /**
					 * This event will be triggered after date range picker open animation
					 */
	                "opened" : null
	            }
	        };
	//
	// METHODS
	//
	/**
	 * set date range
	 * 
	 * @param start:
	 *            Date object
	 * @param end:
	 *            Date object
	 * @param skipFireChangeEvent:
	 *            boolean [default: false]
	 * 
	 */
	objThis.setDateRange = setDateRange;
	/**
	 * clear date range
	 */
	objThis.clear = clear;
	/**
	 * close date range picker overlay
	 */
	objThis.close = close;
	/**
	 * open date range picker overlay
	 */
	objThis.open = open;
	/**
	 * reset to default months
	 */
	objThis.resetMonthsView = resetMonthsView;
	/**
	 * destroy all date range picker related things
	 */
	objThis.destroy = destroy;
	/**
	 * redraw and re-instantiate the date range picker
	 */
	objThis.redraw = redraw;
	/**
	 * Return the input value of the date range component
	 */
	objThis.getValue = getValue;
	/**
	 * Return the Start | End date values in JS object
	 */
	objThis.getDatesValue = getDatesValue;
	/**
	 * Return the default date time, by default the current date time
	 */
	objThis.getDefaultDateTime = getDefaultDateTime;
	/**
	 * Return the time stamp of the given param
	 * 
	 * @param Date
	 *            object or String with the same given format
	 */
	objThis.toLocalTimestamp = toLocalTimestamp;
	/**
	 * Return the full month name
	 * 
	 * @param monthIndex :
	 *            Ineteger starting by 0
	 * 
	 */
	objThis.getMonthName = getMonthName;
	/**
	 * Return the number of days between 2 given dates
	 * 
	 * @param date1:
	 *            Date object or String of same given format
	 * @param date2:
	 *            Date object or String of same given format
	 * 
	 */
	objThis.countDays = countDays;

	//
	// PUBLIC METHODS
	//
	function getValue(forDisplay)
	{
		return _getDateRangePickerInst().getValue(forDisplay);
	}

	function getDatesValue(forDisplay)
	{
		var result = {startDate: null, endDate: null};
		var valueStr = _getDateRangePickerInst().getValue(forDisplay);
		if(valueStr) 
		{
			var splitted = valueStr.split(objThis.options.separator);
			if(splitted)
			{
				if(splitted.length > 0)
				{
					result.startDate = splitted[0]; 
					if(splitted.length > 1) result.endDate = splitted[1]; 
				}
			}
		}
		//
		return result;
	}

	function setDateRange(from, to, skipFireChangeEvent)
	{
		to = to || from || new Date();
		_getDateRangePickerInst().setDateRange(from, to, skipFireChangeEvent);
	}

	function clear()
	{
		_getDateRangePickerInst().clear();
	}

	function close()
	{
		evt = evt || objThis.win.event;
		if (evt)
			evt.stopPropagation();
		_getDateRangePickerInst().close();
	}

	function open(evt)
	{
		evt = evt || objThis.win.event;
		if (evt)
			evt.stopPropagation();
		_getDateRangePickerInst().open();
	}

	function resetMonthsView()
	{
		_getDateRangePickerInst().resetMonthsView();
	}

	function destroy()
	{
		_getDateRangePickerInst().destroy();
	}

	function redraw()
	{
		_getDateRangePickerInst().redraw();
	}

	function getDefaultDateTime()
	{
		return _getDateRangePickerInst().getDefaultTime();
	}

	function toLocalTimestamp(t)
	{
		return _getDateRangePickerInst().toLocalTimestamp(t);
	}

	function getMonthName(monthIndex)
	{
		return _getDateRangePickerInst().nameMonth(monthIndex);
	}

	function countDays(start, end)
	{
		return _getDateRangePickerInst().countDays(start, end);
	}

	//
	// PRIVATE METHDS
	//
	function _getDateRangePickerInst()
	{
		return objThis.selectorElt.data('dateRangePicker');
	}

	function _translate(translationKey)
	{
		return _getDateRangePickerInst().translate(translationKey);
	}

	function _renderInputsDislay(jqElt)
	{
		var id = jqElt.attr("id");
		var name = jqElt.attr("name");
		//
		if(!id || id=="") id = name;
		//
		if (id && id != "") {
			jqElt.attr("id", id + "_disp");
			jqElt.attr("inputvalueid", id);
		}
		//
		if (name && name != "") {
			jqElt.attr("name", name + "_disp");
			jqElt.attr("inputvaluename", name);
			//
			jqElt.after("<input type='hidden' name='" + name + "' id='" + id + "' value='" + jqElt.val() + "' ></input>");
		}
	}

	function _prepareInputs()
	{
		var inputs = _getInputs();
		if (inputs) {
			_renderInputsDislay(inputs.startValue);
			_renderInputsDislay(inputs.endValue);
		}else
			{
				_renderInputsDislay(objThis.selectorElt);
			}

	}

	function _init()
	{
		//
		objThis.options = objThis.ttUtilsInst.extendObject(objThis.options, objThis.defaultOptions);

		objThis.options.showTopbar = !objThis.options.singleMonth;

		var events = objThis.options.events;
		delete objThis.options.events;
		//
		objThis.selectorElt = objThis.JQ(objThis.selector);
		//
		_prepareInputs();
		//
		objThis.selectorElt.dateRangePicker(objThis.options);
		//
		//Change type="date" to prevent conflicts with Browser default picker
		//
        var inputs = _getInputs();
        if (inputs) {
        	inputs.start.attr("type", "text");
        	inputs.end.attr("type", "text");
        } else {
    		objThis.selectorElt.attr("type", "text");
        }
        //
		//
		if (events) {
			for ( var ev in events) {
				if (null != events[ev]) {
					_on(ev, events[ev]);
				}
			}
		}
		//
		if (!objThis.options.singleDate && objThis.options.inputs) {
			if (!objThis.options.inputs.start || !objThis.options.inputs.end)
				objThis.options.inputs = null;
		}
		//
		if(objThis.selectorElt.val() && objThis.selectorElt.val() != "") 
			objThis.setDateRange( new Date(objThis.selectorElt.val()), null, false );
		else if(_getInputs() && _getInputs().startValue.val() != "" && _getInputs().endValue.val() != "")
			{
				objThis.setDateRange( new Date(_getInputs().startValue.val()), new Date(_getInputs().endValue.val()), false );
			}
		//
	}

	function _getInputs()
	{
		if (objThis.options.inputs) {
			return {
			    start : objThis.JQ(objThis.options.inputs.start + "_disp"),
			    end : objThis.JQ(objThis.options.inputs.end + "_disp"),
			    startValue : objThis.JQ(objThis.options.inputs.start),
			    endValue : objThis.JQ(objThis.options.inputs.end)
			};
		} else
			return null;
	}

	function _getValueDate(dateStr)
	{
		if(dateStr && dateStr!="")
		{
			var dateObj = new Date( objThis.toLocalTimestamp(dateStr) );
			var month = dateObj.getMonth() + 1;
			if(month<10) month = "0" + month;
			var day = dateObj.getDate();
			if(day<10) day = "0" + day;
			return month + "/" + day + "/" + dateObj.getFullYear();
		}else return "";
	}
	//
	//
	/**
	 * @param ev:
	 *            Special Date Range Event of type String, to be attached for the date range picker element and to be
	 *            fired when the given event is captured
	 * 
	 * @param callback:
	 *            Method to be called when the given event trigger is captured
	 */
	function _on(ev, callback)
	{
		objThis.selectorElt.bind("datepicker-" + ev, function(e, obj)
		{
			callback(e, obj);
		});
	}
	//
	//
	_init();
	//
}

