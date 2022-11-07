var ttModal;
window.userId=null;
window.accountId=null;
window.fromDate = null;
window.toDate = null;
window.currencyCode = null;
window.serviceType = null;

$(document).ready(function () {
    ttModal = window.getTTModal("myModalZ", {});
    //
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
    //
    var currencyOpts = {
            url: generateLangURL('/corporate/SearchCurrency','ajax'),
            searchButton: false,
            resetButton: false,
            colModel: [ 
            		{ 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
	                { 'dbName' : 'p.code' ,'columnName':'code','width':'80px','label':'code', 'isIdProperty': true, 'hidden': true},
	                {'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
                ]
        };
    var comboGridCurrency = new AutoCompleteComboGrid("currency", currencyOpts);
    var comboGridCurrency = new AutoCompleteComboGrid("currency_2", currencyOpts);   
    //
    var userOpts = {
            url: generateLangURL('/corporate/users/searchUser','ajax'),
            searchButton: false,
            resetButton: false,
            colModel: [ 
                    { 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
                    {'dbName' : 'p.fullname' ,'columnName':'fullname','width':'250px','label':'fullname','isProperty': true}
                ]
        };
    var comboGridUser = new AutoCompleteComboGrid("user", userOpts);
    var comboGridUser = new AutoCompleteComboGrid("user_2", userOpts);   
    //
    $("[id^=types]").on("change", function(a, b, c){
    	window.serviceType = $(this).val();
    	if(window.serviceType==0 || window.serviceType=="0") window.serviceType = "";
    });
    //
    if ($('.account_range_picker').length > 0) {
	$account_range_picker = $(document).find('.account_range_picker');
    $account_range_picker.each(function () {
            var $datePicker = $(this);
            isSingle = false;

            $datePicker.daterangepicker({
                singleDatePicker: isSingle,
                autoApply: true,
                autoUpdateInput: false,
                opens: 'left',
                maxDate: getTodatDate(),
                startDate: getTodatDate(),
                locale: {cancelLabel: 'Clear'}
            });

            $datePicker.on('apply.daterangepicker', function (ev, picker) {
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
});

function openModal(id,moduleId)
{
    $.ajax({
	    url: generateLangURL('/corporate/admin/showAccountTransactionDetail','ajax'),
	    data: {id:id, moduleId:moduleId},
	    type: 'post',
	    success: function (data) {
	        ttModal.show({title: Translator.trans("Account transaction of #") + id, content: data.htmlDetail, footer:""});
	    }
    });
}

function actionCellHandler(td,cellData, rowData, rowIndex, colIndex){

    var rowId = rowData.cat__id;
    var moduleId = rowData.cat__module_id;
	// 
	var viewTrxTmpl = {actionUrl: "javascript:openModal('" + rowId + "', '" + moduleId + "')", label: rowId };

	var html = window.ttUtilsInst.template($("#dataTableBtnCustom").html(), viewTrxTmpl);

	$(td).html(html);
	//
	return html;
}

function preferredAmountCellHandler(td,cellData, rowData, rowIndex, colIndex)
{ 
    var preferredCurrency = rowData.ca__preferred_currency;
    cellData = formatAmount(cellData);

    $(td).html(preferredCurrency + ' ' + cellData);
}

function onAccountFilterSelected(selPropId, selPropVal, drpInpt)
{
	window.accountId = selPropId;
}

function onCurrencyFilterSelected(selPropId, selPropVal, drpInpt)
{
	window.currencyCode = selPropId;
}

function onUserFilterSelected(selPropId, selPropVal, drpInpt)
{
	window.userId = selPropId;
}

function filterInfo()
{
	if(!window.serviceType) window.serviceType = "";
	if(window.currencyCode == '') window.currencyCode = null;
	if(window.accountId == '') window.accountId = null;
    if(window.userId == '') window.userId = null;

    //
    window.reloadTable();
    //
}

function accountTransactionsFooterCallBack(row, data, start, end, display)
{
	//TODO Make an AJAX call to get the total amounts of the data table filtered records (for all pages not current one only)
	//We need to send the same paramters to the same Data table query without the pagination, then execute a sum on the FBC, SBC and account amount fields
	//Then return a JSON to fill the below total values in footer
	//window.serviceType | window.currencyCode | window.accountId | window.userId
	//
	//
	var api = this.api();
	//
	//window.totalFbcOverlay = new TTOverlay("totalFbc");
	//window.totalSbcOverlay = new TTOverlay("totalSbc");
	//window.totalPreferredOverlay = new TTOverlay("totalPreferred");
	//
	var totalFbcIdx = window.getCellIndexById("amountFBC_ID");
	var totalSbcIdx = window.getCellIndexById("amountSBC_ID");
	var totalPreferredIdx = window.getCellIndexById("acctAmt_ID");
	//
	//
	var fbcAmount = formatAmount(0);
	var sbcAmount = formatAmount(0);
    var preferredAmount = formatAmount(0);
	//
	$.ajax("/corporate/account/transactions/datatablesum", 
			{
				data:{
                    serviceType: window.serviceType,
                    currencyCode: window.currencyCode,
                    accountId: window.accountId,
                    userId: window.userId,
                    fromDate: window.fromDate,
                    toDate: window.toDate
				}
				,beforeSend: function(jqXHR, settings )
							{
								//window.totalFbcOverlay.show();
								//window.totalSbcOverlay.show();
								//window.totalPreferredOverlay.show();
								//
							    $( api.column( totalFbcIdx ).footer() ).html("");
							    $( api.column( totalSbcIdx ).footer() ).html("");
							    $( api.column( totalPreferredIdx ).footer() ).html("");
							}
				,success: function( data, textStatus, jqXHR )
						{
							fbcAmount = formatAmount(data.sumAmountFBC);
							sbcAmount = formatAmount(data.sumAmountSBC);
                            preferredAmount = formatAmount(data.sumAccountAmount);
                            
                            // Update footer
                            $( api.column( totalFbcIdx ).footer() ).html(fbcAmount);
                            $( api.column( totalSbcIdx ).footer() ).html(sbcAmount);
                            $( api.column( totalPreferredIdx ).footer() ).html(preferredAmount);

                            /**set due amount */
                            $('.price_fb').text(formatAmount(data.sumAccountAmount));
						}
				,complete: function( jqXHR, textStatus )
						{
							//window.totalFbcOverlay.hide();
							//window.totalSbcOverlay.hide();
							//window.totalPreferredOverlay.hide();
							//
						}
			}
	);
	//
    // Update footer
    $( api.column( totalFbcIdx ).footer() ).html(fbcAmount);
    $( api.column( totalSbcIdx ).footer() ).html(sbcAmount);
    $( api.column( totalPreferredIdx ).footer() ).html(preferredAmount);
}

function soaRowHandler(dom, data, index)
{
	if(data && data.cafs__id == cancelledStat) {
		$(dom).css({"background-color": "#ffc1c1"});
	}
}