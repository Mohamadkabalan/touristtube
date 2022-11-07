<?php

/*
 * Read from csv and write row by row the details of user ( show full name not username)
 * username is generated paravision.time()
 * images get from two folders Fimg and Mimg for female and male users
 * the csv file should have this format
 * [first name], [last name], [email], [birthday], [gender]
 * example:
 * joe, smith, joe.smith@gmail.com, 1988-11-19, M
 * rana, smith, rana.smith@gmail.com, 1990-10-16, F
 *
 * The images should be 165 X 165
 */

$path = "../";

$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );

$fileFullPath = $CONFIG['server']['root'] . "tools/file.csv";
$m = "1";
$f = '1';
$fp = fopen($fileFullPath, "r"); // Open the file for reading

while ($line = fgets($fp)) { // Loop through each line
    list ($fname, $lname, $YourEmail, $YourBdaySave, $gender) = split(",", $line); // Split the line by the tab delimiter and store it in our list
    $YourUserName = "para" .rand(100,9999);
    $YourPassword = "paravision123";
    $FullName = $fname . " " . $lname;
    $gender = trim($gender);
    list ($month, $day, $year) = split("/", $YourBdaySave, 3);
    $YourBdaySave = $year."-".$month."-".$day;

    echo "[".$fname."] - - [".$lname."] - - [".$YourEmail."] - - [".$YourBdaySave."] - - [".$gender."] - - [".$YourUserName."] - - [".$YourPassword."] - - [".$FullName."] - - <br />";
    
    $usid = userRegister($FullName, $YourEmail, '', $YourBdaySave, $YourUserName, $YourPassword, $fname, $lname, 0, $gender, '');
    echo "user created:" . $usid . "<br />";
    if ($gender == "F") {
        $theImg = $CONFIG['server']['root'] . 'tools/Fimg/' . $f.".jpg";
//        $f++;
    } else {
        $theImg = $CONFIG['server']['root'] . 'tools/Mimg/' . $m.".jpg";
//        $m++;
    }
    $picname = 'Profile_' . time() . '_' . $usid . '.jpg';
    $uploaddir = $CONFIG['server']['root'] . 'media/tubers/';

    copy($theImg, $uploaddir . $picname);

    echo "image copied from:" . $theImg . " ---To:--- " . $uploaddir . $picname . "<br />";

    photoThumbnailCreate($uploaddir . $picname, $uploaddir . 'cropable_' . $picname, "165", "165");

    echo "Thumbnail Created<br />";

    if (userSetProfilePic($usid, $uploaddir . $picname, $uploaddir . $picname,0)) {
        userCropPhoto($picname);

        echo "Photo cropped<br />";
    } else {
        echo "Photo not cropped<br />";
    }
    echo "<br /><br /><br />";
}