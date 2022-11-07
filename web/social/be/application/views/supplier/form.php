<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$controller = $this->router->class; 
if(isset($user)){
    $id = $supplier['id'];
    $firstName = $user['fname'];
    $lastName = $user['lname'];
    $userName = $user['username'];
    $country_code = $supplier['country'];
    $role = $user['role'];
    $is_admin = $user['is_admin'];
    $email = $supplier['email'];
}
?>
<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo $this->form_builder->open_form(array('action' => "$controller/submit")); ?> 

<?php echo form_input(array('id'=>'id', 'type'=>'hidden', 'name'=>'id', 'value' => !empty($id) ? $id  : ''));?>

<div class="form_group">
         <label class='col-md-2 control-label'><em class="require">*</em>Fname</label>
        <div class="col-md-9">
             <?php echo form_input(array('name'=>'fname', 'value' => !empty($firstName) ? $firstName  : set_value('fname'),'class'=>'form-control'));?>
             <?php echo form_error('fname');?>
        </div>
</div>

<div class="form_group">
         <label class='col-md-2 control-label'><em class="require">*</em>Lname</label>
         <div class="col-md-9">
       <?php echo form_input(array('name'=>'lname', 'value' => !empty($lastName) ? $lastName  : set_value('lname'),'class'=>'form-control'));?>
        <?php echo form_error('lname');?>
         </div>
</div>

<div class="form_group">
         <label class='col-md-2 control-label'><em class="require">*</em>Email</label>
         <div class="col-md-9">
       <?php echo form_input(array('name'=>'email', 'value' => !empty($email) ? $email  : set_value('email'),'class'=>'form-control'));?>
        <?php echo form_error('email');?>
         </div>
</div>

<div class="form_group">
         <label class='col-md-2 control-label'><em class="require">*</em>Username</label>
         <div class="col-md-9">
       <?php echo form_input(array('name'=>'username', 'value' => !empty($userName) ? $userName  : set_value('username'),'class'=>'form-control'));?>
        <?php echo form_error('username');?>
         </div>
</div>

<div class="form_group">
         <label class='col-md-2 control-label'><em class="require">*</em>Password</label>
         <div class="col-md-9">
       <?php echo form_input(array('name'=>'password', 'type'=>'password', 'value' => !empty($password) ? $password  : set_value('password'),'class'=>'form-control'));?>
        <?php echo form_error('password');?>
         </div>
</div>

 <div class="form_group">
         <label class='col-md-2 control-label'><em class="require">*</em>Country</label>
         <div class="col-md-9">
       <?php echo form_input(array('name'=>'country_code', 'value' => !empty($country_code) ?  $country_code : set_value('country_code'),'class'=>'form-control'));?>
        <?php echo form_error('country_code');?>
         </div>
 </div>

<?php 
$options = array(
            'admin' => 'Admin',
            'editor' => 'Editor',
            'dealer' => 'Dealer',
            'copywriter' => 'Copywriter',
            'translator' => 'Translator',
            'user_translator' => 'User Translator',
            'user_translator_admin' => 'User Translator Admin' // added by sushma mishra on 27-08-2015 to add a new role in user type drop down
        );
?>

<div class="form_group">
        <label class='col-md-2 control-label'><em class="require">*</em>Role</label>
        <div class="col-md-9"  style='margin-top: 10px;'>
            <?php 
                $user_role = set_value('role');
                $user_role = !empty($user_role)?$user_role : "dealer";
                $pre_role = !empty($role)?$role : $user_role;
               // echo form_dropdown('role',$options,$pre_role,'class="form-control"');
                echo form_input(array('name'=>'role', 'value' => !empty($pre_role) ?  $pre_role : set_value('role'),'class'=>'form-control','readonly'=>'readonly'));
            ?>
            <?php echo form_error('role');?>
         </div>
</div>

 <div class="form_group">
     <label class='col-md-2 control-label'>&nbsp;</label>
    <?php echo form_submit('submit', 'submit',"class='btn btn-primary'");?>
</div>

<?= $this->form_builder->close_form(); ?>

