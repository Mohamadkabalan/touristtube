<?php
//set_time_limit(0);
$path = "../";

$bootOptions = array("loadDb" => 1, "requireLogin" => 0, "loadLocation" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/videos.php" );

$today = date('Y-m-d H:i:s');

$file = "test.txt";

$myfile = fopen($file, "r") or die("Unable to open file!");
$current_date = trim(fread($myfile,filesize($file)));
fclose($myfile);

$myfile2 = fopen($file, "w") or die("Unable to open file!");
$txt = "".$today ;
fwrite($myfile2, $txt);
fclose($myfile2);



global $dbConn;
$params   = array(); 
$params2  = array(); 

$select_data_query ="select     
                        coalesce(concat('|', 
                               group_concat( distinct(id) SEPARATOR '|'), 
                               '|', 
                               replace( (SELECT users 
                                         FROM   cms_users_privacy_extand 
                                         WHERE  entity_id       = :Main_id 
                                         AND    entity_type     = :Media_type ), ',','|') 
                               , '|') , '')
                    from (
                            SELECT :Owner_id AS id

                                UNION

                            SELECT (CASE WHEN 
                                        (SELECT count(id) 
                                         FROM   cms_users_privacy_extand 
                                         WHERE  entity_id               = :Main_id 
                                         AND    entity_type             = :Media_type 
                                         AND    (kind_type LIKE '%1%'   OR kind_type LIKE '%3%')) > 0 
                                    THEN receipient_id 
                                    ELSE 0 end) as id 
                            FROM    cms_friends 
                            WHERE   published       = 1
                            AND     status          = 1 
                            AND     profile_blocked = 0 
                            AND     requester_id    = :Owner_id

                                UNION

                            SELECT (CASE WHEN 
                                        (SELECT count(id) 
                                         FROM   cms_users_privacy_extand 
                                         WHERE  entity_id       = :Main_id 
                                         AND    entity_type     = :Media_type 
                                         AND    kind_type       LIKE '%3%') > 0 
                                    THEN receipient_id 
                                    ELSE 0 end) as id 
                            FROM    cms_friends 
                            WHERE   published       = 1
                            AND     status          = 1 
                            AND     profile_blocked = 0 
                            AND     requester_id    IN (SELECT receipient_id as id 
                                                        FROM   cms_friends 
                                                        WHERE  status           = 1 
                                                        AND    profile_blocked  = 0 
                                                        AND requester_id        = :Owner_id)

                            UNION

                            SELECT (CASE WHEN 
                                        (SELECT count(id) 
                                         FROM   cms_users_privacy_extand 
                                         WHERE  entity_id       = :Main_id 
                                         AND    entity_type     = :Media_type  
                                         AND    kind_type       LIKE '%5%') > 0 
                                    THEN subscriber_id 
                                    ELSE 0 end) as id  
                            FROM    cms_subscriptions 
                            WHERE   published   = 1
                            AND     user_id     = :Owner_id 

                          ) as temp
                    where id <> 0";



$query = "SELECT requester_id,receipient_id FROM cms_friends WHERE published = 1 AND last_modified > :Current_date";

$params[] = array(  "key" => ":Current_date",
                    "value" =>$current_date);
$select = $dbConn->prepare($query);
PDO_BIND_PARAM($select,$params);
$res    = $select->execute();
$ret    = $select->rowCount();
$row    = $select->fetchAll(PDO::FETCH_ASSOC);

if($res || $ret != 0){
    foreach ($row as $key)
    {
        $requester_id  = $key['requester_id'];
        $receipient_id = $key['receipient_id'];
        
        $select_query_frnd = "SELECT U.id FROM cms_friends AS F INNER JOIN cms_users AS U WHERE (F.published=1 AND U.id=F.receipient_id AND F.requester_id= :User_id2 AND F.status=" . FRND_STAT_ACPT . " AND F.blocked=0 AND F.profile_blocked=0) AND U.published=1 ORDER BY request_ts DESC";
        $params9 = array();
        $params9[] = array(  "key" => ":User_id2",
                            "value" =>$requester_id);
        $select9 = $dbConn->prepare($select_query_frnd);
        PDO_BIND_PARAM($select9,$params9);
        $res9    = $select9->execute();
        $ret9    = $select9->rowCount();
        $datas   = $select9->fetchAll(PDO::FETCH_ASSOC);
        
        
        $params10 = array();
        $params10[] = array(  "key" => ":User_id2",
                              "value" =>$receipient_id);
        $select10 = $dbConn->prepare($select_query_frnd);
        PDO_BIND_PARAM($select10,$params10);
        $res10    = $select10->execute();
        $ret10    = $select10->rowCount();
        $receipient_datas   = $select10->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($datas as $data){
            $friends_user_id = $data['id'];
            
            $video_friends_query = "SELECT id FROM `cms_videos` WHERE `userid` = :Friends_user_id";
            $params5 = array();
            $params5[] = array(  "key" => ":Friends_user_id",
                                 "value" =>$friends_user_id);
            $select5 = $dbConn->prepare($video_friends_query);
            PDO_BIND_PARAM($select5,$params5);
            $res5    = $select5->execute();
            $ret5    = $select5->rowCount();
            $row5    = $select5->fetchAll(PDO::FETCH_ASSOC);
            
            if($res5 || $ret5 != 0){
                foreach ($row5 as $key5)
                {
                    $media_id = $key5['id'];
                    $params6 = array();

                    $params6[] = array(  "key" => ":Main_id",
                                         "value" =>$media_id);
                    $params6[] = array(  "key" => ":Owner_id",
                                         "value" =>$friends_user_id);
                    $params6[] = array(  "key" => ":Media_type",
                                         "value" =>1);
                    $update_query = "UPDATE cms_videos  SET allowed_users =($select_data_query) where id = :Main_id";
                    $update = $dbConn->prepare($update_query);
                    PDO_BIND_PARAM($update,$params4);
                    $res6    = $update->execute();
                    
                }
            }
        }
        
        foreach($receipient_datas as $receipient_data){
            $receipient_user_id = $receipient_data['id'];
            
            $video_receipient_query = "SELECT id FROM `cms_videos` WHERE `userid` = :Receipient_user_id";
            $params7 = array();
            $params7[] = array(  "key" => ":Receipient_user_id",
                                 "value" =>$receipient_user_id);
            $select7 = $dbConn->prepare($video_receipient_query);
            PDO_BIND_PARAM($select7,$params7);
            $res7    = $select7->execute();
            $ret7    = $select7->rowCount();
            $row7    = $select7->fetchAll(PDO::FETCH_ASSOC);
            if($res7 || $ret7 != 0){
                foreach ($row7 as $key7)
                {
                    $media_id = $key7['id'];
                    $params8 = array();

                    $params8[] = array(  "key" => ":Main_id",
                                         "value" =>$media_id);
                    $params8[] = array(  "key" => ":Owner_id",
                                         "value" =>$friends_user_id);
                    $params8[] = array(  "key" => ":Media_type",
                                         "value" =>1);

                    $update_query = "UPDATE cms_videos  SET allowed_users =($select_data_query) where id = :Main_id";
                    $update = $dbConn->prepare($update_query);
                    PDO_BIND_PARAM($update,$params8);
                    $res8    = $update->execute();

                }
            }
        }
    }
}
    
$query2 = "SELECT user_id FROM cms_subscriptions WHERE published = 1 AND last_modified > :Current_date";

$params2[] = array(  "key" => ":Current_date",
                     "value" =>$current_date);
$select2 = $dbConn->prepare($query2);
PDO_BIND_PARAM($select2,$params2);
$res2    = $select2->execute();
$ret2    = $select->rowCount();
$row2    = $select2->fetchAll(PDO::FETCH_ASSOC);

if($res2 || $ret2 != 0){
    foreach ($row2 as $key2)
    {
        $user_id = $key2['user_id'];

        $video_follower_query = "SELECT id FROM `cms_videos` WHERE `userid` = :User_id";
        $params3 = array();
        $params3[] = array(  "key" => ":User_id",
                             "value" =>$user_id);
        $select3 = $dbConn->prepare($video_follower_query);
        PDO_BIND_PARAM($select3,$params3);
        $res3    = $select3->execute();
        $ret3    = $select->rowCount();
        $row3    = $select3->fetchAll(PDO::FETCH_ASSOC);
        if($res3 || $ret3 != 0){
            foreach ($row3 as $key3)
            {
                $media_id = $key3['id'];
                $params4 = array();
                
                $params4[] = array(  "key" => ":Main_id",
                                     "value" =>$media_id);
                $params4[] = array(  "key" => ":Owner_id",
                                     "value" =>$user_id);
                $params4[] = array(  "key" => ":Media_type",
                                     "value" =>1);
                
                $update_query = "UPDATE cms_videos  SET allowed_users =($select_data_query) where id = :Main_id";
                $update = $dbConn->prepare($update_query);
                PDO_BIND_PARAM($update,$params4);
                $res4    = $update->execute();
                
            }
        }
    }
}
echo 'done';