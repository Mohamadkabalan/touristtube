<?php
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
$user_id = userGetID();

$limit = 20;
$currentpage = 0;
if (UriArgIsset('page')):
    $currentpage = intval(UriGetArg('page'));
else:
    $currentpage = 0;
endif;

$srch_optionsnew = array(
    'user_id' => $user_id,
    'orderby' => 'favorite_ts',
    'types' => array(SOCIAL_ENTITY_MEDIA, SOCIAL_ENTITY_WEBCAM),
    'n_results' => true
);
$ret_count = socialFavoritesGet($srch_optionsnew);

$options = array(
    'limit' => $limit,
    'page' => $currentpage,
    'user_id' => $user_id,
    'order' => 'd',
    'orderby' => 'favorite_ts',
    'types' => array(SOCIAL_ENTITY_MEDIA, SOCIAL_ENTITY_WEBCAM)
);

$ret = socialFavoritesGet($options);
$ret = (!$ret) ? array() : $ret;
//debug($ret);

$includes = array('media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,'media1'=>'css_media_query/favorites_media.css?v='.MQ_FAVORITES_MEDIA_CSS_V,'js/jquery.selectbox-0.6.1.js', 'css/jquery.selectbox.1.css', 'assets/tuber/js/favorites.js', 'css/favorites.css');

if (userIsLogged() && userIsChannel()) {
    array_unshift($includes, 'css/channel-header.css');
   tt_global_set('includes', $includes);
    include("TopChannel.php");
} else {
   tt_global_set('includes', $includes);
    include("TopIndex.php");
}

$userInfo = getUserInfo(userGetID());
$creator_avatar_link = userProfileLink($userInfo) . '/TTpage';
$section = 'favorites';
$realUser = true;
include('parts/profile_header.php');
$i = 0;
$len = count($ret);
?>

<div class="upload-overlay-loading-fix"><div></div></div>
<div id="contentcontainer">

    <div id="favoritescontainer">
        <div id="head_media_container">
            <div id="media_container">
                <div class="data_head_text"><?php echo _('MY FAVORITES'); ?> <span class="yellowbold12">(<?php echo $ret_count; ?>)</span></div>
                <div class="clearboth"></div>
                <div class="linestyle_container" style="width:981px;">
                    <div class="linestyle1"></div>
                    <div class="linestyle2"></div>
                </div>
            </div>
        </div>
        <div id="favoritesimagecontainerup">
            <?php if ($len > 0) { ?>
                <div id="favoritesimagecontainer" data-cnt="<?php echo $ret_count; ?>">
                    <ul>
                        <?php
                        for ($i = 0; $i < $len; $i++) {
                            $mydata = $ret[$i];
                            $entityType = $mydata['entity_type'];
                            $info = array();
                            $imageVideo = '';
                            $title = '';
                            $impathss = '';
                            if ($entityType == SOCIAL_ENTITY_MEDIA) {
                                $info = getVideoInfo($mydata['entity_id']);

                                $imageVideo = $info['image_video'];
                                $title = htmlEntityDecode($info['title']);
                                $impathss = photoReturnThumbSrc($info);
                            } else if ($entityType == SOCIAL_ENTITY_WEBCAM) {
                                $info = webcamGetInfo($mydata['entity_id']);

                                $imageVideo = 'v';
                                $title = htmlEntityDecode($info['name']);
                                $impathss = ReturnLink($CONFIG['cam']['uploadPath'] . $info['url'] . '.jpg');
                            }
//                        exit(debug($info));
                            $tim = $mydata['favorite_ts'];
                            $date = returnSocialTimeFormat($tim,3);
                            echo '<li id="' . $mydata['entity_id'] . '" class="" data-type="' . $imageVideo . '" data-val="' . $entityType . '">';
                            echo '<div class="imgbk">';
                            echo '<div class="imgdate">' . $date . '</div>';
                            echo '<div class="imgtitle">' . $title . '</div>';
                            echo '<div class="imgbk_butons">';
                            echo '<div class="clsimg imgbk_buts" data-title="' . _("remove") . '"></div>';
                            echo '</div>';
                            echo '<div class="imgbk_footer">';
                            if ($imageVideo == 'v') {
                                echo '<div class="imgbk_info">' . tt_number_format($info['nb_views']) . '<br/><span>' . displayPlaysCount($info['nb_views'], 0) . '</span></div>';
                            } else {
                                echo '<div class="imgbk_info">' . tt_number_format($info['nb_views']) . '<br/><span>' . displayViewsCount($info['nb_views'], 0) . '</span></div>';
                            }
                            echo '<div class="imgbk_info">' . tt_number_format($info['like_value']) . '<br/><span>' . displayLikesCount($info['like_value'], 0) . '</span></div>';
                            echo '<div class="imgbk_info">' . tt_number_format($info['nb_comments']) . '<br/><span>' . displayCommentsCount($info['nb_comments'], 0) . '</span></div>';
                            echo '<div class="imgbk_info" style="margin-right:0">' . tt_number_format($info['nb_shares']) . '<br/><span>' . displaySharesCount($info['nb_shares'], 0) . '</span></div>';
                            echo '</div>';
                            echo '</div>';
                            $media_uri = '';
                            if ($imageVideo == 'v') {
                                if ($entityType == SOCIAL_ENTITY_MEDIA) {
                                    $media_uri = ReturnVideoUriHashed($info);
                                } else if ($entityType == SOCIAL_ENTITY_WEBCAM) {
                                    $media_uri = ReturnWebcamUri($info);
                                }
                                echo '<a href="' . $media_uri . '" title="' . $title . '" target="_blank">'
                                . '<div class="vidimg selectitem" style="opacity:0" data-type="2">'
                                . ' <div class="selectitem_footer">'
                                . '     <div class="ratingvalueimg' . intval($info['rating']) . ' imgstatistvaluerating">'
                                . '     </div>'
                                . ' </div>'
                                . '</div>'
                                . '</a>';
                            } else {
                                $media_uri = ReturnPhotoUri($info);
                                echo '<a href="' . $media_uri . '" title="' . $title . '" target="_blank">'
                                . '<div class="photoimg selectitem" style="opacity:0" data-type="1">'
                                . ' <div class="selectitem_footer">'
                                . '     <div class="ratingvalueimg' . intval($info['rating']) . ' imgstatistvaluerating">'
                                . '     </div>'
                                . ' </div>'
                                . '</div>'
                                . '</a>';
                            }
                            echo '<a href="' . $media_uri . '" title="' . $title . '" target="_blank">'
                            . '<img class="" src="' . $impathss . '" border="0" '
                            . 'width="189" height="106" alt="' . $title . '"/>'
                            . '</a>';

                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
                <?php
            } else {
                echo '<div class="notFoundCon">'
                . _("No favorites found") .
                "</div>";
            }
            ?>
        </div>
        <div class="clearboth"></div>
        <div class="buttonmorecontainerup">
            <div class="buttonmorecontainer margintop30" style="float:none; width:981px;">
                <?php
                $pagcount = floor($ret_count / $limit);
                if (($ret_count % $limit) != 0) {
                    $pagcount++;
                }
                if ($pagcount > ($currentpage + 1)) {
                    ?>

                    <a id="load_more_next" class="load_more_previous_new" style="margin:0;" href="<?php echo ReturnLink('favorites/page/' . ($currentpage + 1)); ?>"><?php echo _('Load More...'); ?></a>
                    <?php
                }
                ?>
                <?php if (UriArgIsset('page') && ($currentpage != 0)): ?>
                    <a id="load_more_previous" class="load_more_previous_new" style="margin:0;" href="<?php echo ReturnLink('favorites/page/' . ($currentpage - 1)); ?>"><?php echo _('Load Previous...'); ?></a>
                <?php endif ?>
                <script>
                    $(document).ready(function(e) {
                        var load_more_next_diff = 4;
                        if ($('#load_more_next').length > 0) {
                            load_more_next_diff += 29;
                        }
                        if ($('#load_more_previous').length > 0) {
                            load_more_next_diff += 29;
                        }
                        $('.buttonmorecontainer').css('width', ($('#load_more_next').width() + $('#load_more_previous').width() + load_more_next_diff) + "px");
                    });
                </script>
            </div>
        </div>

    </div>

</div>
<?php
include("BottomIndex.php");
