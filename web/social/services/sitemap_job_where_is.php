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

    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_where_is_static.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_where_is_static.xml", "w");

    if (!$sitemapindex_handle)
    {
        $dbConn = null;
        exit;
    }
    //open the sitemap index
    fwrite($sitemapindex_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

    $where_is_indexes = array('where-is');

    $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_where_is_static.xml";
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

    foreach ($where_is_indexes as $indx => $static_page)
    {
        $last_mod = date('Y-m-d');
        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://where-is.touristtube.com/$lang_val/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);
        else fprintf($sitemap_loop_handle, "<url><loc>https://where-is.touristtube.com/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);

        unset($where_is_indexes[$indx]);
    }

    fwrite($sitemap_loop_handle, '</urlset>');
    fclose($sitemap_loop_handle);
    system("gzip -9 -f {$path}{$sitemap_loop_name}");

    //close the sitemapindex
    fwrite($sitemapindex_handle, '</sitemapindex>');
    fclose($sitemapindex_handle);

    

    // sitemap_where_is_cities.xml
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_where_is_cities.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_where_is_cities.xml", "w");
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
        $query = "SELECT id, name, country_code FROM webgeocities LIMIT $start, $end";

        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $ret    = $statement->rowCount();

        if(!$res || !$ret) {
            $pageloop = false;
        } else {
            $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_where_is_cities$pagename.xml";
            echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");

            if (!$sitemap_loop_handle) {
                fclose($sitemapindex_handle);
                $statement->closeCursor();
                $statement = null;
                $dbConn = null;
                exit;
            }

            if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
            else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
            fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

            $rows = $statement->fetchAll();
            $statement->closeCursor();
            $statement = null;
            foreach($rows as $indx => $row_item) {
                $last_mod = date('Y-m-d');
                $changeFreq = 'monthly';
                $id = $row_item[0];
                $title = cleanTitleSitemap($row_item[1]);
                if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://where-is.touristtube.com/$lang_val/where-is-%s-C_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
                else fprintf($sitemap_loop_handle, "<url><loc>https://where-is.touristtube.com/where-is-%s-C_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
                unset($rows[$indx]);
            }
            fwrite($sitemap_loop_handle, '</urlset>');
            fclose($sitemap_loop_handle);
            system("gzip -9 -f {$path}{$sitemap_loop_name}");
            $page++;
        }
    }
    //close the sitemapindex
    fwrite($sitemapindex_handle, '</sitemapindex>');
    fclose($sitemapindex_handle);

    // sitemap_where_is_countries.xml
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_where_is_countries.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_where_is_countries.xml", "w");
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
        $query = "SELECT code, name FROM cms_countries LIMIT $start, $end";

        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $ret    = $statement->rowCount();

        if(!$res || !$ret) {
            $pageloop = false;
        } else {
            $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_where_is_countries$pagename.xml";
            echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");

            if (!$sitemap_loop_handle) {
                fclose($sitemapindex_handle);
                $statement->closeCursor();
                $statement = null;
                $dbConn = null;
                exit;
            }

            if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
            else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
            fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

            $rows = $statement->fetchAll();
            $statement->closeCursor();
            $statement = null;

            foreach($rows as $indx => $row_item) {
                $last_mod = date('Y-m-d');
                $changeFreq = 'monthly';
                $id = $row_item[0];
                $title = cleanTitleSitemap($row_item[1]);
                if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://where-is.touristtube.com/$lang_val/where-is-%s-CO_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
                else fprintf($sitemap_loop_handle, "<url><loc>https://where-is.touristtube.com/where-is-%s-CO_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
                unset($rows[$indx]);
            }
            fwrite($sitemap_loop_handle, '</urlset>');
            fclose($sitemap_loop_handle);
            system("gzip -9 -f {$path}{$sitemap_loop_name}");
            $page++;
        }
    }
    //close the sitemapindex
    fwrite($sitemapindex_handle, '</sitemapindex>');
    fclose($sitemapindex_handle);

    // sitemap_where_is_states.xml
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_where_is_states.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_where_is_states.xml", "w");
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
        $query = "SELECT `country_code`,`state_code`,`state_name` FROM states LIMIT $start, $end";

        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $ret    = $statement->rowCount();

        if(!$res || !$ret) {
            $pageloop = false;
        } else {
            $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_where_is_states$pagename.xml";
            echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");

            if (!$sitemap_loop_handle) {
                fclose($sitemapindex_handle);
                $statement->closeCursor();
                $statement = null;
                $dbConn = null;
                exit;
            }
            if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
            else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
            fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

            $rows = $statement->fetchAll();
            $statement->closeCursor();
            $statement = null;

            foreach($rows as $indx => $row_item) {
                $last_mod = date('Y-m-d');
                $changeFreq = 'monthly';
                $country_code = $row_item[0];
                $state_code = $row_item[1];
                $title = cleanTitleSitemap($row_item[2]);
                if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://where-is.touristtube.com/$lang_val/where-is-%s-S_%s_%s</loc><changefreq>%s</changefreq></url>\n", $title,$state_code,$country_code, $changeFreq);
                else fprintf($sitemap_loop_handle, "<url><loc>https://where-is.touristtube.com/where-is-%s-S_%s_%s</loc><changefreq>%s</changefreq></url>\n", $title,$state_code,$country_code, $changeFreq);
                unset($rows[$indx]);
            }
            fwrite($sitemap_loop_handle, '</urlset>');
            fclose($sitemap_loop_handle);
            system("gzip -9 -f {$path}{$sitemap_loop_name}");
            $page++;
        }
    }
    //close the sitemapindex
    fwrite($sitemapindex_handle, '</sitemapindex>');
    fclose($sitemapindex_handle);
}

$dbConn = null;
echo PHP_EOL.strftime('%Y-%m-%d %T')." END\n\n";