<?php
$id = isset($review) ? $review->id: '';
$title = isset($review) ? $review->title: '';
$description = isset($review) ? $review->description: '';
?>
<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo form_open('poi/review_submit');?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('poi_id', $poi_id);?>
<div class="form-group"><label class="col-md-2">Title</label><div class="col-md-9"><?php echo form_input(array('name'=>'title', 'value'=>$title, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'description','value'=>$description, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit');?></div></div>
<?php echo form_close();?>













