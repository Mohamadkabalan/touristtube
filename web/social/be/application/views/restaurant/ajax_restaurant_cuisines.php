<tr id="restaurantCuisinesRow"><th>Cuisines (<?= anchor("#", "Edit", array('id' => 'btnEditCuisines'));?>)</th>
    <td>
    <?= $restaurant_cuisine_titles ?>
    </td>
</tr>
<tr id="editCuisinesRow" style="display: none;"><th>Cuisines</th>
    <td>
        <select multiple style="width: 100%;" id="mainSltCuisines" style="display: none;" >
            <?php foreach($cuisines_all as $cuisine) { ?>
            <option value="<?= $cuisine['id'] ?>" <?php if($cuisine['selected']) echo 'selected'; ?>><?= $cuisine['text'] ?></option>
            <?php } ?>
        </select>
        <?= anchor("#", "Save", array('id' => 'btnSaveCuisines'));?> | <?= anchor("#", "Cancel", array('id' => 'btnCancelCuisines'));?> <img id="imgSaving" style="display: none" src="/media/images/ajax-loader.gif"/>
    </td>
</tr>