<?php 
    $controller = $this->router->class; 
    $this->load->helper('ml_poi_categories');
    $add_btn_template = '<a class="fadd" href="#">Add</a>';
?>

<div style="width: 50%; height:150px;">
<h1>New category</h1>
<?php echo validation_errors(); ?>
<?php echo $this->form_builder->open_form(array('action' => "$controller/category_new")); ?>     
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

<div style="width: 100%;">
<h1><?= $title?></h1>
<table class="table">
    <tr>
        <th>#</th>
        <th>EN</th>
        <th>IN</th>
        <th>FR</th>
        <th>ZH</th>
        <th>ES</th>
        <th>PT</th>
        <th>IT</th>
        <th>DE</th>
        <th>TL</th>
    </tr>
    <?php foreach($categories as $c){ ?>
        <tr>
            <td><?= $c['id'];?></td>    
            <td><div class="fedit" id="c_<?= $c['id'];?>"><?= $c['title'];?></div></td>
            <td>
                <div class="in_edit" id="in_<?= $c['id'];?>">
                    <?php $hi_res = get_ml_report_reason_title($c['id'], 'in');
                          echo !empty($hi_res) ? trim($hi_res) : $add_btn_template;
                    ?>
                </div>
            </td>
            <td>
                <div class="fr_edit" id="fr_<?= $c['id'];?>">
                    <?php $fr_res = get_ml_report_reason_title($c['id'], 'fr');
                          echo !empty($fr_res) ? trim($fr_res) : $add_btn_template;
                        ?>
                </div>
            </td>
            <td>
                <div class="zh_edit" id="zh_<?= $c['id'];?>">
                    <?php $zh_rs = get_ml_report_reason_title($c['id'], 'zh'); 
                          echo !empty($zh_rs) ? trim($zh_rs) : $add_btn_template;   
                    ?>
                </div>
            </td>
            <td>
                <div class="es_edit" id="es_<?= $c['id'];?>">
                    <?php $es_res = get_ml_report_reason_title($c['id'], 'es');
                           echo !empty($es_res) ? trim($zh_rs) : $add_btn_template;     
                    ?>
                    
                </div>
            </td>
            <td>
                <div class="pt_edit" id="pt_<?= $c['id'];?>">
                    <?php $pt_res = get_ml_report_reason_title($c['id'], 'pt'); 
                          echo !empty($pt_res) ? trim($pt_res) : $add_btn_template;     
                    ?>
                </div>
            </td>
            <td>
                <div class="it_edit" id="it_<?= $c['id'];?>">
                    <?php $it_res = get_ml_report_reason_title($c['id'], 'it'); 
                          echo !empty($it_res) ? trim($it_res) : $add_btn_template;       
                    ?>
                </div>
            </td>
            <td>
                <div class="de_edit" id="de_<?= $c['id'];?>">
                    <?php $de_res = get_ml_report_reason_title($c['id'], 'de'); 
                          echo !empty($de_res) ? trim($de_res) : $add_btn_template;    
                    ?>
                </div>
            </td>
            <td>
                <div class="tl_edit" id="tl_<?= $c['id'];?>">
                    <?php $tl_res = get_ml_report_reason_title($c['id'], 'tl'); 
                          echo !empty($tl_res) ? trim($tl_res) : $add_btn_template;      
                    ?>
                </div>
            </td>
        </tr> 
  <?php  } ?>
</table>
</div>
