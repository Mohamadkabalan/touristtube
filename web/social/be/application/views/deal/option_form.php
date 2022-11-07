<?php
$controller = $this->router->class;
if(isset($option)){
    $id = $option['id'];
    $option_title = $option['title'];
    $description = $option['description'];
    $price = $option['price'];
    $currency_id = $option['currency_id'];
    $availability = $option['availability'];
    $nb_persons = $option['nb_persons'];
    $deal_id = $option['deal_id'];
}
?>
<div class="title"><?=(isset($title) ? $title : '')?></div>
<br/>
<?php echo form_open_multipart("$controller/option_submit"); ?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('deal_id', $deal_id);?>
<div class="row">
    <div class="room">
        <label><em class="require">*</em>title</label>
        <?php echo form_input(array('name'=>'title', 'value' => !empty($option_title) ? $option_title : set_value('title'), 'class' => 'room-name'));?>
        <?php echo form_error('title');?>
    </div>
    <div class="room-price">
        <label><em class="require">*</em>price</label>
        <?php echo form_input(array('name'=>'price', 'value' => !empty($price) ? $price : set_value('price'), 'class' => 'price'));?>
        <?php echo form_error('price');?>
    </div>
    <div class="currency-cont">
        <label><em class="require">*</em>currency</label>
        <?php echo form_input(array('name'=>'currency', 'value' => !empty($currency_id) ? $currency_id : set_value('currency')));?>
        <?php echo form_error('currency');?>
    </div>
    <div>
        <label>availability</label>
        <?php echo form_input(array('name'=>'availability', 'value' => !empty($availability) ? $availability : set_value('availability'), 'class' => 'available'));?>
         <?php echo form_error('availability');?>
    </div>
     <div>
        <label><em class="require">*</em>nb persons</label>
        <?php echo form_input(array('name'=>'nb_persons', 'value' => !empty($nb_persons) ? $nb_persons : set_value('nb_persons'), 'class' => 'available'));?>
         <?php echo form_error('nb_persons');?>
     </div>
</div>
<div class="row">
    <div class="room-description">
        <label><em class="require">*</em>description</label>
        <?php echo form_textarea(array('name'=>'description', 'value' => !empty($description) ? $description : set_value('description') ));?>
         <?php echo form_error('description');?>
    </div>
</div>

<button type="submit">&raquo; Click to Submit</button>
<?php echo form_close();?>