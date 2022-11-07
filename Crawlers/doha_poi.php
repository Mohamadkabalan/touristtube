<?php
echo "hi";
die();
require_once 'simple_html_dom.php';
//require_once 'conn.php';
$conn = mysqli_connect("localhost","root","tt","tt");
if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($conn));
}  

for($page=1;$page<=6;$page++)
{

$html = file_get_html("http://www.gogobot.com/qatar--things_to_do?page=".$page);
  
  $lat="";
  $lng="";
  $name="";
  $city="";
  $latlng="";

 foreach($html->find('.place') as $link)
 {
     
	 
$link1 = $link->find("a", 0)->href;
$img=$link->find("img",0)->src;
$img_link =str_replace("&#47;","/",trim($img));
$img1 		= 	file_get_contents($img_link);
$f_link = "http://www.gogobot.com".$link1;
$flink = str_replace("&#47;","/",trim($f_link));

   $detail = file_get_html($flink);
   
   $about=$detail->find(".categoriesList",0)->plaintext;
   $about= str_replace("'","&apos;",trim($about));
   
   $address = $detail->find("*/div[@itemprop='streetAddress']",0)->plaintext;
   $name = $detail->find("*/span[@itemprop='name']",0)->plaintext;
   $city = $detail->find("*/span[@itemprop='title']",1)->plaintext;
  
	$opening_hr= $detail->find('.openHours',0)->plaintext;
	$contact=$detail->find('.phoneNumber',0)->plaintext;
	//foreach($detail->find('.placeNameDiv .row') as $latl)
        foreach($detail->find('.mapViewDiv img') as $latl)
	{
            
            $lat2 = $latl->src; 
            $lat3 = explode(",", $lat2);
            $lat4 = $lat3[0];
            $lat5 = explode("%7C", $lat4);
            $lat =  end($lat5);
            $lng4 = $lat3[1];
            $lng5 = explode("&", $lng4);
            $lng = $lng5[0];
	}
	
  
  $description= $detail->find('.desc .readable',0)->plaintext;
			
  $name= str_replace("'","&apos;",trim($name));
  $description = str_replace("'","&apos;",trim($description));
  //$description= str_replace("'","&apos;",trim($description));
  $address= str_replace("'","&apos;",trim($address));
  $contact= str_replace("'","&apos;",trim($contact));
  $lat= str_replace("'","&apos;",trim($lat));
  $lng= str_replace("'","&apos;",trim($lng));
 
  $flink= str_replace("'","&apos;",trim($flink));
  $opening_hr= str_replace("'","&apos;",trim($opening_hr));
  echo $Query      =   "INSERT INTO `prague_poi`(`id`, `longitude`, `latitude`, `name`, `stars`, `country`, `city`, `zoom_order`, `show_on_map`, `cat`, `sub_cat`, `map_image`, `city_id`, `zipcode`, `phone`, `fax`, `email`, `website`, `price`, `description`, `published`, `last_modified`, `address`, `from_source`, `status`, `State`, `state_code`, `n_city_local`, `n_city_admin`) VALUES "
          . "('','$lng','$lat','$name','','QA','$city','','','','','','','','$contact','', '', '$flink', '', '$description', '', '', '$address', '', '10', '', '', '', '' )";
  

			if (mysqli_query($conn, $Query ))
		    {
				echo "New record created successfully";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
			$id = mysqli_insert_id($conn);
            $result_image   =   'image/brazil/'.$id.'.jpg';
            file_put_contents($result_image,$img1);
  
  
  }
       
$html='';
		
}
        
  
?>
