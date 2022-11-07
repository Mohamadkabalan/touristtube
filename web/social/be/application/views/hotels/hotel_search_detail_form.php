<?php
$controller = $this->router->class;
if(isset($hotel_search_details)){
    $id = $hotel_search_details['id'];
    $name = $hotel_search_details['name'];
    $popular = $hotel_search_details['popular'];
    $longitude = $hotel_search_details['longitude'];
    $latitude = $hotel_search_details['latitude'];
    $entity_id = $hotel_search_details['entity_id'];
    $entity_type = $hotel_search_details['entity_type'];
    $hotel_boooking_id = $hotel_search_details['hotel_booking_id'];
    $popularity = $hotel_search_details['popularity'];
    $country_code = $hotel_search_details['country_code'];
}
?>
<h1><?=(isset($title) ? $title : '')?></h1>

<?php echo validation_errors(); ?>
<?php echo $this->form_builder->open_form(array('id' => 'search_detail_form', 'action' => "$controller/search_detail_submit")); ?>     
<?php
$pref = (isset($prefix)) ? $prefix : '';
$group_sufx = (isset($prefix) && !empty($prefix)) ? str_replace('_', '', $prefix) : uniqid();
$input_span = (isset($input_span)) ? $input_span . ' ' : '';

echo $this->form_builder->build_form_horizontal(
    array(
       array(
           'id' => 'hotel_booking_id',
           'value' => $hotel_search_id,
           'type'=>'hidden'
       ),
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
            'id' => 'entity_type',
            'type' => 'dropdown',
            'options' => array(
                SOCIAL_ENTITY_HOTEL => 'Hotel',
                SOCIAL_ENTITY_LANDMARK => 'POI',
                SOCIAL_ENTITY_AIRPORT => 'Airport',
                SOCIAL_ENTITY_CITY => 'City',
//                SOCIAL_ENTITY_COUNTRY => 'Country',
//                SOCIAL_ENTITY_STATE => 'State',
                SOCIAL_ENTITY_DOWNTOWN => 'Downtown',
                SOCIAL_ENTITY_REGION => 'Region'
            ),
            'value' => !empty($entity_type) ? $entity_type : SOCIAL_ENTITY_HOTEL
        ),
        array(
            'class' => 'onoffswitch-checkbox',
            'id' => 'popular', 'label' => 'Popular',
            'value' => !empty($popular) ? $popular : '1',
            'checked' => !empty($popular) ? $popular : '0',
            'type' => 'checkbox'
        ),
        array(
            'id' => 'country_id', 'label' => 'Country',
            'value' => (!empty($entity_type) && $entity_type == SOCIAL_ENTITY_COUNTRY) ? $entity_id : ''
        ),
        array(
            'id' => 'state_id', 'label' => 'State',
            'value' => (!empty($entity_type) && $entity_type == SOCIAL_ENTITY_STATE) ? $entity_id : ''
        ),
        array(
            'id' => 'city_id', 'label' => 'City',
            'value' => (!empty($entity_type) && $entity_type == SOCIAL_ENTITY_CITY) ? $entity_id : ''
        ),
        array(
            'id' => 'hotel_id', 'label' => 'Hotel',
            'value' => (!empty($entity_type) && $entity_type == SOCIAL_ENTITY_HOTEL) ? $entity_id : ''
        ),
        array(
            'id' => 'poi_id', 'label' => 'POI',
            'value' => (!empty($entity_type) && $entity_type == SOCIAL_ENTITY_LANDMARK) ? $entity_id : ''
        ),
        array(
            'id' => 'airport_id', 'label' => 'Airport',
            'value' => (!empty($entity_type) && $entity_type == SOCIAL_ENTITY_AIRPORT) ? $entity_id : ''
        ),
        array(
            'id' => 'submit',
            'type' => 'submit'
        )
));
?>
<?= $this->form_builder->close_form(); ?>