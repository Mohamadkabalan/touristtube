<?php

    $path = '../';
    $bootOptions = array("loadDb" => 1 , 'requireLogin' => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
		
//    if(!isset($_GET['term']) ) die('');
//    $term = db_sanitize( strtolower($_GET['term']) );
    $term_get = $request->query->get('term','');
    if(!$term_get ) die('');
    $term = strtolower($term_get) ;

    $term = remove_accents($term);
    global $dbConn;
    $params  = array();  
    $params2 = array(); 
    
    $query = "SELECT name , code FROM cms_countries WHERE name LIKE :Term ORDER BY name LIMIT 4";
    $params[] = array(  "key" => ":Term", "value" =>$term."%");
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret = array();
    $i = 0;
    $row = $select->fetchAll(); 
    foreach($row as $row_item){
        $ret[$i]['code'] = $row_item['code'];
        $ret[$i]['state_code'] = 0;
        $ret[$i]['is_country'] = 1;	
        $ret[$i]['value'] = '<span class="City_name_search">'.$row_item['name'].'</span>';
        $ret[$i]['value_display'] = $row_item['name'];	
        $i++;
    }
    $co_num =  db_num_rows($res);
    $ci_limit = 8 + (8-$co_num);
    
    $query = "SELECT WC.id  , WC.name  , WC.country_code, WC.state_code , ST.state_name , CO.name as cname FROM webgeocities WC LEFT JOIN states as ST ON ST.state_code = WC.state_code AND ST.country_code = WC.country_code LEFT JOIN cms_countries as CO ON CO.code = WC.country_code WHERE WC.order_display > 0 AND WC.name LIKE :Term GROUP BY country_code,state_code ORDER BY order_display DESC LIMIT 0,$ci_limit";

    $params2[] = array(  "key" => ":Term", "value" =>$term."%");
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params2);
    $res    = $select->execute();
    $row = $select->fetchAll();
	
    foreach($row as $row_item){	
        $ret[$i]['id'] = $row_item['id'];
        $ret[$i]['code'] = $row_item['country_code'];
        $ret[$i]['state_code'] = $row_item['state_code'];
        $ret[$i]['is_country'] = 0;	
        $ret[$i]['value'] =  '<span class="City_name_search">'.$row_item['name'] .'</span>, <span class="Gray_name_search">'.$row_item['state_name'] .'</span>, <span class="Gray_name_search">'. $row_item['cname'].'</span>';
        $ret[$i]['value_display'] =  $row_item['name'];
        $i++;
    }
    echo json_encode($ret);