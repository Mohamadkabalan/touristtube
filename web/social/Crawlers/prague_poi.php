<meta http-equiv="Content-Type" content="text/html; 
charset=UTF-8" />
<?php
$from=$_GET['frm'];
$t2=$_GET['t2'];

require_once 'simple_html_dom.php';
//require_once 'conn.php';

 $conn = mysqli_connect("localhost","root","tt","tt");
 if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($conn));
}

for($page=$from;$page<=$t2;$page++){
        $domain = "http://www.lonelyplanet.com";
    
        $html = file_get_html($domain."/greece/sights?page=".$page);
	

        foreach($html->find('.card__mask') as $i){
		$title = '';
		$description = '';
		$location = '';
		$address = '';
		$contact = '';
		$price = '';
		$openning_hr = '';
		$title = '';
		$lat = '';
		$lng = '';
		$city='';
            
            $link = $i->find('a', 0)->href; 
			
			$city = $i->find('.card__footer', 0)->plaintext; 
			$city= str_replace("'","''",trim($city));
			$city= str_replace("’","''",trim($city));
			
			
			
            $website = $domain.$link;
           
            $detail = file_get_html($domain.$link); 
            
            $titles = $detail->find('.copy--h1', 0)->plaintext;
 
			$title= str_replace("'","''",trim($titles));
			
            
			$description=$detail->find(".ttd__section",0)->plaintext;

            $description= mysqli_real_escape_string($conn,trim($description));
			
			

	    for ($i=0;$i<=6;$i++)
		  {
		  $data = $detail->find('.grid-wrapper--20 dt',$i)->plaintext;
		  $data= trim($data); 
		  /*if ($data=="Location")
		  {
		  $location=$detail->find('.grid-wrapper--20 dd',$i)->plaintext;
		  }*/
		  if ($data=="Telephone")
		  {
		  $contact=$detail->find('.grid-wrapper--20 dd',$i)->plaintext;
		  $contact= str_replace("'","''",trim($contact));
		  }
		  if ($data=="Address")
		  {
		  $address=$detail->find('.grid-wrapper--20 dd',$i)->plaintext;
		  $address= str_replace("'","''",trim($address));
		  
		  }
			if ($data=="Prices")
		  {
		  $price=$detail->find('.grid-wrapper--20 dd',$i)->plaintext;
		  $price= str_replace("'","''",trim($price));
		  }
		  /*if ($data=="Opening hours")
		  {
		  echo $price=$detail->find('.grid-wrapper--20 dd',$i)->plaintext;
		  }*/
		  }
			    
			    
           
                                 
          
               foreach ($detail->find(".poi-map__container") as $key=>$val){
               echo $lat = $val->getAttribute('data-latitude',0);
               echo $lng = $val->getAttribute('data-longitude',0);
           }
          
   $query      =   "INSERT INTO `prague_poi` (`id`, `longitude`, `latitude`, `name`, `stars`, `country`, `city`, `zoom_order`, `show_on_map`, `cat`, `sub_cat`, `map_image`, `city_id`, `zipcode`, `phone`, `fax`, `email`, `website`, `price`, `description`, `published`,  `address`, `from_source`, `status`, `State`, `state_code`, `n_city_local`, `n_city_admin`) VALUES ('','$lng', '$lat',	'$title', '', 'GR', '$city', '', '', '', '', '', '', '', '$contact', '', '', '$website', '$price', '$description', '',  '$address', 'lonelyplanet', '3', '', '', '', '' )";

         
            echo "-----------------------------------------------------------------"."<BR>";
           
        if (mysqli_query($conn, $query ))
      {
    echo "New record created successfully";
      } 
      else
      {
   echo "Error: " . $query  . "<br>" . mysqli_error($conn);
	  }
          
        }
	
		
		
	$html = '';	

}

?>


