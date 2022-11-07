<?php
if (isset($files) && count($files))
{
    ?>
        <ul>
            <?php
            foreach ($files as $file)
            {
                ?>
                <li class="image_wrap">
                    
                    <img src="uploads/<?php echo $file->filename?>"> 
                    <br />
                    <a href="#" class="delete_restimg_link" data-file_id="<?php echo $file->id?>">Delete</a>
                    
                </li>
                <?php
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