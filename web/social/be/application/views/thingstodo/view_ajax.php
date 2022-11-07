<?php
$user_data = $this->session->userdata('logged_in');
$controller = $this->router->class;
if( $this->config->item('upload_src') == "s3" ){
	$prefix_list = 'https://' . $this->config->item('bucketName').$this->config->item('subdomain_suffix') . ".". S3::$endpoint.'/media/thingstodo';
}
else{
	$prefix_list = '/media/thingstodo';
}
$id = isset($thingstodo)?$thingstodo['id']:0;
?>
<table class="table table-striped">
	<tr><th>Order</th><th>Id</th><th>Title</th><th>Description</th><th>Has Image</th><th>Image</th><th></th><th></th></tr>
	<?php foreach($items as $item){
		$image_url = $prefix_list . "/".$item['image'];

		?>
		<tr>
			<td><?= $item['order_display'];?></td>
			<td><?= $item['id'];?></td>
			<td><?= $item['title'];?></td>
			<td><?= $item['description'];?></td>
			<td><?php if($item['image']<>'') echo "Yes"; else echo "No"; ?></td>
			<td><?php if($item['image']<>'') echo  img(array('src'=>$image_url, 'height'=>120));?></td>
			<td><?= anchor("$controller/item_edit/".$item['id']."/".$id, "Edit");?></td>
			<td><?= anchor("$controller/division_add/".$item['id'], "Add Division");?></td>
			<td><?= $user_data['role'] != 'copywriter' ? anchor("$controller/item_delete/".$item['id']."/".$id, "Delete", array('class'=>'deleteAct')) : '';?></td>
		</tr>
	<?php  } ?>
</table>