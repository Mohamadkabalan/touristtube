<?php
$controller = $this->router->class;
$this->load->helper('discover_hotels_feature_type');
?>
<div style="width: 45%; float: left;">
    <h1><?= $title ?></h1>
    <table class="table">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Type</th>
            <th>Amenity level</th>
        </tr>
        <?php
        foreach ($facilities as $f) {
            $type_title = get_facility_type_title($f['type_id']);
            
            ?>
            <tr>
                <td><?= $f['id']; ?></td>    
                <td><div class="fedit" id="f_<?= $f['id']; ?>"><?= $f['name']; ?></div></td>
                <td>
                    <div class="ftedit" id="ft_<?= $f['type_id']; ?>">
                        <span id="f_<?= $f['id']; ?>" class="dropdown_text"><?= $type_title; ?></span>
                        <div id="f_<?= $f['id']; ?>" class="select_box" style="display:none;">
                            <select id="f_<?= $f['id']; ?>" class="valid" name="type_id">
                                <?php
                                 foreach ($facility_types as $t){
                                     $selected = ($t['id'] == $f['type_id']) ? ' selected="selected"' : '';
                                ?>
                                    <option value="<?php echo $t['id']; ?>"<?php echo $selected; ?> ><?php echo get_facility_type_title($t['id']); ?></option>
                                <?php } ?>    
                            </select>
                            <a id="Type_Save" href="#">Save</a> | <a id="Type_Cancel" href="#">Cancel</a><img id="imgSaving" style="display: none" src="/media/images/ajax-loader.gif">
                        </div>
                    </div>
                </td>
                <td>
                    <div class="fledit" id="fl_<?= $f['amenity_level']; ?>">
                        <span id="f_<?= $f['id']; ?>" class="dropdown_text"><?= $f['amenity_level']; ?></span>
                        <div id="f_<?= $f['id']; ?>" class="select_box" style="display:none;">
                            <select id="f_<?= $f['id']; ?>" class="valid" name="amenity_level">
                                <option value="0"<?php if($f['amenity_level'] == '0') { echo 'selected="selected"'; } ?> >0</option>
                                <option value="1"<?php if($f['amenity_level'] == '1') { echo 'selected="selected"'; } ?> >1</option>
                                <option value="2"<?php if($f['amenity_level'] == '2') { echo 'selected="selected"'; } ?> >2</option>
                                <option value="3"<?php if($f['amenity_level'] == '3') { echo 'selected="selected"'; } ?> >3</option>
                            </select>
                            <a id="Level_Save" href="#">Save</a> | <a id="Level_Cancel" href="#">Cancel</a><img id="imgSaving" style="display: none" src="/media/images/ajax-loader.gif">
                        </div>
                    </div>
                </td>
                <td><?= $user_data['role'] != 'hotel_desc_writer' ? anchor("$controller/ajax_delete_facility/".$f['id'], "Delete", array('class'=>'deleteAct')) : '';?></td>
            </tr> 
        <?php } ?>
    </table>
</div>
<div style="width: 45%; float: right;">
    <h1>New facility</h1>
    <?php echo validation_errors(); ?>
    <?php echo $this->form_builder->open_form(array('action' => "$controller/facility_new")); ?>     
    <?php
    $pref = (isset($prefix)) ? $prefix : '';
    $group_sufx = (isset($prefix) && !empty($prefix)) ? str_replace('_', '', $prefix) : uniqid();
    $input_span = (isset($input_span)) ? $input_span . ' ' : '';

    foreach ($facility_types as $t){
        $options[$t['id']] = $t['name'];
    }
    $drop = array(
            'id' => 'type',
            'type' => 'dropdown',
            'options' => $options,
        );
    
    echo $this->form_builder->build_form_horizontal(
            array(
                array(
                    'id' => 'name',
                    'placeholder' => '',
                    'label' => 'Name',
                    'class' => $input_span . " required",
                    'value' => ''
                ),
                $drop,
                array(
                    'id' => 'submit',
                    'type' => 'submit'
                )
    ));
    ?>
    <?= $this->form_builder->close_form(); ?>

</div>

<div style="width: 45%; float: right;">
    <h1>New facility type</h1>
    <?php echo validation_errors(); ?>
    <?php echo $this->form_builder->open_form(array('action' => "$controller/facility_type_new")); ?>     
    <?php
    echo $this->form_builder->build_form_horizontal(
            array(
                array(
                    'id' => 'name',
                    'placeholder' => '',
                    'label' => 'Name',
                    'class' => $input_span . " required",
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