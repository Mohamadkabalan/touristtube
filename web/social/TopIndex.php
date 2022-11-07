<!DOCTYPE HTML>
<html lang="<?php echo $slang; ?>">
    <head itemscope itemtype="http://schema.org/WebSite">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <?php list ($page_title, $page_desc, $page_meta) = seoTextGet(); ?>
        <title itemprop='name'><?php echo $page_title ?></title>
        <meta name="description" content="<?php echo $page_desc ?>"/>
        <?php
        if (in_array(tt_global_get('page'), array('xxx-things2do.php', 'xxx-thotel.php', 'xxx-thotels.php', 'three-sixty.php', 'xxx-trestaurant.php', 'xxx-trestaurants.php', 'xxx-map.php', 'xxx-discover.php', 'xxx-planner.php', 'xxx-hotel-search.php', 'xxx-restaurant-search.php'))) {
         $loggedUser = userGetID();
         if ($loggedUser != 70 && $loggedUser != 25)
          header('Location:' . ReturnLink('/'));
        }
        ?>
        <meta property="og:title" content="<?php echo $page_title; ?>" />
        <meta property="og:url" content="<?php echo UriCurrentPageURL(); ?>" />
        <?php if (in_array(tt_global_get('page'), array('photo.php', 'photo1.php', 'video.php', 'photo-album.php', 'album.php', 'video-album.php', 'live-cam.php'))) { ?>
         <meta property="og:image" content="<?php echo $fb_img; ?>" />
        <?php } ?>
        <link rel="shortcut icon" href="<?php GetLink('media/images/favicon.ico') ?>" />
        <?php
//        $ur_array = UriCurrentPageURLForLanguage();
//        $langarray = array('en' => 'www', 'fr' => 'fr', 'hi' => 'in');
//        foreach ($langarray as $lang_key => $lang_val) {
//            if ($lang_key != 'en')
//                    $langUrl      = $ur_array[0].$ur_array[1].'/'.$lang_val.'/'.$ur_array[2];
//            else $langUrl      = $ur_array[0].$ur_array[1].'/'.$ur_array[2];
//            echo '<link rel="alternate" hreflang="'.$lang_key.'" href="'.$langUrl.'" itemprop="url"/>';
//        }
        ?>        
        <!--<link rel="publisher" href="https://www.google.com/+Touristtubepage" />-->

        <link rel="stylesheet" type="text/css" href="<?php GetLink("css/css.css") . "?v=" . CSS_CSS_V; ?>" />        
        <link rel="stylesheet" type="text/css" href="<?php GetLink("css/jquery-ui-1.10.0.custom.css"); ?>" />

        <?php if (!in_array(tt_global_get('page'), array('index.php'))) { ?>
         <link rel="stylesheet" type="text/css" href="<?php GetLink("css/jquery.jscrollpane.css"); ?>" />
        <?php } ?>
         <link rel="stylesheet" type="text/css" href="<?php GetLink("css/jquery.fancybox-new.css") ?>"/>
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
        if (($includesIE8 = tt_global_get('includesIE8'))) {
         foreach ($includesIE8 as $include) {
          if (strstr($include, '.css') != null) {
           printf('<!--[if lte IE 8]><link href="%s" rel="stylesheet" type="text/css"/><![endif]-->', ReturnLink($include));
          }
          echo "\r\n";
         }
        }
        $loggedUser = userGetID();

        echo checkTimeZoneCookie();
        ?>

        <script type="text/javascript" src="/assets/vendor/jquery/dist/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/assets/vendor/jquery/plugins/jquery-ui/js/jquery-ui-1.10.0.custom.js"></script>
        <script type="text/javascript" src="/bundles/bazingajstranslation/js/translator.min.js"></script>
        <script type="text/javascript" src="/translations/client?locales=<?php print LanguageGet(); ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery-migrate-1.1.1.js") ?>"></script>

        <script type="text/javascript" src="<?php GetLink("assets/vendor/jquery/plugins/il8n/js/jquery.i18n.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("assets/common/js/utils.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/pofile/dist/pofile.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.cookie.js") ?>"></script>        
        <?php if (!in_array(tt_global_get('page'), array('photo.php', 'video.php', 'photo-album.php', 'album.php', 'video-album.php', 'index.php'))) { ?>
         <script type="text/javascript" src="<?php GetLink("js/requestinvitation.js") ?>"></script>
         <script type="text/javascript" src="<?php GetLink("js/jquery.mousestop.js") ?>"></script>
        <?php } ?>
        <script type="text/javascript" src="<?php GetLink("js/init.js") ?>"></script>
        <?php if (!in_array(tt_global_get('page'), array('photo.php', 'video.php', 'photo-album.php', 'album.php', 'video-album.php'))) { ?>
         <script type="text/javascript" src="<?php GetLink("js/hammer4.js") ?>"></script> 
        <?php } ?>
        <script type="text/javascript" src="<?php GetLink("js/jquery.mousewheel_latest.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.jscrollpane.js") ?>"></script>
        <?php if (!in_array(tt_global_get('page'), array('index.php'))) { ?>
         <?php if (in_array(tt_global_get('page'), array('video.php', 'video-album.php'))) { ?>
          <script type="text/javascript" src="<?php GetLink("js/jquery.tools.js") ?>"></script>
          <script type="text/javascript" src="<?php GetLink("js/AC_RunActiveContent.js") ?>"></script>
         <?php } ?>
         <?php if (!in_array(tt_global_get('page'), array('photo.php', 'video.php', 'photo-album.php', 'album.php', 'video-album.php'))) { ?>
          <script type="text/javascript" src="<?php GetLink("js/jquery.hammer.js") ?>"></script>
          <script type="text/javascript" src="<?php GetLink("js/jquery.noselect.js") ?>"></script>
          <script type="text/javascript" src="<?php GetLink("js/jquery.sizes.js") ?>"></script>
          <script type="text/javascript" src="<?php GetLink("js/scroll-fix.js") ?>"></script>
         <?php } ?>
        <?php } ?>
        <script type="text/javascript" src="<?php GetLink("assets/uploads/js/plupload.full.js") ?>"></script>
        <script type="text/javascript" src="<?php GetLink("js/jquery.preloader2.js") ?>"></script>
