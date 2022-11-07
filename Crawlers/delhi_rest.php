<?php
require_once 'simple_html_dom.php';
$conn = mysqli_connect("localhost","root","tt","zomato");
$city_url=$_GET['city_url'];
$city_url2=$_GET['city_url2'];
$start=$_GET['start'];
$end=$_GET['end'];
$status=$_GET['status'];


for($page=$start;$page<=$end;$page++)
{
    $html = file_get_html("https://www.zomato.com/$city_url/restaurants?page=".$page);
    //echo $html->find(".langbox__btn-name",0)->plaintext;
    //die();

    $detail=array();
    foreach($html->find('.search-result .result-title') as $i)
    {

        $link = $i->getAttribute('href');

        $linking=explode("/",$link);
        $link1=end($linking);
        array_pop($linking);
        //$link5=end($linking);
        //array_pop($linking);
        //array_pop($linking);
        $link4=implode("/",$linking);
        $link3=urlencode($link1);
        echo $final=$link4."/".$link3;
        $detail = file_get_html($final);

        $name = $detail->find(".res-name span",0)->plaintext;
        $rating = $detail->find(".res-rating .rating-div",0)->plaintext;


        if($name=="")
        {
            echo "not found </br>";
        }
        else

        {
            $detail->find(".langbox__btn-name",0)->plaintext;

            "Title:".$res_name		= $name;
            "Price:".$price 		= $detail->find("*/span[@itemprop='priceRange']",0)->plaintext;
            "Contact:".$tel		= $detail->find(".col-m-5 .phone", 0)->plaintext;
            //"Features:".$features	= $detail->find(".res-page-collection-text",0)->plaintext;
            "Cusine:".$cusine		= $detail->find(".res-info-cuisines",0)->plaintext;
            "Address:".$location	= $detail->find(".res-main-address-text",0)->plaintext;
            "Opening Hrs:".$time	= $detail->find(".res-info-timings",0)->plaintext;

            "Latitude:".$lat		= $detail->find("meta[property=place:location:latitude]",0)->getAttribute('content');
            "Longitude:".$lng		= $detail->find("meta[property=place:location:longitude]",0)->getAttribute('content');
            echo "---------------------------------------------------------------------------------<br>";

            $name = str_replace("'","&apos;",trim($res_name));
            $price = str_replace("€","&euro;",trim($price));
            $tel = str_replace("'","&apos;",trim($tel));
            $features = str_replace("'","&apos;",trim($features));
            $cusine= str_replace("'","&apos;",trim($cusine));
            $time= str_replace("'","&apos;",trim($time));
            $location= str_replace("'","&apos;",trim($location));

            $location1= str_replace(", ".$city_url2."India","",trim($location));
            $rating= str_replace("-","0",trim($rating));


            $city=$city_url2;

            $country="India";
            $cc="IN";
            if($rating=="")
            {
                $rating="0";
            }

            echo $Query      =   "INSERT INTO `zomato`.`zomato_in_restaurant` (`id` ,`res_name` ,`res_description` ,`res_about` ,`res_website` ,`res_address` ,`location`,`city`,`country_name`,`country_code`,`res_contact` ,`res_latitude` ,`res_longitude` ,`res_imagelink` ,`res_link` ,`res_cost` ,`res_feature`,`opening_hr`,`res_fax`,`status`) VALUES ('','$name','$cusine','','$final','$location','$location1','$city','$country','$cc','$tel','$lat','$lng','',$rating,'$price','','$time','',$status )";
            if (mysqli_query($conn, $Query ))
            {
                echo "New record created successfully";
            }
            else
            {
                echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
            }
            echo "---------------------------------------------------------------------------------<br>";
            /*
            $id = mysqli_insert_id($conn);
            $result_image   =   'image/beirut/'.$id.'.'.$ext1;
            file_put_contents($result_image,$img);
            */

        }
    }
}
$end='';
$start='';
?>
