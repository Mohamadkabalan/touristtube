var datePicker;
var ttModal;
var ttModalButton;
var loader;

window.userId=null;
window.accountId=null;
window.fromDate = null;
window.toDate = null;
window.serviceType = null;
window.bookingStatus = 1;//default status to pending


$(document).ready(function () {

    ttModalButton = window.getTTModal("myModalZ", {});
    //
    //
    var accOpts = {
    		url: generateLangURL('/corporate/account/searchAccount','ajax'),
    		searchButton: false,
    		resetButton: false,
    		colModel: [	
    					{ 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
    					{'dbName' : 'p.name' ,'columnName':'name','width':'250px','label':'name','isProperty': true}
    		    ]
        };
    var comboGridAccount = new AutoCompleteComboGrid("account", accOpts);
    var comboGridAccount2 = new AutoCompleteComboGrid("account_2", accOpts);
    //
    var userOpts = {
            url: generateLangURL('/corporate/users/searchUser','ajax'),
            searchButton: false,
            resetButton: false,
            colModel: [ 
                    { 'dbName' : 'p.id' ,'columnName':'id','width':'80px','label':'id', 'isIdProperty': true, 'hidden': true},
                    {'dbName' : 'p.fullname' ,'columnName':'fullname','width':'250px','label':'fullname','isProperty': true}
                ]
        };
    var comboGridUser = new AutoCompleteComboGrid("user", userOpts);
    var comboGridUser = new AutoCompleteComboGrid("user_2", userOpts);   
    //
    //
    $("[id^=types_]").on("change", function(){
    	var isChecked = $(this).prop('checked');
    	var id = $(this).attr('id');
    	var value = $(this).val();
    	var suffix = window.suffix || $(this).attr('suffix');
    	var selector = "[id^=types" + suffix + "]";

    	//
    	if(value == ""){
    		if(isChecked) $(selector).prop('checked', "true");
    		else {
    			$(selector).prop('checked', false);
    			$(selector).removeProp('checked');
    		}
    	}

        var selectedTypes = $(selector + ":checked").not("[id$=all]");

    	window.serviceType = [];

    	for(var i = 0 ; i < selectedTypes.length; i++){
            type = selectedTypes[i];
            window.serviceType.push($(type).val());
        }

        window.serviceType = window.serviceType.join(",");

    	if(window.serviceType==0 || window.serviceType=="0") window.serviceType = "";
    });
    //
    $("[id$=_status" + window.suffix + "]").on("change", function(){
    	window.bookingStatus = $(this).val();
    	if(window.bookingStatus == "") window.bookingStatus = null;
    });
    //
    //
    if ($('.account_range_picker').length > 0) {
		$account_range_picker = $(document).find('.account_range_picker');
		$account_range_picker.each(function()
		{
		    var $datePicker = $(this);
		    isSingle = false;
	
		    $datePicker.daterangepicker({
				singleDatePicker : isSingle,
				autoApply: true,
				showDropdowns: true,
				autoUpdateInput: false,
				opens : 'left',
				startDate : getTodatDate(),
				locale : {
					cancelLabel : 'Clear'
				}
		    });
	
		    $datePicker.on('apply.daterangepicker', function(ev, picker)
		    {
				var $this = $(this);
				var $fromDate = picker.startDate.format('YYYY-MM-DD');
				var $fromDateStan = picker.startDate.format('MM-DD-YYYY');
				var $toDate = picker.endDate.format('YYYY-MM-DD');
				var $toDateStan = picker.endDate.format('MM-DD-YYYY');
		
				var $thisBrother = '';
				if ($this.hasClass("fromDate")) {
				    $this.val($fromDate);
				    $this.attr('value', $fromDate);
				    $thisBrother = $this.closest('.dateRangeContainer').find('.toDate');
				    $thisBrother.val($toDate);
				    $thisBrother.attr('value', $toDate);
				} else if ($this.hasClass("toDate")) {
				    $this.val($toDate);
				    $this.attr('value', $toDate);
				    $thisBrother = $this.closest('.dateRangeContainer').find('.fromDate');
				    $thisBrother.val($fromDate);
				    $thisBrother.attr('value', $fromDate);
				}
				$thisBrother.data('daterangepicker').setStartDate($fromDateStan);
				$thisBrother.data('daterangepicker').setEndDate($toDateStan);
				//
				window.fromDate = $fromDate;
				window.toDate = $toDate;
		    });
	
			    var $this = $datePicker;
			    if ($this.hasClass("fromDate")) {
					$fromDate = $this.val();
					$thisBrother = $this.closest('.dateRangeContainer').find('.toDate');
					$toDate = $thisBrother.val();
			    } else if ($this.hasClass("toDate")) {
					$toDate = $this.val();
					$thisBrother = $this.closest('.dateRangeContainer').find('.fromDate');
					$fromDate = $thisBrother.val();
			    }
			    if ($fromDate != '') {
			    	$this.data('daterangepicker').setStartDate(convertmyDate($fromDate));
			    }
			    if ($toDate != '') {
			    	$this.data('daterangepicker').setEndDate(convertmyDate($toDate));
			    }
		});
    }

    $('.bookingType').find(':checkbox').each(function(){

        $(this).click(function(){
            var bookingType = $(this).attr('id');
            if (bookingType == "all"){
                $('.bookingType .form-check-input').prop('checked', $(this).is(':checked'));
            }
            else if (bookingType == "flights"){
                if ($(this).is(':checked') && $('.bookingType #hotels').is(':checked')){
                    $('.bookingType #all').prop('checked', true);
                }
                else {
                    $('.bookingType #all').prop('checked', false);
                }
            }
            else {
                if ($(this).is(':checked') && $('.bookingType #flights').is(':checked')){
                    $('.bookingType #all').prop('checked', true);
                }
                else {
                    $('.bookingType #all').prop('checked', false);
                }
            }
        });
    });

    $("#searchBooking, #searchBooking_2").click(function() {
        filterData();
    });
});

$(document).on('click',".approval_pagination, .prev_pg, .next_pg, .first_pg, .last_pg" ,function(){

    var $this = $(this).closest('li');
    var data_page = $this.attr('data-page');

    if(data_page==0) {
        return;
    }
    paginateBooking(data_page);
    //
    $('html,body').scrollTop(0);
});

function paginateBooking(page) {
	var params = getFilterParams();
	params.page = page;
	//
	showLoader();
	//
    $.ajax({
        url: generateLangURL('/corporate/booking/manage-bookings/filter', 'ajax'),
        data: params,
        type: 'post',
        cache: false,
        success: function (data) {
            getTotalAmount();
            $('#bookingList').html(data.allApprovalFlow);
            if (data.pagination) {
                $('#paginationPages').html(data.pagination);
            } else {
                $('#paginationPages').html("");
            }
        },
        complete: function (data) {
        	loader.hide();
        }
        
    });
}



function getTotalAmount() {
    var params = getFilterParams();

    $.ajax({
        url: generateLangURL('/corporate/booking/manage-bookings/gettotal', 'ajax'),
        data: params,
        type: 'post',
        cache: false,
        success: function (data) {
            if (data.total) {
                $('#bookingTotalAmount').html(data.total);
            }
        },
        complete: function (data) {

        }
    });
}

function detailPopup(link){
    ttModal = window.getTTModal("myModalZ", {url:{href: link}, width: 1024, title: "Reservation Details"});
    ttModal.show();
}

function approveRequest(reservationId,moduleId,accountId,transactionUserId,requestServicesDetailsId){
    ttModalButton.confirm(Translator.trans("Are you sure you want to Approved this Booking?"), function (btn) {
        if(btn == "ok"){
            showLoader();
            window.location.href = generateLangURL('/corporate/booking/manage-bookings/approve/'+reservationId+'-'+moduleId+'-'+accountId+'-'+transactionUserId+'-'+requestServicesDetailsId,'corporate')
        }
    }, null, {ok:{value:Translator.trans("Yes")},cancel:{value:Translator.trans("No")}});
}

function rejectRequest(requestServicesDetailsId,transactionUserId){
    ttModalButton.confirmDelete(Translator.trans("Are you sure you want to Reject this Booking?"), function (btn) {
        if (btn == "delete"){
            showLoader();
            $.ajax({
                url: generateLangURL('/corporate/booking/manage-bookings/reject','ajax'),
                data:{ requestServicesDetailsId: requestServicesDetailsId, transactionUserId: transactionUserId},
                type: 'post',
                success: function (res) {
                    loader.hide();
                    if(res.msg == 'success'){
                        $( "#searchBooking" ).trigger( "click" );
                        res.message = Translator.trans("Rejection successfully done");
                    }else{
                        res.message =  Translator.trans(res.msg);
                    }

                    ttModalButton.alert(res.message, null, null, {ok:{value:"close"}})
                }
            });
        }
    },null, {delete:{value:Translator.trans("reject")},cancel:{value:Translator.trans("close")}});
}

function cancelRequest(reservationId,requestServicesDetailsId,moduleId, transactionUserId){
    ttModalButton.confirmDelete(Translator.trans("Are you sure you want to Cancel this Booking?"), function (btn) {
        if (btn == "delete"){
            showLoader();
            $.ajax({
                url: generateLangURL('/corporate/booking/manage-bookings/cancel','ajax'),
                data:{ reservationId: reservationId, requestServicesDetailsId: requestServicesDetailsId, moduleId: moduleId, transactionUserId: transactionUserId},
                type: 'post',
                success: function (res) {
                    loader.hide();
                    if(res.success){
                        $( "#searchBooking" ).trigger( "click" );
                        if(!res.message || res.message == ''){
                            res.message = Translator.trans("Cancellation successfully done");
                        }

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

function showLoader(){
    loader = window.showTTLoader();
}

function onAccountFilterSelected(selPropId, selPropVal, drpInpt)
{
	window.accountId = selPropId;
}

function onUserFilterSelected(selPropId, selPropVal, drpInpt)
{
	window.userId = selPropId;
}

function getFilterParams()
{
	if(!window.serviceType) window.serviceType = "";
	if(window.accountId == '') window.accountId = null;
	if(window.userId == '') window.userId = null;
	if(window.fromDate == '') window.fromDate = null;
	if(window.toDate == '') window.toDate = null;
	if(window.serviceType == 0 || window.serviceType == '') window.serviceType = null;
	if(window.bookingStatus == 0 || window.bookingStatus == '') window.bookingStatus = null;
	//
	var params = {};

	if(window.serviceType) params.serviceType = window.serviceType;
	if(window.accountId) params.accountId = window.accountId;
	if(window.userId) params.userId = window.userId;
	if(window.fromDate) params.fromDate = window.fromDate;
	if(window.toDate) params.toDate = window.toDate;
	if(window.serviceType) params.serviceType = window.serviceType;
	if(window.bookingStatus) params.bookingStatus = window.bookingStatus;
	//
	return params;
}

function filterData()
{
    //
    paginateBooking(1);
    //
}
