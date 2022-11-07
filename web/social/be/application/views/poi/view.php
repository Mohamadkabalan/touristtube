<?php
$controller = $this->router->class;
$id = $poi['id'];
$latitude = $poi['latitude'];
$longitude = $poi['longitude'];
$name = $poi['name'];
$country = $poi['country'];
$city = $poi['city'];
$zipcode = $poi['zipcode'];
$phone = $poi['phone'];
$fax = $poi['fax'];
$email = $poi['email'];
$website = $poi['website'];
$price = $poi['price'];
$description = $poi['description'];
?>

<div style=" float: left; width: 65%; min-height: 1000px;">
    <h1><?= $title?>    <span style="float:right;"> <?= anchor("$controller/edit/".$id, "Edit");?></span></h1> 
 <table class="table table-striped hotelsTable">
     <tbody>
         <tr><th>Name</th><td><?= $name?></td></tr>
     </tbody>
     <tbody id="categoriesContainer">
         <?= $this->load->view("poi/ajax_poi_categories") ?>
     </tbody>
     <tbody>
        <tr><th>Latitude</th><td><?= $latitude?></td></tr>
        <tr><th>Longitude</th><td><?= $longitude?></td></tr>
        <tr><th>Price</th><td><?= $price ?></td></tr>
        <tr><th>Country</th><td><?= $country?></td></tr>
        <tr><th>City</th><td><?= $cityName?></td></tr>
        <tr><th>Old City</th><td><?= $poi['city']?></td></tr>
        <tr><th>Zip Code</th><td><?= $zipcode?></td></tr>
        <tr><th>Phone</th><td><?= $phone?></td></tr>
        <tr><th>Fax</th><td><?= $fax?></td></tr>
        <tr><th>Email</th><td><?= $email?></td></tr>
        <tr><th>Web Site</th><td><?= $website?></td></tr>
        <tr><th>Description</th><td><?= $description?></td></tr>
        <tr><th>Show on map</th><td><?php if($poi['show_on_map']) echo "Yes"; else echo "No";?></td></tr>
        <tr><th>Hotel Nearby</th><td><?= $nearby['hotel_nearby']?></td></tr>
        <tr><th>Restaurant Nearby</th><td><?= $nearby['restaurant_nearby']?></td></tr>
        <tr><th>Poi Nearby</th><td><?= $nearby['poi_nearby']?></td></tr>
     </tbody>
    
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
        <form method="post" action="" id="upload_poi_img">
            <input type="hidden" name="id" id="id" value="<?= $id?>" />
            <input type="file" name="userfile[]" id="userfile" size="20" multiple />

            <input type="submit" name="submit" id="submit" />
        </form>
        <h2>Files</h2>
        <div id="files"><?php $this->load->view("poi/files")?></div>
    </div>
    <div id="map_image_container" style="float: left;">
    <?php $this->load->view("poi/map_img") ?>
    </div>
</div>

