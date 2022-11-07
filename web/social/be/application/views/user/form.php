<?php 
$controller = $this->router->class; 
if(isset($user)){
    $id = $user['id'];
    $firstName = $user['fname'];
    $lastName = $user['lname'];
    $userName = $user['username'];
    $role = $user['role'];
    $is_admin = $user['is_admin'];
}
?>
<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo $this->form_builder->open_form(array('action' => "$controller/submit")); ?>     
<?php
$pref = (isset($prefix)) ? $prefix : '';
$group_sufx = (isset($prefix) && !empty($prefix)) ? str_replace('_', '', $prefix) : uniqid();
$input_span = (isset($input_span)) ? $input_span . ' ' : '';

echo $this->form_builder->build_form_horizontal(
     array(
       array(
        'id' => 'id',
        'value' => !empty($id) ? $id : '',
        'type'=>'hidden'
    ),array(
        'id' => 'fname',
        'placeholder' => 'First Name',
        'class'=>$input_span." required",
        'value' => !empty($firstName) ? $firstName : ''
    ),
    array(
      'id' => 'lname',
       'placeholder' => 'Last Name',
       'class'=>$input_span." required",
        'value' => !empty($lastName) ? $lastName : '',
    ),
    array(
      'id' => 'username',
       'placeholder' => 'Username',
       'class'=>$input_span." required",
        'value' => !empty($userName) ? $userName : '',
    ),
    array(
        'id' => 'password',
        'class'=>$input_span." required",
        'type' => 'password'
    ),
    array(
        'id' => 'role',
        'type' => 'dropdown',
        'options' => array(
            'admin' => 'Admin',
            'editor' => 'Editor',
            'dealer' => 'Dealer',
            'copywriter' => 'Copywriter',
            'hotel_desc_writer' => 'Hotel description writer',
            'translator' => 'Translator',
            'user_translator' => 'User Translator',
            'user_translator_admin' => 'User Translator Admin', // added by sushma mishra on 27-08-2015 to add a new role in user type drop down
            'hotel_chain' => 'Hotel Chain'
        ),
        'value' => !empty($role) ? $role : 'editor'
    ),
    array(
        'id' => 'submit',
        'type' => 'submit'
    )
        ));
?>
<?= $this->form_builder->close_form(); ?>