<?php
$controller = $this->router->class;
if(isset($hotel_search)){
    $id = $hotel_search['id'];
    $name = $hotel_search['name'];
    $keyword = $hotel_search['keyword'];
}
?>
<h1><?=(isset($title) ? $title : '')?></h1>

<?php echo validation_errors(); ?>
<?php echo $this->form_builder->open_form(array('id' => 'hotel_search_form', 'action' => "$controller/hotel_search_submit")); ?>     
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
        ),array(
            'id' => 'keyword',
            'placeholder' => 'Keyword',
            'class'=>$input_span." required",
            'value' => !empty($keyword) ? $keyword : ''
        ),
        array(
            'id' => 'submit',
            'type' => 'submit'
        )
));
?>
<?= $this->form_builder->close_form(); ?>