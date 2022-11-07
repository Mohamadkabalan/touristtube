<?php $controller = $this->router->class;
 ?>
<h1><?= $title?></h1>

<br/>
<?php
$date = strtotime("-6 day");
$startdate =  date('Y-m-d', $date);
$enddate = date ('Y-m-d');
?>
<input type="hidden" id="user_id" value="<?php echo $user_id ?>">
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
	<select name="activity_type" id="activity_type" class="form-control activity_drp">
	<option value="">Select Activity Type</option>
	<?php foreach($activity_types as $code => $desc) {?>
	<option value="<?php echo $code?>"><?php echo $desc?></option>
	<?php }?>
	</select>
	</div>
<!--     
        Added By Anthony Malak 04-07-2015 to fix the filter by the Hash id added by the user
        <start>
-->
	<br/>
        <div class="hash_id_input">
            <span>Hash Id : </span>
            <input type="text" name="hash_id" id="hash_id" value="">
        </div>
<!--     
        Added By Anthony Malak 04-07-2015 to fix the filter by the Hash id added by the user
        <end>
-->

<!--    
        Code added By Sushma Mishra 26-08-2015 to add the filter by cms videos id starts from here
-->
	<br/>
        <div class="cmsvideo_id_input">
            <span>Video Id : </span>
            <input type="text" name="cmsvideo_id" id="cmsvideo_id" value="">
        </div>
<!--     
        Code added By Sushma Mishra 26-08-2015 to add the filter by cms videos id ends here
-->
</div>

<br>
<div id="listContainer" style="margin-top:32px;">
<?php $this->load->view('user/ajax_user_activities_details') ?>
</div>