<?php
//$path = "";
//$bootOptions = array("loadDb" => 1, 'requireLogin' => false);
//include_once ( $path . "inc/common.php" );
//include_once ( $path . "inc/bootstrap.php" );
//include_once ( $path . "inc/functions/videos.php" );
//include_once ( $path . "inc/functions/users.php" );

///global $CONFIG;
    /*$originalDirectory0 = $CONFIG ['server']['root'] ;
    ini_set('memory_limit', '1000M');

    $query = "SELECT * FROM cms_videos WHERE `image_video`='i' ORDER BY id asc";
    $res = db_query($query);
    //debug($res);
    if($res && (db_num_rows($res)!=0) ){
        while($row = db_fetch_array($res)){
            $originalDirectory = $originalDirectory0.'/'.$row['fullpath'];
            $imageName=$row['name'];
            //resizeUploadedImage3($originalDirectory.$imageName,$originalDirectory. 'small_' . $imageName);
            //resizeUploadedImage4($originalDirectory.$imageName,$originalDirectory. 'xsmall_' .$imageName);
            resizeUploadedImage5($originalDirectory.$imageName,$originalDirectory. 'thumb_' . $imageName);            
        }
    }*/
    
    /*$originalDirectory0 = $CONFIG ['server']['root'] ;
    ini_set('memory_limit', '1000M');

    $query = "SELECT * FROM cms_videos WHERE `image_video`='v' ORDER BY id asc";
    
    $res = db_query($query);
    if($res && (db_num_rows($res)!=0) ){
        while($row = db_fetch_array($res)){//debug($row);
            $originalDirectory = $originalDirectory0.'/'.$row['fullpath'];
            $videoCode = $row[ 'code' ];
            
            $thumbs = glob(  $originalDirectory . $videoCode . "*.jpg" );
            if ( $thumbs && count($thumbs) > 0 ){
                $path_parts = pathinfo($thumbs[0]);
                $filename = $path_parts['filename'];
                resizeUploadedImage3($originalDirectory . $filename.'.jpg',$originalDirectory . 'small_' .  $filename.'.jpg');
                resizeUploadedImage4($originalDirectory . $filename.'.jpg',$originalDirectory . 'xsmall_' .  $filename.'.jpg');
                resizeUploadedImage5($originalDirectory . $filename.'.jpg',$originalDirectory . 'thumb_' .  $filename.'.jpg');
            }
        }
    }*/

    /*$originalDirectory0 = $CONFIG ['server']['root'] ;
    ini_set('memory_limit', '1000M');

    $query = "SELECT * FROM cms_users WHERE `profile_Pic` !=''";
    $res = db_query($query);
    //debug($res);
    if($res && (db_num_rows($res)!=0) ){
        while($row = db_fetch_array($res)){//debug($row);
            $originalDirectory = $originalDirectory0.'/media/images/tubers/';
            $imageName=$row['profile_Pic'];
            //photoThumbnailCreate($originalDirectory.$imageName, $originalDirectory. 'xsmall_' .$imageName ,28,28);
            if($imageName!=''){
            photoThumbnailCreate($originalDirectory.$imageName, $originalDirectory. '' .$imageName ,130,130);
            }
            //resizeUploadedImage4($originalDirectory.$imageName,$originalDirectory. 'xsmall_' .$imageName);
            //createThumbnailFromImage($originalDirectory.$imageName, $originalDirectory. 'thumb_' .$imageName,0,0);
            
        }
    }*/
    
// $val = '/home/nginx1/www/media/videos/uploads/2014/40/thumb_46854/';
//  debug(spriteImage($val));
// 
// dsdfgcf gfdgdf


/*require_once 'vendor/autoload.php';
use MaxMind\Db\Reader;
$ipAddress = '24.24.24.24';
$ipAddress = '89.249.212.3';
$databaseFile = '/home/para-tube/www/GeoLite2-Country.mmdb';
$reader = new Reader($databaseFile);
print_r($reader->get($ipAddress));
$reader->close();*/

