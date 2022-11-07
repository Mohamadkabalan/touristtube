var signflag=0;
var profileFlag=0;
var staticsearchflag=0;
var profileFadeoutTimeout;
var staticsearchTimeout;
$(document).ready(function(){
    $('.searchlocation').attr('data-id',0);
    $('.searchlocation').attr('data-city','');
    $('.searchlocation').attr('data-state','');
    $('.searchlocation').attr('data-contryC','');
    $('.searchlocation').attr('data-type',0);
    $('.searchlocation').val('');
    // for trens scroller
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

    // for language functionality
            $(document).on('change','#languageSelect',function(){
                var optionvalue = $(this).val();
                    window.location.href=optionvalue;
            });

    // for menu
    $('.menu-toggle').click(function(){
        $("#mobile_tabsmain").slideToggle(500);
    });
    $('#SignInBtn').click(function (event){
        event.stopImmediatePropagation();
        event.preventDefault();
        if (signflag == 0) {
            $('#SignInDiv').fadeIn(); 
            signflag = 1;           
        }
    });
    $('#SignInDiv').mouseenter(function (event){
        signflag = 0;
    });
    $('#SignInDiv').mouseleave(function (event){
        signflag = 1;
    });
    $('body').click(function () {                
        if (signflag == 1) $('#SignInDiv').fadeOut();
        signflag = 0;        
        if (staticsearchflag == 1) {
            clearTimeout(staticsearchTimeout);
            staticsearchTimeout = setTimeout(function () {
                $(".search-static").hide();
                staticsearchflag=0;
            }, 700);
        }
    });
    $('.opensubout').click(function (event){
        event.stopImmediatePropagation();
        event.preventDefault();
        if (profileFlag == 0) {
            $('#TopProfileDiv').fadeIn();
            $('#TopProfileDiv').unbind('mouseenter mouseleave').hover(function () {
                clearTimeout(profileFadeoutTimeout);
                $('#TopProfileDiv').stop(true, true);
                $('#TopProfileDiv').fadeIn();
                profileFlag = 1;
            }, function () {
                profileFadeoutTimeout = setTimeout(function () {
                    $('#TopProfileDiv').fadeOut();
                    profileFlag = 0;
                }, 500);
            });
            profileFlag = 1;
            profileFadeoutTimeout = setTimeout(function () {
                $('#TopProfileDiv').fadeOut();
                profileFlag = 0;
            }, 1500);
        } else {
            $('#TopProfileDiv').fadeOut();
            profileFlag = 0;
        }
    });
    var moreshown = false;
    $(".showChannels").click(function () {
        if (!moreshown) {
            moreshown = true;
            $("#OtherChannelsPop").show();
            InitCarouselOther();
        } else {
            moreshown = false;
            $("#OtherChannelsPop").hide();
        }
    });
    $(".showChannels").click(function () {
        if (!moreshown) {
            moreshown = true;
            $("#OtherChannelsPop").show();
            InitCarouselOther();
        } else {
            moreshown = false;
            $("#OtherChannelsPop").hide();
        }
    });
    $(".search-static-items").click(function () {
        setTimeout(function () {
            clearTimeout(staticsearchTimeout);
        }, 500);
        clearTimeout(staticsearchTimeout);
        staticsearchflag=1;
    });
    $(".input-search.searchfor").click(function () {
        setTimeout(function () {
            clearTimeout(staticsearchTimeout);
        }, 500);
        clearTimeout(staticsearchTimeout);
        staticsearchflag=1;
    });
    $(".input-search.searchfor").focus(function () {
        setTimeout(function () {
            clearTimeout(staticsearchTimeout);
        }, 500);
        clearTimeout(staticsearchTimeout);
        staticsearchflag=1;
        if ($(this).val()=="" && $(".input-search.searchlocation").val()!="" && ( $(".input-search.searchlocation").attr('data-id')!=0 || $(".input-search.searchlocation").attr('data-contryc')!='' ) ) {
            $(".search-static").show();
            var text =$('.searchlocation').attr('data-city');
            $('.itemstextcontent2').text(text);
        } else {
            $(".search-static").hide();            
        }
    });
    $(".input-search.searchfor").blur(function () {
        //$(".search-static").hide();
    });
    $(".input-search.searchlocation").focus(function () {        
        //$(".search-static").hide();
    });
    $(".input-search.searchfor").keyup(function () {
        if ($(this).val()=="" && $(".input-search.searchlocation").val()!="" && ( $(".input-search.searchlocation").attr('data-id')!=0 || $(".input-search.searchlocation").attr('data-contryc')!='' ) ) {
            $(".search-static").show();
            var text =$('.searchlocation').attr('data-city');
            $('.itemstextcontent2').text(text);
        } else {
            $(".search-static").hide();            
        }
    });
    
    
    $("#searchfor").focus(function () {
        if ($(this).val()=="" && $(".searchlocation").val()!="" && $(".searchlocation").attr('data-id')!="") {
            $(".search-static2").show();
            var text2 =$(".searchlocation").attr('data-city');
            $('.itemstextcontent3').html(text2);
        } else {
            //$(".search-static2").hide();            
        }
    });
    $("#searchfor").blur(function () {
        //$(".search-static2").hide();
    });
    $(".searchlocation").focus(function () {        
        //$(".search-static2").hide();
    });
    $("#searchfor").keyup(function () {
        if ($(this).val()=="" && $(".searchlocation").val()!="" && $(".searchlocation").attr('data-id')!="") {
            $(".search-static2").show();
            var text2 =$(".searchlocation").attr('data-city');
            $('.itemstextcontent3').html(text2);
        } else {
            //$(".search-static2").hide();            
        }
    });
    
    
    
    
    if( $("#searchcontid").length>0 ) addAutoCompleteList("searchcontid");
});
function InitCarouselOther() {
    if ($(".caouselother").length > 0) {
        $(".caouselother").jCarouselLite({
            circular: false,
            vertical: false,
            scroll: 1,
            visible: 1,
            auto: 0,
            speed: 750,
            hoverPause: true,
            btnNext: "#OtherChannelsPop .next",
            btnPrev: "#OtherChannelsPop .prev"
        });
    }
}
function addAutoCompleteList(which) {
    var $ccity = $("input[name=city]", $('#' + which));
    $ccity.autocomplete({
        appendTo: "#searchdestination_id",
        search: function (event, ui) {                        
            $ccity.autocomplete("option", "source", '/ajax/search_locality');                    
        },
        select: function (event, ui) {
            $ccity.val(ui.item.value);
            $ccity.attr('data-id',ui.item.id);
            $ccity.attr('data-city',ui.item.city);
            $ccity.attr('data-state',ui.item.state);
            $ccity.attr('data-contryC',ui.item.contryC);
            $ccity.attr('data-type',ui.item.type);
            updateSearchStatic(ui.item.name,ui.item.lkname ,ui.item.pid, ui.item.chlink);
            $("input[name=searchfor]", $('#' + which)).focus();
            event.preventDefault();
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a class='auto_tuber'>" + item.label + "</a>")
                    .appendTo(ul);
    };
    var $searchfor = $("input[name=searchfor]", $('#' + which));
    $searchfor.autocomplete({
        appendTo: "#searchfor_id",
        select: function (event, ui) {
            $searchfor.val(ui.item.value);
            $searchfor.val(ui.item.city);
            $searchfor.val(ui.item.type);
            $searchfor.val(ui.item.state);
            $searchfor.val(ui.item.contryC);
            event.preventDefault();
        },
        source: function(request, response) {
            $.ajax({
               url: "/ajax/search_for",
               dataType: "json",
               data: {
                  term: request.term,
                  city: $ccity.attr('data-city'),
                  type: $ccity.attr('data-type'),
                  state: $ccity.attr('data-state'),
                  contryC: $ccity.attr('data-contryC')
               },
               success: function(data) {
                  response(data);
               }
            });
         },
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a class='auto_tuber'>" + item.label + "</a>")
                    .appendTo(ul);
    };
}
function updateSearchStatic(val,lkname,pid,chlink){
    var str='<a class="search-static-items" href="where-is-'+lkname+'-'+pid+'"><div class="itemspiccontent"><img width="44" height="43" alt="location" src="'+ReturnLink('images/search/search_static1.png')+'" class="search-static-items-pic"></div><div class="itemstextcontent">'+ t('Where is '+val+'?') +'</div></a>';
    str +='<a class="search-static-items" href="hotels-in-'+lkname+'-'+pid+'_1"><div class="itemspiccontent itemspiccontent1"><img width="44" height="43" alt="location" src="'+ReturnLink('images/search/search_static2.png')+'" class="search-static-items-pic"></div><div class="itemstextcontent">'+ t('Hotels in '+val+'') +'</div></a>';
    str +='<a class="search-static-items" href="restaurants-in-'+lkname+'-'+pid+'_1"><div class="itemspiccontent itemspiccontent2"><img width="44" height="43" alt="location" src="'+ReturnLink('images/search/search_static2.png')+'" class="search-static-items-pic"></div><div class="itemstextcontent">'+ t('Restaurants in '+val+'') +'</div></a>';
    str +='<div class="search-static-items"><div class="itemspiccontent itemspiccontent3"><img width="44" height="43" alt="location" src="'+ReturnLink('images/search/search_static3.png')+'" class="search-static-items-pic"></div><div class="itemstextcontent">'+ t('Things to do in '+val+'') +'</div></div>';
    str +='<a class="search-static-items" href="photos-videos-'+lkname+'-'+pid+'"><div class="itemspiccontent itemspiccontent4"><img width="44" height="43" alt="location" src="'+ReturnLink('images/search/search_static4.png')+'" class="search-static-items-pic"></div><div class="itemstextcontent">'+ t('Photos / Videos of '+val+'') +'</div></a>';
    str +='<a class="search-static-items" href="/channel-search/'+chlink+'"><div class="itemspiccontent itemspiccontent5"><img width="44" height="43" alt="location" src="'+ReturnLink('images/search/search_static5.png')+'" class="search-static-items-pic"></div><div class="itemstextcontent">'+ t('Channels about '+val+'') +'</div></a>';
   $('.search-static').html(str);
}