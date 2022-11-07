var map;
var TO_CAL;
var entity_type_search=0;
var fr_txt="";
var to_txt="";
var MapMarkers = [];
var markerTypes = ['tuber'];
var MyLocation = {};
var infoWindow;
var newff=0;
var fullscreenmode = false;
var AllMarkerTypes = ['tuber','hotel','restaurant'];

/**
 * general view mode
 */
var ViewModeGeneral = 0;
/**
 * detailed view mode
 */
var ViewModeDetail = 1;
/**
 * the view mode of the map
 */
var ViewMode = ViewModeGeneral;
/**
 * allow popus to be shown on the map
 */
var ShowPopups = false;

/**
 * the current uid being viewed for reviews
 */
var CurrentUID = 0;
var UI_EVENT = 0;

function changeFilter(obj){
    $(".footer_container_all").show();
    if(parseInt($(obj).val())==1){
        if(fullscreenmode){
            $('html,body').css({ overflow:'visible' });
            $('#BigMap').css({'position': '', top : '' , left : '',height: '100%', width: '100%'}).detach().prependTo($('#BigMap_up'));
        }
        fullscreenmode = false
        $("#mapcalendarcontent").show();
        $(".search_data_container").hide();
        $(".search_data_container").removeClass('full');

        $("#BigMap_up").animate({height: "175px"}, 500 ,function(){
            google.maps.event.trigger(map, 'resize');
            map.setCenter(new google.maps.LatLng( $("#BigMap_up").attr('data-lat') , $("#BigMap_up").attr('data-log') ) );
        });
    }else if(parseInt($(obj).val())==2){
        if(fullscreenmode){
            $('html,body').css({ overflow:'visible' });
            $('#BigMap').css({'position': '', top : '' , left : '',height: '100%', width: '100%'}).detach().prependTo($('#BigMap_up'));
        }
        fullscreenmode = false
        $("#mapcalendarcontent").hide();
        $(".search_data_container").show();
        $(".search_data_container").removeClass('full');

        $("#BigMap_up").animate({height: "480px"}, 500 ,function(){
            google.maps.event.trigger(map, 'resize');
            map.setCenter(new google.maps.LatLng( $("#BigMap_up").attr('data-lat') , $("#BigMap_up").attr('data-log') ) );
        });
    }else{
        if( $('#chatList').css('display') !="none" ) $('#openChatList').click();
        fullscreenmode = true
        $("#mapcalendarcontent").hide();
        $(".footer_container_all").hide();
        $(".search_data_container").addClass('full');
        $(".search_data_container").show();
        $('html,body').css({ overflow: 'hidden' });
        //$('#BigMap').css({'position': 'fixed', 'top' : '0px' , 'left' : '0px', width : $(window).width() + 'px', height : $(window).height() + 'px' , 'z-index': 200 }).detach().prependTo($('body'));	
        $('#BigMap').css({'position': 'fixed', 'top' : '0px' , 'left' : '0px', width :'100%', height : '100%' , 'z-index': 200 }).detach().prependTo($('body'));
        google.maps.event.trigger(map, 'resize');
        map.setCenter(new google.maps.LatLng( $("#BigMap_up").attr('data-lat') , $("#BigMap_up").attr('data-log') ) );
    }
}

function customRadio(radioName){
    var radioButton = $('input[name="'+ radioName +'"]');
    $(radioButton).each(function(){
        $(this).wrap( "<span class='custom-radio'></span>" );
        if($(this).is(':checked')){
            $(this).parent().addClass("selected");
        }
    });
    $(radioButton).click(function(){
        if($(this).is(':checked')){
            $(this).parent().addClass("selected");
        }
        $(radioButton).not(this).each(function(){
            $(this).parent().removeClass("selected");
        });
    });
}

