<h1><?= $title?>   </h1> 
<br /><select id="cc" name="cc" class="input-xlarge">
    <option value="all">--ALL--</option>
    <?php foreach($cities as $c){?>
    <option <?php if(strcmp($c,$cc)==0) echo ' selected="selected" '?> value="<?php echo $c?>"><?php echo $c?></option>
    <?php }?>
</select>
<br />
<table class="table">
<tr>
<th>#</th>
<th>Name</th>
<th>Counry</th>
<th>City</th>
<th>Latitude</th>
<th>Longitude</th>
<th>Stars</th>
<th></th>
<th></th>
</tr>
<?php foreach($hotels as $hotel){ ?>
<tr>
<td><?= $hotel['id'];?></td>    
<td><?= $hotel['hotelName'];?></td>    
<td><?= $hotel['countryName'];?></td>    
<td><?= $hotel['cityName'];?></td>
<td><?= $hotel['latitude'];?></td>    
<td><?= $hotel['longitude'];?></td>
<td><?= $hotel['stars'];?></td>    
<td><?= anchor("hotel/view/".$hotel['id'], "Detail");?></td>    
<td><?= anchor("hotel/delete/".$hotel['id'], "Delete", array('class'=>'deleteAct'));?></td>    
</tr> 
<?php  } ?>
</table>