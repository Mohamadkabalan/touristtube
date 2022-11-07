<?php 
$controller = $this->router->class;
$user_data = $this->session->userdata('logged_in');

if( $this->config->item('upload_src') == "s3" ){
    $prefix = 'https://' . $this->config->item('bucketName').$this->config->item('subdomain_suffix') . ".". S3::$endpoint;
}
else{
    $prefix = '';
}
?>
<h1><?= $title?>   </h1>
<br />
<div id="listContainer">
	<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
	<table class="table">
		<tr>
			<th>#</th>
			<th>Title</th>
			<th>Description</th>
			<th>Image</th>
			<th></th>
			<th></th>
		</tr>
		<?php foreach($thingstodo_region as $item){
			$image_url = $prefix . "/".$item['image'];
			?>
			<tr>
				<td><?= $item['id'];?></td>			
				<td><?= $item['title'];?></td>    
				<td><?= $item['description'];?></td>
				<td><img height="120px" src="<?= $image_url;?>"></td> 
				<td><?= anchor("$controller/thingstodoplace/".$item['id'], 'View');?></td>    
				<td><?= $user_data['role'] != 'copywriter' ? anchor("$controller/ajax_regiondelete/".$item['id'], "Delete", array('class'=>'deleteAct')) : '';?></td>    
			</tr> 
		<?php  } ?>
	</table>
	<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>
</div>