<?php

// php sitemap_job.php &>/home/nginx1/www/web/social/services/sitemap_job.log &

$path = "../";

echo PHP_EOL.strftime('%Y-%m-%d %T').' BEGIN';

define('ENVIRONMENT', getenv('ENVIRONMENT'));
include_once ($path.'inc/config.php');

include_once ('sitemap_header.php');

$langarray = array('en');

echo PHP_EOL.strftime('%Y-%m-%d %T').' languages:: '.implode(', ', $langarray);

foreach($langarray as $lang_val)
{
    $prefixlang = '';
    if($lang_val != 'en') $prefixlang = $lang_val.'_';

    // sitemap_thingstodo.xml
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_thingstodo.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_thingstodo.xml", "w");
    if (!$sitemapindex_handle) {
        $dbConn = null;
        exit;
    }
    //open the sitemap index
    fwrite($sitemapindex_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

    $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_thingstodo.xml";
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
    $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");

    if (!$sitemap_loop_handle) {
        fclose($sitemapindex_handle);
        $dbConn = null;
        exit;
    }
    if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
    else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
    fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

    $query = "SELECT a.alias,a.language FROM `cms_thingstodo_region` as r INNER JOIN alias as a on a.id=r.alias_id where r.published=1 AND a.language='$lang_val'";
    $statement = $dbConn->prepare($query);
    $res    = $statement->execute();
    $rows = $statement->fetchAll();
    $statement->closeCursor();
    $statement = null;
    foreach($rows as $indx => $row_item) {
        $last_mod = date('Y-m-d');
        $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
        $category = $row_item[0];
        $lang_val1 = $row_item[1];
        if($lang_val1 != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val1/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        unset($rows[$indx]);
    }

    $query = "SELECT a.alias,a.language FROM `cms_thingstodo_country` as r INNER JOIN alias as a on a.id=r.alias_id where r.published=1 AND a.language='$lang_val'";
    $statement = $dbConn->prepare($query);
    $res    = $statement->execute();
    $rows = $statement->fetchAll();
    $statement->closeCursor();
    $statement = null;
    foreach($rows as $indx => $row_item){
        $last_mod = date('Y-m-d');
        $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
        $category = $row_item[0];
        $lang_val1 = $row_item[1];
        if($lang_val1 != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val1/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        unset($rows[$indx]);
    }

    $query = "SELECT a.alias,a.language FROM `cms_thingstodo` AS r INNER JOIN alias AS a on a.id=r.alias_id where r.published=1 AND a.language='$lang_val'";
    $statement = $dbConn->prepare($query);
    $res    = $statement->execute();
    $rows = $statement->fetchAll();
    $statement->closeCursor();
    $statement = null;
    foreach($rows as $indx => $row_item){
        $last_mod = date('Y-m-d');
        $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
        $category = $row_item[0];
        $lang_val1 = $row_item[1];
        if($lang_val1 != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val1/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        unset($rows[$indx]);
    }

    $query = "SELECT a.alias, a.language, td.title, td.slug, tdd.division_category_id FROM `cms_thingstodo` AS r INNER JOIN alias AS a on a.id=r.alias_id INNER JOIN cms_thingstodo_details AS td on td.parent_id=r.id AND td.published=1 INNER JOIN thingstodo_division tdd ON tdd.ttd_id = td.id AND tdd.parent_id IS NULL where r.published=1 AND a.language='$lang_val'";
    $statement = $dbConn->prepare($query);
    $res    = $statement->execute();
    $rows = $statement->fetchAll();
    $statement->closeCursor();
    $statement = null;
    foreach($rows as $indx => $row_item){
        $last_mod = date('Y-m-d');
        $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
        $category = $row_item[0];
        $lang_val1 = $row_item[1];
        $slug = $row_item[3];
        if($lang_val1 != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val1/%s/%s</loc><changefreq>%s</changefreq></url>\n", $category, $slug, $changeFreq);
        else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/%s/%s</loc><changefreq>%s</changefreq></url>\n", $category, $slug, $changeFreq);
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