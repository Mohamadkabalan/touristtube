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

    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_near_by_static.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_near_by_static.xml", "w");

    if (!$sitemapindex_handle)
    {
        $dbConn = null;
        exit;
    }
    //open the sitemap index
    fwrite($sitemapindex_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

    $nearby_indexes = array('nearby');

    $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_near_by_static.xml";
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

    foreach ($nearby_indexes as $indx => $static_page)
        {
        $last_mod = date('Y-m-d');
        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://nearby.touristtube.com/$lang_val/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);
        else fprintf($sitemap_loop_handle, "<url><loc>https://nearby.touristtube.com/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);

                unset($nearby_indexes[$indx]);
    }

    fwrite($sitemap_loop_handle, '</urlset>');
    fclose($sitemap_loop_handle);
    system("gzip -9 -f {$path}{$sitemap_loop_name}");

    //close the sitemapindex
    fwrite($sitemapindex_handle, '</sitemapindex>');
    fclose($sitemapindex_handle);


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
                        $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_hotels_near_by$pagename.xml";
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
                if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
                else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
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