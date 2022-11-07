<?php $controller = $this->router->class ?>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<table class="table">
<tr>
<th>#</th>
<th>Name</th>
<th>Country Code</th>
<th>Country</th>
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
<td><?= $hotel['countryCode'];?></td>
<td><?= $hotel['countryName'];?></td>    
<td><?= $hotel['cityName']?></td>
<td><?= $hotel['loc']['lat'];?></td>    
<td><?= $hotel['loc']['lon'];?></td>
<td><?= $hotel['stars'];?></td>    
<td><?= anchor("$controller/view/".$hotel['id'], 'Detail');?></td>    
<td><?= anchor("$controller/ajax_delete/".$hotel['id'], "Delete", array('class'=>'deleteAct'));?></td>    
</tr> 
<?php  } ?>
</table>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>