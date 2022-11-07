<?php $controller = $this->router->class; ?>
<h1><?= $title?></h1>

<br/>
<?php
$date = strtotime("-6 day");
$startdate =  date('Y-m-d', $date);
$enddate = date ('Y-m-d');
?>
<input type="hidden" id="start_date" value="<?php echo $startdate;?>">
<input type="hidden" id="end_date" value="<?php echo $enddate;?>">
<div >
    <div id="reportrange" >
        <i class="fa fa-calendar fa-lg"></i>
        <span><?php echo date("F j, Y", strtotime('-6 days')); ?> - <?php echo date("F j, Y"); ?></span> <b class="caret"></b>
    </div>
    <img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/>
	
	<br/>
	<div class="activity_drop_down">
	<select name="search_type" id="search_type" class="form-control activity_drp">
	<option value="">Select Search Type</option>
	<?php foreach($searc_type as $searctype) {?>
	<option value="<?php echo $searctype?>"><?php echo $searctype?></option>
	<?php }?>
	</select>
	</div>
</div>

<br>
<div id="listContainer" style="margin-top:32px;">
<?php $this->load->view('log/ajax_search_logs') ?>
</div>
