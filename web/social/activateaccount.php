<?php
	
	$path = "";

    $bootOptions = array("loadDb" => 1 , 'requireLogin' => 0);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/users.php" );
	include_once ( $path . "inc/functions/videos.php" );

        if(userIsLogged()){
            userLogout();
            header("Refresh:0");
            exit();
        }
            
	if (userIsLogged() && userIsChannel()) {
            $includes = array('css/channel-header.css');
           tt_global_set('includes', $includes);
            include("TopChannel.php");
        } 
		else {
	
		// add 17/8/2015 		
		$includes = array('media'=>'css_media_query/media_style.css');
		tt_global_set('includes', $includes);
        //--------------------------------------------------------------------
		
		 include("TopIndex.php");
       }
 
 ?>
    
        <div id="MiddleInsideNormal">
        
            <div id="InsideNormal">
            	<div id="SignInLayer"></div>
                <div id="RegisterForm" class="activate_acc_mail">
                <table class="poptableclass">
                      <tr>
                        <td class="WhiteTitle" height="44" valign="top"></td>
                      </tr>
                </table>
		<?php 
//  Changed by Anthony Malak 13-05-2015 to PDO database
//  <start>
                        global $dbConn;
                        $params = array();  
                        $user_email = UriGetArg('user');
                        $user_email = ($user_email=="") ? null : $user_email;
                        $found = false;
                        $todisplayerror = _('Unable to find your account,<br>Kindly contact us by email on ').'<a href="mailto:support@touristube.com">support@touristube.com</a>';
                        if(!is_null($user_email)){
                            $SelectUserSQL = checkUserEmailMD5( $user_email );
                            if($SelectUserSQL !== false && $SelectUserSQL){
                                $date_now=date('Y-m-d',strtotime($SelectUserSQL['RegisteredDate']));
                                $date_now_before= date('Y-m-d', time() - 604800);
                                if($date_now>=$date_now_before){
//                                    $UpdateUserSQL = "UPDATE cms_users set published = 1 where id = ".$SelectUserSQL['id'];
                                    $UpdateUserSQL = "UPDATE cms_users set published = 1 where id = :Id";
                                    $params[] = array(  "key" => ":Id",
                                                        "value" =>$SelectUserSQL['id']);
                                    $update = $dbConn->prepare($UpdateUserSQL);
                                    PDO_BIND_PARAM($update,$params);
                                    $res    = $update->execute();
                                    if( !$res )        header("location:".ReturnLink('notfound'));
                                    $found = true;
                                    $todisplayerror = _('Your account is activated,<br>you may login now');
                                }else{                                    
                                    $todisplayerror = _('your activation link<br/>has expired');
                                }
                            }
                        }
                  
//  Changed by Anthony Malak 11-05-2015 to PDO database
//  <end>
                ?>
                    <div class="activation_wrong_credentials"><?php echo $todisplayerror; ?> </div>
                </div>
                
            </div>
            
        </div>
    
<?php include("BottomIndex.php"); ?>
