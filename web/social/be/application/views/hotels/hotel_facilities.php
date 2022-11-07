<div>
    <h1 style="float:left;"><?= $title?> </h1>
    <button class="btn btn-primary" id="btnSave">Apply Changes</button>
</div>

<br>
<input id="hotel_id" type="hidden" value="<?= $hotel['id'] ?>">
<div id="facilitiesContainer">
    <?php foreach($facility_types as $type){?>
    <div class="grid-item">
        <ul>
            <li class="typeLabel"><input class="typeCheck" data-id="<?= $type['id']?>" type="checkbox" <?php if($type['selected'] == '1') echo 'checked=checked' ?>><?= $type['name']?></li>
            <?php foreach($type['facilities'] as $facility){ ?>
            <li><input class="facilityCheck" data-type-id="<?= $type['id'] ?>" data-id="<?= $facility['id']?>" type="checkbox" <?php if($facility['selected'] == '1') echo 'checked=checked' ?>><?= $facility['name'] ?></li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>
</div>