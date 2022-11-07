$(document).ready(function(){
    $('#searchdiscover').val('');
    addAutoCompleteListHotels();    
    $(document).on('click',".search_result" ,function(){
        var val = $('#searchdiscover').attr('data-link');
        if(val!=''){
            document.location.href = generateLangURL(val);
        }
    });
});
function addAutoCompleteListHotels(which) {
    var $ccity = $("#searchdiscover");
    $ccity.autocomplete({
        minLength: minSearchLength, 
        appendTo: "#searchdiscovercontainer",
        search: function (event, ui) {                        
            $ccity.autocomplete("option", "source", generateLangURL('/ajax/Hotel_search_for'));                    
        },
        select: function (event, ui) {
            $ccity.val(ui.item.name);
            var lnk = ui.item.lkname;
//            var pid = ui.item.pid;
//            var lnk = 'hotels-in-'+lkname+'-'+pid;
            $ccity.attr('data-link',lnk);
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13) {
            srch = $("#searchdiscover").val();
            console.log(srch);
            document.location = "/search-hotels-"+srch.replace(" ","+")+"_1";
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a class='auto_tuber'>" + item.label + "</a>")
                    .appendTo(ul);
    };
}