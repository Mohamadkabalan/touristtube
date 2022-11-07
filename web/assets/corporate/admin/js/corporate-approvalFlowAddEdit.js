var parentId = null;
var userId = null;
var drp;
var ddUser;
var ddParent;
var frmValidator;
var sourceUser;
var sourceParent;
var sourceOther;
$(document).ready(function () {
    sourceUser = generateLangURL('/corporate/definitions/users/searchUser','ajax');
    sourceParent = generateLangURL('/corporate/definitions/users/searchUser','ajax');
    sourceOther = generateLangURL('/corporate/definitions/users/searchUser','ajax');
    ddUser = new TTAutoComplete('[data-toggle="ttautocompleteUser"]', {source: sourceUser, params: {excludeId: {varname: "parentId"}}, mapping: {value: "name", id: "id"}, events: {onSearchStart: onDDStartSearch}});
    ddParent = new TTAutoComplete('[data-toggle="ttautocompleteParent"]', {source: sourceParent, params: {excludeId: {varname: "userId"}}, mapping: {value: "name", id: "id"}, events: {onSearchStart: onDDStartSearch}});
    ddOther = new TTAutoComplete('[data-toggle="ttautocompleteOther"]', {source: sourceOther, params: {excludeId: {varname: "userId"}}, mapping: {value: "name", id: "id"}, events: {onSearchStart: onDDStartSearch}});
    frmValidator = new TTFormValidator("#formId", {msgPosition: "bottom"});
});
//
function onDDStartSearch()
{
    parentId = ddParent.getValue().value;
    userId = ddUser.getValue().value;
}
$("#approvalOther").change(function() {
    if(this.checked) {
    $('.hidenInput').show();
    }else{
    $('.hidenInput').hide();
    }
});