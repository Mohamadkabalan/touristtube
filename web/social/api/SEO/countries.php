<?php
set_time_limit(0);
$expath = "../";	
	include("../heart.php");
	global $myConn;
	$res = "";
	global $dbConn; 
	$sql = "select * from `cms_countries`";// where UCASE(`code`) in (select UCASE(`country_code`) from `webgeocities`)";	
	//$sql = "select * from `cms_countries` where UCASE(`code`) in (select UCASE(`country_code`) from `cms_mobile_countryXY`)";	
//	$query = db_query($sql);
	$select = $dbConn->prepare($sql);
	$query    = $select->execute();
	
	
	/*$j = json_decode('{"users":34000,"bid_estimations":[{"unsupported":false,"location":3,"cpc_min":128.7,"cpc_median":238,"cpc_max":766,"cpm_min":0,"cpm_median":3,"cpm_max":138}],"estimate_ready":true,"imp_estimates":[],"data":{"users":34000,"bid_estimations":[{"unsupported":false,"location":3,"cpc_min":128.7,"cpc_median":238,"cpc_max":766,"cpm_min":0,"cpm_median":3,"cpm_max":138}],"estimate_ready":true,"imp_estimates":[]}}',true);
	
	echo $j['users'];
	echo $j['bid_estimations'][0]['cpc_min'];
	echo $j['bid_estimations'][0]['cpc_median'];
	echo $j['bid_estimations'][0]['cpc_max'];
	echo $j['bid_estimations'][0]['cpm_min'];
	echo $j['bid_estimations'][0]['cpm_median'];
	echo $j['bid_estimations'][0]['cpm_max'];
	
	print_r($j);
	*/
	echo '<table>';
	
	$i=0;
	while (($data = db_fetch_array($query)) && ($i < 9993))
	{
		$i++;
		//all languages - //$link =  'https://graph.facebook.com/act_23287714/reachestimate?endpoint=%2Fact_23287714%2Freachestimate&locale=en_US&accountId=23287714&targeting_spec=%7B%22genders%22%3A%5B%5D%2C%22age_max%22%3A65%2C%22age_min%22%3A13%2C%22broad_age%22%3Atrue%2C%22regions%22%3A%5B%5D%2C%22countries%22%3A%5B%22'.$data['code'].'%22%5D%2C%22cities%22%3A%5B%5D%2C%22zips%22%3A%5B%5D%2C%22radius%22%3A0%2C%22keywords%22%3A%5B%5D%2C%22connections%22%3A%5B%5D%2C%22excluded_connections%22%3A%5B%5D%2C%22friends_of_connections%22%3A%5B%5D%2C%22relationship_statuses%22%3Anull%2C%22interested_in%22%3A%5B%5D%2C%22college_networks%22%3A%5B%5D%2C%22college_majors%22%3A%5B%5D%2C%22college_years%22%3A%5B%5D%2C%22education_statuses%22%3A%5B0%5D%2C%22locales%22%3A%5B%5D%2C%22work_networks%22%3A%5B%5D%2C%22user_adclusters%22%3A%5B%5D%2C%22user_os%22%3A%5B%5D%2C%22user_device%22%3A%5B%5D%2C%22wireless_carrier%22%3A%5B%5D%7D&currency=USD&method=get&access_token=CAACZBzNhafycBAIDkBGhDqFEk1BfDsOU5QsQpCl4xHhrTRoaEL0JZCtesav7TyWD2d6M5tAhF8Aq0RnjF16dnLgcldFKLOXYU7W8noUA1SbZCUCY5iM41sjcWMMFwquIVrNGqUTdm3zLpVAveUYYvzQzYuykLoZD&pretty=0&callback=__globalCallbacks.fe781bde8';
		
		//english
		$link = 'https://graph.facebook.com/act_23287714/reachestimate?endpoint=%2Fact_23287714%2Freachestimate&locale=en_US&accountId=23287714&targeting_spec=%7B%22genders%22%3A%5B%5D%2C%22age_max%22%3A65%2C%22age_min%22%3A13%2C%22broad_age%22%3Atrue%2C%22regions%22%3A%5B%5D%2C%22countries%22%3A%5B%22'.$data['code'].'%22%5D%2C%22cities%22%3A%5B%5D%2C%22zips%22%3A%5B%5D%2C%22radius%22%3A0%2C%22keywords%22%3A%5B%5D%2C%22connections%22%3A%5B%5D%2C%22excluded_connections%22%3A%5B%5D%2C%22friends_of_connections%22%3A%5B%5D%2C%22relationship_statuses%22%3Anull%2C%22interested_in%22%3A%5B%5D%2C%22college_networks%22%3A%5B%5D%2C%22college_majors%22%3A%5B%5D%2C%22college_years%22%3A%5B%5D%2C%22education_statuses%22%3A%5B0%5D%2C%22locales%22%3A%5B%221001%22%5D%2C%22work_networks%22%3A%5B%5D%2C%22user_adclusters%22%3A%5B%5D%2C%22user_os%22%3A%5B%5D%2C%22user_device%22%3A%5B%5D%2C%22wireless_carrier%22%3A%5B%5D%7D&currency=USD&method=get&access_token=CAACZBzNhafycBAGNWvEAA2w89ULyZCB9oA9HRDaqC2ZCjO2AAYCYVhp1bPOwXyJo89N3YzCS6mo7voHe438ZBQl0URfwwOVIEiN2s062ZCsj83J7PyVLOSKT0qMnZBDEfcTGH6yHx7OPasCPZCCBi9bIHJgo51Ofl0ZD&pretty=0&callback=__globalCallbacks.f225f3e65';
		
		
		$get = file_get_contents($link);
		//echo $get ;
		$res = str_replace("/**/ __globalCallbacks.f225f3e65(","",$get);
		$res = str_replace(");","",$res);
		
		$j = json_decode($res,true);
		echo '<tr>';

		echo '<td>'.$data['name']."</td>";
		echo '<td>'.$j['users']."</td>";
		echo '<td>'.$j['bid_estimations'][0]['cpc_min']."</td>";
		echo '<td>'.$j['bid_estimations'][0]['cpc_median']."</td>";
		echo '<td>'.$j['bid_estimations'][0]['cpc_max']."</td>";
		echo '<td>'.$j['bid_estimations'][0]['cpm_min']."</td>";
		echo '<td>'.$j['bid_estimations'][0]['cpm_median']."</td>";
		echo '<td>'.$j['bid_estimations'][0]['cpm_max']."</td>";
		
		echo '</tr>';
		
		flush();
		ob_flush();
		
	}