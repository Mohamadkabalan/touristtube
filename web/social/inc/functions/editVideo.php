<?php
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//function GetVideoDuration($videoConverter, $videoFile){
//        ob_start();
//        //getting video duration
//        system("$videoConverter -i $videoFile 2>&1");
//        $originalSize = ob_get_contents();
//        preg_match('/Duration: ((\d+):(\d+):(\d+))/s', $originalSize, $duration);
//        $totalDuration = ( $duration[2] * 3600 ) + ( $duration[3] * 60 ) + $duration[4];
//        ob_end_clean();
//        return $totalDuration;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>
function deleteFrames($InterDelArray, $path_parts,$id){
    //print_r($InterDelArray);echo '<br>';print_r($path_parts);echo '<br>';echo $id;exit();
    foreach($InterDelArray as $Group){
        unlink( $path_parts['dirname'].'/thumb_'.$id.'/out'.$Group.'.jpg');
        unlink( $path_parts['dirname'].'/thumb_'.$id.'/frame'.$Group.'.jpg');
    }
}
function exportVideo($Start, $Duration, $videoName, $path_parts, $videoConverter){
        $videoFile = $path_parts['dirname'].'/'.$path_parts['basename'];
        ob_start();
        system("$videoConverter -i $videoFile -ss $Start -t $Duration $videoName 2>&1");
        ob_end_clean();
        //exit( var_dump($Duration). var_dump($videoName). var_dump($path_parts). var_dump($videoConverter) . var_dump($videoFile). "$videoConverter -i $videoFile -ss $Start -t $Duration $videoName 2>&1");
        return $videoName;
}
function CombineVideosPost($path_parts, $videoList, $videoConverter, $id,$postInfo){
    $ext0 = ShowFileExtension($postInfo['media_file']);
    $ext = '.'.$ext0;
    $basename = basename($postInfo['media_file'],$ext);
    $title = $basename;
    $finalvideo = $path_parts['dirname'].'/'.$title;
    ob_start();
    unlink($path_parts['dirname'].'/'.$path_parts['basename']);
    system("$videoConverter -i concat:\"$videoList\" -c copy $finalvideo.mpeg 2>&1");
    ConvertFromMPEGtoMP4($finalvideo, $videoConverter,$ext0);
    deleteVideo($videoList);
    
    $toEdit = array(
        'id'=>$id,
        'published' => '0',
        'user_id'=> intval($postInfo['user_id']), 
        'channel_id'=> intval($postInfo['channel_id'])
    );
    socialPostsEdit($toEdit);
    ob_end_clean();
}
function CombineVideos($path_parts, $videoList, $videoConverter, $id,$VideoInfo){
    $ext0 = ShowFileExtension($VideoInfo['name']);
    $ext = '.'.$ext0;
    $basename = basename($VideoInfo['name'],$ext);
    $title = $basename;
    $finalvideo = $path_parts['dirname'].'/'.$title;
    ob_start();
    unlink($path_parts['dirname'].'/'.$path_parts['basename']);
    system("$videoConverter -i concat:\"$videoList\" -c copy $finalvideo.mpeg 2>&1");
    ConvertFromMPEGtoMP4($finalvideo, $videoConverter,$ext0);
    deleteVideo($videoList);
    
    $toEdit = array(
		'id' => $id,
		'published' => '0',
		'title' => $VideoInfo['title'],
		'name' => $title.$ext
	);
    videoEdit($toEdit);
    ob_end_clean();
}
function ConvertFromMPEGtoMP4($finalvideo, $videoConverter,$ext){
        ob_start();
        system("$videoConverter -i $finalvideo.mpeg $finalvideo.$ext 2>&1");

        deleteVideo($finalvideo.'.mpeg');
        ob_end_clean();
}
function GroupValues($array) { 
   $ret  = array(); 
   $temp = array(); 
   foreach($array as $val) { 
          if(next($array) == ($val + 1)) 
                 $temp[] = $val; 
          else 
                 if(count($temp) > 0) { 
                        $temp[] = $val; 
                        $ret[]  = $temp[0].':'.end($temp); 
                        $temp   = array(); 
                 } 
                 else 
                        $ret[] = $val; 
   } 
   return $ret; 
}
function deleteVideo($videoList){
        $VideoArray = explode("|", $videoList);
        foreach($VideoArray as $VideoLink){
                unlink($VideoLink);
        }
}
function ExportVideosAndReturnVideoList($GroupArray, $path_parts, $videoConverter){
        $i=0;
        $videoList = '';
        foreach($GroupArray as $Group){
                $VideoName = $path_parts['dirname'].'/'.$path_parts['filename']."_".$i.".mpeg";
                $ArrGroup = explode(":", $Group);
                $Start = $ArrGroup[0];
                if( isset( $ArrGroup[1] ) ){
                        $Duration = $ArrGroup[1] - $Start;
                }else{
                        $Duration = 1;
                }

                $videoExported = exportVideo($Start, $Duration, $VideoName, $path_parts, $videoConverter);

                $videoList .= $videoExported."|";
                $i++;
        }
        return substr( $videoList, 0, -1 );
}