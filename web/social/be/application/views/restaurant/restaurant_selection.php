<?php
$controller = $this->router->class;
?>
<h1> <?= $title?> <span style="float:right;"> <?= anchor("$controller/addrselection", "Add");?></span></h1>
<div id="listContainer">
 <?php $this->load->view('restaurant/ajax_restaurantSelection');?>
</div>