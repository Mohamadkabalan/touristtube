<!DOCTYPE HTML>
<html lang="<?php echo $slang; ?>">
    <?php
    $pagecurname = _seoExecutingFile();
    checkChannelSubdomain();
    ?>
    <head itemscope itemtype="http://schema.org/WebSite">
        <?php list ($page_title, $page_desc, $page_meta) = seoTextGet(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />        
        <title itemprop='name'><?php echo $page_title ?></title>
        <meta name="description" content="<?php echo $page_desc ?>"/>
        <meta property="og:title" content="<?php echo $page_title; ?>" />
        <meta property="og:url" content="<?php echo UriCurrentPageURL(); ?>" />
        <?php if (in_array(tt_global_get('page'), array('photo.php', 'photo1.php', 'video.php', 'photo-album.php', 'album.php', 'video-album.php', 'live-cam.php'))) { ?>
         <meta property="og:image" content="<?php echo $fb_img; ?>" />        
        <?php } ?>
        <link rel="shortcut icon" href="<?php GetLink('media/images/favicon.ico') ?>" />
        <?php
//        $ur_array = UriCurrentPageURLForLanguage();
//        $langarray = array('www', 'fr', 'in');
//        $langarray = array('en' => 'www', 'fr' => 'fr', 'hi' => 'in');
//        foreach ($langarray as $lang_key => $lang_val) {
//            if ($lang_key != 'en')
//                    $langUrl      = $ur_array[0].$ur_array[1].'/'.$lang_val.'/'.$ur_array[2];
//            else $langUrl      = $ur_array[0].$ur_array[1].'/'.$ur_array[2];
//            echo '<link rel="alternate" hreflang="'.$lang_key.'" href="'.$langUrl.'" itemprop="url"/>';
//        }
        ?>
        <!--<link rel="publisher" href="https://www.google.com/+Touristtubepage" />-->        
        <?php
        if (in_array(tt_global_get('page'), array('things2do.php', 'thotel.php', 'thotels.php', 'three-sixty.php', 'trestaurant.php', 'trestaurants.php', 'map.php', 'discover.php', 'planner.php', 'hotel-search.php', 'restaurant-search.php'))) {
         $loggedUser = userGetID();
         if ($loggedUser != 70 && $loggedUser != 25)
          header('Location:' . ReturnLink('/'));
        }
        ?>

        <link rel="stylesheet" type="text/css" href="<?php GetLink("css/jquery.jscrollpane.css"); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php GetLink("css/css.css") . "?v=" . CSS_CSS_V ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php GetLink("css/jquery-ui-1.10.0.custom.css") ?>"/>

        <link rel="stylesheet" type="text/css" href="<?php GetLink("css/jquery.fancybox-new.css?v=2.0.5") ?>"/>
        
        <!-- comments files -->
        <?php
        if (isset($myEventsCalendarPage))
         $myEventsCalendarPage = intval($myEventsCalendarPage);
        else
         $myEventsCalendarPage = '';
        if ($myEventsCalendarPage != 1):
         ?>
         <link rel="stylesheet" type="text/css" href="<?php GetLink("css/jscal2.css") ?>"/>
        <?php else: ?>
         <link rel="stylesheet" type="text/css" href="<?php GetLink("css/jscal2_events_calendar.css") ?>"/>
        <?php endif ?>
        <?php
        if (($includes = tt_global_get('includes'))) {
         foreach ($includes as $key => $include) {
            if (strstr($include, '.css') != null && !strstr($key, 'media')) {
                printf('<link href="%s" rel="stylesheet" type="text/css"/>', ReturnLink($include));
            } elseif (strstr($include, '.css') && strstr($key, 'media') && RESPONSIVE) {
                printf('<link href="%s" rel="stylesheet" type="text/css" media="screen" />', ReturnLink($include));
            }
          echo "\r\n";
         }
        }
        echo checkTimeZoneCookie();
        ?>
        <script type="text/javascript" src="/assets/vendor/jquery/dist/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery-migrate-1.1.1.js") ?>"></script>
        <script type="text/javascript" src="/bundles/bazingajstranslation/js/translator.min.js"></script>
        <script type="text/javascript" src="/translations/client?locales=<?php print LanguageGet(); ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.cookie.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/json2.js") ?>"></script>
        <script src="<?php GetLink("js/requestinvitation.js"); ?>" type="text/javascript"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.mousestop.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("assets/common/js/utils.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/init.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.mousewheel_latest.js") ?>"></script>
        <script src="<?php GetLink("js/jquery.jscrollpane.js"); ?>" type="text/javascript"></script>
        <script type="text/javascript">
         var userIsLogged = <?php echo userIsLogged() ? 1 : 0; ?>;
         var ServerMaxFileSize = <?php echo intval(ini_get('upload_max_filesize')); ?>;
         var USER_IS_LOGGED = <?php echo userIsLogged() ? 'true' : 'false'; ?>;
