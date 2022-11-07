<?php $controller = $this->router->class;
$user_data = $this->session->userdata('logged_in');
?>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<table class="table">
<tr>
<th>#</th>
<th>Name</th>
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
<td><?= $hotel->name;?></td>
<td><?= $hotel->city?></td>
<td><?= $hotel->latitude;?></td>    
<td><?= $hotel->longitude;?></td>
<td><?= $hotel->stars;?></td>    
<td><?= anchor("$controller/view/".$hotel->id, 'Detail');?></td>    
</tr> 
<?php  } ?>
</table>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>