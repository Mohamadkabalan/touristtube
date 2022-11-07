<?php
$path = "";
$bootOptions = array("loadDb" => 1, 'loadLocation' => 1, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/webcams.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/videos.php" );

//$url = db_sanitize($_GET['arg']);
//$url = $request->query->get('arg','');
$url = UriGetArg(0);
$webcam = webcamFromUrl($url);

if ($webcam === false) {
    header('Location:' . ReturnLink(''));
    exit;
}
$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}
$userIschannel=userIsChannel();
$userIschannel = ($userIschannel) ? 1 : 0;

$user_id = userGetID();
$userInfo = getUserInfo($user_id);
//webcamIncViews($webcam['id']); //increment number of views for the live cam
$data_action_id = $webcam['id'];
$suggestedCams = getSuggestedLiveCam($webcam['id'], $webcam['city_id']);

$webcamurl = $webcam['url'];
$CONFIG_uploadPath = $CONFIG['cam']['uploadPath'];
$fb_img = ReturnLink($CONFIG_uploadPath.$webcamurl.'.jpg');

$includes = array('js/jquery.selectBox.js','js/social-actions.js', 'css/jquery.selectBox.css', 'css/media_page.css?v='.MEDIA_PAGE_CSS_V, 'css/social-actions.css?v='.SOCIAL_ACTIONS_CSS_V, 'css/live.css', 
'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V, 
'media1'=>'css_media_query/live.css');

tt_global_set('includes', $includes);

include("TopIndex.php");

$uricurpage = UriCurrentPageURL();
$uricurserver = currentServerURL();

$vid = $webcam['id'];
$entity_type = SOCIAL_ENTITY_WEBCAM;
$current_id = $vid;
$liveCamTTL = $webcam['name'];
$sortviews = tt_number_format(intval($webcam['nb_views']));
if(intval($webcam['nb_views'])==1){
    $viewsText = _('play');
}else{
    $viewsText = _('plays');
}
$sortcomments = $webcam['nb_comments'];
$nb_comments_str = displayCommentsCount($webcam['nb_comments'],0);
$sortlikes = $webcam['like_value'];
$like_value_str = displayLikesCount($webcam['like_value'],0);
$sortshares = $webcam['nb_shares'];
$nb_shares_str = displaySharesCount($webcam['nb_shares'],0);

$longitude = $webcam['longitude'];
$latitude = $webcam['latitude'];

