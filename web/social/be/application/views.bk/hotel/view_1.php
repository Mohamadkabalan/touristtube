<?php
$id = $hotel['id'];
$hotelName = $hotel['hotelName'];
$stars = $hotel['stars'];
$price = $hotel['price'];
$cityName = $hotel['cityName'];
$stateName = $hotel['stateName'];
$countryCode = $hotel['countryCode'];
$countryName = $hotel['countryName'];
$address = $hotel['address'];
$location = $hotel['location'];
$url = $hotel['url'];
$latitude = $hotel['latitude'];
$longitude = $hotel['longitude'];
$latlong = $hotel['latlong'];
$propertyType = $hotel['propertyType'];
$chainId = $hotel['chainId'];
$rooms = $hotel['rooms'];
$facilities = $hotel['facilities'];
$facilities1 = $hotel['facilities1'];
//print_r($hotel);
$checkIn = $hotel['checkIn'];
$checkOut = $hotel['checkOut'];
$rating = $hotel['rating'];
$about = $hotel['about'];
$description = $hotel['description'];
$general_facilities = $hotel['general_facilities'];
$services = $hotel['services'];
?>

<div style=" float: left; width: 65%;">
    <h1><?= $title?>    <span style="float:right;"> <?= anchor("hotel/edit/".$id, "Edit");?></span></h1> 
 <table class="table table-striped">
    <tr><th>hotelName</th><td><?= $hotelName?></td></tr>
    <tr><th>stars</th><td><?= $stars?></td></tr>
    <tr><th>price</th><td><?= $price?></td></tr>
    <tr><th>cityName</th><td><?= $cityName?></td></tr>
    <tr><th>stateName</th><td><?= $stateName?></td></tr>
    <tr><th>country</th><td><?= '('.$countryCode .') '. $countryName ?></td></tr>
    <tr><th>address</th><td><?= $address?></td></tr>
    <tr><th>location</th><td><?= $location?></td></tr>
    <tr><th>latitude</th><td><?= $latitude?></td></tr>
    <tr><th>longitude</th><td><?= $longitude?></td></tr>
    <tr><th>propertyType</th><td><?= $propertyType?></td></tr>
    <tr><th>rooms</th><td><?= $rooms?></td></tr>
    <tr><th>facilities</th><td><?php echo $facilities?>
        <?php foreach($facilities1 as $f) echo $f['facility_id'].", "; ?>
        </td></tr>
    <tr><th>rating</th><td><?= $rating?></td></tr>
    <tr><th>About</th><td><?= $about?></td></tr>
    <tr><th>Description</th><td><?= $description?></td></tr>
    <tr><th>General facilities</th><td><?= $general_facilities?></td></tr>
    <tr><th>Services</th><td><?= $services?></td></tr>
  
</table>

<h1> Rooms   <span style="float:right;"> <?= anchor("hotel/room_add/$id", "Add");?></span></h1> 
<table class="table table-striped">
<tr><th>Title</th><th>Num persons</th><th>Price</th><th>Pic 1</th><th>Pic 2</th><th>Pic 3</th><th></th><th></th></tr>
<?php foreach($hotel['room_details'] as $room){ ?>
<tr>
<td><?= $room['title'];?></td>    
<td><?= $room['num_person'];?></td>    
<td><?= $room['price'];?></td>
<td><?php if($room['pic1']<>'') echo  img(array('src'=>'uploads/rooms/'.$room['pic1'], 'width'=>100));?></td>    
<td><?php if($room['pic2']<>'') echo  img(array('src'=>'uploads/rooms/'.$room['pic2'], 'width'=>100));?></td>    
<td><?php if($room['pic3']<>'') echo  img(array('src'=>'uploads/rooms/'.$room['pic3'], 'width'=>100));?></td>    
<td><? //echo anchor("hotel1/room_delete/".$room['id'], "Delete");?></td>     
<td><? //echo anchor("hotel1/room_edit/".$room['id'], "Edit");?></td>    
</tr> 
<?php  } ?>
</table>

<h1> Reviews   <span style="float:right;"> <?= anchor("hotel/review_add/$id", "Add");?></span></h1> 
<table class="table table-striped">
<tr><th>Title</th><th>Description</th><th></th><th></th></tr>
<?php foreach($hotel['reviews'] as $review){ ?>
<tr>
<td><?= $review['title'];?></td>    
<td><?= $review['description'];?></td>    
<td><? //echo anchor("hotel/review_delete/".$review->id, "Delete");?></td>    
<td><? //echo anchor("hotel/review_edit/".$review->id, "Edit");?></td>    
</tr> 
<?php  } ?>
</table>
</div>
<div style="width: 25%; float: right;">
    <h1>Upload Images</h1>
    <form method="post" action="" id="upload_hotel_img">
        <input type="hidden" name="id" id="id" value="<?= $id?>" />
        <input type="file" name="userfile[]" id="userfile" size="20" multiple />
 
        <input type="submit" name="submit" id="submit" />
    </form>
    <h2>Files</h2>
    <div id="files"><?php $this->load->view('hotel/files_1')?></div>
</div>
