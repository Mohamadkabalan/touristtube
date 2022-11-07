<?php $controller = $this->router->class; ?>
<div style="width: 45%; float: left;">
    <h1><?= $title ?></h1>
    <table class="table">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Has Count</th>
        </tr>
        <?php foreach ($amenities as $amenity) { ?>
            <tr>
                <td><?= $amenity['id']; ?></td>    
                <td><div class="fedit" id="a_<?= $amenity['id']; ?>"><?= $amenity['name']; ?></div></td> 
                <td>
                    <div class="hcedit" id="hc_<?= $amenity['id']; ?>" class="hc_check">
                        <span class="check_text" data-hascount="<?= $amenity['has_count'] ?>" id="a_<?= $amenity['id']; ?>"><?= $amenity['has_count'] ? 'Yes' : 'No' ?></span>
                        <div id="a_<?= $amenity['id']; ?>" class="check_box" style="display:none;">
                            <input type="checkbox" name="has_count" id="a_<?= $amenity['id']; ?>">
                            <a id="Hc_Save" href="#">Save</a> | <a id="Hc_Cancel" href="#">Cancel</a><img id="imgSaving" style="display: none" src="/media/images/ajax-loader.gif">
                        </div>
                    </div>
                </td>
            </tr> 
        <?php } ?>
    </table>
</div>
<div style="width: 45%; float: right;">
    <h1>New amenity</h1>
    <?php echo validation_errors(); ?>
    <?php echo $this->form_builder->open_form(array('action' => "$controller/amenity_new")); ?>     
    <?php
    $pref = (isset($prefix)) ? $prefix : '';
    $group_sufx = (isset($prefix) && !empty($prefix)) ? str_replace('_', '', $prefix) : uniqid();
    $input_span = (isset($input_span)) ? $input_span . ' ' : '';

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
                'id' => 'has_count',
                'value' => '0',
                'checked' => '0',
                'type' => 'checkbox'
              ),
                array(
                    'id' => 'submit',
                    'type' => 'submit'
                )
    ));
    ?>
    <?= $this->form_builder->close_form(); ?>

</div>