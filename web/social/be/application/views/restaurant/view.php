<?php
$controller = $this->router->class;
$id = $restaurant['id'];
$latitude = $restaurant['latitude'];
$longitude = $restaurant['longitude'];
$name = $restaurant['name'];
$country = $restaurant['country'];
$city = $restaurant['city'];
$address = $restaurant['address'];
$about = $restaurant['about'];
$description = $restaurant['description'];
$facilities = $restaurant['facilities'];
$zipcode = $restaurant['zipcode'];
$phone = $restaurant['phone'];
$fax = $restaurant['fax'];
$email = $restaurant['email'];
$website = $restaurant['website'];
$opening_hours = $restaurant['opening_hours'];
$opening_days = $restaurant['opening_days'];
?>

<div style=" float: left; width: 65%; min-height: 1000px;">
    <h1><?= $title?>    <span style="float:right;"> <?= anchor("$controller/edit/".$id, "Edit");?></span></h1> 
 <table class="table table-striped hotelsTable">
     <tbody>
         <tr><th>Name</th><td><?= $name?></td></tr>
     </tbody>
     <tbody id="cuisinesContainer">
         <?= $this->load->view("restaurant/ajax_restaurant_cuisines") ?>
     </tbody>
     <tbody>
        <tr><th>Opening hours</th><td><?= $opening_hours?></td></tr> 
        <tr><th>Opening days</th><td><?= $opening_days?></td></tr> 
        <tr><th>Latitude</th><td><?= $latitude?></td></tr>
        <tr><th>Longitude</th><td><?= $longitude?></td></tr>
        <tr><th>Country</th><td><?= $country?></td></tr>
        <tr><th>City</th><td><?= $cityName?></td></tr>
        <tr><th>Old City</th><td><?= $restaurant['city']?></td></tr>
        <tr><th>Zip Code</th><td><?= $zipcode?></td></tr>
        <tr><th>Phone</th><td><?= $phone?></td></tr>
        <tr><th>Fax</th><td><?= $fax?></td></tr>
        <tr><th>Email</th><td><?= $email?></td></tr>
        <tr><th>Web Site</th><td><?= $website?></td></tr>
        <tr><th>Address</th><td><?= $address ?></td></tr>
        <tr><th>About</th><td><?= $about?></td></tr>
        <tr><th>Description</th><td><?= $description?></td></tr>
        <tr><th>facilities</th><td><?php echo $facilities?></td></tr>
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
        <form method="post" action="" id="upload_restaurant_img">
            <input type="hidden" name="id" id="id" value="<?= $id?>" />
            <input type="file" name="userfile[]" id="userfile" size="20" multiple />

            <input type="submit" name="submit" id="submit" />
        </form>
        <h2>Files</h2>
        <div id="files"><?php $this->load->view("restaurant/files")?></div>
    </div>
    <div id="map_image_container" style="float: left">
    <?php $this->load->view("restaurant/map_img") ?>
    </div>
</div>

