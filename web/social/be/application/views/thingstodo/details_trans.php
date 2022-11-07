<?php 
$controller = $this->router->class;
$input_span = (isset($input_span)) ? $input_span . ' ' : ''; 
?>
<td colspan="1" style="width: 33%;<?php if(isset($bg)){?>background-color: <?=$bg?>;<?php } ?>">
    <?php echo form_open("$controller/lang_detailssubmit");?>
    <?php $data = array(
        'name' => 'lang',
        'value' => $key,
        'type'=>'hidden'
        );
        echo form_input($data);
    ?>
    <?php $data = array(
        'name' => 'id',
        'value' => $id,
        'type'=>'hidden'
        );
        echo form_input($data);
    ?>
    <div>
        <?php echo form_label('Title', $key.'_title'); ?>
        <?php $data = array(
                'id' => $key.'_title',
                'name' => $key.'_title',
                'class'=> $input_span . " required input-large",
                'value' => !empty($item['title']) ? $item['title'] : ''
            );
        echo form_input($data);
        ?>
    </div>
    <div>
        <?php echo form_label('Description', $key.'_description'); ?>
        <?php $data = array(
                'id' => $key.'_description',
                'name' => $key.'_description',
                'class'=> $input_span . " required input-large",
                'value' => !empty($item['description']) ? $item['description'] : ''
            );
        echo form_textarea($data);
        ?>
    </div>
    <div><?php echo form_submit('submit', 'Submit', 'class="btn btn-primary"');?> </div>
    <?php echo form_close();?>
</td>