<?php

	$path = "";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => 1);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

	include_once ( $path . "inc/functions/users.php" );
    include_once ( $path . "inc/functions/videos.php" );
	
	$user_id = userGetID();

//	$file = $_POST['fname'];
//	$dir = $_POST['dir'];
	$file = $request->request->get('fname', '');
	$dir = $request->request->get('dir', '');
	
	$total_path = $CONFIG ['video'] ['uploadPath'] . $dir . $file;

	if ((strstr($dir, '..') != null) || ($dir[0] == '/' )) {
		die('error');
	}

	$total_path = $CONFIG ['video'] ['uploadPath'] . $dir . $file;

	$id = videoDeleteFind($dir,$file,$user_id);
        
	if($id != false){
		videoDelete($id);
		@unlink($total_path);
	}else if(videoTemporaryOwner($user_id,$file)){            
		if( videoTemporaryDelete($user_id, $file) ){
            $dir1 = new DirectoryIterator(dirname($total_path));
            foreach ($dir1 as $fileinfo) {
                if (!$fileinfo->isDot()) {
                    $file_name = $fileinfo->getFilename();
                    $split = explode('.',basename($file));
                    array_pop($split);
                    $basename = implode($split,'.');
                    if(strpos($file_name,$basename) !== false){
                        //echo $CONFIG ['video'] ['uploadPath'] . $dir.$fileinfo->getFilename();
                        @unlink($CONFIG ['video'] ['uploadPath'] . $dir.$file_name);
                    }
                }
            }
		}
	}
	
?>