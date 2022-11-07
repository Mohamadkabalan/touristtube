<?php

set_time_limit(0);
ini_set('display_errors', 0);

$database_name = "touristtube";

//$absolutePath = '/home/para-tube/www/';
$absolutePath = '/home/nginx1/www/';

//mysql_connect('localhost', 'root', 'mysql_root');
mysql_connect( "192.168.2.5" , "root" , "7mq17psb" );
mysql_select_db($database_name);

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

function renameQuery($row) {
    $id = $row['id'];
    $name = $row['name'];
    $title = $row['title'];
    $title = remove_accents($title);
    $title = str_replace(' ', '-', $title);
    $title = str_replace(' ', '-', $title);
    $title = preg_replace('/[^a-z0-9A-Z\-]/', '', $title);
    $title = str_replace('--', '-', $title);

    $for = strrpos($name, '.');
    $ext = substr($name, $for);

    $name = $title . '-' . time() . rand(100, 999) . $ext;
    $query = "UPDATE `cms_videos` SET `old`=`name`,`name`='$name'"
            . " where `id` = $id";

    if ($ret = mysql_query($query)) {
        return 0;
    } else {
        return $id;
    }
}

function renameMediaDB() {
    $selQ = 'SELECT * FROM `cms_videos` ';
    $selQ .= "WHERE `image_video` = 'i' ";
//    $selQ .= "AND `id` = 46858"; //for test
    $ret = mysql_query($selQ);
    while ($row = mysql_fetch_array($ret)) {
        $toEcho = renameQuery($row);
        if ($toEcho != 0) {
            writeLine("(db) ID : [ " . $toEcho . " ]");
        }
    }
}

function renameMediaFiles() {
    $selQ = 'SELECT * FROM `cms_videos` ';
    $selQ .= "where `image_video` like 'i' ";
//    $selQ .= "AND `id` = 46858 "; //for test
    $ret = mysql_query($selQ);

    global $absolutePath;

    while ($row = mysql_fetch_array($ret)) {
        $old = $absolutePath . $row['fullpath'] . $row['old'];
        $new = $absolutePath . $row['fullpath'] . $row['name'];
        if (!rename($old, $new)) {
            writeLine('(img) ID : [ ' . $row['id'] . " ] | OLD : [ " . $old . " ] | NEW : [ " . $new . " ]");
        }
        $old = $absolutePath . $row['fullpath'] . 'thumb_' . $row['old'];
        $new = $absolutePath . $row['fullpath'] . 'thumb_' . $row['name'];
        if (!rename($old, $new)) {
            writeLine('(img) ID : [ ' . $row['id'] . " ] | OLD : [ " . $old . " ] | NEW : [ " . $new . " ]");
        }
        $old = $absolutePath . $row['fullpath'] . 'xsmall_' . $row['old'];
        $new = $absolutePath . $row['fullpath'] . 'xsmall_' . $row['name'];
        if (!rename($old, $new)) {
            writeLine('(img) ID : [ ' . $row['id'] . " ] | OLD : [ " . $old . " ] | NEW : [ " . $new . " ]");
        }
        $old = $absolutePath . $row['fullpath'] . 'small_' . $row['old'];
        $new = $absolutePath . $row['fullpath'] . 'small_' . $row['name'];
        if (!rename($old, $new)) {
            writeLine('(img) ID : [ ' . $row['id'] . " ] | OLD : [ " . $old . " ] | NEW : [ " . $new . " ]");
        }
        $old = $absolutePath . $row['fullpath'] . 'org_' . $row['old'];
        $new = $absolutePath . $row['fullpath'] . 'org_' . $row['name'];
        if (!rename($old, $new)) {
            writeLine('(img) ID : [ ' . $row['id'] . " ] | OLD : [ " . $old . " ] | NEW : [ " . $new . " ]");
        }
        $old = $absolutePath . $row['fullpath'] . 'med_' . $row['old'];
        $new = $absolutePath . $row['fullpath'] . 'med_' . $row['name'];
        if (!rename($old, $new)) {
            writeLine('(img) ID : [ ' . $row['id'] . " ] | OLD : [ " . $old . " ] | NEW : [ " . $new . " ]");
        }
    }
}

//first we call renameMediaDB to rename in DB then renameMediaFiles to change the file name
//renameMediaDB();
//renameMediaFiles();

/**
 * clean the title
 */
