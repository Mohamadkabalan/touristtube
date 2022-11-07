<?php
if(isset($hotel)){
$id = $hotel->id;
$hotelName = $hotel->hotelName;
$stars = $hotel->stars;
$price = $hotel->price;
$cityName = $hotel->cityName;
$stateName = $hotel->stateName;
$countryCode = $hotel->countryCode;
$countryName = $hotel->countryName;
$address = $hotel->address;
$location = $hotel->location;
$url = $hotel->url;
$tripadvisorUrl = $hotel->tripadvisorUrl;
$latitude = $hotel->latitude;
$longitude = $hotel->longitude;
$latlong = $hotel->latlong;
$propertyType = $hotel->propertyType;
$chainId = $hotel->chainId;
$rooms = $hotel->rooms;
$facilities = $hotel->facilities;
$checkIn = $hotel->checkIn;
$checkOut = $hotel->checkOut;
$rating = $hotel->rating;
$about = $hotel->about;
$description = $hotel->description;
$general_facilities = $hotel->general_facilities;
$services = $hotel->services;
}
?>


<h1><?=(isset($title) ? $title : '')?></h1>
<?php echo validation_errors(); ?>
<?php echo $this->form_builder->open_form(array('action' => 'hotel/submit')); ?>     
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
        'id' => 'hotelName',
        'placeholder' => 'Name',
        'class'=>$input_span." required",
        'value' => !empty($hotelName) ? $hotelName : ''
    ),
    array(
      'id' => 'stars',
       'placeholder' => 'stars',
       'class'=>$input_span." required",
        'value' => !empty($stars) ? $stars : '',
    ),
    array(
      'id' => 'price',
       'placeholder' => 'price',
       'class'=>$input_span." required",
        'value' => !empty($price) ? $price : '',
    ),
    array(
      'id' => 'cityName',
       'placeholder' => 'city Name',
       'class'=>$input_span." required",
        'value' => !empty($cityName) ? $cityName : '',
    ),
    array(
      'id' => 'stateName',
       'placeholder' => 'stateName',
       'class'=>$input_span." required",
        'value' => !empty($stateName) ? $stateName : '',
    ),
    array(
      'id' => 'countryCode',
       'placeholder' => 'countryCode',
       'class'=>$input_span." required",
        'value' => !empty($countryCode) ? $countryCode : '',
    ),
    array(
      'id' => 'countryName',
       'placeholder' => 'countryName',
       'class'=>$input_span." required",
        'value' => !empty($countryName) ? $countryName: '',
    ),
    array(
      'id' => 'address',
       'type' => 'textarea',
       'class'=>$input_span." required",
        'value' => !empty($address) ? $address : '',
    ),
    array(
      'id' => 'location',
       'placeholder' => 'location',
       'class'=>$input_span." required",
        'value' => !empty($location) ? $location : '',
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
      'id' => 'propertyType',
       'placeholder' => 'propertyType',
       'class'=>$input_span." required",
        'value' => !empty($propertyType) ? $propertyType : '',
    ),


       array(
      'id' => 'facilities',
       'placeholder' => 'facilities',
       'class'=>$input_span." required",
        'value' => !empty($facilities) ? $facilities : '',
    ),
   array(
      'id' => 'rating',
       'placeholder' => 'rating',
       'class'=>$input_span." required",
        'value' => !empty($rating) ? $rating : '',
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
    ),    array(
      'id' => 'general_facilities',
       'type' => 'textarea',
       'class'=>$input_span." editor wysihtml5 required",
        'value' => !empty($general_facilities) ? $general_facilities : '',
    ),    array(
      'id' => 'services',
       'type' => 'textarea',
       'class'=>$input_span." editor wysihtml5 required",
        'value' => !empty($services) ? $services : '',
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











