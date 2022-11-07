<?php
$controller = $this->router->class;
$entity_type_options = array(
    '30' => 'Point of interest',
    '28' => 'Hotel',
    '29' => 'Restaurant'
);
if(isset($item)){
    $id = $item['id'];
    $item_title = $item['title'];
    $description = $item['description'];
    $country = $item['country'];
    $city_id = $item['city_id'];
    $image = $item['image'];
    $entity_type = $item['entity_type'];
    $entity_id = $item['entity_id'];
    $order_display = $item['order_display'];
}

?>
<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo form_open_multipart("$controller/item_submit");?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('image', $image);?>
<?php echo form_hidden('parent_id', $parent_id);?>
<div class="form-group"><label class="col-md-2">Title</label><div class="col-md-9"><?php echo form_input(array('name'=>'title', 'value'=>$item_title, 'class'=>'form-control', 'readonly' => ''));?></div></div>
<div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'description','value'=>$description, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit');?></div></div>
<?php echo form_close();