<?php
$controller = $this->router->class;
if(isset($item)){
    $id = $item['id'];
    $city_id = $item['city_id']; 	 
	$sel_type = $item['sel_type']; 
	$published = $item['published'];
	$seltype = $item['seltype'];
}
$published_opt = array(
    '1' => 'Published',
    '-2' => 'Unpublished'
);
$seltype_opt = array(
    '1' => 'Small',
    '2' => 'Big'
); 
?>
<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo form_open_multipart("$controller/hselection_submit");?>
<?php echo form_hidden('id', $id);?>
<div class="form-group"><label class="col-md-2">City</label><div class="col-md-9"><?php echo form_input(array('name'=>'cityId', 'value' => $city_id));?></div></div>
<div class="form-group" style="clear:both;"><label class="col-md-2">Image</label><div class="col-md-9"> <?php echo form_upload(array('name'=>'image','id'=>'image'));?> </div></div>
<div class="form-group"><label class="col-md-2">Selection Type</label><div class="col-md-9"><?php echo form_dropdown('seltype', $seltype_opt, !empty($seltype) ? $seltype : 'small');?></div></div>
<div class="form-group"><label class="col-md-2" style="clear:both;">Status</label><div class="col-md-9"><?php echo form_dropdown('published', $published_opt, !empty($published) ? $published : 'small');?></div></div>
<div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit');?></div></div>
<?php echo form_close();?>