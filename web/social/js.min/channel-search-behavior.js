function submitSearchForm() {
    $(".SearchSubmit").click();
}
$(document).ready(function() {

    $(".SearchSubmit").click(function() {
        var SearchCriteria = $("#tsearch").val();
        if (SearchCriteria == 'Search Channels...' || SearchCriteria == '' || SearchCriteria == 'Search') {
            TTAlert({
                msg: t("Please choose a search criteria."),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
        } else {
//			SearchCriteria = SearchCriteria.replace(" ","+");
            window.top.location.href = ReturnLink('channel-search/t/' + SearchCriteria);
        }
    });

    /*
     $('#SearchField').autocomplete({
     delay: 5,
     search: function(event, ui) {
     var $searchString = $('#SearchField');
     var searchString = $searchString.val();
     
     $('#SearchField').autocomplete( "option", "source", ReturnLink('/ajax/channel_suggest.php') );
     },
     focus: function( event, ui ) {
     //$(this).val(ui.item.right);
     return false;
     },
     select: function(event, ui) {
     var wrong = ui.item.wrong;
     var right = ui.item.right;
     var oldVal = $('#SearchField').val();
     event.preventDefault();
     right = right.replace(/(<([^>]+)>)/ig,"");
     $('#SearchField').val( right );
     }
     }).keydown(function(event){
     
     if(event.keyCode == 13){
     event.preventDefault();
     $(".SearchSubmit").click();
     }
     
     }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
     return $( "<li></li>" )
     .data( "item.autocomplete", item )
     //.append( "<a href='"+item.url+"' title='"+ item.right + "' onClick=\"addTotop('"+item.name+"');\">"+ item.label + "<span class='searchttl'>"+ item.name + "</span></a>" )
     .append( "<a title='"+ item.right + "'>"+ item.label + "<span class='searchttl'>"+ item.name + "</span></a>" )
     .appendTo( ul );
     }
     */
});