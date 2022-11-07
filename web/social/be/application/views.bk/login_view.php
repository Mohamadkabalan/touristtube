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
<link rel="icon" type="image/png" href="/media/images/icon.png">
<link rel="apple-touch-icon" type="image/png" href="/media/images/apple-touch-icon.png">
</head>
<body>
<div class="container">
<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo $this->form_builder->open_form(array('action' => 'verifylogin')); ?>     
<?php
$pref = (isset($prefix)) ? $prefix : '';
$group_sufx = (isset($prefix) && !empty($prefix)) ? str_replace('_', '', $prefix) : uniqid();
$input_span = (isset($input_span)) ? $input_span . ' ' : '';

echo $this->form_builder->build_form_horizontal(
     array(
       array(
        'id' => 'username',
        'placeholder' => 'Username',
        'class'=>$input_span." required",
        'value' => !empty($username) ? $username : ''
    ),
    array(
      'id' => 'password',
       'placeholder' => 'Password',
       'class'=>$input_span." required",
        'value' => !empty($password) ? $password : '',
        'type'=>'password'
    ),  
    array(
        'id' => 'submit',
        'type' => 'submit'
    )
        ));
?>
<?= $this->form_builder->close_form(); ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/application.js"></script>
</body>
</html>