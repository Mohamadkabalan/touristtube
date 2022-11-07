<?php
$controller = $this->router->class;
if(isset($room)){
    $id = $room['id'];
    $name = $room['name'];
    $price = $room['price'];
    $currency_id = $room['currency_id'];
    $rooms_left = $room['rooms_left'];
    $n_persons = $room['n_persons'];
    $hotel_id = $room['hotel_id'];
//    $date_id = $room['date_id'];
//    $destination_id = $room['destination_id'];
}
?>
<div class="title"><?=(isset($title) ? $title : '')?></div>
<br/>
<?php echo form_open_multipart("$controller/room_submit"); ?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('hotel_id', $hotel_id);?>
<table>
    <tr class="date">
        <th>from</th>
        <th>to</th>
    </tr>
    <tr class="tour_info">
        <td><span class="date"><?php echo date('m/d/Y', strtotime($date['from_date'])); ?></span><span><img src="/media/images/calender-icon.png" /></span></td>
         <td><span class="date"><?php echo date('m/d/Y', strtotime($date['to_date'])); ?></span><span><img src="/media/images/calender-icon.png" /></span></td>
    </tr>
</table>
<div>
    <div class="country-info">
        <div class="country-name"><?php echo $destination['name'];?></div>
    </div>
    <div class="room-info">
      <table class="table">
        <tr>
            <th>name of the hotel</th>
            <td><?php echo $hotel['name'];?></td>
        </tr>
        <tr>
            <th>stars</th>
            <td><?php echo $hotel['stars'];?></td>
        </tr>
        <tr class="lasttr">
            <th>photo</th>
            <td><img src="" /></td>
        </tr>
    </table>
</div>
</div>
<div class="row">
    <div class="room">
        <label>room name</label>
        <?php echo form_input(array('name'=>'name', 'value' => $name, 'class' => 'room-name'));?>
        <span class="labelss"><a href="#">upload photo</a></span>
        <img src="" />
    </div>
    <div class="room-price">
        <label>price</label>
        <?php echo form_input(array('name'=>'price', 'value' => $price, 'class' => 'price'));?>
    </div>
    <div class="currency-cont">
        <label>currency</label>
        <?php echo form_input(array('name'=>'currency', 'value' => $currency_id));?>
    </div>
    <div>
        <label>availability</label>
        <?php echo form_input(array('name'=>'availability', 'value' => '', 'class' => 'available'));?>
    </div>
    <div>
        <label>#of persons</label>
        <?php echo form_input(array('name'=>'nPersons', 'value' => $n_persons, 'class' => 'person'));?>
    </div>
    <div>
        <label>discount</label>
        <?php echo form_input(array('name'=>'discount', 'value' => $discount, 'class' => 'discount'));?>
    </div>
</div>

<button type="submit">&raquo; Click to Submit</button>
<?php echo form_close();?>