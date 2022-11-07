<?php

$path =
        "";
$currentpage =
        0;
$bootOptions =
        array(
    "loadDb" => 1,
    "loadLocation" => 0,
    "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/videos.php" );

$userid = userGetID();
if (UriArgIsset('page')):
    $currentpage = intval(UriGetArg('page'));
else:
    $currentpage = 0;
endif;

$includes = array('css/channel.css',
    'assets/channel/js/channel.js',
    'assets/channel/js/channel-header.js',
    'css/channel-header.css',
	'js/swiper.js',
	'js/media_category_dd.js',
	'css_media_query/swiper_slider.css?v='.MQ_SWIPER_SLIDER_CSS_V,
    'media'=>'css_media_query/media_channel_search.css?v='.MQ_MEDIA_STYLE_CSS_V, 
	'media1'=>'css_media_query/channel_media.css?v='.MQ_CHANNEL_MEDIA_CSS_V, 
	);
	
	
tt_global_set('includes', $includes);
include("TopChannel.php");

?>
<script type="text/javascript">
    var globchannelid = '';
    channelGlobalID(globchannelid);
    $(document).ready(function () {
        reintialiseslider();
    });
</script>
<div class="upload-overlay-loading-fix"><div></div></div>
<div id="MiddleBody">    
    <div class="evContainer2Over">
        <div class="ProfileHeaderOverin"></div>
        <div class="icons-overtik"></div>
    </div>
    <div id="sliderContent" class="sliderContent_channel">
      
     <!-- desktop -->
        <div class="sliderGallery swiper-container">
                <?php
                //category_id comes from
                $options =
                        array(
                    'limit' => 15,
                    'page' => 0,
                    'type' => 'v',
                    'orderby' => 'pdate',
                    'order' => 'd',
                    'channel_id' => -2);
                $thumbVideosData = mediaSearch($options);
                $n_thumbs =
                        count($thumbVideosData);
                $item_width =
                        239;
                $items_width =
                        $n_thumbs *
                        $item_width;
                ?>
            <div class="items swiper-wrapper" style="left:0px; width: <?php echo $items_width; ?>px">
    <?php
    foreach ($thumbVideosData as $oneThumVideo) {
        $vid_code = $oneThumVideo['code'];
        $imgdir = "thumbnails";
        $max_title = 26;
        $pdate = $oneThumVideo['pdate'];
        $duration = $oneThumVideo['duration'];
        $linktogogle = currentServerURL().ReturnVideoUriHashed($oneThumVideo);
        $description = htmlEntityDecode($oneThumVideo['description']);
        $val_db = htmlEntityDecode($oneThumVideo['title']);
        $title = cut_sentence_length($val_db, $max_title);
        $description = ($description=='')? $title : $description;
        ?>
                   <div style="float:left" class="imageitem  swiper-slide" itemscope itemtype="http://schema.org/VideoObject">
                        <a class="iconinslider" href="<?php GetVideoUriHashed($oneThumVideo) ?>" style="opacity: 0; display: block; overflow: hidden;">
                            <div class="VideoViewPane" itemprop="name"><?php echo $title ?></div>
                            <div class="VideoViewIconLarge"></div>
                        </a>
                        <a href="<?php GetVideoUriHashed($oneThumVideo) ?>" class="IndexRecentLink" title="<?php echo $title; ?>">
                            <img src="<?php echo videoReturnThumbSrc($oneThumVideo); ?>" itemprop="thumbnailUrl" class="IndexRecentThumb right-middle" alt="<?php echo $title; ?>" />
                        </a>
                            <link itemprop="contentUrl" href="<?php echo $linktogogle ?>" />
                            <meta itemprop="uploadDate" content="<?php echo $pdate ?>"/>
                            <meta itemprop="duration" content="<?php echo $duration ?>" />
                            <span class="displaynone" itemprop="description"><?php echo $description ?></span>
                    </div>
                <?php } ?>
            </div>
            
            
            <div id="slidercontainer">
                <div class="sliderBar_text"><?php echo _('recent videos'); ?></div>
                <div class="sliderWrap">
                    <div class="sliderBar"></div>
                </div>
            </div>
        </div>
        
     <!-- desktop -->   
    </div>
                <?php
                $defaultchannelarray =
                        userCurrentChannelGet();
                if ($defaultchannelarray !=
                        false) {
                    $defaultchannelid =
                            $defaultchannelarray["id"];
                    $defaultchannelowner =
                            $defaultchannelarray["owner_id"];
                    ?>
        <div id="other_channels_slider"><?php include("parts/other_channels_slider.php"); ?></div>
<?php } ?>
    <div id="ChannelContainer" class="margintop79">
        <div id="LeftMainChannel">
            <h1 class="channelyellowttl marginleft10">
            <a href="javascript:void(0);"><?php echo _('Categories') ?></a>
            </h1>         
            
            <div id="Categories">
                <div id="CategoryMarker" style="display:none; position: absolute;"></div>
                <div id="CategoryVerticalLine"></div>
                <ul class="marginleft20 cat_content">
                    <?php include("parts/channel_category_list_inside.php"); ?>
                </ul>
            </div>
        </div>

        <div id="MiddelMainChannel">
            <div id="scrollableChannel">
                                <?php
                                $j = 0;
                                $limitcategory =8;
                                $categoriesAll = channelGetCategoryList( array('id' => 0,'limit' => $limitcategory,'start' => $limitcategory *$currentpage,'has_data' => true));
                                $categoriesAllnum = channelGetCategoryList(array('id' => 0,'has_data' => true,'n_results' => true));
                                
                                foreach ($categoriesAll as $cat) {
                                    $catId = $cat["id"];
                                    $catName = $cat["title"];
                                    $catImage = $cat["image"];

                                    $oneChannel = allchannelGetInfo(0,4,$catId);
                                    $channelcount = getchannelCount($catId);
                                    $catlink = seoEncodeURL($catName);
                                    if ($oneChannel) {
                                        ?>
                        <div class="MediaCont" id="<?php echo $catId; ?>" data-count="<?php echo $channelcount; ?>">
                            <div class="Line"></div>
                            <?php if( $channelcount > 4 ){ ?>
                                <div class="Expand"></div>
                            <?php } ?>
                            <h2 class="TTL h2bottom0"><a href="<?php GetLink('channels-category/' . $catlink); ?>" title="<?php echo $catName; ?>"><?php echo $catName; ?> <span class="YellowTTL">(<?php echo $channelcount; ?>)</span></a></h2>
                            <div class="MediaList">
                                <a href="<?php GetLink('channels-category/' . $catlink); ?>" title="<?php echo $catName; ?>" class="MediaListImage<?php if (file_exists('images/channel/category/' . $catImage)) echo ' image_category' . $catId; ?>">

                                </a>
        <?php ?>
                                <div class="ListAll" id="MediaChannel_<?php echo $catId; ?>" data-catId="<?php echo $catId; ?>" data-number="<?php echo count($oneChannel); ?>">
                                    <ul>
        <?php
        $k = 0;
        foreach ($oneChannel as $channelInfo) {
            $k++;
            //photoThumbnailCreate($in_path,$out_path, $w,$h);
            $channel_id = $channelInfo["id"];
            $channel_name = $channelInfo["channel_name"];
            $channel_header = $channelInfo["header"];
            $channel_created_date = returnSocialTimeFormat( $channelInfo["create_ts"] ,3);
            $channel_owner_id = $channelInfo["owner_id"];
            $channel_video_count_init = channelCountMediaInfo($channel_id,'v',0,0);
            $channel_video_count =tt_number_format($channel_video_count_init);
            $channel_image_count_init = channelCountMediaInfo($channel_id,'i',0,0);
            $channel_image_count = tt_number_format($channel_image_count_init);

            $optionsC = array(
                'channelid' => $channelInfo['id'],
                'user_id' => $channelInfo['owner_id'],
                'n_results' => true);
            $channelalbumInfoCount_init =userCatalogSearch($optionsC);
            $channelalbumInfoCount =tt_number_format($channelalbumInfoCount_init);
            $channel_location =str_replace(" - ",", ",channelOwnerLocation($channelInfo));
            $channel_connections =tt_number_format(countConnectedtubers($channel_id));
            $channel_connectionsdisp =countConnectedtubers($channel_id);
            ?>
                        <li data-vid="<?php echo $channel_id; ?>">

                            <div class="plusSign all" data-display="4"></div>

                            <div class="image">
                                <?php if ($channel_header) { ?>
                                    <img width="136" height="76" src="<?php echo createchannelThumb($channelInfo,0,0,136,76); ?>" alt="<?php echo $channel_name; ?>"/>
                                <?php } else { ?>
                                    <img width="136" height="76" src="<?php echo ReturnLink('images/channel/preview_coverphoto.jpg'); ?>" alt="<?php echo $channel_name; ?>"/>
                                <?php } ?>
                                <div class="insideBorder play"></div>
                                <a class="stanClick" href="<?php echo channelMainLink($channelInfo); ?>" title="<?php echo $channel_name; ?>"></a>
                                <div class="closeSign hide" data-flag="MediaChannel_<?php echo $catId; ?>" data-number="<?php echo $channelcount; ?>"></div>
                            </div>
            <?php
            if (is_arabic($channel_name)) {
                $classnew =
                        " arabic";
            } else {
                $classnew =
                        "";
            }
            ?>
                                                <div class="ttl<?php echo $classnew; ?> linkpointer" onclick="GoLink('<?php echo channelMainLink($channelInfo); ?>')"><?php echo $channel_name; ?><br /><span class="font10 linkdefault">(<?php printf(ngettext("%d connection", "%d connections", $channel_connectionsdisp), $channel_connections); ?>)</span></div>
                                                <div class="popUp" data-display="4">
                                                    <div class="main">
                                                        <div class="ttl capitalfirst"><?php echo $channel_location; ?></div>
                                                        <div><?php print _("created on"); ?> <?php echo $channel_created_date; ?></div>
                                                        <div>
                                                            <table class="poptableclass">
                                                                <tr>
                                                                    <td class="yellowsmall poptableclassTD33P"><?php echo $channel_image_count; ?></td>
                                                                    <td class="yellowsmall poptableclassTD33P"><?php echo $channel_video_count; ?></td>
                                                                    <td class="yellowsmall poptableclassTD33P"><?php echo $channelalbumInfoCount; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="poptableclassTD33P"><?php echo sprintf(ngettext("photo","photos",$channel_image_count_init)); ?></td>
                                                                    <td class="poptableclassTD33P"><?php echo sprintf(ngettext("video","videos",$channel_video_count_init)); ?></td>
                                                                    <td class="poptableclassTD33P"><?php echo sprintf(ngettext("album","albums",$channelalbumInfoCount_init)); ?></td>
                                                                </tr>
                                                            </table>
                                                        </div>
            <?php
            if (userGetID() !=
                    $channel_owner_id) {
                echo ReturnConnectButton(array(
                    'channel_id' => $channel_id,
                    'class' => 'channelPopopConnect'));
            }
            ?>
                                                        <div class="catImage">
            <?php if (file_exists('images/channel/category/' . $catImage)) { ?>
                                                                <a href="<?php GetLink('channels-category/' . $catlink); ?>">
                                                                    <img src="<?php GetLink('images/channel/category/' . $catImage); ?>" alt="<?php echo $catName; ?>" title="<?php echo $catName; ?>" width="28" height="28" class="imgborder0" />
                                                                </a>
            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="minus"></div>
                                                </div>

                                            </li>
        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
        <?php
    }
    $j++;
}
?>
            </div>
            <div class="buttonmorecontainer" style="float:none; width:584px;">
<?php
$pagcount =
        floor($categoriesAllnum /
        $limitcategory);
if (($categoriesAllnum %
        $limitcategory) !=
        0) {
    $pagcount++;
}
if ($pagcount >
        ($currentpage +
        1)) {
    ?>
                    <a id="load_more_next" class="load_more_channels load_more_previous_new" href="<?php GetLink('channels/page/' . ($currentpage +
            1)); ?>"><?php echo _('Load More...'); ?></a>
    <?php
}
?>
<?php if (UriArgIsset('page') &&
        ($currentpage !=
        0)): ?>
                    <a id="load_more_previous" class="load_more_previous_new" href="<?php GetLink('channels/page/' . ($currentpage -
            1)); ?>"><?php echo _('Load Previous...'); ?></a>
<?php endif; ?>
                <script>
                    $(document).ready(function (e) {
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
        <div id="ChannelRight" class="paddingtop57">
<?php include("parts/recently_added_channels.php"); ?>
<?php include("parts/channels_best_of.php"); ?>
        </div>
    </div>
</div>

<!-- Slider script  -->
 <script type="text/javascript">
$(document).ready(function() {
    $(window).bind('resize',function(){
        var w=$(window).width();
        var slidesPerView = 4 ;
		if(w>300 &&  w<480){slidesPerView =2 ;}
		else if(w>481 &&  w<767){slidesPerView =3 ;}
	    else if(w>768 &&  w<960){slidesPerView =4 ;}
		var swiper = new Swiper('.swiper-container', {
		pagination: '.swiper-pagination',
		slidesPerView:slidesPerView,
		paginationClickable: true,
		spaceBetween: 0,
		freeMode: true
        });
});
        
      $(window).load(function(){
         $(window).trigger('resize');
      });
  });
</script>

<!-- end slider script  -->
<?php include("BottomIndex.php"); ?>