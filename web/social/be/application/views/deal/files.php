<?php
$deal_id = $this->uri->segment(3);
if (isset($files) && count($files))
{
    ?>
        <a href="javascript:void(0);" class="btn outlined mleft_no reorder_link" id="save_reorder">reorder photos</a>
        <div id="reorder-helper" class="light_box" style="display:none;">1. Drag photos to reorder.<br>2. Click 'Save Reordering' when finished.</div>
        <ul class="reorder_ul reorder-photos-list">
            <?php foreach ($files as $file){ ?>
                <li id="image_li_<?php echo $file['id']; ?>" class="image_wrap ui-sortable-handle">
                    <a href="javascript:void(0);" style="float:none;" class="image_link">
                    <img src="../media/deals/<?php echo $file['path']?>" style="border:2px solid"> 
                    </a>
                    <br />
                    <a href="#" class="delete_file_link" data-file_id="<?php echo $file['id']?>">Delete</a>

                </li>
               
        <?php } ?>
        </ul>
    <?php
}
else
{
    ?>
    <p>No Images Uploaded</p>
    <?php
}
?>