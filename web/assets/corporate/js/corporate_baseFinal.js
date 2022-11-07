$(document).ready(function () {
    $(document).on('click', ".angleleft_style", function () {
        $(".angleleft_style").hide();
        $(".toggledtitle").hide();
        $(".toggledmenu").hide();
        $(".angleright_style").show();
        $(".left_panel").removeClass('col-md-3');
        $(".right_panel").removeClass('col-md-9');
        $(".left_panel").addClass('col-md-1');
        $(".right_panel").addClass('col-md-11');
    });
    $(document).on('click', ".angleright_style", function () {
        $(".angleright_style").hide();
        $(".angleleft_style").show();
        $(".toggledtitle").show();
        $(".toggledmenu").show();
        $(".left_panel").removeClass('col-md-1');
        $(".right_panel").removeClass('col-md-11');
        $(".left_panel").addClass('col-md-3');
        $(".right_panel").addClass('col-md-9');
    });
});

function goToLinkNew(link) {
    window.location.href = link;
}

function formatAmount(amount, format)
{
	if(value==null || value == "") value = 0;
	if(!format || format== "") format = "0,0.00";
	//
	var value = numeral(amount).format(format);
	//
	return value;
}
