<h1><?= $title?>   </h1>
<br />
<?php $controller = $this->router->class ?>
<p><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
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
<td><?= $hotel->id;?></td>    
<td><?= $hotel->hotelName;?></td>    
<td><?= $hotel->countryCode;?></td>
<td><?= $hotel->countryName;?></td>    
<td><?= $hotel->webgeocity->name?></td>
<td><?= $hotel->latitude;?></td>    
<td><?= $hotel->longitude;?></td>
<td><?= $hotel->stars;?></td>    
<td><?= anchor("$controller/ajax_accept/".$hotel->id, "Accept", array('class'=>'acceptAct'));?></td>    
<td><?= anchor("$controller/view/".$hotel->id, 'Detail');?></td>    
<td><?= anchor("$controller/ajax_delete/".$hotel->id, "Delete", array('class'=>'deleteAct'));?></td>    
</tr> 
<?php  } ?>
</table>
<p><?php if(isset($links)) echo $links; ?></p>
