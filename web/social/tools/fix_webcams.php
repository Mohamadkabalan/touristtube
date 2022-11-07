<?php
$path = "";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
$query = 'SELECT c.id, c.name, (
SELECT COUNT( w.id ) 
FROM webgeocities AS w
WHERE c.country_code = w.country_code
AND c.name = w.name
) AS cnt, (SELECT  w.id 
FROM webgeocities AS w
WHERE c.country_code = w.country_code
AND c.name = w.name LIMIT 1) as wid
FROM cms_webcams AS v
INNER JOIN webgeocities AS c ON c.id = v.city_id
WHERE v.city_id <=1427
group by v.city_id
ORDER BY cnt DESC ';
$res = db_query($query);
while($row = db_fetch_array($res)){
    if($row['cnt'] > 1)
        continue;
    $old_city_id = $row['id'];
    $new_city_id =$row['wid'];
    $update_query = "UPDATE `cms_webcams` SET `city_id`=$new_city_id  WHERE `city_id` in ($old_city_id)";
    db_query($update_query);
}