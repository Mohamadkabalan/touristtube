<?php $controller = $this->router->class ?>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<table class="table">
<tr>
<th>#</th>
<th>Name</th>
<th></th>
<th></th>
</tr>
<?php foreach($deals as $deal){ ?>
<tr>
<td><?= $deal->id;?></td>    
<td><?= $deal->name;?></td>
<td><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
<td><?= anchor("$controller/ajax_delete/".$deal->id, "Delete", array('class'=>'deleteAct'));?></td>  
<td><?= anchor("$controller/add_duplicate/".$deal->id, 'Duplicate', array('class'=>'duplicateAct'));?></td> 
<td><?= anchor("$controller/view/".$deal->id, 'Preview');?></td> 
</tr> 
<?php  } ?>
</table>