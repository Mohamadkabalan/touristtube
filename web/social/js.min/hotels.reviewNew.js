var shareFadeoutTimeout;
$(document).ready(function(){
    /*initSocialActions();
    $(".social_data_all").each(function(){
        var $this = $(this);        
        if ( $this.find('.likes').length > 0) initLikes( $this );
    });*/
    if (parseInt($('.hotelsContainer').attr('data-logged')) != 0) {
        InitChannelUploaderHome('uploadReview_Img', 'menu1', 15, 0);
    }
    $(".hotel_infos_imgbig").fancybox({
        helpers : {
            overlay : {closeClick:true}
        }
    });
    $(document).on('click', ".photoremove", function (e) {
        var $this = $(this).closest('.photoitems');
        var picid = $this.attr("data-id");
        TTAlert({
            msg: t('are you sure you want to remove permanently this image?') ,
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function(data){
                if(data){
                    $('.upload-overlay-loading-fix').show();
                    $.ajax({
                        url: ReturnLink('/ajax/remove_discover_image.php'),
                        data: {
                            id: picid,
                            entity_type: $('.hotelsContainer').attr('data-type')
                        },
                        type: 'post',
                        success: function (data) {
                            $('.upload-overlay-loading-fix').hide();
                            var ret = null;
                            try {
                                ret = $.parseJSON(data);
                            } catch (Ex) {
                                return;
                            }
                            if (ret.status == 'error') {
                                TTAlert({
                                    msg: ret.msg,
                                    type: 'alert',
                                    btn1: t('ok'),
                                    btn2: '',
                                    btn2Callback: null
                                });
                            } else {
                                $this.remove();
                            }
                        }
                    });
                }
            }
        });
    });
    $(document).on('click','.remove_button_rev',function(){
        var log_obj = $(this).closest('.social_data_all');
        TTAlert({
            msg: t('are you sure you want to remove this review'),
            type: 'action',
            btn1: t('cancel'),
            btn2: t('confirm'),
            btn2Callback: function(data){
                if(data){
                    $.ajax({
                        url: ReturnLink('/ajax/ajax_remove_review.php'),
                        data: {
                            id: log_obj.attr('data-id'),
                            data_type: $('.hotelsContainer').attr('data-type')
                        },
                        type: 'post',
                        success: function (data) {
                            $('.upload-overlay-loading-fix').hide();
                            var ret = null;
                            try {
                                ret = $.parseJSON(data);
                            } catch (Ex) {
                                return;
                            }
                            if (ret.status == 'error') {
                                TTAlert({
                                    msg: ret.msg,
                                    type: 'alert',
                                    btn1: t('ok'),
                                    btn2: '',
                                    btn2Callback: null
                                });
                            } else {
                                log_obj.remove();
                            }
                        }
                    });
                }
            }
        });        
    });
    $(document).on('click', ".review-buts2 a", function (e) {
        e.preventDefault();
        if ($('.share_link_holder').css('display') != "none") {
            $('.share_link_holder').hide();
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
            $('.share_link_holder').show();
            
            shareFadeoutTimeout = setTimeout(function () {
                $('.share_link_holder').hide();
                $('.review-buts2 a').removeClass('active');
            }, 1500);

            $('.share_link_holder').unbind('mouseenter mouseleave').hover(function () {
                clearTimeout(shareFadeoutTimeout);
                $('.share_link_holder').stop(true, true);
                $('.share_link_holder').show();
            }, function () {
                shareFadeoutTimeout = setTimeout(function () {
                    $('.share_link_holder').hide();
                    $('.review-buts2 a').removeClass('active');
                }, 500);
            });
        }
    });
    $(document).on('click', ".review-buts0 a", function (e) {
        e.preventDefault();
        $('.view-review').removeClass('active');
        $(this).addClass('active');
        $('#menu1').hide();
        $('#home').show();
    });
    $(document).on('click', ".review-buts1 a", function (e) {
        e.preventDefault();
        $('.view-review').removeClass('active');
        $(this).addClass('active');
        $('#home').hide();
        $('#menu1').show();
    });
    var pagedimensions = parent.window.returnIframeDimensions();
    $(".showOnMap").fancybox({
        width: pagedimensions[0],
        height: pagedimensions[1],
        closeBtn: true,
        autoSize: false,
        autoScale: true,
        transitionIn: 'elastic',
        transitionOut: 'fadeOut',
        type: 'iframe',
        padding: 0,
        margin: 0,
        scrolling: 'no',
        helpers : {
            overlay : {closeClick:true}
        }
    });
    $(document).on('click','.addtomychannelBut',function(){
        var chid = $('#channel_code').val();
        if(chid==''){
            TTAlert({
                msg: t('please select one of your channels...'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        var data_id = $('.hotelsContainer').attr('data-id');
        var data_type = $('.hotelsContainer').attr('data-type');
        $('.upload-overlay-loading-fix').show();
        $.ajax({
            url: ReturnLink('/ajax/ajax_add_discover_tochannel.php'),
            data: {
                id: data_id,
                data_type: data_type,
                channel_id:chid
            },
            type: 'post',
            success: function (data) {
                $('.upload-overlay-loading-fix').hide();
                var ret = null;
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.status == 'error') {
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
                   $('#channel_code').find('#channel_code' + chid ).remove(); 
                }
            }
        });
    });
    $(document).on('click', ".specificRate .eval_options_glob", function () {
        var $index = $(this).index();
        if (parseInt($('.hotelsContainer').attr('data-logged')) === 0) {
            //$(".TopRegisterLink").click();
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to evaluate.'),
                type: 'action',
                btn1: t('cancel'),
                btn2: t('register'),
                btn2Callback: function (data) {
                    if (data) {
                        window.location.href = ReturnLink('/register');
                    }
                }
            });
            return;
        }
        var $this = $(this);
        var $parents = $this.closest('.specificRate');
        var data_value = $(this).attr('data-value');
        TTCallAPI({
            what: ReturnLink('/ajax/modal_newsfeed_rate.php'),
            data: {entity_id: $('.hotelsContainer').attr('data-id'), entity_type: $parents.attr('data-type'), score: data_value, globchannelid: null},
            ret: 'json',
            callback: function (resp) {
                if (resp.status == 'error') {
                    TTAlert({
                        msg: resp.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                $parents.find('.eval_options_glob').removeClass('active');
                $this.addClass('active');
                $parents.find(".eval_options_glob").each(function () {
                    var newindex = $(this).index();
                    if (newindex <= $index) {
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                });
            }
        });
    });
    $(".revThemesOption span").click(function () {

        if (parseInt($('.hotelsContainer').attr('data-logged')) === 0) {
            //$(".TopRegisterLink").click();
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to select themes.'),
                type: 'action',
                btn1: t('cancel'),
                btn2: t('register'),
                btn2Callback: function (data) {
                    if (data) {
                        window.location.href = ReturnLink('/register');
                    }
                }
            });
            return;
        }

        $(this).toggleClass('active');
    });
    $(document).on('click', ".submitReview", function () {
        if (parseInt($('.hotelsContainer').attr('data-logged')) === 0) {
            //$(".TopRegisterLink").click();
            TTAlert({
                msg: t('you need to have a') + ' <a class="black_link" href="' + ReturnLink('/register') + '">' + t('TT account') + '</a> ' + t('in order to add reviews.'),
                type: 'action',
                btn1: t('cancel'),
                btn2: t('register'),
                btn2Callback: function (data) {
                    if (data) {
                        window.location.href = ReturnLink('/register');
                    }
                }
            });
            return;
        }
        var revTxt = $('.rev_description').val();
        var revThemes = new Array();
        var revDate = $('.rev_select').val();
//        var revRates = new Array();
        var revNear = new Array();
        var revChecked = ($(".certify.active").length == 1);
        if (revTxt === '') {
            TTAlert({
                msg: t('Your review cannot be empty !'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        if (!revChecked) {
            TTAlert({
                msg: t('Your have to certify that this review is based on your own experience !'),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
            return;
        }
        $(".revThemesOption.active").each(function (index) {
            revThemes.push($(this).attr('data-value'));
        });
        var revThemesstr = revThemes.join(',');
        $(".whatnear.active").each(function (index) {
            var val = $(this).attr('data-value');

            revNear.push(val);
        });
        var revNearstr = revNear.join(',');

        $.ajax({
            url: ReturnLink('/ajax/ajax_add_review.php'),
            data: {
                id: $('.hotelsContainer').attr('data-id'),
                data_type: $('.hotelsContainer').attr('data-type'),
                txt: revTxt,
                themes: revThemesstr,
                date: revDate,
                near: revNearstr
            },
            type: 'post',
            success: function (data) {
                $('.upload-overlay-loading-fix').hide();
                var ret = null;
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.status == 'error') {
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                } else {
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: function (e) {
                            location.reload();
                        }
                    });
                }
            }
        });

    });
    $(document).on('mouseover', ".specificRate .eval_options_glob", function () {
        var val_txt = $(this).attr('data-text');
        var parents = $(this).parent();
        parents.find('.eval_options_over .eval_options_overtxt').html(val_txt);
        parents.find('.eval_options_over').show();
    });
    $(document).on('mouseout', ".specificRate .eval_options_glob", function () {
        var parents = $(this).parent();
        parents.find('.eval_options_over').hide();
    });
    $(document).on('click', ".review1 .opt", function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        appendArrows:'.slider-for',
        asNavFor: '.slider-for',
        dots: false,
        centerMode: true,
        focusOnSelect: true
    });
    $(window).resize(function(){         
        setTimeout(function () {
            updateGallery();
        }, 1000);
    });
    updateGallery();
});
function updateGallery(){
    $('.slider-for').removeClass('opacity0');
    $('.slider-for .slick-track .slick-slide').each(function(){
        var $this = $(this);
        var objw=$this.width();
        var objh=$this.height();
        var bigw=$('.slider-for').width();
        var bigh=$('.slider-for').height();
        var mtop=0;
        var mleft=0;
        if(objh<bigh){
            mtop= Math.round( (bigh-objh)/2 );
        }
        if(objw<bigw){
            mleft= Math.round( (bigw-objw)/2 );
        }   
        $this.css('margin-top',mtop);
        $this.css('margin-left',mleft);
    });
}
function updateImage(str, pic_link, _type) {
    if (_type == "uploadReview_Img") {
        $.ajax({
            url: ReturnLink('/ajax/add_discover_image.php'),
            data: {
                item_id: $('.hotelsContainer').attr('data-id'),
                filename: userGlobalDISName() + '' + pic_link,
                entity_type: $('.hotelsContainer').attr('data-type')
            },
            type: 'post',
            success: function (data) {
                $('.upload-overlay-loading-fix').hide();
                var ret = null;
                try {
                    ret = $.parseJSON(data);
                } catch (Ex) {
                    return;
                }
                if (ret.status === 'error') {
                    TTAlert({
                        msg: ret.msg,
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                $('.yourPhotosContainer').prepend('<div class="col-xs-4 col-sm-4 col-md-3 col-lg-3 photoitems" data-id="'+ret.id+'"><div class="photoremove">X remove</div><img src="' + ReturnLink('media/discover/thumb/' + userGlobalDISName()+ pic_link) + '" class="width100"/></div>');
            }
        });
    }
    closeFancyBox();
}