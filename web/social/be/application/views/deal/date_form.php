<?php
$controller = $this->router->class;
if(isset($date)){
    $id = $date['id'];
    $from_date = date('m/d/Y', strtotime($date['from_date']));
    $to_date = date('m/d/Y', strtotime($date['to_date']));
    $deal_id = $date['deal_id'];
}
?>
<div class="title"><?=(isset($title) ? $title : '')?></div>
<br/>
<?php echo form_open("$controller/date_submit"); ?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('deal_id', $deal_id);?>
<div>
<div class="edit-sect-main">
        <label>start date</label>
        <div class="input-group date" id="tourFromDate">
            <?php echo form_input(array('name'=>'fromDate', 'value' => $from_date, 'class' => 'form-control'));?>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <div class="edit-sect-main">
        <label>end date</label>
        <div class="input-group date" id="tourToDate">
            <?php echo form_input(array('name'=>'toDate', 'value' => $to_date, 'class' => 'form-control'));?>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
</div>
<button type="submit" id="btnFilter">&raquo; Click to Submit</button>
<?php echo form_close();?>