function cleanTitle($title) {
    $ret = remove_accents($title);
    $ret = str_replace(' ', '-', $ret);
    $ret = str_replace(' ', '-', $ret);
    $ret = preg_replace('/[^a-z0-9A-Z\-]/', '', $ret);
    $ret = str_replace('--', '-', $ret);

    return $ret;
}

function renameVDB() {
    $selQ = 'SELECT * FROM `cms_videos` ';
    $selQ .= "WHERE id IN (50,10149,10150,10151,10157,10159,10160,10161,10162,10163,10164,10165,10166,10167,10168,10169,10170,10171,10172,39993,40738,41977)";
//    $selQ .= "AND (`id` = 49678)"; //for test
    $ret = mysql_query($selQ);
    
    while ($row = mysql_fetch_array($ret)) {
        writeLine("--------------");
        writeLine("ID : [ " . $row['id'] . " ]");
        writeLine("--------------");
        $toEcho = updateDbAndRename($row);
        if ($toEcho != 0) {
            writeLine("(db) ID : [ " . $toEcho . " ]");
        }
    }
}

/**
 * get video name and extension from old video name
 */
function updateDbAndRename($row) {
    global $absolutePath;
    $id = $row['id'];
    $code = $row['code'];
    $name = $row['name'];
    $title = cleanTitle($row['title']);

    $for = strrpos($name, '.');

    $ext = substr($name, $for);

    $VideoName = substr($name, 0, $for);

    
    $path = $absolutePath . $row['fullpath'];
    $name = $namewoExt . $ext;
   
    renameVideosAndThumbs(array('ext' => $ext, 'oldVideoName' => $code, 'namewoExt' => $VideoName, 'id' => $id, 'path' => $path, 'code' => $row['code']));
    
    /*$query = "UPDATE `cms_videos` SET `old`='".$oldVideoName.$ext."',`name`='$name'"
            . " where `id` = $id";
//    exit(var_dump($query));
    if ($ret = mysql_query($query)) {
        renameVideosAndThumbs(array('ext' => $ext, 'oldVideoName' => $oldVideoName, 'namewoExt' => $namewoExt, 'id' => $id, 'path' => $path, 'code' => $row['code']));
        return 0;
    } else {
        return $id;
    }*/
}

function renameVideosAndThumbs($arr) {
    //rename thumbs
    $thumbPrefixArr = array('', 'large_', 'small_', 'xsmall_', 'thumb_');
    $vidPrefixArr = array('1920x1080', '1280x720', '860x480', '640x360', '430x240');
    for ($i = 1; $i <= 3; $i++) {
        foreach ($thumbPrefixArr as $thumbPrefix) {
            //$old = $arr['path'] . $thumbPrefix . $arr['code'] . "_" . $i . "_*.jpg";
            $old = glob($arr['path'] . $thumbPrefix . $arr['code'] . "_" . $i . "_*.jpg");
            $old = $old[0];
            
            $new = $arr['path'] . $thumbPrefix . "_" . $arr['namewoExt'] . "_" . $arr['code'] . "_" . $i . '_.jpg';
            if (!rename($old, $new)) {
                writeLine('(vt) ID : [ ' . $arr['id'] . " ] | OLD : [ " . $old . " ] | NEW : [ " . $new . " ]");
            }
        }
    }
    //rename video
    //original:
    /*$oldOrig = $arr['path'] . $arr['oldVideoName'] . $arr['ext'];
    $newOrig = $arr['path'] . $arr['namewoExt'] . $arr['ext'];
    if (!rename($oldOrig, $newOrig)) {
        writeLine('(vo) ID : [ ' . $arr['id'] . " ] | OLD : [ " . $oldOrig . " ] | NEW : [ " . $newOrig . " ]");
    }
    //resized
    foreach ($vidPrefixArr as $vidPrefix) {
        $old = $arr['path'] . $vidPrefix . $arr['oldVideoName'] . '.mp4';
        $new = $arr['path'] . $vidPrefix . $arr['namewoExt'] . '.mp4';
        if (!rename($old, $new)) {
            writeLine('(vc) ID : [ ' . $arr['id'] . " ] | OLD : [ " . $old . " ] | NEW : [ " . $new . " ]");
        }
    }*/
}

function writeLine($txt) {
    global $absolutePath;
    $file = $absolutePath . 'be/maintenance/rename.log';
    file_put_contents($file, $txt . PHP_EOL, FILE_APPEND);
//    echo $txt."<br>";
}

//renameVDB();