<?php
$controller = $this->router->class;
?>
<h1> <?= $title?>    <span style="float:right;"> <?= anchor("$controller/whereis_add", "Add");?></span></h1>
<div id="listContainer">
 <?php  $this->load->view('seo/ajax_whereis');?>
</div>