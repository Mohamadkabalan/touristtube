var ttModal;
var btnsInst;
var ddUser;
var sourceUser = generateLangURL('/corporate/definitions/users/searchUser','ajax');
$(document).ready(function () {
    ttModalButton = window.getTTModal("myModalZ", {});

    ddUser = new TTAutoComplete('[data-toggle="ttautocompleteUser"]', {source: sourceUser, params: {excludeId: {varname: "parentId"}}, mapping: {value: "name", id: "id"}, events: {onSearchStart: onDDStartSearch}});
    $('#createdDate').dateRangePicker({
        autoClose : true,
        showTopbar: false,
        singleDate: true,
        singleMonth: true
    });
});

function onDDStartSearch()
{
    userId = ddUser.getValue().value;
}

function showLoader(){
    var loader = window.showTTLoader();
    setTimeout(function(){
            loader.hide();
            // >> OR
            //window.hideTTLoader();
        }, 3000
    );
}
function detailPopup(link){
    ttModal = window.getTTModal("myModalZ", {url:{href: link}, width: 1024, title: "Reservation Details"});
    ttModal.show();
}

function approveRequest(reservationId,moduleId,accountId,transactionUserId,requestServicesDetailsId){
    showLoader();
    ttModalButton.confirm(Translator.trans("Are you sure you want to approve?"), function (btn) {
                    if(btn == "ok"){
                window.location.href = generateLangURL('/corporate/approval-flow/approve/'+reservationId+'-'+moduleId+'-'+accountId+'-'+transactionUserId+'-'+requestServicesDetailsId,'corporate')
            }
    }, null, {ok:{value:Translator.trans("Yes")},cancel:{value:Translator.trans("No")}});
}

function rejectRequest(requestServicesDetailsId,transactionUserId){
    showLoader();
    ttModalButton.confirmDelete(Translator.trans("Are you sure you want to cancel?"), function (btn) {
    if (btn == "delete"){
                $.ajax({
                    url: generateLangURL('/corporate/approval-flow/reject/','ajax'),
                    data:{ requestServicesDetailsId: requestServicesDetailsId, transactionUserId: transactionUserId},
                    type: 'post',
                    success: function (res) {
                        if(res.msg == 'success'){
                            $('#paginationPages li.active a').click();
                        }else{
                            ttModalButton.alert(res.msg, null, null, {ok:{value:"close"}})
                        }
                    }
                });
            }
    },null, {delete:{value:Translator.trans("reject")},cancel:{value:Translator.trans("close")}});
}

function cancelRequest(reservationId,requestServicesDetailsId,moduleId){
    showLoader();
    ttModalButton.confirmDelete(Translator.trans("Are you sure you want to cancel?"), function (btn) {
    if (btn == "delete"){
                $.ajax({
                    url: generateLangURL('/corporate/approval-flow/cancel/','ajax'),
                    data:{ reservationId: reservationId, moduleId: moduleId},
                    type: 'post',
                    success: function (res) {
                        console.log(res);
                        if(res.success){
                            if(!res.message || res.message == ''){
                                res.message = Translator.trans("Cancellation successfully done");
                            }
                            //$('#'+requestServicesDetailsId).remove();
                            $('#paginationPages li.active a').click();
                        }else{
                            if(!res.message || res.message == ''){
                                res.message =  Translator.trans("the transaction was not cancelled"); 
                            }
                        }
                        ttModalButton.alert(res.message, null, null, {ok:{value:"close"}})
                    }
                });
            }
    },null, {delete:{value:Translator.trans("Yes")},cancel:{value:Translator.trans("close")}});
}

function filterInfo() {
    showLoader();
    var status = $('#myTabs[class=active]').attr("data-value");
    var createdBy = $("input[name=created_by]").val();
    var createdDate = $("input[name=created_date]").val();
    var types = $('#types').find(":selected").val();
    var start = 1;
    
    $.ajax({
    url: generateLangURL('/corporate/account/filterTravelApproval','ajax'),
    data: {status: status, createdBy: createdBy , createdDate: createdDate, types: types , start: start},
    type: 'post',
        success: function (data) {
            if(data.sumPreferredAccountAmout){
                $('#preferredAccountCurrency').html(data.preferredAccountCurrency);
                $('#accountPreferrredCurencyAmount').html(data.sumPreferredAccountAmout);
            }
            $('#travelApprovalInfo').html(data.travelApproval);
            $('#paginationPages').html(data.pagination);
        }
    });
}

$('#myTabs a').click(function (e) {
    e.preventDefault();
    showLoader();
    var tabObject   = $(this);
    var tabValue    = $(this).attr('data-value');
    var start       = 1;

    var createdBy = $("input[name=created_by]").val();
    var createdDate = $("input[name=created_date]").val();
    var types = $('#types').find(":selected").val();
        
        approvalFlowAjax(tabValue,start,createdBy,createdDate,types);
})
    
    $(document).on('click',".approval_pagination, .prev_pg, .next_pg, .first_pg, .last_pg" ,function(){
    showLoader();
        var $this = $(this).closest('li');
        var $rowsect = $this.closest('.row-sect');
        var data_page = $this.attr('data-page'); 
        if(data_page==0) return;
        var page   = data_page;
        
        var status = $('#myTabs[class=active]').attr("data-value");
        var createdBy = $("input[name=created_by]").val();
        var createdDate = $("input[name=created_date]").val();
        var types = $('#types').find(":selected").val();
        
        approvalFlowAjax(status,page,createdBy,createdDate,types);
        $('html,body').scrollTop(0);
    
    });
    
    function approvalFlowAjax(statusApproval,pageApproval,createdBy,createdDate,types){
        var status = statusApproval;
        var page    = pageApproval;
        $.ajax({
    url: generateLangURL('/corporate/account/filterTravelApproval','ajax'),
    data: { status:status ,start: page, createdBy: createdBy , createdDate: createdDate, types: types},
    type: 'post',
    success: function (data) {
        if(data.sumPreferredAccountAmout){
        $('#preferredAccountCurrency').html(data.preferredAccountCurrency);
        $('#accountPreferrredCurencyAmount').html(data.sumPreferredAccountAmout);
        }
        $('#travelApprovalInfo').html(data.travelApproval);
        $('#paginationPages').html(data.pagination);
    }
    });
}