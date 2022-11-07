<?php
$controller = $this->router->class;
if(isset($thingstodo)){
    $id = $thingstodo['id'];
    $thingstodo_title = $thingstodo['title'];
    $h3 = $thingstodo['h3'];
    $p3 = $thingstodo['p3'];
    $h4 = $thingstodo['h4'];
    $p4 = $thingstodo['p4'];
    $description = $thingstodo['description'];
    $order_display = $thingstodo['order_display'];	
    $region_id = $thingstodo['parent_id'];
    $country_code = $thingstodo['country_code'];
    $city_id = $thingstodo['city_id'];
    $desc_thingstodo = $thingstodo['desc_thingstodo'];
    $desc_discover = $thingstodo['desc_discover'];
    $desc_hotelsin = $thingstodo['desc_hotelsin'];
}
else{
    $region_id = $regionid;
    $thingstodo_title = 'Things to do in';
}
?>


<h1><?=(isset($title) ? $title : '')?></h1>

<?php echo validation_errors(); ?>
<?php echo form_open_multipart("$controller/submit/".$rid);?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('rid', $regionid);?>
<div class="form-group"><label class="col-md-2">Title</label><div class="col-md-9"><?php echo form_input(array('name'=>'title', 'value'=>$thingstodo_title, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'description','value'=>$description, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">H3</label><div class="col-md-9"><?php echo form_input(array('name'=>'h3','value'=>$h3, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">P3</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'p3','value'=>$p3, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">H4</label><div class="col-md-9"><?php echo form_input(array('name'=>'h4','value'=>$h4, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">P4</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'p4','value'=>$p4, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Order</label><div class="col-md-9"><?php echo form_input(array('name'=>'order_display', 'value'=>$order_display, 'class'=>'form-control'));?></div></div>
<div class="form-group" style="clear:both;"><label class="col-md-2">Image</label><div class="col-md-9"> <?php echo form_upload(array('name'=>'image','id'=>'image'));?> </div></div>
<div class="form-group" style="clear:both;"><label class="col-md-2">Country</label><div class="col-md-9"><?php echo form_input(array('name'=>'country_code', 'value' => $country_code));?></div></div>
<div class="form-group" style="clear:both;"><label class="col-md-2">State</label><div class="col-md-9"><?php echo form_input(array('name'=>'state_id', 'value' => $state_id));?></div></div>
<div class="form-group" style="clear:both;"><label class="col-md-2">City</label><div class="col-md-9"><?php echo form_input(array('name'=>'city_id', 'value' => $city_id));?></div></div>
<div class="form-group" style="clear:both;"><label class="col-md-2">Things to do Country</label><div class="col-md-9"><?php echo form_input(array('name'=>'region', 'value' => $region_id));?></div></div>
<div class="form-group"><label class="col-md-2">Things To Do Keywords</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'desc_thingstodo','value'=>$desc_thingstodo, 'class'=>'form-control'));?></div></div>
<div class="form-group" style="clear:both;"><label class="col-md-2">Discover Keywords</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'desc_discover','value'=>$desc_discover, 'class'=>'form-control'));?></div></div>
<div class="form-group" style="clear:both;"><label class="col-md-2">Hotels In Keywords</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'desc_hotelsin','value'=>$desc_hotelsin, 'class'=>'form-control'));?></div></div>

<div class="form-group" style="clear:both;"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit');?></div></div>
<?php echo form_close();?>











