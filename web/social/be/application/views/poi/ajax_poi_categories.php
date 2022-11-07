<tr id="poiCategoriesRow"><th>Categories (<?= anchor("#", "Edit", array('id' => 'btnEditCategories'));?>)</th>
    <td>
    <?= $poi_category_titles ?>
    </td>
</tr>
<tr id="editCategoriesRow" style="display: none;"><th>Categories</th>
    <td>
        <select multiple style="width: 100%;" id="mainSltCategories" style="display: none;" >
            <?php foreach($categories_all as $category) { ?>
            <option value="<?= $category['id'] ?>" <?php if($category['selected']) echo 'selected'; ?>><?= $category['text'] ?></option>
            <?php } ?>
        </select>
        <?= anchor("#", "Save", array('id' => 'btnSaveCategories'));?> | <?= anchor("#", "Cancel", array('id' => 'btnCancelCategories'));?> <img id="imgSaving" style="display: none" src="/media/images/ajax-loader.gif"/>
    </td>
</tr>