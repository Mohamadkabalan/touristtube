<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$controller = $this->router->class;
?>
<h1><?= $title?>   </h1>
<br />
<br />
<div id="listContainer">
    <table class="table">
    <tr>
    <th>#</th>
    <th>Code</th>
    <th>Name</th>
    <th>Email</th>
    <th>Username</th>
    <th>Country</th>
    <th>Last Login</th>
    <th></th>
    <th></th>
    </tr>
    <?php foreach($suppliers as $supplier){ ?>
    <tr>
    <td><?= $supplier['id'];?></td>    
    <td><?= $supplier['code'];?></td>    
    <td><?= $supplier['name'];?></td>    
    <td><?= $supplier['email'];?></td>
    <td><?= $supplier['user']['username'];?></td>    
    <td><?= $supplier['country_name'];?></td>
    <td><?= $supplier['user']['last_login'];?></td>
    <td><?= anchor("$controller/view/".$supplier['id'], 'Detail');?></td>    
    <td><?= anchor("$controller/ajax_delete/".$supplier['id'] ."/" . $supplier['user_id'], "Delete", array('class'=>'deleteAct'));?></td>    
    </tr> 
    <?php  } ?>
    </table>
</div>
