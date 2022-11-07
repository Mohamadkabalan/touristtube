var LoadedPages = 1;
var Loading = 0;
var MAX_LOAD = 3;
function setHeight(){
	var height = 0 ;
	/*var single = $('#AllVideoList li:first').outerHeight() + 23;
        var rows1 = Math.ceil( $('li',$('#AllVideoList')).length/3 );
	var rows2 = ($('li',$('#AllPhotoList')).length)/2;
	var rows = rows1;
	if(rows < rows2) rows = rows2;
	height = single * rows;
	$('#AllVideoList').height(height);
	$('#VideoPhotoSep').height(height)
	$('#MostViewedDiv').height(height + $('#AllVideoList').position().top + 100); //100 pixels for the load more button
	var ContentHeight = height + $('#AllVideoList').position().top + 10;
	var MinContent = 640;
	if(ContentHeight < MinContent )ContentHeight = MinContent;
	$('#Content').height( ContentHeight );*/
}
function LoadMorePages(){
	if( Loading != 0){
            if( LoadedPages <= MAX_LOAD ) LoadedPages--;
            return ;
        }
	Loading = 2;
	
	$.ajax({
		url : decodeURL( $('#MoreVideos').attr('href') ), 
		success: function(data){
			
			TrackAjaxRequest( $('#MoreVideos').attr('href') );
			$('#AllVideoList').append(data);
			setHeight();
			$(window).resize();
			Loading--;
                        
			if( LoadedPages == MAX_LOAD ){
				$('#LoadMoreBtn').show();
			}
			$("#MostViewedDiv").preloader();
		}
	});
	$.ajax({
		url : decodeURL( $('#MorePhotos').attr('href') ),
		success: function(data){
			
			TrackAjaxRequest( $('#MorePhotos').attr('href') );
			$('#AllPhotoList').append(data);
			setHeight();
			$(window).resize();
			Loading--;
			$("#MostViewedDiv").preloader();
		}
	});
}
var left = 0;
$(document).ready(function(){
        reintialiseslider();
        var opt = {
            behavior: 'circle',
            mouseover: function(o) {
                o.stop();
                o.css({cursor: 'pointer'});
            },
            mouseout: function(o) {
                o.start();
            }
        }
        $('#TrendsDesc').mscroller(opt);
        
        $('#IndexCategories li.IndexCategory').mouseover(function() {
            var $Category = $(this);
            var pos = $Category.position();

            if ($('a', $(this)).hasClass('active'))
                return;

            //it was pos.top + 6 made it pos.top + 3
            $('#CategoryMarker').show().css({
                top: (pos.top ) + 'px',
                left: '-1px'
            });
        }).mouseout(function() {
            $('#CategoryMarker').hide();
        });
        $('#LoadMoreBtn').click(function() {
            LoadMorePages();
        });
        
        $('#TuberSearchSubmit').click(function() {
            $('input[name=SearchCategory]').removeAttr('checked');
            $('#SearchCategory4').click();
            var tosearch =getObjectData($('#SearchFieldTuber'));
            if( tosearch=='' || tosearch.length<=2){                
                return;
            }
            $('#SearchField').val(tosearch);            
            $('.SearchSubmit').click();
        });
        $('#SearchFieldTuber').keydown(function(e) {
            if (e.keyCode == 13)
                $('#TuberSearchSubmit').click();
        });
        //COPIED FROM TopIndex.php
        $('#SearchFieldTuber').autocomplete({
            appendTo: $('.MapSearchDiv'),
            delay: 5,
            search: function(event, ui) {
                var $searchString = $('#SearchField');
                var searchString = $searchString.val();                
                var type = 'u';
                var append = "&t=" + type;
                $('#SearchFieldTuber').autocomplete('option', 'source', ReturnLink('/ajax/media-autocomplete.php') + append);
            },
            focus: function(event, ui) {
                //$(this).val(ui.item.right);
                return false;
            },
            select: function(event, ui) {
                var wid = ui.item.id;
                var right = ui.item.value_display;            
                $('#SearchFieldTuber').val(right);
                document.location.href = ui.item.us_link;                            
                event.preventDefault();
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a class='auto_tuber'>" + item.label + "</a>")
                    .appendTo(ul);
        }
        
	setHeight();
        if( navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i)){
            $('#LoadMoreBtn').show();
        }else{
            $(window).scroll(function() {
                if(LoadedPages == MAX_LOAD ) return ;
                if( $(window).scrollTop() + $(window).height() == $(document).height() ) {
                        if(LoadedPages == MAX_LOAD ) return ;
                        var width =   $(window).width();
                        if(width>767 ){                            
                            LoadedPages++;
                            LoadMorePages();
                        }
                }
            });
        }	
});

function addValue3(obj){
	if($(obj).attr('value') == '') $(obj).attr('value',$(obj).attr('data-value'));
} 
function removeValue3(obj) {
	if($(obj).attr('value') == $(obj).attr('data-value')) $(obj).attr('value','');
}