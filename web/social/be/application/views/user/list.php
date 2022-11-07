<?php $controller = $this->router->class ?>
<h1><?= $title?>   </h1>
<br />
<br />
<div id="listContainer">
    <table class="table">
    <tr>
    <th>#</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Username</th>
    <th>Last Login</th>
    <th>Role</th>
    <th></th>
    <th></th>
    </tr>
    <?php foreach($users as $user){ ?>
    <tr>
    <td><?= $user->id;?></td>    
    <td><?= $user->fname;?></td>    
    <td><?= $user->lname;?></td>
    <td><?= $user->username;?></td>    
    <td><?= $user->last_login;?></td>
    <td><?= $user->role;?></td>
    <td><?= anchor("$controller/view/".$user->id, 'Detail');?></td>    
    <td><?= anchor("$controller/ajax_delete/".$user->id, "Delete", array('class'=>'deleteAct'));?></td>    
    </tr> 
    <?php  } ?>
    </table>
</div>