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
        </tr>
        <?php
        foreach ($facilities as $f) {
            $type_title = get_discover_hotels_feature_type_title($f['feature_type']);
            ?>
            <tr>
                <td><?= $f['id']; ?></td>    
                <td><div class="fedit" id="f_<?= $f['id']; ?>"><?= $f['title']; ?></div></td>
                <td>
                    <div class="ftedit" id="ft_<?= $f['feature_type']; ?>"><span id="f_<?= $f['id']; ?>" class="dropdown_text"><?= $type_title; ?></span>
                        <div id="f_<?= $f['id']; ?>" class="select_box" style="display:none;">
                            <select id="f_<?= $f['id']; ?>" class="valid" name="feature_type">
                                <?php
                                 foreach ($feature_type as $t){
                                     $selected = ($t['id'] == $f['feature_type']) ? ' selected="selected"' : '';
                                ?>
                                    <option value="<?php echo $t['id']; ?>"<?php echo $selected; ?> ><?php echo get_discover_hotels_feature_type_title($t['id']); ?></option>
                                <?php } ?>    
                            </select>
                            <a id="Type_Save" href="#">Save</a> | <a id="Type_Cancel" href="#">Cancel</a><img id="imgSaving" style="display: none" src="/media/images/ajax-loader.gif">
                        </div>
                    </div>
                </td>
            </tr> 
        <?php } ?>
    </table>
</div>
<div style="width: 45%; float: right;">
    <h1>New feature</h1>
    <?php echo validation_errors(); ?>
    <?php echo $this->form_builder->open_form(array('action' => "$controller/feature_new")); ?>     
    <?php
    $pref = (isset($prefix)) ? $prefix : '';
    $group_sufx = (isset($prefix) && !empty($prefix)) ? str_replace('_', '', $prefix) : uniqid();
    $input_span = (isset($input_span)) ? $input_span . ' ' : '';

    foreach ($feature_type as $t){
        $options[$t['id']] = $t['title'];
    }
    $drop = array(
            'id' => 'type',
            'type' => 'dropdown',
            'options' => $options,
        );
    
    echo $this->form_builder->build_form_horizontal(
            array(
                array(
                    'id' => 'title',
                    'placeholder' => '',
                    'label' => 'Title',
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