<?php 
    $controller = $this->router->class;
    $this->load->helper('ml_report_reason');
    $add_btn_template = '<a class="fadd" href="#">Add</a>';
?>

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
    <?php foreach($reason as $r){  ?>
        <tr>
            <td><?= $r['id'];?></td>    
            <td><div class="fedit" id="c_<?= $r['id'];?>"><?= $r['reason'];?></div></td>
            <td>
                <div class="in_edit" id="in_<?= $r['id'];?>">
                    <?php $hi_res = get_ml_report_reason_title($r['id'], 'in');
                          echo !empty($hi_res) ? trim($hi_res) : $add_btn_template;
                    ?>
                </div>
            </td>
            <td>
                <div class="fr_edit" id="fr_<?= $r['id'];?>">
                    <?php $fr_res = get_ml_report_reason_title($r['id'], 'fr');
                          echo !empty($fr_res) ? trim($fr_res) : $add_btn_template;
                        ?>
                </div>
            </td>
            <td>
                <div class="zh_edit" id="zh_<?= $r['id'];?>">
                    <?php $zh_rs = get_ml_report_reason_title($r['id'], 'zh'); 
                          echo !empty($zh_rs) ? trim($zh_rs) : $add_btn_template;   
                    ?>
                </div>
            </td>
            <td>
                <div class="es_edit" id="es_<?= $r['id'];?>">
                    <?php $es_res = get_ml_report_reason_title($r['id'], 'es');
                           echo !empty($es_res) ? trim($es_res) : $add_btn_template;     
                    ?>
                    
                </div>
            </td>
            <td>
                <div class="pt_edit" id="pt_<?= $r['id'];?>">
                    <?php $pt_res = get_ml_report_reason_title($r['id'], 'pt'); 
                          echo !empty($pt_res) ? trim($pt_res) : $add_btn_template;     
                    ?>
                </div>
            </td>
            <td>
                <div class="it_edit" id="it_<?= $r['id'];?>">
                    <?php $it_res = get_ml_report_reason_title($r['id'], 'it'); 
                          echo !empty($it_res) ? trim($it_res) : $add_btn_template;       
                    ?>
                </div>
            </td>
            <td>
                <div class="de_edit" id="de_<?= $r['id'];?>">
                    <?php $de_res = get_ml_report_reason_title($r['id'], 'de'); 
                          echo !empty($de_res) ? trim($de_res) : $add_btn_template;    
                    ?>
                </div>
            </td>
            <td>
                <div class="tl_edit" id="tl_<?= $r['id'];?>">
                    <?php $tl_res = get_ml_report_reason_title($r['id'], 'tl'); 
                          echo !empty($tl_res) ? trim($tl_res) : $add_btn_template;      
                    ?>
                </div>
            </td>
        </tr> 
  <?php  
         }
    ?>
</table>
</div>