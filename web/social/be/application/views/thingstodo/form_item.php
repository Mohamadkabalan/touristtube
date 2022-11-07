<?php
$controller = $this->router->class;
$entity_type_options = array(
    '30' => 'Point of interest',
    '28' => 'Hotel',
    '29' => 'Restaurant'
);
$all_tags = array(
    '0' => 'None',
    '1' => 'Side trip',
    '2' => 'Nearby'
);
if(isset($item)){
    $id = $item['id'];
    $item_title = $item['title'];
    $description = $item['description'];
    $country = $item['country'];
    $city_id = $item['city_id'];
    $latitude = $item['latitude'];
    $longitude = $item['longitude'];
    $image = $item['image'];
    $entity_type = $item['entity_type'];
    $entity_id = $item['entity_id'];
    $order_display = $item['order_display'];
    $xml_360 = $item['xml_360'];
    $tags = $item['tag'];
    if(!$tags){
	$tag = 0;
    }elseif($tags == 'Side trip'){
	$tag = 1;
    }elseif($tags == 'Nearby'){
	$tag = 2;
    }
}

?>
<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo form_open_multipart("$controller/item_submit");?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('image', $image);?>
<div class="form-group"><label class="col-md-2">Related to city</label><div class="col-md-9"><?php echo form_input(array('name'=>'parent_id', 'value' => $parent_id));?></div></div>
<div class="form-group"><label class="col-md-2">Title</label><div class="col-md-9"><?php echo form_input(array('name'=>'title', 'value'=>$item_title, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'description','value'=>$description, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Country</label><div class="col-md-9"><?php echo form_input(array('name'=>'country', 'value' => $country));?></div></div>
<div class="form-group"><label class="col-md-2">City</label><div class="col-md-9"><?php echo form_input(array('name'=>'city_id', 'value' => $city_id));?></div></div>
<div class="form-group"><label class="col-md-2">Longitude</label><div class="col-md-9"><?php echo form_input(array('name'=>'longitude', 'value'=>$longitude, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Latitude</label><div class="col-md-9"><?php echo form_input(array('name'=>'latitude', 'value'=>$latitude, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Order</label><div class="col-md-9"><?php echo form_input(array('name'=>'order_display', 'value'=>$order_display, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Xml_360</label><div class="col-md-9"><?php echo form_input(array('name'=>'xml_360', 'value'=>$xml_360, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Tag</label><div class="col-md-9"><div class="select2-container"><?php echo form_dropdown('tag', $all_tags, !empty($tag) ? $tag : 0);?></div></div></div><div style="clear: both;"></div>
<div class="form-group"><label class="col-md-2">Image</label><div class="col-md-9"> <?php echo form_upload(array('name'=>'image','id'=>'image'));?> </div></div>
<div class="form-group"><label class="col-md-2">Relation type</label><div class="col-md-9"><div class="select2-container"><?php echo form_dropdown('entity_type', $entity_type_options, !empty($entity_type) ? $entity_type : 30);?></div></div></div>
<div class="form-group"><label style="left:-190px;margin-top:10px;" class="col-md-2">Related to</label><div style="width:45%;left:-191px;margin-top:10px;" class="col-md-9"><?php echo form_input(array('name'=>'entity_id', 'value' => $entity_id));?></div></div>
<div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit');?></div></div>
<?php echo form_close();
if(isset($item)){
?>
<br>
<table>
    <tr>
        <th colspan="1" style="background-color: #f8f8f8;">French</th>
        <th colspan="1">Hindu</th>
        <th colspan="1">Chinese</th>
    </tr>
    <tr>
        <?= $this->load->view('thingstodo/details_trans', array('id' => $id, 'bg' => '#f8f8f8', 'key' => 'fr', 'item' => $result['fr'])) ?>
        <?= $this->load->view('thingstodo/details_trans', array('id' => $id, 'bg' => '#fff', 'key' => 'in', 'item' => $result['in'])) ?>
        <?= $this->load->view('thingstodo/details_trans', array('id' => $id, 'bg' => '#fff', 'key' => 'cn', 'item' => $result['cn'])) ?>
    </tr>
</table>
<?php } ?>