<?php $controller = $this->router->class ?>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
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
<td><?= $poi['id'];?></td>    
<td><?= $poi['name'];?></td>    
<td><?= $poi['country'];?></td>   
<td><?= $poi['city'];?></td>   
<td><?= $poi['loc']['lat'];?></td>    
<td><?= $poi['loc']['lon'];?></td>
<td><?php if($poi['show_on_map']) echo "Yes"; else echo "No";?></td>
<td><?= anchor("$controller/view/".$poi['id'], "Detail");?></td>    
<td><?= anchor("$controller/ajax_delete/".$poi['id'], "Delete", array('class'=>'deleteAct'));?></td>    
</tr> 
<?php  } ?>
</table>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>