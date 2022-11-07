<?php $controller = $this->router->class;
    function photoReturnLink($photoInfo, $size = ''){
        $relativepath = $photoInfo['relativepath'];
        $relativepath = str_replace('/', '', $relativepath);
        $fullPath = $photoInfo['fullpath'].(!empty($size) ? $size.'_' : '').$photoInfo['name'];
        $fullPath_exist = '../'.$photoInfo['fullpath'].''.(!empty($size) ? $size.'_' : '').$photoInfo['name'];

        if(!file_exists($fullPath_exist))
            $fullPath = '/media/images/unavailable-preview.gif';
        return '../'.$fullPath;
    }
    
    function videoReturnLink($videoInfo, $size = ''){
        $mediaPath = '../'. $videoInfo ['fullpath'].'';
        $videoCode = $videoInfo[ 'code' ];
        $thumbs = glob(  $mediaPath . $videoCode . "*.jpg" );
        if ( $thumbs && count($thumbs) > 0 ){
            $path_parts = pathinfo($thumbs[0]);
            $filename = $path_parts['filename'];
            $relativepath = $videoInfo['relativepath'];
            $relativepath = str_replace('/', '', $relativepath);
            $fullPath = $videoInfo ['fullpath'].(!empty($size) ? $size.'_' : '').$filename.'.jpg';
            return '../'.$fullPath;
        }
        else{
            return '/media/images/unavailable-preview.gif';
        }
    }
?>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<table class="table">
<tr>
<th>#</th>
<th></th>
<th>User</th>
<th>Last updated</th>
<th>Title</th>
<th>Country</th>
<th>City</th>
<th>Description</th>
<th>Translated to</th>
<th></th>
</tr>
<?php foreach($media as $item){ 
    $trans = $item->ml_videos->all_to_array();
    $arr_item = $item->to_array();
    $translated_to = '';
    $title = $item->title;
    $country = $item->country;
    $cityname = $item->cityname;
    $description = $item->description;
    foreach($trans as $lang){
        if($translated_to != '')
            $translated_to .= ', ';
        $translated_to .= $lang['lang_code'];
        if($lang['lang_code'] == 'en'){
            $title = $lang['title'];
            $description = $lang['description'];
        }
    }
    
    ?>
<tr>
<td><?= $item->id;?></td>    
<!--<td><img style="width: 48px;height: 48px;" src="../pmedia/<?php echo $item->image_video."/".$item->name ?>" ></td>-->
<td><img style="width: 48px;height: 48px;" src="<?php if($arr_item['image_video'] == 'i') {echo photoReturnLink($arr_item, 'small');} else {echo videoReturnLink($arr_item, 'small');}?>"></td>
<td><?= $item->userid;?></td>
<td><?= substr($item->last_modified, 0, 10);?></td>
<td width="150"><?= $title;?></td>
<td><?= $country ?></td>
<td><?= $cityname ?></td>
<td width="300"><?= $description;?></td>
<td><?= $translated_to;?></td>
<td><?= anchor("$controller/edit/".$item->id, 'Edit');?></td>  
</tr> 
<?php  } ?>
</table>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>