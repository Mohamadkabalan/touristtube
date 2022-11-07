<?php $controller = $this->router->class; ?>
<h1><?= $title?></h1>
<br/>
<div>
    <div id="reportrange">
        <i class="fa fa-calendar fa-lg"></i>
        <span><?php echo date("F j, Y", strtotime('-6 days')); ?> - <?php echo date("F j, Y"); ?></span> <b class="caret"></b>
    </div>
    <img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/>
</div>
<br/>
<div id="listContainer">
<?= $this->load->view('user/ajax_activities') ?>
</div>