<?php
if(isset($restaurant)){
$id = $restaurant->id;
$name = $restaurant->name;
$latitude = $restaurant->latitude;
$longitude = $restaurant->longitude;
$country = $restaurant->country;
$city = $restaurant->city;
$address = $restaurant->address;
$about = $restaurant->about;
$description = $restaurant->description;
$facilities = $restaurant->facilities;
}
?>


<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo $this->form_builder->open_form(array('action' => 'restaurant/submit')); ?>     
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
        'value' => !empty($name) ? $name : ''
    ),

      array(
      'id' => 'country',
       'placeholder' => 'Country',
       'class'=>$input_span." required",
        'value' => !empty($country) ? $country : '',
    ),
    array(
      'id' => 'city',
       'placeholder' => 'City',
       'class'=>$input_span." required",
        'value' => !empty($city) ? $city : '',
    ),
    array(
      'id' => 'address',
       'placeholder' => 'Address',
       'class'=>$input_span." required",
        'value' => !empty($address) ? $address : '',
    ),
    array(
      'id' => 'latitude',
       'placeholder' => 'latitude',
       'class'=>$input_span." required",
        'value' => !empty($latitude) ? $latitude : '',
    ),

    array(
      'id' => 'longitude',
       'placeholder' => 'longitude',
       'class'=>$input_span." required",
        'value' => !empty($longitude) ? $longitude : '',
    ),

     
 array(
      'id' => 'about',
       'type' => 'textarea',
       'class'=>$input_span." wysihtml5 required",
        'value' => !empty($about) ? $about : '',
    ),
          array(
      'id' => 'description',
       'type' => 'textarea',
       'class'=>$input_span." wysihtml5 required",
        'value' => !empty($description) ? $description : '',
    ), 
 array(
      'id' => 'facilities',
      'type' => 'textarea',
       'placeholder' => 'facilities',
       'class'=>$input_span." required",
        'value' => !empty($facilities) ? $facilities : '',
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













