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

    echo PHP_EOL.strftime('%Y-%m-%d %T').' '.$path .$prefixlang. "sitemap_static.xml".PHP_EOL;
    $sitemapindex_handle = fopen($path .$prefixlang. "sitemap_static.xml", "w");

    if (!$sitemapindex_handle)
    {
                $dbConn = null;
        exit;
    }
    //open the sitemap index
    fwrite($sitemapindex_handle, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);

    $static_pages = array('about-us', 'contact','disclaimer', 'terms-and-conditions' , 'privacy-policy','faq','review','help','support', 'cookie-policy');
    $changing_indexes = array('discover', 'hotel-booking', 'things-to-do', 'best-hotels-in-360-virtual-tour', '360-landmarks-virtual-tour', 'flight-booking', 'live');

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
    
    fwrite($sitemap_loop_handle, '</urlset>');
    fclose($sitemap_loop_handle);
    system("gzip -9 -f {$path}{$sitemap_loop_name}");

    //close the sitemapindex
    fwrite($sitemapindex_handle, '</sitemapindex>');
    fclose($sitemapindex_handle);
}

$dbConn = null;
echo PHP_EOL.strftime('%Y-%m-%d %T')." END\n\n";