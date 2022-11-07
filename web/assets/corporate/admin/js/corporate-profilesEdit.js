var ddAccount;
var frmValidator;
var btnsInst;
var sourceAccount = generateLangURL('/corporate/account/searchAccount','ajax');
$(document).ready(function () {
    ddAccount = new TTAutoComplete('[data-toggle="ttautocompleteAccount"]', {source: sourceAccount, mapping: {value: "name", id: "id"}});
    btnsInst = new TTButtons(".tt_buttons_bar");
    frmValidator = new TTFormValidator("#formId", {msgPosition: "bottom"});
});