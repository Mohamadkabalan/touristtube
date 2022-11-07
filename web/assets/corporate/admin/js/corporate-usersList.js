var ttModal;
window.slug = null;
window.affiliateUserId = null;
$(document).ready(function () {
    ttModal = window.getTTModal("myModalZ", {});
});
//
function editUser(userId)
{
	if(window.slug){
		goToLinkNew(generateLangURL('/corporate/definitions/users/edit-' + window.slug + '/' + userId,'empty'));	
	}else{
		goToLinkNew(generateLangURL('/corporate/definitions/users/edit/' + userId,'empty'));
	}
    
}

function deleteUser(userId)
{
	ttModal.confirmDelete(Translator.trans("Are you sure you want to suspend this User?"), function (btn) {
		if (btn == "delete"){
			if(window.slug){
				goToLinkNew(generateLangURL('/corporate/definitions/users/delete-' + window.slug + '/' + userId,'empty'));
			}else{
				goToLinkNew(generateLangURL('/corporate/definitions/users/delete/' + userId,'empty'));
			}
		}
	});
}

function disallow(id)
{
    ttModal.confirm(Translator.trans("Are you sure you want to disallow approval for user?"), function (btn) {
    if (btn == "ok"){
    	if(window.slug){
    		goToLinkNew(generateLangURL('/corporate/definitions/users/disallow-' + window.slug + '/' + id, 'empty'));	
    	}else{
    		goToLinkNew(generateLangURL('/corporate/definitions/users/disallow/' + id, 'empty'));
    	}
    }
    });
}

function allow(id)
{
    ttModal.confirm(Translator.trans("Are you sure you want to allow approval for user?"), function (btn) {
    if (btn == "ok"){
    	if(window.slug){
    		goToLinkNew(generateLangURL('/corporate/definitions/users/allow-' + window.slug + '/' + id, 'empty'));
    	}else{
    		goToLinkNew(generateLangURL('/corporate/definitions/users/allow/' + id, 'empty'));
    	}
    }
    });
}
    
function accountTransactionFilterByUser(userId)
{
	var link = generateLangURL('/corporate/account/transactions?userId=' + userId,'corporate');
    goToLinkNew(link);
}

function accountViewFilterByUser(userId){
	var link = generateLangURL('corporate/account/view/' + window.slug + '?userId=' + userId,'corporate');
    goToLinkNew(link);
}

function allowedToApproveCellHandler(td,cellData, rowData, rowIndex, colIndex){

	var rowId = rowData.cu__id;
	var allowApprove = rowData.allow_approval_all_user;
	var btnLabel;
    var action;

	if(allowApprove == 1) {
		btnLabel = Translator.trans('Disallow');
		action = "javascript:disallow('" + rowId + "')";
	} else {
		btnLabel = Translator.trans('Allow');
        action = "javascript:allow('" + rowId + "')";
	}

	var activeTmpl = {actionUrl: action, label: btnLabel };
    var html = window.ttUtilsInst.template($("#dataTableBtnCustom").html(), activeTmpl);
	
    $(td).html(html);
	return html;
}

function actionCellHandler(td,cellData, rowData, rowIndex, colIndex)
{
	var rowId = rowData.cu__id;
	// 
	var editTmpl = {actionUrl: "javascript:editUser('" + rowId + "')"};
	var deleteTmpl = {actionUrl: "javascript:deleteUser('" + rowId + "')"};
	var viewTrxTmpl = {actionUrl: "javascript:accountTransactionFilterByUser('" + rowId + "')", label: Translator.trans("Transactions") };
	
	var html = window.ttUtilsInst.template($("#dataTableBtnEdit").html(), editTmpl);
		html += $("#dataTableBtnSeparator").html();
		html += window.ttUtilsInst.template($("#dataTableBtnSuspend").html(), deleteTmpl);
		html += $("#dataTableBtnSpace").html();
		html += window.ttUtilsInst.template($("#dataTableBtnCustom").html(), viewTrxTmpl);

        if(window.slug == 'affiliate') {
            html += $("#dataTableBtnSeparator").html();
            var viewSales = {actionUrl: "javascript:viewAffiliateSales('" + rowId + "')", label: Translator.trans("Sales Persons") };
            html += window.ttUtilsInst.template($("#dataTableBtnCustom").html(), viewSales);
        } else if(window.slug == 'sales') {
            if(rowData.company__companyId !== null) {
                html += $("#dataTableBtnSeparator").html();
                var viewCompanies = {actionUrl: "javascript:viewSalesCompanies('" + rowId + "')", label: Translator.trans("Companies") };
                html += window.ttUtilsInst.template($("#dataTableBtnCustom").html(), viewCompanies);
            }
            if(rowData.agency__agencyId !== null) {
                html += $("#dataTableBtnSeparator").html();
                var viewCompanies = {actionUrl: "javascript:viewSalesAgencies('" + rowId + "')", label: Translator.trans("Agencies") };
                html += window.ttUtilsInst.template($("#dataTableBtnCustom").html(), viewCompanies);
            }
            if(rowData.rAgency__rAgencyId !== null) {
                html += $("#dataTableBtnSeparator").html();
                var viewCompanies = {actionUrl: "javascript:viewSalesRAgencies('" + rowId + "')", label: Translator.trans("Retail Agencies") };
                html += window.ttUtilsInst.template($("#dataTableBtnCustom").html(), viewCompanies);
            }
        } else if(window.slug == 'company' || window.slug == 'agency' || window.slug == 'retail-agency') {
            html += $("#dataTableBtnSeparator").html();
            var viewUsersTmpl = {actionUrl: "javascript:accountViewFilterByUser('" + rowId + "')", label: Translator.trans("View Users") };
            html += window.ttUtilsInst.template($("#dataTableBtnCustom").html(), viewUsersTmpl);
        }

	$(td).html(html);
	//
	return html;
}

function viewAffiliateSales(affiliateUserId)
{
	var link = generateLangURL('/corporate/definitions/users?affiliateUserId=' + affiliateUserId, 'empty');
    goToLinkNew(link);
}

function viewSalesCompanies(salesUserId)
{
	var link = generateLangURL('/corporate/account/company?salesUserId=' + salesUserId, 'empty');
    goToLinkNew(link);
}

function viewSalesAgencies(salesUserId)
{
	var link = generateLangURL('/corporate/account/agency?salesUserId=' + salesUserId, 'empty');
    goToLinkNew(link);
}

function viewSalesRAgencies(salesUserId)
{
	var link = generateLangURL('/corporate/account/retail-agency?salesUserId=' + salesUserId, 'empty');
    goToLinkNew(link);
}
