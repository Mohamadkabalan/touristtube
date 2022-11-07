<?php
$user_data = $this->session->userdata('logged_in');
$controller = $this->router->class;
$id = $hotel['id'];
$hotelName = $hotel['name'];
$stars = $hotel['stars'];
$city = $hotel['city'];
$latitude = $hotel['latitude'];
$longitude = $hotel['longitude'];
$description = $hotel['description'];
$zip_code = $hotel['zip_code'];
?>

<div style=" float: left; width: 100%; min-height: 1000px;">
    <h1><?= $title?>    <span style="float:right;"> <?= anchor("$controller/edit/".$id, "Edit");?></span></h1> 
    <input type="hidden" id="id" value="<?= $id?>" />
 <table class="table table-striped hotelsTable">
     <tbody>
        <tr><th>Name</th><td><?= $hotelName?></td></tr>
        <tr><th>Stars</th><td><?= $stars?></td></tr>
        <tr><th>City</th><td><?= $city?></td></tr>
        <tr><th>Latitude</th><td><?= $latitude?></td></tr>
        <tr><th>Longitude</th><td><?= $longitude?></td></tr>
        <tr><th>Zip Code</th><td><?= $zip_code?></td></tr>
     </tbody>
    <tbody id="facilitiesContainer">
        <?= $this->load->view("hotels/ajax_hotel_facilities", array('user_data' => $user_data)) ?>
    </tbody>
    <tbody id="amenitiesContainer">
        <tr id="hotelFacilitiesRow"><th>Amenities<?= $user_data['role'] != 'hotel_desc_writers' ? ' ('.anchor("$controller/edit_amenities/".$id, "Edit").')' : '';?></th>
            <td>
            <?= $amenity_titles ?>
            </td>
        </tr>
    </tbody>
    <tbody>
        <tr><th>Description</th><td><?= $description?></td></tr>
    </tbody>
</table>
    <div id="slider_container">
        <?php $this->load->view("hotels/images") ?>
    </div>
</div>



