<?php

// php sitemap_job.php &>/home/nginx1/www/web/social/services/sitemap_job.log &

$path = "../";

echo PHP_EOL.strftime('%Y-%m-%d %T').' BEGIN';

define('ENVIRONMENT', getenv('ENVIRONMENT'));
include_once ($path.'inc/config.php');

include_once ('sitemap_header.php');

echo PHP_EOL.strftime('%Y-%m-%d %T').' languages:: '.implode(', ', $langarray);

foreach($langarray as $lang_val)
{
    $prefixlang = '';
    if($lang_val != 'en') $prefixlang = $lang_val.'_';

    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_restaurants_static.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_restaurants_static.xml", "w");

    if (!$sitemapindex_handle)
    {
        $dbConn = null;
        exit;
    }
    //open the sitemap index
    fwrite($sitemapindex_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

    $restaurants_indexes = array('best-restaurants');

    $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_restaurants_static.xml";
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name.PHP_EOL;
    $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
    if (!$sitemap_loop_handle)
    {
        fclose($sitemapindex_handle);
        $dbConn = null;
        exit;
    }

    if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
    else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
    fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

    foreach ($restaurants_indexes as $indx => $static_page)
    {
        $last_mod = date('Y-m-d');
        $changeFreq = 'weekly'; //could also be 'hourly' or 'always'
        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/$lang_val/%s</loc><changefreq>%s</changefreq></url>\n",$static_page, $changeFreq);
        else fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n",$static_page, $changeFreq);

        unset($restaurants_indexes[$indx]);
    }

    //360 restaurant
    $query = "SELECT r.id, r.name, h.name AS h_name, w.name AS w_name FROM restaurant r INNER JOIN cms_hotel h ON h.id=r.hotel_id LEFT JOIN webgeocities w ON w.id=r.city_id AND r.city_id IS NOT NULL AND r.city_id>0 WHERE r.published = 1";
    $statement = $dbConn->prepare($query);
    $res    = $statement->execute();
    $rows = $statement->fetchAll();
        $statement->closeCursor();
        $statement = null;

    foreach($rows as $indx => $row_item)
    {
        $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
        $id = $row_item[0];
        $title = cleanTitleSitemap($row_item[2].' '.$row_item[1]);
        $city = cleanTitleSitemap($row_item[3]);
        
        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/$lang_val/360-restaurant-%s-%s-%s</loc><changefreq>%s</changefreq></url>\n", $title, $city, $id, $changeFreq);
        else fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/360-restaurant-%s-%s-%s</loc><changefreq>%s</changefreq></url>\n", $title, $city, $id, $changeFreq);

        unset($rows[$indx]);
    }

    fwrite($sitemap_loop_handle, '</urlset>');
    fclose($sitemap_loop_handle);
    system("gzip -9 -f {$path}{$sitemap_loop_name}");

    //close the sitemapindex
    fwrite($sitemapindex_handle, '</sitemapindex>');
    fclose($sitemapindex_handle);
}

$dbConn = null;
echo PHP_EOL.strftime('%Y-%m-%d %T')." END\n\n";