<?php
$user_data = $this->session->userdata('logged_in');
$controller = $this->router->class;


if( $this->config->item('upload_src') == "s3" ){
	$prefix = 'https://' . $this->config->item('bucketName').$this->config->item('subdomain_suffix') . ".". S3::$endpoint.'/media/thingstodo';
}
else{
	$prefix = '/media/thingstodo';
}
?>

<div style=" float: left; width: 100%; min-height: 1000px;">
    <?php if(isset($thingstodo) && count($thingstodo)>0){?>
	<?php
	$id = $thingstodo['id'];
	$title = $thingstodo['title'];
	$h3 = $thingstodo['h3'];
	$h4 = $thingstodo['h4'];
	$p3 = $thingstodo['p3'];
	$p4 = $thingstodo['p4'];
	$desc_thingstodo = $thingstodo['desc_thingstodo'];
	$desc_discover = $thingstodo['desc_discover'];
	$desc_hotelsin = $thingstodo['desc_hotelsin'];
	$description = $thingstodo['description'];
	$order_display = $thingstodo['order_display'];
	$language = isset($thingstodo['language'])?$thingstodo['language']:'';
	?>
    <h1><?= $title?>    <span style="float:right;"> <?= anchor("$controller/edit/".$id, "Edit");?></span></h1>
 <table class="table table-striped hotelsTable">
     <tbody>
        <tr><th>Order</th><td><?= $order_display?></td></tr>
        <tr><th>Title</th><td><?= $title?></td></tr>
        <tr><th>Description</th><td><?= $description?></td></tr>
        <tr><th>H3</th><td><?= $h3?></td></tr>
        <tr><th>P3</th><td><?= $p3?></td></tr>
        <tr><th>H4</th><td><?= $h4?></td></tr>
        <tr><th>P4</th><td><?= $p4?></td></tr>
        <tr><th>Things To Do Keywords</th><td><?= $desc_thingstodo?></td></tr>
        <tr><th>Discover Keywords</th><td><?= $desc_discover?></td></tr>
        <tr><th>Hotels In Keywords</th><td><?= $desc_hotelsin?></td></tr>
        <tr><th>Language</th><td><?= $language?></td></tr>
        <tr><th>Image</th><td><img height="120px" src="<?= $prefix . '/'.$thingstodo['image'];?>"></td></tr>
     </tbody>
</table>
<?php if($user_data['role'] != 'copywriter') {?>
<h1>French<span style="float:right;"> <?= anchor("$controller/lang_itemedit/fr/".$id, "Edit");?></span></h1>
<table class="table table-striped hotelsTable">
        <tbody>
                <tr><th>Title</th><td><?php echo $result['fr']['title'];?></td></tr>
                <tr><th>Description</th><td><?php echo $result['fr']['description'];?></td></tr>
                <tr><th>H3</th><td><?php echo $result['fr']['h3'];?></td></tr>
                <tr><th>P3</th><td><?php echo $result['fr']['p3'];?></td></tr>
                <tr><th>H4</th><td><?php echo $result['fr']['h4'];?></td></tr>
                <tr><th>P4</th><td><?php echo $result['fr']['p4'];?></td></tr>
                <tr><th>Description Things To Do</th><td><?php echo $result['fr']['desc_thingstodo'];?></td></tr>
                <tr><th>Description Discover</th><td><?php echo $result['fr']['desc_discover'];?></td></tr>
                <tr><th>Description Hotels In</th><td><?php echo $result['fr']['desc_hotelsin'];?></td></tr>
        </tbody>
</table>

<h1>Hindu<span style="float:right;"> <?= anchor("$controller/lang_itemedit/in/".$id, "Edit");?></span></h1>
<table class="table table-striped hotelsTable">
        <tbody>
                <tr><th>Title</th><td><?php echo $result['hi']['title'];?></td></tr>
                <tr><th>Description</th><td><?php echo $result['hi']['description'];?></td></tr>
                <tr><th>H3</th><td><?php echo $result['hi']['h3'];?></td></tr>
                <tr><th>P3</th><td><?php echo $result['hi']['p3'];?></td></tr>
                <tr><th>H4</th><td><?php echo $result['hi']['h4'];?></td></tr>
                <tr><th>P4</th><td><?php echo $result['hi']['p4'];?></td></tr>
                <tr><th>Description Things To Do</th><td><?php $result['hi']['desc_thingstodo'];?></td></tr>
                <tr><th>Description Discover</th><td><?php echo $result['hi']['desc_discover'];?></td></tr>
                <tr><th>Description Hotels In</th><td><?php echo $result['hi']['desc_hotelsin'];?></td></tr>
        </tbody>
</table>

<h1>Chinese<span style="float:right;"> <?= anchor("$controller/lang_itemedit/cn/".$id, "Edit");?></span></h1>
<table class="table table-striped hotelsTable">
        <tbody>
                <tr><th>Title</th><td><?php echo $result['cn']['title'];?></td></tr>
                <tr><th>Description</th><td><?php echo $result['cn']['description'];?></td></tr>
                <tr><th>H3</th><td><?php echo $result['cn']['h3'];?></td></tr>
                <tr><th>P3</th><td><?php echo $result['cn']['p3'];?></td></tr>
                <tr><th>H4</th><td><?php echo $result['cn']['h4'];?></td></tr>
                <tr><th>P4</th><td><?php echo $result['cn']['p4'];?></td></tr>
                <tr><th>Description Things To Do</th><td><?php echo $result['cn']['desc_thingstodo'];?></td></tr>
                <tr><th>Description Discover</th><td><?php echo $result['cn']['desc_discover'];?></td></tr>
                <tr><th>Description Hotels In</th><td><?php echo $result['cn']['desc_hotelsin'];?></td></tr>
        </tbody>
</table>
<?php } ?>
<input type="hidden" name="type" value="tplace" id="ttype">
<input type="hidden" id="ttdId" value="<?php echo $id ?>">
<?php } ?>
<?php $id=isset($thingstodo)?$thingstodo['id']:0;?>
<div>
    <input type="text" id="txtId" placeholder="Filter by id"/>
    <input type="text" id="txtName" placeholder="Filter by name..."/>
    <button type="button" id="btnFilter">Search</button>
    <button type="button" id="btnReset">Reset</button>
</div>
<h1> Items    <?php if($user_data['role'] != 'copywriter') {?><span style="float:right;"> <?= anchor("$controller/item_add/$id", "Add");?></span><?php } ?></h1>
<div id="listContainer">
    <?php $this->load->view('thingstodo/view_ajax');?>
</div>

</div>



