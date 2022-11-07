var ttModal;
var btnsInst;
$(document).ready(function () {
    btnsInst = new TTButtons(".tt_buttons_bar");
    var btnsAddInst = new TTButtons(".tt_buttons_bar_add");
    ttModal = window.getTTModal("myModalZ", {});
});

function editAgency(agencyId)
{
	goToLinkNew(generateLangURL('/corporate/admin/agencies/edit/' + agencyId, 'empty'));
}
function deleteAgency(agencyId)
{
    ttModal.confirmDelete(Translator.trans("Are you sure you want to delete this agency?"), function (btn) {
    if (btn == "delete")
        goToLinkNew(generateLangURL('/corporate/admin/agencies/delete/' + agencyId, 'empty'));
    });
}

function actionCellHandler(td,cellData, rowData, rowIndex, colIndex){

	var rowId = rowData.ca__id;
	// 
	var editTmpl = {actionUrl: "javascript:editAgency('" + rowId + "')"};
	var deleteTmpl = {actionUrl: "javascript:deleteAgency('" + rowId + "')"};
	
	var html = window.ttUtilsInst.template($("#dataTableBtnEdit").html(), editTmpl);
		html += $("#dataTableBtnSeparator").html();
		html += window.ttUtilsInst.template($("#dataTableBtnSuspend").html(), deleteTmpl);

	$(td).html(html);
	//
	return html;
}