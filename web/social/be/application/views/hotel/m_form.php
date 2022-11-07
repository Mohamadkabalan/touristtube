<?php
$controller = $this->router->class;
if(isset($hotel)){
$id = $hotel['id'];
$hotelName = $hotel['hotelName'];
$stars = $hotel['stars'];
$price = $hotel['price'];
$cityName = $hotel['cityName'];
$cityId = isset($hotel['city_id']) ? $hotel['city_id'] : '';
$stateName = $hotel['stateName'];
$countryCode = $hotel['countryCode'];
$countryName = $hotel['countryName'];
$address = $hotel['address'];
$location = $hotel['location'];
$url = $hotel['url'];
$latitude = $hotel['loc']['lat'];
$longitude = $hotel['loc']['lon'];
$latlong = $hotel['latlong'];
$propertyType = $hotel['propertyType'];
$chainId = $hotel['chainId'];
$checkIn = $hotel['checkIn'];
$checkOut = $hotel['checkOut'];
$rating = $hotel['rating'];
$about = $hotel['about'];
$description = $hotel['description'];
$services = $hotel['services'];
$zipcode = isset($hotel['zipcode']) ? $hotel['zipcode'] : '';
$phone = isset($hotel['phone']) ? $hotel['phone'] : '';
$fax = isset($hotel['fax']) ? $hotel['fax'] : '';
$email = isset($hotel['email']) ? $hotel['email'] : '';
$website = isset($hotel['website']) ? $hotel['website'] : '';
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
//    array(
//      'id' => 'cityName',
//       'placeholder' => 'city Name',
//       'class'=>$input_span." required",
//        'value' => !empty($cityName) ? $cityName : '',
//    ),
    array(
      'id' => 'cityId',
        'value' => !empty($cityId) ? $cityId : '',
        'label' => 'City'
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
      'id' => 'zipcode',
       'placeholder' => 'zipcode',
       'class'=>$input_span." required",
        'value' => !empty($zipcode) ? $zipcode : ''
    ),
    array(
      'id' => 'phone',
       'placeholder' => 'phone',
       'class'=>$input_span." required",
        'value' => !empty($phone) ? $phone : ''
    ),
    array(
      'id' => 'fax',
       'placeholder' => 'fax',
       'class'=>$input_span." required",
       'value' => !empty($fax) ? $fax : ''
    ),
    array(
      'id' => 'email',
       'placeholder' => 'email',
       'class'=>$input_span." required",
       'value' => !empty($email) ? $email : ''
    ),
    array(
      'id' => 'website',
       'placeholder' => 'website',
       'class'=>$input_span." required",
       'value' => !empty($website) ? $website : ''
    ),
    array(
      'id' => 'propertyType',
       'placeholder' => 'propertyType',
       'class'=>$input_span." required",
        'value' => !empty($propertyType) ? $propertyType : '',
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
    ),
    array(
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











