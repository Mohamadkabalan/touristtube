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

    // sitemap_airports_reviews.xml
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_airports_reviews.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_airports_reviews.xml", "w");
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
        $query = "SELECT id,name FROM airport where published=1 LIMIT $start, $end";
        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $ret    = $statement->rowCount();

        if(!$res || !$ret) {
            $pageloop = false;
        } else {
            $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_airports_reviews$pagename.xml";
            echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");

            if (!$sitemap_loop_handle) {
                fclose($sitemapindex_handle);
                $statement->closeCursor();
                $statement = null;
                $dbConn = null;
                exit;
            }
            if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
            else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
            fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);
            $rows = $statement->fetchAll();
            $statement->closeCursor();
            $statement = null;

            foreach($rows as $indx => $row_item) {
                $last_mod = date('Y-m-d');
                $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
                $id = $row_item[0];
                $title = cleanTitleSitemap($row_item[1]);
                if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val/%s-review-A%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
                else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/%s-review-A%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
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

    // sitemap_pois_reviews.xml
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_pois_reviews.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_pois_reviews.xml", "w");
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
        $query = "SELECT id, name FROM discover_poi where published = 1 LIMIT $start, $end";
        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $ret    = $statement->rowCount();

        if(!$res || !$ret) {
            $pageloop = false;
        } else {
            $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_pois_reviews$pagename.xml";
            echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");

            if (!$sitemap_loop_handle) {
                fclose($sitemapindex_handle);
                $statement->closeCursor();
                $statement = null;
                $dbConn = null;
                exit;
            }
            if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
            else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
            fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);
            $rows = $statement->fetchAll();
            $statement->closeCursor();
            $statement = null;

            foreach($rows as $indx => $row_item) {
                $last_mod = date('Y-m-d');
                $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
                $id = $row_item[0];
                $title = cleanTitleSitemap($row_item[1]);
                if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val/%s-review-T%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
                else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/%s-review-T%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
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

    // sitemap_hotels_reviews.xml
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_hotels_reviews.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_hotels_reviews.xml", "w");
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
        $query = "SELECT id,hotelName FROM discover_hotels where published=1 LIMIT $start, $end";

        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $ret    = $statement->rowCount();

        if(!$res || !$ret) {
            $pageloop = false;
        } else {
            $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_hotels_reviews$pagename.xml";
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
                $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
                $id = $row_item[0];
                $title = cleanTitleSitemap($row_item[1]);
                if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val/%s-review-H%s</loc><changefreq>%s</changefreq></url>\n", $title, $id, $changeFreq);
                else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/%s-review-H%s</loc><changefreq>%s</changefreq></url>\n", $title, $id, $changeFreq);
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