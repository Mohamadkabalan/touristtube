<?php
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
$all_cats_query = "SELECT id, title FROM discover_categs";
$all_cats_res = db_query($all_cats_query);
$all_cats = array();
while($row = db_fetch_array($all_cats_res)){
    $all_cats[] = $row;
}
$query = "SELECT id, cat, sub_cat FROM discover_poi where cat <> '' and sub_cat <> ''";
$res = db_query($query);
while($row = db_fetch_array($res)){
    $poi_id = $row['id'];
    $cat = $row['cat'];
    $sub_cat = $row['sub_cat'];
    $cat_term = '';
    if($cat == "Tourism"){
        switch($sub_cat){
            case "Spring": case "Peak":
                $cat_term = "Nature";
            break;
            case "View point":
                $cat_term = "Viewpoint";
            break;
            case "Camp site":
                $cat_term = "Campsite";
            break;
            case "Public pieces of art":
                $cat_term = "Art and Culture";
            break;
            default :
                $cat_term = $sub_cat;
            break;
        }
    }
    else {
        switch($cat){
            case "Leisure":
                $cat_term = "Entertainment";
            break;
            case "Automotive":
                $cat_term = "Transport";
            break;
            default:
                $cat_term = $cat;
        }
    }
    foreach ($all_cats as $category){
        if($category['title'] == $cat_term)
        {
            $cat_id = $category['id'];
            break;
        }
    }
    $select_query = "SELECT * FROM discover_poi_categ WHERE categ_id = $cat_id AND poi_id = $poi_id";
    $select_query_res = db_query($select_query);
    if(db_num_rows($select_query_res) == 0) {
        $insert_query = "INSERT INTO discover_poi_categ(categ_id, poi_id) VALUES ($cat_id, $poi_id)";
        db_query($insert_query);
    }
}
echo 'done';