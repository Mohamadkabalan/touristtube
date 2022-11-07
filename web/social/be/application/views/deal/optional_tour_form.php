<?php
$controller = $this->router->class;
if(isset($optional_tour)){
    $id = $optional_tour['id'];
    $name = $optional_tour['name'];
    $price = $optional_tour['price'];
    $currency_id = $optional_tour['currency_id'];
    $seats_left = $optional_tour['seats_left'];
    $n_persons = $optional_tour['n_persons'];
    $discount = $optional_tour['discount'];
    $date_id = $optional_tour['date_id'];
    $destination_id = $optional_tour['destination_id'];
    $date = date('m/d/Y H:i', strtotime($optional_tour['tour_date']));
}
?>

<div class="title"><?=(isset($title) ? $title : '')?></div>
<br/>
<?php echo form_open_multipart("$controller/optional_tour_submit"); ?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('destination_id', $destination_id);?>
<?php echo form_hidden('date_id', $date_id);?>
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
<div class="row">
    <div class="room">
        <label>optional tour name</label>
        <?php echo form_input(array('name'=>'name', 'value' => $name, 'class' => 'room-name'));?>
        <span class="labelss"><a href="#">upload photo</a></span>
        <img src="" />
    </div>
    <div>
        <label>price</label>
        <?php echo form_input(array('name'=>'price', 'value' => $price));?>
    </div>
    <div class="currency-cont">
        <label>currency</label>
        <?php echo form_input(array('name'=>'currency', 'value' => $currency_id));?>
    </div>
    <div>
        <label>#of persons</label>
        <?php echo form_input(array('name'=>'nPersons', 'value' => $n_persons));?>
    </div>
    <div class="date-cont">
        <label>date</label>
        <?php echo form_input(array('name'=>'date', 'value' => $date, 'class' => 'date-input'));?>
    </div>
    <div>
        <label>discount</label>
        <?php echo form_input(array('name'=>'discount', 'value' => $discount));?>
    </div>
</div>

<button type="submit">&raquo; Click to Submit</button>
<?php echo form_close();?>