<?php if (in_array(tt_global_get('page'), array('photo.php', 'video.php', 'video-old.php', 'photo-album.php', 'album.php', 'video-album.php'))) { ?>
          var ORIGINAL_MEDIA_ID = <?php echo $current_id ?>;
          var ORIGINAL_ENTITY_TYPE = <?php echo $entity_type ?>;
          var is_owner = '<?php echo $is_owner; ?>';
          var user_Is_channel = '<?php echo $userIschannel; ?>';
          var user_is_logged = '<?php echo $user_is_logged; ?>';
          var pagename = "media_page";
          var COMMENT_TYPE = "m";
          ThumbPath = '';
          channelGlobalID('<?php echo $VideoInfo['channelid']; ?>');
<?php } ?>
         flagme = 1;
        </script>
        <script type="text/javascript" src="<?php GetLink("js/AC_RunActiveContent.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.tools.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/hammer4.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.hammer.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.noselect.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.sizes.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery-ui-1.10.0.custom.js") ?>"></script>

        <?php 
        $MAP_KEY = '';        
        $pagehttp = getUriPageURLHTTP();
        if($pagehttp =='https') $MAP_KEY = '&key='.MAP_KEY;
        if (in_array(tt_global_get('page'), array('map.php', 'live.php', 'channel-upload.php', 'channel-notifications.php', 'channel-upload-list.php', 'channel-upload-album.php'))):?>
         <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true<?php echo $MAP_KEY; ?>"></script>
        <?php endif ?>
        <script type="text/javascript" src="<?php GetLink("js/jquery.preloader2.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.fullscreen.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.raty.js") ?>"></script>        
        <script type="text/javascript" src="<?php GetLink("js/underscore-min.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.elastic.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.timeentry.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.mentionsInput.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("assets/vendor/jquery/plugins/il8n/js/jquery.i18n.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/pofile/dist/pofile.js") ?>"></script>
        <script  type="text/javascript">
         $(document).ready(function () {
             $.ajax({
                 url: ReturnLink('/i18n/<?php print $js_local; ?>/LC_MESSAGES/_<?php print LanguageGet(); ?>.json'),
                 async: false,
                 type: 'get',
                 success: function (i18n_dict) {
                     $.i18n.load(i18n_dict);
                 }
             });
         });
        </script>
        <script type="text/javascript" src="<?php GetLink("js/jscal2.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jscal2.en.js") ?>"></script>        
        <script type="text/javascript" src="<?php GetLink("js/jquery.fancybox.js") ?>"></script>
        <?php
        if (($includes = tt_global_get('includes'))) {
         foreach ($includes as $include) {
            if (strstr($include, '.js') != null) {
                printf('<script type="text/javascript" src="%s"></script>', ReturnLink($include));
            }
            echo "\r\n";
         }
        }
        ?>
        <script type="text/javascript" src="<?php GetLink("assets/uploads/js/plupload.full.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jcarousellite_1.0.1c5.js") ?>"></script>
        <script type="text/javascript">
         $(document).ready(function (e) {

             $('body').prepend('<style type="text/css"> .fullFancyDim { height: ' + screen.height + 'px !important; } </style>');
             $('#tsearch').autocomplete({
                 delay: 5,
                 search: function (event, ui) {
                     var $searchString = $('#tsearch');
                     var searchString = $searchString.val();

                     $('#tsearch').autocomplete("option", "source", ReturnLink('/ajax/channel_suggest.php'));
                 },
                 focus: function (event, ui) {
                     //$(this).val(ui.item.right);
                     return false;
                 },
                 select: function (event, ui) {
                     event.preventDefault();
                     $('#tsearch').val(ui.item.channel_name);
                     submitSearchForm();
                 }
             }).keydown(function (event) {
                 if (event.keyCode == 13) {
                     event.preventDefault();
                     submitSearchForm();
                 }

             }).data("ui-autocomplete")._renderItem = function (ul, item) {
                 return $("<li></li>")
                         .data("item.autocomplete", item)
                         .append("<a title='" + item.right.replace(/(<([^>]+)>)/ig, "") + "'>" + item.label + "<span class='searchttl'>" + item.name + "</span></a>")
                         .appendTo(ul);
             }
         });
         function closeFancy() {
             $.fancybox.close();
         }
        </script>
        <?php
        $current_channel = userCurrentChannelGet();
        $channel_arrayDF = channelGetInfo($current_channel['id']);
        if ($channel_arrayDF['published'] == 0) {
         userCurrentChannelReset();
         $current_channel = userCurrentChannelGet();
        }
        $welcome_name = userGetName();
        $loggedUser = userGetID();
        $welcome_link = userProfileLink(getUserInfo($loggedUser));
        $switch_id = 0;
        if (!isset($myEventsPage))
         $myEventsPage = 0;
        $myEventsPage = intval($myEventsPage);
        if (isset($is_owner) && $is_owner == 1) {
         $val_db = htmlEntityDecode($channelInfo['channel_name']);
         $welcome_nameinit = $val_db;
         $welcome_name = substr($val_db, 0, 17);
         $welcome_link = ReturnLink('channel/' . $channelInfo['channel_url']);
         if (strlen($val_db) > 18) {
          $welcome_name = $welcome_name . ' ...';
         }
        } else if ($current_channel && sizeof($current_channel) != 0) {
         $switch_id = $current_channel['id'];
         $val_db = htmlEntityDecode($current_channel['channel_name']);
         $welcome_nameinit = $val_db;
         $welcome_name = substr($val_db, 0, 17);
         $welcome_link = ReturnLink('channel/' . $current_channel['channel_url']);
         if (strlen($val_db) > 18) {
          $welcome_name = $welcome_name . ' ...';
         }
         $userIschannel = 1;
        }
        ?>

        <link rel="stylesheet" type="text/css" href="<?php GetLink('css_media_query/channel_media.css') . '?v=' . MQ_CHANNEL_MEDIA_CSS_V; ?>" />        
