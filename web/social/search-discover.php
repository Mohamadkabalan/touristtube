<?php
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );

$type = $request->query->get('t','a');
$id_type = UriGetArg('t');
$id_type = ( !isset($id_type) ) ? 'a' : $id_type;
if( $id_type!='a' && $id_type!='' ) $type = $id_type;
$type = ($type != 'a' && $type != 'i' && $type != 'v' && $type != 'u') ? 'a' : $type;
$srch_options['t'] = $t = $type;
$qrsearch = $id_qr = $q = $route['qr'];
if( !isset($qrsearch) ) $qrsearch='';
$q = str_replace('"', '', $q);
$q =  urldecode($q);
$srch_options['c'] = $c = $route['c'];
$id_category = $route['c'];
$id_category = ( !isset($id_category) ) ? '' : $id_category;
if($id_category!='') $srch_options['c'] = $c = $id_category;

$srch_options['orderby'] = $orderby = $request->query->get('orderby','');
$id_orderby = UriGetArg('orderby');
$id_orderby = ( !isset($id_orderby) ) ? '' : $id_orderby;
if($id_orderby!='') $srch_options['orderby'] = $orderby = $id_orderby;

$srch_options['page'] = $page = $route['np'];
$id_page = $route['np'];
$id_page = ( !isset($id_page) ) ? 1 : intval($id_page);
if($id_page!=1 && $id_page!='') $srch_options['page'] = $page = $id_page;
$srch_options['q'] = $q;
tt_global_set('title', $q);

$letter = $route['l'];
$l = $route['l'];
$l = ( !isset($l) ) ? '' : $l;

$cuisine = $route['cuisine'];
$cuis = $route['cuisine'];
$cuis = ( !isset($cuis) ) ? '' : $cuis;


if( $c!='' && intval($c)>0 ){
    $cat_selected_info = categoryGetInfo($c);
}
$includes = array('js/jquery.infinitescroll.js', 'css/style.css',
    'js/jquery-bubble-popup-v3-fixed.js', 'css/jquery-bubble-popup-v3.css', 'css/search.css',
	'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V, 
	'media1'=>'css_media_query/search_media.css?v='.MQ_SEARCH_MEDIA_CSS_V, 
	'js/media_category_dd.js' 
	);

if (userIsLogged() && userIsChannel()) {
    array_unshift($includes, 'css/channel-header.css');
   tt_global_set('includes', $includes);
    include("TopChannel.php");
} else {
   tt_global_set('includes', $includes);
    include("TopIndex.php");
}

define('SEARCH_ROW_PER_PAGE', 15);

$n_videos = 0;
$n_images = 0;
$did_you_mean = '';
$userVideos = array();
$paging = $page - 1;
if($paging<0) $paging=0;

$srch_options['limit'] = SEARCH_ROW_PER_PAGE;
$params1 = array('hosts' => array($CONFIG [ 'elastic' ] [ 'ip' ]));
$client = new Elasticsearch\Client($params1);
if($id_category == 1){
    $searchParams['index'] = 'tt';
    $searchParams['type']  = 'restaurant';
    $qrsubarray=array();
    if( $qrsearch != '' ){
        $qrsubarray[]['multi_match'] = array(   'query' => $qrsearch,
                                                'fields' => array(
                                                    "locality^5", "region^5", "name^2", "admin_region^5", "country_name"
                                            ));
    }
    if( $l != '' ){
        $qrsubarray[]['prefix'] = array( 'title1' =>  $l );
    }
    if( $cuis != ''){
        $qrsubarray[]['match'] = array( 'category_labels' => $cuis );
    }
    $searchParams['body'] = array(
                                'query' => array(
                                    'bool' => array(
                                        'must' => $qrsubarray
                                    )
                                )
                            );
    
    $retDoc = $client->search($searchParams);
    $count_of_all_records = $retDoc['hits']['total'];
    $total_num_Pages = ceil($retDoc['hits']['total']/12);
    $userVideosB = array('numFound' => $count_of_all_records,
                         'totalPages' => $total_num_Pages);
}elseif($id_category == 2){
    $srch_options = array(
                    'letter' => $l,
                    'search_string' => $qrsearch,
                    'n_results' => true
    );
    $count_of_all_records = getSuggestedHotels($srch_options);
    $total_num_Pages = ceil($count_of_all_records['count']/12);
    $userVideosB = array('numFound' => $count_of_all_records['count'],
                         'totalPages' => $total_num_Pages);
}elseif($id_category == 3){
    $srch_options = array(
                    'letter' => $l,
                    'search_string' => $qrsearch,
                    'n_results' => true
    );
    $count_of_all_records = getSuggestedPois($srch_options);
    $total_num_Pages = ceil($count_of_all_records['count']/12);
    $userVideosB = array('numFound' => $count_of_all_records['count'],
                         'totalPages' => $total_num_Pages);
}

