<pre><?php
set_time_limit ( 0 );
ini_set('display_errors',0);
$database_name = "touristtube";


//mysql_connect('localhost','root','mysql_root');
mysql_connect( "MYSQL" , "touristtube" , "sN2HxLDj89Dym9BR" );
mysql_select_db($database_name);


mysql_query("SET NAMES utf8");

/*$string = file_get_contents("factual.txt");
$json_a = json_decode($string, true);
foreach($json_a as $key=>$arr){
    $cuisine_id = $key;
    $labels = $arr['labels'];
    $en = addslashes($labels['en']);
    $de = addslashes($labels['de']);
    $fr = addslashes($labels['fr']);
    $es = addslashes($labels['es']);
    $jp = addslashes($labels['jp']);
    $kr = addslashes($labels['kr']);
    $zh_hant = addslashes($labels['zh_hant']);
    $zh = addslashes($labels['zh']);
    $it = addslashes($labels['it']);
    $pt = addslashes($labels['pt']);
    $parents = implode(',',$arr['parents']);
    $abstract = $arr['abstract'];
    $abstract = ($abstract)?1:0;
    $sql="INSERT INTO `factual_cuisine`(`cuisine_id`, `en`, `de`, `fr`, `es`, `jp`, `kr`, `zh_hant`, `zh`, `it`, `pt`, `parents`, `abstract`) VALUES ($cuisine_id,'$en','$de','$fr','$es','$jp','$kr','$zh_hant','$zh','$it','$pt','$parents',$abstract)";//."; \n";
   //mysql_query($sql);
    $results = mysql_query($sql) or die( mysql_error());
}*/
$sql="SELECT * FROM global_restaurants_new where published=1 limit 0, 100000";

$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
$strg='';
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
    $name = addslashes($r['name']);
    $address = addslashes($r['address']);
    $address_extended = addslashes($r['address_extended']);
    $po_box = addslashes($r['po_box']);
    $locality = addslashes($r['locality']);
    $region = addslashes($r['region']);
    $post_town = addslashes($r['post_town']);
    $admin_region = addslashes($r['admin_region']);
    $country = addslashes($r['country']);
    $tel = addslashes($r['tel']);
    $fax = addslashes($r['fax']);
    $latitude = addslashes($r['latitude']);
    $longitude = addslashes($r['longitude']);
    $neighborhood = addslashes($r['neighborhood']);
    $website = addslashes($r['website']);
    $email = addslashes($r['email']);
    $category_ids = addslashes($r['category_ids']);
    $category_labels = addslashes($r['category_labels']);
    $chain_name = addslashes($r['chain_name']);
    $chain_id = addslashes($r['chain_id']);
    $hours = addslashes($r['hours']);
    $hours_display = addslashes($r['hours_display']);
    $existence = addslashes($r['existence']);
    $postcode = $r['postcode'];
    $factual_id = addslashes($r['factual_id']);
    
    $sql="SELECT * FROM global_restaurants where `factual_id` = '".$factual_id."' and factual_id<>0 and factual_id<>''";         
    $results1 = mysql_query($sql) or die( mysql_error());
    $num =mysql_num_rows($results1);
    if( $num>0 ) {
        $cdata =mysql_fetch_array($results1);
        $sqr= "UPDATE `global_restaurants` SET `name`='".$name."',`address`='".$address."',`address_extended`='".$address_extended."',`po_box`='".$po_box."',`locality`='".$locality."',`region`='".$region."',`post_town`='".$post_town."',`admin_region`='".$admin_region."',`postcode`=".$postcode.",`country`='".$country."',`tel`='".$tel."',`fax`='".$fax."',`latitude`='".$latitude."',`longitude`='".$longitude."',`neighborhood`='".$neighborhood."',`website`='".$website."',`email`='".$email."',`category_ids`='".$category_ids."',`category_labels`='".$category_labels."',`chain_name`='".$chain_name."',`chain_id`='".$chain_id."',`hours`='".$hours."',`hours_display`='".$hours_display."',`existence`='".$existence."', updated=1 WHERE id=".$cdata['id'];
        mysql_query($sqr);   
        $sqr= "UPDATE `global_restaurants_new` SET published=-4 WHERE id=".$r['id'];    
    }else{
        $sqr= "INSERT INTO `global_restaurants`(`factual_id`, `name`, `address`, `address_extended`, `po_box`, `locality`, `region`, `post_town`, `admin_region`, `postcode`, `country`, `tel`, `fax`, `latitude`, `longitude`, `neighborhood`, `website`, `email`, `category_ids`, `category_labels`, `chain_name`, `chain_id`, `hours`, `hours_display`, `existence`) VALUES ('".$factual_id."','".$name."','".$address."','".$address_extended."','".$po_box."','".$locality."','".$region."','".$post_town."','".$admin_region."','".$postcode."','".$country."','".$tel."','".$fax."','".$latitude."','".$longitude."','".$neighborhood."','".$website."','".$email."','".$category_ids."','".$category_labels."','".$chain_name."','".$chain_id."','".$hours."','".$hours_display."','".$existence."')";
        mysql_query($sqr);      
        $sqr= "UPDATE `global_restaurants_new` SET published=-3 WHERE id=".$r['id'];
    } 
    mysql_query($sqr);
}