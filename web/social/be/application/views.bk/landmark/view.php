<?php
$id = $restaurant->id;
$restaurantName = $restaurant->restaurantName;
$stars = $restaurant->stars;
$price = $restaurant->price;
$cityName = $restaurant->cityName;
$stateName = $restaurant->stateName;
$countryCode = $restaurant->countryCode;
$countryName = $restaurant->countryName;
$address = $restaurant->address;
$location = $restaurant->location;
$url = $restaurant->url;
$latitude = $restaurant->latitude;
$longitude = $restaurant->longitude;
$latlong = $restaurant->latlong;
$propertyType = $restaurant->propertyType;
$chainId = $restaurant->chainId;
$rooms = $restaurant->rooms;
$facilities = $restaurant->facilities;
$checkIn = $restaurant->checkIn;
$checkOut = $restaurant->checkOut;
$rating = $restaurant->rating;
$description = $restaurant->description;
?>

<div style=" float: left; width: 45%;">
    <h1><?= $title?>    <span style="float:right;"> <?= anchor("restaurant/edit/".$id, "Edit");?></span></h1> 
 <table class="table table-striped">
    <tr><th>restaurantName</th><td><?= $restaurantName?></td></tr>
    <tr><th>stars</th><td><?= $stars?></td></tr>
    <tr><th>price</th><td><?= $price?></td></tr>
    <tr><th>cityName</th><td><?= $cityName?></td></tr>
    <tr><th>stateName</th><td><?= $stateName?></td></tr>
    <tr><th>country</th><td><?= $countryCode . $countryName ?></td></tr>
    <tr><th>address</th><td><?= $address?></td></tr>
    <tr><th>location</th><td><?= $location?></td></tr>
    <tr><th>url</th><td><?= $url?></td></tr>
    <tr><th>latitude</th><td><?= $latitude?></td></tr>
    <tr><th>longitude</th><td><?= $longitude?></td></tr>
    <tr><th>latlong</th><td><?= $latlong?></td></tr>
    <tr><th>propertyType</th><td><?= $propertyType?></td></tr>
    <tr><th>chainId</th><td><?= $chainId?></td></tr>
    <tr><th>rooms</th><td><?= $rooms?></td></tr>
    <tr><th>facilities</th><td><?= $facilities?></td></tr>
    <tr><th>checkIn</th><td><?= $checkIn?></td></tr>
    <tr><th>checkOut</th><td><?= $checkOut?></td></tr>
    <tr><th>rating</th><td><?= $rating?></td></tr>
    <tr><th>Description</th><td><?= $description?></td></tr>
  
</table></div>
<div style="width: 45%; float: right;">
    <h1>Upload Images</h1>
    <form method="post" action="">
        <input type="hidden" name="id" id="id" value="<?= $id?>" />
        <input type="file" name="userfile[]" id="userfile" size="20" multiple />
 
        <input type="submit" name="submit" id="submit" />
    </form>
    <h2>Files</h2>
    <div id="files"><?php $this->load->view('restaurant/files')?></div>
</div>