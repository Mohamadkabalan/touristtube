<?php $controller = $this->router->class; 
$user_data = $this->session->userdata('logged_in');
$id = $regionid;

if( $this->config->item('upload_src') == "s3" ){
	$prefix_list = 'https://' . $this->config->item('bucketName').$this->config->item('subdomain_suffix') . ".". S3::$endpoint.'/media/thingstodo';
}
else{
	$prefix_list = '/media/thingstodo';
}

?>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<h1> Items    <?php if($user_data['role'] != 'copywriter') {?> <span style="float:right;"> <?= anchor("$controller/country_add/$id", "Add");?></span><?php } ?></h1> 
<table class="table">
<tr>
<th>#</th>
<th>Order</th>
<th>Title</th>
<th>Description</th>
<th>Image</th>
<th></th>
<th></th>
</tr>
<?php foreach($thingstodo as $item){ 
$image_url = $prefix_list . "/".$item->image;
	?>
<tr>
<td><?= $item->id;?></td>   
<td><?= $item->order_display;?></td>
<td><?= $item->title;?></td>    
<td><?= $item->description;?></td>
<td><img height="120px" src="<?= $image_url;?>"></td> 
<td><?= anchor("$controller/country_view/".$item->id, 'View');?></td>    
<td><?= $user_data['role'] != 'copywriter' ? anchor("$controller/ajax_delete_country/".$item->id, "Delete", array('class'=>'deleteAct')) : '';?></td>    
</tr> 
<?php  } ?>
</table>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>