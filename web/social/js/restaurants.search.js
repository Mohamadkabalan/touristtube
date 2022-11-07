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
        appendTo: "#searchdiscovercontainer",
        search: function (event, ui) {                        
            $ccity.autocomplete("option", "source", generateLangURL('/ajax/Restaurant_search_for'));                    
        },
        select: function (event, ui) {
            $ccity.val(ui.item.name);
            var lkname = ui.item.lkname;
            $ccity.attr('data-link',lkname);
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13) {
            srch = $("#searchdiscover").val();
            document.location = generateLangURL("/search-restaurants-"+srch.replace(" ","+")+"_1",'restaurants');
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a class='auto_tuber'>" + item.label + "</a>")
                    .appendTo(ul);
    };
}