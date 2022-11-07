<?php
$id = $restaurant->id;
$latitude = $restaurant->latitude;
$longitude = $restaurant->longitude;
$name = $restaurant->name;
$country = $restaurant->country;
$city = $restaurant->city;
$address = $restaurant->address;
$about = $restaurant->about;
$description = $restaurant->description;
$facilities = $restaurant->facilities;
?>

<div style=" float: left; width: 65%;">
    <h1><?= $title?>    <span style="float:right;"> <?= anchor("restaurant/edit/".$id, "Edit");?></span></h1> 
 <table class="table table-striped">
    <tr><th>Name</th><td><?= $name?></td></tr>
    <tr><th>Latitude</th><td><?= $latitude?></td></tr>
    <tr><th>Longitude</th><td><?= $longitude?></td></tr>
    <tr><th>Country</th><td><?= $country?></td></tr>
    <tr><th>City</th><td><?= $city?></td></tr>
    <tr><th>Address</th><td><?= $address ?></td></tr>
    <tr><th>About</th><td><?= $about?></td></tr>
    <tr><th>Description</th><td><?= $description?></td></tr>
    <tr><th>facilities</th><td><?php echo $facilities?></td></tr>
</table>
<h1> Reviews   <span style="float:right;"> <?= anchor("restaurant/review_add/$id", "Add");?></span></h1> 
<table class="table table-striped">
<tr><th>Title</th><th>Description</th><th></th><th></th></tr>
<?php foreach($restaurant->review as $review){ ?>
<tr>
<td><?= $review->title;?></td>    
<td><?= $review->description;?></td>    
<td><?= anchor("restaurant/review_delete/".$review->id, "Delete");?></td>    
<td><?= anchor("restaurant/review_edit/".$review->id, "Edit");?></td>    
</tr> 
<?php  } ?>
</table>
</div>
<div style="width: 25%; float: right;">
    <h1>Upload Images</h1>
    <form method="post" action="" id="upload_restaurant_img">
        <input type="hidden" name="id" id="id" value="<?= $id?>" />
        <input type="file" name="userfile[]" id="userfile" size="20" multiple />
 
        <input type="submit" name="submit" id="submit" />
    </form>
    <h2>Files</h2>
    <div id="files"><?php $this->load->view('restaurant/files')?></div>
</div>
