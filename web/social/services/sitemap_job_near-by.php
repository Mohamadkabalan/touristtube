<?php

// php sitemap_job.php &>/home/nginx1/www/web/social/services/sitemap_job.log &

$path = "../";

echo PHP_EOL.strftime('%Y-%m-%d %T').' BEGIN';

define('ENVIRONMENT', getenv('ENVIRONMENT'));
include_once ($path.'inc/config.php');

$dbConn;

function dbConnect1($dbConfig)
{
	try 
	{
		$connection = 'mysql:host='.$dbConfig[ 'host' ].';dbname='.$dbConfig['name'];
		$conn = new PDO($connection, $dbConfig['user'], $dbConfig['pwd']);
		$conn->exec("set names utf8");
	}
	catch(PDOException $e)
	{
		echo "Failed to get DB handle: ".$e->getMessage()."\n";
		exit;
	}
	
	return $conn;
}
function cleanTitleSitemap($titles){
    $titles = html_entity_decode($titles, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $title = str_replace("'", " ", $titles);
    $title = preg_replace('/\r\n|\r|\n/', '', $title);
    $title = trim($title);
    $title = str_replace('"', " ", $title);
    $title = str_replace("’", " ", $title);
    $title = str_replace("`", " ", $title);
    $title = str_replace(',', "-", $title);
    $title = str_replace('(', "-", $title);
    $title = str_replace(')', "-", $title);
    $title = str_replace('?', "-", $title);
    $title = str_replace('#', "", $title);
    $title = str_replace('!', "-", $title);
    $title = str_replace('}', "-", $title);
    $title = str_replace('.', "-", $title);
    $title = str_replace('/', "-", $title);
    $title = str_replace(' & ', "-", $title);
    $title = str_replace('&', '-and-', $title);
    $title = str_replace(">", "-", $title);
    $title = str_replace("<", "-", $title);
    $title = trim($title);
    $title = str_replace(' ', "-", $title);
    $title = str_replace("%+", "-", $title);
    $title = str_replace("%-", "-", $title);
    $title = str_replace("100%", "100", $title);
    $title = str_replace("%", "-", $title);
    $title = str_replace('+', "-", $title);
    $title = preg_replace("/\-+/", '-', $title);
    $title = str_replace('-', "+", $title);
    $title = remove_accents($title);
    $title = preg_replace('/[^a-z0-9A-Z\-+]/', '', $title);
    $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $title;
}
function remove_accents($string)
    {
    if (!preg_match('/[\x80-\xff]/', $string)) return $string;

    if (seems_utf8($string)) {
        $chars = array(
            // Decompositions for Latin-1 Supplement
            chr(195).chr(128) => 'A',
            chr(195).chr(129) => 'A',
            chr(195).chr(130) => 'A',
            chr(195).chr(131) => 'A',
            chr(195).chr(132) => 'A',
            chr(195).chr(133) => 'A',
            chr(195).chr(135) => 'C',
            chr(195).chr(136) => 'E',
            chr(195).chr(137) => 'E',
            chr(195).chr(138) => 'E',
            chr(195).chr(139) => 'E',
            chr(195).chr(140) => 'I',
            chr(195).chr(141) => 'I',
            chr(195).chr(142) => 'I',
            chr(195).chr(143) => 'I',
            chr(195).chr(145) => 'N',
            chr(195).chr(146) => 'O',
            chr(195).chr(147) => 'O',
            chr(195).chr(148) => 'O',
            chr(195).chr(149) => 'O',
            chr(195).chr(150) => 'O',
            chr(195).chr(153) => 'U',
            chr(195).chr(154) => 'U',
            chr(195).chr(155) => 'U',
            chr(195).chr(156) => 'U',
            chr(195).chr(157) => 'Y',
            chr(195).chr(159) => 's',
            chr(195).chr(160) => 'a',
            chr(195).chr(161) => 'a',
            chr(195).chr(162) => 'a',
            chr(195).chr(163) => 'a',
            chr(195).chr(164) => 'a',
            chr(195).chr(165) => 'a',
            chr(195).chr(167) => 'c',
            chr(195).chr(168) => 'e',
            chr(195).chr(169) => 'e',
            chr(195).chr(170) => 'e',
            chr(195).chr(171) => 'e',
            chr(195).chr(172) => 'i',
            chr(195).chr(173) => 'i',
            chr(195).chr(174) => 'i',
            chr(195).chr(175) => 'i',
            chr(195).chr(177) => 'n',
            chr(195).chr(178) => 'o',
            chr(195).chr(179) => 'o',
            chr(195).chr(180) => 'o',
            chr(195).chr(181) => 'o',
            chr(195).chr(182) => 'o',
            chr(195).chr(182) => 'o',
            chr(195).chr(185) => 'u',
            chr(195).chr(186) => 'u',
            chr(195).chr(187) => 'u',
            chr(195).chr(188) => 'u',
            chr(195).chr(189) => 'y',
            chr(195).chr(191) => 'y',
            // Decompositions for Latin Extended-A
            chr(196).chr(128) => 'A',
            chr(196).chr(129) => 'a',
            chr(196).chr(130) => 'A',
            chr(196).chr(131) => 'a',
            chr(196).chr(132) => 'A',
            chr(196).chr(133) => 'a',
            chr(196).chr(134) => 'C',
            chr(196).chr(135) => 'c',
            chr(196).chr(136) => 'C',
            chr(196).chr(137) => 'c',
            chr(196).chr(138) => 'C',
            chr(196).chr(139) => 'c',
            chr(196).chr(140) => 'C',
            chr(196).chr(141) => 'c',
            chr(196).chr(142) => 'D',
            chr(196).chr(143) => 'd',
            chr(196).chr(144) => 'D',
            chr(196).chr(145) => 'd',
            chr(196).chr(146) => 'E',
            chr(196).chr(147) => 'e',
            chr(196).chr(148) => 'E',
            chr(196).chr(149) => 'e',
            chr(196).chr(150) => 'E',
            chr(196).chr(151) => 'e',
            chr(196).chr(152) => 'E',
            chr(196).chr(153) => 'e',
            chr(196).chr(154) => 'E',
            chr(196).chr(155) => 'e',
            chr(196).chr(156) => 'G',
            chr(196).chr(157) => 'g',
            chr(196).chr(158) => 'G',
            chr(196).chr(159) => 'g',
            chr(196).chr(160) => 'G',
            chr(196).chr(161) => 'g',
            chr(196).chr(162) => 'G',
            chr(196).chr(163) => 'g',
            chr(196).chr(164) => 'H',
            chr(196).chr(165) => 'h',
            chr(196).chr(166) => 'H',
            chr(196).chr(167) => 'h',
            chr(196).chr(168) => 'I',
            chr(196).chr(169) => 'i',
            chr(196).chr(170) => 'I',
            chr(196).chr(171) => 'i',
            chr(196).chr(172) => 'I',
            chr(196).chr(173) => 'i',
            chr(196).chr(174) => 'I',
            chr(196).chr(175) => 'i',
            chr(196).chr(176) => 'I',
            chr(196).chr(177) => 'i',
            chr(196).chr(178) => 'IJ',
            chr(196).chr(179) => 'ij',
            chr(196).chr(180) => 'J',
            chr(196).chr(181) => 'j',
            chr(196).chr(182) => 'K',
            chr(196).chr(183) => 'k',
            chr(196).chr(184) => 'k',
            chr(196).chr(185) => 'L',
            chr(196).chr(186) => 'l',
            chr(196).chr(187) => 'L',
            chr(196).chr(188) => 'l',
            chr(196).chr(189) => 'L',
            chr(196).chr(190) => 'l',
            chr(196).chr(191) => 'L',
            chr(197).chr(128) => 'l',
            chr(197).chr(129) => 'L',
            chr(197).chr(130) => 'l',
            chr(197).chr(131) => 'N',
            chr(197).chr(132) => 'n',
            chr(197).chr(133) => 'N',
            chr(197).chr(134) => 'n',
            chr(197).chr(135) => 'N',
            chr(197).chr(136) => 'n',
            chr(197).chr(137) => 'N',
            chr(197).chr(138) => 'n',
            chr(197).chr(139) => 'N',
            chr(197).chr(140) => 'O',
            chr(197).chr(141) => 'o',
            chr(197).chr(142) => 'O',
            chr(197).chr(143) => 'o',
            chr(197).chr(144) => 'O',
            chr(197).chr(145) => 'o',
            chr(197).chr(146) => 'OE',
            chr(197).chr(147) => 'oe',
            chr(197).chr(148) => 'R',
            chr(197).chr(149) => 'r',
            chr(197).chr(150) => 'R',
            chr(197).chr(151) => 'r',
            chr(197).chr(152) => 'R',
            chr(197).chr(153) => 'r',
            chr(197).chr(154) => 'S',
            chr(197).chr(155) => 's',
            chr(197).chr(156) => 'S',
            chr(197).chr(157) => 's',
            chr(197).chr(158) => 'S',
            chr(197).chr(159) => 's',
            chr(197).chr(160) => 'S',
            chr(197).chr(161) => 's',
            chr(197).chr(162) => 'T',
            chr(197).chr(163) => 't',
            chr(197).chr(164) => 'T',
            chr(197).chr(165) => 't',
            chr(197).chr(166) => 'T',
            chr(197).chr(167) => 't',
            chr(197).chr(168) => 'U',
            chr(197).chr(169) => 'u',
            chr(197).chr(170) => 'U',
            chr(197).chr(171) => 'u',
            chr(197).chr(172) => 'U',
            chr(197).chr(173) => 'u',
            chr(197).chr(174) => 'U',
            chr(197).chr(175) => 'u',
            chr(197).chr(176) => 'U',
            chr(197).chr(177) => 'u',
            chr(197).chr(178) => 'U',
            chr(197).chr(179) => 'u',
            chr(197).chr(180) => 'W',
            chr(197).chr(181) => 'w',
            chr(197).chr(182) => 'Y',
            chr(197).chr(183) => 'y',
            chr(197).chr(184) => 'Y',
            chr(197).chr(185) => 'Z',
            chr(197).chr(186) => 'z',
            chr(197).chr(187) => 'Z',
            chr(197).chr(188) => 'z',
            chr(197).chr(189) => 'Z',
            chr(197).chr(190) => 'z',
            chr(197).chr(191) => 's',
            // Euro Sign
            chr(226).chr(130).chr(172) => 'E',
            // GBP (Pound) Sign
            chr(194).chr(163) => ''
        );

        $string = strtr($string, $chars);
    } else {
        // Assume ISO-8859-1 if not UTF-8
        $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158).chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194).chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202).chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210).chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218).chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227).chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235).chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243).chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251).chr(252).chr(253).chr(255);

        $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

        $string              = strtr($string, $chars['in'], $chars['out']);
        $double_chars['in']  = array(
            chr(140),
            chr(156),
            chr(198),
            chr(208),
            chr(222),
            chr(223),
            chr(230),
            chr(240),
            chr(254)
        );
        $double_chars['out'] = array(
            'OE',
            'oe',
            'AE',
            'DH',
            'TH',
            'ss',
            'ae',
            'dh',
            'th'
        );
        $string              = str_replace($double_chars['in'], $double_chars['out'], $string);
    }
    return $string;
}
function seems_utf8($str)
{
    $length = strlen($str);
    for ($i = 0; $i < $length; $i ++) {
        $c = ord($str[$i]);
        if ($c < 0x80) $n = 0; // 0bbbbbbb
        elseif (($c & 0xE0) == 0xC0) $n = 1; // 110bbbbb
        elseif (($c & 0xF0) == 0xE0) $n = 2; // 1110bbbb
        elseif (($c & 0xF8) == 0xF0) $n = 3; // 11110bbb
        elseif (($c & 0xFC) == 0xF8) $n = 4; // 111110bb
        elseif (($c & 0xFE) == 0xFC) $n = 5; // 1111110b
        else return false; // Does not match any model
        for ($j = 0; $j < $n; $j ++) { // n bytes matching 10bbbbbb follow ?
            if (( ++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80)) return false;
        }
    }
    return true;
}
                    