function chatOnOff(clicked){
    if(clicked == 1){
        $(".chatOffTxt").removeClass("chatActive");
        $(".chatOnTxt").addClass("chatActive");
        $("#newsticker").show();
    }else{
        $(".chatOnTxt").removeClass("chatActive");
        $(".chatOffTxt").addClass("chatActive");
        $("#newsticker").hide();
    }
}
function resizePartsContainer(){
    var windowSize = $(window).width();
    var contSizeUp = $('.parts_container_up').width();
    var contSize = $('.parts_container').width();
    //console.log(contSize,windowSize);
    var cond1 = (windowSize>contSize);
    //var cond2 = ( (windowSize<=1229) && (contSize>923));
    //var cond3 = ( (windowSize<=1229) && (contSize<=923));
    if(cond1){
        $(".parts_container").css('marginLeft',"0");
        $('.parts_container_up').css('width', $('.parts_container').css('width') );
        $(".prev_btn,.next_btn").hide();
        count = 0;
    }else{
        $('.parts_container_up').css('width', '923px' );
        $(".prev_btn,.next_btn").show();
    }
}
$( document ).ready(function(){
    $( window ).resize(function(){
        resizePartsContainer();
    });
	$('.incont').css('width', $('.incont_inside').css('width') );
        //windowSize = $(window).width();
	$('.parts_container_up').css('width', $('.parts_container').css('width') );
	$('.parts_container').css('width', $('.parts_container_up').css('width') );
	$('.parts_container_up').css('width', '923px' );
	resizePartsContainer();
	autocompleteDiscover($('#mapsearchfield'));
	var maxwidth = parseInt($(".incont").css("width"));
	var contwidth = 982;//parseInt($(".outcont").css("width"));
	var lastleft = maxwidth - contwidth;
	var theleft = parseInt($(".incont").css("left"));
        $(".chatOnTxt").click(function(){
           chatOnOff(1);
        });
        $(".chatOffTxt").click(function(){
           chatOnOff(0);
        });
   $('.hotelsplus_inactive').click(function(){
        TTAlert({
                        msg: t('you need to have a')+' <a class="black_link" href="'+ReturnLink('/register')+'">'+t('TT account')+'</a> '+t('in order to add items to your bag.'),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		});
    });
	$("#searchCalendarbut").click(function(){
		if($('#fromtxt').html()=="" && $('#fromtxt').attr('data-cal')==""){
			TTAlert({
				msg: t('Invalid date from'),
				type: 'alert',
				btn1: t('ok'),
				btn2: '',
				btn2Callback: null
			});
			return;
		}
		if($('#totxt').html()=="" && $('#totxt').attr('data-cal')==""){
			TTAlert({
				msg: t('Invalid date to'),
				type: 'alert',
				btn1: t('ok'),
				btn2: '',
				btn2Callback: null
			});
			return;
		}
		fr_txt=""+$('#fromtxt').attr('data-cal');
		to_txt=""+$('#totxt').attr('data-cal');	
		entity_type_search = SOCIAL_ENTITY_EVENTS;
		getToDoDataRelated();
	});
	$(".tt_advisor").click(function(){		
		if($('#fromtxt').html()=="" && $('#fromtxt').attr('data-cal')==""){
			TTAlert({
				msg: t('Invalid date from'),
				type: 'alert',
				btn1: t('ok'),
				btn2: '',
				btn2Callback: null
			});
			return;
		}
		if($('#totxt').html()=="" && $('#totxt').attr('data-cal')==""){
			TTAlert({
				msg: t('Invalid date to'),
				type: 'alert',
				btn1: t('ok'),
				btn2: '',
				btn2Callback: null
			});
			return;
		}
		fr_txt=""+$('#fromtxt').attr('data-cal');
		to_txt=""+$('#totxt').attr('data-cal');	
		var str =""+$('#discoverData').attr('data-bag');
                if($('#discoverData').attr('data-country')!=''){
                    str +="_"+$('#discoverData').attr('data-country');
                    if($('#discoverData').attr('data-state')!=''){
                        str +="_"+$('#discoverData').attr('data-state');
                    }
                }
		window.open(ReturnLink('/planner/'+str+'/'+fr_txt+'/'+to_txt),'_blank');
	});
	$(".menu_header_active").click(function(){
            fr_txt=""+$('#fromtxt').attr('data-cal');
            to_txt=""+$('#totxt').attr('data-cal');	
            var str =""+$('#discoverData').attr('data-bag');
            var data_city = ""+ ($('#discoverData').attr('data-city'));
            if( data_city!='' && data_city!=0){
                str +="/C/"+data_city;
            }
            if($('#discoverData').attr('data-country')!=''){
                str +="/CO/"+$('#discoverData').attr('data-country');
                if($('#discoverData').attr('data-state')!=''){
                    str +="/ST/"+$('#discoverData').attr('data-state');
                }
            }
            var real_link = str;
            if(fr_txt!=''){
                    real_link += '/from/'+fr_txt;
                    if(to_txt!=''){
                            real_link += '/to/'+to_txt;
                    }
            }
            var page_link = "";
            if( $(this).attr('data-type') == SOCIAL_ENTITY_LANDMARK ){
                page_link = ReturnLink('/things-to-do');
            }else if( $(this).attr('data-type') == SOCIAL_ENTITY_HOTEL ){
                page_link = ReturnLink('/hotel-search/'+real_link);
            }else if( $(this).attr('data-type') == SOCIAL_ENTITY_RESTAURANT ){
                page_link = ReturnLink('/restaurant-search/'+real_link);
            }else if( $(this).attr('data-type') == SOCIAL_ENTITY_CHANNEL ){
                page_link = ReturnLink('/channel-search/co/'+$('#discoverData').attr('data-country')+'');
            }
            if(page_link!=""){
                window.open(page_link,'_blank');
            }
	});
	$(document).on('click',".middle_search_container .search_checkbox" ,function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
		}else{
			if($(this).hasClass('search_checkbox_all')){
				$('.middle_search_container .search_checkbox').removeClass('active');
			}else{
				$('.middle_search_container .search_checkbox_all').removeClass('active');
			}
			$(this).addClass('active');
		}
		$('.upload-overlay-loading-fix').show();
		setTimeout(function(){
			setMapMarkers(currentmapzoom);
			$('.upload-overlay-loading-fix').hide();
		},1000);
	});
	
    $("#activetubers").on('mouseover','img', function(e){
        if( !$(this).data('title') ){
            $(this).data('title', $(this).attr('title') );
            $(this).attr('title', '');
        }
        var title = $(this).data('title');
        var c = (title !== "" ) ? "<br/>" + title : "";
        $("body").append("<div id='preview'><img src='"+ $(this).attr('data-src') +"' alt='Image preview' style='width: 100px;height: 100px;'/>"+ c +"</div>");
        var pos_top = $(this).offset().top;
        var pos_left = $(this).offset().left + 28; //width of thumb is 28
        $("#preview").css("top",pos_top + "px").css("left",pos_left + "px").css({
            position:'absolute',
            border: '1px solid #ccc',
            background: '#333',
            'font-size': '12px',
            padding: '5px',
            display: 'none',
            color:'#fff',
            'z-index': 10000,
            'text-align': 'center'
        }).fadeIn("fast");
    }).on('mouseout','img', function(){
        $("#preview").remove();
    }).mousemove(function(e){
        //$("#preview").css("top",(e.pageY - yOffset) + "px").css("left",(e.pageX + xOffset) + "px");
    });

    $(document).on('click',"#mapsearchbutton" ,function(){
        var str = $('#mapsearchfield').val();
        var code = ""+ ($('#mapsearchfield').attr('data-code')).toUpperCase();
        var state_code = ""+ ($('#mapsearchfield').attr('data-state-code'));
        var iscountry = parseInt($('#mapsearchfield').attr('data-iscountry'));
        if(iscountry == 0 && code!="" ){
            str += "/"+code;
            if(parseInt(state_code) != 0 ){
                str += "/"+state_code;
            }
        }
        if(str!=''){
            window.location.href = ReturnLink('/map/'+str);
        }
    });
    $('.moveright').click(
        function() {
            var theanimate = 0;
            if(theleft <= -lastleft){
                theanimate = -lastleft;
            }
            else if( (theleft-contwidth) <= -lastleft){
                theanimate = -lastleft;
            }
            else{
                theanimate = theleft - contwidth;
            }
            /*var value_left = Math.round( Math.abs(theanimate/380) );
            if( (value_left%2) == 0 ) theanimate +=3;*/
            $('.incont').stop().animate({left: theanimate}, "6000");
            theleft = theanimate;
        }
    );
    $('.moveleft').click(
        function() {
            var theanimate = 0;
            if(theleft >= 0){
                theanimate = 0;
            }
            else if( (theleft+contwidth) >= 0){
                theanimate = 0;
            }
            else{
                theanimate = theleft + contwidth;
            }
            /*var value_left = Math.round( Math.abs(theanimate/380) );
            if( (value_left%2) != 0 && value_left!=0) theanimate -=3;*/
            $('.incont').stop().animate({left: theanimate}, "6000");
            theleft = theanimate;
        }
    );

    $('.middle_map_items_cont').css('width', $('.middle_map_item').length*227+'px' );
    $(".select3").selectbox();
    $(".alert_bulb" ).delay( 4000 ).fadeOut( 600 );
    $("#collapsemenu").animate({opacity: "1",}, 500 );
    $(".things_imgs").animate({opacity: "1",}, 500 );

    $(function(){
        $('#vertical-ticker').totemticker({
            row_height	:	'46px',
            next		:	'#ticker-next',
            previous	:	'#ticker-previous',
            stop		:	'#stop',
            start		:	'#start',
            mousestop	:	true,
        });
    });
