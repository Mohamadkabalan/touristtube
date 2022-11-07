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

    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_channels_static.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_channels_static.xml", "w");

    if (!$sitemapindex_handle)
    {
        $dbConn = null;
        exit;
    }
    //open the sitemap index
    fwrite($sitemapindex_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

    $channels_indexes = array('channels');

    $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_channels_static.xml";
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

    foreach ($channels_indexes as $indx => $static_page)
    {
        $last_mod = date('Y-m-d');
        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://channels.touristtube.com/$lang_val/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);
        else fprintf($sitemap_loop_handle, "<url><loc>https://channels.touristtube.com/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);

        unset($channels_indexes[$indx]);
    }

    $query = "SELECT title FROM cms_channel_category WHERE published = 1";
    $statement = $dbConn->prepare($query);
    $res    = $statement->execute();
    $rows = $statement->fetchAll();
    $statement->closeCursor();
    $statement = null;
    foreach($rows as $indx => $row_item)
    {
        $last_mod = date('Y-m-d');
        $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
        $category = str_replace(' ', '-', $row_item[0]);
        $category = str_replace(">", "+", $category);
        $category = str_replace("<", "+", $category);
        $category = html_entity_decode($category, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://channels.touristtube.com/$lang_val/channels-category-%s</loc><changefreq>%s</changefreq></url>\n",$category, $changeFreq);
        else fprintf($sitemap_loop_handle, "<url><loc>https://channels.touristtube.com/channels-category-%s</loc><changefreq>%s</changefreq></url>\n",$category, $changeFreq);

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