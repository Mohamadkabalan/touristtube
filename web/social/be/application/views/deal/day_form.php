<?php
$controller = $this->router->class;
if(isset($day)){
    $id = $day['id'];
    $name = $day['name'];
    $day_title = $day['title'];
    $description = $day['description'];
    $deal_id = $day['deal_id'];
}
?>
<div class="title"><?=(isset($title) ? $title : '')?></div>
<br/>
<?php echo form_open("$controller/day_submit"); ?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('deal_id', $deal_id)?>
<div class="day-sect">
    <label>day</label>
    <?php echo form_input(array('name'=>'name', 'value' => $name));?>
    <label>title</label>
    <?php echo form_input(array('name'=>'title', 'value' => $day_title));?>
    <label>description</label>
    <?php echo form_textarea(array('name'=> 'description','value'=> $description));?>
    
</div>
<div class="day-photos">
    <div>
    <div class="photo-label">upload photos<br/>(maximun 6 photos)</div>
    <div class="add-deal"><a href="#">add photo</a></div>
 </div>
    <div>
        <img src="/media/images/detailed-restaurant-page.jpg" />
        <img src="/media/images/detailed-restaurant-page.jpg" />
        <img src="/media/images/detailed-restaurant-page.jpg" />
        <img src="/media/images/detailed-restaurant-page.jpg" />
    </div>
</div>

<button type="submit" id="btnFilter">&raquo; Click to Submit</button>

<?php echo form_close();?>