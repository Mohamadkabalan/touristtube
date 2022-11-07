<?php
$controller = $this->router->class;
if(isset($destination)){
    $id = $destination['id'];
    $name = $destination['name'];
    $latitude = $destination['latitude'];
    $longitude = $destination['longitude'];
    $country_code = $destination['country_code'];
    $deal_id = $destination['deal_id'];
}
?>
<div class="title"><?=(isset($title) ? $title : '')?></div>
<br/>
<?php echo form_open("$controller/destination_submit"); ?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('deal_id', $deal_id);?>
<div>
    <div class="edit-sect-main">
        <label><em class="require">*</em>destination</label>
        <?php echo form_input(array('name'=>'name', 'id' => 'txtName', 'value' => !empty($name) ? $name : set_value('name')));?>
         <?php echo form_error('name');?>
    </div>
    <div class="edit-sect-main">
        <label>latitude</label>
        <?php echo form_input(array('name'=>'latitude', 'value' => !empty($latitude) ?  $latitude : set_value('latitude')));?>
    </div>
    
    <div class="edit-sect-main">
        <label>longitude</label>
        <?php echo form_input(array('name'=>'longitude', 'value' => !empty($longitude) ?  $longitude : set_value('longitude')));?>
    </div>
    <div class="edit-sect-main" style="width:50%;">
         <label><em class="require">*</em>Country</label>
       <?php echo form_input(array('name'=>'country_code', 'value' => !empty($country_code) ?  $country_code : set_value('country_code')));?>
        <?php echo form_error('country_code');?>
    </div>
<button type="submit" id="btnFilter">&raquo; Click to Submit</button>
</div>
<?php echo form_close();?>