<?php
$controller = $this->router->class;
if(isset($poi)){
$id = $poi['id'];
$name = $poi['name'];
$latitude = $poi['loc']['lat'];
$longitude = $poi['loc']['lon'];
$country = $poi['country'];
$show_on_map = $poi['show_on_map'];
$cityId = $poi['city_id'];
$city = $poi['city'];
$zipcode = $poi['zipcode'];
$phone = $poi['phone'];
$fax = $poi['fax'];
$email = $poi['email'];
$website = $poi['website'];
$price = $poi['price'];
$description = $poi['description'];
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
        'value' => !empty($name) ? $name : ''
    ),
    array(
        'id' => 'price',
        'placeholder' => 'Price',
        'class'=>$input_span." required",
        'value' => !empty($price) ? $price : ''
    ),
      array(
      'id' => 'country',
       'placeholder' => 'Country',
       'class'=>$input_span." required",
        'value' => !empty($country) ? $country : ''
    ),
//      array(
//      'id' => 'city',
//       'placeholder' => 'City',
//       'class'=>$input_span." required",
//        'value' => !empty($city) ? $city : ''
//    ),
    array(
      'id' => 'cityId',
        'value' => !empty($cityId) ? $cityId : '',
        'label' => 'City'
    ),
    array(
      'id' => 'latitude',
       'placeholder' => 'latitude',
       'class'=>$input_span." required",
        'value' => !empty($latitude) ? $latitude : ''
    ),

    array(
      'id' => 'longitude',
       'placeholder' => 'longitude',
       'class'=>$input_span." required",
        'value' => !empty($longitude) ? $longitude : ''
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
      'id' => 'description',
       'placeholder' => 'description',
       'class'=>$input_span." required",
       'value' => !empty($description) ? $description : ''
    ),
    array(
      'id' => 'show_on_map',
      'value' => !empty($show_on_map) ? $show_on_map : '0',
      'checked' => !empty($show_on_map) ? $show_on_map : '0',
      'type' => 'checkbox'
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












