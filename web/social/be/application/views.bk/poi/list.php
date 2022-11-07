<h1><?= $title?>   </h1> 
<br /><select id="poi_cc" name="rest_cc" class="input-xlarge">
    <option value="all">--ALL--</option>
    <?php foreach($countries as $c){?><option <?php if($c->country == $cc) echo 'selected="selected"'?> value="<?php echo $c->country?>"><?php echo $c->country?> ( <?php echo $c->num?>)</option><?php }?>
</select>
<br />
<p><?php echo $links; ?></p>
<table class="table">
<tr>
<th>#</th>
<th>Name</th>
<th>Counry</th>
<th>City</th>
<th>Latitude</th>
<th>Longitude</th>
<th></th>
<th></th>
</tr>
<?php foreach($pois as $poi){ ?>
<tr>
<td><?= $poi->id;?></td>    
<td><?= $poi->name;?></td>    
<td><?= $poi->country;?></td>    
<td><?= $poi->latitude;?></td>    
<td><?= $poi->longitude;?></td>
<td><?= anchor("poi/view/".$poi->id, "Detail");?></td>    
<td><?= anchor("poi/delete/".$poi->id, "Delete", array('class'=>'deleteAct'));?></td>    
</tr> 
<?php  } ?>
</table>
<p><?php echo $links; ?></p>