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
<div id="listContainer">
<?php if( isset($regiondetail) && (count($regiondetail)>0)){?>
	<?php $image_url = $prefix . "/".$regiondetail['image'];?>
        <h1><?php echo $regiondetail['title'];?><?php if($user_data['role'] != 'copywriter') {?><span style="float:right;"> <?= anchor("$controller/editregion/".$regionid, "Edit");?></span><?php } ?></h1>
	<table class="table table-striped hotelsTable">
		<tbody>
			<tr><th>Title</th><td><?php echo $regiondetail['title'];?></td></tr>
			<tr><th>Description</th><td><?php echo $regiondetail['description'];?></td></tr>
                        <tr><th>H3</th><td><?php echo $regiondetail['h3'];?></td></tr>
                        <tr><th>P3</th><td><?php echo $regiondetail['p3'];?></td></tr>
                        <tr><th>H4</th><td><?php echo $regiondetail['h4'];?></td></tr>
                        <tr><th>P4</th><td><?php echo $regiondetail['p4'];?></td></tr>
			<tr><th>Image</th><td><img height="120px" src="<?php echo $image_url;?>"></td></tr>
		</tbody>
	</table>
        <?php if($user_data['role'] != 'copywriter') {?>
        <h1>French<span style="float:right;"> <?= anchor("$controller/lang_regionedit/fr/".$regionid, "Edit");?></span></h1>
	<table class="table table-striped hotelsTable">
		<tbody>
			<tr><th>Title</th><td><?php echo $result['fr']['title'];?></td></tr>
			<tr><th>Description</th><td><?php echo $result['fr']['description'];?></td></tr>
            <tr><th>H3</th><td><?php echo $result['fr']['h3'];?></td></tr>
            <tr><th>P3</th><td><?php echo $result['fr']['p3'];?></td></tr>
            <tr><th>H4</th><td><?php echo $result['fr']['h4'];?></td></tr>
            <tr><th>H4</th><td><?php echo $result['fr']['p4'];?></td></tr>
		</tbody>
	</table>

        <h1>Hindu<span style="float:right;"> <?= anchor("$controller/lang_regionedit/in/".$regionid, "Edit");?></span></h1>
	<table class="table table-striped hotelsTable">
		<tbody>
			<tr><th>Title</th><td><?php echo $result['in']['title'];?></td></tr>
			<tr><th>Description</th><td><?php echo $result['in']['description'];?></td></tr>
                        <tr><th>H3</th><td><?php echo $result['in']['h3'];?></td></tr>
                        <tr><th>P3</th><td><?php echo $result['in']['p3'];?></td></tr>
                        <tr><th>H4</th><td><?php echo $result['in']['h4'];?></td></tr>
                        <tr><th>P4</th><td><?php echo $result['in']['p4'];?></td></tr>
		</tbody>
	</table>

        <h1>Chinese<span style="float:right;"> <?= anchor("$controller/lang_regionedit/cn/".$regionid, "Edit");?></span></h1>
	<table class="table table-striped hotelsTable">
		<tbody>
			<tr><th>Title</th><td><?php echo $result['cn']['title'];?></td></tr>
			<tr><th>Description</th><td><?php echo $result['cn']['description'];?></td></tr>
                        <tr><th>H3</th><td><?php echo $result['cn']['h3'];?></td></tr>
                        <tr><th>P3</th><td><?php echo $result['cn']['p3'];?></td></tr>
                        <tr><th>H4</th><td><?php echo $result['cn']['h4'];?></td></tr>
                        <tr><th>P4</th><td><?php echo $result['cn']['p4'];?></td></tr>
		</tbody>
	</table>
        <?php } ?>

	<input type="hidden" name="type" value="tregion" id="ttype">
<?php } ?>

<?php $this->load->view('thingstodo/ajax_list');?>
</div>