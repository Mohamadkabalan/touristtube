<?php
$user_data = $this->session->userdata('logged_in');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?=(isset($title) ? $title : '')?></title>
<base href="<?=base_url()?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="<?=(isset($description) ? $description : '')?>">
<meta name="viewport" content="width=device-width, user-scalable=no">
<link rel="stylesheet" href="css/bootstrap.min.css">
<!--<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">-->
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/screen.css">
<link rel="stylesheet" href="css/jquery.fileupload.css">
<link rel="icon" type="image/png" href="images/icon.png">
<link rel="stylesheet" href="css/select2.css">
<link rel="stylesheet" href="css/select2-bootstrap.css">
<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
<link rel="stylesheet" href="css/daterangepicker.css">
<link rel="stylesheet" href="css/datepicker3.css">
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css">
<?php if(isset($cssIncludes) && count($cssIncludes)){
     foreach ($cssIncludes as $cssInclude) {
         echo '<link rel="stylesheet" href="css/'.$cssInclude.'">';
     }
} ?>
<link rel="apple-touch-icon" type="image/png" href="images/apple-touch-icon.png">
<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.ui.core.min.js"></script>
<script src="js/jquery.ui.widget.min.js"></script>
<script src="js/jquery.ui.position.min.js"></script>
<script src="js/jquery.ui.menu.min.js"></script>
<script src="js/jquery.ui.autocomplete.min.js"></script>
<script src="js/ajaxfileupload.js"></script>
<script src="js/jquery.jeditable.mini.js"></script>
<script src="js/bootstrap.min.js"></script>
<!--<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>-->
<script src="js/select2.min.js"></script>
<script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
<script src="js/moment-with-locales.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datetimepicker.js"></script>
<script src="js/application.js"></script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false&amp;libraries=places"></script>
<script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/23/6/common.js"></script>
<script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/23/6/util.js"></script>
<script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/23/6/controls.js"></script>
<script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/23/6/places_impl.js"></script>
<script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/23/6/stats.js"></script>
<?php if(isset($jsIncludes) && count($jsIncludes)){
     foreach ($jsIncludes as $jsInclude) {
         echo '<script src="js/'.$jsInclude.'"></script>';
     }
} ?>
</head>
<body>
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-37276506-1', 'auto');
  ga('send', 'pageview');

