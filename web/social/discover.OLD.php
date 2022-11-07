<?php
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/bag.php" );
    
$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}
$user_id = userGetID();
$includes = array('css/discover_first.css','js/discover_first.js','css/jquery.selectbox.map.css', 'js/jquery.selectbox-0.6.1.js');
tt_global_set('includes', $includes);
include("TopIndex.php");

?>
<script type="text/javascript" src="//google-maps-utility-library-v3.googlecode.com/svn-history/r290/trunk/infobox/src/infobox_packed.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    var markersArrayData =[];
    var boxText = document.createElement("div");
    var myOptions = {
        content: boxText
        , disableAutoPan: false
        , maxWidth: 0
        , pixelOffset: new google.maps.Size(-140, 0)
        , zIndex: null
        , boxStyle: {
             opacity: 0.9
            , width: "120px"
            , height: "140px"
        }
        , closeBoxURL: ""
        , infoBoxClearance: new google.maps.Size(1, 1)
        , isHidden: false
        , pane: "floatPane"
        , enableEventPropagation: false
    };

    ib = new InfoBox(myOptions);
    var image = new google.maps.MarkerImage(ReturnLink("/images/map_marker.png"));							
    var mapOptions = {
      center: new google.maps.LatLng( 46.227638 , 2.213749 ),
      zoom: 4,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("googlemap_places"),mapOptions);
<?php
$discoverAvailable = discoverWorldcitiesAvailable();
$index = 0;
foreach ($discoverAvailable as $item_val) {
    $name = '<span class="mtooltip_title_white">'.$item_val['name'].'</span>';
    $str = $item_val['name'];
    if($item_val['state_name']!=''){
       $name .= '<br/>'.$item_val['state_name'].''; 
    }
    if($item_val['country_name']!=''){
       $name .= '<br/>'.$item_val['country_name'].'';
       $str .= '/'.$item_val['country_code'];
       if( $item_val['state_name']!='' ){
            $str .= '/'.$item_val['state_code'];
       }
    }
    $name = addslashes($name);
    $lat = $item_val['latitude'];
    $log = $item_val['longitude'];
    $cityid = $item_val['cityid'];
    $page_link = addslashes(ReturnLink('map/'.$str));
?>
   markersArrayData.push(Array(map,'<?php echo $name; ?>', '<?php echo $page_link; ?>', <?php echo $lat; ?>, <?php echo $log; ?>));     
<?php } ?>
        
        setTimeout(function() {
            for (var k = 0; k < markersArrayData.length; k++) {
                var data_arr = markersArrayData[k];
                drawmarkers( data_arr[0] , data_arr[1] , data_arr[2] , data_arr[3] , data_arr[4] );
            }
        }, 3000);
        function drawmarkers( map , title, page_link , la , lo ) {
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(la, lo),
                map: map,
                icon: image,
                title: title
            });

            //Setting the onclick marker function
            var base_url = '';
            var onMarkerClick = function() {
                ib.close();
                var marker_c = this;

                var content = '<div class="mtooltip"><div class="mtooltipin"><div class="clsgoo" onClick="ib.close();">X</div>';

                content += '<div class="mtooltip_title">' + title + '</div>';

                content += '</div><div class="anchor_bk"></div><div class="mtooltip_buttons"><a class="mtooltip_buts marginleft9" href="'+page_link+'">'+t('view')+'</a></div></div>';
                
                boxText.innerHTML = content;
                ib.open(map, marker_c);
            };
            google.maps.event.addListener(marker, 'click', onMarkerClick);
        }
        google.maps.event.addListener(map, 'click', function() {
            ib.close();
        });
    });
</script>

<div class="upload-overlay-loading-fix"><div></div></div>
<div id="MiddleBody">
    <div class="liveBar">
        <div class="liveBarIn">
            <div id="collapsemenu">
                <div id="filtercmb">
                    <div class="collapse_thumb"><img src="<?php echo ReturnLink('images/discover/mapcollapsemenu.png'); ?>" alt=""/></div>
                    <select name="filterby" id="filterby" class="select3" style="width:130px;" onchange="changeFilter(this);">
                        <option value="1"  selected="selected"><?php echo _('SMALL MAP'); ?></option>
                        <option value="2" ><?php echo _('HALF SCREEN'); ?></option>
                        <option value="3" ><?php echo _('FULLSCREEN'); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div id="MiddleMainLive">
        <div id="mapLiveCam">            
            <div id="googlemap_places"></div>
        </div>
    </div>
    <div id="MainContent">
        <div id="menu_discover_content">
            <div class="menu_discover_contentright">
                <div class="thingsToDo_left_part">
                    <h1 class="thingsToDo_title"><?php echo _('T Discover'); ?></h1>
                    <h2 class="thingsToDo_description"><?php echo _('A helpful tool for anyone who is making travel plans.'); ?><br/><?php echo _('Discover a new country, save your favorite restaurants, hotels and touristic places'); ?></h2>                    
                </div>
                <div class="thingsToDo_right_part">
                    <div class="city_search">
                        <span><?php echo _('DISCOVER YOUR DESTINATION'); ?></span>
                        <input type="text" id="inputArea" value="" placeholder="..." onkeypress="return checkSubmitcatadd(event)" data-code="" data-id="" data-state-code="" data-iscountry="0"/>
                    <div class="discover_button"><?php echo _('search'); ?></div>
                    </div>
                    <div class="bag_container">
                        <?php 
                        $userBagItemsCount=0;
                        if($user_is_logged==1) $userBagItemsCount = userAllBagItemsCount($user_id);
                        if($userBagItemsCount>99) $userBagItemsCount='99+';
                        echo '<a class="bagcontainer_a" href="'. ReturnLink('bags').'" title="'._('bag').'"><div class="bagcontainer"><div class="bag_count">'.$userBagItemsCount.'</div></div></a>'; ?>
                        <span class="bag_add"><?php echo _('tour bag'); ?> <img style="margin-left:5px;" width="17" height="18" src="<?php echo ReturnLink('images/add-bag.png'); ?>"></span>
                    </div>
                </div>
            </div>
            <div class="menu_discover_contentleft"> 
                <div class="discover_left_line"><span>Trending Destinations</span></div>
                    <ul class="products">
            
                    <?php 
                    $image_array = array('abu-dhabi.jpg','beirut.jpg','berlin.jpg','california.jpg','cannes.jpg','dubai.jpg','hamburg.jpg','london.jpg','monaco.jpg','paris.jpg','rome.jpg','sa-n-fransisco.jpg','vatican.jpg');
                    $i=0;
                    foreach($discoverAvailable as $item_val){
                        $titre = $item_val['name'];
                        $str = $item_val['name'];
                        if($item_val['country_name']!=''){
                           $str .= '/'.$item_val['country_code'];
                           if( $item_val['state_name']!='' ){
                                $str .= '/'.$item_val['state_code'];
                           }
                        }
                        $page_link = ReturnLink('map/'.$str);
                        $imglink=ReturnLink('images/discover/discoverfirst/'.$image_array[$i]);
                        $i++;
                        ?>
                        <li>
                            <a class="avb_discover_item" href="<?php echo $page_link; ?>">
                                <img src="<?php echo $imglink; ?>" class="discover_img" width="205" height="205" />
                                <h4> <?php echo _('Discover').' '.$titre; ?></h4>
                            </a>
                        </li>
                    <?php } ?> 
                    </ul>
            </div>            
        </div>
    </div>
</div>
<?php include("BottomIndex.php"); ?>