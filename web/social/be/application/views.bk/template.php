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
<link rel="stylesheet" href="css/screen.css">
<link rel="stylesheet" href="css/jquery.fileupload.css">

<link rel="icon" type="image/png" href="images/icon.png">
<link rel="apple-touch-icon" type="image/png" href="images/apple-touch-icon.png">
</head>
<body>
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
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hotels <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="hotel/index/Paris">List</a></li>
            <li><a href="hotel/add">Add</a></li>
            <li><a href="hotel/facilities">Facilities</a></li>
          </ul>
        </li>
<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Restaurants <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="restaurant/index/FR">List</a></li>
            <li><a href="restaurant/add">Add</a></li>
          </ul>
        </li>     
        
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">POI<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="poi">List</a></li>
            <li><a href="poi/add">Add</a></li>
          </ul>
        </li>   


      </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="user/profile">Welcome <?php echo $username; ?></a></li>
        <li><a href="dashboard/logout">Logout</a></li>

      </ul>
    </div><!-- /.navbar-collapse -->
 </div><!-- /.container-fluid -->
</nav>
<?=(isset($content) ? $this->load->view($content) : NULL)?>    
</div>
<script src="js/jquery.min.js"></script>
<script src="js/jquery.ui.core.min.js"></script>
<script src="js/jquery.ui.widget.min.js"></script>
<script src="js/jquery.ui.position.min.js"></script>
<script src="js/jquery.ui.menu.min.js"></script>
<script src="js/jquery.ui.autocomplete.min.js"></script>
<script src="js/ajaxfileupload.js"></script>
<script src="js/jquery.jeditable.mini.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/application.js"></script>
</body>
</html>