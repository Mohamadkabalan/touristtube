<tr id="hotelFacilitiesRow"><th>Facilities<?= $user_data['role'] != 'hotel_desc_writers' ? ' ('.anchor("#", "Edit", array('id' => 'btnEditFacilities')).')' : '';?></th>
    <td>
    <?= $hotel_facility_titles ?>
    </td>
</tr>
<tr id="editFacilitiesRow" style="display: none;"><th>Facilities</th>
    <td>
        <select multiple style="width: 100%;display: none;" id="mainSltFacilities" >
            <?php foreach($facilities_all as $facility) { ?>
            <option value="<?= $facility['id'] ?>" <?php if($facility['selected']) echo 'selected'; ?>><?= $facility['text'] ?></option>
            <?php } ?>
        </select>
        <?= anchor("#", "Save", array('id' => 'btnSaveFacilities'));?> | <?= anchor("#", "Cancel", array('id' => 'btnCancelFacilities'));?> <img id="imgSaving" style="display: none" src="/media/images/ajax-loader.gif"/>
    </td>
</tr>