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
$location = $hotel['location'];
?>

<div style=" float: left; width: 100%; min-height: 1000px;">
    <h1><?= $title?></h1> 
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
        <tr id="hotelFacilitiesRow"><th>Facilities</th>
            <td>
            <?= $hotel_facility_titles ?>
            </td>
        </tr>
    </tbody>
    <tbody>
        <tr><th>Description</th><td><?= $description?></td></tr>
    </tbody>
    <tbody>
        <tr>
            <th>location</th>
            <td>
                <form method="post" action="" id="upload_hotels_img">
                    <select id="location" class="location">
                        <?php foreach($locations as $location){ ?>
                            <option id="<?= $location ?>"><?= $location ?></option>
                        <?php } ?>
                    </select>
                    <h1>Upload Images</h1>
                    <input type="hidden" name="id" id="id" value="<?= $hotel['id']?>" />
                    <input type="hidden" name="title" id="title" value="<?= $hotel['name']?>" />
                    <input type="hidden" name="typehotel" id="typehotel" value="1" />
                    <input type="file" name="userfile[]" id="userfile" size="20" multiple />
                    <input type="submit" name="submit" id="submit" />
                </form>
            </td>
        </tr>
    </tbody>
</table>
    <div id="slider_container">
        <?php $this->load->view("hotelchain/images") ?>
    </div>
</div>



