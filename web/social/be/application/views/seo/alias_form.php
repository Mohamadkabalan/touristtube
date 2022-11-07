<?php
$controller = $this->router->class;
if(isset($item)){
    $id = $item['id'];
    $name = $item['title']; 
    $description = $item['description']; 
    $keywords = $item['keywords']; 
    $url = $item['url'];
}
?>
<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo form_open_multipart("$controller/alias_submit");?>
<?php echo form_hidden('id', $id);?>
<div class="form-group"><label class="col-md-2">Url</label><div class="col-md-9"><?php echo form_input(array('name'=>'url', 'value' => $url, 'class'=>'form-control'));?></div></div>
<?php if(isset($item)){ ?>
<div class="form-group"><label class="col-md-11"><h2>English</h2></label></div>
<?php } ?>
<div class="form-group"><label class="col-md-2">Name</label><div class="col-md-9"><?php echo form_input(array('name'=>'title', 'value'=>$name, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'description','value'=>$description, 'class'=>'form-control'));?></div></div>
<div class="form-group"><label class="col-md-2">Keywords</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'keywords', 'value' => $keywords, 'class'=>'form-control'));?></div></div>

<div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit English');?></div></div>
<?php echo form_close();?>



<?php
    if(isset($item)){
        if(isset($item_fr)){
        $title_fr = $item_fr['title'];
        $description_fr = $item_fr['description'];
        $keywords_fr = $item_fr['keywords'];
        $id_fr = $item_fr['id'];
        ?>


    <div class="form-group"><label class="col-md-11"><h2>French</h2></label></div>
    <?php echo form_open_multipart("$controller/alias_submit");?>
    <?php echo form_hidden('parent_id', $id);?>
    <?php echo form_hidden('lang', 'fr');?>
    <?php echo form_hidden('id_fr', $id_fr);?>
    <div class="form-group"><label class="col-md-2">Name</label><div class="col-md-9"><?php echo form_input(array('name'=>'title_fr', 'value'=>$title_fr, 'class'=>'form-control'));?></div></div>
    <div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'description_fr','value'=>$description_fr, 'class'=>'form-control'));?></div></div>
    <div class="form-group"><label class="col-md-2">Keywords</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'keywords_fr', 'value' => $keywords_fr, 'class'=>'form-control'));?></div></div>
    <div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit_fr', 'Submit French');?></div></div>
    <?php echo form_close();?>

    <?php }
    
        }
        
    if(isset($item)){
        if(isset($item_in)){
        $title_in = $item_in['title'];
        $description_in = $item_in['description'];
        $keywords_in = $item_in['keywords'];
        $id_in = $item_in['id'];
        ?>


    <div class="form-group"><label class="col-md-11"><h2>Hindi</h2></label></div>
    <?php echo form_open_multipart("$controller/alias_submit");?>
    <?php echo form_hidden('parent_id', $id);?>
    <?php echo form_hidden('lang', 'in');?>
    <?php echo form_hidden('id_in', $id_in);?>
    <div class="form-group"><label class="col-md-2">Name</label><div class="col-md-9"><?php echo form_input(array('name'=>'title_in', 'value'=>$title_in, 'class'=>'form-control'));?></div></div>
    <div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'description_in','value'=>$description_in, 'class'=>'form-control'));?></div></div>
    <div class="form-group"><label class="col-md-2">Keywords</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'keywords_in', 'value' => $keywords_in, 'class'=>'form-control'));?></div></div>
    <div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit_in', 'Submit Hindi');?></div></div>
    <?php echo form_close();?>

    <?php }
    
        }
?>