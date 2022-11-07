<?php $controller = $this->router->class;
    function photoReturnLink($photoInfo, $size = ''){
        $relativepath = $photoInfo->relativepath;
        $relativepath = str_replace('/', '', $relativepath);
        $fullPath = $photoInfo->fullpath.(!empty($size) ? $size.'_' : '').$photoInfo->name;
        $fullPath_exist = '../'.$photoInfo->fullpath.''.(!empty($size) ? $size.'_' : '').$photoInfo->name;

        if(!file_exists($fullPath_exist))
            return '/media/images/unavailable-preview.gif';
        return '../'.$fullPath;
    }
    
    function videoReturnLink($videoInfo, $size = ''){
        $mediaPath = '../'. $videoInfo->fullpath.'';
        $videoCode = $videoInfo->code;
        $thumbs = glob($mediaPath ."_*_". $videoCode . "_*.jpg");
        if ( $thumbs && count($thumbs) > 0 ){
            $path_parts = pathinfo($thumbs[0]);
            $filename = $path_parts['filename'];
            $relativepath = $videoInfo->relativepath;
            $relativepath = str_replace('/', '', $relativepath);
            $fullPath = $videoInfo->fullpath.(!empty($size) ? $size.'_' : '').$filename.'.jpg';
            return '../'.$fullPath;
        }
        else{
            return '/media/images/unavailable-preview.gif';
        }
    }
?>
<?php if(isset($links)){ ?>
<p class="linksContainer"><?php echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<?php } ?>
<table class="table">
<tr>
<th>#</th>
<th></th>
<th>Title</th>
<th>Description</th>
<?php if($accepted == FALSE){ ?>
<th></th>
<th></th>
<?php } ?>
</tr>
<?php foreach($media as $media_item){ ?>
<tr>
<td><?= $media_item->id;?></td>    
<td><a target="_blank" href="../<?php if($media_item->image_video == 'i') {echo 'photo/'.$media_item->hash_id;} else {echo 'video/'.$media_item->hash_id;} ?>"><img style="height: 96px;" src="<?php if($media_item->image_video == 'i') {echo photoReturnLink($media_item, 'thumb');} else {echo videoReturnLink($media_item, 'thumb');}?>"></a></td>
<td><?= $media_item->title;?></td>   
<td><?= $media_item->description;?></td>   
<?php if($accepted == FALSE){ ?>
<td><?= anchor("$controller/ajax_accept/".$media_item->id, "Accept", array('class'=>'acceptAct'));?></td>
<td><?= anchor("$controller/michel_accept/".$media_item->id, "Michel", array('class'=>'acceptMAct'));?></td>
<td><?= anchor("$controller/ajax_delete/".$media_item->id, "Delete", array('class'=>'deleteAct'));?></td>
<?php } ?>
</tr> 
<?php  } ?>
</table>
<?php if(isset($links)){ ?>
<p class="linksContainer"><?php echo $links; ?></p>
<?php } ?>