<?php
$controller = $this->router->class;
if(isset($hotel)){
$id = $hotel['id'];
$name = $hotel['name'];
$stars = $hotel['stars'];
$cityName = $hotel['city'];
//$cityId = isset($hotel['city_id']) ? $hotel['city_id'] : '';
$latitude = $hotel['latitude'];
$longitude = $hotel['longitude'];
$description = $hotel['description'];
$zipcode = isset($hotel['zip_code']) ? $hotel['zip_code'] : '';
}
?>


<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo $this->form_builder->open_form(array('action' => "$controller/submit")); ?>     
<?php
$pref = (isset($prefix)) ? $prefix : '';
$group_sufx = (isset($prefix) && !empty($prefix)) ? str_replace('_', '', $prefix) : uniqid();
$input_span = (isset($input_span)) ? $input_span . ' ' : '';

echo $this->form_builder->build_form_horizontal(
     array(
       array(
        'id' => 'id',
        'value' => !empty($id) ? $id : '',
        'type'=>'hidden'
    ),array(
        'id' => 'name',
        'placeholder' => 'Name',
        'class'=>$input_span." required",
        'value' => !empty($name) ? $name : '',
        'readonly' => ''
    ),
    array(
      'id' => 'description',
       'type' => 'textarea',
       'class'=>$input_span." wysihtml5 required",
        'value' => !empty($description) ? $description : '',
    ),
    array(
      'id' => 'stars',
       'placeholder' => 'stars',
       'class'=>$input_span." required",
        'value' => !empty($stars) ? $stars : '',
        'readonly' => ''
    ),
    array(
      'id' => 'city',
       'placeholder' => 'City',
       'class'=>$input_span." required",
        'value' => !empty($cityName) ? $cityName : '',
        'readonly' => ''
    ),
//    array(
//      'id' => 'cityId',
//        'value' => !empty($cityId) ? $cityId : '',
//        'label' => 'City'
//    ),
    array(
      'id' => 'latitude',
       'placeholder' => 'latitude',
       'class'=>$input_span." required",
        'value' => !empty($latitude) ? $latitude : '',
        'readonly' => ''
    ),

    array(
      'id' => 'longitude',
       'placeholder' => 'longitude',
       'class'=>$input_span." required",
        'value' => !empty($longitude) ? $longitude : '',
        'readonly' => ''
    ),
    array(
      'id' => 'zipcode',
       'placeholder' => 'zipcode',
       'class'=>$input_span." required",
        'value' => !empty($zipcode) ? $zipcode : '',
        'readonly' => ''
    ),
    
    array(
        'id' => 'submit',
        'type' => 'submit'
    )
        ));
?>
<?= $this->form_builder->close_form(); ?>

 <style>
.ui-autocomplete-loading {
background: white url('media/images/ui-anim_basic_16x16.gif') right center no-repeat;
}
</style>
<!--
<div class="ui-widget">
<label for="birds">Birds: </label>
<input id="birds">
</div>

<div class="ui-widget" style="margin-top:2em; font-family:Arial">
Result:
<div id="log" style="height: 200px; width: 300px; overflow: auto;" class="ui-widget-content"></div>
</div>
-->











