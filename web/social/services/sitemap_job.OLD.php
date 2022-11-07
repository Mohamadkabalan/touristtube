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
    $title = str_replace('"', " ", $title);
    $title = str_replace('%0D', "", $title);
    $title = str_replace('%0A', "", $title);
    $title = str_replace('%0d', "", $title);
    $title = str_replace('%0a', "", $title);
    $title = str_replace(',', "+", $title);
    $title = str_replace('(', "+", $title);
    $title = str_replace(')', "+", $title);
    $title = str_replace('?', "+", $title);
    $title = str_replace('#', "", $title);
    $title = str_replace('!', "+", $title);
    $title = str_replace('}', "+", $title);
    $title = str_replace('.', "+", $title);
    $title = str_replace('/', "+", $title);
    $title = str_replace(' & ', '+', $title);
    $title = str_replace('&', '+and+', $title);
    $title = str_replace(">", "+", $title);
    $title = str_replace("<", "+", $title);
    $title = str_replace(' ', '+', $title);
    $title = str_replace('-', '+', $title);
    $title = str_replace("%+", "+", $title);
    $title = str_replace("%-", "-", $title);
    $title = str_replace("100%", "100", $title);
    $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $title;
}
                    
if (!isset($dbConn)) { $dbConn = dbConnect1( $CONFIG ['db'] ); }

$langarray = array('www','fr', 'in');

$params  = array();  
$params2 = array();  
$params3 = array();  
$params4 = array();  
$params5 = array();

echo PHP_EOL.strftime('%Y-%m-%d %T').' languages:: '.implode(', ', $langarray);

