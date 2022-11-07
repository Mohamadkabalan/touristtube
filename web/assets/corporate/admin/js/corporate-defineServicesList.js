var ttModal;
var btnsInst;
$(document).ready(function () {
    btnsInst = new TTButtons(".tt_buttons_bar");
    var btnsAddInst = new TTButtons(".tt_buttons_bar_add");
    ttModal = window.getTTModal("myModalZ", {});
});
//
function deleteService(accId)
{
    ttModal.confirmDelete(Translator.trans("Are you sure you want to delete this Service?"), function (btn) {
    if (btn == "delete")
        goToLinkNew(generateLangURL('/corporate/definitions/services/delete/' + accId,'empty'));
    });
}