/*$(".middle_map_item").each(function(){
        var $this = $(this);		
        $this.on('mouseenter',function(){
                switch($this.attr('id')){
                        case 'middle_map_item0':
                                $("#middle_map_item1").animate({marginLeft: "547px",}, 200 );
                                $(".attractions_data").fadeIn(200 );
                        break;
                        case 'middle_map_item1':
                                $("#middle_map_item2").animate({marginLeft: "250px",}, 200 );
                                $(".activities_data").fadeIn(200 );
                        break;
                        case 'middle_map_item2':
                                $("#middle_map_item3").animate({marginLeft: "150px",}, 200 );
                                $(".nightlife_data").fadeIn(200 );
                        break;
                        case 'middle_map_item3':
                                $("#middle_map_item4").animate({marginLeft: "150px",}, 200 );
                                $(".shopping_data").fadeIn(200 );
                        break;
                }
        }).on('mouseleave',function(){
            switch($this.attr('id')){
                case 'middle_map_item0':
                        $("#middle_map_item1").animate({marginLeft: "0px",}, 200 );
                        $(".attractions_data").fadeOut( 200 );
                break;
                case 'middle_map_item1':
                        $("#middle_map_item2").animate({marginLeft: "0px",}, 200 );
                        $(".activities_data").fadeOut(200 );
                break;
                case 'middle_map_item2':
                        $("#middle_map_item3").animate({marginLeft: "0px",}, 200 );
                        $(".nightlife_data").fadeOut(200 );
                break;
                case 'middle_map_item3':
                        $("#middle_map_item4").animate({marginLeft: "0px",}, 200 );
                        $(".shopping_data").fadeOut(200 );
                break;
            }
        });
});*/
	//********* THINGS TO DO BUTTONS ************ //
	$("#things_cont_inside").each(function(){
            var $this = $(this);
            $this.on('click',function(){
                alert($this);
                /*$.ajax({
                    type: "POST",
                    url: ReturnLink("/parts/discover-things.php"),
                    data: {},
                    success: function(data){
                        if(data){
                        }
                    }
                });*/
            })
	});
	//********* RADIO BUTTONS ************ //
	$(".map_radio").each(function(){
            var $this = $(this);
            $this.on('click',function(){
                $(".map_radio").find('.rd-selected').hide();
                $this.find('.rd-selected').show();
            })
	});
	//********* ACCORDION ************ //
	$("#splash").zAccordion({
		timeout: 4500,
		speed: 500,
		slideClass: 'slide',
		animationStart: function () {
                    document.getElementById('things_header').innerHTML = $('#splash').find('li.slide-open').attr('data-title');
                    $('#splash').find('li.slide-previous div').fadeOut();
		},
		animationComplete: function (){
                    $('#splash').find('li.slide-open div').fadeIn();
		},
		buildComplete: function () {
                    $('#splash').find('li.slide-closed div').css('display', 'none');
                    $('#splash').find('li.slide-open div').fadeIn();
		},
		startingSlide: 0,
		slideWidth: 168,
		width: 280,
		height: 123
	});
    InitCalendar();
});
function InitCalendar(){
    if( $('#fromtxt').length>0 ){
	Calendar.setup({
		inputField : "fromtxt",
                noScroll  	 : true,
            fixed: true,
		trigger    : "frombutcontainer",
		align:"B",
		onSelect  : function() {
			var date = Calendar.intToDate(this.selection.get());
		    TO_CAL.args.min = date;
		    TO_CAL.redraw();
			$('#fromtxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
			
			addCalToEvent(this);
			this.hide();
		},
		dateFormat : "%d / %m / %Y",
        disabled      : function(date) {
            var dats = new Date();
            var currentdate = new Date(dats.getFullYear(), dats.getMonth(), dats.getDate(), 0, 0, 0, 0);
            return (date < currentdate);
        }
	});
	TO_CAL=Calendar.setup({
            inputField : "totxt",
                noScroll  	 : true,
            fixed: true,
            trigger    : "tobutcontainer",
            align:"B",
            onSelect   : function() {
                    var date = Calendar.intToDate(this.selection.get());
                    $('#totxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
                    addCalToEvent(this);
                    this.hide();
            },
            dateFormat : "%d / %m / %Y",
			disabled      : function(date) {
				var dats = new Date();
				var currentdate = new Date(dats.getFullYear(), dats.getMonth(), dats.getDate(), 0, 0, 0, 0);
				return (date < currentdate);
			}
	});
    }  
}
function addCalToEvent(cals){
	if(new Date($('#fromtxt').attr('data-cal'))>new Date($('#totxt').attr('data-cal'))){
		$('#totxt').attr('data-cal',$('#fromtxt').attr('data-cal'));
		$('#totxt').html($('#fromtxt').html());
	}
}
function checkSubmitcatadd(e){
    if(e && e.keyCode == 13){
       $('#mapsearchbutton').click();
    }
}
function checkSubmitcatadd1(e){
	if(e && e.keyCode == 13){
	   window.location.href = ReturnLink('/map');
	}
}
function addMapData(){
	
}
function getToDoDataRelated(){
	$('.upload-overlay-loading-fix').show();
	$('#search01_landmarks').val(0);
	$.post(ReturnLink('/ajax/ajax_ToDo_result.php'), { frtxt:fr_txt,totxt:to_txt , data_log:$('#BigMap_up').attr('data-log'), data_angle:$('#BigMap_up').attr('data-angle') , data_lat:$('#BigMap_up').attr('data-lat'), entity_type:entity_type_search },function(data){
		var ret = null;
		try{
			ret = $.parseJSON(data);
		}catch(Ex){
				$('.upload-overlay-loading-fix').hide();
			return ;
		}
		if(ret.error){
			TTAlert({
				msg: ret.error,
				type: 'alert',
				btn1: t('ok'),
				btn2: '',
				btn2Callback: null
			});			
		}else{	
			$("#todo_container").html(ret.data);
		}
		$('.upload-overlay-loading-fix').hide();
   });
}
function filterToDoData(obj){
	var id=parseInt($(obj).val());
	entity_type_search = id;
	if( entity_type_search != SOCIAL_ENTITY_LANDMARK && entity_type_search != SOCIAL_ENTITY_EVENTS ) entity_type_search = 0;
	fr_txt ="";
	to_txt ="";
	getToDoDataRelated();
}
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}
function setMapMarkers(current_map_zoom){
	var currentzoom = current_map_zoom - 10;
	if( currentzoom <=0 ){
		currentzoom = 0;	
	}
	var search_checkbox_filter = new Array();
	search_checkbox_filter.push( -1 );
	$('.search_checkbox.active').each(function(index, element) {
		search_checkbox_filter.push( parseInt($(this).attr('data-value')) );
	});
	
	for(var i = 0; i<marker_global_array.length; i++){
            var usedarray = marker_global_array[i];
            try{
		for(var j = 0; j<usedarray.length; j++){
			var markerdata = usedarray[j];
			var markerdata_second = marker_global_array1[i][j];
			if(i<=currentzoom){
				markerdata[0].setMap(null);
				if( inArray( parseInt(markerdata[1]) , search_checkbox_filter) || inArray( 0 , search_checkbox_filter) ) markerdata_second[0].setMap(map);
				else markerdata_second[0].setMap(null);
			}else{
				markerdata_second[0].setMap(null);
				if( inArray( parseInt(markerdata[1]) , search_checkbox_filter) || inArray( 0 , search_checkbox_filter) ) markerdata[0].setMap(map);
				else markerdata[0].setMap(null);
			}
		}
            }catch(e){}
	}
}