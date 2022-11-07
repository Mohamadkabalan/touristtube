<tr id="hotelFacilitiesRow"><th>facilities (<?= anchor("#", "Edit", array('id' => 'btnEditFacilities'));?>)</th>
    <td>
    <?= $hotel_facility_titles ?>
    </td>
</tr>
<tr id="editFacilitiesRow" style="display: none;"><th>facilities</th>
    <td>
        <input style="width: 100%;" id="mainSltFacilities" type="hidden" data-all='<?= $hotel_facilities; ?>' value="<?= $hotel_facility_ids; ?>" >
        <?= anchor("#", "Save", array('id' => 'btnSaveFacilities'));?> | <?= anchor("#", "Cancel", array('id' => 'btnCancelFacilities'));?> <img id="imgSaving" style="display: none" src="/media/images/ajax-loader.gif"/>
    </td>
</tr>