foreach($langarray as $lang_val)
{
    $prefixlang = '';
	
    if($lang_val != 'www')
		$prefixlang = $lang_val.'_';
	
	echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap.xml", "w");
	
    if (!$sitemapindex_handle)
	{
		$dbConn = null;
        exit;
    }
	
    //open the sitemap index
    fwrite($sitemapindex_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);
    //get minimum date from media table
    /*$query = "SELECT MIN(pdate) FROM cms_videos WHERE is_public=" . USER_PRIVACY_PUBLIC;
    //$res = db_query($query);
    $row = db_fetch_row($res);

    $min_media_date = $row[0];
    $min_media_date_ts = strtotime($min_media_date);
    $min_media_date_year = intval(date('Y', $min_media_date_ts));
    $min_media_date_week = intval(date('W', $min_media_date_ts));
    $current_year = intval(date('Y'));
    $current_week = intval(date('W'));

    $loop_year = $min_media_date_year;
    $loop_week = $min_media_date_week;
    while (!( ($loop_year == $current_year) && ($loop_week == $current_week) )) {
        //the daily sitemap for media
        $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_media_{$loop_year}_{$loop_week}.xml";
        if (!OVERWRITE_EXISTING_MAP && file_exists($path.$sitemap_loop_name)) {
        } else {
            $query = "SELECT image_video,hash_id,pdate,title FROM cms_videos WHERE WEEK(pdate)=$loop_week AND YEAR(pdate)=$loop_year AND is_public=" . USER_PRIVACY_PUBLIC;
            //$res = db_query($query);
            if ($res && (db_num_rows($res) != 0)) {
				echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
                if (!$sitemap_loop_handle) {
                    fclose($sitemapindex_handle);
					$dbConn = null;
                    exit;
                }
                fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc><lastmod>%s</lastmod></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);
                while ($row = db_fetch_row($res)) {
                    //$ldate = date('Y-m-d', strtotime($row[2]));
                    $ldate = date('Y-m-d');
                    $titles = cleanTitle($row[3]);
                    if ($row[0] == 'i')
                        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/photo/%s/%s</loc><lastmod>%s</lastmod></url>\n", $row[1],$titles, $ldate);
                    else if ($row[0] == 'v')
                        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/video/%s/%s</loc><lastmod>%s</lastmod></url>\n", $row[1],$titles, $ldate);
                }
                fwrite($sitemap_loop_handle, '</urlset>');
                fclose($sitemap_loop_handle);
                system("gzip -9 -f {$path}{$sitemap_loop_name}");
            }else {
            }
        }

        //next week
        $loop_week++;
        if ($loop_week == 53) {
            $loop_week = 1;
            $loop_year++;
        }
    }*/

    /////////////////////////////////////////////
    //locations sitemap
    ////////////////////////////////////////////////
    //static sitemap
    /*$static_pages = array('about-us', 'advisor', 'barcode-reader', 'contact',
        'dating', 'disclaimer', 'feedback', 'register', 'terms-and-conditions',
        'tourist-advisor', 'tourist-dating', 'tourist-explore', 'tourist-pro',
        'tourist-tubers');*/
    
    $static_pages = array('about-us', 'contact','disclaimer', 'terms-and-conditions' , 'privacy-policy','faq','review','channels','echoes','help','support','where-is','nearby');
    $changing_indexes = array('discover', 'live','hotel-booking','best-restaurants','things-to-do','photo-video-sharing','best-images','best-videos');

    $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_static.xml";
	echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name.PHP_EOL;
    $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
    if (!$sitemap_loop_handle)
	{
        fclose($sitemapindex_handle);
		
		$dbConn = null;
        exit;
    }

//    fprintf($sitemapindex_handle, "<sitemap><loc>https://%s.touristtube.com/%s.gz</loc></sitemap>\n", $lang_val,$sitemap_loop_name);
    fprintf($sitemapindex_handle, "<sitemap><loc>https://%s.touristtube.com/%s.gz</loc></sitemap>\n", $lang_val,$sitemap_loop_name);
    fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);
	
	fprintf($sitemap_loop_handle, "<url><loc>https://%s.touristtube.com/</loc><changefreq>daily</changefreq><priority>1.0</priority></url>\n", $lang_val);
	
    //static pages
    foreach ($static_pages as $indx => $static_page)
	{
        //$last_mod = date('Y-m-d', filemtime($path . $static_page . '.php'));
        $last_mod = date('Y-m-d');
        //fprintf($sitemap_loop_handle, "<url><loc>https://%s.touristtube.com/%s</loc></url>\n", $lang_val, $static_page);
        fprintf($sitemap_loop_handle, "<url><loc>https://%s.touristtube.com/%s</loc><changefreq>weekly</changefreq></url>\n", $lang_val, $static_page);
		
		unset($static_pages[$indx]);
    }

    //pages with a lot of dynamic content
    foreach ($changing_indexes as $indx => $static_page)
	{
        $last_mod = date('Y-m-d');
        $changeFreq = 'weekly'; //could also be 'hourly' or 'always'
        //fprintf($sitemap_loop_handle, "<url><loc>https://%s.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n",$lang_val, $static_page, $changeFreq);
        fprintf($sitemap_loop_handle, "<url><loc>https://%s.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n",$lang_val, $static_page, $changeFreq);
		
		unset($changing_indexes[$indx]);
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
        fprintf($sitemap_loop_handle, "<url><loc>https://%s.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n",$lang_val, $category, $changeFreq);
		
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
        fprintf($sitemap_loop_handle, "<url><loc>https://%s.touristtube.com/%s--Sa_1_0</loc><changefreq>%s</changefreq></url>\n",$lang_val, $category, $changeFreq);
		
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
        fprintf($sitemap_loop_handle, "<url><loc>https://%s.touristtube.com/channels-category/%s</loc><changefreq>%s</changefreq></url>\n",$lang_val, $category, $changeFreq);
		
		unset($rows[$indx]);
    }
    
    $query = "SELECT DISTINCT co.code FROM `cms_countries` as co INNER JOIN cms_channel AS ch ON ch.country = co.code AND ch.published = 1 ORDER BY name ASC";
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
        $category = $row_item[0];
        fprintf($sitemap_loop_handle, "<url><loc>https://%s.touristtube.com/channel-search/co/%s</loc><changefreq>%s</changefreq></url>\n",$lang_val, $category, $changeFreq);
		
		unset($rows[$indx]);
    }

    /*$query = "SELECT channel_url,id FROM cms_channel WHERE published=1";
    //$res = db_query($query);
    while ($row = db_fetch_row($res)) {
        $last_mod = date('Y-m-d');
        $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
        $category = $row[0];
        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/channel/%s</loc><changefreq>%s</changefreq></url>\n", $category,  $changeFreq);
        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/channel-events/%s</loc><changefreq>%s</changefreq></url>\n", $category,  $changeFreq);
        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/channel-brochures/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/channel-news/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/channel-photos/%s</loc><changefreq>%s</changefreq></url>\n", $category,  $changeFreq);
        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/channel-videos/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/channel-albums/%s</loc><changefreq>%s</changefreq></url>\n", $category,  $changeFreq);
        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/channel-log/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);

        $query1 = "SELECT id FROM cms_channel_event WHERE channelid=$row[1] AND published=1";
        //$res1 = db_query($query1);
        while ($row1 = db_fetch_row($res1)) {
            $last_mod = date('Y-m-d');
            $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
            $category = $row1[0];
            fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/channel-events-detailed/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        }
    }*/

    fwrite($sitemap_loop_handle, '</urlset>');
    fclose($sitemap_loop_handle);
    system("gzip -9 -f {$path}{$sitemap_loop_name}");
        
        // diseabled till version 2
        /*$sitemap_loop_name = "sitemap/".$prefixlang."sitemap_hotels.xml";
		echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
        $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
        if (!$sitemap_loop_handle) {
            fclose($sitemapindex_handle);
			$dbConn = null;
            exit;
        }
        fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
        fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

        $query = "SELECT id FROM discover_hotels";
        //$res = db_query($query);
        while ($row = db_fetch_row($res)) {
            $last_mod = date('Y-m-d');
            $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
            $category = $row[0];
            fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/thotel/id/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        }
        fwrite($sitemap_loop_handle, '</urlset>');
        fclose($sitemap_loop_handle);
        system("gzip -9 -f {$path}{$sitemap_loop_name}");
         */

        $page = 0;
        $pageloop = true;
		
        while($pageloop)
		{
            $pagename = $page + 1;
            $end = 25000;
            $start = $page * $end;
            $query = "SELECT id, name, country_code FROM webgeocities LIMIT $start, $end";
            
            $statement = $dbConn->prepare($query);
            $res    = $statement->execute();
            $ret    = $statement->rowCount();
			
            if(!$res || !$ret)
			{
                $pageloop = false;
            }
			else
			{
                $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_where_is_cities$pagename.xml";
				echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
				
                if (!$sitemap_loop_handle)
				{
                    fclose($sitemapindex_handle);
					$statement->closeCursor();
					$statement = null;
					$dbConn = null;
                    exit;
                }
				
                fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

                $rows = $statement->fetchAll();
				$statement->closeCursor();
				$statement = null;
				
                $rowsvalall = $rows;
				
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $id = $row_item[0];
                    $title = cleanTitleSitemap($row_item[1]);                    
                    fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/where-is-%s-C_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
					
					unset($rows[$indx]);
                }
				
                fwrite($sitemap_loop_handle, '</urlset>');
                fclose($sitemap_loop_handle);
                system("gzip -9 -f {$path}{$sitemap_loop_name}");
                
                $rows = $rowsvalall;
                $valcount = 0;
				
				$qry = "SELECT CASE WHEN EXISTS (SELECT id FROM discover_hotels WHERE city_id = :city_id) THEN 1 ELSE 0 END AS hotels_exists";
				$stmt = $dbConn->prepare($qry);
				
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $id = $row_item[0];
					
					/*
                    $qry = "SELECT id FROM `discover_hotels` WHERE city_id = $id LIMIT 1";            
					$slct = $dbConn->prepare($qry);
                    $res1 = $slct->execute();
                    $ret1 = $slct->rowCount();
					*/
					
					$stmt->bindValue(':city_id', $id, PDO::PARAM_INT);
					$res1 = $stmt->execute();
					$ret1 = $stmt->fetchColumn();
					
                    if($res1 && $ret1)
					{
                        if(!$valcount)
						{
                            $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_hotels_in_cities$pagename.xml";
							echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
							
                            if (!$sitemap_loop_handle)
							{
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
                        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/hotels-in-%s-C_%s</loc><changefreq>%s</changefreq></url>\n", $title, $id, $changeFreq);
                    }
					
					unset($rows[$indx]);
                }
				
				$stmt->closeCursor();
				$stmt = null;
				
                if($valcount)
				{
                    fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                    fwrite($sitemap_loop_handle, '</urlset>');
                    fclose($sitemap_loop_handle);
                    system("gzip -9 -f {$path}{$sitemap_loop_name}"); 
                }
                
                $rows = $rowsvalall;
                $valcount = 0;
				
                $qry = "SELECT CASE WHEN EXISTS (SELECT id FROM global_restaurants WHERE country = :country AND published = 1 AND (locality LIKE :locality OR region LIKE :region)) THEN 1 ELSE 0 END AS hotels_exists";
                $stmt = $dbConn->prepare($qry);
				
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $id = $row_item[0];
                    $country = $row_item[2];
					
					/*
                    $qry = "SELECT id FROM `global_restaurants` WHERE `country`='$country' AND published = 1 AND (`locality` LIKE '".$row_item[1]."' OR `region` LIKE '".$row_item[1]."') LIMIT 1";            
                    $slct = $dbConn->prepare($qry);
                    $res1 = $slct->execute();
                    $ret1 = $slct->rowCount();
					*/
					
					$stmt->bindValue(':country', $country, PDO::PARAM_STR);
					$stmt->bindValue(':locality', $row_item[1], PDO::PARAM_STR);
					$stmt->bindValue(':region', $row_item[1], PDO::PARAM_STR);
					
					$res1 = $stmt->execute();
					$ret1 = $stmt->fetchColumn();
					
                    if($res1 && $ret1)
					{
                        if(!$valcount)
						{
                            $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_restaurants_in_cities$pagename.xml";
							echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
							
                            if (!$sitemap_loop_handle)
							{
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
                        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/restaurants-in-%s-C_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
                    }
					
					unset($rows[$indx]);					
                }
				
				$stmt->closeCursor();
				$stmt = null;
				
                if($valcount)
				{
                    fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                    fwrite($sitemap_loop_handle, '</urlset>');
                    fclose($sitemap_loop_handle);
                    system("gzip -9 -f {$path}{$sitemap_loop_name}");
                }
				
                $page++;
            }
        }
		
        $page = 0;
        $pageloop = true;
        while($pageloop)
		{
            $pagename = $page + 1;
            $end = 25000;
            $start = $page * $end;
            $query = "SELECT code, name FROM cms_countries LIMIT $start, $end";
            
            $statement = $dbConn->prepare($query);
            $res    = $statement->execute();
            $ret    = $statement->rowCount();
			
            if(!$res || !$ret)
			{
                $pageloop = false;
            }
			else
			{
                $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_where_is_countries$pagename.xml";
				echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
				
                if (!$sitemap_loop_handle)
				{
                    fclose($sitemapindex_handle);
					$statement->closeCursor();
					$statement = null;
					$dbConn = null;
                    exit;
                }
				
                fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

                $rows = $statement->fetchAll();
				$statement->closeCursor();
				$statement = null;
				
                $rowsvalall = $rows;
				
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $id = $row_item[0];
                    $title = cleanTitleSitemap($row_item[1]);
                    fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/where-is-%s-CO_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
					
					unset($rows[$indx]);
                }
				
                fwrite($sitemap_loop_handle, '</urlset>');
                fclose($sitemap_loop_handle);
                system("gzip -9 -f {$path}{$sitemap_loop_name}");
                
                $rows = $rowsvalall;
                $valcount = 0;
				
				$stmt = $dbConn->prepare("SELECT CASE WHEN EXISTS (SELECT id FROM discover_hotels WHERE countryCode = :country_code) THEN 1 ELSE 0 END AS hotels_exists");
				
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $id = $row_item[0];
					
                    /*
					$qry = "SELECT id FROM discover_hotels WHERE countryCode = '$id' LIMIT 1";
                    $slct = $dbConn->prepare($qry);					
                    $res1 = $slct->execute();
                    $ret1 = $slct->rowCount();
					*/
					
					$stmt->bindValue(':country_code', $id, PDO::PARAM_STR);
					$res1 = $stmt->execute();
					$ret1 = $stmt->fetchColumn();
					
                    if($res1 && $ret1)
					{
                        if(!$valcount)
						{
                            $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_hotels_in_countries$pagename.xml";
							echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
                            
							if (!$sitemap_loop_handle)
							{
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
                        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/hotels-in-%s-CO_%s</loc><changefreq>%s</changefreq></url>\n", $title, $id, $changeFreq);
                    }
					
					unset($rows[$indx]);
                }
				
				$stmt->closeCursor();
				$stmt = null;
				
                if($valcount)
				{
                    fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                    fwrite($sitemap_loop_handle, '</urlset>');
                    fclose($sitemap_loop_handle);
                    system("gzip -9 -f {$path}{$sitemap_loop_name}");
                }

                $rows = $rowsvalall;
                $valcount = 0;
				
				$stmt = $dbConn->prepare("SELECT CASE WHEN EXISTS (SELECT id FROM global_restaurants WHERE country = :country_code AND published = 1) THEN 1 ELSE 0 END AS restaurants_exists");
				
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $id = $row_item[0];
					
					/*
                    $qry = "SELECT id FROM `global_restaurants` WHERE `country`='$id' AND published = 1 LIMIT 1";            
                    $slct = $dbConn->prepare($qry);
                    $res1 = $slct->execute();
                    $ret1 = $slct->rowCount();
					*/
					
					$stmt->bindValue(':country_code', $id, PDO::PARAM_STR);
					$res1 = $stmt->execute();
					$ret1 = $stmt->fetchColumn();
					
                    if($res1 && $ret1)
					{
                        if(!$valcount)
						{
                            $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_restaurants_in_countries$pagename.xml";
							echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
							
                            if (!$sitemap_loop_handle)
							{
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
                        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/restaurants-in-%s-CO_%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
                    }
					
					unset($rows[$indx]);
                }
				
				$stmt->closeCursor();
				$stmt = null;
				
                if($valcount)
				{
                    fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                    fwrite($sitemap_loop_handle, '</urlset>');
                    fclose($sitemap_loop_handle);
                    system("gzip -9 -f {$path}{$sitemap_loop_name}");
                }
				
                $page++;
            }
        }
		
        $page = 0;
        $pageloop = true;
		
        while($pageloop)
		{
            $pagename = $page + 1;
            $end = 25000;
            $start = $page * $end;
            $query = "SELECT `country_code`,`state_code`,`state_name` FROM states LIMIT $start, $end";
            
            $statement = $dbConn->prepare($query);
            $res    = $statement->execute();
            $ret    = $statement->rowCount();
			
            if(!$res || !$ret)
			{
                $pageloop = false;
            }
			else
			{
                $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_where_is_states$pagename.xml";
				echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
				
                if (!$sitemap_loop_handle)
				{
                    fclose($sitemapindex_handle);
					$statement->closeCursor();
					$statement = null;
					$dbConn = null;
                    exit;
                }
				
                fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

                $rows = $statement->fetchAll();
				$statement->closeCursor();
				$statement = null;
				
                $rowsvalall = $rows;
				
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $country_code = $row_item[0];    
                    $state_code = $row_item[1];
                    $title = cleanTitleSitemap($row_item[2]);
                    fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/where-is-%s-S_%s_%s</loc><changefreq>%s</changefreq></url>\n", $title,$state_code,$country_code, $changeFreq);
					
					unset($rows[$indx]);
                }
				
                fwrite($sitemap_loop_handle, '</urlset>');
                fclose($sitemap_loop_handle);
                system("gzip -9 -f {$path}{$sitemap_loop_name}");

                $rows = $rowsvalall;
                $valcount = 0;
				
				$qry = "SELECT CASE WHEN EXISTS (SELECT h.id FROM discover_hotels as h inner join webgeocities as w on (w.id = h.city_id) inner join states as s (on s.country_code = w.country_code and s.state_code = w.state_code) WHERE h.city_id > 0 and w.state_code = :state_code and w.country_code = :country_code) THEN 1 ELSE 0 END AS hotels_exists";
				$stmt = $dbConn->prepare($qry);
				
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $country_code = $row_item[0];    
                    $state_code = $row_item[1];
					
					/*
                    $qry = "SELECT h.id FROM `discover_hotels` as h inner join webgeocities as w on w.id=h.city_id inner join states as s on s.country_code=w.country_code and s.state_code=w.state_code WHERE h.city_id>0 and w.state_code='$state_code' and w.country_code='$country_code' LIMIT 1";            
                    $slct = $dbConn->prepare($qry);
                    $res1 = $slct->execute();
                    $ret1 = $slct->rowCount();
					*/
					
					$stmt->bindValue(':country_code', $country_code, PDO::PARAM_STR);
					$stmt->bindValue(':state_code', $state_code, PDO::PARAM_STR);
					$res1 = $stmt->execute();
					$ret1 = $stmt->fetchColumn();
					
                    if($res1 && $ret1)
					{
                        if(!$valcount)
						{
                            $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_hotels_in_states$pagename.xml";
							echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
                            if (!$sitemap_loop_handle)
							{
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
                        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/hotels-in-%s-S_%s_%s</loc><changefreq>%s</changefreq></url>\n", $title,$state_code,$country_code, $changeFreq);
                    }
					
					unset($rows[$indx]);
                }
				
				$stmt->closeCursor();
				$stmt = null;
				
                if($valcount)
				{
                    fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                    fwrite($sitemap_loop_handle, '</urlset>');
                    fclose($sitemap_loop_handle);
                    system("gzip -9 -f {$path}{$sitemap_loop_name}");
                }
                
                /*$sitemap_loop_name = "sitemap/".$prefixlang."sitemap_restaurants_in_states$pagename.xml";
				echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
                if (!$sitemap_loop_handle) {
                    fclose($sitemapindex_handle);
					$dbConn = null;
                    exit;
                }
                fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

                $rows = $rowsvalall;
                foreach($rows as $row_item){
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly';
                    $country_code = $row_item[0];    
                    $state_code = $row_item[1];
                    $title = cleanTitleSitemap($row_item[2]);
                    fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/restaurants-in-%s-S_%s_%s</loc><changefreq>%s</changefreq></url>\n", $title,$state_code,$country_code, $changeFreq);
                }
                fwrite($sitemap_loop_handle, '</urlset>');
                fclose($sitemap_loop_handle);
                system("gzip -9 -f {$path}{$sitemap_loop_name}");*/
                
                $page++;
            }
        }
		
        $page = 0;
        $pageloop = true;
		
        while($pageloop)
		{
            $pagename = $page + 1;
            $end = 25000;
            $start = $page * $end;
            $query = "SELECT id, name, city_id, country FROM discover_poi where published = 1 LIMIT $start, $end";
            $statement = $dbConn->prepare($query);
            $res    = $statement->execute();
            $ret    = $statement->rowCount();
    //            $res = db_query($query);
    //            if(!$res || (db_num_rows($res) == 0) ){
            if(!$res || !$ret)
			{
                $pageloop =false;
            }
			else
			{
                $rows = $statement->fetchAll();
                $rowsvalall = $rows;
				
				$statement->closeCursor();
				$statement = null;
				
                $valcount = 0;
				
				$qry = "SELECT CASE WHEN EXISTS (SELECT id FROM discover_hotels WHERE city_id = :city_id) THEN 1 ELSE 0 END AS hotels_exists";
				$stmt = $dbConn->prepare($qry);
				
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly'; 
                    $id = $row_item[0];
                    $city_id = $row_item[2];
					
					/*
                    $qry = "SELECT id FROM `discover_hotels` WHERE `city_id`=$city_id LIMIT 1";            
                    $slct = $dbConn->prepare($qry);
                    $res1 = $slct->execute();
                    $ret1 = $slct->rowCount();
					*/
					
					$stmt->bindValue(':city_id', $city_id, PDO::PARAM_INT);
					$res1 = $stmt->execute();
					$ret1 = $stmt->fetchColumn();
					
                    if($res1 && $ret1)
					{
                        if(!$valcount)
						{
                            $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_hotels_near_by$pagename.xml";
							echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
                            if (!$sitemap_loop_handle)
							{
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
                        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/hotels-near-by-%s_%s_1</loc><changefreq>%s</changefreq></url>\n",$title ,$id, $changeFreq);
                    }
					
					unset($rows[$indx]);
                }
				
				$stmt->closeCursor();
				$stmt = null;
				
                if($valcount)
				{
                    fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
                    fwrite($sitemap_loop_handle, '</urlset>');
                    fclose($sitemap_loop_handle);
                    system("gzip -9 -f {$path}{$sitemap_loop_name}");
                }

                $rows = $rowsvalall;
                $valcount = 0;
				
				$qry = "SELECT CASE WHEN EXISTS (SELECT id FROM global_restaurants WHERE country = :country) THEN 1 ELSE 0 END AS restaurants_exists";
				$stmt = $dbConn->prepare($qry);
				
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly'; 
                    $id = $row_item[0];
                    $country = $row_item[3];
                    
					/*
					$qry = "SELECT id FROM `global_restaurants` WHERE `country`='$country' AND published = 1 LIMIT 1";            
                    $slct = $dbConn->prepare($qry);
                    $res1 = $slct->execute();
                    $ret1 = $slct->rowCount();
					*/
					
					$stmt->bindValue(':country', $country, PDO::PARAM_STR);
					$res1 = $stmt->execute();
					$ret1 = $stmt->fetchColumn();
					
                    if($res1 && $ret1)
					{
                        if(!$valcount)
						{
                            $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_restaurants_near_by$pagename.xml";
							echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
                            if (!$sitemap_loop_handle)
							{
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
                        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/restaurants-near-by-%s_%s_1_1</loc><changefreq>%s</changefreq></url>\n",$title, $id, $changeFreq);
                    }
					
					unset($rows[$indx]);
                }
				
				$stmt->closeCursor();
				$stmt = null;
				
                if($valcount)
				{
                    fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
                    fwrite($sitemap_loop_handle, '</urlset>');
                    fclose($sitemap_loop_handle);
                    system("gzip -9 -f {$path}{$sitemap_loop_name}");  
                }
				
                $page++;
            }
        }
		
        $page = 0;
        $pageloop = true;
		
        while($pageloop)
		{
            $pagename = $page + 1;
            $end = 25000;
            $start = $page * $end;
            $query = "SELECT id,hotelName FROM discover_hotels where published=1 LIMIT $start, $end";
            
            $statement = $dbConn->prepare($query);
            $res    = $statement->execute();
            $ret    = $statement->rowCount();
//            $res = db_query($query);

//            if(!$res || (db_num_rows($res) == 0) ){
            if(!$res || !$ret)
			{
                $pageloop = false;
            }
			else
			{
                $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_hotels_reviews$pagename.xml";
				echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
				
                if (!$sitemap_loop_handle)
				{
                    fclose($sitemapindex_handle);
					$statement->closeCursor();
					$statement = null;
					$dbConn = null;
                    exit;
                }
				
                fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz", date('Y-m-d'));
                fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

                $rows = $statement->fetchAll();
				
				$statement->closeCursor();
				$statement = null;
				
//                while ($row = db_fetch_row($res)) {
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
                    $id = $row_item[0];
                    $title = cleanTitleSitemap($row_item[1]);
                    fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/%s-review-H%s</loc><changefreq>%s</changefreq></url>\n", $title, $id, $changeFreq);
                    unset($rows[$indx]);
                }
				
                fwrite($sitemap_loop_handle, '</urlset>');
                fclose($sitemap_loop_handle);
                system("gzip -9 -f {$path}{$sitemap_loop_name}");
				
                $page++;
            }
        }
        // diseabled till version 2
        /*$sitemap_loop_name = "sitemap/".$prefixlang."sitemap_restaurants.xml";
		echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
        $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
        if (!$sitemap_loop_handle) {
            fclose($sitemapindex_handle);
			$dbConn = null;
            exit;
        }
        fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
        fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

        $query = "SELECT id FROM discover_restaurants where published=1";
        //$res = db_query($query);
        while ($row = db_fetch_row($res)) {
            $last_mod = date('Y-m-d');
            $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
            $category = $row[0];
            fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/trestaurant/id/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
        }
        fwrite($sitemap_loop_handle, '</urlset>');
        fclose($sitemap_loop_handle);
        system("gzip -9 -f {$path}{$sitemap_loop_name}");
         */
    if($lang_val=='www')
	{
        $page = 0;
        $pageloop = true;
		
        while($pageloop)
		{
            $pagename = $page + 1;
            $end = 25000;
            $start = $page * $end;
            $query = "SELECT id, name FROM global_restaurants where published = 1 ORDER BY zoom_order DESC LIMIT $start, $end";
            $statement = $dbConn->prepare($query);
            $res    = $statement->execute();
            $ret    = $statement->rowCount();
			
            if(!$res || !$ret)
			{
                $pageloop = false;
            }
			else
			{
                $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_restaurants_reviews$pagename.xml";
				echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
                $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
				
                if (!$sitemap_loop_handle)
				{
                    fclose($sitemapindex_handle);
					$statement->closeCursor();
					$statement = null;
					$dbConn = null;
                    exit;
                }
				
                fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
                fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

                $rows = $statement->fetchAll();
				$statement->closeCursor();
				$statement = null;
				
//                while ($row = db_fetch_row($res)) {
                foreach($rows as $indx => $row_item)
				{
                    $last_mod = date('Y-m-d');
                    $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
                    $id = $row_item[0];
                    $title = cleanTitleSitemap($row_item[1]);
                    fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/%s-review-R%s</loc><changefreq>%s</changefreq></url>\n",$title ,$id, $changeFreq);
					
					unset($rows[$indx]);
                }
				
                fwrite($sitemap_loop_handle, '</urlset>');
                fclose($sitemap_loop_handle);
                system("gzip -9 -f {$path}{$sitemap_loop_name}");
				
                $page++;
            }
        }
    }
    // diseabled till version 2
    /*$sitemap_loop_name = "sitemap/".$prefixlang."sitemap_pois.xml";
	echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
    $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
    if (!$sitemap_loop_handle) {
        fclose($sitemapindex_handle);
		$dbConn = null;
        exit;
    }
    fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
    fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

    $query = "SELECT id FROM discover_poi where published=1";
    //$res = db_query($query);
    while ($row = db_fetch_row($res)) {
        $last_mod = date('Y-m-d');
        $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
        $category = $row[0];
        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/things2do/id/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
    }
    fwrite($sitemap_loop_handle, '</urlset>');
    fclose($sitemap_loop_handle);
    system("gzip -9 -f {$path}{$sitemap_loop_name}");
     */
    $page = 0;
    $pageloop = true;
	
    while($pageloop)
	{
        $pagename = $page + 1;
        $end = 25000;
        $start = $page * $end;
        $query = "SELECT id, name FROM discover_poi where published = 1 LIMIT $start, $end";
        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $ret    = $statement->rowCount();
//            $res = db_query($query);
//            if(!$res || (db_num_rows($res) == 0) ){
        if(!$res || !$ret)
		{
            $pageloop = false;
        }
		else
		{
            $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_pois_reviews$pagename.xml";
			echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
            if (!$sitemap_loop_handle) 
			{
                fclose($sitemapindex_handle);
				$statement->closeCursor();
				$statement = null;
				$dbConn = null;
                exit;
            }
			
            fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
            fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

            $rows = $statement->fetchAll();
			
			$statement->closeCursor();
			$statement = null;
			
//                while ($row = db_fetch_row($res)) {
            foreach($rows as $indx => $row_item)
			{
                $last_mod = date('Y-m-d');
                $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
                $id = $row_item[0];
                $title = cleanTitleSitemap($row_item[1]);
                fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/%s-review-T%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
				
				unset($rows[$indx]);
            }
			
            fwrite($sitemap_loop_handle, '</urlset>');
            fclose($sitemap_loop_handle);
            system("gzip -9 -f {$path}{$sitemap_loop_name}");
			
            $page++;
        }
    }

    $page = 0;
    $pageloop = true;
	
    while($pageloop)
	{
        $pagename = $page + 1;
        $end = 25000;
        $start = $page * $end;
        $query = "SELECT id,name FROM airport where published=1 LIMIT $start, $end";
        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $ret    = $statement->rowCount();
//            $res = db_query($query);

//            if(!$res || (db_num_rows($res) == 0) ){
        if(!$res || !$ret)
		{
            $pageloop = false;
        }
		else
		{
            $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_airports_reviews$pagename.xml";
			echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
            $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
            if (!$sitemap_loop_handle)
			{
                fclose($sitemapindex_handle);
				$statement->closeCursor();
				$statement = null;
				$dbConn = null;
                exit;
            }
			
            fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
            fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

            $rows = $statement->fetchAll();
			
			$statement->closeCursor();
			$statement = null;
			
//                while ($row = db_fetch_row($res)) {
            foreach($rows as $indx => $row_item)
			{
                $last_mod = date('Y-m-d');
                $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
                $id = $row_item[0];
                $title = cleanTitleSitemap($row_item[1]);
                fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/%s-review-A%s</loc><changefreq>%s</changefreq></url>\n", $title,$id, $changeFreq);
				
				unset($rows[$indx]);
            }
			
            fwrite($sitemap_loop_handle, '</urlset>');
            fclose($sitemap_loop_handle);
            system("gzip -9 -f {$path}{$sitemap_loop_name}");
			
            $page++;
        }
    }

    
    $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_lives.xml";
	echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
    $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
	
    if (!$sitemap_loop_handle)
	{
        fclose($sitemapindex_handle);
		$dbConn = null;
        exit;
    }
	
    fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
    fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);
    
    $query = "SELECT url FROM cms_webcams WHERE state=1";
//    $res = db_query($query);
    $statement = $dbConn->prepare($query);
    $res    = $statement->execute();
    $rows = $statement->fetchAll();
	
	$statement->closeCursor();
	$statement = null;
	
    foreach($rows as $indx => $row_item)
	{
//    while ($row = db_fetch_row($res)) {
        $last_mod = date('Y-m-d');
        $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
        $category = $row_item[0];    
        fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val.touristtube.com/live-cam/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
		
		unset($rows[$indx]);
    }
	
    fwrite($sitemap_loop_handle, '</urlset>');
    fclose($sitemap_loop_handle);
    system("gzip -9 -f {$path}{$sitemap_loop_name}");
    
    if($lang_val == 'www')
	{
        $lang_valqr = 'en';
        $sitemap_loop_name = "sitemap/".$prefixlang."sitemap_thingstodo.xml";
		echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$sitemap_loop_name;
        $sitemap_loop_handle = fopen($path.$sitemap_loop_name, "w");
		
		if (!$sitemap_loop_handle)
		{
            fclose($sitemapindex_handle);
			$dbConn = null;
            exit;
        }
		
        fprintf($sitemapindex_handle, "<sitemap><loc>https://$lang_val.touristtube.com/%s</loc></sitemap>\n", $sitemap_loop_name . ".gz");
        fwrite($sitemap_loop_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);

        $query = "SELECT a.alias,a.language FROM `cms_thingstodo_region` as r inner join alias as a on a.id=r.alias_id where r.published=1 and a.language='en'";
    //    $res = db_query($query);
        $statement = $dbConn->prepare($query);
        $res    = $statement->execute();
        $rows = $statement->fetchAll();
		
		$statement->closeCursor();
		$statement = null;
		
        foreach($rows as $indx => $row_item)
		{
    //    while ($row = db_fetch_row($res)) {
            $last_mod = date('Y-m-d');
            $changeFreq = 'monthly'; //could also be 'hourly' or 'always'
            $category = $row_item[0];
            $lang_val1 = $row_item[1];
            if($lang_val1=='en') $lang_val1='www';
            fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val1.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
			
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
            if($lang_val1=='en') $lang_val1='www';   
            fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val1.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
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
            if($lang_val1=='en') $lang_val1='www';   
            fprintf($sitemap_loop_handle, "<url><loc>https://$lang_val1.touristtube.com/%s</loc><changefreq>%s</changefreq></url>\n", $category, $changeFreq);
            unset($rows[$indx]);
        }
		
        fwrite($sitemap_loop_handle, '</urlset>');
        fclose($sitemap_loop_handle);
        system("gzip -9 -f {$path}{$sitemap_loop_name}");
    }
    //close the sitemapindex
    fwrite($sitemapindex_handle, '</sitemapindex>');
    fclose($sitemapindex_handle);
}

$dbConn = null;
echo PHP_EOL.strftime('%Y-%m-%d %T')." END\n\n";

