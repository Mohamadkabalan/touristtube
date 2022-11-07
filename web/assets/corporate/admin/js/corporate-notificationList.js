var ttModal;
var btnsInst;
//
$(document).ready(function () {
    btnsInst = new TTButtons(".tt_buttons_bar");
    var btnsAddInst = new TTButtons(".tt_buttons_bar_add");
    ttModal = window.getTTModal("myModalZ", {});
});

function editNotification(notificationId)
{
	goToLinkNew(generateLangURL('/corporate/admin/notification/edit/' + notificationId, 'empty'));
}
function deleteNotification(notificationId)
{
    ttModal.confirmDelete(Translator.trans("Are you sure you want to delete this notification?"), function (btn) {
    if (btn == "delete")
        goToLinkNew(generateLangURL('/corporate/admin/notification/delete/' + notificationId, 'empty'));
    });
}

function actionCellHandler(td,cellData, rowData, rowIndex, colIndex){

	var rowId = rowData.cn__id;
	// 
	var editTmpl = {actionUrl: "javascript:editNotification('" + rowId + "')"};
	var deleteTmpl = {actionUrl: "javascript:deleteNotification('" + rowId + "')"};

	var html = window.ttUtilsInst.template($("#dataTableBtnEdit").html(), editTmpl);
		html += $("#dataTableBtnSeparator").html();
		html += window.ttUtilsInst.template($("#dataTableBtnDelete").html(), deleteTmpl);

	$(td).html(html);
	//
	return html;
}
