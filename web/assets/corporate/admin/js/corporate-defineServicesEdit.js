var frmValidator;
var btnsInst;
$(document).ready(function () {
    btnsInst = new TTButtons(".tt_buttons_bar");
    frmValidator = new TTFormValidator("#formId", {msgPosition: "bottom"});
});