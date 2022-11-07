var ttModal;
window.accountId = null;
window.fromDate = null;
window.accounTypetId = null;
window.accountId = null;

$(document).ready(function ()
{
    ttModal = window.getTTModal("myModalZ", {});
});

function executeFilter()
{
    showLoader();
    if (window.accountId == '')
	window.accountId = null;
    if (window.accounTypetId == '')
	window.accounTypetId = null;

    window.reloadTable();
}

function openModal(id,moduleId)
{
    $.ajax({
	    url: generateLangURL('/corporate/admin/showAccountTransactionDetail','ajax'),
	    data: {accountId:id, moduleId:moduleId},
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