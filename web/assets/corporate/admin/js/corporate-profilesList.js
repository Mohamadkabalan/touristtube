var ttModal;
var btnsInst;
$(document).ready(function () {
    btnsInst = new TTButtons(".tt_buttons_bar");
    var btnsAddInst = new TTButtons(".tt_buttons_bar_add");
    ttModal = window.getTTModal("myModalZ", {});
});
//
function deleteProfile(accId)
{
    ttModal.confirmDelete(Translator.trans("Are you sure you want to delete this profile?"), function (btn) {
    if (btn == "delete")
        goToLinkNew(generateLangURL('/corporate/admin/profiles/delete/' + accId,'empty'));
    });
}