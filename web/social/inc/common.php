<?php

/**
 * the currently running page 
 */
//$php_page = basename($_SERVER['PHP_SELF']); removed to bootstrap 


function apiTranslate( $slang , $str ){
    setLangGetText($slang);
    return _($str);
}

function setLangGetText( $slang , $listLangs = false  ){
    global $CONFIG;
//    global $ix;
//    $ix += 1;
    $lang_def=array(
        'fr'=>'fr_FR.utf8',
        'en'=>'en_US.utf8',
        'in'=>'hi_IN.utf8',
        'zh'=>'zh_CN.utf8',
        'es'=>'es_ES.utf8',
        'pt'=>'pt_PT.utf8',
        'it'=>'it_IT.utf8',
        'de'=>'de_DE.utf8',
        'tl'=>'tl_PH.utf8'
    );
    $lang_folder=array(
        'fr'=>'fr_FR',
        'en'=>'en_US',
        'in'=>'hi_IN',
        'zh'=>'zh_CN',
        'es'=>'es_ES',
        'pt'=>'pt_PT',
        'it'=>'it_IT',
        'de'=>'de_DE',
        'tl'=>'tl_PH'
    ); 
    if(!array_key_exists($slang, $lang_folder)){
        $slang = 'en';
    }
    if($listLangs){
        return in_array($slang,array_keys ( $lang_def ));
    }
    $language = $lang_def[$slang];


    putenv('LANG=' . $language);
    setlocale(LC_ALL, $lang_def[$slang]);

    $filename = $CONFIG ['server']['root']."i18n/{$lang_folder[$slang]}/LC_MESSAGES/tt_1.mo";
    $mtime = filemtime($filename);
    $filename_new =  $CONFIG ['server']['root']."i18n/{$lang_folder[$slang]}/LC_MESSAGES/tt_1_{$mtime}.mo"; 

    if (!file_exists($filename_new)) copy($filename,$filename_new);

    bindtextdomain( "tt_1_{$mtime}" , $CONFIG ['server']['root'].'i18n');
    //bindtextdomain( "tt_1_{$mtime}" , 'i18n');
    textdomain("tt_1_{$mtime}");

    $exp = explode('.',$language);
    $js_local = $exp[0];
    return $js_local;        
}




/**
 * for long running prcosses 
 */
$_tt_chat_conn = null;
function db_reconnect(){
    global $CONFIG;
    global $_tt_chat_conn;

  //$last_run = cacheIsSet('last.run') ? cacheGet('last.run') : 0;
  //  $need_to_reconnect = (time() - $last_run > 3600) ? true : false;
  //  if( $need_to_reconnect || is_null($_tt_chat_conn) ){
        //close the old one if still active
  //      if( !is_null($_tt_chat_conn) ) @db_close($_tt_chat_conn);

        $dbConfig = $CONFIG[ 'db' ];
        try {   
            $connection = 'mysql:host='.$dbConfig[ 'host' ].';dbname='.$dbConfig[ 'name' ];
            //$connection = 'mysql:host='.$dbConfig[ 'host' ].';port='.$dbConfig[ 'port' ].';dbname='.$dbConfig[ 'name' ];
            $username   = $dbConfig[ 'user' ];
            $password   = $dbConfig[ 'pwd' ];
            $conn = new PDO($connection, $username, $password);
        } catch (PDOException $e) {
            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
            return false;
        }
        return true;
//    }else{
//        return true;
//    }
}

function mime_by_extension($filename){
	$mime_types = array(

		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv',

		// images
		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp',
		'ico' => 'image/vnd.microsoft.icon',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',

		// archives
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',

		// audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',

		// adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',

		// ms office
		'doc' => 'application/msword',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'ppt' => 'application/vnd.ms-powerpoint',

		// open office
		'odt' => 'application/vnd.oasis.opendocument.text',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		
		//mts
		//'video/avchd-stream', 
		'm2ts' => 'video/m2ts',
		'mp2t' => 'video/mp2t',
		'mts' => 'video/vnd.dlna.mpeg-tts'
	);

	$ext = strtolower(array_pop(explode('.',$filename)));
	if (array_key_exists($ext, $mime_types)) {
		return $mime_types[$ext];
	}
	elseif (function_exists('finfo_open')) {
		$finfo = finfo_open(FILEINFO_MIME);
		$mimetype = finfo_file($finfo, $filename);
		finfo_close($finfo);
		return $mimetype;
	}
	else {
		return 'application/octet-stream';
	}
}

if(!function_exists('mime_content_type')) {

    function mime_content_type($filename) {
		return mime_by_extension($filename);
    }
}


function getUploadDirTree ( $uploadRootDir ){
	
	$uploadDir = $uploadRootDir . date( "Y" ) .'/'. date("W");
	
	if ( !file_exists( $uploadDir ) ){
		mkdir( $uploadDir , 0777 , TRUE);
	}
	
	return $uploadDir . "/" ;
	
}