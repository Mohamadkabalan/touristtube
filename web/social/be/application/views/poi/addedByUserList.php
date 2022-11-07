<h1><?= $title?>   </h1>
<br />

<?php $controller = $this->router->class ?>
<p><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<table class="table">
<tr>
<th>#</th>
<th>Name</th>
<th>Country</th>
<th>City</th>
<th>Latitude</th>
<th>Longitude</th>
<th>Show on map</th>
<th></th>
<th></th>
</tr>
<?php foreach($pois as $poi){ ?>
<tr>
<td><?= $poi->id;?></td>    
<td><?= $poi->name;?></td>    
<td><?= $poi->country;?></td>   
<td><?= $poi->webgeocity->name;?></td>   
<td><?= $poi->latitude;?></td>    
<td><?= $poi->longitude;?></td>
<td><?php if($poi->show_on_map) echo "Yes"; else echo "No";?></td>
<td><?= anchor("$controller/ajax_accept/".$poi->id,"Accept", array('class'=>'acceptAct'));?></td>
<td><?= anchor("$controller/view/".$poi->id, "Detail");?></td>    
<td><?= anchor("$controller/ajax_delete/".$poi->id, "Delete", array('class'=>'deleteAct'));?></td>    
</tr> 
<?php  } ?>
</table>
<p><?php if(isset($links)) echo $links; ?></p>