</head>
<body>
    <a rel="nofollow" href="https://www.facebook.com/pages/Tourist-Tube/1635194046710440?fref=ts" title="facebook" class="displaynone"></a>
    <div id="TopMenu">
        <div id="InsideTopMenu">
            <a href="<?php GetLink('') ?>" id="Logo"></a>
            <div id="TopMenuContainer">
                <div id="RightTopMenuInside">
                    <ul class="chregMN">
                        <?php
                        $channelupID = false;
                        if (userIsLogged()) {

                         $channelInfobyURL = channelFromURL(UriGetArg(0));
                         $channelInfobyID = channelFromID(UriGetArg(0));
                         $channelTopArray = array();
                         if ($channelInfobyURL) {
                          $channelTopArray = $channelInfobyURL;
                         } else if ($channelInfobyID) {
                          $channelTopArray = $channelInfobyID;
                         }
                         $channelupID = (sizeof($channelTopArray) > 0) ? $channelTopArray['id'] : false;
                         if (!$channelupID && $current_channel && sizeof($current_channel) != 0) {
                          $channelupID = $current_channel['id'];
                          $channelTopArray = $current_channel;
                         }
                         ?>
                         <li><a href="<?php GetLink('/channel-add') ?>"><?php echo _('create my channel') ?></a></li>
                         <li>|</li>
                         <?php
                         if ($channelupID) {
                          if (( isset($channelTopArray['owner_id']) && userGetID() == $channelTopArray['owner_id'] && sizeof(UriGetArg(0)) != 0) || ($myEventsPage != 0 && $switch_id != 0)) {
                           echo '<li class="top_menu_addcontent"><a id="uploadLink" href="' . ReturnLink('channel-upload/' . $channelupID) . '">' . _('add content') . '</a>';
                           ?>
                           <div id="top_menu_mouseover" class="top_menu_popunder">
                               <div class="top_menu_popunder_body">
                                   <?php
                                   $optionsnewlength = array(
                                    'user_id' => userGetID(),
                                    'channel_id' => $channelupID,
                                    'n_results' => true
                                   );
                                   $tempcount = videoTemporaryGetAll($optionsnewlength);
                                   if ($tempcount == 0) {
                                    $srch_options2 = array(
                                     'user_id' => userGetID(),
                                     'channelid' => $channelupID,
                                     'n_results' => true
                                    );
                                    $tempcount = videoTemporaryGetAlbums($srch_options2);
                                   }
                                   if ($tempcount > 0) {
                                    ?>
                                    <a href="<?php GetLink('channel-upload/' . $channelupID); ?>"><?php echo _('Pending media') ?></a>
                                    <div class="line"></div>
                                   <?php } ?>
                                   <a href="<?php GetLink('channel-upload/' . $channelupID); ?>"><?php echo _('Photos') ?></a>
                                   <div class="line"></div>
                                   <a href="<?php GetLink('channel-upload/' . $channelupID); ?>"><?php echo _('Videos') ?></a>
                                   <div class="line"></div>
                                   <a href="<?php GetLink('channel-upload/' . $channelupID); ?>"><?php echo _('albums') ?></a>
                                   <div class="line"></div>
                                   <a href="<?php GetLink('channel-add-brochure/' . $channelupID); ?>"><?php echo _('brochures') ?></a>
                                   <div class="line"></div>
                                   <a href="<?php GetLink('channel-add-event/' . $channelupID); ?>"><?php echo _('events') ?></a>
                                   <div class="line"></div>
                                   <a href="<?php GetLink('channel-add-news/' . $channelupID); ?>"><?php echo _('news') ?></a>
                               </div>
                           </div>
                           <?php
                           echo '</li>';
                           echo '<li>|</li>';
                          }
                         }
                         ?>
                         <li><a id="WelcomeStringComb" href="<?php echo $welcome_link; ?>">
                                 <span id="WelcomeString"><?php echo _('Welcome') ?></span> - <span id="WelcomeUserName"><?php echo $welcome_name; ?></span></a></li>
                        <?php } else { ?>
                         <li><a href="<?php GetLink('register') ?>" class="TopRegisterLink marginRight0"><?php echo _('register') ?></a></li>
                         <li>|</li>
                         <li><a href="<?php GetLink('/channel-add') ?>" style="margin-right:10px;"><?php echo _('create my channel') ?></a></li>
                        <?php } ?>
                    </ul>
                    <?php if (!userIsLogged()): ?>
                     <a href="<?php GetLink('login') ?>" title="<?php echo _('SIGN IN'); ?>" id="SignInBtn"><?php echo _('SIGN IN'); ?><div id="SignInBtnbk"></div></a>
                    <?php else: ?>
                     <div id="SignOutBtn"></div>
                     <div id="TopProfileDiv">
                         <?php
                         $options = array('owner_id' => userGetID(), 'published' => 1, 'page' => 0, 'limit' => 4);
                         $channelList = channelSearch($options);

                         $options2 = array('owner_id' => userGetID(), 'published' => 1, 'page' => 0, 'limit' => null);
                         $channelListAll = channelSearch($options2);
                         ?>
                         <div id="OtherChannelsPop">
                             <div class="main">
                                 <div class="top">
                                     MY CHANNELS <span class="yellowbold11">(<?php echo tt_number_format(count($channelListAll)); ?>)</span>
                                     <div class="next<?php if (count($channelListAll) <= 12) echo ' disabled'; ?>"><?php print _("next"); ?></div>
                                     <div class="prev disabled"><?php print _("previous"); ?></div>
                                     <div class="close showChannels"></div>
                                 </div>
                                 <div class="containertop caouselother">
                                     <ul>
                                         <li>
                                             <?php
                                             $m = 0;
                                             foreach ($channelListAll as $channel) {

                                              $top_channel_id = $channel['id'];
                                              $top_channel_name_stan = htmlEntityDecode($channel['channel_name']);
                                              $top_channel_name = substr($top_channel_name_stan, 0, 22);
                                              if (strlen($top_channel_name_stan) > 22) {
                                               $top_channel_name = $top_channel_name . '...';
                                              }
                                              $top_channel_url = $channel['channel_url'];
                                              $top_channel_logo = '<img src="' . photoReturnchannelLogo($channel) . '" alt="' . $top_channel_name_stan . '" width="28" height="28">';
                                              //
                                              if (is_arabic($top_channel_name_stan)) {
                                               $classnew = " arabic";
                                              } else {
                                               $classnew = "";
                                              }
                                              if ($m % 12 == 0 && $m != 0)
                                               echo '</li><li>';
                                              ?>
                                              <div class="insideli" data-id="<?php echo $top_channel_id; ?>">
                                                  <?php if (isset($is_owner) && $is_owner == 0) { ?>
                                                   <div title="<?php echo _("switch to ") . $top_channel_name; ?>" class="insideli_switch<?php if ($switch_id == $top_channel_id) echo ' active'; ?>"></div>
                                                  <?php } ?>
                                                  <a href="<?php GetLink('channel/' . $top_channel_url); ?>" title="<?php echo $top_channel_name_stan; ?>"><div class="TopProfileDivIcon"><?php echo $top_channel_logo; ?></div><div class="TopProfileDivText<?php echo $classnew; ?>"><?php echo $top_channel_name; ?></div></a>
                                              </div>
                                              <?php
                                              $m++;
                                             }
                                             ?>
                                         </li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                         <ul class="smallChMN">
                             <?php
                             if (!userIsChannel()) {
                              $userInfo = getUserInfo(userGetID());
                              $profilePic = $userInfo['profile_Pic'];
                              $fullNameStan = htmlEntityDecode($userInfo['FullName']);
                              $fullName = returnUserDisplayName($userInfo, array('max_length' => 25));
                              $s_user_link = userProfileLink($userInfo);
                              ?>
                              <li data-id="profile">
                                  <?php if (isset($is_owner) && $is_owner == 0 && $myEventsPage == 0) { ?>
                                   <div title="<?php echo _("switch to ") . $fullName; ?>" class="insideli_switch<?php if ($switch_id == 0) echo ' active'; ?>" style="bottom:14px;"></div>
                                  <?php } ?>
                                  <a href="<?php GetLink('myprofile') ?>" title="<?php echo $fullNameStan; ?>"><div class="TopProfileDivIcon"><img src="<?php GetLink('media/tubers/' . $profilePic); ?>" width="28" height="28" alt="<?php echo $fullNameStan; ?>"/></div><div class="TopProfileDivText"><?php echo $fullName; ?></div></a></li>
                             <?php } ?>
                             <?php
                             foreach ($channelList as $channel) {

                              $top_channel_id = $channel['id'];
                              $top_channel_name_stan = htmlEntityDecode($channel['channel_name']);
                              $top_channel_name = substr($top_channel_name_stan, 0, 21);
                              if (strlen($top_channel_name_stan) > 21) {
                               $top_channel_name = $top_channel_name . ' ...';
                              }

                              $top_channel_url = $channel['channel_url'];
                              $top_channel_logo = '<img src="' . photoReturnchannelLogo($channel) . '" alt="' . $top_channel_name_stan . '" width="28" height="28">';

                              if (is_arabic($top_channel_name_stan)) {
                               $classnew = " arabic";
                              } else {
                               $classnew = "";
                              }
                              ?>
                              <li data-id="<?php echo $top_channel_id; ?>">
                                  <?php if (isset($is_owner) && $is_owner == 0) { ?>
                                   <div title="<?php echo _("switch to ") . $top_channel_name; ?>" class="insideli_switch<?php if ($switch_id == $top_channel_id) echo ' active'; ?>" style="bottom:14px;"></div>
                                  <?php } ?>
                                  <a href="<?php GetLink('channel/' . $top_channel_url); ?>" title="<?php echo $top_channel_name_stan; ?>"><div class="TopProfileDivIcon"><?php echo $top_channel_logo; ?></div><div class="TopProfileDivText<?php echo $classnew; ?>"><?php echo $top_channel_name; ?></div></a></li>
                             <?php } ?>
                             <?php if (isset($channelListAll) && sizeof($channelListAll) > 4) { ?>
                              <li><a href="javascript:;" class="showChannels"><div class="TopProfileDivIcon"></div><div class="TopProfileDivText"><?php echo _('view all'); ?></div></a></li>
                             <?php } ?>
                             <li><a href="<?php GetLink('logout'); ?>"><div class="TopProfileDivIcon"></div><div class="TopProfileDivText"><?php echo _('sign out'); ?></div></a></li>
                         </ul>
                     </div>
                    <?php endif ?>
                </div>
                <div id="TopSearchArea">
                    <form id="SearchForm" name="SearchForm" action="<?php GetLink('channel-search') ?>" method="get">

                        <div class="SearchField channel" style="width: 300px;">
                            <?php
                            $search = urldecode(UriGetArg(0));

                            if (channelGlobalSearchGet()) {
                             $search = channelGlobalSearchGet();
                            } else {
                             $search = _('Search Channels...');
                            }
                            $tsc = htmlspecialchars(urldecode(UriGetArg('t')));
                            ?>
                            <input placeholder="<?php print _("Search Channels..."); ?>" type="text" id="tsearch" name="tsearch" value="<?php echo $tsc; ?>"/>
                        </div>
                        <div class="SearchSubmit"></div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="MiddleTop" class="visited">
        <div class="profileimagesbuttonsOver">
            <div class="ProfileHeaderOverin"></div>
            <div class="icons-overtik"></div>
        </div>

        <div id="InsideMiddelTop">
            <?php include("SlotMachineChannel.php"); ?>
        </div>
    </div>
    <?php if (RESPONSIVE) { ?>       
     <!-- nav -->
     <div id="nav_bar"> 
         <script>
          function menushow()
          {
              $("#mobile_tabsmain").slideToggle();
          }

         </script>
         <div id="nav_bar_mobile"> 
             <a href="#" class="menu-toggle" onClick="menushow();"> 
                 <span class="text"><?php print _("Menu"); ?></span> 
                 <span class="i_bar_btn"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></span> 
             </a>
             <ul id="mobile_tabsmain" style="display:none">
                 <li>
                     <a class="requestinvite" href="<?php echo ReturnLink('things-to-do'); ?>" title="<?php print _("Things To Do"); ?>">
                         <span><?php print _("Things To Do"); ?></span>
                     </a>
                 </li>
                 <li class="largecell3"><a class="tabsofive requestinvite" href="<?php echo ReturnLink('channels', null , 0, 'channels'); ?>" title="<?php print _("Tourist Channels"); ?>">

                         <span><?php print _("Tourist Channels"); ?></span></a></li>


                 <li class=""><a class="tabsofive requestinvite" href="<?php GetLink('live'); ?>" title="<?php print _("Tourist Live"); ?>">

                         <span><?php print _("Tourist Live"); ?></span></a></li>

                 


                 <li class=""><a class="tabsofive requestinvite" href="<?php GetLink('review'); ?>" title="<?php print _("Review & Rate"); ?>">
                         <div></div>
                         <span><?php print _("Review & Rate"); ?></span></a></li>
                <?php if( userIsLogged() ){ ?>
<!--                    <li class=""><a class="tabsofive requestinvite" href="<?php //GetLink('account/invite'); ?>" title="<?php //print _("Send Invitation"); ?>">
                         <div></div>
                         <span><?php //print _("Send Invitation"); ?></span></a></li>-->
                <?php } ?>
             </ul>
         </div>
     </div>
     <!-- Nav -->
    <?php } ?>      
    <div id="TTAlertContainer">
        <div id="TTAlertContainerInside">
            <div id="TTAlertContainerInside_data">
                <div id="TTAlert_content">
                    <div class="TTAlert_text"></div>
                </div>
                <div id="TTAlert_shadow"></div>
                <div id="TTAlert_button_container">
                    <div class="TTAlert_buts1 TTAlert_buts"><?php echo _('cancel'); ?></div>
                    <div class="TTAlert_seperator"></div>
                    <div class="TTAlert_buts2 TTAlert_buts"><?php echo _('register'); ?></div>
                </div>
                <div id="TTAlert_close" class="sprite"></div>
            </div>
        </div>
        <div class="TTAlert_back_fix"></div>
    </div>