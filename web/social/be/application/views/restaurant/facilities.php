
<div style="width: 45%; float: left;">
<h1><?= $title?></h1>
<table class="table">
    <tr>
          <th>#</th>
          <th>Name</th>
        </tr>
    <?php foreach($facilities as $f){ ?>
        <tr>
            <td><?= $f->id;?></td>    
            <td><div class="fedit" id="f_<?= $f->id;?>"><?= $f->title;?></div></td>    
        </tr> 
  <?php  } ?>
</table>
</div>
<div style="width: 45%; float: right;">
<h1>New facility</h1>
<?php echo validation_errors(); ?>
<?php echo $this->form_builder->open_form(array('action' => 'hotel/facility_new')); ?>     
<?php
$pref = (isset($prefix)) ? $prefix : '';
$group_sufx = (isset($prefix) && !empty($prefix)) ? str_replace('_', '', $prefix) : uniqid();
$input_span = (isset($input_span)) ? $input_span . ' ' : '';

echo $this->form_builder->build_form_horizontal(
     array(
       array(
        'id' => 'title',
        'placeholder' => '',
        'label'=>'Title',
        'class'=>$input_span." required",
        'value' => ''
    ),

    array(
        'id' => 'submit',
        'type' => 'submit'
    )
        ));
?>
<?= $this->form_builder->close_form(); ?>

</div>