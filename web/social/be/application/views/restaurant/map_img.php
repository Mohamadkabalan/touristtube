<h1>Map Image</h1>
<?php if(isset($map_image)){ ?>
<img src="<?php echo $map_image?>" alt="No image.">
<?php 
} 
else{
    ?>
<p>No image</p>
<?php
}
?>
<p>
    <input style="width: auto;" type="button" value="Update" id="updateMapImage"/>
</p>
