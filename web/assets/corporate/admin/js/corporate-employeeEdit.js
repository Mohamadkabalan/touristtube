var drp;
var drp2;
var ddCity;
var ddAccount;
var ddCountry;
var ddDepartment;
var ddUser;
var frmValidator;
var btnsInst;
var sourceCity = generateLangURL('/corporate/SearchCity','ajax');
var sourceAccount = generateLangURL('/corporate/account/searchAccount','ajax');
var sourceCountry = generateLangURL('/corporate/SearchCountry','ajax');
var sourceDepartment = generateLangURL('/corporate/definitions/searchDepartment','ajax');
var sourceUser = generateLangURL('/corporate/definitions/users/searchUser','ajax');
$(document).ready(function () {
    ddCity = new TTAutoComplete('[data-toggle="ttautocompleteCity"]', {source: sourceCity, mapping: {value: "fullname", id: "id"}});
    ddAccount = new TTAutoComplete('[data-toggle="ttautocompleteAccount"]', {source: sourceAccount, mapping: {value: "name", id: "id"}});
    ddCountry = new TTAutoComplete('[data-toggle="ttautocompleteCountry"]', {source: sourceCountry, mapping: {value: "name", id: "id"}});
    ddDepartment = new TTAutoComplete('[data-toggle="ttautocompleteDepartment"]', {source: sourceDepartment, mapping: {value: "name", id: "id"}});
    ddUser = new TTAutoComplete('[data-toggle="ttautocompleteUser"]', {source: sourceUser, mapping: {value: "name", id: "id"}})
    btnsInst = new TTButtons(".tt_buttons_bar");
    frmValidator = new TTFormValidator("#formId", {msgPosition: "bottom"});
    
    drp = new TTDateRangePicker('[data-toggle="daterangepicker"]', {
        autoClose : true,
        singleDate : true,
        format : 'MM/DD/YYYY',
        singleMonth : true, //'auto',
        monthSelect : false,
        yearSelect : true
    });
    
    drp2 = new TTDateRangePicker('[data-toggle="daterangepicker2"]', {
        autoClose : true,
        singleDate : true,
        format : 'MM/DD/YYYY',
        singleMonth : true, //'auto',
        monthSelect : false,
        yearSelect : true
    });

});