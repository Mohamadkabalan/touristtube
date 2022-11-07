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
                <th><div class="add-deal"><a href=<?php echo $controller."/division_category_add" ?>>Add</a></div></th>
            </tr>
            <?php foreach($categorys as $category){ ?>
            <tr>
                <td><?= $category->id;?></td>
                <td><?= $category->name;?></td>
                <td><?= anchor("$controller/division_category_edit/".$category->id, 'Edit');?></td>
                <td><?= anchor("$controller/ajax_division_category_delete/".$category->id, "Delete");?></td>
            </tr>
            <?php  } ?>
        </table>
</div>