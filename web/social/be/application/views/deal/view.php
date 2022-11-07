<?php 
$controller = $this->router->class;
?>
<div class="title">More info</div>
<br/>

<div>
    <div class="info-nb">1</div>
    <div class="info-desp">deal</div>
    <div class="edit-deal"><a href="<?php echo "$controller/edit/".$deal['id']; ?>">edit</a></div>
</div>
<br/>
<table class="table first">
<tr>
<th>Title</th>
<td><?php echo $deal['name']; ?></td>
</tr>
<tr>
<th>Subtitle</th>    
<td><?php echo $deal['subtitle']; ?></td>
</tr> 
<tr>
<th>Minimum days</th>    
<td><?php echo $deal['min_days']; ?></td>
</tr> 
<tr>
<th>Maximum days</th>    
<td><?php echo $deal['max_days']; ?></td>
</tr> 
<tr>
<th>Category</th>    
<td><?php echo $deal['category']; ?></td>
</tr> 
<tr>
<th>Highlights</th>
<td><?php echo $deal['highlights']; ?></td>
</tr>
<tr>
<th>Start date</th>    
<td><?php echo date('m/d/Y', strtotime($deal['tour_from_date'])); ?></td>
</tr> 
<tr>
<th>End date</th>    
<td><?php echo date('m/d/Y', strtotime($deal['tour_to_date'])); ?></td>
</tr> 
<tr>
<th>Deal title</th>    
<td><?php echo $deal['summary_title']; ?></td>
</tr> 
<tr>
<th>Details</th>    
<td>
    <?php //echo nl2br($deal['summary']); 
        foreach($details as $detail){
            echo "<strong>".$detail['title']."</strong>";
            echo "<br>";
            echo $detail['description'];
            echo "<br><br>";
        }
    ?>
</td>
</tr> 

<tr>
<th>Hotel title</th>    
<td><?php echo $deal['hotel_summary_title']; ?></td>
</tr> 
<tr>
<th>Hotel description</th>    
<td><?php echo nl2br($deal['hotel_summary']); ?></td>
</tr> 

<tr>
<th>Terms and conditions</th>    
<td><?php echo nl2br($deal['terms_conditions']); ?></td>
</tr>
<tr>
<th>Includes</th>    
<td><?php echo nl2br($deal['deal_includes']); ?></td>
</tr> 
<tr>
<th>Excludes</th>    
<td><?php echo nl2br($deal['deal_not_include']); ?></td>
</tr> 
<tr class='last'>
<th>Thumb</th>    
<td>
     <div class="add-deal">
        <form method="post" action="" id="upload_deal_thumb_img" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="<?= $deal['id'] ?>" />
            <input style="padding-left: 27px;float:left;" type="file" name="thumb" id="thumb" size="20" />
            <input type="submit" name="submit" value="submit"/>
        </form>    
    </div>
    <img src="../media/deals/<?php echo $deal['thumb']?>" id="thumb_image" style="border:2px solid; <?php if(!$deal['thumb']) { echo 'display:none;'; } ?>"> </td>
</tr> 
                                      
</table>

<div class="deal-sect">
    <div class="info-nb">2</div>
    <div class="info-desp">Details</div>
    <div class="add-deal"><?= anchor("$controller/detail_add/".$deal['id'], 'add');?></div>
</div>
<table class="table">
<tr>
<th>Title</th>
<th>Description</th>
<th>Start Time</th>
<th>End Time</th>
<th>Breakfast Include</th>
<th>Lunch Include</th>
<th>Dinner Include</th>
<th></th>
</tr>
<?php foreach($details as $detail){?>
<tr>
    <td><?php echo $detail['title'];?></td>    
<td><?php echo $detail['description'];?></td>
<td><?php echo date('g:i a', strtotime($detail['start_time']));?></td>
<td><?php echo date('g:i a', strtotime($detail['end_time'])); ?></td>
<td><?php if($detail['breakfast_include'] == 1) { echo "Yes";} else { echo "No";}?></td>
<td><?php if($detail['lunch_include'] == 1) { echo "Yes";} else { echo "No";}?></td>
<td><?php if($detail['dinner_include'] == 1) { echo "Yes";} else { echo "No";}?></td>
<td class="link"><?= anchor("$controller/detail_edit/".$detail['id'], 'Edit');?></td>    
<td class="link"><?= anchor("$controller/ajax_delete_detail/".$detail['id'], "Delete", array('class'=>'deleteAct'));?></td> 
</tr>
<?php } ?>
</table>

<div class="deal-sect">
    <div class="info-nb">3</div>
    <div class="info-desp">destinations list</div>
    <div class="add-deal"><?= anchor("$controller/destination_add/".$deal['id'], 'add');?></div>
</div>
<table class="table">
<tr>
<th>destination's name</th>
<th>latitude</th>
<th>longitude</th>
<th>Country</th>
<th></th>
</tr>
<?php foreach($destinations as $destination){?>
<tr>
<td><?php echo $destination['name'];?></td>    
<td><?php echo $destination['latitude'];?></td>
<td><?php echo $destination['longitude'];?></td>
<td><?php echo $destination['country_name'];?></td>
<td class="link"><?= anchor("$controller/destination_edit/".$destination['id'], 'Edit');?></td>    
<td class="link"><?= anchor("$controller/ajax_delete_destination/".$destination['id'], "Delete", array('class'=>'deleteAct'));?></td> 
</tr>
<?php } ?>
</table>



<div class="deal-sect">
    <div class="info-nb">4</div>
    <div class="info-desp">Options</div>
    <div class="add-deal"><?= anchor("$controller/option_add/".$deal['id'], 'add');?></div>
</div>
<table class="table">
<tr>
<th>title</th>
<th>description</th>
<th>availability</th>
<th>price</th>
<th>currency</th>
<th>nb persons</th>
<th></th>
<th></th>
</tr>
<?php foreach($options as $option){
    $pc = new Currency_Model();
    $pc->where('id', $option['currency_id'])->get();
    $price_currency = $pc->name;
?>
<tr>
<td><?php echo $option['title'];?></td>
<td><?php echo $option['description'];?></td>
<td><?php echo $option['availability'];?></td>
<td><?php echo $option['price'];?></td>
<td><?php echo $price_currency;?></td>
<td><?php echo $option['nb_persons'];?></td>
<td class="link"><?= anchor("$controller/option_edit/".$option['id'], 'Edit');?></td>    
<td class="link"><?= anchor("$controller/ajax_delete_option/".$option['id'], "Delete", array('class'=>'deleteAct'));?></td> 
</tr>
<?php } ?>
</table>

<div class="deal-sect">
    <div class="info-nb">5</div>
    <div class="info-desp">Images</div>
    <div class="add-deal">
        <form method="post" action="" id="upload_deal_img">
            <input type="hidden" name="id" id="id" value="<?= $deal['id']?>" />
            <input style="padding-left: 27px;float:left;" type="file" name="userfile[]" id="userfile" size="20" multiple />
            <input type="submit" name="submit" />
        </form>
    </div>
</div>
<div id="deal_files" class="images-container">
    <?php $this->load->view("deal/files") ?>
</div>