$search_result_count = $userVideosB['numFound'];
$totalPages = $userVideosB['totalPages'];

if($id_category == 1){
    $urll = "Restaurants";
}elseif($id_category == 2){
    $urll = "Hotels";
}elseif($id_category == 3){ 
    $urll = "Landmarks";
}
$myUrl = $urll."-".$q."-";
$myUrl1 = $myUrl;
if ($l <> ''){
    $myUrl1 .= "$l";
}
if ($c <> ''){
    $myUrl1 .= "_";
}
$myUrlorder = $myUrl1;



$search_paging_output='';
if ($totalPages > 1) {
    $search_paging_output = '<div class="searchPaginationClass"><div class="pagelinks">';
    $xStart = $page - 5;
    if ($xStart < 1)
        $xStart = 1;
    $xEnd = $xStart + 10;
    if ($xEnd > $totalPages)
        $xEnd = $totalPages;
    for ($x = $xStart; $x <= $xEnd; $x++) {
        //$search_paging_output .= '<a href="' . ReturnLink($myUrl1 . '&page=' . $x) . '" ';
        if($id_category == 1){
            $link = returnRestaurantCuisineLink($cuis,$q,$l,$x);
            $search_paging_output .= '<a title="'.$page_title.'" href="' . $link . '" ';
        }else{
            $search_paging_output .= '<a title="'.$page_title.'" href="' . ReturnLink($myUrl1.$x."_C".$id_category ) . '" ';
        }
        if ($x == $page)
            $search_paging_output .= 'class="current"';
        $search_paging_output .= '>' . $x . '</a> .';
    }
    $search_paging_output .= '    </div></div>';
}
?>
<script type="text/javascript">
    var MEDIA_PROFILE_HEIGHT = <?php echo MEDIA_PROFILE_HEIGHT; ?>;
    var MEDIA_PROFILE_WIDTH = <?php echo MEDIA_PROFILE_WIDTH; ?>;
    var MEDIA_LIST_MODE_RESULTS = <?php echo MEDIA_LIST_MODE_RESULTS; ?>;
    var MEDIA_THUMB_MODE_RESULTS = <?php echo MEDIA_THUMB_MODE_RESULTS; ?>;
