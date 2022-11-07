<?php $controller = $this->router->class;
$user_data = $this->session->userdata('logged_in');
?>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<h1><span style="float:right;"> <?= anchor("$controller/hotel_search_add/".$hotel_search['id'], "Add");?></span></h1> 
<table class="table">
<tr>
<th>#</th>
<th>Name</th>
<th>Keyword</th>
<th></th>
<th></th>
</tr>
<?php foreach($hotel_searches as $hotel_search){ ?>
<tr>
<td><?= $hotel_search->id;?></td>    
<td><?= $hotel_search->name;?></td>
<td><?= $hotel_search->keyword?></td>
<td><?= anchor("$controller/hotel_search_view/".$hotel_search->id, 'Detail');?></td>    
<td><?= anchor("$controller/hotel_search_delete/".$hotel_search->id, "Delete", array('class'=>'deleteAct'));?></td>    
</tr> 
<?php  } ?>
</table>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>