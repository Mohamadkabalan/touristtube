var ttModal;
window.accountId = null;
window.fromDate = null;
window.toDate = null;
$(document).ready(function(){
    
    ttModal = window.getTTModal("myModalZ", {});
    
    var accOpts = {
    		url: generateLangURL('/corporate/account/searchAccount','ajax'),
    		searchButton: false,
    		resetButton: false,
    		colModel: [	
    					{ 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
    					{'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
    		    ]
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
			    autoApply: true,
			    showDropdowns: true,
			    autoUpdateInput: false,
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
		})
	}
});

function onAccountFilterSelected(selPropId, selPropVal, drpInpt)
{
	window.accountId = selPropId;
}

function filterInfo()
{
	if(window.accountId == '') window.accountId = null;
    //
    window.reloadTable();
    //
}
//
function deleteAccountPayment(accId)
{
	ttModal.confirmDelete(Translator.trans("Are you sure you want to delete this payment?"), function(btn)
	{
		if (btn == "delete")
			goToLinkNew('/corporate/accounting/payment/delete/' + accId);
	});
}

function openPaymentForm(payId)
{
	var path = "add";
	if (payId && payId != "")
		path = "edit/" + payId;

	goToLinkNew("/corporate/accounting/payment/" + path);


	/*
	var path = "add";
	if (payId && payId != "")
		path = "edit/" + payId;
	//
	ttModal.show({
	    width : "600px",
	    height : "600px",
	    url : {
	        href : "/corporate/accounting/payment/" + path,
	        inFrame : true
	    },
	    title : "Payment",
	    content : " ",
	    footer : " ",
	    buttons : [ {
	        id : 'save',
	        value : Translator.trans('save'),
	        type : 'validate',
	        action : validatePayment
	    }, {
	        id : 'cancel',
	        value : Translator.trans('cancel'),
	        type : 'close',
	        action : validatePayment,
	        closeModal : true
	    } ],
	    cls : null,
	    attr : null
	});
	*/
}

function validatePayment(btn)
{
	if (btn == "save") {
		if (ttModal.getIframe() && ttModal.getIframe().length > 0) {
			if (ttModal.getIframe()[0].contentWindow.submitForm) {
				ttModal.getIframe()[0].contentWindow.submitForm();
			}
		}
	}
}

function paymentUpdateCallBack()
{
	filterInfo();
	ttModal.hide();
}

function actionCellHandler(td, cellData, rowData, rowIndex, colIndex)
{

	var rowId = rowData.cap__id;

	var editTmpl = {
		actionUrl : "javascript:openPaymentForm('" + rowId + "')"
	};
	var deleteTmpl = {
	    actionUrl : "javascript:deleteAccountPayment('" + rowId + "')",
	    label : Translator.trans('Delete')
	};

	var html = window.ttUtilsInst.template($("#dataTableBtnEdit").html(), editTmpl);
	html += $("#dataTableBtnSeparator").html();
	html += window.ttUtilsInst.template($("#dataTableBtnCustom").html(), deleteTmpl);

	$(td).html(html);

	return html;
}

function amountCellHandler(td,cellData, rowData, rowIndex, colIndex)
{ 
    var currency = rowData.cap__currency_code;
    cellData = window.formatAmount(cellData);

    $(td).html(currency + ' ' + cellData);
}

function preferredAmountCellHandler(td,cellData, rowData, rowIndex, colIndex)
{ 
	var preferredCurrency = rowData.ca__preferred_currency;
	cellData = window.formatAmount(cellData);
	
	$(td).html(preferredCurrency + ' ' + cellData);
}