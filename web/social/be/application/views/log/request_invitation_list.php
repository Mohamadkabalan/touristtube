<?php $controller = $this->router->class ?>
<h1><?= $title?>   </h1>
<br />
<br />
<div id="listContainer">
    <table class="table">
        <tr>
            <th>#</th>
            <th>Invite Person Name</th>
            <th>Email Id</th>
            <th>Date</th>
            <th></th>
        </tr>
        <?php foreach($users as $user){ ?>
        <tr>
            <td><?= $user->id;?></td>    
            <td><?= $user->to_name;?></td>    
            <td><?= $user->to_email;?></td>
            <td><?= $user->invite_ts;?></td>
        </tr> 
    <?php  } ?>
    </table>
</div>