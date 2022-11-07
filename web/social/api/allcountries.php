<?php
/*! \file
 * 
 * \brief this api gets all countries name and code.
 * 
 * 
 * @return <b>res</b> JSON list with the following keys:
 * @return <pre> 
 * @return       <b>code</b> country code
 * @return       <b>name</b> country name
 * @return </pre>
 * @author Charbel khadra <ck@touristtube.com>

 * 
 *  */
	header('Content-type: application/json');
	include("heart.php");
	global $dbConn;
	/*echo '<?xml version="1.0" encoding="utf-8"?>';*/
//	
//	if (file_exists("allcountries.txt"))
//	{
//		if (filemtime("allcountries.txt")<strtotime("1 day ago"))
//		{
//			$res =  getAllCountries();
//			file_put_contents("allcountries.txt",$res);
//		}else
//		{
//			$res =  file_get_contents("allcountries.txt");
//		}
//	}else
//	{
//		$res =  getAllCountries();
//		file_put_contents("allcountries.txt",$res);
//	}
//        $res =  getAllCountries();
//	$res =  file_get_contents("allcountries.txt");
        $sql = "select * from `cms_countries` order by name"; // where UCASE(`code`) in (select UCASE(`country_code`) from `webgeocities`)";
//        $query = db_query($sql);
	$select = $dbConn->prepare($sql);
	$query    = $select->execute();
        $res = array();
	$data = $select->fetchAll();
        foreach($data as $data_item){
            $res[] = array('code' => $data_item['code'], 'name' => $data_item['name']);
        }
        echo json_encode($res);