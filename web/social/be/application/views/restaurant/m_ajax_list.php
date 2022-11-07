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
<th></th>
<th></th>
</tr>
<?php foreach($restaurants as $restaurant){ ?>
<tr>
<td><?= $restaurant['id'];?></td>    
<td><?= $restaurant['name'];?></td>    
<td><?= $restaurant['country'];?></td>    
<td><?= $restaurant['city'];?></td>
<td><?= $restaurant['loc']['lat'];?></td>    
<td><?= $restaurant['loc']['lon'];?></td>
<td><?= anchor("$controller/view/".$restaurant['id'], "Detail");?></td>    
<td><?= anchor("$controller/ajax_delete/".$restaurant['id'], "Delete", array('class'=>'deleteAct'));?></td>    
</tr> 
<?php  } ?>
</table>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>