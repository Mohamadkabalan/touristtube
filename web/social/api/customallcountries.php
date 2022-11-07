<?php
	header("content-type: application/xml; charset=utf-8");  
	include("heart.php");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	
		global $myConn;
		$res = "";
                global $dbConn;
	
		$sql = "select * from `cms_countries`";// where UCASE(`code`) in (select UCASE(`country_code`) from `webgeocities`)";	
		//$sql = "select * from `cms_countries` where UCASE(`code`) in (select UCASE(`country_code`) from `cms_mobile_countryXY`)";
                $select = $dbConn->prepare($sql);
                $query    = $select->execute();	
//		$query = db_query($sql);
                $data = $select->fetchAll();
//			while ($data = db_fetch_array($query))
                        foreach($data as $data_item){
				

				$res .= '"'.str_replace("&","and",$data_item['name']).'",';
				
										
				 					
			}
		//echo db_error($myConn);
		
			
	echo $res;