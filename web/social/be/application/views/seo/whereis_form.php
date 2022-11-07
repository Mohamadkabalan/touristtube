<?php
//echo $this->uri->segment(3);die;
$controller = $this->router->class;
$this->load->helper('cms_countries');
$entity_type_options = array(
    '71' => 'City',
    '72' => 'Country',
    '73' => 'State'
);
$country = 0;
$state = 0;
$city_id = 0;
if(isset($item)){
    $id = $item['id'];
    $name = $item['title'];
    $link = $item['link'];
    $entity_id = $item['entity_id'];
    $entity_type = $item['entity_type'];
    switch($entity_type){
        case SOCIAL_ENTITY_CITY:
            $city_id = $entity_id;
            break;
        case SOCIAL_ENTITY_COUNTRY:
            $country = $entity_id;
            break;
        case SOCIAL_ENTITY_STATE:
            $state = $entity_id;
            break;
    }
    $description = $item['description'];
}
if(isset($_GET['entity_type'])){ 
    $entity_type = $_GET['entity_type'];
    $entity_id= $_GET['entity_id'];
    switch($entity_type){
        case SOCIAL_ENTITY_CITY:
            $city_id = $entity_id;
            break;
        case SOCIAL_ENTITY_COUNTRY:
            //$country = $entity_id;
            $country_data = countrygetbyid($entity_id);
            $country = $country_data['code'];
            break;
        case SOCIAL_ENTITY_STATE:
            $state = $entity_id;
            break;
    }
}

?>


<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo form_open_multipart("$controller/whereis_submit");?>
<?php echo form_hidden('id', $id);?>



<div class="form-group"><label class="col-md-2">Country</label><div class="col-md-9"><?php echo form_input(array('name'=>'country', 'value' => $country));?></div></div>
<div class="form-group"><label class="col-md-2">State</label><div class="col-md-9"><?php echo form_input(array('name'=>'state', 'value' => $state));?></div></div>
<div class="form-group"><label class="col-md-2">City</label><div class="col-md-9"><?php echo form_input(array('name'=>'city_id', 'value' => $city_id));?></div></div>
<div class="form-group"><label class="col-md-2">Relation type</label><div class="col-md-1"><div class="select2-container"><?php echo form_dropdown('entity_type', $entity_type_options, !empty($entity_type) ? $entity_type : 71);?></div></div>
    <div class="col-md-9 check_div" <?php //if(isset($item)){ ?> style="display: none;" <?php// } ?> ><button type="button" class="check_button">Check</button> </div>
    <div class="col-md-11"></div>
</div>


<div class="check_edit_div">

    <div class="form-group check_link" <?php if(!isset($item)){ ?> style="display: none;"  <?php } ?>><label class="col-md-2">Link</label><div class="col-md-9"><?php echo form_input(array('name'=>'link', 'value'=>$link, 'class'=>'form-control'));?></div></div>

    <?php if(isset($item)){ ?>
    <div class="form-group"><label class="col-md-11"><h2>English</h2></label></div>
    <?php } ?>


    <div class="check_div_title"
        <?php if($this->uri->segment(3)==="1"){ //echo "hii";die;?>
            style="display: block;"
        <?php } else if(isset($item)){ ?>
            style="display: block;"  
     <?php }else{ ?> 
            style="display: none;"
    <?php } ?>>

        <div class="form-group"><label class="col-md-2">Title</label><div class="col-md-9"><?php echo form_input(array('name'=>'title', 'value'=>$name, 'class'=>'form-control'));?></div></div>
        <div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'description','value'=>$description, 'class'=>'form-control'));?></div></div>
        <div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit', 'Submit for English' );?></div></div>

    </div>


    <?php echo form_close();?>

    <?php
    if(isset($item)){ 
        if(isset($item_fr)){   
        //print_r($item_hi);
        $item_id = $item_fr[0]->id;
        $title = $item_fr[0]->title;
        $description=$item_fr[0]->description;
        ?>


    <div class="form-group"><label class="col-md-11"><h2>French</h2></label></div>
    <?php echo form_open_multipart("$controller/whereis_submit");?>
    <?php echo form_hidden('parent_id', $id);?>
    <?php echo form_hidden('lang', 'fr');?>
    <?php echo form_hidden('id_fr', $item_id);?>
    <div class="form-group"><label class="col-md-2">Title</label><div class="col-md-9"><?php echo form_input(array('name'=>'title_fr', 'value'=>$title, 'class'=>'form-control'));?></div></div>
    <div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'description_fr','value'=>$description, 'class'=>'form-control'));?></div></div>
    <div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit_fr', 'Submit for French');?></div></div>
    <?php echo form_close();?>

        <?php } ?>

    <?php if(isset($item_hi)){   
        //print_r($item_hi);
        $item_id = $item_hi[0]->id;
        $title = $item_hi[0]->title;
        $description=$item_hi[0]->description;
        ?>
    <div class="form-group"><label class="col-md-11"><h2>Hindi</h2></label></div>
    <?php echo form_open_multipart("$controller/whereis_submit");?>
    <?php echo form_hidden('parent_id', $id);?>
    <?php echo form_hidden('lang', hi);?>
    <?php echo form_hidden('id_hi', $item_id);?>
    <div class="form-group"><label class="col-md-2">Title</label><div class="col-md-9"><?php echo form_input(array('name'=>'title_hi', 'value'=>$title, 'class'=>'form-control'));?></div></div>
    <div class="form-group"><label class="col-md-2">Description</label><div class="col-md-9"><?php echo form_textarea(array('name'=>'description_hi','value'=>$description, 'class'=>'form-control'));?></div></div>
    <div class="form-group"><label class="col-md-2"></label><div class="col-md-9"><?php echo form_submit('submit_hi', 'Submit for Hindi');?></div></div>
    <?php echo form_close();?>

    <?php 
        }
    } ?>
</div>