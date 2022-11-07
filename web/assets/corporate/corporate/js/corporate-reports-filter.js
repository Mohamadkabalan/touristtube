var ttModal;
window.fromDate = null;
window.toDate = null;
window.accountTypeId = null;
window.accountId = null;

$(document).ready(function()
{
	ttModalButton = window.getTTModal("myModalZ", {});

	var accountTypeOpts = {
	    url : generateLangURL('/corporate/users/searchUserProfile', 'ajax'),
	    searchButton : false,
	    resetButton : false,
	    colModel : [ {
	        'dbName' : 'p.id',
	        'columnName' : 'id',
	        'width' : '80px',
	        'label' : 'id',
	        'isIdProperty' : true,
	        'hidden' : true
	    }, {
	        'dbName' : 'p.name',
	        'columnName' : 'name',
	        'width' : '250px',
	        'label' : 'name',
	        'isProperty' : true
	    } ]
	};
	var comboGridAccountType = new AutoCompleteComboGrid("accountType", accountTypeOpts);
	var comboGridAccountType = new AutoCompleteComboGrid("accountType_2", accountTypeOpts);

	var accOpts = {
	    url : generateLangURL('/corporate/account/searchAccount', 'ajax'),
	    searchButton : false,
	    resetButton : false,
	    colModel : [ {
	        'dbName' : 'p.id',
	        'columnName' : 'id',
	        'width' : '80px',
	        'label' : 'id',
	        'isIdProperty' : true,
	        'hidden' : true
	    }, {
	        'dbName' : 'p.name',
	        'columnName' : 'name',
	        'width' : '250px',
	        'label' : 'name',
	        'isProperty' : true
	    } ]
	};
	var comboGridAccount = new AutoCompleteComboGrid("account", accOpts);
	var comboGridAccount2 = new AutoCompleteComboGrid("account_2", accOpts);

	if ($('.account_range_picker').length > 0) {
		$account_range_picker = $(document).find('.account_range_picker');
		$account_range_picker.each(function()
		{
			var $datePicker = $(this);
			isSingle = false;

			$datePicker.daterangepicker({
			    singleDatePicker : isSingle,
			    autoApply : true,
			    showDropdowns : true,
			    autoUpdateInput : false,
			    opens : 'left',
			    maxDate : getTodatDate(),
			    startDate : getTodatDate(),
			    locale : {
				    cancelLabel : 'Clear'
			    }
			});

			$datePicker.on('apply.daterangepicker', function(ev, picker)
			{
				var $this = $(this);
				var $fromDate = picker.startDate.format('YYYY-MM-DD');
				var $fromDateStan = picker.startDate.format('MM-DD-YYYY');
				var $toDate = picker.endDate.format('YYYY-MM-DD');
				var $toDateStan = picker.endDate.format('MM-DD-YYYY');

				var $thisBrother = '';
				if ($this.hasClass("fromDate")) {
					$this.val($fromDate);
					$this.attr('value', $fromDate);
					$thisBrother = $this.closest('.dateRangeContainer').find('.toDate');
					$thisBrother.val($toDate);
					$thisBrother.attr('value', $toDate);
				} else if ($this.hasClass("toDate")) {
					$this.val($toDate);
					$this.attr('value', $toDate);
					$thisBrother = $this.closest('.dateRangeContainer').find('.fromDate');
					$thisBrother.val($fromDate);
					$thisBrother.attr('value', $fromDate);
				}
				$thisBrother.data('daterangepicker').setStartDate($fromDateStan);
				$thisBrother.data('daterangepicker').setEndDate($toDateStan);
				//
				window.fromDate = $fromDate;
				window.toDate = $toDate;
			});

			var $this = $datePicker;
			if ($this.hasClass("fromDate")) {
				$fromDate = $this.val();
				$thisBrother = $this.closest('.dateRangeContainer').find('.toDate');
				$toDate = $thisBrother.val();
			} else if ($this.hasClass("toDate")) {
				$toDate = $this.val();
				$thisBrother = $this.closest('.dateRangeContainer').find('.fromDate');
				$fromDate = $thisBrother.val();
			}
			if ($fromDate != '') {
				$this.data('daterangepicker').setStartDate(convertmyDate($fromDate));
			}
			if ($toDate != '') {
				$this.data('daterangepicker').setEndDate(convertmyDate($toDate));
			}
		});
	}
	//

	$("button[id^=searchFilter]").on("click", function()
	{
		executeFilter();
	});
});

function onAccountTypeFilterSelected(selPropId, selPropVal, drpInpt)
{
	window.accountTypeId = selPropId;
}

function onAccountFilterSelected(selPropId, selPropVal, drpInpt)
{
	window.accountId = selPropId;
}

function showLoader()
{
	var loader = window.showTTLoader();
	setTimeout(function()
	{
		loader.hide();
		$('.modal-backdrop').remove();
		$('.modal').remove();
		$(document.body).removeAttr('class style');
		// >> OR
		// window.hideTTLoader();
	}, 3000);
}