<!--        <script type="text/javascript" src="<?php //GetLink("js/jquery-ui-1.10.0.custom.js") ?>"></script>-->
        <?php if (!in_array(tt_global_get('page'), array('index.php'))) { ?>
         <?php if (!in_array(tt_global_get('page'), array('photo.php', 'video.php', 'photo-album.php', 'album.php', 'video-album.php'))) { ?>
          <script type="text/javascript" src="<?php GetLink("js/placeholders.jquery.js") ?>"></script>
         <?php } ?>
        <?php } ?>
        <script type="text/javascript">
         var userIsLogged = <?php echo userIsLogged() ? 1 : 0; ?>;
         var ServerMaxFileSize = <?php echo intval(ini_get('upload_max_filesize')); ?>;
         var USER_IS_LOGGED = <?php echo userIsLogged() ? 'true' : 'false'; ?>;
<?php if (in_array(tt_global_get('page'), array('photo.php', 'photo1.php', 'video.php', 'photo-album.php', 'album.php', 'video-album.php'))) { ?>
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
        <?php
//        if(userIsLogged() && _seoExecutingFile() != 'myprofile.php' ){
        if (userIsLogged()) {
         ?>
<!--         <link rel="stylesheet" type="text/css" href="<?php //GetLink('video_chat/css/css_002.css') . '?v=' .CSS_002_CSS_V; ?>" />
         <link rel="stylesheet" type="text/css" href="<?php //GetLink('video_chat/css/jquery_003.css'); ?>">
         <link rel="stylesheet" type="text/css" href="<?php //GetLink('video_chat/css/chat.css') . '?v=' .CHAT_CSS_V ?>">


                     <script src="//code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
          file sharing js starts here
         <script src= "<?php //echo $CONFIG['chat_server']; ?>siofu/client.js"></script>
          file sharing js ends here 

          Application Specific javascripts starts here 
         <script src= "<?php //echo $CONFIG['chat_server']; ?>socket.io/socket.io.js"></script>
          Application Specific javascripts ends here 
         <script type="text/javascript" src="<?php //GetLink('video_chat/js/jscroll-pane.js'); ?>"></script>
         <script type="text/javascript" src="<?php //GetLink('video_chat/js/ddslick.js'); ?>"></script>
         <script type="text/javascript" src="<?php //GetLink('video_chat/js/emoticon.js'); ?>"></script>
         <script type="text/javascript" src="<?php //GetLink('video_chat/js/chat-behavior.js'); ?>"></script>
          add for distinguish between firefox and chrome starts here
         <script type="text/javascript" src="<?php //GetLink('video_chat/js/adapter.js'); ?>"></script>-->
        <?php } ?>
        <?php
        $MAP_KEY = '';        
        $pagehttp = getUriPageURLHTTP();
        if($pagehttp =='https') $MAP_KEY = '&key='.MAP_KEY;
        if (!in_array(tt_global_get('page'), array('photo.php', 'video.php', 'photo-album.php', 'album.php', 'video-album.php', 'index.php'))) { ?>
         <?php
         if (!isset($section))
          $section = '';
         if ( in_array(tt_global_get('page'), array('upload.php', 'myprofile.php', 'profile.php', 'upload-list.php', 'upload-album.php', 'channel-upload.php', 'channel-upload-list.php', 'channel-upload-album.php'))):
          ?>
          <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v3<?php echo $MAP_KEY; ?>&amp;sensor=false"></script>

         <?php endif; ?>
        <?php }// end if for page media   ?>
        <?php if (!in_array(tt_global_get('page'), array('map.php', 'discover.php', 'paris_test4.php', 'us_test.php', 'planner.php', 'index.php'))): ?>
         <!-- comments files -->
         <script type="text/javascript" src="<?php GetLink("js/underscore-min.js") ?>"></script>
         <script type="text/javascript" src="<?php GetLink("js/jquery.elastic.js") ?>"></script>
         <?php if (!in_array(tt_global_get('page'), array('photo.php', 'video.php', 'photo-album.php', 'album.php', 'video-album.php'))) { ?>
          <script type="text/javascript" src="<?php GetLink("js/jquery.timeentry.js") ?>"></script>
         <?php } ?>
         <script type="text/javascript" src="<?php GetLink("js/jquery.mentionsInput.js") ?>"></script>
         <script type="text/javascript" src="<?php GetLink("js/jquery.raty.js") ?>"></script>
         <!-- end comments files -->
        <?php endif ?>
        <script  type="text/javascript">
         $(document).ready(function () {
             $.ajax({
                 url: ReturnLink('i18n/<?php print $js_local; ?>/LC_MESSAGES/_<?php print LanguageGet(); ?>.json'),
                 async: false,
                 type: 'get',
                 success: function (i18n_dict) {
                     $.i18n.load(i18n_dict);
                 }
             });
             //$(".ttl25")._t('The most elegant way to tell your journey');
             //alert( $.i18n._('The most elegant way to tell your journey') );
             //console.log( $.i18n._( 'HTML Content', $(document).width() , $(document).height() ) );
             //$('div#dynamic')._t('Dynamic Content', $(document).width(), $(document).height());
             //$('div#orderedDynamic')._t('Ordered Dynamic Content', $(document).width(), $(document).height());
             //$('div#html')._t();


             //new implementation for js varraiables
             //console.log( t('Password must be minimum {len} characters long').replace('{len}', 5) ) ;
             //OR
             //console.log( sprintf( t('Password must be minimum %s characters long') , [5] ) )

             //console.log(sprintf( t('are you sure you want to remove permanently this %s ? %s this action cannot be undone.') , ['photo','<br/>'] ) )

         });
        </script> 
        <?php        
        if (($includes = tt_global_get('includes'))) {
         foreach ($includes as $include) {
            if (strstr($include, '.js') != null) {
                printf('<script type="text/javascript" src="%s"></script>', ReturnLink($include));
            }
            echo "\r\n";
         }
        }
        if (($includesIE8 = tt_global_get('includesIE8'))) {
         foreach ($includesIE8 as $include) {
            if (strstr($include, '.js') != null) {
                printf('<!--[if lte IE 8]><script type="text/javascript" src="%s"></script><![endif]-->', ReturnLink($include));
            }
          echo "\r\n";
         }
        }
        $category_id = UriArgIsset('cat_id') ? UriGetArg('cat_id') : tt_global_get('category_id');
        $selectedCategory = UriArgIsset('cat_id') ? get_cat_name(UriGetArg('cat_id')) : tt_global_get('category_name');
        ?>
        <script type="text/javascript" src="<?php GetLink("js/jquery.fancybox.js") ?>"></script>
        <script>
         function closeFancy() {
             $.fancybox.close();
         }
        </script>
        <script type="text/javascript" src="<?php GetLink("js/jcarousellite_1.0.1c5.js") ?>"></script>
</head>
<body>
    <a rel="nofollow" href="https://www.facebook.com/pages/Tourist-Tube/1635194046710440?fref=ts" title="facebook" class="displaynone"></a>
    <div id="TopMenu">
        <div id="InsideTopMenu">
            <a href="<?php GetLink('') ?>" id="Logo"></a>
            <div id="TopMenuContainer">
                <div id="RightTopMenuInside">
                    <?php if ( userIsLogged() ): ?>
                    <a id="uploadLink" class="requestinvite" href="<?php GetLink('upload') ?>">
                        <?php echo _('upload') ?></a>
                     &nbsp; | &nbsp;
                    <?php endif ?>
                    <?php if (!userIsLogged()): ?>
                     <a class="TopRegisterLink" href="<?php GetLink('register') ?>"><?php echo _('register') ?></a>
                    <?php else: ?>
                     <a id="WelcomeStringComb" href="<?php GetLink('myprofile') ?>"><span id="WelcomeString"><?php echo _('Welcome') ?></span> - <span id="WelcomeUserName"><?php echo userGetName(); ?></span></a>
                    <?php endif ?>
                    <?php if (!userIsLogged()): ?>
                     <a href="<?php GetLink('login') ?>" title="<?php echo _('SIGN IN'); ?>" id="SignInBtn"><?php echo _('SIGN IN'); ?><div id="SignInBtnbk"></div></a>
                    <?php else: ?>
                     <div id="SignOutBtn"></div>
                     <div id="TopProfileDiv">
                         <?php
                         $options2 = array('owner_id' => userGetID(), 'published' => 1, 'page' => 0, 'limit' => null);
                         $channelListAll = channelSearch($options2);
                         if (!$channelListAll)
                          $channelListAll = array();
                         ?>
                         <?php if (count($channelListAll) > 0) { ?>
                          <div style="display:none;" id="OtherChannelsPop" class="OtherChannelsPopIndex">
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
                         <?php } ?>
                         <ul>
                             <?php if (!userIsChannel()): ?>
                              <li><a href="<?php GetLink('account/info') ?>"><div class="TopProfileDivIcon TopProfileDivAccountIcon"></div><div class="TopProfileDivText"><?php echo _('Account Info'); ?></div></a></li>
                             <?php endif ?>
                             <?php if (count($channelListAll) > 0) { ?>
                              <li><a href="javascript:;" class="showChannels"><div class="TopProfileDivIcon TopProfileDivChannelsIcon"></div><div class="TopProfileDivText"><?php echo _('Channels'); ?></div></a></li>
                             <?php } ?>
                             <li><a href="<?php GetLink('myprofile/photos') ?>"><div class="TopProfileDivIcon TopProfileDivPhotosIcon"></div><div class="TopProfileDivText"><?php echo _('Photos'); ?></div></a></li>
                             <li><a href="<?php GetLink('myprofile/videos') ?>"><div class="TopProfileDivIcon TopProfileDivVideosIcon"></div><div class="TopProfileDivText"><?php echo _('Videos'); ?></div></a></li>
                             <li><a href="<?php GetLink('myprofile/albums') ?>"><div class="TopProfileDivIcon TopProfileDivAlbumsIcon"></div><div class="TopProfileDivText"><?php echo _('Albums'); ?></div></a></li>
                             <li><a href="<?php GetLink('logout'); ?>"><div class="TopProfileDivIcon"></div><div class="TopProfileDivText"><?php echo _('sign out'); ?></div></a></li>
                         </ul>
                     </div>
                    <?php endif ?>
                </div>
                <div id="TopSearchArea">
                    <?php
                    $t_get = $request->query->get('t', 'a');
//                        $t = ( isset($_GET['t']) && $_GET['t'] <> '' ) ? $_GET['t'] : 'a';
                    $t = $t_get;
                    $id_type = UriGetArg('t');
//                    $id_type = (!isset($id_type) ) ? 'a' : $id_type;
                    if ($id_type != ''){  
                        $t = $id_type;
                    }else{
                        $id_type = (!isset($id_type) ) ? 'a' : $id_type;
                    }
                    $t = ($t != 'a' && $t != 'i' && $t != 'v' && $t != 'u') ? 'a' : $t;
                    
                    ?>
                    <form id="SearchForm" enctype="application/x-www-form-urlencoded" name="SearchForm" action="<?php $t == 'u' ? GetLink('tubers') : ''?>" method="post">
                        <div id="SearchCategoryList">
                            <?php
                            //media search page stores the type in 'SearchCategory'
                            $order_get = $request->query->get('order', '');
//                                $order = ( isset($_GET['order']) && $_GET['order'] <> '' ) ? $_GET['order'] : '';
                            $order = $order_get;
                            $id_orderby = UriGetArg('orderby');
                            $id_orderby = (!isset($id_orderby) ) ? '' : $id_orderby;
                            if ($id_orderby != '')
                             $order = $id_orderby;
                            ?>
                            <ul>
                                <li data-type="a" id="SearchCategory1"><?php echo _('All Media'); ?></li>
                                <li data-type="i" id="SearchCategory2"><?php echo _('Photos'); ?></li>
                                <li data-type="v" id="SearchCategory3"><?php echo _('Videos'); ?></li>
                                <li data-type="i" id="SearchCategory7" style="display:none;"></li>
                                <li data-type="v" id="SearchCategory8" style="display:none;"></li>
                                <li data-type="u" id="SearchCategory4"><?php echo _('Friends'); ?></li>
                            </ul>
                        </div>
                        <div class="SearchCategoryBtn">
                            <?php
                            if ($t == 'a')
                             echo _('All Media');
                            elseif ($t == 'i')
                             echo _('Photos');
                            elseif ($t == 'v')
                             echo _('Videos');
                            //else if($search_cat == 'h') echo _('Hotels');
                            //else if($search_cat == 'r') echo _('Restaurants');
                            elseif ($t == 'u')
                             echo _('Friends');
                            ?>
                            <span class="SearchCategoryIcon"></span>
                        </div>
                        <div class="SearchCategorySep"></div>
                        <div class="SearchField">
                            <?php
                            $search = urldecode(UriGetArg('ss'));
                            if (tt_global_get('page') == 'tubers.php')
                             $search = UriGetArg('search-string');
                            else if (tt_global_get('page') == 'search-location.php')
                             $search = UriGetArg('search-string');
                            if ($search == null) {
                             if (tt_global_get('page') == 'search.php') {
                              //if we are on the search page and its null we are searching for "nothing"
                             } else {
                              $search = _('Search');
                             }
                            }

                            if ($selectedCategory == '') {
                             $selectedCategory = _('all categories');
                            }
                            if (!isset($q))
                             $q = '';
                            if (!isset($c))
                             $c = '';
//                                $urldecode = htmlspecialchars($_GET['qr']);
                            $category_names = categoryGetInfo($c);
                            $urldecode = htmlspecialchars($request->query->get('qr', ''));
                            $id_qr = UriGetArg('qr');
                            $id_qr = (!isset($id_qr) ) ? '' : $id_qr;
                            if ($id_qr != '')
                            ?>
                            <input type="text" id="SearchField" onkeypress="return checkSubmitSearchHome(event)" rel="<?php print $category_id; ?>" name="qr" placeholder="<?php print _('Search'); ?>" value="<?php echo urldecode($urldecode); ?>" data-id="<?php echo $c?>" data-name ="<?php echo str_replace(" ", "+",$category_names['title']) ?>"/>
                            <input type="hidden" name="t" id="t" value="<?php print $t <> '' ? $t : 'a'; ?>" />
                            <input type="hidden" name="orderby" id="orderby" value="<?= htmlspecialchars($order); ?>" />
                            <input type="hidden" name="c" id="c" value="<?= $c; ?>" />
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
            <div id="ArrowMiddleTop">
<!--                    <img src="<?php //GetLink('media/images/top_menu_expand.jpg') ?>" width="15" height="22" style="width: 15px; height: 22px" alt="switch">-->
            </div>
            <?php
            include("SlotMachine.php");
            ?>
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
<!--                 <li class=""><a class="tabsofive requestinvite" href="<?php // GetLink('account/invite'); ?>" title="<?php //print _("Send Invitation"); ?>">
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
                    <div class="TTAlert_buts1 TTAlert_buts"><?php print _('cancel'); ?></div>
                    <div class="TTAlert_seperator"></div>
                    <div class="TTAlert_buts2 TTAlert_buts"><?php print _('register') ?></div>
                </div>
                <div id="TTAlert_close" class="sprite"></div>
            </div>
        </div>
        <div class="TTAlert_back_fix"></div>
    </div>