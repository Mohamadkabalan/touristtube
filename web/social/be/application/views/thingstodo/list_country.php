<?php
$controller = $this->router->class;
$user_data = $this->session->userdata('logged_in');
$id = $countryid;

if( $this->config->item('upload_src') == "s3" ){
	$prefix = 'https://' . $this->config->item('bucketName').$this->config->item('subdomain_suffix') . ".". S3::$endpoint.'/media/thingstodo';
}
else{
	$prefix = '/media/thingstodo';
}

?>
<div id="listContainer">
    <?php if( isset($countrydetail) && (count($countrydetail)>0)){?>
    <h1><?php echo $countrydetail['title'];?><?php if($user_data['role'] != 'copywriter') {?><span style="float:right;"> <?= anchor("$controller/country_edit/".$countryid, "Edit");?></span><?php } ?></h1>
    <table class="table table-striped hotelsTable">
            <tbody>
                    <tr><th>Title</th><td><?php echo $countrydetail['title'];?></td></tr>
                    <tr><th>Description</th><td><?php echo $countrydetail['description'];?></td></tr>
                    <tr><th>H3</th><td><?php echo $countrydetail['h3'];?></td></tr>
                    <tr><th>P3</th><td><?php echo $countrydetail['p3'];?></td></tr>
                    <tr><th>H4</th><td><?php echo $countrydetail['h4'];?></td></tr>
                    <tr><th>P4</th><td><?php echo $countrydetail['p4'];?></td></tr>
                    <tr><th>Image</th><td><img height="120px" src="<?php echo $prefix . '/' .$countrydetail['image'];?>"></td></tr>
            </tbody>
    </table>
    <?php if($user_data['role'] != 'copywriter') {?>
    <h1>French<span style="float:right;"> <?= anchor("$controller/lang_countryedit/fr/".$countryid, "Edit");?></span></h1>
    <table class="table table-striped hotelsTable">
            <tbody>
                    <tr><th>Title</th><td><?php echo $result['fr']['title'];?></td></tr>
                    <tr><th>Description</th><td><?php echo $result['fr']['description'];?></td></tr>
                    <tr><th>H3</th><td><?php echo $result['fr']['h3'];?></td></tr>
                    <tr><th>P3</th><td><?php echo $result['fr']['p3'];?></td></tr>
                    <tr><th>H4</th><td><?php echo $result['fr']['h4'];?></td></tr>
                    <tr><th>P4</th><td><?php echo $result['fr']['p4'];?></td></tr>
            </tbody>
    </table>

    <h1>Hindu<span style="float:right;"> <?= anchor("$controller/lang_countryedit/in/".$countryid, "Edit");?></span></h1>
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

    <h1>Chinese<span style="float:right;"> <?= anchor("$controller/lang_countryedit/cn/".$countryid, "Edit");?></span></h1>
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
    <p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
    <h1> Items    <?php if($user_data['role'] != 'copywriter') {?><span style="float:right;"> <?= anchor("$controller/add/$id", "Add");?></span><?php } ?></h1>
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

        ?>
    <tr>
    <td><?= $item->id;?></td>
    <td><?= $item->order_display;?></td>
    <td><?= $item->title;?></td>
    <td><?= $item->description;?></td>
    <td><img height="120px" src="<?= $prefix .'/'.$item->image;?>"></td>
    <td><?= anchor("$controller/view/".$item->id, 'View');?></td>
    <td><?= $user_data['role'] != 'copywriter' ? anchor("$controller/ajax_delete/".$item->id, "Delete", array('class'=>'deleteAct')): '';?></td>
    </tr>
    <?php  } ?>
    </table>
    <p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>
</div>