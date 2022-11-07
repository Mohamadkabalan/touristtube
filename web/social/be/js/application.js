var SOCIAL_ENTITY_HOTEL = 28;
var SOCIAL_ENTITY_LANDMARK = 30;
var SOCIAL_ENTITY_AIRPORT = 63;
var SOCIAL_ENTITY_CITY = 71;
var SOCIAL_ENTITY_COUNTRY = 72;
var SOCIAL_ENTITY_STATE = 73;
var SOCIAL_ENTITY_DOWNTOWN = 74;

$.extend({
    handleError: function( s, xhr, status, e ) {
        // If a local callback was specified, fire it
        if ( s.error )
            s.error( xhr, status, e );
        // If we have some XML response text (e.g. from an AJAX call) then log it in the console
        else if(xhr.responseText)
            console.log(xhr.responseText);
    }
});

$(document).on('ajaxComplete', function(event, xhr, settings){
    ga('send', 'pageview', settings.url);
});

function GetController(pathname){
    var res = pathname.split("/");
    return res[1];
}


$(function() {
    $('.container').on('click', '.deleteAct', function(e){
        e.preventDefault();
        var self = this;
        if(confirm("Are you sure you want to delete this record?")){
            var url = $(this).attr('href');
            $.ajax({
               url: url,
               type: 'post',
               dataType: 'json',
               success: function (res){
                   if(res.success){
                       $(self).closest('tr').remove();
                   }
                   else{
                       alert("An error occured while trying to delete this record.")
                   }
               }
            });
        }
    });
});

$(function() {
    $('.container').on('click', '.acceptAct', function(e){
        e.preventDefault();
        var self = this;
        if(confirm("Are you sure you want to accept this record?")){
            var url = $(this).attr('href');
            $.ajax({
               url: url,
               type: 'post',
               dataType: 'json',
               success: function (res){
                   if(res.success){
                       $(self).closest('tr').remove();
                   }
                   else{
                       alert("An error occured while trying to accept this record.")
                   }
               }
            });
        }
    });
});
//$(function() {
//function log( message ) {
//$( "<div>" ).text( message ).prependTo( "#log" );
//$( "#log" ).scrollTop( 0 );
//}
//$( "#birds" ).autocomplete({
//source: "hotel/fsearch/",
//minLength: 2,
//select: function( event, ui ) {
//log( ui.item ?
//ui.item.id + " | " + ui.item.value   :
//"Nothing selected, input was " + this.value );
//}
//});
//});

$(function() {
    $('.container').on('click', '.duplicateAct', function(e){
        e.preventDefault();
        var self = this;
        if(confirm("Are you sure you want to duplicate this record?")){
            var url = $(this).attr('href');
            $.ajax({
               url: url,
               type: 'post',
               dataType: 'json',
               success: function (data){
                   if(data.status === "success"){
                       alert("Record created successfully.");
                   }
                   else{
                       alert("An error occured while trying to duplicate this record.");
                   }
               }
            });
        }
    });
});