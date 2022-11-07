var frmValidator;
var comboGridCity;
$(document).ready(function()
{
	frmValidator = new TTFormValidator("#formId", {
		msgPosition : "bottom"
	});
	//
    var accOpts = {
    		url: generateLangURL('/corporate/account/searchAccount','ajax'),
    		searchButton: false,
    		resetButton: false,
    		colModel: [	{ 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
    			{'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
		    ]
        };
    var comboGridAccount = new AutoCompleteComboGrid("countryCode", accOpts);
    //
    var ctryOpts = {
    		url: generateLangURL("/corporate/SearchCountry", "ajax"),
    		searchButton: false,
    		resetButton: false,
    		colModel: [	{ 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': false, 'hidden': true},
    			{'dbName' : 'p.code' ,'columnName':'code','width':'80px','label':'code','isIdProperty': true},
    			{'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
		    ]
        };
    var comboGridCountry = new AutoCompleteComboGrid("country", ctryOpts);
    //
    var cityOpts = {
            url: generateLangURL('/corporate/SearchCity','ajax'),
            parameter: "countryCode",
            searchButton: false,
            resetButton: false,
            colModel: [ { 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
                {'dbName' : 'p.fullname' ,'columnName':'fullname','width':'250px','label':'fullname','isProperty': true}
                ]
        };
    comboGridCity = new AutoCompleteComboGrid("cityName", cityOpts);
    //
    //
	if ($('#account_pic').length > 0) {
	    initCropAndUpload();
//		InitUploaderHome('account_pic', 'btnstan_account_about', 15, 0);
	}

	if ($('.passportExpiryDate').length > 0) {
		if ($('.passportExpiryDate').hasClass('picker_single')) {
			isSingle = true;
		}
		var $datePicker = $('.passportExpiryDate');
		var today = new Date();
		var minYear = 1910;
		var $date = $datePicker.val();
		$datePicker.daterangepicker({
		    singleDatePicker : isSingle,
		    autoApply : true,
		    showDropdowns : true,
		    autoUpdateInput : false,
		    opens : 'left',
		    minYear : minYear,
		    locale : {
			    cancelLabel : 'Clear'
		    }
		});
		if ($date != '') {
			$datePicker.data('daterangepicker').setStartDate(convertmyDate($date));
		}
		$datePicker.on('apply.daterangepicker', function(ev, picker)
		{
			var $this = $(this);
			var $fromDate = picker.startDate.format('YYYY-MM-DD');
			$this.val($fromDate);
			$this.attr('value', $fromDate);
		});
	}

	if ($('.issueDate').length > 0) {
		if ($('.issueDate').hasClass('picker_single')) {
			isSingle = true;
		}
		var $datePicker = $('.issueDate');
		var today = new Date();
		var maxYear = today.getFullYear();
		var minYear = 1910;
		var $date = $datePicker.val();
		$datePicker.daterangepicker({
		    singleDatePicker : isSingle,
		    autoApply : true,
		    showDropdowns : true,
		    autoUpdateInput : false,
		    opens : 'left',
		    startDate : getTodatDate(),
		    maxDate : getTodatDate(),
		    minYear : minYear,
		    maxYear : maxYear,
		    locale : {
			    cancelLabel : 'Clear'
		    }
		});
		if ($date != '') {
			$datePicker.data('daterangepicker').setStartDate(convertmyDate($date));
		}
		$datePicker.on('apply.daterangepicker', function(ev, picker)
		{
			var $this = $(this);
			var $fromDate = picker.startDate.format('YYYY-MM-DD');
			$this.val($fromDate);
			$this.attr('value', $fromDate);
		});
	}
});
function onDDStartSearch()
{
	countryId = ddCountry.getValue().value;
}
function updateImage(str, pic_link, _type)
{
	if (_type == "account_pic") {
		document.location.reload();
	}
	closeFancyBox();
}

function onCountrySelected(selPropId, selPropVal, drpInpt)
{
	window.countryCode = selPropId;
	//
	if(comboGridCity) comboGridCity.setValue({id: "", value: ""});
}
