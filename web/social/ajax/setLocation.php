<?php

$path = '../';
$bootOptions = array("loadDb" => 1, 'requireLogin' => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/users.php" );

//$latitude = floatval($_POST['latitude']);
//$longitude = floatval($_POST['longitude']);
//$set = isset($_POST['set']) ? intval($_POST['set']) : 0;
$latitude = floatval($request->request->get('latitude', 0));
$longitude = floatval($request->request->get('longitude', 0));
$set = intval($request->request->get('set', 0));

$uid = $_COOKIE["lt"];
$user_id = userIsLogged() ? userGetID() : null;
$radius = 750;
if ($set === 1 && $user_id && $uid!='') userSetLocation($uid, $user_id, $latitude, $longitude);

$ret = array();

//$tubers = tubersGetByLocation($latitude, $longitude, $radius);
$srch_options = array('latitude' => $latitude, 'longitude' => $longitude, 'radius' => $radius);
$tubers = tuberSearch($srch_options);
//$tubers = tubersGetByLocation($latitude, $longitude, $radius);

foreach ($tubers as $tuber) {
    $row = array();

    if ($tuber['uid'] == $uid)
        continue;

    $row['uid'] = intval($tuber['id']);
    $row['type'] = 'tuber';
    $row['latitude'] = $tuber['latitude'];
    $row['longitude'] = $tuber['longitude'];
    $row['image'] = '';

    $row['name'] = '';
    $row['gender'] = '';
    $row['country'] = '';
    $row['age'] = '';

    if ($tuber['id'] != null) {
        //$userInfo = getUserInfo($tuber['user_id']);
        //dont show user image just show marker
        //$row['image'] = 'small_' . $userInfo['profile_Pic'];
        $row['name'] = returnUserDisplayName($tuber);
        $row['gender'] = $tuber['gender'];
        $row['country'] = $tuber['country'];
        if ($tuber['YourBday'] == '0000-00-00' || !$tuber['YourBday'] || is_null($tuber['YourBday'])) {
            $row['age'] = '';
        } else {
            $row['age'] = (time() - strtotime($tuber['YourBday'])) / (365 * 24 * 3600);
        }
    }

    $ret[] = $row;
}

///////////////////////////
/* $test_loop = 0;
  while($test_loop < 10){
  $gen = array('M','F','O');
  $row['uid'] = 10 + $test_loop;
  $row['type'] = 'tuber';
  $row['latitude'] = 48.856614 + (rand(-10,10)/5000.0);
  $row['longitude'] = 2.3522219 + (rand(-10,10)/5000.0);
  $row['name'] = "test" . $test_loop;
  $row['gender'] = $gen[rand(0,2)];
  $row['country'] = 'FR';
  $row['age'] = rand(18,40);
  $ret[] = $row;

  $test_loop++;
  } */
///////////////////////////

$options = array('latitude' => $latitude, 'longitude' => $longitude, 'radius' => $radius, 'limit' => 2000); //, 'type' => 'h'
$res = locationSearch($options);
foreach ($res as $location_row) {

    switch ($location_row['category_id']) {
        case '1':
            $row['type'] = 'restaurant';
            break;
        case '2':
            $row['type'] = 'hotel';
            break;
        default:
            $row['type'] = 'activity';
            break;
    }

    $row['link'] = locationGetURL($location_row);

    $row['latitude'] = $location_row['latitude'];
    $row['longitude'] = $location_row['longitude'];
    $row['image'] = '';
    $row['name'] = $location_row['name'];
    $row['uid'] = $location_row['id'];

    $ret[] = $row;
}

echo json_encode($ret);
