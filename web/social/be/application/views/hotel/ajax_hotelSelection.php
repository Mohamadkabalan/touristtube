<?php $controller = $this->router->class ?>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<table class="table">
<tr>
<th>#</th>
<th>City</th>
<th>Count</th>
<th>Image</th>
<th>Selection Type</th>
<th>Status</th>
<th></th>
<th></th>
</tr>
<?php foreach($aitems as $alias){ ?>
<tr>
<td><?= $alias['id'];?></td>
<td><?= $alias['city_name'];?></td>    
<td><?= $alias['count'];?></td>
<td><img height="120px" src="<?= '/media/images/selection/'.$alias['img'];?>"></td>
<td><?= ($alias['selection_type']==1?'small':'big');?></td>
<td><?= ($alias['published']==1?'published':'unpublished');?></td>
<td><?= anchor("$controller/edithselection/".$alias['id'], "Edit");?></td>
<td><?= anchor("$controller/ajaxhsel_delete/".$alias['id'], "Delete", array('class'=>'deleteAct'));?></td>
</tr>
<?php  } ?>
</table>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>