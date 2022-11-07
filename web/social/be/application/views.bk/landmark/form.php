<?php
$id = $restaurant->id;
$restaurantName = $restaurant->restaurantName;
$stars = $restaurant->stars;
$price = $restaurant->price;
$cityName = $restaurant->cityName;
$stateName = $restaurant->stateName;
$countryCode = $restaurant->countryCode;
$countryName = $restaurant->countryName;
$address = $restaurant->address;
$location = $restaurant->location;
$url = $restaurant->url;
$tripadvisorUrl = $restaurant->tripadvisorUrl;
$latitude = $restaurant->latitude;
$longitude = $restaurant->longitude;
$latlong = $restaurant->latlong;
$propertyType = $restaurant->propertyType;
$chainId = $restaurant->chainId;
$rooms = $restaurant->rooms;
$facilities = $restaurant->facilities;
$checkIn = $restaurant->checkIn;
$checkOut = $restaurant->checkOut;
$rating = $restaurant->rating;
$description = $restaurant->description;
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
        'id' => 'restaurantName',
        'placeholder' => 'Name',
        'class'=>$input_span." required",
        'value' => !empty($restaurantName) ? $restaurantName : ''
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
      'id' => 'url',
       'placeholder' => 'url',
       'class'=>$input_span." required",
        'value' => !empty($url) ? $url : '',
    ),

    array(
      'id' => 'tripadvisorUrl',
       'placeholder' => 'tripadvisorUrl',
       'class'=>$input_span." required",
        'value' => !empty($tripadvisorUrl) ? $tripadvisorUrl : '',
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
      'id' => 'longitude',
       'placeholder' => 'longitude',
       'class'=>$input_span." required",
        'value' => !empty($longitude) ? $longitude : '',
    ),

    array(
      'id' => 'longitude',
       'placeholder' => 'longitude',
       'class'=>$input_span." required",
        'value' => !empty($longitude) ? $longitude : '',
    ),

    array(
      'id' => 'longitude',
       'placeholder' => 'longitude',
       'class'=>$input_span." required",
        'value' => !empty($longitude) ? $longitude : '',
    ),

    array(
      'id' => 'latlong',
       'placeholder' => 'latlong',
       'class'=>$input_span." required",
        'value' => !empty($latlong) ? $latlong : '',
    ),

    array(
      'id' => 'propertyType',
       'placeholder' => 'propertyType',
       'class'=>$input_span." required",
        'value' => !empty($propertyType) ? $propertyType : '',
    ),

    array(
      'id' => 'chainId',
       'placeholder' => 'chainId',
       'class'=>$input_span." required",
        'value' => !empty($chainId) ? $chainId : '',
    ),
       array(
      'id' => 'facilities',
       'placeholder' => 'facilities',
       'class'=>$input_span." required",
        'value' => !empty($facilities) ? $facilities : '',
    ),
     array(
      'id' => 'checkIn',
       'placeholder' => 'checkIn',
       'class'=>$input_span." required",
        'value' => !empty($checkIn) ? $checkIn : '',
    ),
         array(
      'id' => 'checkOut',
       'placeholder' => 'checkOut',
       'class'=>$input_span." required",
        'value' => !empty($checkOut) ? $checkOut : '',
    ),    
         array(
      'id' => 'rating',
       'placeholder' => 'rating',
       'class'=>$input_span." required",
        'value' => !empty($rating) ? $rating : '',
    ),
          array(
      'id' => 'description',
       'type' => 'textarea',
       'class'=>$input_span." wysihtml5 required",
        'value' => !empty($description) ? $description : '',
    ),
    array(
        'id' => 'submit',
        'type' => 'submit'
    )
        ));
?>
<?= $this->form_builder->close_form(); ?>