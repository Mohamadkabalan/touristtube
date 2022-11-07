<?php 
$controller = $this->router->class;
if(isset($thingstodo)){
    $id = $thingstodo['id'];
    $thingstodo_title = $thingstodo['title'];   
    $description = $thingstodo['description'];
    $h3 = $thingstodo['h3'];
    $p3 = $thingstodo['p3'];
    $h4 = $thingstodo['h4'];
    $p4 = $thingstodo['p4'];
}
?>
<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo form_open_multipart("$controller/lang_countrysubmit");?>
<?php echo form_hidden('lang', $key);?>
<?php echo form_hidden('id', $countryid);?>
<div class="form-group"><label class="col-md-2">Title</label><div class="col-md-9"><?php echo form_input(array('name'=>$key.'_title', 'value'=>$thingstodo_title, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>$key.'_description','value'=>$description, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">H3</label><div class="col-md-9"><?php echo form_input(array('name'=>$key.'_h3','value'=>$h3, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">P3</label><div class="col-md-9"><?php echo form_textarea(array('name'=>$key.'_p3','value'=>$p3, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">H4</label><div class="col-md-9"><?php echo form_input(array('name'=>$key.'_h4','value'=>$h4, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">P4</label><div class="col-md-9"><?php echo form_textarea(array('name'=>$key.'_p4','value'=>$p4, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit');?></div></div>
<?php echo form_close();?>