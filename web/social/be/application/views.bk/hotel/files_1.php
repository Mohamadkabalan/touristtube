<?php
if (isset($hotel['images']) && count($hotel['images']))
{
    ?>
        <ul>
            <?php
            foreach ($hotel['images'] as $file)
            {
                ?>
                <li class="image_wrap">
                    
                    <img src="uploads/<?php echo $file['filename']?>"> 
                    <br />
                    <a href="#" class="delete_file_link" data-file_id="<?php //echo $file->id?>">Delete</a>
                    
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