<h1><?= $title?>  <select id="land_cc" name="cc" class="input-xlarge">
    <option value="all">--ALL--</option>
    <?php foreach($cities as $c){?><option <?php if($c->city == $cc) echo 'selected="selected"'?> value="<?php echo $c->city?>"><?php echo $c->city?>(<?php echo $c->num?>)</option><?php }?>
</select> </h1> 
<br />
<p><?php echo $links; ?></p>
<table class="table">
<tr>
<th>#</th>
<th>Name</th>
<th>City</th>
<th>Latitude</th>
<th>Longitude</th>
<th></th>
<th></th>
</tr>
<?php foreach($landmarks as $landmark){ ?>
<tr>
<td><?= $landmark->id;?></td>    
<td><?= $landmark->name;?></td>    
<td><?= $landmark->city;?></td>    
<td><?= $landmark->F2;?></td>
<td><?= $landmark->F1;?></td>    
<td><?= anchor("landmark/view/".$landmark->id, "Detail");?></td>    
<td><?= anchor("landmark/edit/".$landmark->id, "Edit");?></td>    
</tr> 
<?php  } ?>
</table>
<p><?php echo $links; ?></p>