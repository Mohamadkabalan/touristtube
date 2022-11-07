<?php $controller = $this->router->class; ?>
<div id="listContainer">
    <table class="table">
    <tr>
    <th>#</th>
    <th>Username</th>
    <th>Activity Type</th>
    <th>Activity ID</th>
    <th>Date</th>
    </tr>
    <?php 
	$i=1;
	if(count($activities) >0){
	foreach($activities as $activity){ ?>
    <tr>
    <td><?= $i;?></td>    
    <td><?= $activity->username;?></td>    
    <td><?= $activity_types[$activity->activity_code];?></td>
     <td><?php if($activity->activity_code>=30 &&  $activity->activity_code<=38 ) {?>
     <?= anchor("ml/edit/".$activity->entity_id, $activity->entity_id, array('target' => '_blank') );?>
         <?php } else echo $activity->entity_id;?></td>    
    <td><?= date("F j, Y, g:i:s a",strtotime($activity->timestamp)); ;?></td>      
    </tr> 
    <?php $i++; } } else { ?>
	
	<tr>
	<td colspan="4">No records found</td>
	</tr>
	<?php }	?>
    </table>
</div>