<?php
$controller = $this->router->class;
if(isset($detail)){
    $id = $detail['id'];
    $dtitle = $detail['title'];
    $description = $detail['description'];
    $starttime = date('g:i a', strtotime($detail['start_time']));
    $endtime = date('g:i a', strtotime($detail['end_time']));
    $breakfast = $detail['breakfast_include'];
    $lunch = $detail['lunch_include'];
    $dinner = $detail['dinner_include'];
    $deal_id = $detail['deal_id'];
}
?>
<div class="title"><?=(isset($title) ? $title : '')?></div>
<br/>
<?php echo form_open("$controller/detail_submit"); ?>
<?php echo form_hidden('id', $id);?>
<?php echo form_hidden('deal_id', $deal_id);?>
    
        <label><em class="require">*</em>title</label>
        <?php echo form_input(array('name'=>'title', 'id' => 'title', 'value' => !empty($dtitle) ? $dtitle : set_value('title')));?>
         <?php echo form_error('title');?>
  
        <label><em class="require">*</em>description</label>
        <?php echo form_textarea(array('name'=>'description', 'id' => 'description', 'value' => !empty($description) ? $description : set_value('description')));?>
         <?php echo form_error('description');?>
  
        <label><em class="require">*</em>Start Time</label>
        <div class='input-group date' id='datetimepicker1'>
                    <?php echo form_input(array('name'=>'starttime', 'id' => 'starttime', 'class'=>'form-control','value' => !empty($starttime) ? $starttime : set_value('starttime')));?>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
        </div>
            <?php echo form_error('starttime');?>
        
        <label><em class="require">*</em>End Time</label>
         <div class='input-group date' id='datetimepicker2'>
                    <?php echo form_input(array('name'=>'endtime', 'id' => 'endtime', 'value' => !empty($endtime) ? $endtime : set_value('endtime'),'class'=>'form-control'));?>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
        </div>
         <?php echo form_error('endtime');?>
            
        <label>Breakfast Included</label>
        <?php $bf_value = set_value('breakfast');
                if($breakfast == 1 || $bf_value == 1) { 
                    echo form_checkbox('breakfast','1', TRUE,"class='form-control'"); 
                    
                } else { 
                    echo form_checkbox('breakfast','1', FALSE,"class='form-control'"); 
                    
                }
        ?>
         <?php echo form_error('breakfast');?>
    
   
        <label>Lunch Included</label>
        <?php $lunch_value = set_value('lunch');
                if($lunch == 1 || $lunch_value == 1) { 
                    echo form_checkbox('lunch','1', TRUE,"class='form-control'");
                }else{
                    echo form_checkbox('lunch','1', FALSE,"class='form-control'");
                }
        ?>
         <?php echo form_error('lunch');?>
    
   
         <label>Dinner Included</label>
        <?php $dinner_value = set_value('dinner');
                if($dinner == 1 || $dinner_value == 1) { 
                    echo form_checkbox('dinner','1' ,TRUE,"class='form-control'");
                }else{
                    echo form_checkbox('dinner','1' ,FALSE,"class='form-control'");
                }
        ?>
        <?php echo form_error('dinner');?>
         <br>
<button type="submit" id="btnFilter">&raquo; Click to Submit</button>
<?php echo form_close();?>