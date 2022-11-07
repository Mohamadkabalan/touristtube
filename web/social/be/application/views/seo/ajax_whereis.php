<?php $controller = $this->router->class ?>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<table class="table">
<tr>
<th>#</th>
<th>Title</th>
<th>Link</th>
<th>Name</th>
<th>Type</th>
<th>Description</th>
<th></th>
<th></th>
</tr>
<?php foreach($items as $item){ 
    switch($item->entity_type){
        case SOCIAL_ENTITY_COUNTRY:
            $type = "Country";
            break;
        case SOCIAL_ENTITY_STATE:
            $type  = "State";
            break;
        case SOCIAL_ENTITY_CITY:
            $type = "City";
            break;
    }
    ?>
<tr>
<td><?= $item->id;?></td>
<td><?= $item->title;?></td> 
<td><?= $item->link;?></td> 
<td><?= $item->name;?></td>    
<td><?= $type;?></td>
<td><?= $item->description;?></td>
<td><?= anchor("$controller/whereis_edit/".$item->id, "Edit");?></td>
<td><?= anchor("$controller/delete_whereis/".$item->id, "Delete", array('class'=>'deleteAct'));?></td>
</tr>
<?php  } ?>
</table>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>