$favorite = '';
$data_status_favorite = '';
$data_is_favorite = 'favorite';
if ($user_id) {
    if (socialFavoriteAdded($user_id, $vid, SOCIAL_ENTITY_WEBCAM)) {
        $favorite = ' active';
        $data_is_favorite = 'removefavorite';
        $data_status_favorite = 'yellow';
    }
}
$seo_bread_crumbs = seoBreadCrumbs(array('entity_id' => $vid, 'entity_type' => SOCIAL_ENTITY_WEBCAM));
?>
<div id="MiddleInside" class="liveCamCon">

    <div class="mrgintop66" id="InsideContainer">
        <div id="VideoBreadCrumb"><?php echo $seo_bread_crumbs; ?></div>
        <h1 id="liveInsideTTL"><?php echo $liveCamTTL; ?></h1>

        <div class="linestyle_container">
            <div class="linestyle1"></div>
            <div class="linestyle2"></div>
            <div class="mediainfo_data">
                <ul>
                    <li style="background:none; padding:0; width:auto; margin-right:32px;"><span class="mediaInfoYellow sortviews"><?php echo $sortviews; ?></span><br><?php echo $viewsText; ?></li>
                    <li style="background:none; padding:0; width:auto; margin-right:32px;"><span class="mediaInfoYellow sortlikes"><?php echo $sortlikes; ?></span><br><?php echo $like_value_str; ?></li>
                    <li style="background:none; padding:0; width:auto; margin-right:32px;"><span class="mediaInfoYellow sortcomments"><?php echo $sortcomments; ?></span><br><?php echo $nb_comments_str; ?></li>
                    <li style="background:none; padding:0; width:auto; margin-right:32px;"><span class="mediaInfoYellow sortshares"><?php echo $sortshares; ?></span><br><?php echo $nb_shares_str; ?></li>
                </ul>
            </div>
        </div>
        <div id="RightInsideContainer">
            <?php include('parts/social-cam-buttons.php'); ?>
            
            <?php if ($suggestedCams) { ?>
                <div id="BottomProfile">
                    <h2 class="BottomProfileTitle"><?php print _("suggested live cams");?></h2>
                    <div id="BottomProfileList">
                        <table class="poptableclass">
                            <?php foreach ($suggestedCams as $suggestedCam) { ?>
                                <tr>
                                    <td class="poptableclassTD100">
                                        <div class="suggCams">
                                            <a title="<?php echo $suggestedCam['name']; ?>" class="VideoMoreThumbLink" href="<?php echo ReturnWebcamUri($suggestedCam); ?>">
                                                <div class="insidevideolistimage_more"></div>
                                                <div class="VideoViewIcon_more enlarge_video"></div>
                                                <div class="insidevideomore">
                                                    <div class="insidevideolistrating"><?php echo $suggestedCam['nb_views'] . ' ' . _('views'); ?></div>
                                                </div>
                                                <!--<img width="120" height="90" border="0" alt="<?php echo $suggestedCam['name']; ?>" src="../images/discover/liveTTIco.png">-->
                                                <img width="120" height="90" border="0" alt="<?php echo $suggestedCam['name']; ?>" src="<?php GetLink($CONFIG['cam']['uploadPath'] . $suggestedCam['url'] . '.jpg') ?>">
                                            </a>
                                        </div>
                                        <div class="BottomProfileImageDesc"><!--<div class="BottomProfileImageDesc_bk"></div><span>(40)</span>--></div>
                                    </td>
                                    <td class="poptableclassTD5"></td>
                                    <td class="poptableclassTDtop">
                                        <div class="videoInsideTitle black12">
                                            <?php                                            
                                            $city_id2 = $suggestedCam['city_id'];
                                            $city_record2 = cityGetInfo($city_id2);
                                            echo $city_record2['accent'];
                                            $country2 = countryGetName($city_record2['country_code']);
                                            echo ', ';
                                            echo $country2;
                                            ?>
                                        </div>
                                        <div class="videoInsideDescription grey10"><?php echo $suggestedCam['name']; ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="poptableclassTDH5"></td><td></td><td></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            <?php } ?>

        </div>

        <div id="BigImageHolder">
            <?php  $HTTP_USER_AGENT_server = $request->server->get('HTTP_USER_AGENT', '');
                if (isset($HTTP_USER_AGENT_server) && (strpos($HTTP_USER_AGENT_server, 'MSIE') !== false) || true): ?>
                <div class="play_live_cam" id="play_live_cam" align="center"></div>
                <input type="hidden" id="still_url" value="<?php echo $webcam['still_url'] ?>?resolution=320x240"/>
                <img src="<?php echo $webcam['still_url'] ?>?resolution=320x240" alt="<?php echo htmlEntityDecode($webcam['name']) ?>" style="width: 700px; height: 525px" id="feed1" />
                <img src="<?php echo $webcam['still_url'] ?>?resolution=320x240" alt="<?php echo htmlEntityDecode($webcam['name']) ?>" style="width: 700px; height: 525px; display: none" id="feed2" />
                <script type="text/javascript">
                    $(document).ready(function() {
                        $(document).on('click',".play_live_cam" ,function(){
                            $('.play_live_cam').hide();
                            $('#feed2').load(function() {
                                $('#feed1').hide();
                                $('#feed2').show();

                                $('#feed1').attr('src', $('#still_url').val() + '&x=' + GUID());
                            });
                            $('#feed1').load(function() {
                                $('#feed2').hide();
                                $('#feed1').show();

                                $('#feed2').attr('src', $('#still_url').val() + '&x=' + GUID());
                            });
                            $('#feed1').attr('src', $('#still_url').val() + '&x=' + GUID());
                        });
                        $('#feed1').attr('src', $('#still_url').val() + '&x=' + GUID());
                    });
                </script>
            <?php else: ?>
                <img src="<?php echo $webcam['live_url'] ?>?resolution=320x240" alt="<?php echo htmlEntityDecode($webcam['name']) ?>" style="width: 700px; height: 525px" id="feed" />
            <?php endif ?>

        </div>
        <div class="shareFavCont">
            <div class="share_link_holder simplemode">
                <div class="share_link_arrow"></div>
                <input type="text" readonly="readonly" value="<?php echo $uricurpage; ?>" class="share_link_text" name="share_link_text"/>
                <div class="share_link_buttons">
                    <a target="_blank" rel="nofollow" href="http://www.facebook.com/sharer.php?s=100&amp;p[url]=<?php echo $uricurpage; ?>"><div class="share_link_butts share_link_butts_fck"></div></a>
                    <a target="_blank" rel="nofollow" href="https://plus.google.com/share?url=<?php echo $uricurpage; ?>"><div class="share_link_butts share_link_butts_gol"></div></a>
                    <a target="_blank" rel="nofollow" href="http://www.twitter.com/intent/tweet?url=<?php echo $uricurpage; ?>"><div class="share_link_butts share_link_butts_twit"></div></a>
                    <a target="_blank" rel="nofollow" href="https://pinterest.com/pin/create/bookmarklet/?media=<?php echo $fb_img; ?>&url=<?php echo $uricurpage; ?>"><div class="share_link_butts share_link_butts_pint"></div></a>
                </div>
            </div>
            <div id="media_buttons">
                <div class="mediabuttonsOver">
                    <div class="ProfileHeaderOverin"></div>
                    <div class="icons-overtik"></div>
                </div>
                <div class="LiveFeedButtons">
                    <div id="favorite" class="MediaButton_data MediaButton_data_favorite<?php echo $favorite; ?>" data-title="<?php echo _($data_is_favorite) ?>" data-statu="<?php echo $data_status_favorite ?>"></div>
                    <div id="share_link" class="MediaButton_data MediaButton_data_share" data-title="<?php echo _('share link') ?>" data-statu="<?php echo $data_status_favorite ?>"></div>
                </div>
            </div>
        </div>
        <div class="liveCamComments">
            <?php
            $_GET['type'] = $request->query->set('type','w');
            include('parts/comment_section.php');
            ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true<?php echo $MAP_KEY; ?>"></script>
<script type="text/javascript" src="<?php GetLink("js/map_locations.js") ?>"></script>
<script type="text/javascript">
    var user_Is_channel = '<?php echo $userIschannel; ?>';
    var user_is_logged = '<?php echo $user_is_logged; ?>';
    var is_owner =0;
    var shareFadeoutTimeout;
                $(document).ready(function() {
                    initSocialActions();
//                    if ($(".social_data_all").find('#likes').length > 0) initLikes( $(".social_data_all") );
                    $('.btn_enabled').show();
//                    if ($(".social_data_all").find('#comments').length > 0) initComments( $(".social_data_all") );
//                    if ($(".social_data_all").find('#shares').length > 0) initShares( $(".social_data_all") );
                    LocationMap('LocationMap2',<?php echo $webcam['latitude'] ?>,<?php echo $webcam['longitude'] ?>, '<?php echo addslashes(htmlEntityDecode($webcam['name'])) ?>');
                    incrementObjectsViews(  $(".social_data_all").attr('data-type') , $(".social_data_all").attr("data-id") );
                    
                    $(document).on('click',"#Commentsmore" ,function(){
                        if(parseInt($('.social_data_all').attr('data-enable'))==1 || parseInt(is_owner)==1){
                            $('#comments').click();
                            $("html, body").animate({ scrollTop: $('#comments').offset().top}, 1);	
                        }
                    });
                    $(document).on('click', ".MediaButton_data_share", function() {
                        if ($('.share_link_holder').css('display') !="none") {
                            $('.share_link_holder').hide();
                            $('.mediabuttonsOver').removeClass('inactive');
                            $(this).removeClass('active');
                        } else {
                            $('.mediabuttonsOver').addClass('inactive');
                            $(this).addClass('active');
                            $('.share_link_holder').show();
                            $('.mediabuttonsOver').hide();
                            
                            shareFadeoutTimeout = setTimeout(function () {
                                $('.share_link_holder').hide();
                                $('.mediabuttonsOver').removeClass('inactive');
                                $('.MediaButton_data_share').removeClass('active');
                            }, 1500);

                            $('.share_link_holder').unbind('mouseenter mouseleave').hover(function () {
                                clearTimeout(shareFadeoutTimeout);
                                $('.share_link_holder').stop(true, true);
                                $('.share_link_holder').show();
                            }, function () {
                                shareFadeoutTimeout = setTimeout(function () {
                                    $('.share_link_holder').hide();
                                    $('.mediabuttonsOver').removeClass('inactive');
                                    $('.MediaButton_data_share').removeClass('active');
                                }, 500);
                            });
                        }
                    });

                    $('#favorite').click(function() {
                        var $button = $(this);
                        if ($(this).data('reset')) {

                        }
                        TTCallAPI({
                            what: 'social/favorite',
                            data: {entity_id: '<?php echo $vid;?>', entity_type: SOCIAL_ENTITY_WEBCAM, channel_id: ''},
                            callback: function(data) {

                                $button.removeData('reset');

                                if (data.status == 'ok') {
                                    if (data.favorite == 0) {
                                        $('#favorite').removeClass('active');
                                        $('#favorite').attr('data-title', t('add to favorites') );
                                    } else {
                                        $('#favorite').addClass('active');
                                        $('#favorite').attr('data-title', t('remove from favorites') );
                                    }
                                    $('.mediabuttonsOver .ProfileHeaderOverin').html($('#favorite').attr('data-title'));
                                } else {
                                    TTAlert({
                                        msg: data.msg,
                                        type: 'action',
                                        btn1: 'sign in',
                                        btn2: 'register',
                                        btn2Callback: function(data) {
                                            if (data) {
                                                window.location.href = ReturnLink('/register');
                                            } else {
                                                SignInTO = setTimeout(function() {
                                                    $('#SignInDiv').fadeIn();
                                                    signflag = 1;
                                                }, 300);
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    });
                });
</script>
<?php include("BottomIndex.php"); ?>