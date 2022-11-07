<?php

$include_dir = dirname(dirname(dirname(__DIR__))).'/inc';

include $include_dir.'/config.php';


class elasticSearchClient {
    public function elasticSearchClientConnection(){ 
        global $CONFIG;
        $conn = mysqli_connect($CONFIG['db']['host'], $CONFIG['db']['user'], $CONFIG['db']['pwd'], $CONFIG['db']['name']);
        $conn->set_charset("utf8");
        
        return $conn; 
    }
    
    public function insertBulk($data,$type,$index) {
        global $CONFIG;
        
        $url = $CONFIG['elastic']['clientIp']."/".$index."/".$type."/_bulk";
        
        $curl = curl_init($url);

        curl_setopt_array($curl, array(
        CURLOPT_PORT => $CONFIG['elastic']['clientPORT'],
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30, 
		CURLOPT_FOLLOWLOCATION => true, 
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data));
        
        $content = curl_exec($curl);
        
		print_r("\n=============================================================\n");
		print_r($content);
		print_r("\n=============================================================\n");
		
		if (!curl_errno($curl)) {
			$info = curl_getinfo($curl);
			print_r($info);
		}
		
		curl_close($curl);
		
		print_r("\n=============================================================\n");

        return $content;
    }
    
    public function elasticSearchCreateTagsFromName($rowName) { 
        $rowTags = array();
        $tagsLen = round(strlen($rowName)/2) + 1;
        for($j = 3 ; $j < $tagsLen; $j++)
        {
            $rowTags[] = substr($rowName, 0, $j);
        }
        
        return $rowTags; 
    }

}
?>