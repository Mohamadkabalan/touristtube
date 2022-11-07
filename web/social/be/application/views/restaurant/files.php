<?php
$restaurant_id = $this->uri->segment(3);
if (isset($files) && count($files))
{
    
    ?>
        <ul>
            <?php
            foreach ($files as $file){
                if($file['default_pic'] == '1'){ 
            ?>
                    <li class="image_wrap">
                        <img src="../media/discover/thumb/<?php echo $file['filename']?>" style="border:2px solid"> 
                        <br />
                        <a href="#" class="delete_restimg_link" data-file_id="<?php echo $file['id']?>">Delete</a>

                    </li>
            <?php
                }else{ ?>
                    <li class="image_wrap">

                        <img src="../media/discover/thumb/<?php echo $file['filename']?>"> 
                        <br />
                        <a href="#" class="delete_restimg_link" data-file_id="<?php echo $file['id']?>">Delete</a>&nbsp;&nbsp;
                        <a href="<?php echo site_url('restaurant/default_image/'.$restaurant_id.'/'.$file['id'])?>" class="" data-file_id="<?php echo $file['id']?>">Default</a>

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