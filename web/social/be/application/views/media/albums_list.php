<?php $controller = $this->router->class;
?>
<?php if(isset($links)){ ?>
<p class="linksContainer"><?php echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<?php } ?>
<table class="table">
<tr>
<th>#</th>
<th>Title</th>
<?php if($accepted == FALSE){ ?>
<th></th>
<th></th>
<?php } ?>
</tr>
<?php foreach($albums as $album){ ?>
<tr>
<td><?= $album->id;?></td>    
<td><?= $album->catalog_name;?></td>   
<?php if($accepted == FALSE){ ?>
<td><?= anchor("$controller/album_accept/".$album->id, "Accept", array('class'=>'acceptAct'));?></td>
<td><?= anchor("$controller/album_delete/".$album->id, "Delete", array('class'=>'deleteAct'));?></td>
<?php } ?>
</tr> 
<?php  } ?>
</table>
<?php if(isset($links)){ ?>
<p class="linksContainer"><?php echo $links; ?></p>
<?php } ?>