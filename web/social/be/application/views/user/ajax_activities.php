<?php $controller = $this->router->class ?>
<table class="table">
<tr>
<th>Username</th>
<th>Logins</th>
<th>Logouts</th>
<th>Hotels Activities</th>
<th>Restaurants Activities</th>
<th>POIs Activities</th>
<th>Total Activities</th>
</tr>
<?php foreach($activities as $activity){ ?>
<tr>
<td><?= $activity->username;?></td>    
<td><?= $activity->logins;?></td>
<td><?= $activity->logouts;?></td>
<td>Add: <?= $activity->hotel_insert_count;?>, Edit: <?= $activity->hotel_update_count;?>, Delete: <?= $activity->hotel_delete_count;?></td>
<td>Add: <?= $activity->restaurant_insert_count;?>, Edit: <?= $activity->restaurant_update_count;?>, Delete: <?= $activity->restaurant_delete_count;?></td>
<td>Add: <?= $activity->poi_insert_count;?>, Edit: <?= $activity->poi_update_count;?>, Delete: <?= $activity->poi_delete_count;?></td>
<td><?= ($activity->hotel_insert_count + $activity->hotel_update_count + $activity->hotel_delete_count + 
        $activity->restaurant_insert_count + $activity->restaurant_update_count + $activity->restaurant_delete_count + 
        $activity->poi_insert_count + $activity->poi_update_count + $activity->poi_delete_count);?></td>
<td><?= anchor("$controller/useractivitydetails/".$activity->userid, 'Detail');?></td> 		
</tr> 
<?php  } ?>
</table>