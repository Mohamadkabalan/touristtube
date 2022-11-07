window.accountId = null;
window.fromDate = null;
window.accountTypeId = null;
window.accountId = null;

$(document).ready(function () {
    $("#btnpopup").fancybox();
});

function executeFilter()
{
    showLoader();

    if (window.accountId == '')
    window.accountId = null;
    if (window.accountTypeId == '')
    window.accountTypeId = null;
    if (window.fromDate == '')
    window.fromDate = null;
    if (window.toDate == '')
    window.toDate = null;

    window.reloadTable();

    $.ajax({
    url: generateLangURL('/corporate/accounting/payment/totals', 'ajax'),
    data: {
        // fromDate: window.fromDate,
        // toDate: window.toDate,
        accountId: window.accountId,
        accounTypeId: window.accountTypeId
    },
    type: 'post',
    success: function (data)
    {
        var totalFbcAmt = window.formatAmount(data.totalFbc);
        var totalSbcAmt = window.formatAmount(data.totalSbc);
        var totalDueAmount = window.formatAmount(data.dueAmount);

        $("#FBCCurrency").html(data.amountFBCCurrency);
        $("#totalFbcAmt").html(totalFbcAmt);
        $("#SBCCurrency").html(data.amountSBCCurrency);
        $("#totalSbcAmt").html(totalSbcAmt);
        $("#preferredCurrency").html(data.preferredCurrency);
        $("#dueAmount").html(totalDueAmount);
    },
    error: function (error) {
        console.log(Translator.trans('Error loading Payment Totals'));
        console.log(err);
    }
    });
}

function actionCellHandler(td, cellData, rowData, rowIndex, colIndex)
{

    var rowId = rowData.cap__id;

    var editTmpl = {
    actionUrl: "javascript:openPaymentForm('" + rowId + "')"
    };
    var deleteTmpl = {
    actionUrl: "javascript:deleteAccountPayment('" + rowId + "')",
    label: Translator.trans('Delete')
    };

    var html = window.ttUtilsInst.template($("#dataTableBtnEdit").html(), editTmpl);
    html += $("#dataTableBtnSeparator").html();
    html += window.ttUtilsInst.template($("#dataTableBtnCustom").html(), deleteTmpl);

    $(td).html(html);

    return html;
}

function amountCellHandler(td, cellData, rowData, rowIndex, colIndex)
{
    var currency = rowData.cap__currency_code;
    cellData = window.formatAmount(cellData);

    $(td).html(currency + ' ' + cellData);
}

function preferredAmountCellHandler(td, cellData, rowData, rowIndex, colIndex)
{
    var preferredCurrency = rowData.ca__preferred_currency;
    cellData = window.formatAmount(cellData);

    $(td).html(preferredCurrency + ' ' + cellData);
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