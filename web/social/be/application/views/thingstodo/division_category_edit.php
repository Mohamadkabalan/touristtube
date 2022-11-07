<?php
$controller = $this->router->class;
if(isset($category)){
    if(isset($category['id'])){
        $id = $category['id'];
    }
    $name = $category['name'];
}
?>


<h1><?=(isset($title) ? $title : '')?></h1>

<?php echo validation_errors(); ?>
<?php echo form_open_multipart("$controller/division_category_submit/");?>
<?php echo form_hidden('id', $id);?>
<div class="form-group"><label class="col-md-2">Name</label><div class="col-md-9"><?php echo form_input(array('name'=>'name', 'value'=>$name, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit');?></div></div>
<?php echo form_close();?>











