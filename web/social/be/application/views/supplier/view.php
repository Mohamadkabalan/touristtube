<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$controller = $this->router->class;
?>
<div style=" float: left; width: 65%; min-height: 1000px;">
    <h1><?= $title?>    <span style="float:right;"> <?= anchor("$controller/edit/".$supplier['id'], "Edit");?></span></h1> 
 <table class="table table-striped hotelsTable">
     <tbody>
         <tr><th>Code</th><td><?= $supplier['code']?></td></tr>
        <tr><th>Name</th><td><?= $supplier['name']?></td></tr>
        <tr><th>Email</th><td><?= $supplier['email']?></td></tr>
        <tr><th>Username</th><td><?= $user['username']?></td></tr>
        <tr><th>Country</th><td><?= $supplier['country_code']?></td></tr>
     </tbody>
</table>