$path = "../../";
    $bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
    
    include_once ( $path . "inc/functions/videos.php" );
 
    //$user_ID = userGetID();
    //$freinds = userGetChatList($user_ID);
    
    //debug($freinds);
ini_set('display_errors',1);

/*$sql="SELECT distinct dir FROM `datastellar_hotel_image`";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $dest_dir ='be/uploads/thumb/' .$r['dir'];
    @mkdir($dest_dir);
    @chmod($dest_dir, '777');
}*/

/*$sql="SELECT * FROM `datastellar_hotel_image`";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $sqr= "INSERT INTO `discover_hotels_images`(`user_id`, `filename`, `hotel_id`) VALUES (0,'".$r['path']."','".$r['hotel_id']."')";
    $filename=$r['path'];    
    photoThumbnailCreate('be/uploads/'. $filename,  'be/uploads/thumb/' . $filename , 175, 109);
    mysql_query($sqr);    
}*/
global $CONFIG;
/*$sql="SELECT i.*, h.hotelName as name FROM `discover_hotels_images` as i inner join discover_hotels as h on h.id = i.hotel_id where i.hotel_id=288509";
//$sql="SELECT i.*, h.name as name FROM `discover_restaurants_images` as i inner join discover_restaurants as h on h.id = i.restaurant_id";
//$sql="SELECT i.*, h.name as name FROM `discover_poi_images` as i inner join discover_poi as h on h.id = i.poi_id";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
$k=1;
while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $filename = $r['filename'];
    $name = $r['name'];
    $title = cleanTitle($name);
    $for = strrpos($filename, '.');
    $ext = substr($filename, $for);
    $newname = $title.'_hotel_'. $k.$ext;
            
    $absolutePath = $CONFIG ['server']['root'];
    
    $old = $absolutePath . 'media/discover/' . $filename;
    $new = $absolutePath . 'media/discover/' . $newname;
    rename($old, $new);
    
    $old1 = $absolutePath . 'media/discover/thumb/' . $filename;
    $new1 = $absolutePath . 'media/discover/thumb/' . $newname;
    rename($old1, $new1);
    debug($newname);
    
    $sqr= "UPDATE `discover_hotels_images` SET `filename`='".$newname."' WHERE id=".$r['id'];
    mysql_query($sqr);
    $k++;
}*/

/*$sql="SELECT * FROM `discover_hotels_images`";
//$sql="SELECT * FROM `cms_videos` where `image_video`='v'";
$results = mysql_query($sql) or die( mysql_error());
$un_id='';
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
     $fullPath_exist = $CONFIG ['server']['root'] . 'media/discover/' . $r['filename'];
     if (!file_exists($fullPath_exist)) $un_id .= $r['id'].',';
}
echo $un_id;*/

$sql="SELECT * FROM `discover_hotels_images` where hotel_id=288509";
//$sql="SELECT * FROM `cms_videos` where `image_video`='v'";
$results = mysql_query($sql) or die( mysql_error());
$un_id='';
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {     
        $filename = $r['filename'];
        $pathto = $CONFIG ['server']['root'] . 'media/discover/';
        
        $for = strrpos($filename, '_') + 1;
        $extbig = substr($filename, $for);
        
        if (!file_exists($pathto.$extbig) ) $extbig= $filename ;
        
        $minfo = mediaFileInfo($pathto.$extbig);
        $width = mediaFileWidth($minfo);
        $height = mediaFileHeight($minfo);

        $scalex = $width/994;
        $scaley = $height/530;
        $scale = $scalex;
        if($scaley>$scalex) $scale = $scaley;

        $ww = round($width/$scale);
        $hh = round($height/$scale);

        photoThumbnailCreate($pathto. $extbig, $pathto . 'large/' . $filename , $ww, $hh);
}

