var currentpage_like = 0;
var currentpage_rates = 0;
var currentpage_comments = 0;
var currentpage_replies = 0;
var currentpage_reechoes = 0;
var currentpage_shares = 0;
var currentpage_sponsors = 0;
var currentpage_userjoin = 0;

function initSocialActions() {
    $(document).on('click', ".socialButtons", function () {
        var $this = $(this);
        var $parents = $this.closest('.social_data_all');
        if ($(this).hasClass('opacity0')) {
            return;
        }
        if (!$this.hasClass('active')) {
            var thisID = '';
            if( $this.hasClass('likes') ){
                thisID = "likes";
            }else if( $this.hasClass('comments') ){
                thisID = "comments";
            }else if( $this.hasClass('shares') ){
                thisID = "shares";
            }            
            $parents.find('.socialButtons').removeClass('active');
            $parents.find('.socialInitDiv').hide();
            if ($("." + thisID + "Div")) $parents.find("." + thisID + "Div").show();
            $this.addClass('active');
        }
    });
} // End initSocialActions.
function initLikes(media_object) {
    currentpage_like = 0;
    media_object.attr("data-page-like", 0);
    $.ajax({
        url: '/ajax/ajax_all_likes',
        data: {entity_type: media_object.attr("data-type"), entity_id: media_object.attr("data-id"), page: 0, channelid: channelGlobalID()},
        type: 'post',
        cache: false,
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                var myData = ret.data;
                var divNum = parseInt(ret.count);
                var is_liked = ret.is_liked;

                media_object.find('.likesDiv .containerDiv').html('');
                media_object.find('.likesDiv .containerDiv').html('<div class="commentsAll max_height412"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".likesNumber").html(divNum);
                $(".sortlikes").html(divNum);
                
                // Set the "like" / "unlike" button.
                if (is_liked == 1) {
                    media_object.find('.likeDiv').attr('data-action', 'unlike');
                    media_object.find('.likeDiv').html('<span> ' + t("unlike") + '</span>');
                    media_object.find('.likeDiv').addClass('active');
                } else {
                    media_object.find('.likeDiv').attr('data-action', 'like');
                    media_object.find('.likeDiv').html('<span> ' + t("like") + '</span>');
                    media_object.find('.likeDiv').removeClass('active');
                }
                if (divNum <= 7) {
                    media_object.find(".likesDiv .showMore_like").hide();
                } else {
                    media_object.find(".likesDiv .showMore_like").show();
                }
                media_object.find(".likesDiv .commentsAll").addClass("scrollpane_likes");

                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_l').length > 0) {
                    var nb_likes = divNum;
                    if (nb_likes > 1 || nb_likes == 0) {
                        item_list.find('.news_count_l').html(nb_likes + " " + t('likes'));
                    } else {
                        item_list.find('.news_count_l').html(nb_likes + " " + t('like'));
                    }
                }

                //initLikersData();
            }
        }
    });
}
function initComments(media_object) {
    currentpage_comments = 0;
    media_object.attr("data-page-comments", 0);
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_comments_channel_media.php?no_cache=' + Math.random()),
        data: {media: media_object.attr("data-type"), mediaid: media_object.attr("data-id"), page: 0, globchannelid: channelGlobalID(), pagename: pagename},
        type: 'post',
        cache: false,
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                var myData = ret.data;
                var divNum = ret.count;

                media_object.find('.commentsDiv .containerDiv').html('');
                media_object.find('.commentsDiv .containerDiv').html('<div class="commentsAll max_height412"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".commentsNumber").html(divNum);
                $(".sortcomments").html(divNum);
                if (divNum <= 5) {
                    media_object.find(".commentsDiv .showMore_comments").hide();
                } else {
                    media_object.find(".commentsDiv .showMore_comments").show();
                }
                media_object.find(".commentsDiv .commentsAll").addClass("scrollpane_comments");

                initLikersDataComments();
            }
        }
    });
}

function initShares(media_object) {
    var shares_height = 'max_height412';
    var mylimit = 7;
    if (parseInt(user_Is_channel) == 0 && parseInt(user_is_logged) == 1) {
        shares_height = 'max_height221';
        mylimit = 4;
    }
    if (media_object.find('.sharesDiv #share_select1').length > 0 || media_object.find('.sharesDiv #share_select').length == 0) {
        shares_height = 'max_height412';
        mylimit = 7;
    }
    currentpage_shares = 0;
    media_object.attr("data-page-shares", 0);
    $.ajax({
        url: ReturnLink('/ajax/ajax_all_shares_channel_media.php?no_cache=' + Math.random()),
        data: {media: media_object.attr("data-type"), mediaid: media_object.attr("data-id"), page: 0, globchannelid: channelGlobalID(), mylimit: mylimit},
        type: 'post',
        cache: false,
        success: function (data) {
            var ret = null;
            try {
                ret = $.parseJSON(data);
            } catch (Ex) {
                return;
            }
            if (ret.error) {
                TTAlert({
                    msg: ret.error,
                    type: 'alert',
                    btn1: t('ok'),
                    btn2: '',
                    btn2Callback: null
                });
                return;
            } else {
                var myData = ret.data;
                var divNum = ret.count;

                media_object.find('.sharesDiv .containerDiv').html('');
                media_object.find('.sharesDiv .containerDiv')
                        .html('<div class="commentsAll ' + shares_height + '"><div class="commentsAll_inside">' + myData + '</div></div>');
                media_object.find(".sharesNumber").html(divNum);
                $(".sortshares").html(divNum);
                var item_list = media_object.closest('.log_item_list');
                if (item_list.find('.news_count_s').length > 0) {
                    if (divNum > 1 || divNum == 0) {
                        item_list.find('.news_count_s').html(divNum + " " + t('shares'));
                    } else {
                        item_list.find('.news_count_s').html(divNum + " " + t('share'));
                    }
                }

                if (divNum <= mylimit) {
                    media_object.find(".sharesDiv .showMore_shares").hide();
                } else {
                    currentpage_shares = parseInt(media_object.attr("data-page-shares"));
                    if ((currentpage_shares + 1) * mylimit >= divNum) {
                        media_object.find(".sharesDiv .showMore_shares").hide();
                    } else {
                        media_object.find(".sharesDiv .showMore_shares").show();
                    }
                }
                media_object.find(".sharesDiv .commentsAll").addClass("scrollpane_shares");
                initLikersDataShares();
            }
        }
    });
}