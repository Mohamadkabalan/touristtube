<?php

// php sitemap_job.php &>/home/nginx1/www/web/social/services/sitemap_job.log &

$path = "../";

echo PHP_EOL.strftime('%Y-%m-%d %T').' BEGIN';

define('ENVIRONMENT', getenv('ENVIRONMENT'));
include_once ($path.'inc/config.php');

include_once ('sitemap_header.php');

$skipRestaurants = true;
$skipspecial = false;
$skiplive = true;

echo PHP_EOL.strftime('%Y-%m-%d %T').' languages:: '.implode(', ', $langarray);

foreach($langarray as $lang_val)
{
    $prefixlang = '';	
    if($lang_val != 'en') $prefixlang = $lang_val.'_';
    
    if( !$skipspecial ){
        echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap.xml".PHP_EOL;
        $sitemapindex_handle = fopen($path .$prefixlang. "sitemap.xml", "w");

        if (!$sitemapindex_handle)
            {
                    $dbConn = null;
            exit;
        }

        //open the sitemap index
        fwrite($sitemapindex_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

        $static_pages = array('about-us', 'contact','disclaimer', 'terms-and-conditions' , 'privacy-policy','faq','review','help','support');
        $changing_indexes = array('discover','hotel-booking','things-to-do');
        $restaurants_indexes = array('best-restaurants');
        $media_indexes = array('photo-video-sharing','best-images','best-videos');
        $channels_indexes = array('channels');
        $where_is_indexes = array('where-is');
        $nearby_indexes = array('nearby');

        $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_static.xml";
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
	
	if($lang_val != 'en') fprintf($sitemap_loop_handle,"<url><loc> https://www.touristtube.com/$lang_val/</loc><changefreq>daily</changefreq><priority>1.0</priority></url>\n");
        else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/</loc><changefreq>daily</changefreq><priority>1.0</priority></url>\n");
	
        //static pages
        foreach ($static_pages as $indx => $static_page)
            {
            //$last_mod = date('Y-m-d', filemtime($path . $static_page . '.php'));
            $last_mod = date('Y-m-d');
            if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);
            else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);

                    unset($static_pages[$indx]);
        }

        //pages with a lot of dynamic content
        foreach ($changing_indexes as $indx => $static_page)
            {
            $last_mod = date('Y-m-d');
            $changeFreq = 'weekly'; //could also be 'hourly' or 'always'
            if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/$lang_val/%s</loc><changefreq>%s</changefreq></url>\n",$static_page, $changeFreq);
            else fprintf($sitemap_loop_handle, "<url><loc>https://www.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n",$static_page, $changeFreq);

                    unset($changing_indexes[$indx]);
        }
        foreach ($restaurants_indexes as $indx => $static_page)
            {
            $last_mod = date('Y-m-d');
            $changeFreq = 'weekly'; //could also be 'hourly' or 'always'
            if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/$lang_val/%s</loc><changefreq>%s</changefreq></url>\n",$static_page, $changeFreq);
            else fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n",$static_page, $changeFreq);

                    unset($changing_indexes[$indx]);
        }
        foreach ($media_indexes as $indx => $static_page)
            {
            $last_mod = date('Y-m-d');
            $changeFreq = 'weekly'; //could also be 'hourly' or 'always'
            if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://media.touristtube.com/$lang_val/%s</loc><changefreq>%s</changefreq></url>\n",$static_page, $changeFreq);
            else fprintf($sitemap_loop_handle, "<url><loc>https://media.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n",$static_page, $changeFreq);

                    unset($changing_indexes[$indx]);
        }
        foreach ($channels_indexes as $indx => $static_page)
            {
            $last_mod = date('Y-m-d');
            if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://channels.touristtube.com/$lang_val/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);
            else fprintf($sitemap_loop_handle, "<url><loc>https://channels.touristtube.com/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);

                    unset($static_pages[$indx]);
        }
        foreach ($where_is_indexes as $indx => $static_page)
            {
            $last_mod = date('Y-m-d');
            if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://where-is.touristtube.com/$lang_val/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);
            else fprintf($sitemap_loop_handle, "<url><loc>https://where-is.touristtube.com/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);

                    unset($static_pages[$indx]);
        }
        foreach ($nearby_indexes as $indx => $static_page)
            {
            $last_mod = date('Y-m-d');
            if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://nearby.touristtube.com/$lang_val/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);
            else fprintf($sitemap_loop_handle, "<url><loc>https://nearby.touristtube.com/%s</loc><changefreq>weekly</changefreq></url>\n", $static_page);

                    unset($static_pages[$indx]);
        }

        //the category
        $query = "SELECT title FROM cms_allcategories WHERE published = 1";
    //    $res = db_query($query);
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
        // $query = "SELECT name FROM webgeocities WHERE id IN (SELECT cityid FROM ( SELECT cityid , COUNT(id) AS ct FROM cms_videos as v1 WHERE v1.is_public = 2 and v1.published=1 GROUP BY cityid ORDER BY ct DESC LIMIT 20 ) AS A)";
            $query = "SELECT c.name 
                            FROM webgeocities c 
                            INNER JOIN (SELECT cityid, COUNT(id) AS cnt 
                                                    FROM cms_videos v 
                                                    WHERE v.is_public = 2 and v.published = 1 
                                                    GROUP BY cityid 
                                                    ORDER BY cnt DESC 
                                                    LIMIT 20) q ON (q.cityid = c.id) 
                            ORDER BY q.cnt DESC";

    //    $res = db_query($query);
        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $rows = $statement->fetchAll();
            $statement->closeCursor();
            $statement = null;

    //    while ($row = db_fetch_row($res)) {
        foreach($rows as $indx => $row_item)
            {
            $last_mod = date('Y-m-d');
            $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
            $category = html_entity_decode($row_item[0], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://media.touristtube.com/$lang_val/%s--Sa_1_0</loc><changefreq>%s</changefreq></url>\n",$category, $changeFreq);
            else fprintf($sitemap_loop_handle, "<url><loc>https://media.touristtube.com/%s--Sa_1_0</loc><changefreq>%s</changefreq></url>\n",$category, $changeFreq);

                    unset($rows[$indx]);
        }
        $query = "SELECT title FROM cms_channel_category WHERE published = 1";
    //    $res = db_query($query);
        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $rows = $statement->fetchAll();
            $statement->closeCursor();
            $statement = null;

    //    while ($row = db_fetch_row($res)) {
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

        /*$query = "SELECT DISTINCT co.code FROM `cms_countries` as co INNER JOIN cms_channel AS ch ON ch.country = co.code AND ch.published = 1 ORDER BY name ASC";
        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $rows = $statement->fetchAll();
            $statement->closeCursor();
            $statement = null;

        foreach($rows as $indx => $row_item)
            {
            $last_mod = date('Y-m-d');
            $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
            $category = $row_item[0];
            if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://channels.touristtube.com/$lang_val/channel-search/co/%s</loc><changefreq>%s</changefreq></url>\n",$category, $changeFreq);
            else fprintf($sitemap_loop_handle, "<url><loc>https://channels.touristtube.com/channel-search/co/%s</loc><changefreq>%s</changefreq></url>\n",$category, $changeFreq);
            unset($rows[$indx]);
        }*/

        fwrite($sitemap_loop_handle, '</urlset>');
        fclose($sitemap_loop_handle);
        system("gzip -9 -f {$path}{$sitemap_loop_name}");

        //close the sitemapindex
        fwrite($sitemapindex_handle, '</sitemapindex>');
        fclose($sitemapindex_handle);
    }
    if( !$skipspecial ){
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
    }
    if( !$skipspecial ){
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
    }
    if( !$skipRestaurants ){
        // sitemap_restaurants_in_cities.xml
        echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_restaurants_in_cities.xml".PHP_EOL;
        $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_restaurants_in_cities.xml", "w");	
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

                $qry = "SELECT CASE WHEN EXISTS (SELECT id FROM global_restaurants WHERE country = :country AND published = 1 AND (locality LIKE :locality OR region LIKE :region)) THEN 1 ELSE 0 END AS restaurants_exist";
                $stmt = $dbConn->prepare($qry);

                foreach($rows as $indx => $row_item) {
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $id = $row_item[0];
                    $country = $row_item[2];
                    $stmt->bindValue(':country', $country, PDO::PARAM_STR);
                    $stmt->bindValue(':locality', $row_item[1], PDO::PARAM_STR);
                    $stmt->bindValue(':region', $row_item[1], PDO::PARAM_STR);
                    $res1 = $stmt->execute();
                    $ret1 = $stmt->fetchColumn();

                    if($res1 && $ret1) {
                        if(!$valcount) {
                            $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_restaurants_in_cities$pagename.xml";
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
                        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/$lang_val/restaurants-in-%s-C_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
                        else fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/restaurants-in-%s-C_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
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
    if( !$skipspecial ){
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
    }
    if( !$skipspecial ){
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
    }
    if( !$skipRestaurants ){
        // sitemap_restaurants_in_countries.xml
        echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_restaurants_in_countries.xml".PHP_EOL;
        $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_restaurants_in_countries.xml", "w");	
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

                $stmt = $dbConn->prepare("SELECT CASE WHEN EXISTS (SELECT id FROM global_restaurants WHERE country = :country_code AND published = 1) THEN 1 ELSE 0 END AS restaurants_exist");

                foreach($rows as $indx => $row_item) {
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $id = $row_item[0];

                    $stmt->bindValue(':country_code', $id, PDO::PARAM_STR);
                    $res1 = $stmt->execute();
                    $ret1 = $stmt->fetchColumn();

                    if($res1 && $ret1) {
                        if(!$valcount) {
                            $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_restaurants_in_countries$pagename.xml";
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
                        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/$lang_val/restaurants-in-%s-CO_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
                        else fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/restaurants-in-%s-CO_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
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
    if( !$skipspecial ){
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
    if( !$skipspecial ){
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
    if( !$skipspecial ){
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
    if( !$skipRestaurants ){
        // sitemap_restaurants_near_by.xml
        echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_restaurants_near_by.xml".PHP_EOL;
        $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_restaurants_near_by.xml", "w");
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

                $qry = "SELECT CASE WHEN EXISTS (SELECT id FROM global_restaurants WHERE country = :country and published=1) THEN 1 ELSE 0 END AS restaurants_exist";
                $stmt = $dbConn->prepare($qry);

                foreach($rows as $indx => $row_item) {
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $id = $row_item[0];
                    $country = $row_item[3];

                    $stmt->bindValue(':country', $country, PDO::PARAM_STR);
                    $res1 = $stmt->execute();
                    $ret1 = $stmt->fetchColumn();

                    if($res1 && $ret1) {
                        if(!$valcount) {
                            $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_restaurants_near_by$pagename.xml";
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
                        if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/$lang_val/restaurants-near-by-%s_%s_1_1</loc><changefreq>%s</changefreq></url>\n",$title, $id, $changeFreq);
                        else fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/restaurants-near-by-%s_%s_1_1</loc><changefreq>%s</changefreq></url>\n",$title, $id, $changeFreq);
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
    
    if( $lang_val=='en' && !$skipRestaurants ) {
    
        // sitemap_restaurants_reviews.xml
        echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_restaurants_reviews.xml".PHP_EOL;
        $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_restaurants_reviews.xml", "w");	
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
            $query = "SELECT id, name FROM global_restaurants where published = 1 ORDER BY id ASC LIMIT $start, $end";
            $statement = $dbConn->prepare($query);
            $res    = $statement->execute();
            $ret    = $statement->rowCount();

            if(!$res || !$ret) {
                $pageloop = false;
            } else {
                $sitemap_loop_name = "sitemap/files/".$prefixlang."sitemap_restaurants_reviews$pagename.xml";
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
                    if($lang_val != 'en') fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/$lang_val/%s-review-R%s</loc><changefreq>%s</changefreq></url>\n",$title ,$id, $changeFreq);
                    else fprintf($sitemap_loop_handle, "<url><loc>https://restaurants.touristtube.com/%s-review-R%s</loc><changefreq>%s</changefreq></url>\n",$title ,$id, $changeFreq);
					
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
    
    if( !$skiplive ){
        // sitemap_lives.xml
        echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_lives.xml".PHP_EOL;
        $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_lives.xml", "w");	
        if (!$sitemapindex_handle) {
            $dbConn = null;
            exit;
        }
        fwrite($sitemapindex_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

        $query = "SELECT url FROM cms_webcams WHERE state=1";
        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $rows = $statement->fetchAll();	
        $statement->closeCursor();
        $statement = null;

        foreach($rows as $indx => $row_item) {
            $last_mod = date('Y-m-d');
            $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
            $category = $row_item[0];    
            if($lang_val != 'en') fprintf($sitemapindex_handle, "<url><loc>https://www.touristtube.com/$lang_val/live-cam/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
            else fprintf($sitemapindex_handle, "<url><loc>https://www.touristtube.com/live-cam/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
            unset($rows[$indx]);
        }	
        fwrite($sitemapindex_handle, '</urlset>');
        fclose($sitemapindex_handle);
    }
    if($lang_val == 'en'){
        
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
		$statement->closeCursor();
		$statement = null;
		$dbConn = null;
		exit;
	}
	if($lang_val != 'en') fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
        else fprintf($sitemapindex_handle, "<sitemap><loc>https://static2.touristtube.com/%s.gz</loc></sitemap>\n", $sitemap_loop_name);
	fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);
	
	$query = "SELECT a.alias,a.language FROM `cms_thingstodo_region` as r inner join alias as a on a.id=r.alias_id where r.published=1 and a.language='en'";
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
	$query = "SELECT a.alias,a.language FROM `cms_thingstodo_country` as r inner join alias as a on a.id=r.alias_id where r.published=1 and a.language='en'";
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
	
	$query = "SELECT a.alias,a.language FROM `cms_thingstodo` as r inner join alias as a on a.id=r.alias_id where r.published=1 and a.language='en'";
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
	fwrite($sitemap_loop_handle, '</urlset>');
	fclose($sitemap_loop_handle);
	system("gzip -9 -f {$path}{$sitemap_loop_name}");	
        //close the sitemapindex
        fwrite($sitemapindex_handle, '</sitemapindex>');
        fclose($sitemapindex_handle);
    
    }
    
}

$dbConn = null;
echo PHP_EOL.strftime('%Y-%m-%d %T')." END\n\n";