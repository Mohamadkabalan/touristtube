var ttModal;
var btnsInst;
$(document).ready(function () {
	btnsInst = new TTButtons(".tt_buttons_bar");
	var btnsAddInst = new TTButtons(".tt_buttons_bar_add");
	ttModal = window.getTTModal("myModalZ", {});
});
//
function deleteDepartments(accId)
{
	ttModal.confirmDelete(Translator.trans("Are you sure you want to delete this departments?"), function (btn) {
	if (btn == "delete")
		goToLinkNew(generateLangURL('/corporate/definitions/departments/delete/' + accId,'empty'));
	});
}