<?php
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//	if( !isset($_REQUEST['what']) ){
	if( !isset($submit_post_get['what']) ){
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = 'No query criteria';
	}else{
//		$what = $_REQUEST['what'];
//		$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 5;
//		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
		$what = $submit_post_get['what'];
		$limit = isset($submit_post_get['limit']) ? intval($submit_post_get['limit']) : 5;
		$page = isset($submit_post_get['page']) ? intval($submit_post_get['page']) : 0;
		switch($what){
			case 'videos':
				$videos = getRandomVideos($limit,$page);
				$ret_arr['status'] = 'ok';
				$i = 0;
				while($i < count($videos)){
					$video = array();
					$video['id'] = $videos[$i]['id'];
					$video['title'] = $videos[$i]['title'];
                                        $description_db = htmlEntityDecode($videos[$i]['description']);
					$video['description'] = $description_db;
					$video['type'] = 'video';
					$video['url'] = 'https://www.touristtube.com/'. ReturnVideoUriHashed($videos[$i]);
					$video['rating'] = $videos[$i]['rating'];
					$video['rating'] = rand(0,5);

					$ret_arr['results'][] = $video;
					$i++;
				}
				break;
			case 'trends':
				$trends = queryGetTrends($limit,$page);
				$ret_arr['status'] = 'ok';
				$ret_arr['results'] = array();
				foreach ($trends as $trend) {
					$ret_arr['results'][] = $trend['name'];
				}
				break;
			default:
				$ret_arr['status'] = 'error';
				$ret_arr['error_msg'] = 'Invalid query criteria';
				break;
		}

	}
?>
