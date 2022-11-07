<?php
$hotel_id = $this->uri->segment(3);
if( $this->config->item('upload_src') == "s3" ){
    $prefix = 'https://' . $this->config->item('bucketName') . ".". S3::$endpoint . "/media/discover";
}
else{
    $prefix = "../media/discover/thumb";
}
if (isset($files) && count($files))
{
    ?>
    <ul>
        <?php
        foreach ($files as $file){
            if($file['default_pic'] == '1'){ 
                ?>

                <li class="image_wrap">
                    <img src="<?php echo $prefix . '/' . $file['filename'] . '?d=100x100'?>" style="border:2px solid"> 
                    <br />
                    <a href="#" class="delete_file_link" data-file_id="<?php echo $file['id']?>">Delete</a>

                </li>

                <?php
            }else{ ?>
               <li class="image_wrap">

                <img src="<?php echo $prefix . '/' . $file['filename']. '?d=100x100'?>"> 
                <br />
                <a href="#" class="delete_file_link" data-file_id="<?php echo $file['id']?>">Delete</a>&nbsp;&nbsp;
                <a href="<?php echo site_url('hotel/default_image/'.$hotel_id.'/'.$file['id'])?>" class="" data-file_id="<?php echo $file['id']?>">Default</a>

            </li>
        <?php    }
    }
    ?>
</ul>
</form>
<?php
}
else
{
    ?>
    <p>No Files Uploaded</p>
    <?php
}
?>