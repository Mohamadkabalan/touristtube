<?php $controller = $this->router->class; ?>
<div style=" float: left; width: 65%; min-height: 1000px;">
    <h1><?= $title?>    <span style="float:right;"> <?= anchor("$controller/edit/".$user['id'], "Edit");?></span></h1> 
 <table class="table table-striped hotelsTable">
     <tbody>
        <tr><th>First Name</th><td><?= $user['fname']?></td></tr>
        <tr><th>Last Name</th><td><?= $user['lname']?></td></tr>
        <tr><th>Username</th><td><?= $user['username']?></td></tr>
        <tr><th>Last Login</th><td><?= $user['last_login']?></td></tr>
        <tr><th>Role</th><td><?= $user['role']?></td></tr>
     </tbody>
</table>