<?php 
    $controller = $this->router->class;
    //$this->load->helper('cms_report_entity_subject');        
?>
<h1><?= $title?>   </h1>
<br />
<p><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<br />
<div id="listContainer">
    <table class="table">
        <tr>
            <th>#</th>
            <th>User Name</th>
            <th>IP Address</th>
            <th>Forwarded IP Address</th>
            <th>User Agent</th>
            <th>Date</th>
        </tr>
        <?php foreach($logs as $report){  //print_r($report);die;
            //$reasons= get_cms_report_reason_reasontitle($report->reason);
           
        ?>
        <tr>
            <td><?= $report->id;?></td>    
            <td><?= $report->user_name;?></td>    
            <td><?= $report->ip_address;?></td>
            <td><?= $report->forwarded_ip_address; ?></td>
            <td><?= $report->user_agent; ?></td>
            <td><?= $report->log_ts; ?></td> 
        </tr> 
    <?php  } ?>
    </table>
</div>