var ttModal;
var ddCurrency;
var ddUser;
var drp2;
var btnsInst;
var sourceCurrency = generateLangURL('/corporate/SearchCurrency','ajax');
var sourceUser = '/corporate/definitions/users/searchUser';
$(document).ready(function () {
    ttModalButton = window.getTTModal("myModalZ", {});
    ddCurrency = new TTAutoComplete('[data-toggle="ttautocompleteCurrency"]', {source: sourceCurrency, mapping: {value: "name", id: "code"}});
    ddUser = new TTAutoComplete('[data-toggle="ttautocompleteUser"]', {source: sourceUser, mapping: {value: "name", id: "id"}});
    drp2 = new TTDateRangePicker('.daterangepicker_inputs', {
    singleDate: false,
    format : 'MM/DD/YYYY',
    inputs: {
        start: '#fromDate',
        end: '#toDate' 
    }
    });
});

function showLoader(){
    var loader = window.showTTLoader();
    setTimeout(function(){
            loader.hide();
            // >> OR
            //window.hideTTLoader();
        }, 3000
    );
}

function filterInfo() {
        var fromDay = $("input[name=fromDate]").val();
    var toDay = $("input[name=toDate]").val();
        var accountId = $("input[name=accountId]").val();
        var userId = $("input[name=userId]").val();
    var currencyCode = $("input[name=currencyCode]").val();
    var types = $('#types').find(":selected").val();
        var start = 0;
    $.ajax({
    url: generateLangURL('/corporate/admin/filterMyBookings','ajax'),
    data: {fromDay: fromDay , toDay: toDay, accountId: accountId, userId: userId, currencyCode: currencyCode, types: types , start: start},
    type: 'post',
            success: function (data) {
                $('#aprovalFlowList').html(data.allApprovalFlow);
                if(data.pagination){
                    $('#paginationPages').html(data.pagination);
                }else{
                    $('#paginationPages').html("");
                }
            }
    });
}
    
    $(document).on('click',".approval_pagination, .prev_pg, .next_pg, .first_pg, .last_pg" ,function(){
    showLoader();
        var $this = $(this).closest('li');
        var $rowsect = $this.closest('.row-sect');
        var data_page = $this.attr('data-page'); 
        if(data_page==0) return;
        var page   = data_page;
        myBookingPaginatioAjax(page);
        
        $('html,body').scrollTop(0);
    
    });
    
    function myBookingPaginatioAjax(pageApproval){
        var page    = pageApproval;
        $.ajax({
    url: generateLangURL('/corporate/admin/filterMyBookings','ajax'),
    data: { start: page},
    type: 'post',
    success: function (data) {
                $('#aprovalFlowList').html(data.allApprovalFlow);
                if(data.pagination){
                    $('#paginationPages').html(data.pagination);
                }else{
                    $('#paginationPages').html("");
                }
    }
    });
}
    
function detailPopup(link){
    ttModal = window.getTTModal("myModalZ", {url:{href: link}, width: 1024, title: "Reservation Details"});
    ttModal.show();
}