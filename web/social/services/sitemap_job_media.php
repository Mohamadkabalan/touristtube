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

    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_media_static.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_media_static.xml", "w");

    if (!$sitemapindex_handle)
    {
        $dbConn = null;
        exit;
    }
    //open the sitemap index
    fwrite($sitemapindex_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

    $media_indexes = array('photo-video-sharing','best-images','best-videos');

    $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_media_static.xml";
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

    foreach ($media_indexes as $indx => $static_page)
    {
        $last_mod = date('Y-m-d');
        $changeFreq = 'weekly'; //could also be 'hourly' or 'always'
        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://media.touristtube.com/$lang_val/%s</loc><changefreq>%s</changefreq></url>\n",$static_page, $changeFreq);
        else fprintf($sitemap_loop_handle, "<url><loc>https://media.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n",$static_page, $changeFreq);

        unset($media_indexes[$indx]);
    }
    //the category
    $query = "SELECT title FROM cms_allcategories WHERE published = 1";
    $statement = $dbConn->prepare($query);
    $res    = $statement->execute();
    $rows = $statement->fetchAll();
        $statement->closeCursor();
        $statement = null;

    foreach($rows as $indx => $row_item)
    {
        $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
        $category = $row_item[0];
        $category = str_replace(" ", "+", $category);
        $category = str_replace(">", "+", $category);
        $category = str_replace("<", "+", $category);
        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://media.touristtube.com/$lang_val/%s</loc><changefreq>%s</changefreq></url>\n",$category, $changeFreq);
        else fprintf($sitemap_loop_handle, "<url><loc>https://media.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n",$category, $changeFreq);

        unset($rows[$indx]);
    }
    //the trends
    $query = "SELECT c.name  FROM webgeocities c
        INNER JOIN (SELECT cityid, COUNT(id) AS cnt
        FROM cms_videos v
        WHERE v.is_public = 2 and v.published = 1
        GROUP BY cityid
        ORDER BY cnt DESC
        LIMIT 20) q ON (q.cityid = c.id)
        ORDER BY q.cnt DESC";

    $statement = $dbConn->prepare($query);
    $res    = $statement->execute();
    $rows = $statement->fetchAll();
        $statement->closeCursor();
        $statement = null;

    foreach($rows as $indx => $row_item)
    {
        $last_mod = date('Y-m-d');
        $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
        $category = html_entity_decode($row_item[0], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://media.touristtube.com/$lang_val/%s--Sa_1_0</loc><changefreq>%s</changefreq></url>\n",$category, $changeFreq);
        else fprintf($sitemap_loop_handle, "<url><loc>https://media.touristtube.com/%s--Sa_1_0</loc><changefreq>%s</changefreq></url>\n",$category, $changeFreq);

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