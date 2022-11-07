<?php

//db connection
$conn = mysqli_connect("localhost", "root", "mysql_root", "touristtube");
if (!mysqli_set_charset($conn, "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($conn));
}
////////////////////////////////////


$timeout = 3;
$url = "http://www.travelshoptours.com/api/xml/tours/search?country_id=2433";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
$data = curl_exec($ch);
curl_close($ch);
$data1 = simplexml_load_string($data);
print_r($data1);
exit;
//print_r($data1);
//print_r($data1->item[0]->ID);
foreach ($data1->item as $value) {
    $id = $value->ID;
    $image_thumb = $value->Image_Long;
    $img_thumb_link = "http://www.travelshoptours.com/assets/public/files/thumbs/" . $image_thumb;
//Rename thumb Image
    $original_thumb_filename = $image_thumb;
    $extension_thumb = explode(".", $image_thumb);
    $new_name_thumb = microtime();
    $new_name_thumb = str_replace('0.', '', $new_name_thumb);
    $new_name_thumb = str_replace(' ', '', $new_name_thumb);

    /* change thumb file name */
    $new_full_name_thumb = "thumb_deal" . '_' . $new_name_thumb . '.' . end($extension_thumb);



    $img = file_get_contents($img_thumb_link);
    $result_image = './media/' . $new_full_name_thumb;
    file_put_contents($result_image, $img);



    $name = $value->Name;
    $name = str_replace("'", "''", trim($name));
    $start_city = $value->Tour_Start_City;
    $start_city = str_replace("'", "''", trim($start_city));
    $end_city = $value->Tour_End_City;
    $end_city = str_replace("'", "''", trim($end_city));
    $category = $value->Tour_Category_Name;
    $category = str_replace("'", "''", trim($category));
    $highlights = $value->Highlights;
    $highlights = str_replace("'", "''", trim($highlights));
    $price = $value->Price_Default;
    $price = str_replace("'", "''", trim($price));
    $from_date = $value->Available_On_Dates;
    $to_date = $value->Available_On_Dates;


//Isert into deal
    $Query = "INSERT INTO `deal`(`name`,`price`,`highlights`,`thumb`,`category`, `dealer_id`,`tour_from_date`, `tour_to_date`) VALUES ('$name','$price','$highlights','$new_full_name_thumb','$category',1,now(),now())";

    if (mysqli_query($conn, $Query)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $Query . "<br>" . mysqli_error($conn);
    }
    echo $inserted_deal_id = mysqli_insert_id($conn);

    /* Insert into deal_destination

      $Query1="INSERT INTO `deal_destination`(`name`,`deal_id`) VALUES ('$name','$price','$highlights','$new_full_name_thumb')";

      if (mysqli_query($conn, $Query ))
      {
      echo "New record created successfully";
      }
      else
      {
      echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
      }
     */



    $url2 = "http://www.travelshoptours.com/api/xml/tours/tour_info?tour_id=$id";
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $url2);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 2);
    $data2 = curl_exec($ch1);
    curl_close($ch1);
    $data3 = simplexml_load_string($data2);
    //print_r($data3);
//Insert into deal_detail				 
    foreach ($data3->tourPrices->item as $price_option){
        $tour_price_id = $price_option->Tour_Price_ID;
        $amount = $price_option->Amount;
        $offer_type_id = $price_option->Offer_Type_ID;
        $offer_type_name = $price_option->Offer_Type_Name;
        $group_size_id = $price_option->Group_Size_ID;
        $group_size_name = $price_option->Group_Size_Name;
        $season_id = $price_option->Season_ID;
        $season_name = $price_option->Season_Name;
        
        $amount_3_star_triple = $price_option->Amount_3_Star_Triple;
        $amount_3_star_shared_double = $price_option->Amout_3_Star_Shared_Double;
        $amount_3_star_single = $price_option->Amount_3_Star_Single;
        
        $amount_4_star_triple = $price_option->Amount_4_Star_Triple;
        $amount_4_star_shared_double = $price_option->Amout_4_Star_Shared_Double;
        $amount_4_star_single = $price_option->Amount_4_Star_Single;
        
        $amount_5_star_triple = $price_option->Amount_5_Star_Triple;
        $amount_5_star_shared_double = $price_option->Amout_5_Star_Shared_Double;
        $amount_5_star_single = $price_option->Amount_5_Star_Single;
        
        $price_query = <<<EOX
INSERT INTO deal_option (title, description, price, currency_id, deal_id, nb_persons, season, offer_type, group_size, accomodation_type) 
VALUES ()
EOX;
    }
    foreach ($data3->tourItinerary->item as $value2) {
        $title = $value2->Name;
        $title = str_replace("'", "''", trim($title));
        $description = $value2->Description;
        $description = str_replace("'", "''", trim($description));


        $Query2 = "INSERT INTO `deal_detail`(`deal_id`,`title`,`description`) VALUES ('$inserted_deal_id','$title','$description')";

        if (mysqli_query($conn, $Query2)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $Query2 . "<br>" . mysqli_error($conn);
        }

        //die();				
    }
//Insert into deal_image
    $i = 1;
    foreach ($data3->tourGalleryItems->item as $value3) {
        echo $image_title = $value3->Name;
        echo "</br>";
        echo $image_name = $value3->Image;
        echo "</br>";
        $img_link = "http://www.travelshoptours.com/assets/public/files/" . $image_name;

//Rename Image
        $original_filename = $image_name;
        $extension = explode(".", $image_name);
        $new_name = microtime();
        $new_name = str_replace('0.', '', $new_name);
        $new_name = str_replace(' ', '', $new_name);

        /* change file name */
        $new_full_name = "deal" . '_' . $new_name . '.' . end($extension);



        $img = file_get_contents($img_link);
        $result_image = './media/' . $new_full_name;
        file_put_contents($result_image, $img);

        //die();				
        $Query3 = "INSERT INTO `deal_image`(`deal_id`,`path`,`image_order`) VALUES ('$inserted_deal_id','$new_full_name','$i')";

        if (mysqli_query($conn, $Query3)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $Query3 . "<br>" . mysqli_error($conn);
        }

        $i++;
    }
    die();
}
?>