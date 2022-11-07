<?php 
$controller = $this->router->class;
?>
<div class="title">Hotel detailed Info</div>
<br/>
<div class="sect-date">
<div class="info-nb">1</div>
    <div class="info-desp">hotel</div>
    <div class="edit-deal"><?= anchor("$controller/hotel_edit/".$hotel['id'], 'edit');?></div>
    <table class="table">
        <tr>
            <th>name of the hotel</th>
            <td><?php echo $hotel['name']; ?></td>
        </tr>
        <tr>
            <th>stars</th>
            <td><?php echo $hotel['stars']; ?></td>
        </tr>
        <tr class="lasttr">
            <th>photo</th>
            <td>X</td>
        </tr>
    </table>
</div>


<div class="sect-date">
    <div class="info-nb">2</div>
    <div class="info-desp">room’s list</div>
     <div class="add-deal"><?= anchor("$controller/room_add/".$hotel['id'], 'add');?></div>
    <table class="table">
        <tr>
            <th>type of room </th>
            <th>photo</th>
            <th>price</th>
            <th>currency </th>
            <th>rooms left</th>
            <th>#of persons</th>
            <th>discount</th>
            <th></th>
            <th></th>
        </tr>
        <?php foreach($rooms as $room) { 
            $pc = new Currency_Model();
            $pc->where('id', $room['currency_id'])->get();
            $price_currency = $pc->name;
        ?>
        <tr>
            <td><?php echo $room['name']; ?></td>
            <td>X</td>
            <td><?php echo $room['price']; ?></td>
            <td><?php echo $price_currency; ?></td>
            <td><?php echo $room['rooms_left']; ?></td>
            <td><?php echo $room['n_persons']; ?></td>
            <td></td>
            <td class="link"><?= anchor("$controller/room_edit/".$room['id'], 'edit');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_room/".$room['id'], "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <?php } ?>
    </table>
</div>
<br/>
<br/>
<br/>
