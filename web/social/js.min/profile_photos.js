var fr_txt = "";
var to_txt = "";
var TO_CAL;
var $container;
var globorderby = 'pdate';

$(document).ready(function () {
    $(document).on('click', "#resetpagebut", function () {
        document.location.reload();
    });
    $container = $('#container');
    $("#sortby").selectbox();
    InitCalendar();
    $("#searchCalendarbut").click(function () {
        if ($('#fromtxt_date').val() != '' || $('#totxt_date').val() != '') {
            var section = $('#page_nav a').attr('data-case');
            ProfilePage = 0;
            $container.html('');
            RebuildLink(section);
            from_date = $('#from_date').val();
            to_date = $('#to_date').val();
            displayMediaDataRelates();
        }
    });
    $("#searchbut").click(function () {
        ss = "" + $("#srchtxt").val();
        if (ss == $("#srchtxt").attr('data-value')) {
            ss = "";
        }
        globorderby = $("#sortby").val();
        var section = $('#page_nav a').attr('data-case');
        ProfilePage = 0;
        $container.html('');
        RebuildLink(section);
        displayMediaDataRelates();
    });
    if ($('#container div.element').length === 0) {
        $('#loadmore').hide();
    }
    $('#loadmore').click(function () {
        if ($(this).data('no_more'))
            return;
        displayMediaDataRelates();
    });
});
function displayMediaDataRelates() {
    $('.upload-overlay-loading-fix-file').show();
    var section = $('#page_nav a').attr('data-case');
    $.ajax({
        url: $('#page_nav a').attr('href') + '&from_date=' + from_date + '&to_date=' + to_date + '&globorderby=' + globorderby + '&one_object=' + one_object,
        type: 'post',
        success: function (data) {
            var $newEls = $(data);
            var $resp = $(data);
            $container.find('.userscounthide').remove();
            if ($container.find('.userscounthide').attr('data-value') === "true") {
                $('#loadmore').hide();
            }
            ProfilePage++;
            RebuildLink(section);
            //$('#uploadlistpopuppublishalbum').remove();
            $container.append($resp);
            //$container.append('<div id="uploadlistpopuppublishalbum" style="display:none"></div>');
            refreshEditAlbums();
            refreshEditMedia();
            
            $('.upload-overlay-loading-fix-file').hide();
        }
    });
    one_object=0;
}
function initOverDocumment() {

}

function InitCalendar() {
    Calendar.setup({
        inputField: "fromtxt_date",
                noScroll  	 : true,
        trigger: "frombutcontainer",
        align: "B",
        onSelect: function () {
            var date = Calendar.intToDate(this.selection.get());
            TO_CAL.args.min = date;
            TO_CAL.redraw();
            $('#from_date').val(Calendar.printDate(date, "%Y-%m-%d"));

            addCalToEvent(this);
            this.hide();
        },
        dateFormat: "%d / %m / %Y"
    });
    TO_CAL = Calendar.setup({
        inputField: "totxt_date",
                noScroll  	 : true,
        trigger: "tobutcontainer",
        align: "B",
        onSelect: function () {
            var date = Calendar.intToDate(this.selection.get());
            $('#to_date').val(Calendar.printDate(date, "%Y-%m-%d"));

            addCalToEvent(this);
            this.hide();
        },
        dateFormat: "%d / %m / %Y"
    });
}
function addCalToEvent(cals) {
    if (new Date($('#from_date').val()) > new Date($('#to_date').val())) {
        $('#to_date').val($('#from_date').val());
        $('#totxt_date').val($('#fromtxt_date').val());
    }
}