/*$sql="SELECT * FROM `cms_videos` where `image_video`='i' and id IN (44327,44352,44376,46851,46852,46855,46856)";
//$sql="SELECT * FROM `cms_videos` where `image_video`='v'";
$results = mysql_query($sql) or die( mysql_error());
$un_id='';
$absolutePath = $CONFIG ['server']['root'];
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    //resizeUploadedImage($absolutePath. $row['fullpath'] . $r ['name'], $absolutePath . $r ['name']);
     $row = $r;
    resizeUploadedImage2($absolutePath. $row['fullpath'] . $r ['name'], $absolutePath. $row['fullpath'] . 'med_' . $r ['name']);
    resizeUploadedImage3($absolutePath. $row['fullpath'] . $r ['name'], $absolutePath. $row['fullpath'] . 'small_' . $r ['name']);
    resizeUploadedImage4($absolutePath. $row['fullpath'] . $r ['name'], $absolutePath. $row['fullpath'] . 'xsmall_' . $r ['name']);
    resizeUploadedImage5($absolutePath. $row['fullpath'] . $r ['name'], $absolutePath. $row['fullpath'] . 'thumb_' . $r ['name']);
    
}*/

function cleanTitle($title) {
    $ret = remove_accents($title);
    $ret = str_replace(' ', '-', $ret);
    $ret = str_replace(' ', '-', $ret);
    $ret = preg_replace('/[^a-z0-9A-Z\-]/', '', $ret);
    $ret = str_replace('--', '-', $ret);

    return $ret;
}
function seems_utf8($str) {
    $length = strlen($str);
    for ($i = 0; $i < $length; $i++) {
        $c = ord($str[$i]);
        if ($c < 0x80)
            $n = 0;# 0bbbbbbb
        elseif (($c & 0xE0) == 0xC0)
            $n = 1;# 110bbbbb
        elseif (($c & 0xF0) == 0xE0)
            $n = 2;# 1110bbbb
        elseif (($c & 0xF8) == 0xF0)
            $n = 3;# 11110bbb
        elseif (($c & 0xFC) == 0xF8)
            $n = 4;# 111110bb
        elseif (($c & 0xFE) == 0xFC)
            $n = 5;# 1111110b
        else
            return false;# Does not match any model
        for ($j = 0; $j < $n; $j++) { # n bytes matching 10bbbbbb follow ?
            if (( ++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                return false;
        }
    }
    return true;
}

function remove_accents($string) {
    if (!preg_match('/[\x80-\xff]/', $string))
        return $string;

    if (seems_utf8($string)) {
        $chars = array(
            // Decompositions for Latin-1 Supplement
            chr(195) . chr(128) => 'A', chr(195) . chr(129) => 'A',
            chr(195) . chr(130) => 'A', chr(195) . chr(131) => 'A',
            chr(195) . chr(132) => 'A', chr(195) . chr(133) => 'A',
            chr(195) . chr(135) => 'C', chr(195) . chr(136) => 'E',
            chr(195) . chr(137) => 'E', chr(195) . chr(138) => 'E',
            chr(195) . chr(139) => 'E', chr(195) . chr(140) => 'I',
            chr(195) . chr(141) => 'I', chr(195) . chr(142) => 'I',
            chr(195) . chr(143) => 'I', chr(195) . chr(145) => 'N',
            chr(195) . chr(146) => 'O', chr(195) . chr(147) => 'O',
            chr(195) . chr(148) => 'O', chr(195) . chr(149) => 'O',
            chr(195) . chr(150) => 'O', chr(195) . chr(153) => 'U',
            chr(195) . chr(154) => 'U', chr(195) . chr(155) => 'U',
            chr(195) . chr(156) => 'U', chr(195) . chr(157) => 'Y',
            chr(195) . chr(159) => 's', chr(195) . chr(160) => 'a',
            chr(195) . chr(161) => 'a', chr(195) . chr(162) => 'a',
            chr(195) . chr(163) => 'a', chr(195) . chr(164) => 'a',
            chr(195) . chr(165) => 'a', chr(195) . chr(167) => 'c',
            chr(195) . chr(168) => 'e', chr(195) . chr(169) => 'e',
            chr(195) . chr(170) => 'e', chr(195) . chr(171) => 'e',
            chr(195) . chr(172) => 'i', chr(195) . chr(173) => 'i',
            chr(195) . chr(174) => 'i', chr(195) . chr(175) => 'i',
            chr(195) . chr(177) => 'n', chr(195) . chr(178) => 'o',
            chr(195) . chr(179) => 'o', chr(195) . chr(180) => 'o',
            chr(195) . chr(181) => 'o', chr(195) . chr(182) => 'o',
            chr(195) . chr(182) => 'o', chr(195) . chr(185) => 'u',
            chr(195) . chr(186) => 'u', chr(195) . chr(187) => 'u',
            chr(195) . chr(188) => 'u', chr(195) . chr(189) => 'y',
            chr(195) . chr(191) => 'y',
            // Decompositions for Latin Extended-A
            chr(196) . chr(128) => 'A', chr(196) . chr(129) => 'a',
            chr(196) . chr(130) => 'A', chr(196) . chr(131) => 'a',
            chr(196) . chr(132) => 'A', chr(196) . chr(133) => 'a',
            chr(196) . chr(134) => 'C', chr(196) . chr(135) => 'c',
            chr(196) . chr(136) => 'C', chr(196) . chr(137) => 'c',
            chr(196) . chr(138) => 'C', chr(196) . chr(139) => 'c',
            chr(196) . chr(140) => 'C', chr(196) . chr(141) => 'c',
            chr(196) . chr(142) => 'D', chr(196) . chr(143) => 'd',
            chr(196) . chr(144) => 'D', chr(196) . chr(145) => 'd',
            chr(196) . chr(146) => 'E', chr(196) . chr(147) => 'e',
            chr(196) . chr(148) => 'E', chr(196) . chr(149) => 'e',
            chr(196) . chr(150) => 'E', chr(196) . chr(151) => 'e',
            chr(196) . chr(152) => 'E', chr(196) . chr(153) => 'e',
            chr(196) . chr(154) => 'E', chr(196) . chr(155) => 'e',
            chr(196) . chr(156) => 'G', chr(196) . chr(157) => 'g',
            chr(196) . chr(158) => 'G', chr(196) . chr(159) => 'g',
            chr(196) . chr(160) => 'G', chr(196) . chr(161) => 'g',
            chr(196) . chr(162) => 'G', chr(196) . chr(163) => 'g',
            chr(196) . chr(164) => 'H', chr(196) . chr(165) => 'h',
            chr(196) . chr(166) => 'H', chr(196) . chr(167) => 'h',
            chr(196) . chr(168) => 'I', chr(196) . chr(169) => 'i',
            chr(196) . chr(170) => 'I', chr(196) . chr(171) => 'i',
            chr(196) . chr(172) => 'I', chr(196) . chr(173) => 'i',
            chr(196) . chr(174) => 'I', chr(196) . chr(175) => 'i',
            chr(196) . chr(176) => 'I', chr(196) . chr(177) => 'i',
            chr(196) . chr(178) => 'IJ', chr(196) . chr(179) => 'ij',
            chr(196) . chr(180) => 'J', chr(196) . chr(181) => 'j',
            chr(196) . chr(182) => 'K', chr(196) . chr(183) => 'k',
            chr(196) . chr(184) => 'k', chr(196) . chr(185) => 'L',
            chr(196) . chr(186) => 'l', chr(196) . chr(187) => 'L',
            chr(196) . chr(188) => 'l', chr(196) . chr(189) => 'L',
            chr(196) . chr(190) => 'l', chr(196) . chr(191) => 'L',
            chr(197) . chr(128) => 'l', chr(197) . chr(129) => 'L',
            chr(197) . chr(130) => 'l', chr(197) . chr(131) => 'N',
            chr(197) . chr(132) => 'n', chr(197) . chr(133) => 'N',
            chr(197) . chr(134) => 'n', chr(197) . chr(135) => 'N',
            chr(197) . chr(136) => 'n', chr(197) . chr(137) => 'N',
            chr(197) . chr(138) => 'n', chr(197) . chr(139) => 'N',
            chr(197) . chr(140) => 'O', chr(197) . chr(141) => 'o',
            chr(197) . chr(142) => 'O', chr(197) . chr(143) => 'o',
            chr(197) . chr(144) => 'O', chr(197) . chr(145) => 'o',
            chr(197) . chr(146) => 'OE', chr(197) . chr(147) => 'oe',
            chr(197) . chr(148) => 'R', chr(197) . chr(149) => 'r',
            chr(197) . chr(150) => 'R', chr(197) . chr(151) => 'r',
            chr(197) . chr(152) => 'R', chr(197) . chr(153) => 'r',
            chr(197) . chr(154) => 'S', chr(197) . chr(155) => 's',
            chr(197) . chr(156) => 'S', chr(197) . chr(157) => 's',
            chr(197) . chr(158) => 'S', chr(197) . chr(159) => 's',
            chr(197) . chr(160) => 'S', chr(197) . chr(161) => 's',
            chr(197) . chr(162) => 'T', chr(197) . chr(163) => 't',
            chr(197) . chr(164) => 'T', chr(197) . chr(165) => 't',
            chr(197) . chr(166) => 'T', chr(197) . chr(167) => 't',
            chr(197) . chr(168) => 'U', chr(197) . chr(169) => 'u',
            chr(197) . chr(170) => 'U', chr(197) . chr(171) => 'u',
            chr(197) . chr(172) => 'U', chr(197) . chr(173) => 'u',
            chr(197) . chr(174) => 'U', chr(197) . chr(175) => 'u',
            chr(197) . chr(176) => 'U', chr(197) . chr(177) => 'u',
            chr(197) . chr(178) => 'U', chr(197) . chr(179) => 'u',
            chr(197) . chr(180) => 'W', chr(197) . chr(181) => 'w',
            chr(197) . chr(182) => 'Y', chr(197) . chr(183) => 'y',
            chr(197) . chr(184) => 'Y', chr(197) . chr(185) => 'Z',
            chr(197) . chr(186) => 'z', chr(197) . chr(187) => 'Z',
            chr(197) . chr(188) => 'z', chr(197) . chr(189) => 'Z',
            chr(197) . chr(190) => 'z', chr(197) . chr(191) => 's',
            // Euro Sign
            chr(226) . chr(130) . chr(172) => 'E',
            // GBP (Pound) Sign
            chr(194) . chr(163) => '');

        $string = strtr($string, $chars);
    } else {
        // Assume ISO-8859-1 if not UTF-8
        $chars['in'] = chr(128) . chr(131) . chr(138) . chr(142) . chr(154) . chr(158)
                . chr(159) . chr(162) . chr(165) . chr(181) . chr(192) . chr(193) . chr(194)
                . chr(195) . chr(196) . chr(197) . chr(199) . chr(200) . chr(201) . chr(202)
                . chr(203) . chr(204) . chr(205) . chr(206) . chr(207) . chr(209) . chr(210)
                . chr(211) . chr(212) . chr(213) . chr(214) . chr(216) . chr(217) . chr(218)
                . chr(219) . chr(220) . chr(221) . chr(224) . chr(225) . chr(226) . chr(227)
                . chr(228) . chr(229) . chr(231) . chr(232) . chr(233) . chr(234) . chr(235)
                . chr(236) . chr(237) . chr(238) . chr(239) . chr(241) . chr(242) . chr(243)
                . chr(244) . chr(245) . chr(246) . chr(248) . chr(249) . chr(250) . chr(251)
                . chr(252) . chr(253) . chr(255);

        $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

        $string = strtr($string, $chars['in'], $chars['out']);
        $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
        $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
        $string = str_replace($double_chars['in'], $double_chars['out'], $string);
    }

    return $string;
}
