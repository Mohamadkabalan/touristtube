var ttModal;
var btnsInst;
$(document).ready(function () {
    btnsInst = new TTButtons(".tt_buttons_bar");
    var btnsAddInst = new TTButtons(".tt_buttons_bar_add");
    ttModal = window.getTTModal("myModalZ", {});
});

function editUserProfile(id)
{
	goToLinkNew(generateLangURL('/corporate/admin/userProfiles/edit/' + id, 'empty'));
}

function unpublish(id)
{
    ttModal.confirm(Translator.trans("Are you sure you want to unpublish this user profile?"), function (btn) {
    if (btn == "ok")
        goToLinkNew(generateLangURL('/corporate/admin/userProfiles/unpublish/' + id, 'empty'));
    });
}

function publish(id)
{
    ttModal.confirm(Translator.trans("Are you sure you want to publish this user profile?"), function (btn) {
    if (btn == "ok")
        goToLinkNew(generateLangURL('/corporate/admin/userProfiles/publish/' + id, 'empty'));
    });
}

function publishedCellHandler(td,cellData, rowData, rowIndex, colIndex){
    var rowId = rowData.up__id;
    var published = rowData.up__published;
    var btnLabel;
    var action;
    //
    if( published == 1 ) {
        btnLabel = Translator.trans('Unpublish');
        action = "javascript:unpublish('" + rowId + "')";
    } else {
        btnLabel = Translator.trans('Publish');
        action = "javascript:publish('" + rowId + "')";
    }
    //
    var activeTmpl = {actionUrl: action, label: btnLabel };
    var html = window.ttUtilsInst.template($("#dataTableBtnCustom").html(), activeTmpl);
	//
    $(td).html(html);
	return html;
}

function actionCellHandler(td,cellData, rowData, rowIndex, colIndex){

	var rowId = rowData.up__id;
	// 
	var editTmpl = {actionUrl: "javascript:editUserProfile('" + rowId + "')"};
	
	var html = window.ttUtilsInst.template($("#dataTableBtnEdit").html(), editTmpl);

	$(td).html(html);
	//
	return html;
}