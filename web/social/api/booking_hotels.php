<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
ini_set('display_errors', 1);
require_once '../Crawlers/simple_html_dom.php';

$url = 'http://www.booking.com/hotel/fr/le-claridge-champs-elysees-by-fraser-serviced-residences.html?dest_id=-1456928';

$html = file_get_html($url);
//print_r($html->plaintext);exit;
//print_r($html->find('#hp_facilities_box', 0)->plaintext);exit;

//print_r($html->find('.facilitiesChecklistSection'));exit;
$facilities_box = $html->find('#hp_facilities_box', 0);
//print_r($facilities_box->plaintext);
$facilities = array();
//print_r($facilities_box->first_child()->plaintext);exit;
//print_r($facilities_box->find('.facilitiesColumn')->plaintext);exit;
foreach($facilities_box->children() as $item){
//    print_r($item->plaintext);
    $type = $item->find('h5', 0);
    if($type){
        print_r($type->plaintext.',');
    }
}
//echo json_encode($facilities);
exit;

foreach($facilities_box->children() as $facility){
//    if($column->class == 'facilitiesColumn'){
//        print_r($column->plaintext);
//    }
    
//    print_r($column->class).'<br>';
//    print_r($facility->)
    $category = $facility->first_child()->plaintext;
    print_r($category);continue;
    $category_facilities = $facility->find('ul', 0);
    $item = array('category'=> $category, 'facilities'=> array());
    foreach($category_facilities->children() as $facility_item){
        $item['facilities'][] = $facility_item->plaintext;
    }
    $facilities[] = $item;
//    $category = $i->find('h5', 0)->plaintext;
//    $category_facilities = $i->find('ul', 0);
//    $item = array('category'=>$category, 'facilities'=>array());
//    foreach($category_facilities as $facility){
//        print_r($facility);
//    }
//    $facilities[] = $item;
}

print_r($facilities);