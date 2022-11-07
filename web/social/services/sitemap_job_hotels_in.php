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

    // sitemap_hotels_in_cities.xml
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_hotels_in_cities.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_hotels_in_cities.xml", "w");
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

                $stmt->bindValue(':city_id', $id, PDO::PARAM_INT);
                $res1 = $stmt->execute();
                $ret1 = $stmt->fetchColumn();

                if($res1 && $ret1) {
                    if(!$valcount) {
                        $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_hotels_in_cities$pagename.xml";
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
                    if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val/hotels-in-%s-C_%s</loc><changefreq>%s</changefreq></url>\n", $title, $id, $changeFreq);
                    else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/hotels-in-%s-C_%s</loc><changefreq>%s</changefreq></url>\n", $title, $id, $changeFreq);
                }
                unset($rows[$indx]);
            }

            $stmt->closeCursor();
            $stmt = null;
            if($valcount) {
                if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
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

    // sitemap_hotels_in_countries.xml
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_hotels_in_countries.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_hotels_in_countries.xml", "w");
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
            $rows = $statement->fetchAll();
            $statement->closeCursor();
            $statement = null;
            $valcount = 0;

            $stmt = $dbConn->prepare("SELECT CASE WHEN EXISTS (SELECT id FROM discover_hotels WHERE countryCode = :country_code and published=1) THEN 1 ELSE 0 END AS hotels_exist");

            foreach($rows as $indx => $row_item) {
                $last_mod = date('Y-m-d');
                $changeFreq = 'monthly';
                $id = $row_item[0];

                $stmt->bindValue(':country_code', $id, PDO::PARAM_STR);
                $res1 = $stmt->execute();
                $ret1 = $stmt->fetchColumn();

                if($res1 && $ret1) {
                    if(!$valcount) {
                        $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_hotels_in_countries$pagename.xml";
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
                    if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val/hotels-in-%s-CO_%s</loc><changefreq>%s</changefreq></url>\n", $title, $id, $changeFreq);
                    else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/hotels-in-%s-CO_%s</loc><changefreq>%s</changefreq></url>\n", $title, $id, $changeFreq);
                }
                unset($rows[$indx]);
            }
            $stmt->closeCursor();
            $stmt = null;
            if($valcount) {
                if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
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

    // sitemap_hotels_in_states.xml
    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_hotels_in_states.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_hotels_in_states.xml", "w");
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
            $rows = $statement->fetchAll();
            $statement->closeCursor();
            $statement = null;
            $valcount = 0;

            $qry = "SELECT CASE WHEN EXISTS (SELECT h.id FROM discover_hotels as h inner join webgeocities as w on (w.id = h.city_id) inner join states as s ON (s.country_code = w.country_code and s.state_code = w.state_code) WHERE h.city_id > 0 and w.state_code = :state_code and w.country_code = :country_code and h.published=1) THEN 1 ELSE 0 END AS hotels_exist";
            $stmt = $dbConn->prepare($qry);

            foreach($rows as $indx => $row_item) {
                $last_mod = date('Y-m-d');
                $changeFreq = 'monthly';
                $country_code = $row_item[0];
                $state_code = $row_item[1];

                $stmt->bindValue(':country_code', $country_code, PDO::PARAM_STR);
                $stmt->bindValue(':state_code', $state_code, PDO::PARAM_STR);
                $res1 = $stmt->execute();
                $ret1 = $stmt->fetchColumn();

                if($res1 && $ret1) {
                    if(!$valcount) {
                        $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_hotels_in_states$pagename.xml";
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
                    $title = cleanTitleSitemap($row_item[2]);
                    if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val/hotels-in-%s-S_%s_%s</loc><changefreq>%s</changefreq></url>\n", $title,$state_code,$country_code, $changeFreq);
                    else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/hotels-in-%s-S_%s_%s</loc><changefreq>%s</changefreq></url>\n", $title,$state_code,$country_code, $changeFreq);
                }
                unset($rows[$indx]);
            }
            $stmt->closeCursor();
            $stmt = null;
            if($valcount) {
                if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
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