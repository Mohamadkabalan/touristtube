var ddAccount;
var drp;
var frmValidator;
var sourceAccount;
var sourceUser;
$(document).ready(function () {
    sourceAccount = generateLangURL('/corporate/account/searchAccount','ajax');
    sourceUser = generateLangURL('/corporate/definitions/users/searchUser','ajax');
    ddAccount = new TTAutoComplete('[data-toggle="ttautocompleteAccount"]', {source: sourceAccount, mapping: {value: "name", id: "id"}});
    ddUser = new TTAutoComplete('[data-toggle="ttautocompleteUser"]', {source: sourceUser, mapping: {value: "name", id: "id"}}); 
    frmValidator = new TTFormValidator("#formId", {msgPosition: "bottom"});
    if ($('.account_range_picker').length > 0) {
	if ($('.account_range_picker').hasClass('picker_single')) {
	    isSingle = true;
	}
	var $datePicker = $('.account_range_picker');
	var today = new Date();
	var minYear = 1910;
	var $date = $datePicker.val();
	$datePicker.daterangepicker({
	    singleDatePicker: isSingle,
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