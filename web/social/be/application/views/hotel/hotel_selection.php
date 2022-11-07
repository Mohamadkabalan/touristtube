<?php
$controller = $this->router->class;
?>
<h1> <?= $title?> <span style="float:right;"> <?= anchor("$controller/addhselection", "Add");?></span></h1>
<div id="listContainer">
 <?php $this->load->view('hotel/ajax_hotelSelection');?>
</div>