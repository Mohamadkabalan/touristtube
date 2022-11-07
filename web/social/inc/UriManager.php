<?php
/**
 * functionality that deals with url parsing and link creation
 * @package uri
 */
/**
 * the global array of url arguments 
 */
$_tt_global_args =array();
/**
 * Decomposes the freindly url to GET superglobal 
 */
function DecomposeUri() {
	global $_tt_global_args;
        global $request;
        
//	if(isset($_SERVER['QUERY_STRING'])) $q = explode('&',$_SERVER['QUERY_STRING'],2);   
         $q = $request->query->get('q') ? $request->query->get('q','')  : '';
//	if( !isset($q[0]) ) return false;
	
//	$q = $q[0];
//	if(!isset($q[0]) || $q[0] != 'q'){
//		return false;
//	}
//	$q = explode('=',$q,2);
//	if( count($q) != 2 ){
//		return false;
//	}
	
//	$q = $q[1];

        $_tt_global_args = explode('/', $q);
	
//	if (count($_tt_global_args) == 0)
//		return false;

//	if (strlen($_tt_global_args[count($_tt_global_args) - 1]) == 0) {
//		unset($_tt_global_args[count($_tt_global_args) - 1]);
//	}

//	if (count($_tt_global_args) == 0)
//		return false;

//	if (count($_tt_global_args) == 1) {
		//$_GET['arg'] = $_tt_global_args[0];
            $request->query->set('arg', $_tt_global_args[0]);
//            return;
//	}
	foreach ($_tt_global_args as $arg => $val) $request->query->set($arg, $val);
        
		//$_GET[$arg] = $val;

        return;
        
}
/**
 * gets current page url
 * return string the page url
 */
