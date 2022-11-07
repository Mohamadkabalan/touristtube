var ttModal;
var btnsInst;
$(document).ready(function () {
    btnsInst = new TTButtons(".tt_buttons_bar");
    var btnsAddInst = new TTButtons(".tt_buttons_bar_add");
    ttModal = window.getTTModal("myModalZ", {});
});

function editNotification(accTypeId)
{
	goToLinkNew(generateLangURL('/corporate/admin/accountType/edit/' + accTypeId, 'empty'));
}

function deactivateAcctType(accTypeId)
{
    ttModal.confirm(Translator.trans("Are you sure you want to deactivate this account type?"), function (btn) {
    if (btn == "ok")
        goToLinkNew(generateLangURL('/corporate/admin/accountType/deactivate/' + accTypeId, 'empty'));
    });
}

function activateAcctType(accTypeId)
{
    ttModal.confirm(Translator.trans("Are you sure you want to activate this account type?"), function (btn) {
    if (btn == "ok")
        goToLinkNew(generateLangURL('/corporate/admin/accountType/activate/' + accTypeId, 'empty'));
    });
}

function statusCellHandler(td,cellData, rowData, rowIndex, colIndex){
    var rowId = rowData.cat__id;
    var status = rowData.is_active || "";
    var btnLabel;
    var action;
    //
    if( status.toLowerCase() == 'active') {
        btnLabel = Translator.trans('Is Activate');
        action = "javascript:deactivateAcctType('" + rowId + "')";
    } else {
        btnLabel = Translator.trans('Is Inactive');
        action = "javascript:activateAcctType('" + rowId + "')";
    }
    //
    var activeTmpl = {actionUrl: action, label: btnLabel };
    var html = window.ttUtilsInst.template($("#dataTableBtnCustom").html(), activeTmpl);
	//
    $(td).html(html);
	return html;
}

function actionCellHandler(td,cellData, rowData, rowIndex, colIndex){

    var rowId = rowData.cat__id;
    
	var editTmpl = {actionUrl: "javascript:editNotification('" + rowId + "')"};

	var html = window.ttUtilsInst.template($("#dataTableBtnEdit").html(), editTmpl);

    $(td).html(html);

	return html;
}
