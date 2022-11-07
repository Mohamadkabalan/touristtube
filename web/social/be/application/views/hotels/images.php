<div class="slider-for">
    <?php
    $hotel_id = $hotel['id']; 

    if( $this->config->item('upload_src') == "s3" ){
        $prefix = 'https://' . $this->config->item('bucketName') . ".". S3::$endpoint;
    }
    else{
        $prefix = "..";
    }

    foreach($hotel_images as $image) { 
        $image_id = $image['id'];
        $image_locatcion = $image['location'];
        $image_filename = $image['filename'];


        $image_url = $prefix . '/media/hotels/' . $image['hotel_id'] . '/' . $image['location'] . '/' . $image['filename'] . '?d=994x530';
        ?>

        <div class="slider-for-item" data-id="<?= $image['id'] ?>">
            <div style="height: 530px; width: 994px;  background-image: url('<?= $image_url ?>'); background-repeat:no-repeat;"></div>
            <div class="btn_delete"><a href="<?php echo site_url('hotels/ajax_delete_image2/'.$image['id']); ?>">X</a></div>
            <div style="height: 100px; left: 0px; z-index: 20; position: absolute; bottom: -108px;">
                <h1> Change the above Image Type </h1>
                <form method="post" action="<?php echo site_url('hotels/ajax_edit_location/'); ?>" id="edit_image_type">
                    <select name="image_types" id="image_types" class="image_types"> 
                        <?php 
                        foreach($locations as $locationes){ 
                            $selected = ($locationes == $image_locatcion) ? ' selected="selected"' : '';
                            ?>
                            <option <?=$selected?> id="<?= $locationes ?>"><?= $locationes ?></option>
                        <?php } ?>
                    </select><br>
                    <input type="hidden" name="image_id" id="image_id" value="<?= $image_id ?>" />
                    <input type="hidden" name="old_location" id="old_location" value="<?= $image_locatcion ?>" />
                    <input type="hidden" name="image_name" id="image_name" value="<?= $image_filename ?>" />
                    <input type="hidden" name="hotel_id" id="hotel_id" value="<?= $hotel_id ?>" />
                    <input type="submit" name="submit" id="submit" />
                </form>
            </div>
        </div>
    <?php } ?>
</div>

<div class="slider-nav">
    <?php 
    $count = 0;
    $x=0;
    foreach($hotel_images as $image) { 
        $image_url = $prefix . '/media/hotels/' . $image['hotel_id'] . '/' . $image['location'] . '/' . $image['filename']. '?d=100x71';
        if($hotel['id']){
            $id = $hotel['id'];
        }
        ?>
        <div class="mediathumb slider-nav-item" data-id="<?= $image['id'] ?>" <?php if(($count == 0 && !in_array($image['location'], $location_condition2) || $x == 10)  ){ $count = 1; $x=10;?>  style="background-color: black;" <?php }?>>
            <div class="mediathumbcontainer"><img src="<?= $image_url ?>" /></div>
            <div class="mediathumbbk"></div>
            <?php if($image['default_pic'] == 1){ ?>
                <div class="defaultBtn active" data-file_id="<?php echo $id ?>"><span>Default</span></div>
            <?php }else{ ?>
                <a href="<?php echo site_url('hotels/default_image_update/'.$id.'/'.$image['id'])?>" class="defaultBtn" data-file_id="<?php echo $id ?>"><span>Default</span></a>
            <?php } ?>
        </div>
        
    <?php } ?>
</div>

