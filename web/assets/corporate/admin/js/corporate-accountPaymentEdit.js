var drp;
var ddAccount;
var ddUser;
var frmValidator;
var sourceAccount;
var sourceUser;
var sourceCurrency;

$(document).ready(function () {
    sourceAccount = '/corporate/account/searchAccount';
    sourceUser = '/corporate/definitions/users/searchUser';
    sourceCurrency = '/corporate/SearchCurrency';
    ddAccount = new TTAutoComplete('[data-toggle="ttautocompleteAccount"]', {source: sourceAccount, mapping: {value: "name", id: "id"}});
    ddUser = new TTAutoComplete('[data-toggle="ttautocompleteUser"]', {source: sourceUser, mapping: {value: "name", id: "id"}});
    
    frmValidator = new TTFormValidator("#formId", {msgPosition: "bottom"});

    //
    //
    var currencyOpts = {
            url: generateLangURL('/corporate/SearchCurrency','ajax'),
            searchButton: false,
            resetButton: false,
            colModel: [ 
            		{'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
	                {'dbName' : 'p.code' ,'columnName':'code','width':'80px','label':'code', 'isIdProperty': true, 'hidden': true},
	                {'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
                ]
        };
    var comboGridCurrency = new AutoCompleteComboGrid("currency", currencyOpts);   
    
    var accOpts = {
        url: generateLangURL('/corporate/account/searchAccount','ajax'),
        searchButton: false,
        resetButton: false,
        colModel: [ { 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
            {'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
            ]
    };
    var comboGridAccount = new AutoCompleteComboGrid("account", accOpts); 
    //
    //
    if ($('.account_range_picker').length > 0) {	    
	    var $datePicker = $('.account_range_picker');
	    var today = new Date();
	    var minYear = 1910;
	    var $date = $datePicker.val();
	    $datePicker.daterangepicker({
		singleDatePicker: true,
		autoApply: true,
		showDropdowns: true,
		autoUpdateInput: false,
		opens: 'left',
		minYear: minYear,
		locale: {cancelLabel: 'Clear'}
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
//
function submitForm()
{
    frmValidator.addCustomRule("#amount", "zeroCheck", Translator.trans("Amount should be greater than 0."), function(value) {
        if (value > 0) {
            return true;
        } else {
            return false;
        }
    });
    
    if(frmValidator.validate());
    $("#formId").submit();
}
//
function callBack()
{
    if(parent.paymentUpdateCallBack)  
    parent.paymentUpdateCallBack();
}