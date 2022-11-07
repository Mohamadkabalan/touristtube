<?php
$controller = $this->router->class;
$user_data = $this->session->userdata('logged_in');
if(isset($parent)){
    if(isset($parent['id'])){
        $id = $parent['id'];
    }
    $name = $parent['name'];
    $parentId = $parent['parent_id'];
    $division_category_id = $parent['division_category_id'];
    $ttd_id = $parent['ttd_id'];
    $media_settings = $parent['media_settings'];
    $sort_order = $parent['sort_order'];
}
?>
<div id="listContainer">
	<h1><?php echo $title;?></h1>

<?php echo validation_errors(); ?>
<?php echo form_open_multipart("$controller/division_submit/");?>
<?php echo form_hidden('id', $id);?>
<div class="form-group"><label class="col-md-2">Name</label><div class="col-md-9"><?php echo form_input(array('name'=>'name', 'value'=>$name, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Division Category </label><div class="col-md-9">
        <select id="division_category_id" name="division_category_id" class="form-control">
            <?php foreach($thingstodoDivisionCategorys as $thingstodoDivisionCategory){ ?>
            <option value="<?php echo $thingstodoDivisionCategory['thingstodoDivisionCategoryId'] ?>"<?php if($division_category_id == $thingstodoDivisionCategory['thingstodoDivisionCategoryId']){ ?>selected<?php } ?>><?php echo $thingstodoDivisionCategory['thingstodoDivisionCategoryTitle'] ?></option>
            <?php } ?>
        </select>
    </div>
</div>
<div style="clear: both; height: 20px; margin-bottom: 5px;"></div>
<div class="form-group"><label class="col-md-2">Things to do in</label><div class="col-md-9">
        <select id="ttd_id" name="ttd_id" class="form-control">
            <?php foreach($thingstodo as $thingstod){ ?>
            <option value="<?php echo $thingstod['thingstodoId'] ?>" <?php if($ttd_id == $thingstod['thingstodoId']){ ?>selected<?php } ?>><?php echo $thingstod['thingstodoTitle'] ?></option>
            <?php } ?>
        </select>
    </div>
</div>
<div style="clear: both; height: 20px; margin-bottom: 5px;"></div>
<div class="form-group"><label class="col-md-2">Media Settings</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'media_settings', 'value'=>$media_settings, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Sort Order</label><div class="col-md-9"><?php echo form_input(array('name'=>'sort_order', 'value'=>$sort_order, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit');?></div></div>
<?php echo form_close();?>

        <table class="table">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th></th>
                <th><div class="add-deal"><a href=<?php echo $controller."/division_details_add/".$id ?>>Add</a></div></th>
            </tr>
            <?php foreach($divisions as $division){ ?>
            <tr>
                <td><?= $division->id;?></td>
                <td><?= $division->name;?></td>
                <td><?= anchor("$controller/division_details_edit/".$division->id, 'Edit');?></td>
                <td><?= anchor("$controller/ajax_division_details_delete/".$division->id, "Delete");?></td>
            </tr>
            <?php  } ?>
        </table>
</div>