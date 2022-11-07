<?php
$controller = $this->router->class;
if(isset($deal)){
    $id = $deal['id'];
    $name = $deal['name'];
    $subtitle = $deal['subtitle'];
    $summary_title = $deal['summary_title'];
    $summary = $deal['summary'];
    $description = $deal['description'];
    $terms_conditions = $deal['terms_conditions'];
    $optional_terms_conditions = $deal['optional_terms_conditions'];
    $deal_includes = $deal['deal_includes'];
    $deal_not_include = $deal['deal_not_include'];
    $tour_from_date = date('m/d/Y', strtotime($deal['tour_from_date']));
    $tour_to_date = date('m/d/Y', strtotime($deal['tour_to_date']));
    $nbr_days = $deal['n_days'];
    $tour_route = $deal['tour_route'];
    $price = $deal['price'];
    $currency_id = $deal['currency_id'];
    $hotel_summary_title = $deal['hotel_summary_title'];
    $hotel_summary = $deal['hotel_summary'];
    $highlights = $deal['highlights'];
    
}
?>
<div class="title"><?=(isset($title) ? $title : '')?></div>
<br/>
<?php echo form_open("$controller/submit"); ?>
<?php echo form_hidden('id', $id);?>
<div>
    <div class="edit-sect-main">
        <label>title</label>
        <?php echo form_input(array('name'=>'title', 'value' => $name));?>
    </div>
    <div class="edit-sect-main">
        <label>subtitle</label>
        <?php echo form_input(array('name'=>'subtitle', 'value' => $subtitle));?>
    </div>
    
    <div class="edit-sect-main">
        <label>number of days</label>
        <?php echo form_input(array('name'=>'nbrDays', 'value' => $nbr_days));?>
    </div>
    <div class="edit-sect-main">
        <label>tour route</label>
        <?php echo form_input(array('name'=>'tourRoute', 'value' => $tour_route));?>
    </div>
    <div class="edit-sect-main">
        <label>tour start date</label>
        <div class="input-group date" id="tourFromDate">
            <?php echo form_input(array('name'=>'tourFromDate', 'value' => $tour_from_date, 'class' => 'form-control'));?>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <div class="edit-sect-main">
        <label>tour end date</label>
        <div class="input-group date" id="tourToDate">
            <?php echo form_input(array('name'=>'tourToDate', 'value' => $tour_to_date, 'class' => 'form-control'));?>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <div class="edit-sect-main">
        <label>price</label>
        <?php echo form_input(array('name'=>'price', 'value' => $price));?>
    </div>
    <div class="edit-sect-main">
        <label>currency</label>
        <?php echo form_input(array('name'=>'currency', 'value' => $currency_id));?>
    </div>
</div>
<div class="summary">
    <div class="sect-title">summary :</div>
    <div class="edit-sect">
        <label>title</label>
        <?php echo form_input(array('name'=>'summaryTitle', 'value' => $summary_title));?>
    </div>
    <div class="edit-sect">
        <label>description</label>
        <?php echo form_textarea(array('name'=> 'summary','value'=> $summary));?>
    </div> 
</div>
<div>
    <div class="sect-title">terms and conditions :</div>
    <div class="edit-sect">
        <label>text</label>
        <?php echo form_textarea(array('name'=> 'termsConditions','value'=> $terms_conditions));?>
    </div> 
</div>
<div>
    <div class="sect-title">optional terms and conditions :</div>
    <div class="edit-sect">
        <label>text</label>
        <?php echo form_textarea(array('name'=> 'optionalTermsConditions','value'=> $optional_terms_conditions));?>
    </div> 
</div>
<div class="include">
    <div class="sect-title">this tour includes :</div>
    <div class="edit-sect">
        <label>text</label>
        <?php echo form_textarea(array('name'=> 'dealIncludes','value'=> $deal_includes));?>
    </div> 
</div>
<div class="no-include">
    <div class="sect-title">this tour doesn't include:</div>
    <div class="edit-sect">
        <label>text</label>
        <?php echo form_textarea(array('name'=> 'dealNotInclude','value'=> $deal_not_include));?>
    </div> 
</div>
<button type="submit">&raquo; Click to Submit</button>
<?php echo form_close();?>