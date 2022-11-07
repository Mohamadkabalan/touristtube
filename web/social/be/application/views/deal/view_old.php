<?php 
$controller = $this->router->class;
?>
<div class="title">More info</div>
<br/>

<div>
    <div class="info-nb">1</div>
    <div class="info-desp">deal</div>
    <div class="edit-deal"><a href="<?php echo "$controller/edit/".$deal['id']; ?>">edit</a></div>
</div>
<br/>
<table class="table first">
<tr>
<th>title</th>
<td><?php echo $deal['name']; ?></td>
</tr>
<tr>
<th>subtitle</th>    
<td><?php echo $deal['subtitle']; ?></td>
</tr> 
<tr>
<th>start date</th>    
<td><?php echo date('m/d/Y', strtotime($deal['tour_from_date'])); ?></td>
</tr> 
<tr>
<th>end date</th>    
<td><?php echo date('m/d/Y', strtotime($deal['tour_to_date'])); ?></td>
</tr> 
<tr>
<th>number of days</th>    
<td><?php echo $deal['n_days']; ?></td>
</tr> 
<tr> 
<th>tour route</th>    
<td><?php echo $deal['tour_route']; ?></td>
</tr> 
<tr> 
<th>price</th>    
<td><?php echo $deal['price']; ?></td>
</tr> 
<tr> 
<th>currency</th>    
<td><?php echo $currency; ?></td>
</tr> 
<tr>
<th>Summary title</th>    
<td><?php echo $deal['summary_title']; ?></td>
</tr> 
<tr>
<th>summary description</th>    
<td><?php echo nl2br($deal['summary']); ?></td>
</tr> 
<tr>
<th>terms and conditions</th>    
<td><?php echo nl2br($deal['terms_conditions']); ?></td>
</tr> 
<tr>
<th>optional terms and conditions</th>    
<td><?php echo nl2br($deal['optional_terms_conditions']); ?></td>
</tr> 
<tr>
<th>this deal includes</th>    
<td><?php echo nl2br($deal['deal_includes']); ?></td>
</tr> 
<tr class='last'>
<th>this deal doesn’t include</th>    
<td><?php echo nl2br($deal['deal_not_include']); ?></td>
</tr> 
                                      
</table>
<div class="deal-sect">
    <div class="info-nb">2</div>
    <div class="info-desp">destinations list</div>
    <div class="add-deal"><?= anchor("$controller/destination_add/".$deal['id'], 'add');?></div>
</div>
<table class="table">
<tr>
<th>destination's name</th>
<th>latitude</th>
<th>longitude</th>
<th></th>
</tr>
<?php foreach($destinations as $destination){?>
<tr>
<td><?php echo $destination['name'];?></td>    
<td><?php echo $destination['latitude'];?></td>
<td><?php echo $destination['longitude'];?></td>
<td class="link"><?= anchor("$controller/destination_edit/".$destination['id'], 'Edit');?></td>    
<td class="link"><?= anchor("$controller/ajax_delete_destination/".$destination['id'], "Delete", array('class'=>'deleteAct'));?></td> 
</tr>
<?php } ?>
</table>

<div class="deal-sect">
    <div class="info-nb">3</div>
    <div class="info-desp">days</div>
    <div class="add-deal"><?= anchor("$controller/day_add/".$deal['id'], 'add');?></div>
</div>
<table class="table">
<tr>
<th>day</th>
<th>title</th>
<th>description</th>
<th>pic 1</th>
<th>pic 2</th>
<th>pic 3</th>
<th>pic 4</th>
<th>pic 5</th>
<th>pic 6</th>
<th></th>
<th></th>
</tr>
<?php foreach($days as $day){ ?>
<tr>
<td><?php echo $day['name'];?></td>    
<td><?php echo $day['title'];?></td>
<td><?php echo $day['description'];?></td>
<td>X</td>
<td>X</td>
<td>X</td>
<td>X</td>
<td>X</td>
<td>X</td>
<td class="link"><?= anchor("$controller/day_edit/".$day['id'], 'Edit');?></td>    
<td class="link"><?= anchor("$controller/ajax_delete_day/".$day['id'], "Delete", array('class'=>'deleteAct'));?></td> 
</tr>
<?php } ?>
</table>

<div class="deal-sect">
    <div class="info-nb">4</div>
    <div class="info-desp">dates</div>
    <div class="add-deal"><?= anchor("$controller/date_add/".$deal['id'], 'add');?></div>
</div>
<table class="table">
<tr>
<th>from</th>
<th>to</th>
<th></th>
<th></th>
</tr>
<?php foreach($dates as $date){?>
<tr>
<td><?php echo date('l d F Y', strtotime($date['from_date']));?></td>    
<td><?php echo date('l d F Y', strtotime($date['to_date']));?></td>
<td class="link"><?= anchor("$controller/date_view/".$date['id'], 'Detail');?></td>    
<td class="link"><?= anchor("$controller/ajax_delete_date/".$date['id'], "Delete", array('class'=>'deleteAct'));?></td> 
</tr>
<?php } ?>
</table>

