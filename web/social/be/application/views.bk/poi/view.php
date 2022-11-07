<?php
$id = $poi->id;
$latitude = $poi->latitude;
$longitude = $poi->longitude;
$name = $poi->name;
$country = $poi->country;
?>

<div style=" float: left; width: 65%;">
    <h1><?= $title?>    <span style="float:right;"> <?= anchor("poi/edit/".$id, "Edit");?></span></h1> 
 <table class="table table-striped">
    <tr><th>Name</th><td><?= $name?></td></tr>
    <tr><th>Latitude</th><td><?= $latitude?></td></tr>
    <tr><th>Longitude</th><td><?= $longitude?></td></tr>
    <tr><th>Country</th><td><?= $country?></td></tr>
</table>
<h1> Reviews   <span style="float:right;"> <?= anchor("poi/review_add/$id", "Add");?></span></h1> 
<table class="table table-striped">
<tr><th>Title</th><th>Description</th><th></th><th></th></tr>
<?php foreach($poi->review as $review){ ?>
<tr>
<td><?= $review->title;?></td>    
<td><?= $review->description;?></td>    
<td><?= anchor("poi/review_delete/".$review->id, "Delete");?></td>    
<td><?= anchor("poi/review_edit/".$review->id, "Edit");?></td>    
</tr> 
<?php  } ?>
</table>
</div>
<div style="width: 25%; float: right;">
    <h1>Upload Images</h1>
    <form method="post" action="" id="upload_poi_img">
        <input type="hidden" name="id" id="id" value="<?= $id?>" />
        <input type="file" name="userfile[]" id="userfile" size="20" multiple />
 
        <input type="submit" name="submit" id="submit" />
    </form>
    <h2>Files</h2>
    <div id="files"><?php $this->load->view('poi/files')?></div>
</div>
