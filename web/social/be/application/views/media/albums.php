<?php $controller = $this->router->class; $k = 0; ?>
<select style="width: 100%;" id="albumsList" size="47">
<?php foreach($albums as $album){ ?>
    <option <?php if($k == 0) echo 'selected="selected"'; else echo ''; ?> value="<?= $album->id ?>"><?= $album->id . ' - '. $album->catalog_name ?></option>
<?php $k++;  } ?>
</select>