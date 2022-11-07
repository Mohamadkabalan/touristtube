<?php
$controller = $this->router->class;
if(isset($ttd_idas)){
    $ttd_idas = $ttd_idas;
}
if(isset($parentId)){
    $parentId = $parentId;
}
if(isset($division)){
    if(isset($division['id'])){
        $id = $division['id'];
    }
    $name = $division['name'];
    $parentId = $division['parent_id'];
    $division_category_id = $division['division_category_id'];
    $ttd_id = $division['ttd_id'];
    $media_settings = $division['media_settings'];
    $sort_order = $division['sort_order'];
}
?>


<?php echo validation_errors(); ?>
<?php echo form_open_multipart("$controller/division_details_submit/");?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('parentId', $parentId);?>
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
            <option value="<?php echo $thingstod['thingstodoId'] ?>" <?php if($ttd_id == $thingstod['thingstodoId'] || $ttd_idas == $thingstod['thingstodoId'] ){ ?>selected<?php } ?>><?php echo $thingstod['thingstodoTitle'] ?></option>
            <?php } ?> 
        </select> 
    </div>   
</div>
<div style="clear: both; height: 20px; margin-bottom: 5px;"></div>
<div class="form-group"><label class="col-md-2">Media Settings</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'media_settings', 'value'=>$media_settings, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Sort Order</label><div class="col-md-9"><?php echo form_input(array('name'=>'sort_order', 'value'=>$sort_order, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit');?></div></div>
<?php echo form_close();?>











