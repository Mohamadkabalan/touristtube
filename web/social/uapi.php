<?php
    $path="";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/users.php" );
    include_once ( $path . "inc/functions/videos.php" );
    include_once ( $path . "inc/functions/bag.php" );
    include_once ( $path . "inc/functions/discover.php" );
    $sid_post = $request->request->get('description', '');

    $path = "";

     $request->query->set('lang', 'en');
	
	/**
	* converts php hash to xml
	* @param array $arr the input array
	* @return string the array in xml format
	*/
	function json_to_xml($node_name, $arr,$depth = 0) {
		$str = "";
		if(!is_array($arr)) return '';

		$pre = str_repeat("\t", $depth);
		
		$str .= $pre . '<' . $node_name . ">\n";
		foreach($arr as $node => $val){
			$t_node = $node;
			
			if( is_int($t_node) ) $t_node = substr($node_name, 0, strlen($node_name) - 1 );
					
			if(is_array($val)){
				$str .= json_to_xml($t_node, $val, $depth + 1);
			}else{
				$str .= $pre . "\t<$t_node>" . $val . "</$t_node>\n";
			}
		}
		$str .= $pre . '</' . $node_name . ">\n";
		return $str;
	}
	
	///////////////////////////////////////////////
	
    $user_id = userIsLogged() ? userGetID() : null;
	
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	
//	$operation = isset($_REQUEST['operation']) ? $_REQUEST['operation'] : UriGetArg(null);
//	$return = isset($_REQUEST['return']) ? $_REQUEST['return'] : 'json';
	$operation = isset($submit_post_get['operation']) ? $submit_post_get['operation'] : UriGetArg(null);
	$return = isset($submit_post_get['return']) ? $submit_post_get['return'] : 'json';
	
	$operation = trim($operation, ' /');
	$operation = trim($operation, '?');
	
	//should never happen
	if(strstr($operation, '..')) die('');
	
	$ret_arr = array();
	
	//here we can access the following global variables
	//$user_id, $path, $CONFIG
	//all output should be inserted into $ret_arr array
	if(file_exists('uapi_inc/' . $operation . '.php')){
		include('uapi_inc/' . $operation . '.php');
	}else{
		$ret_arr['status'] = 'error';
		$ret_arr['error_msg'] = _('Invalid Operation').' '.$operation.'';
	}
	
	//return
	if($return == 'xml'){
		header("Content-type: application/xml");
		//echo "<response>\n" .  . "</response>";
		echo json_to_xml('response',$ret_arr);
	}else{
		echo json_encode($ret_arr);
	}
?>