</script>
<div id="MiddleInsideNormal" style="height:auto; min-height: 0;">
    <div id="headerNavProfile" style="clear:both">
        <div id="headerNavProfileInternal" style="height: 43px; z-index: 5;">
            <div class="videosFilter" id="options">
                <div class="search_n_results">


                    <script type="text/javascript">
                        function preloadContainer() {
                            $("#contentcontainer").preloader();
                        }
                        SearchPageSet('search');
                        $(document).ready(function() {
                            preloadContainer();
                            $('.SearchPhotosButton').css({'cursor': 'pointer'}).click(function() {
                                var tmp = $('#SearchField').val();
                                $('#SearchCategory2').click();
                                $('#SearchField').val(tmp);
                                $('.SearchSubmit').addClass('keepcategory');
                                $('.SearchSubmit').click();
                            });
                            $('.SearchVideosButton').css({'cursor': 'pointer'}).click(function() {
                                var tmp = $('#SearchField').val();
                                $('#SearchCategory3').click();
                                $('#SearchField').val(tmp);
                                $('.SearchSubmit').addClass('keepcategory');
                                $('.SearchSubmit').click();
                            });
                            $('.SearchFoundButton').css({'cursor': 'pointer'}).click(function() {
                                var tmp = $('#SearchField').val();
                                $('#SearchCategory1').click();
                                $('#SearchField').val(tmp);
                                $('.SearchSubmit').addClass('keepcategory');
                                $('.SearchSubmit').click();
                            });
                        });
                    </script>
                </div>
                    <?php print $search_paging_output; ?>
                    <?php if ($did_you_mean) print '<div class="did_you_mean">' . $did_you_mean . '</div>'; ?>
                <div class="SearchHeaderOver">
                    <div class="SearchHeaderOverin"></div>
                    <div class="icons-overtik"></div> 
                </div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#search_order').click(function() {
                            $('#sort-by').toggle();
                        });
                    });
                </script>
            </div>
        </div>
    </div>
    <div id="SearchBreadCrumbs" class="SearchBreadCrumbs">
    </div>    
    <div id="contentcontainer">
        <div id="contentholder" data-count="">
            <div id="content">
                <?php
                    $action_text='';
                    if($q != '' || $cuis != ''){
                        $action_array = array();                   
                        $action_text1 =  'Best of %s %s on TouristTube';
                        $action_array[]= $q;
                        $action_array[]= strtolower($cuis);
                        $action_text = vsprintf(_($action_text1), $action_array);
                    }
                ?>              
                <ul class="CategoriesTTL_search_ul" itemscope itemtype="http://schema.org/BreadcrumbList">
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a href="<?php echo ReturnLink($myUrl1); ?>" id="CategoriesTTL_search" class="CategoriesTTL_searchTT" itemprop="item"><?php echo $action_text; ?></a>
                    </li>                    
                </ul>
                <div class="fl" >                    
                    <div id="IndexCategories">
                        <h1 id="CategoriesTTL_search"><?php echo _('Categories'); if( $c!='' && intval($c)>0 ) echo ' | <span>'.$cat_selected_info['title'].'</span>'; ?></h1>
                        <div id="Categories">
                            <img width="16" height="16" src="<?php GetLink('images/circle-categories-roll-over.png') ?>" style="display:none; position: absolute;" id="CategoryMarker" alt="<?php echo _('category marker') ?>"/>
                            
                            <?php 
                                $categories = categoryGetHash(array('orderby' => 'item_order'));
                            ?>
                            <div style="height: <?php echo (count($categories) * 19 - 11).'px'; ?>" id="CategoryVerticalLine"></div>
                            <ul>
                                <?php
                                
                                $i = 0;
                                foreach ($categories as $cat_id => $name) {
                                    if ($cat_id == $c) {
                                        $myUrl = $q."-".$urll."-";
                                        printf('<li class="IndexCategory"><a href="%s" class="active">%s</a></li>', ReturnLink($myUrl."Sa_1_".$cat_id), $name);
                                        ?><li><img width="16" height="16" src="<?php GetLink('images/circle-categories.png') ?>" style="position: absolute; top: <?php print ($i * 18) + 2 ?>px; left: -1px;" alt="selected marker"/></li>
                                        <?php
                                    } else {
                                        $myUrl = $q."-".$name."-";
                                        printf('<li class="IndexCategory"><a href="%s">%s</a></li>', ReturnLink($myUrl."Sa_1_".$cat_id), $name);
                                        ?>
                                        <?php
                                    }
                                    $i++;
                                }
                                ?>
                            </ul>
                            
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('#IndexCategories li.IndexCategory').mouseover(function() {
                                    var $Category = $(this);
                                    var pos = $Category.position();
                                    if ($('a', $(this)).hasClass('active'))
                                        return;
                                    //it was pos.top + 6 made it pos.top + 3
                                    $('#CategoryMarker').show().css({
                                        top: (pos.top) + 'px',
                                        left: '-1px'
                                    });
                                }).mouseout(function() {
                                    $('#CategoryMarker').hide();
                                });
                            });
                        </script>
                    </div>
                </div>
                <div id="container" class="variable-sizes clearfix">
                    <script type="text/javascript">
                        SearchPage = 1;
                        SearchType = '<?php echo $type ?>';
                        SearchString = '';
                        var SearchResultsPage = 'parts/search_discover_results.php';
                    </script>
                    <?php
                    $_GET['type'] = $request->query->set('type',urlencode($type));
                    $_GET['t'] = $request->query->set('t',urlencode($t));
                    $_GET['page'] = $request->query->set('page',urlencode($page));
                    $_GET['orderby'] = $request->query->set('orderby',urlencode($orderby));
                    $_GET['order'] = $request->query->set('order',urlencode($order));
                    include('parts/search_discover_results.php');
                    if (UriArgIsset('pageIndex')):
                        $pageIndex = intval(UriGetArg('pageIndex'));
                    else:
                        $pageIndex = 1;
                    endif;
                    ?>
                    <?php print $search_paging_output; ?>
                <nav id="page_nav">
                    <a href="<?php GetLink('search/SearchCategory/' . $type . '/ss/' . $search_string . '/pageIndex/' . $pageIndex); ?>"></a>
                </nav>
            </div>
        </div>
    </div>
</div>
<?php include("BottomIndex.php"); ?>