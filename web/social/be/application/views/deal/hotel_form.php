<?php
$controller = $this->router->class;
if(isset($hotel)){
    $id = $hotel['id'];
    $name = $hotel['name'];
    $stars = $hotel['stars'];
    $destination_id = $hotel['destination_id'];
    $date_id = $hotel['date_id'];
}
?>
<div class="title"><?=(isset($title) ? $title : '')?></div>
<br/>
<?php echo form_open_multipart("$controller/hotel_submit"); ?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('destination_id', $destination_id);?>
<?php echo form_hidden('date_id', $date_id);?>
<table>
    <tr class="date">
        <th>from</th>
        <th>to</th>
    </tr>
    <tr class="tour_info">
<!--        <td><input value="<?php echo date('m/d/Y', strtotime($date['from_date'])); ?>"><span><img src="/media/images/calender-icon.png" /></span></td>
         <td><input value="<?php echo date('m/d/Y', strtotime($date['to_date'])); ?>"><span><img src="/media/images/calender-icon.png" /></span></td>-->
        <td><span class="date"><?php echo date('m/d/Y', strtotime($date['from_date'])); ?></span><span><img src="/media/images/calender-icon.png" /></span></td>
         <td><span class="date"><?php echo date('m/d/Y', strtotime($date['to_date'])); ?></span><span><img src="/media/images/calender-icon.png" /></span></td>
    </tr>
</table>
<div>
    <div class="country-info">
        <div class="country-name"><?php echo $destination['name'];?></div>
    </div>
</div>
<div class="row">
                <div class="room">
                    <label>hotel name</label>
                    <?php echo form_input(array('name'=>'name', 'value' => $name, 'class' => 'room-name'));?>
                    <span class="labelss"><a href="#">upload photo</a></span>
                    <img src="" />
                </div>
                <div>
                    <label>stars</label>
                    <?php echo form_input(array('name'=>'stars', 'value' => $stars, 'class' => 'price'));?>
                </div>
            </div>

<button type="submit">&raquo; Click to Submit</button>
<?php echo form_close();?>