</script>
<div class="container">


  <nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
        <a class="navbar-brand" href="dashboard"><img src="/media/images/Logo.png"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
          <?php if($user_data['role'] != 'translator' && $user_data['role'] != 'user_translator' && $user_data['role'] != 'user_translator_admin' && $user_data['role'] != 'dealer' && $user_data['role'] != 'copywriter' && $user_data['role'] != 'hotel_chain'){ ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hotels <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="hotels/index">List</a></li>
            <li><a href="hotels/add">Add</a></li>
            <li><a href="hotels/hotel_search">Search</a></li>
            <li><a href="hotels/facilities">Facilities</a></li>
            <li><a href="hotels/amenities">Amenities</a></li>
          </ul>
        </li>
         <?php if($user_data['role'] != 'translator' && $user_data['role'] != 'user_translator' && $user_data['role'] != 'user_translator_admin' && $user_data['role'] != 'dealer' && $user_data['role'] != 'hotel_desc_writer' && $user_data['role'] != 'hotel_chain'){ ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Things to do<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="thingstodo/index">Regions List</a></li>
            <li><a href="thingstodo/thingstodoplace">Things to do Countries</a></li>
            <li><a href="thingstodo/country_view">Things to do List</a></li>
            <li><a href="thingstodo/view">Things to do Pois</a></li>
            <li><a href="thingstodo/division_category">Things to do Division Category</a></li>
            <li><a href="thingstodo/division">Things to do Division</a></li>
            <?php if($user_data['role'] != 'copywriter'){?>
            <li><a href="thingstodo/addregion">Add Region</a></li>
            <?php } ?>
          </ul>
        </li>   
        <?php }?>
        <?php if($user_data['role'] != 'hotel_desc_writer' && $user_data['role'] != 'hotel_chain'){?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Discover Hotels <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="hotel/index">List</a></li>
            <li><a href="hotel/add">Add</a></li>
            <li><a href="hotel/features">Features</a></li>
            <li><a href="hotel/addedByUser">Added By User</a></li>
            <li><a href="hotel/hotelSelection">Hotel Selections</a></li>
          </ul>
        </li>
        <?php } ?>
        <?php if( $user_data['role'] != 'hotel_desc_writer' && $user_data['role'] != 'hotel_chain'){ ?>
        <li class="dropdown" style="display: none;">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Restaurants <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="restaurant/index">List</a></li>
            <li><a href="restaurant/add">Add</a></li>
            <li><a href="restaurant/cuisines">Cuisines</a></li>
            <li><a href="restaurant/addedByUser">Added By User</a></li>
			<li><a href="restaurant/restaurantSelection">Restaurant Selections</a></li>
          </ul>
        </li>     
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">POI<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="poi/index">List</a></li>
            <li><a href="poi/add">Add</a></li>
            <li><a href="poi/categories">Categories</a></li>
            <li><a href="poi/addedByUser">Added By User</a></li>
          </ul>
        </li>   
        <?php }?>
          <?php } ?>
        <?php if($user_data['role'] != 'translator' && $user_data['role'] != 'user_translator' && $user_data['role'] != 'user_translator_admin' && $user_data['role'] != 'dealer' && $user_data['role'] != 'copywriter' && $user_data['role'] != 'hotel_desc_writer' && $user_data['role'] != 'hotel_chain'){ ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Logs<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="log/searchLogs">Search logs</a></li>
            <li><a href="log/requestInvitation">Request Invitation</a></li>
            <li><a href="log/report">Report</a></li>
            <li><a href="log/userTracking">User Tracking</a></li>
          </ul>
        </li>   
        <?php }?>
        <?php if($user_data['role'] != 'dealer' && $user_data['role'] != 'copywriter' && $user_data['role'] != 'hotel_desc_writer' && $user_data['role'] != 'hotel_chain') {?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Translation<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="ml/index">Media</a></li>
<!--            <li><a href="ml/placesToStay">Places To Stay</a></li>
            <li><a href="ml/whereIs">Where Is</a></li>-->
          </ul>
        </li>  
        <?php } ?>
        <?php if($user_data['role'] != 'translator' && $user_data['role'] != 'user_translator' && $user_data['role'] != 'user_translator_admin' && $user_data['role'] != 'dealer' && $user_data['role'] != 'copywriter' && $user_data['role'] != 'hotel_desc_writer' && $user_data['role'] != 'hotel_chain'){ ?>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Manage<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="media/index">Media</a></li>
                <li><a href="ml/mediaCategory">Media Categories</a></li>
                <li><a href="ml/channelCategory">Channel Categories</a></li>
                <li><a href="ml/reportReason">Report Reason</a></li>
                <li><a href="media/albums_list">Albums</a></li>
            </ul>
        </li>
        <?php } ?>
        <?php if($user_data['role'] == 'hotel_chain'){?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Flights <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="flight/index">List</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">POI<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="poi/index">List</a></li>
          </ul>
        </li>   
        <?php } ?>
        <?php if($user_data['role'] == 'admin'){ ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">SEO<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="seo/alias">Alias</a></li>
            <li><a href="seo/whereis">Where is</a></li>
            <li><a href="seo/homepage">Home page</a></li>
            <li><a href="seo/discover">Discover</a></li>
          </ul>
        </li>   
        <?php }?>
        <?php if($user_data['role'] == 'admin' || $user_data['role'] == 'dealer'){?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Deals<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="deal/index">List</a></li>
            <li><a href="deal/add">Add</a></li>
          </ul>
        </li>   
        <?php }?>
        
         <?php if($user_data['role'] == 'admin'){ ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Suppliers<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="supplier/index">List</a></li>
            <li><a href="supplier/add">Add</a></li>
          </ul>
        </li>   
        <?php }?>
        
        <?php if($user_data['role'] == 'admin'){ ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Users<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="user/index">List</a></li>
            <li><a href="user/add">Add</a></li>
            <li><a href="user/activities">Activities</a></li>
          </ul>
        </li>   
        <?php }?>

      </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="user/profile">Welcome <?php echo $username; ?></a></li>
        <li><a href="dashboard/logout">Logout</a></li>

      </ul>
    </div><!-- /.navbar-collapse -->
 </div><!-- /.container-fluid -->
</nav>
<?= (isset($content) ? $this->load->view($content) : NULL)?>    
</div>
    
 <!--   <script type="text/javascript">
var clicky_site_ids = clicky_site_ids || [];
clicky_site_ids.push(100798585);
(function() {
  var s = document.createElement('script');
  s.type = 'text/javascript';
  s.async = true;
  s.src = '//static.getclicky.com/js';
  ( document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0] ).appendChild( s );
})();
</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100798585ns.gif" /></p></noscript>
-->
<!-- Piwik -->
<!--
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//piwik.touristtube.com/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//piwik.touristtube.com/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
-->
<!-- End Piwik Code -->



</body>
</html>