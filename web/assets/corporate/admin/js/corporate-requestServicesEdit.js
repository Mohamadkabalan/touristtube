var dt;
var drp2;
var ddCity;
var ddAccount;
var ddCountry;
var frmValidator;
var btnsInst;
var sourceCity = generateLangURL('/corporate/SearchCity','ajax');
var sourceAccount = generateLangURL('/corporate/account/searchAccount','ajax');
var sourceCountry = generateLangURL('/corporate/SearchCountry','ajax');
$(document).ready(function () {
    ddDestinationCity = new TTAutoComplete('[data-toggle="ttautocompleteDestinationCity"]', {source: sourceCity, mapping: {value: "fullname", id: "id"}});
    ddDepartureCity = new TTAutoComplete('[data-toggle="ttautocompleteDepartureCity"]', {source: sourceCity, mapping: {value: "fullname", id: "id"}});
    ddAccount = new TTAutoComplete('[data-toggle="ttautocompleteAccount"]', {source: sourceAccount, mapping: {value: "name", id: "id"}});
    ddCountry = new TTAutoComplete('[data-toggle="ttautocompleteCountry"]', {source: sourceCountry, mapping: {value: "name", id: "id"}});
    btnsInst = new TTButtons(".tt_buttons_bar");
    frmValidator = new TTFormValidator("#formId", {msgPosition: "bottom"});
});
$(document).ready(function () {
    drp2 = new TTDateRangePicker('.daterangepicker_inputs', {
    singleDate: false,
    inputs: {
        start: '#fromDate',
        end: '#toDate'
    }
    });
});