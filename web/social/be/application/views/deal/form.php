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
    $hotel_summary_title = $deal['hotel_summary_title'];
    $hotel_summary = $deal['hotel_summary'];
    $highlights = $deal['highlights'];
    $category = $deal['category'];
    $min_days = $deal['min_days'];
    $max_days = $deal['max_days'];
    $thumb = $deal['thumb'];
    $country_code = $deal['target_population'];
    $dealer_name = $deal['dealer_id'];
    
}
$deal_categories = array();
 $deal_categories['']='Please select';
foreach($categories as $deal_category){
    $deal_categories[$deal_category] = $deal_category;
}

$deal_dealers = array();
$deal_dealers['']='Please select';
foreach($dealers as $deal_dealer){
    $deal_dealers[$deal_dealer['id']] = $deal_dealer['fname'] .' '. $deal_dealer['lname'];
}

?>
<div class="title"><?=(isset($title) ? $title : '')?></div>
<?php echo form_open("$controller/submit"); ?>
<?php echo form_hidden('id', $id);?>
<div>
    <div class="edit-sect-main">
        <label><em class="require">*</em> title</label>
        <?php echo form_input(array('name'=>'title', 'value' => !empty($name)? $name : set_value('title')));?>
         <?php echo form_error('title');?>
    </div>
    <div class="edit-sect-main">
        <label><em class="require">*</em>subtitle</label>
        <?php echo form_input(array('name'=>'subtitle', 'value' => !empty($subtitle) ? $subtitle : set_value('subtitle')));?>
         <?php echo form_error('subtitle');?>
    </div>
    <div class="edit-sect-main">
        <label><em class="require">*</em>tour start date</label>
        <div class="input-group date" id="tourFromDate">
            <?php echo form_input(array('name'=>'tourFromDate', 'value' => !empty($tour_from_date) ? $tour_from_date : set_value('tourFromDate'), 'class' => 'form-control'));?>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
         <?php echo form_error('tourFromDate');?>
    </div>
    <div class="edit-sect-main">
        <label><em class="require">*</em>tour end date</label>
        <div class="input-group date" id="tourToDate">
            <?php echo form_input(array('name'=>'tourToDate', 'value' => !empty($tour_to_date) ? $tour_to_date : set_value('tourToDate'), 'class' => 'form-control'));?>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
         <?php echo form_error('tourToDate');?>
    </div>
    <div class="edit-sect-main">
        <label>Minimum days</label>
        <div>
            <?php echo form_input(array('name'=>'minDays', 'value' => !empty($min_days) ? $min_days : set_value('minDays')));?>
        </div>
        <?php echo form_error('minDays');?>
    </div>
    <div class="edit-sect-main">
        <label><em class="require">*</em>Maximum days</label>
        <div>
        <?php echo form_input(array('name'=>'maxDays', 'value' => !empty($max_days) ? $max_days : set_value('maxDays')));?>
        </div>
        <?php echo form_error('maxDays');?>
         
    </div>
    
    <div class="edit-sect-main">
        <label><em class="require">*</em>category</label>
        <?php
            $cat = !empty($category)?$category : set_value('category');
            echo form_dropdown('category', $deal_categories, $cat); 
        ?>
        <?php echo form_error('category');?>
    </div>
    <?php if($role == 1){?>
    <div class="edit-sect-main">
        <label><em class="require">*</em>Dealer</label>
        <?php
            $dealer_id = !empty($dealer_name)?$dealer_name : set_value('dealer');
            echo form_dropdown('dealer', $deal_dealers, $dealer_id); 
        ?>
        <?php echo form_error('dealer');?>
    </div>
    <?php } ?>
     <div class="edit-sect-main" style="width:50%;">
         <label><em class="require">*</em>Target Population</label>
       <?php echo form_input(array('name'=>'country_code', 'value' => !empty($country_code) ?  $country_code : set_value('country_code')));?>
        <?php echo form_error('country_code');?>
    </div>
</div>

<div class="summary">
    <div class="sect-title">Deal:</div>
    <div class="edit-sect">
        <label>title</label>
        <?php echo form_input(array('name'=>'summaryTitle', 'value' => !empty($summary_title) ? $summary_title : set_value('summaryTitle') ));?>
        <?php echo form_error('summaryTitle');?>
    </div>
    <div class="edit-sect">
        <label>description</label>
        <?php echo form_textarea(array('name'=> 'summary','value'=> !empty($summary) ? $summary : set_value('summary')));?>
    </div> 
</div>
<div class="summary">
    <div class="sect-title">Hotel:</div>
    <div class="edit-sect">
        <label>title</label>
        <?php echo form_input(array('name'=>'hotelSummaryTitle', 'value' => !empty($hotel_summary_title) ? $hotel_summary_title : set_value('hotelSummaryTitle')));?>
    </div>
    <div class="edit-sect">
        <label>description</label>
        <?php echo form_textarea(array('name'=> 'hotelSummary','value'=> !empty($hotel_summary) ? $hotel_summary : set_value('hotelSummary')));?>
    </div> 
</div>
<div>
    <div class="sect-title">terms and conditions :</div>
    <div class="edit-sect">
        <label>text</label>
        <?php echo form_textarea(array('name'=> 'termsConditions','value'=> !empty($terms_conditions) ? $terms_conditions : set_value('termsConditions')));?>
    </div> 
</div>
<div>
    <div class="sect-title">highlights :</div>
    <div class="edit-sect">
        <label>text</label>
        <?php echo form_textarea(array('name'=> 'highlights','value'=> !empty($highlights) ? $highlights : set_value('highlights')));?>
    </div> 
</div>
<div class="include">
    <div class="sect-title">Includes :</div>
    <div class="edit-sect">
        <label>text</label>
        <?php echo form_textarea(array('name'=> 'dealIncludes','value'=> !empty($deal_includes) ?  $deal_includes : set_value('dealIncludes')));?>
    </div> 
</div>
<div class="no-include">
    <div class="sect-title">Excludes:</div>
    <div class="edit-sect">
        <label>text</label>
        <?php echo form_textarea(array('name'=> 'dealNotInclude','value'=> !empty($deal_not_include) ?  $deal_not_include : set_value('dealNotInclude')));?>
    </div> 
</div>

<button type="submit">&raquo; Click to Submit</button>
<?php echo form_close();?>