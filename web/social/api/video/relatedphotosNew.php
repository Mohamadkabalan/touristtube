<?php

$expath = "../";
header("content-type: application/json; charset=utf-8");

require_once($expath . "heart.php");

$res = "please specify a limit and a page";

$limit_get = $request->query->get('limit','');
$page_get = $request->query->get('page','');
$vid_get = $request->query->get('vid','');
//if (isset($_GET['limit']) && isset($_GET['page']) && isset($_GET['vid'])) {
if ($limit_get && $page_get && $vid_get) {
//    if (is_numeric($_GET['limit']) && is_numeric($_GET['page']) && is_numeric($_GET['vid'])) {
    if (is_numeric($limit_get) && is_numeric($page_get) && is_numeric($vid_get)) {
//        $datas = getRelatedVideos($_GET['page'], $_GET['limit'], $_GET['vid']);
        $datas = getRelatedVideos($page_get, $limit_get, $vid_get);
        //shuffle($datas);
        $res = "<photos order='related'>";
        foreach ($datas as $data) {
            $res .= '<photo>';
            $res .= '<id>' . $data['id'] . '</id>';
            $res .= '<title>' . safeXML($data['title']) . '</title>';
            $res .= '<description>' . safeXML($data['description']) . '</description>';

            $userinfo = getUserInfo($data['userid']);
            $res .= '<user>' . $userinfo['YourUserName'] . '</user>';
            $res .= '<n_views>' . $data['nb_views'] . '</n_views>';
            $res .= '<duration>' . $data['duration'] . '</duration>';

            $stats = getVideoStats($data['id']);
            $res .= '<rating>' . $stats['rating'] . '</rating>';
            $res .= '<nb_rating>' . $stats['nb_ratings'] . '</nb_rating>';
            $res .= '<nb_comments>' . $stats['nb_comments'] . '</nb_comments>';

            $res .= '<up_vote>' . $data['up_votes'] . '</up_vote>';
            $res .= '<down_vote>' . $data['down_votes'] . '</down_vote>';
            //$res .= '<rating>'.$data['rating'].'</rating>';				
            //$res .= '<comments>'.$data['comments'].'</comments>';
            $res .= '<fulllink>' . '' . $data['fullpath'] . $data['name'] . '</fulllink>';
            //$res .= '<thumblink>'.''.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'</thumblink>';
            /////$res .= '&lt;item&gt;http://172.16.124.254:8181/touristtube/'.substr(getVideoThumbnail($data['id'],$path.$data['fullpath'],0),strlen($path)).'&lt;&frasl;item&gt;<br>';
            //echo $data['id']." ".$data['fullpath'];
            //var_dump(getVideoThumbnail($data['id'],$data['fullpath'],0));

            $res .= '</photo>';
        }

        $res .= "</photos>";
    }
}


echo $res;

$xml_cnt = str_replace(array("\n", "\r", "\t"), '', $output);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
$xml_cnt = trim(str_replace('"', "'", $xml_cnt));
$simpleXml = simplexml_load_string($xml_cnt);

echo json_encode($simpleXml);    // returns a string wit