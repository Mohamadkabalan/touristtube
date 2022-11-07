<?php

//$id = intval($_POST['id']);
$id = intval($request->request->get('id', 0));
$entity_row = socialRateRow($id);
if (socialRateDelete($id)) {
    $ret_arr['status'] = 'ok';
} else {
    $ret_arr['status'] = 'error';
    $ret_arr['error'] = _("Couldn't delete rating please try again later.");
}
$media_row = socialEntityInfo($entity_row['entity_type'], $entity_row['entity_id']);
$allratesnum = $media_row['nb_ratings'];
$allratesVal = round($media_row['rating']);
$ret_arr['rated_val'] = $allratesVal;
$ret_arr['count'] = $allratesnum;