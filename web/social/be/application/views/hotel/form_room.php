<?php
$controller = $this->router->class;
$id = isset($room) ? $room['id']: '';
$title = isset($room) ? $room['title']: '';
$description = isset($room) ? $room['description']: '';
$num_person = isset($room) ? $room['num_person']: '';
$price = isset($room) ? $room['price']: '';
$pic1name = isset($room) ? $room['pic1']: '';
$pic2name = isset($room) ? $room['pic2']: '';
$pic3name = isset($room) ? $room['pic3']: '';
?>
<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo form_open_multipart("$controller/room_submit");?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('hotel_id', $hotel_id);?>
<?php echo form_hidden('pic1name', $pic1name);?>
<?php echo form_hidden('pic2name', $pic2name);?>
<?php echo form_hidden('pic3name', $pic3name);?>
<div class="form-group"><label class="col-md-2">Title</label><div class="col-md-9"><?php echo form_input(array('name'=>'title', 'value'=>$title, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'description','value'=>$description, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Num persons</label><div class="col-md-9"><?php echo form_input(array('name'=>'num_person', 'value'=>$num_person, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Price</label><div class="col-md-9"><?php echo form_input(array('name'=>'price', 'value'=>$price, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Picture 1</label><div class="col-md-9"><?php if($pic1name<>'') echo img(array('src'=>'uploads/rooms/thumb/'.$pic1name, 'width'=>150));?> <?php echo form_upload(array('name'=>'pic1','id'=>'pic1'));?> </div></div>
<div class="form-group"><label class="col-md-2">Picture 2</label><div class="col-md-9"><?php if($pic2name<>'') echo  img(array('src'=>'uploads/rooms/thumb/'.$pic2name, 'width'=>150));?> <?php echo form_upload(array('name'=>'pic2'));?> </div></div>
<div class="form-group"><label class="col-md-2">Picture 3</label><div class="col-md-9"><?php if($pic3name<>'') echo  img(array('src'=>'uploads/rooms/thumb/'.$pic3name, 'width'=>150));?> <?php echo form_upload(array('name'=>'pic3'));?> </div></div>
<div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit');?></div></div>
<?php echo form_close();?>













