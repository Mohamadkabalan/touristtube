<h1><?= $title ?></h1>
<input id="hotel_id" type="hidden" value="<?= $hotel_id ?>">
<br>
<div style="margin-bottom: 40px; overflow: hidden;" class="form-group">
    <div style="float: left; margin-right: 10px;">Add new amenity</div>
    <select id="amenity_select" style="float: left;">
        <?php foreach($amenities_all as $amenity) {?>
        <option data-has-count="<?= $amenity['has_count'] ?>" value="<?= $amenity['id'] ?>"><?= $amenity['name'] ?></option>
        <?php }?>
    </select>
    <button style="float: left; margin-left: 10px;" id="add_amenity">Add</button>
</div>
<div id="amenities_container">
    <?php foreach($hotel_amenities as $amenity){ ?>
    <div data-id="<?= $amenity->id ?>" class="form-group amenity-container" style="overflow: hidden;">
        <input class="remove_amenity" style="float: left" type="button" value="Remove" >
        <label class="col-md-2 control-label"><?= $amenity->name ?></label>
        <?php if($amenity->has_count){?>
        <div class="col-md-9">
            <input style="width: 70px;" type="text" value="<?= $amenity->join_count_value ?>" class="form-control">
        </div>
        <?php } ?>
    </div>
    <?php }?>
</div>


<input style="width: 67px" type="button" value="Save" id="save_amenities">