function UriCurrentPageURL() {
    global $request;
	 $pageURL = 'http';
        $HTTPS_server = $request->server->get('HTTPS', '');
        $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
        $SERVER_NAME_server = $request->server->get('SERVER_NAME', '');
        $SERVER_PORT_server = $request->server->get('SERVER_PORT', '');
        $REQUEST_URI_server = $request->server->get('REQUEST_URI', '');
//	 if ( (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") || (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") ) {$pageURL .= "s";}
	 if ( (isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https") ) {$pageURL .= "s";}
	 $pageURL .= "://";
//	 if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
//	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
//	 } else {
//	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
//	 }
	 if ($SERVER_PORT_server != "80" && $SERVER_PORT_server != "443") {
	  $pageURL .= $SERVER_NAME_server.":".$SERVER_PORT_server.$REQUEST_URI_server;
	 } else {
	  $pageURL .= $SERVER_NAME_server.$REQUEST_URI_server;
	 }
	 return $pageURL;
}
/**
 * gets current page url
 * return string the page url
 */
function currentServerURL() {
    global $CONFIG;
    global $request;
	 $pageURL = 'http';
        $HTTPS_server = $request->server->get('HTTPS', '');
        $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
        $SERVER_NAME_server = $request->server->get('HTTP_HOST', '');
        $SERVER_PORT_server = $request->server->get('SERVER_PORT', '');
//	 if ( (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") || (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") ) {$pageURL .= "s";}
//	 $pageURL .= "://";
//	 if ( isset($_SERVER['SERVER_PORT'] ) && $_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
//            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	 if ( (isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https") ) {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ( isset($SERVER_PORT_server ) && $SERVER_PORT_server != "80" && $SERVER_PORT_server != "443") {
            $pageURL .= $SERVER_NAME_server.":".$SERVER_PORT_server;
	  //$pageURL .= "89.249.212.8".":".$_SERVER["SERVER_PORT"];
	 } else {
	  //$pageURL .= $_SERVER["SERVER_NAME"];
	  $pageURL .= $CONFIG['server_name'];
	 }
	 return $pageURL;
}
function UriCurrentServerURL()
{
    global $CONFIG;
    global $request;
    $prefix                        = 'http';
    $HTTPS_server                  = $request->server->get('HTTPS', '');
    $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
    $SERVER_NAME_server            = $CONFIG['cookie_path'];//$request->server->get('SERVER_NAME', '');
    $SERVER_PORT_server            = $request->server->get('SERVER_PORT', '');
    if ((isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https")) {
        $prefix .= "s";
    }
    $prefix .= "://";
    if ($SERVER_PORT_server != "80" && $SERVER_PORT_server != "443") {
        $SERVER_NAME_server = $SERVER_NAME_server.":".$SERVER_PORT_server;
    }
    return array($prefix, $SERVER_NAME_server);
}
function getUriPageURLHTTP() {
    global $request;
    $pageURL = 'http';
    $HTTPS_server = $request->server->get('HTTPS', '');
    $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
    if ((isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https")) {
        $pageURL .= "s";
    }
    return $pageURL;
}
/**
 * gets current page url for language
 * return string the page url
 */
function UriCurrentPageURLForLanguage() {
    global $request;
    $prefix = 'http';
    $HTTPS_server = $request->server->get('HTTPS', '');
    $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
    $REQUEST_URI_server = $request->server->get('REQUEST_URI', '');
    $SERVER_PORT_server = $request->server->get('SERVER_PORT', '');
    $SERVER_NAME_server = $request->server->get('HTTP_HOST', '');
//    if ( (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") || (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") ) {$prefix .= "s";}
    if ( (isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https") ) {$prefix .= "s";}
    $prefix .= "://";
    if ($SERVER_PORT_server != "80" && $SERVER_PORT_server != "443") {
        $prefix .= $SERVER_NAME_server.":".$SERVER_PORT_server;
    } else {
        $prefix .= $SERVER_NAME_server;
    }
    $pageURL = $REQUEST_URI_server;
    if (LanguageGet() != '') {
        $linksArray = explode('/'.LanguageGet().'/', $pageURL);
        if (substr($linksArray[0], 0, 1) == '/') {
            $linksArray[0] = substr($linksArray[0], 1,
                strlen($linksArray[0]));
        }
        if (substr($linksArray[0], -1) == '/') {
            $linksArray[0] = substr($linksArray[0], 0,
                strlen($linksArray[0]) - 1);
        }
        if (sizeof($linksArray) > 1) {
            if ($linksArray[0] != '') $linksArray[0] = '/'.$linksArray[0];
            return array($prefix, $linksArray[0], $linksArray[1]);
        }else {
            return array($prefix, '', $linksArray[0]);
        }
    } else {
        if (substr($pageURL, 0, 1) == '/')
                $pageURL = substr($pageURL, 1, strlen($pageURL));
        return array($prefix, '', $pageURL);
    }
}
/**
 * gets the passed uri argument
 * @param integer|string $which the argument to get
 * return null|string the arguments value or null if not found
 */
function UriGetArg($which){
	global $_tt_global_args;
	if(is_null($which)){
		return implode('/',$_tt_global_args);
	}else if( is_int($which) ){
		if( count($_tt_global_args) == 0 ) return null;
		if( $which >= count($_tt_global_args) ) return null;
		else return $_tt_global_args[$which];
	}else{
		$i = 0;
		while($i < count($_tt_global_args) - 1){
			if($_tt_global_args[$i] == $which){
				return $_tt_global_args[$i+1];
			}
			$i++;
		}
		return null;
	}
}

/**
 * checks if the argument exists on the uri
 * @param string $which the argument to get
 * return boolean true|false if found or not
 */
function UriArgIsset($which){
	global $_tt_global_args;
	return in_array($which, $_tt_global_args);
}
function returnHotelDetailedLink($title, $id) {
    $titled = cleanTitleData($title);
    $titled = str_replace('++', '+', $titled);
    $lnk = '/hotel-details-' . $titled . '-' . $id;
    return generateLangURL($lnk);
}
/**
 * edits the uri
 * @param array $args the argument to get
 * return string the new link
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//function UriArgEdit($args,$commit = false){
//	global $_tt_global_args;
//	$_tt_global_args_temp = $_tt_global_args;
//	foreach ($args as $key => $val) {
//		$found = false;
//		$i = 0;
//		while( $i < count($_tt_global_args_temp) - 1 ){
//			if($_tt_global_args_temp[$i] === $key){
//				$found = true;
//				if( !is_null($val) )
//					$_tt_global_args_temp[$i+1] = $val;
//				else{
//					unset($_tt_global_args_temp[$i]);
//					unset($_tt_global_args_temp[$i+1]);
//				}
//				break;
//			}
//			$i++;
//		}
//		if( !$found && ($_tt_global_args_temp[$i] == $key) ){
//			$_tt_global_args_temp[] = $val;
//			$found = true;
//		}
//		if(!$found && ($val !== null) ){
//			$_tt_global_args_temp[] = $key;
//			$_tt_global_args_temp[] = $val;
//		}
//	}
//	$page = str_replace(".php", '', tt_global_get('page') );
//	if( $commit ) $_tt_global_args = $_tt_global_args_temp;
//	return ReturnLink( $page . '/' . implode('/',$_tt_global_args_temp) );
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>

/**
 * Returns an absolute link
 * @param string $link relative link
 * @return string the absolute link 
 */
function ReturnLink($_link, $args = null , $is_media = 0, $page_type = '') {
    global $request;
    $HTTPS_server = $request->server->get('HTTPS', '');
    $HTTP_X_FORWARDED_PROTO_server = $request->server->get('HTTP_X_FORWARDED_PROTO', '');
//	$scfn = dirname($_SERVER['SCRIPT_FILENAME']);
	$scfn = dirname($request->server->get('SCRIPT_FILENAME', ''));
//	$serverRoot = str_replace($_SERVER['DOCUMENT_ROOT'], '', $scfn);
	$serverRoot = str_replace($request->server->get('DOCUMENT_ROOT', ''), '', $scfn);
	//$serverRoot = str_replace('/social', '', $serverRoot);
	if( (strlen($serverRoot) != 0) && ($serverRoot[0] != '/') ) $serverRoot = '/' . $serverRoot;
	
	if(strlen($serverRoot) == 1) $serverRoot = '';	
	//$serverRoot.='/social';
	$link = trim($_link, '/');
        
	$linkFolder1 = explode('/', $link, 2);
	$linkFolder = $linkFolder1[0];
	
	$linkFile = basename($link);
	$linkFile = explode('?', $linkFile, 2);
	$linkFile = $linkFile[0];
	if( ($linkFolder == 'media' || $linkFolder == 'cache') && $is_media!=-1 ){
            $is_media=1;
        }
	if ($args == null) {
		//nothing to do
	} else if (!is_array($args)) {
		$link .= $args;
	} else {
		foreach ($args as $arg => $val) {
			if (($val != '') && ($arg != ''))
				$link .= '/' . $arg . '/' . $val;
		}
	}        
        
	$static = array('assets','js', 'video_chat', 'css', 'media' ,'images' , 'xml' , 'services' , 'fancybox','be','phpDataGrid', 'uapi' ,'pmedia','videochat','css_media_query','jwplayer', 'cache');
	
	if( !$is_media && !in_array($linkFolder, $static) && strstr($link,'.php') != null ){
		if(strstr($link,'?') == null)
			$link .= '?lang=' . LanguageGet();
		else
			$link .= '&lang=' . LanguageGet();
	}
	
	$serverRoot = str_replace('/parts','', $serverRoot);
	$serverRoot = str_replace('/ajax','', $serverRoot);
	
	//remove everything after api
	if(strstr($serverRoot, '/api/') != null ){
		$serverRoot = substr($serverRoot, 0, strpos($serverRoot, '/api/') );
	}
	
	$final_link = $serverRoot . '/' . $link;
	$final_link = str_replace('&','&amp;', $final_link);
	$final_link = str_replace(' ','%20', $final_link);
	
	//TODO: check where this is coming from
	$final_link = str_replace('/./','/', $final_link);
        if( ENVIRONMENT == 'production' ){
            if($linkFolder == 'assets' || $linkFolder == 'js' || $linkFolder == 'images' || $linkFolder == 'css' || $linkFolder == 'css_media_query' || $is_media==1){
                $prefix = ($linkFolder == 'assets' || $linkFolder == 'js' || $linkFolder == 'css' || $linkFolder == 'css_media_query' ) ? 'static' : 'static1';
//                $prefix = 'www';
//                $prefix1 = "http:";
//                if ( (isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https") ) {$prefix1 = "https:";}
//                $final_link = $prefix1.'//'.$prefix.'.touristtube.com'.$final_link;
                          
            }else if($is_media==-1){
                $prefix = 'www';
                $prefix1 = "http:";
//                if ( (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") || (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") ) {$prefix1 = "https:";}
                if ( (isset($HTTPS_server) && $HTTPS_server == "on") || (isset($HTTP_X_FORWARDED_PROTO_server) && $HTTP_X_FORWARDED_PROTO_server == "https") ) {$prefix1 = "https:";}
                $final_link = $prefix1.'//'.$prefix.'.touristtube.com'.$final_link;
            }else{
                $final_link = generateLangURL($final_link,$page_type);
            }
        }else{
            if( !in_array($linkFolder, $static) && $is_media==0){
                $final_link = generateLangURL($final_link,$page_type);
            }
        }
	return $final_link;
}
/**
 * echos the link returned by ReturnLink
 * @param string $link the relative link
 */
function GetLink($link, $args = null) {
	echo ReturnLink($link, $args);
}
