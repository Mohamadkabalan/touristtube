<?php $controller = $this->router->class ?>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<table class="table">
<tr>
<th>#</th>
<th>Name</th>
<th>Url</th>
<th>Description</th>
<th>Keywords</th>
<th></th>
<th></th>
</tr>
<?php foreach($aitems as $alias){ ?>
<tr>
<td><?= $alias->id;?></td>
<td><?= $alias->title;?></td>    
<td><?= $alias->url;?></td>
<td><?= $alias->description;?></td>
<td><?= $alias->keywords;?></td>
<td><?= anchor("$controller/alias_edit/".$alias->id, "Edit");?></td>
<td><?= anchor("$controller/ajaxalias_delete/".$alias->id, "Delete", array('class'=>'deleteAct'));?></td>
</tr>
<?php  } ?>
</table>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>