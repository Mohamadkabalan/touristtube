var ttModal;
var btnsInst;
window.slug = null;
window.salesUserId = null;
$(document).ready(function () {
    btnsInst = new TTButtons(".tt_buttons_bar");
    var btnsAddInst = new TTButtons(".tt_buttons_bar_add");
    ttModal = window.getTTModal("myModalZ", {});
});
//
function editAccount(accId)
{
    if(window.slug != null) {
        goToLinkNew(generateLangURL('/corporate/account/edit-' + window.slug + '/' + accId, 'empty'));
    } else {
        goToLinkNew(generateLangURL('/corporate/admin/account/edit/' + accId, 'empty'));
    }
}
function deleteAccount(accId)
{
    ttModal.confirmDelete(Translator.trans("Are you sure you want to delete this account?"), function (btn) {
	if (btn == "delete")
		if(window.slug != null) {
			var url = generateLangURL('/corporate/account/delete/' + accId + '?slug=' + window.slug, 'empty')
		} else {
			var url = generateLangURL('/corporate/account/delete/' + accId, 'empty')
		}
        goToLinkNew(url);
    });
}
function accountTransactionFilterByAccount(accountId)
{
    var link = generateLangURL('/corporate/account/transactions?accountId=' + accountId,'corporate');
    goToLinkNew(link);
}

function actionCellHandler(td,cellData, rowData, rowIndex, colIndex){

    var rowId = rowData.ca__id;
    // 
    var editTmpl = {actionUrl: "javascript:editAccount('" + rowId + "')"};
    var deleteTmpl = {actionUrl: "javascript:deleteAccount('" + rowId + "')"};
    var viewTrxTmpl = {actionUrl: "javascript:accountTransactionFilterByAccount('" + rowId + "')", label: Translator.trans("Transactions") };

    var html = window.ttUtilsInst.template($("#dataTableBtnEdit").html(), editTmpl);
        html += $("#dataTableBtnSeparator").html();
        html += window.ttUtilsInst.template($("#dataTableBtnSuspend").html(), deleteTmpl);
        html += $("#dataTableBtnSpace").html();
        html += window.ttUtilsInst.template($("#dataTableBtnCustom").html(), viewTrxTmpl);

    $(td).html(html);
    //
    return html;
}