if (!isset($dbConn)) { $dbConn = dbConnect1( $CONFIG ['db'] ); }

$langarray = array('en','fr', 'in');

$params  = array();  
$params2 = array();  
$params3 = array();  
$params4 = array();  
$params5 = array();
echo PHP_EOL.strftime('%Y-%m-%d %T').' languages:: '.implode(', ', $langarray);

foreach($langarray as $lang_val)
{
    $prefixlang = '';
    if($lang_val != 'en') $prefixlang = $lang_val.'_';

    // sitemap_hotels_near_by.xml
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_hotels_near_by.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_hotels_near_by.xml", "w");
    if (!$sitemapindex_handle) {
        $dbConn = null;
        exit;
    }
    //open the sitemap index
    fwrite($sitemapindex_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

    $page = 0;
    $pageloop = true;

    while($pageloop) {
        $pagename = $page + 1;
        $end = 25000;
        $start = $page * $end;
        $query = "SELECT id, name, city_id, country FROM discover_poi where published = 1 LIMIT $start, $end";
        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $ret    = $statement->rowCount();

        if(!$res || !$ret) {
            $pageloop = false;
        } else {
            $rows = $statement->fetchAll();
            $statement->closeCursor();
            $statement = null;
            $valcount = 0;

            $qry = "SELECT CASE WHEN EXISTS (SELECT id FROM discover_hotels WHERE city_id = :city_id and published=1) THEN 1 ELSE 0 END AS hotels_exist";
            $stmt = $dbConn->prepare($qry);

            foreach($rows as $indx => $row_item) {
                $last_mod = date('Y-m-d');
                $changeFreq = 'monthly';
                $id = $row_item[0];
                $city_id = $row_item[2];

                $stmt->bindValue(':city_id', $city_id, PDO::PARAM_INT);
                $res1 = $stmt->execute();
                $ret1 = $stmt->fetchColumn();

                if($res1 && $ret1) {
                    if(!$valcount) {
                        $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_hotels_near_by$pagename.xml";
                        echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                        $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
                        if (!$sitemap_loop_handle) {
                            fclose($sitemapindex_handle);
                            $stmt->closeCursor();
                            $stmt = null;
                            $dbConn = null;
                            exit;
                        }
                        fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);
                    }

                    $valcount++;
                    $title = cleanTitleSitemap($row_item[1]);
                    if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://nearby.touristtube.com/$lang_val/hotels-near-by-%s_%s_1</loc><changefreq>%s</changefreq></url>\n",$title ,$id, $changeFreq);
                    else fprintf($sitemap_loop_handle, "<url><loc>https://nearby.touristtube.com/hotels-near-by-%s_%s_1</loc><changefreq>%s</changefreq></url>\n",$title ,$id, $changeFreq);
                }
                unset($rows[$indx]);
            }
            $stmt->closeCursor();
            $stmt = null;
            if($valcount) {
                if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://www.touristtube.com/$lang_val/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
                else fprintf($sitemapindex_handle, "<sitemap><loc>https://www.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
                fwrite($sitemap_loop_handle, '</urlset>');
                fclose($sitemap_loop_handle);
                system("gzip -9 -f {$path}{$sitemap_loop_name}");
            }
            $page++;
        }
    }
    //close the sitemapindex
    fwrite($sitemapindex_handle, '</sitemapindex>');
    fclose($sitemapindex_handle);
}

$dbConn = null;
echo PHP_EOL.strftime('%Y-%m-%d %T')." END\n\n";