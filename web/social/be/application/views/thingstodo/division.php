<?php
$controller = $this->router->class;
$user_data = $this->session->userdata('logged_in');
?>
<div id="listContainer">
	<h1><?php echo $title;?></h1>
        <table class="table">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th></th>
                <th><div class="add-deal"><a href=<?php echo $controller."/division_add" ?>>Add</a></div></th>
            </tr>
            <?php foreach($divisions as $division){ ?>
            <tr>
                <td><?= $division->id;?></td>
                <td><?= $division->name;?></td>
                <td><?= anchor("$controller/division_details/".$division->id, 'details');?></td>
                <td><?= anchor("$controller/ajax_division_delete/".$division->id, "Delete");?></td>
            </tr>
            <?php  } ?>
        </table>
</div>