<?php
$controller = $this->router->class;
?>
<h1> <?= $title?>    <span style="float:right;"> <?= anchor("$controller/alias_add", "Add");?></span></h1>
<div>
    <input type="text" id="txtName" placeholder="Filter by name..."/>
	<input type="text" id="txtUrl" placeholder="Filter by Url..."/>
	<button type="button" id="btnFilter" onclick="return filterFn();">Search</button>
    <button type="button" id="btnReset" onclick="return resetFn();">Reset</button>
</div>	
<div id="listContainer">
 <?php  $this->load->view('seo/ajax_alias');?>
</div>