<h1><?= $title?>   </h1> 
<br /><select id="rest_cc" name="rest_cc" class="input-xlarge">
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
<?php foreach($restaurants as $restaurant){ ?>
<tr>
<td><?= $restaurant->id;?></td>    
<td><?= $restaurant->name;?></td>    
<td><?= $restaurant->country;?></td>    
<td><?= $restaurant->city;?></td>
<td><?= $restaurant->latitude;?></td>    
<td><?= $restaurant->longitude;?></td>
<td><?= anchor("restaurant/view/".$restaurant->id, "Detail");?></td>    
<td><?= anchor("restaurant/delete/".$restaurant->id, "Delete", array('class'=>'deleteAct'));?></td>    
</tr> 
<?php  } ?>
</table>
<p><?php echo $links; ?></p>