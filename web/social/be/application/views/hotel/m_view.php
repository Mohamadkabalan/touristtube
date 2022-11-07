<?php
$controller = $this->router->class;
$id = $hotel['id'];
$hotelName = $hotel['hotelName'];
$stars = $hotel['stars'];
$price = $hotel['price'];
$stateName = $hotel['stateName'];
$countryCode = $hotel['countryCode'];
$countryName = $hotel['countryName'];
$address = $hotel['address'];
$location = $hotel['location'];
$url = $hotel['url'];
$latitude = $hotel['loc']['lat'];
$longitude = $hotel['loc']['lon'];
$latlong = $hotel['latlong'];
$propertyType = $hotel['propertyType'];
$chainId = $hotel['chainId'];
$facilities = $hotel['facilities'];
$checkIn = $hotel['checkIn'];
$checkOut = $hotel['checkOut'];
$rating = $hotel['rating'];
$about = $hotel['about'];
$description = $hotel['description'];
$general_facilities = $hotel['general_facilities'];
$services = $hotel['services'];
$zipcode = isset($hotel['zipcode']) ? $hotel['zipcode'] : '';
$phone = isset($hotel['phone']) ? $hotel['phone'] : '';
$fax = isset($hotel['fax']) ? $hotel['fax'] : '';
$email = isset($hotel['email']) ? $hotel['email'] : '';
$website = isset($hotel['website']) ? $hotel['website'] : '';
?>

<div style=" float: left; width: 65%; min-height: 1000px;">
    <h1><?= $title?>    <span style="float:right;"> <?= anchor("$controller/edit/".$id, "Edit");?></span></h1> 
 <table class="table table-striped hotelsTable">
     <tbody>
        <tr><th>hotelName</th><td><?= $hotelName?></td></tr>
        <tr><th>stars</th><td><?= $stars?></td></tr>
        <tr><th>price</th><td><?= $price?></td></tr>
        <tr><th>cityName</th><td><?= isset($cityName) ? $cityName : ''?></td></tr>
        <tr><th>Old City</th><td><?= $hotel['cityName']?></td></tr>
        <tr><th>stateName</th><td><?= $stateName?></td></tr>
        <tr><th>country</th><td><?= '('.$countryCode .') '. $countryName ?></td></tr>
        <tr><th>address</th><td><?= $address?></td></tr>
        <tr><th>location</th><td><?= $location?></td></tr>
        <tr><th>latitude</th><td><?= $latitude?></td></tr>
        <tr><th>longitude</th><td><?= $longitude?></td></tr>
        <tr><th>Zip Code</th><td><?= $zipcode?></td></tr>
        <tr><th>Phone</th><td><?= $phone?></td></tr>
        <tr><th>Fax</th><td><?= $fax?></td></tr>
        <tr><th>Email</th><td><?= $email?></td></tr>
        <tr><th>Web Site</th><td><?= $website?></td></tr>
        <tr><th>propertyType</th><td><?= $propertyType?></td></tr>
        <tr><th>rooms</th><td><?= count($rooms)?></td></tr>
     </tbody>
    <tbody id="facilitiesContainer">
        <?= $this->load->view("hotel/ajax_hotel_facilities") ?>
    </tbody>
    <tbody>
        <tr><th>rating</th><td><?= $rating?></td></tr>
        <tr><th>About</th><td><?= $about?></td></tr>
        <tr><th>Description</th><td><?= $description?></td></tr>
        <tr><th>General facilities</th><td><?= $general_facilities?></td></tr>
        <tr><th>Services</th><td><?= $services?></td></tr>
    </tbody>
</table>

<h1> Rooms   <span style="float:right;"> <?= anchor("$controller/room_add/$id", "Add");?></span></h1> 
<table class="table table-striped">
<tr><th>Title</th><th>Num persons</th><th>Price</th><th>Pic 1</th><th>Pic 2</th><th>Pic 3</th><th></th><th></th></tr>
<?php foreach($rooms as $room){ ?>
<tr>
<td><?= $room['title'];?></td>    
<td><?= $room['num_person'];?></td>    
<td><?= $room['price'];?></td>
<td><?php if($room['pic1']<>'') echo  img(array('src'=>'uploads/rooms/thumb/'.$room['pic1'], 'width'=>100));?></td>    
<td><?php if($room['pic2']<>'') echo  img(array('src'=>'uploads/rooms/thumb/'.$room['pic2'], 'width'=>100));?></td>    
<td><?php if($room['pic3']<>'') echo  img(array('src'=>'uploads/rooms/thumb/'.$room['pic3'], 'width'=>100));?></td>    
<td><?= anchor("$controller/room_delete/".$room['id']."/".$id, "Delete");?></td>    
<td><?= anchor("$controller/room_edit/".$room['id']."/".$id, "Edit");?></td>    
</tr> 
<?php  } ?>
</table>

<h1> Reviews   <span style="float:right;"> <?= anchor("$controller/review_add/$id", "Add");?></span></h1> 
<table class="table table-striped">
<tr><th>Title</th><th>Description</th><th></th><th></th></tr>
<?php foreach($reviews as $review){ ?>
<tr>
<td><?= $review['title'];?></td>    
<td><?= $review['description'];?></td>    
<td><?= anchor("$controller/review_delete/".$review['id']."/".$id, "Delete");?></td>    
<td><?= anchor("$controller/review_edit/".$review['id']."/".$id, "Edit");?></td>    
</tr> 
<?php  } ?>
</table>
</div>
<div style="width: 25%; float: right;">
    <div style="float: left;">
        <h1>Upload Images</h1>
        <form method="post" action="" id="upload_hotel_img">
            <input type="hidden" name="id" id="id" value="<?= $id?>" />
            <input type="file" name="userfile[]" id="userfile" size="20" multiple />

            <input type="submit" name="submit" id="submit" />
        </form>
        <h2>Files</h2>
        <div id="files"><?php $this->load->view("hotel/files")?></div>
    </div>
    <div id="map_image_container" style="float: left;">
    <?php $this->load->view("hotel/map_img") ?>
    </div>
</div>



