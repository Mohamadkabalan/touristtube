<?php

$controller = $this->router->class;
$user_data = $this->session->userdata('logged_in');
$this->load->helper('hotel_search_type_display');
?>

<div id="listContainer">
    <h1><span style="float:right;"> <?= anchor("$controller/hotel_search_edit/".$hotel_search['id'], "Edit");?></span></h1> 
    <table class="table table-striped hotelsTable">
        <tbody>
            <tr><th>Name</th><td><?= $hotel_search['name'] ?></td></tr>
            <tr><th>Keyword</th><td><?= $hotel_search['keyword'] ?></td></tr>
        </tbody>
    </table>
    
    <p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
    <h1>Search Items<span style="float:right;"> <?= anchor("$controller/search_detail_add/".$hotel_search['id'], "Add");?></span></h1> 
    <table class="table">
    <tr>
    <th>#</th>
    <th>Name</th>
    <th>Longitude</th>
    <th>Latitude</th>
    <th>Type</th>
    <th>Country Code</th>
    <th>Entity ID</th>
    <th>Popular</th>
    <th></th>
    <th></th>
    </tr>
    <?php foreach($hotel_search_details as $item){ ?>
    <tr>
    <td><?= $item->id;?></td>   
    <td><?= $item->name;?></td>
    <td><?= $item->longitude;?></td>    
    <td><?= $item->latitude;?></td>
    <td><?= hotel_search_type_display($item->entity_type);?></td>
    <td><?= $item->country_code ?></td>
    <td><?= $item->entity_id;?></td>
    <td><?php if($item->popular == 1){ echo "YES"; }else{ echo "NO";} ?></td>
    <td><?=  anchor("$controller/search_detail_edit/".$item->id, "Edit");?></td>
    <td><?=  anchor("$controller/ajax_delete_hotel_search_detail/".$item->id, "Delete", array('class'=>'deleteAct'));?></td>    
    </tr> 
    <?php  } ?>
    </table>
    <p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>

</div>