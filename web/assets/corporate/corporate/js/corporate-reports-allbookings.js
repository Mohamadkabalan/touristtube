window.accountId = null;
window.fromDate = null;
window.accounTypetId = null;
window.accountId = null;

function executeFilter(page = 0)
{
    showLoader();

    var fromDay = window.fromDate;
    var toDay = window.toDate;
    var accounTypetId = window.accountTypeId;
    var accountId = window.accountId;
    var start = page || 0;

    $.ajax({
	url: generateLangURL('/corporate/admin/filterMyBookings', 'ajax'),
	data: {
	    fromDay: fromDay,
	    toDay: toDay,
	    accountId: accountId,
	    accounTypetId: accounTypetId,
	    start: start
	},
	type: 'post',
	success: function (data)
	{
        $('.corporate_common_new_container .pending_ammount span.price').html(formatAmount(data.totalAmt));
	    $('#aprovalFlowList').html(data.allApprovalFlow);
	    if (data.pagination) {
		$('#paginationPages').html(data.pagination);
	    } else {
		$('#paginationPages').html("");
	    }
	}
    });
}

$(document).on('click', ".approval_pagination, .prev_pg, .next_pg, .first_pg, .last_pg", function ()
{
    showLoader();
    var $this = $(this).closest('li');
    var $rowsect = $this.closest('.row-sect');
    var data_page = $this.attr('data-page');
    if (data_page == 0)
	return;
    var page = data_page;
    executeFilter(page);

    $('html,body').scrollTop(0);

});

function detailPopup(link)
{
    ttModal = window.getTTModal("myModalZ", {
	url: {
	    href: link
	},
	width: 1024,
	title: Translator.trans("Reservation Details")
    });
    ttModal.show();
}
