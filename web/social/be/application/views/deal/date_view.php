<?php 
$controller = $this->router->class;
?>
<div class="title">Date details info</div>
<br/>
<div class="sect-date">
<div class="info-nb">1</div>
    <div class="info-desp">date</div>
    <div class="add-deal"><?= anchor("$controller/date_edit/".$date['id'], 'edit');?></div>
    
<table class="table">
    <tr>
        <th class="formth">from</th>
        <td class="formth"><?php echo date('m/d/Y', strtotime($date['from_date'])); ?></td>
    </tr>
   <tr>
        <th>to</th>
        <td><?php echo date('m/d/Y', strtotime($date['to_date'])); ?></td>
    </tr>
</table>
</div>

<div class="sect-date">
    <div class="info-nb">2</div>
    <div class="info-desp">destinations list</div>
    <table class="table">
        <tr>
            <!--<th>destination’s name </th>-->
        </tr>
    </table>
</div>
<?php foreach($destinations as $destination){?>
<div class="country"><?php echo $destination['destination']['name']; ?></div>
<div class="sect-date hotel-description">
     <div class="boxicon"><img src="/media/images/box-icon.png" /></div>
    <div class="sect-name">hotel's list</div>
     <div class="add-deal"><?= anchor("$controller/hotel_add/".$date['id']."/".$destination['destination']['id'], 'add');?></div>
    <table class="table">
        <tr>
            <th>hotel name </th>
            <th>stars</th>
            <th>pic</th>
            <th></th>
            <th></th>
        </tr>
        <?php foreach($destination['hotels'] as $hotel){ ?>
        <tr>
            <td><?php echo $hotel['name']; ?></td>
            <td><?php echo $hotel['stars']; ?></td>
            <td>X</td>
            <td class="link"><?= anchor("$controller/hotel_view/".$hotel['id'], 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_hotel/".$hotel['id'], "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <?php } ?>
    </table>
    <div class="sect-name">optional tour’s list</div>
     <div class="add-deal"><?= anchor("$controller/optional_tour_add/".$date['id']."/".$destination['destination']['id'], 'add');?></div>
    <table class="table">
        <tr>
            <th>optional tour name </th>
            <th>photo</th>
            <th>price</th>
            <th>currency </th>
            <th>seats left</th>
            <th>#of persons</th>
            <th>date</th>
            <th>time</th>
            <th>discount</th>
            <th></th>
            <th></th>
        </tr>
        <?php foreach($destination['optional_tours'] as $optional_tour) { 
            $c = new Currency_Model();
            $c->where('id', $optional_tour['currency_id'])->get();
            $currency = $c->to_array();
        ?>
        <tr>
            <td><?php echo $optional_tour['name']; ?></td>
            <td>X</td>
            <td><?php echo $optional_tour['price']; ?></td>
            <td><?php echo $currency['name']; ?></td>
            <td><?php echo $optional_tour['seats_left']; ?></td>
            <td><?php echo $optional_tour['n_persons']; ?></td>
            <td><?php echo date('m/d/Y', strtotime($optional_tour['tour_date'])); ?></td>
            <td><?php echo date('H:i', strtotime($optional_tour['tour_date'])); ?></td>
            <td><?php echo $optional_tour['discount']; ?></td>
            <td class="link"><?= anchor("$controller/optional_tour_edit/".$optional_tour['id'], 'Edit');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_optional_tour/".$optional_tour['id'], "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <?php } ?>
    </table>
</div>
<br/>
<br/>
<br/>
<?php } ?>