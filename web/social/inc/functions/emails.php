<?php
/**
 * The email functionality
 * @package emails
 */
/**
 * the email acount login username
 */
define('EMAIL_USER', 'noreply@touristtube.com');
/**
 * the email account login password
 */
define('EMAIL_PASS', 'noreplyMailP@ssw0rd');
//define('EMAIL_HOST','www.touristtube.com');
/**
 * the email server to connect to
 */
define('EMAIL_HOST', '178.33.63.198');
/**
 * the smtp port to connect on
 */
define('EMAIL_PORT', 25);
$pathEm = $CONFIG ['server']['root'] ;
//Twig_Autoloader::register();
$bootOptions = array("loadDb" => 1 , 'requireLogin' => TRUE);
//include_once ( $pathEm . "inc/common.php" );
//include_once ( $pathEm . "inc/bootstrap.php" );
include_once ( $pathEm . "inc/twigFct.php" );
include_once ( $pathEm . "inc/functions/lang.php" );
//used for the twig custom filters like (lang, link)

$lang = LanguageGet();
$loader = new Twig_Loader_Filesystem($pathEm . 'twig_templates/emails/'.$lang.'/'  );

$twig = new Twig_Environment($loader, array(
    'debug' => false,
        ));
$twig->addExtension(new Twig_Extension_twigTT());

/**
 * sends a email regarding a video comment
 * @param integer $to_id the source tuber id
 * @param integer $from_id the destination tuber id
 * @return boolean true|false if success|fail
 */
function emailVideoComment($to_id, $from_id) {
    //file_put_contents('log.txt', "email vid from $from_id to $to_id\r\n", FILE_APPEND);
}

/**
 * sends a email regarding a reply to a comment
 * @param integer $to_id the source tuber id
 * @param integer $from_id the destination tuber id
 * @return boolean true|false if success|fail
 */
function emailCommentReply($to_id, $from_id) {
    //file_put_contents('log.txt', "email rep from $from_id to $to_id\r\n", FILE_APPEND);
}

/**
 * sends a email regarding a subscription
 * @param integer $to_id the source tuber id
 * @param integer $from_id the destination tuber id
 * @return boolean true|false if success|fail
 */
function emailSubscription($to_id, $from_id) {
    //file_put_contents('log.txt', "email sub from $from_id to $to_id\r\n", FILE_APPEND);
}

/**
 * sends a email to a friend
 * @param integer $to_id the source tuber id
 * @param integer $from_id the destination tuber id
 * @return boolean true|false if success|fail
 */
function emailFreind($to_id, $from_id) {
    //file_put_contents('log.txt', "email freind from $from_id to $to_id\r\n", FILE_APPEND);
}

/**
 * sends a email regarding a comment spam
 * @param integer $spam_record_id the spam record id
 * @return boolean true|false if success|fail
 */
function emailSpam($spam_record_id) {
    
}

/**
 * 04_forgot_password_email
 * @param string $to_email the destination email.
 * @param string $owner_name the name of the channel owner.
 * @param string $channel_name the name of the channel.
 * @param string $change_pass_lnk a link to the change-password page.
 * @param string $tellus_lnk a link to the tell-us page.
 * @param string $tt_help_lnk a link to the tt-help page.

 * @param string $delete_date the date of the deletion.
 * @param string $canceldelete_lnk the link to press to cancel the deletion as an ABSOLUTE LINK, IE: the channel page.
 * @param string $unsubscribe_lnk the link to unsubscribe as an absolute link.
 * @return boolean true|false if success|fail.
 */
function emailForgotPassword($to_email, $owner_name, $change_pass_lnk, $tellus_lnk, $tt_help_lnk) {
    global $twig;
    $ttpath = currentServerURL() . '/media/images/';
    $subject = _("TouristTube Forgotten Password");
    
    $data['displayTopHead'] = displayTopHeadNew($ttpath,$owner_name);
    $data['displayfoot'] = displayfoot(array());
    
    $template = $twig->loadTemplate('emailForgotPassword.html.twig'); //specify your template here
    global $CONFIG;
    $data['subdomain_suffix'] = $CONFIG['subdomain_suffix'];

    $data['ttpath'] = $ttpath;
    $data['to_email'] = $to_email;
    $data['owner_name'] = $owner_name;
    $data['change_pass_lnk'] = $change_pass_lnk;
    $data['tellus_lnk'] = $tellus_lnk;
    $data['tt_help_lnk'] = $tt_help_lnk;

    $msg = html_entity_decode($template->render($data));
    return send_email_form($to_email, $msg, $subject, 'TouristTube', 0);
}

function displayPartHead($userName, $partTitle, $first) {
    //$link_path = $CONFIG ['server']['root'].'media/images/emails/';
    $link_path = currentServerURL() . '/media/images/emails/';
    if ($first == '1') {
        return '<tr height="24" bgcolor="#FFFFFF">
                <td height="24" bgcolor="#FFFFFF" width="633">
                </td>
            </tr>

            <tr height="79" bgcolor="#FFFFFF">
                <td height="79" bgcolor="#FFFFFF" width="633">
                    <table width="633" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                        <tr height="17" bgcolor="#FFFFFF">
                            <td colspan="5" height="17" width="540">
                            </td>
                            <td rowspan="4" valign="top" width="58">
                                <img src="' . $link_path . 'ttrightpic.jpg" alt="Tourist Tube picture" border="0" width="58" height="58"/>
                            </td>
                            <td width="35">
                            </td>
                        </tr>
                        <tr height="16" bgcolor="#FFFFFF">
                            <td colspan="2" height="16" width="8">
                            </td>
                            <td colspan="3" width="533" height="16">
                                <font face="Arial, Helvetica, sans-serif" size="3" color="#1F1F1F">Hello ' . $userName . ',
                                </font>
                            </td>
                            <td width="35">
                            </td>
                        </tr>
                        <tr height="17" bgcolor="#FFFFFF">
                            <td colspan="5" height="17" width="540">
                            </td>
                            <td width="35">
                            </td>
                        </tr>
                        <tr height="29" bgcolor="#FFFFFF">
                            <td width="1" height="29">
                            </td>
                            <td colspan="2" width="45" height="29" bgcolor="#000000">
                            </td>
                            <td  width="136" height="29" bgcolor="#000000" valign="middle">
                                <font face="Arial, Helvetica, sans-serif" size="2" color="#FFFFFF">' . $partTitle . '</strong></font>
                            </td>
                            <td width="358" height="29">
                            </td>
                            <td width="35">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>';
    } else {
        return '<tr height="37" bgcolor="#FFFFFF">
            <td height="37" bgcolor="#FFFFFF" width="633"></td>
        </tr>
        <tr>
            <td height="29" width="633">
                <table width="633" border="0" cellspacing="0" cellpadding="0" height="29">
                    <tr height="29" bgcolor="#FFFFFF">
                            <td width="1" height="29"></td>
                            <td width="45" height="29" bgcolor="#000000"></td>
                            <td  width="136" height="29" bgcolor="#000000" valign="middle">
                                <font face="Arial, Helvetica, sans-serif" size="2" color="#FFFFFF">' . $partTitle . '</strong>
                                </font>
                            </td>
                            <td width="414" height="29"></td>
                     </tr>
                </table>
            </td>
        </tr>';
    }
}

function displayPartHeadProfileMentioned($userName) {
    global $twig;
    $link_path = currentServerURL() . '/media/images/emails/';

    $template = $twig->loadTemplate('displayPartHeadProfileMentioned.html.twig'); //specify your template here
    global $CONFIG;
    $data['subdomain_suffix'] = $CONFIG['subdomain_suffix'];

    $data['userName'] = $userName;
    $data['link_path'] = $link_path;
    return $template->render($data);
}
function displayTopHeadNew($ttpath,$owner_name) {
    $return = '
        <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
            <tr height="17" bgcolor="#FFFFFF">
                <td height="16" width="46"></td>
                <td height="17"></td>
                <td width="35"></td>
            </tr>
            <tr height="84" bgcolor="#FFFFFF">
                <td height="" width="46"></td>
                <td valign="top">
                    <img src="'.$ttpath.'emails/log.png" alt="Tourist Tube picture" border="0" width="258" height="84"/>
                </td>
                <td width="35"></td>
            </tr>
            <tr height="15" bgcolor="#FFFFFF">
                <td height="5" width="46"></td>
                <td height="5"></td>
                <td width="35"></td>
            </tr>
            <tr height="16" bgcolor="#FFFFFF">
                <td height="16" width="46"></td>
                <td height="16">
                    <font face="Arial, Helvetica, sans-serif" size="3" color="#1F1F1F">'._('Hello').' '.$owner_name.',</font>
                </td>
                <td width="35"></td>
            </tr>
            <tr height="17" bgcolor="#FFFFFF">
                <td height="16" width="46"></td>
                <td height="17"></td>
                <td width="35"></td>
            </tr>
        </table>';
    return $return;
    
}
function displayTopHead($channel) {
    global $twig;
    $link_path = currentServerURL() . '/media/images/emails/';
    $return = '';
    global $CONFIG;
    $data['subdomain_suffix'] = $CONFIG['subdomain_suffix'];
    if ($channel == '1') {
        $template = $twig->loadTemplate('displayTopHead1.html.twig'); //specify your template here        

        $data['link_path'] = $link_path;

        $return = $template->render($data);
    } else {
        $template = $twig->loadTemplate('displayTopHead2.html.twig'); //specify your template here

        $data['link_path'] = $link_path;

        $return = $template->render($data);
    }
    return $return;
    
}

function displayPartHeadNetwork($userName, $partTitle, $first, $is_channel) {
    global $twig;
    $link_path = currentServerURL() . '/media/images/emails/';
    if ($is_channel == 1) {
        $type_val = _('Channel');
    } else {
        $type_val = _('account');
    }
    global $CONFIG;
    $data['subdomain_suffix'] = $CONFIG['subdomain_suffix'];
    if ($first == '1') {
        $template = $twig->loadTemplate('displayPartHeadNetwork1.html.twig');

        $data['userName'] = $userName;
        $data['partTitle'] = $partTitle;
        $data['link_path'] = $link_path;
        $data['type_val'] = $type_val;

        return $template->render($data);
    } else {
        $template = $twig->loadTemplate('displayPartHeadNetwork2.html.twig');

        $data['partTitle'] = $partTitle;

        return $template->render($data);
    }
}

function displayfootNew($socialArray, $unsubscribe_lnk = "", $not_settings = "", $createchannel_lnk = "", $is_channel = 0) {
    $link = currentServerURL() . '/media/images/emails/';
    global $twig;
    $template = $twig->loadTemplate('displayfootNew.html.twig');
    global $CONFIG;
    $data['subdomain_suffix'] = $CONFIG['subdomain_suffix'];
    $data['link'] = $link;
    return $template->render($data);
}

function displayfoot($socialArray=array(), $unsubscribe_lnk = "", $not_settings = "", $createchannel_lnk = "", $is_channel = 0) {
    global $CONFIG;
    $subdomain_suffix = $CONFIG['subdomain_suffix'];
    $link = currentServerURL() . '/media/images/emails/';
    $return = '';
    if (sizeof($socialArray) > 0) {
        $return .='<tr>
            <td>
                <table width="633" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
						<tr height="11">
							<td width="46" height="11"></td>';
        if ($socialArray['views'] != '') {
            $return .='<td width="63" height="11">
									<font face="Arial, Helvetica, sans-serif" size="2" color="#e7ba20"><strong>' . $socialArray['views'] . '</strong></font>
								</td>';
        }
        $return .='<td width="55" height="11">
								<font face="Arial, Helvetica, sans-serif" size="2" color="#e7ba20"><strong>' . $socialArray['likes'] . '</strong></font>
							</td>
							<td width="83" height="11">
								<font face="Arial, Helvetica, sans-serif" size="2" color="#e7ba20"><strong>' . $socialArray['comments'] . '</strong></font>
							</td>
							<td width="68" height="11">
								<font face="Arial, Helvetica, sans-serif" size="2" color="#e7ba20"><strong>' . $socialArray['shares'] . '</strong></font>
							</td>
							<td  height="11" colspan="2">';
        if ($socialArray['rating'] != '') {
            $return .='<font face="Arial, Helvetica, sans-serif" size="2" color="#e7ba20"><strong>' . $socialArray['rating'] . '</strong></font>';
        }
        $return .='</td>
							<td width="37" height="11"></td>
						</tr>
						<tr height="11">
							<td width="46" height="11"></td>';
        if ($socialArray['views'] != '') {
            $return .='<td width="63" height="11" valign="bottom">
									<font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">views</font>
								</td>';
        }
        $return .='<td width="55" height="11" valign="bottom">
								<font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">likes</font>
							</td>
							<td width="83" height="11" valign="bottom">
								<font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">comments</font>
							</td>
							<td width="68" height="11" valign="bottom">
								<font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">shares</font>
							</td>
							<td  height="11" width="35" valign="bottom">';
        if ($socialArray['rating'] != '') {
            $return .='<font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">rating</font>';
        }
        $return .='</td>
							<td  height="11" valign="middle">';
        if ($socialArray['rateImg'] != '') {
            $return .='<img src="' . $socialArray['rateImg'] . '" width="35" height="7" />';
        }
        $return .='</td>
							<td width="37" height="11"></td>
						</tr>
					</table>
            </td>
        </tr>';
    }
    $return .='
        <tr height="42">
            <td></td>
        </tr>
         <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                    <tr height="12">
                        <td width="46" height="16"></td>
                        <td valign="middle" align="left"><a href="//www'.$subdomain_suffix.'.touristtube.com" target="_blank"><span style="color: #616161; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px;"><u>Touristtube.com</u></span></a></td>
                    </tr>
                    <tr height="12">
                        <td width="46" height="16"></td>
                        <td valign="middle" align="left"><span style="color: #616161; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px;">was created in response to tourists\' demands to travel intelligently</span></td>
                    </tr>
                    <tr height="12">
                        <td width="46" height="16"></td>
                        <td valign="middle" align="left"></td>
                    </tr>
                    <tr height="12">
                        <td width="46" height="16"></td>
                        <td valign="middle" align="left"><span style="color: #616161; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px;">TouristTube&trade; - Travel Differently</span></td>
                    </tr>
                    <tr height="12">
                        <td width="46" height="16"></td>
                        <td valign="middle" align="left">
                            <table width="160" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                                <tr height="29">
                                        <td><a href="https://www.facebook.com/pages/TouristTube/1635194046710440?fref=ts" target="_blank"><img src="'.$link.'fb.png" alt="Tourist Tube facebook" border="0" width="29" height="29"/></a></td>
                                        <td><a href="https://twitter.com/TouristTube" target="_blank"><img src="'.$link.'tw.png" alt="Tourist Tube facebook" border="0" width="29" height="29"/></a></td>
                                        <td><a href="https://plus.google.com/u/0/+touristtubepage/posts" target="_blank"><img src="'.$link.'gp.png" alt="Tourist Tube facebook" border="0" width="29" height="29"/></a></td>
                                        <td><a href="https://www.pinterest.com/touristtube/" target="_blank"><img src="'.$link.'pn.png" alt="Tourist Tube facebook" border="0" width="29" height="29"/></a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr height="12">
                        <td width="46" height="16"></td>
                        <td valign="middle" align="left"></td>
                    </tr>
                    <tr height="12">
                        <td width="46" height="16"></td>
                        <td valign="middle" align="left"></td>
                    </tr>                    
                    <tr height="2">
                        <td width="46" height="2"></td>
                        <td bgcolor="#dedede"></td>
                    </tr>
                </table>
            </td>
         </tr>
         <tr height="19">
            <td></td>
        </tr>
        <tr >
            <td align="center">';
        $return .='<span style="color: #7f7f7f; font-family:Arial, Helvetica, sans-serif; font-size:11px; line-height:17px;">Please do not reply to this message<br />Tourist Tube LTD . 71-75, Shelton Street, Covent Garden, London, WC2H 9JQ, ENGLAND</span>';        
//    if ($is_channel == 0) {
//        $return .='<font face="Arial, Helvetica, sans-serif" size="2" color="#959292">Please do not reply to this message; this message is a service email related to your use on Tourist Tube. <br />';
//        if ($unsubscribe_lnk != '')
//            $return .='To stop receiving emails from Tourist Tube, <a href="' . $unsubscribe_lnk . '" title=""><font face="Arial, Helvetica, sans-serif" size="2" color="#ebc019">unsubscribe</font></a> ';
//        if ($not_settings != ''){
//            $return .='or change your <a href="' . $not_settings . '" title=""><font face="Arial, Helvetica, sans-serif" size="2" color="#ebc019">tuber notifications settings.</font></a><br />';
//        }else if ($unsubscribe_lnk != ''){
//            $return .='<br />';
//        }
//        $return .='&copy; ' . date("Y") . ', Tourist Tube. <font color="#ebc019">'
//                . '<a href="mailto:support@touristtube.com">'
//                . '<font face="Arial, Helvetica, sans-serif" size="2" color="#ebc019">support@touristtube.com</font></a>'
//                . '</font></font>';
//    }else {
//        $return .='<font face="Arial, Helvetica, sans-serif" size="2" color="#959292">Please do not reply to this message; this message is a service email related to your use on Tourist Tube. <br />';
//        if ($createchannel_lnk != '')
//            $return .='If you did not create a channel on Tourist Tube, please <a href="' . $createchannel_lnk . '" title=""><font face="Arial, Helvetica, sans-serif" size="2" color="#ebc019">click here.</font></a><br />';
//        if ($unsubscribe_lnk != '')
//            $return .='To stop receiving emails from Tourist Tube, <a href="' . $unsubscribe_lnk . '" title=""><font face="Arial, Helvetica, sans-serif" size="2" color="#ebc019">unsubscribe</font></a> ';
//        if ($not_settings != ''){
//            $return .='or change your <a href="' . $not_settings . '" title=""><font face="Arial, Helvetica, sans-serif" size="2" color="#ebc019">tuber notifications settings.</font></a><br />';
//        }else if ($unsubscribe_lnk != ''){
//            $return .='<br />';
//        }
//        $return .='&copy; ' . date("Y") . ', Tourist Tube. <font color="#ebc019">'
//                . '<a href="mailto:support@touristtube.com">'
//                . '<font face="Arial, Helvetica, sans-serif" size="2" color="#ebc019">support@touristtube.com</font>'
//                . '</a>'
//                . '</font></font>';
//    }
    $return .='</td>
        </tr>';
    return $return;
}

function displayFriends($friendsArray) {
    $return = '<tr height="33">
                <td height="33"  width="552"></td>
            </tr>
            <tr height="51">
                <td  width="552">
                    <table width="552" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                        <tr>';
    $nbfriends = count($friendsArray);
    $rest = $nbfriends % 3;
    if ($rest > 0) {
        $lastcolspan = (4 * (3 - $rest) ) + 1;
    } else {
        $lastcolspan = 1;
    }
    for ($i = 0; $i < $nbfriends; $i++) {
        
        if($friendsArray[$i][0]<>''){
        
                if (($i % 3) == 0 && ($i > 0)) {
                    $return .= '<td height="51"  width="50"></td></tr>
                                        <tr height="13">
                                            <td height="13"  width="552" colspan="13"></td>
                                        </tr>
                                        <tr height="51">';
                }
                $return .= '<td width="51" height="51">
                            <a href="'.$friendsArray[$i][5].'" target="_blank"><img src="' . $friendsArray[$i][0] . '" alt="' . $friendsArray[$i][1] . '" border="0" width="51" height="51"/></a>
                        </td>
                        <td width="6"  height="51"></td>
                        <td valign="top" width="93" height="51">
                            <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">
                                <a href="'.$friendsArray[$i][5].'" target="_blank">' . $friendsArray[$i][1] . '</a> <br />
                            </font>
                            <font face="Arial, Helvetica, sans-serif" size="1" color="#aaaaaa">
                            ' . $friendsArray[$i][2] . ' friends<br />' . $friendsArray[$i][3] . ' followers
                            </font>
                        </td>';
                if ($i == ($nbfriends - 1)) {
                    $return .= '<td colspan="' . $lastcolspan . '"  height="51"></td>';
                } else {
                    $return .= '<td width="21"  height="51"></td>';
                }
        
        }
        
    }
    $return .= ' <td height="51"  width="50"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr height="37">
        <td height="37"  width="552"></td>
    </tr>';
    
    
    return $return;
}

function displayFriendsNetwork($friendsArray, $more = '') {
    $return = '
            <tr height="51">
                <td  width="552">
                    <table width="552" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                        <tr>';
    $nbfriends = count($friendsArray);
    $rest = $nbfriends % 3;
    if ($rest > 0) {
        $lastcolspan = (4 * (3 - $rest) ) + 1;
    } else {
        $lastcolspan = 1;
    }
    for ($i = 0; $i < $nbfriends; $i++) {
        if (($i % 3) == 0 && ($i > 0)) {
            $return .= '<td height="51"  width="50"></td></tr>
                                <tr height="13">
                                    <td height="13"  width="552" colspan="13"></td>
                                </tr>
                                <tr height="51">';
        }
        $return .= '<td width="51" height="51">
                    <a class="" href="' . $friendsArray[$i][4] . '" target="_blank"><img src="' . $friendsArray[$i][0] . '" alt="' . $friendsArray[$i][1] . '" border="0" width="51" height="51"/></a>
                </td>
                <td width="6"  height="51"></td>
                <td valign="top" width="93" height="51">
                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">
                        <a class="" href="' . $friendsArray[$i][4] . '" target="_blank">' . $friendsArray[$i][1] . '</a> <br />
                    </font>
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#aaaaaa">
                    ' . $friendsArray[$i][2] . ' friends<br />' . $friendsArray[$i][3] . ' followers
                    </font>
                </td>';
        if ($i == ($nbfriends - 1)) {
            $return .= '<td colspan="' . $lastcolspan . '"  height="51"></td>';
        } else {
            $return .= '<td width="21"  height="51"></td>';
        }
    }
    $return .= ' <td height="51"  width="50"></td>
                </tr>
            </table>
        </td>
    </tr>';
    if ($more != '') {
        $return .= '<tr height="13">
            <td height="13"  width="552"></td>
        </tr>
        <tr height="13">
            <td height="13"  width="552">
                <a href="' . $more . '" title=""><strong><font face="Arial, Helvetica, sans-serif" size="1" color="#e9c21b">check out more...</font></strong></a>
            </td>
        </tr>';
    }
    $return .= '<tr height="37">
        <td height="37"  width="552"></td>
    </tr>';
    return $return;
}

function displaysocial($array, $case, $rateImg) {
    //case can be 1 :: show rating nb and rating img
    //            2 :: dont show rating details
    //            3 :: details for network part ( complitely different )
    $link_path = currentServerURL() . '/media/images/emails/';
    $return = "";
    if ($case == '3') {
        $return .= '<tr>
                <td height="29" bgcolor="#FFFFFF" width="522"></td>
            </tr>
            <tr>
                <td>
                    <table width="522" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                        <tr height="17">
                            <td width="69" height="17"><font face="Arial, Helvetica, sans-serif" size="3" color="#e7ba20">' . $array[0] . '</font></td>
                            <td rowspan="2" width="3" height="34"><img src="' . $link_path . 'email-network-separ.jpg" alt="" /></td>
                            <td width="13"></td>
                            <td width="67" height="17"><font face="Arial, Helvetica, sans-serif" size="3" color="#e7ba20">' . $array[1] . '</font></td>
                            <td rowspan="2" width="3" height="34"><img src="' . $link_path . 'email-network-separ.jpg" alt="" /></td>
                            <td width="13"></td>
                            <td width="81" height="17"><font face="Arial, Helvetica, sans-serif" size="3" color="#e7ba20">' . $array[2] . '</font></td>
                            <td rowspan="2" width="3" height="34"><img src="' . $link_path . 'email-network-separ.jpg" alt="" /></td>
                            <td width="13"></td>
                            <td width="79" height="17"><font face="Arial, Helvetica, sans-serif" size="3" color="#e7ba20">' . $array[3] . '</font></td>
                            <td rowspan="2" width="3" height="34"><img src="' . $link_path . 'email-network-separ.jpg" alt="" /></td>
                            <td width="13"></td>
                            <td height="17"><font face="Arial, Helvetica, sans-serif" size="3" color="#e7ba20">' . $array[4] . '</font></td>
                        </tr>
                        <tr height="17">
                            <td height="17"><font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">new friends</font></td>
                            <td width="13"></td>
                            <td height="17"><font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">lost friends</font></td>
                            <td width="13"></td>
                            <td height="17"><font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">new followers</font></td>
                            <td width="13"></td>
                            <td height="17"><font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">lost followers</font></td>
                            <td width="13"></td>
                            <td height="17"><font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">network growth</font></td>
                        </tr>';
    } else {
        $return .= '
            <tr>
                <td>
                    <table width="522" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                        <tr height="11">';
        if ($case != '2') {
            $return .= '<td width="63" height="11">
                                <font face="Arial, Helvetica, sans-serif" size="2" color="#e7ba20"><strong>' . $array[0] . '</strong></font>
                            </td>';
        }
        $return .= '<td width="55" height="11">
                                <font face="Arial, Helvetica, sans-serif" size="2" color="#e7ba20"><strong>' . $array[1] . '</strong></font>
                            </td>
                            <td width="83" height="11">
                                <font face="Arial, Helvetica, sans-serif" size="2" color="#e7ba20"><strong>' . $array[2] . '</strong></font>
                            </td>
                            <td width="68" height="11" ';
        if ($case == '2') {
            $return .= 'width="218" colspan="4"';
        } else {
            $return .= 'width="35"';
        }
        $return .= '>
                                <font face="Arial, Helvetica, sans-serif" size="2" color="#e7ba20"><strong>' . $array[3] . '</strong></font>
                            </td>';
        if ($case == '1') {
            $return .= '<td  height="11" colspan="2">
                                <font face="Arial, Helvetica, sans-serif" size="2" color="#e7ba20"><strong>' . $array[4] . '</strong></font>
                            </td>
                            <td width="37" height="11"></td>';
        }
        $return .= '</tr>
                        <tr height="11">';
        if ($case != '2') {
            $return .= '<td width="63" height="11" valign="bottom">
                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">views</font>
                                </td>';
        }
        $return .= '<td width="55" height="11" valign="bottom">
                                <font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">likes</font>
                            </td>
                            <td width="83" height="11" valign="bottom">
                                <font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">comments</font>
                            </td>
                            <td width="68" height="11" valign="bottom">
                                <font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">shares</font>
                            </td>';
        if ($case == '2') {
            $return .= '<td  height="11" valign="bottom" width="218" colspan="4"></td>';
        } else {
            $return .= '<td  height="11" valign="bottom" width="35"><font face="Arial, Helvetica, sans-serif" size="2" color="#807f7f">rating</font></td>';
        }
        if ($case == '1') {
            $return .= '<td  height="11" valign="middle"';
                                //<img src="' . $rateImg . '" width="35" height="7" />
            $return .= '</td>
                            <td width="37" height="11"></td>';
        }
        $return .= '</tr>';
    }
    $return .= '</table>
            </td>
        </tr>';
    return $return;
}

function displayimages($array, $containerwidth, $separator, $imgwidth, $imgheight, $imginrow) {
    $return = '';
    $imgNb = count($array);
    $rowsNb = ceil($imgNb / $imginrow);
    $lasrowNb = $imgNb % $imginrow;
    if ($lasrowNb == 0) {
        $lasrowNb = $imginrow;
    }
    $lastcolspan = 1;
    if ($lasrowNb != $imginrow) {
        $lastcolspan = ( 2 * ($imginrow - $lasrowNb) ) + 1;
    }
    //$additionalWidth = $containerwidth - $lastrowsWidth;
    $index = 0;
    $return .= '<table width="' . $containerwidth . '" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">';
    for ($i = 1; $i <= $rowsNb; $i++) {
        $rowNb = $imginrow;
        if (($i == $rowsNb) && ($lasrowNb != 0)) {
            $rowNb = $lasrowNb;
        }
        if ($i > 1) {
            $return .= '<tr height="' . $separator . '">
            <td></td>
        </tr>';
        }
        $return .= '<tr height="' . $imgheight . '">';
        for ($j = 1; $j <= $rowNb; $j++) {
            if (($index + 1) == $imgNb) {
                $return .= '<td height="' . $imgheight . '" colspan="' . $lastcolspan . '">'
                        . '<a class="" href="' . $array[$index][2] . '" target="_blank">'
                        . '<img src="' . $array[$index][0] . '" alt="" width="' . $imgwidth . '" height="' . $imgheight . '"/>'
                        . '</a>'
                        . '</td>';
            } else {
                $return .= '<td height="' . $imgheight . '" width="' . $imgwidth . '"><a class="" href="' . $array[$index][2] . '" target="_blank">'
                        . '<img src="' . $array[$index][0] . '" alt=""  width="' . $imgwidth . '" height="' . $imgheight . '"/></a></td>';
            }
            $index = $index + 1;
            if ($j != $rowNb) {
                $return .= '<td height="' . $imgheight . '" width="' . $separator . '"></td>';
            }
        }
        $return .= '</tr>';
    }
    $return .= '</table>';
    return $return;
}

function displayEmailSubChannelRequest($to_email, $subject, $globArray, $unsubscribe_lnk, $not_settings, $createchannel_lnk) {
    $link_path = currentServerURL() . '/media/images/emails/';
    $userName = $globArray['ownerName'];
    $activate_lnk = $globArray['activateLink'];
    $array4loop = array();
    $array4loop[] = $globArray['invite'];
    $first = 1;
    $is_channel=1;
    $return = '<body>
    <table width="633" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">';
    $return .= displayTopHead($is_channel);
    foreach ($array4loop as $Arr) {
        if (count($Arr) > 0) {
            $count = count($Arr);
            $return .= displayPartHeadProfileMentioned($userName);
            $return .= '<tr>
                <td>
                    <table width="633" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                        <tr>
                            <td width="46"></td>
                            <td>
                                <table width="552" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">';
            for ($p = 0; $p < $count; $p++) {
                $return .= '<tr  height="23" >
                                <td height="23"  width="552"></td>
                            </tr>';
                $return .= '<tr height="51">
                                    <td height="51"  width="552">
                                        <table width="552" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                                            <tr>
                                                <td rowspan="2" width="51" height="51">
                                                    <a href="'.$Arr[$p]['friends'][0][2].'" target="_blank"><img src="' . $Arr[$p]['friends'][0][0] . '" alt="' . $Arr[$p]['friends'][0][1] . '" border="0" width="51" height="51"/></a>
                                                </td>
                                                <td width="6" rowspan="2"></td>
                                                <td valign="top" width="438" height="13">
                                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">
                                                        <a href="'.$Arr[$p]['friends'][0][2].'" target="_blank">' . $Arr[$p]['friends'][0][1] . '</a>
                                                    </font>
                                                </td>
                                                <td width="56" rowspan="2"></td>
                                            </tr>
                                            <tr>
                                                <td valign="bottom" width="438" height="37">
                                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">' . $Arr[$p]['partTitle'] . '
                                                    </font>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr height="21">
                                    <td height="21"  width="552"></td>
                                </tr>
                                <tr height="27">
                                    <td height="27" width="552"><a href="' . $activate_lnk . '" target="_blank" style="text-decoration: none; color:#e8c11f" onmouseover="this.style.color = \'#000000\'" onmouseout="this.style.color = \'#e8c11f\'"><font face="Arial, Helvetica, sans-serif" size="2">'._('accept channel request').'</font></a></td>
                                </tr>';
                if ($p < ($count - 1)) {//not last{
                    $return .= '<tr height="1">
                                        <td height="1"  width="552" background="separator.jpg"></td>
                                    </tr>';
                }
            }
            $return .= '</table>
                            </td>
                            <td width="37"></td>
                        </tr>
                    </table>
                </td>
            </tr>';
        }
    }
    $return .= displayfoot(array(), $unsubscribe_lnk, $not_settings, $createchannel_lnk, 0);
    $return .= '</table>
</body>';
    return send_email_form($to_email, $return, $subject, 'TouristTube',1);
}
function displayEmailTuberFriendRequest($to_email, $subject, $globArray, $unsubscribe_lnk, $not_settings, $createchannel_lnk) {
    $link_path = currentServerURL() . '/media/images/emails/';
    $userName = $globArray['ownerName'];
    $activate_lnk = $globArray['activateLink'];
    $array4loop = array();
    $array4loop[] = $globArray['invite'];
    $first = 1;
    $return = '<body>
    <table width="633" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">';
    $return .= displayTopHead($is_channel);
    foreach ($array4loop as $Arr) {
        if (count($Arr) > 0) {
            $count = count($Arr);
            $return .= displayPartHeadProfileMentioned($userName);
            $return .= '<tr>
                <td>
                    <table width="633" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                        <tr>
                            <td width="46"></td>
                            <td>
                                <table width="552" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">';
            for ($p = 0; $p < $count; $p++) {
                $return .= '<tr  height="23" >
                                <td height="23"  width="552"></td>
                            </tr>';
                $return .= '<tr height="51">
                                    <td height="51"  width="552">
                                        <table width="552" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                                            <tr>
                                                <td rowspan="2" width="51" height="51">
                                                    <a href="'.$Arr[$p]['friends'][0][5].'" target="_blank"><img src="' . $Arr[$p]['friends'][0][0] . '" alt="' . $Arr[$p]['friends'][0][1] . '" border="0" width="51" height="51"/></a>
                                                </td>
                                                <td width="6" rowspan="2"></td>
                                                <td valign="top" width="438" height="13">
                                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">
                                                        <a href="'.$Arr[$p]['friends'][0][5].'" target="_blank">' . $Arr[$p]['friends'][0][1] . '</a>
                                                    </font>
                                                </td>
                                                <td width="56" rowspan="2"></td>
                                            </tr>
                                            <tr>
                                                <td valign="bottom" width="438" height="37">
                                                    <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">' . $Arr[$p]['partTitle'] . '
                                                    </font>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr height="21">
                                    <td height="21"  width="552"></td>
                                </tr>
                                <tr height="27">
                                    <td height="27" width="552"><a href="' . $activate_lnk . '" target="_blank" style="text-decoration: none; color:#e8c11f" onmouseover="this.style.color = \'#000000\'" onmouseout="this.style.color = \'#e8c11f\'"><font face="Arial, Helvetica, sans-serif" size="2">'._('accept friendship request').'</font></a></td>
                                </tr>';
                if ($p < ($count - 1)) {//not last{
                    $return .= '<tr height="1">
                                        <td height="1"  width="552" background="separator.jpg"></td>
                                    </tr>';
                }
            }
            $return .= '</table>
                            </td>
                            <td width="37"></td>
                        </tr>
                    </table>
                </td>
            </tr>';
        }
    }
    $return .= displayfoot(array(), $unsubscribe_lnk, $not_settings, $createchannel_lnk, 0);
    $return .= '</table>
</body>';
    return send_email_form($to_email, $return, $subject, 'TouristTube',1);
}

function displayEmailShareTypes($to_email, $subject, $globArray, $unsubscribe_lnk, $not_settings, $createchannel_lnk,$is_channel) {
    global $twig;
    $template = $twig->loadTemplate('displayEmailShareTypes.html.twig'); //specify your template here
    global $CONFIG;
    $data['subdomain_suffix'] = $CONFIG['subdomain_suffix'];
    $link_path = currentServerURL() . '/media/images/emails/';
    $userName = $globArray['ownerName'];
    $array4loop = array();
    $array4loop[] = $globArray['invite'];
    $first = 1;
    
    $return = '<body>
    <table width="633" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">';
    
    $data['array4loop']= $array4loop;
    $data['displayTopHead']= displayTopHead($is_channel);
    
    foreach ($array4loop as $k=>$Arr) {
        if (count($Arr) > 0) {
            $count = count($Arr);
            $data['displayPartHeadProfileMentioned'][$k]= displayPartHeadProfileMentioned($userName);
        }
    }
       
    $data['displayfoot'] = displayfoot(array(), $unsubscribe_lnk, $not_settings, $createchannel_lnk, 0);
    $return =html_entity_decode( $template->render($data)  ) ;
   
    return send_email_form($to_email, $return, $subject, 'TouristTube', 1);
}

function displayEmailCanceledUpdateEvent($to_email, $subject, $globArray, $socialArray, $is_channel, $is_channel_foot, $unsubscribe_lnk, $not_settings, $createchannel_lnk) {
    global $twig;
    $template = $twig->loadTemplate('displayEmailCanceledUpdateEvent.html.twig'); //specify your template here
    global $CONFIG;
    $data['subdomain_suffix'] = $CONFIG['subdomain_suffix'];
   
    $link_path = currentServerURL() . '/media/images/emails/';
    $userName = $globArray['ownerName'];
    if (isset($globArray['event'])) {
        $data['Arr'] = $globArray['event'];
    }
    $data['link_path'] = $link_path;
    $data['userName'] = $userName;

    $data['displayTopHead'] = displayTopHead($is_channel, $twig);
    
    $data['displayfoot'] = displayfoot($socialArray, $unsubscribe_lnk, $not_settings, $createchannel_lnk, $is_channel_foot);
    
    $return = html_entity_decode($template->render($data));
        
    return send_email_form($to_email, $return, $subject, 'TouristTube' , 1);
}

function displayProfileMentionedEmail($to_email, $globArray, $socialArray, $unsubscribe_lnk, $not_settings, $createchannel_lnk = "") {
    global $twig;
    $template = $twig->loadTemplate('displayProfileMentionedEmail.html.twig'); //specify your template here
    global $CONFIG;
    $data['subdomain_suffix'] = $CONFIG['subdomain_suffix'];
    
    $link_path = currentServerURL() . '/media/images/emails/';
    $userName = $globArray['ownerName'];
    $array4loop = array();
    $array4loop[] = $globArray['comment'];
    $return = '';
    $data['displayTopHead'] = displayTopHead('0');
    $data['array4loop'] = $array4loop;
    $data['link_path'] = $link_path;
    
    foreach ($array4loop as $k=>$Arr) {
        if (count($Arr) > 0) {
            $count = count($Arr);
            $data['displayPartHeadProfileMentioned'][$k] = displayPartHeadProfileMentioned($userName);           
            for ($p = 0; $p < $count; $p++) {
                if ($Arr[$p]['case'] == 2) {
                    $data['displayFriends'][$p] = displayFriends($Arr[$p]['friends']);
                }
            }
        }
    }
    $data['displayfoot'] = displayfoot($socialArray, $unsubscribe_lnk, $not_settings, $createchannel_lnk, 0);

    $return = html_entity_decode($template->render($data));
    
    $subject = _("Someone commented on your entities");
    return send_email_form($to_email, $return, $subject, 'TouristTube' , 1);
}

function displayitem($array) {
    $link = currentServerURL() . '/media/images/emails/';
    $return = '';
    $url_isset = isset($array['url']) && !empty($array['url']);
    if ($array['title'] == 'things to do') {
        if ($array['type'] == 'poi') {
            $return .= '<tr><td>
<table width="552" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
<tr><td height="28" colspan="2"></td></tr>
<tr>
    <td width="145"  align="left" valign="top">';
            if ($url_isset)
                $return .= '<a href="' . $array['url'] . '">';
            $return .= '<img src="' . $array['img'][0] . '" alt="' . $array['img'][1] . '" width="136" height="76" border="0" />';
            if ($url_isset)
                $return .= '</a>';
            $return .= '</td>
    <td align="left" valign="top">';
            if ($url_isset)
                $return .= '<a href="' . $array['url'] . '">';
            $return .= '<strong><font face="Arial, Helvetica, sans-serif" size="2" color="#747576">' . $array['name'] . '</font>';
            if ($url_isset)
                $return .= '</a>';
            $return .= '<br /></strong>
        <strong><font face="Arial, Helvetica, sans-serif" size="2" color="#747576">location: ' . $array['location'] . '</font></strong>
            </td>
</tr>
</table></td></tr>';
        }
        else {
            $return .= '<tr><td>
<table width="552" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
<tr><td height="28" colspan="2"></td></tr>
<tr>
    <td width="145"  align="left" valign="top">';
            if ($url_isset)
                $return .= '<a href="' . $array['url'] . '">';
            $return .= '<img src="' . $array['img'][0] . '" alt="' . $array['img'][1] . '" width="136" height="76" border="0" />';
            if ($url_isset)
                $return .= '</a>';
            $return .= '</td>
    <td align="left" valign="top"><strong><font face="Arial, Helvetica, sans-serif" size="2" color="#747576">' . $array['poiDetails'][0] . '</font> <img src="' . $link . 'email-time-symb.png" alt="" /> <font face="Arial, Helvetica, sans-serif" size="2" color="#afafaf">' . $array['poiDetails'][1] . '<br /></strong>
        <strong><font face="Arial, Helvetica, sans-serif" size="2" color="#747576">location: ' . $array['poiDetails'][2] . '</font><br /></strong>
        <strong><font face="Arial, Helvetica, sans-serif" size="2" color="#c69d1c">' . $array['poiDetails'][3] . '</font><br /></strong>
        <strong><font face="Arial, Helvetica, sans-serif" size="2" color="#747576">' . $array['poiDetails'][4] . ' </font></strong></td>
</tr>
</table></td></tr>';
        }
    }else {
        $return .= '<tr><td>
<table width="552" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
<tr><td height="28" colspan="3"></td></tr>
<tr>
    <td width="145"  align="left" valign="top">';
        if ($url_isset)
            $return .= '<a href="' . $array['url'] . '">';
        $return .= '<img src="' . $array['img'][0] . '" alt="' . $array['img'][1] . '" width="136" height="76" border="0" />';
        if ($url_isset)
            $return .= '</a>';
        $return .= '</td>
    <td width="198" align="left" valign="top">';
        if ($url_isset)
            $return .= '<a href="' . $array['url'] . '">';
        $return .= '<strong><font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">' . $array['name'] . '</font></strong>';
        if ($url_isset)
            $return .= '</a>';
        $return .= '<br />
        <font face="Arial, Helvetica, sans-serif" size="2" color="727272">' . $array['address'] . '<br />' . $array['location'] . '<br /></font>';
        if ($array['stars'] != '') {
            $return .= '<img src="' . $link . 'album-rating' . $array['stars'] . '.png" alt="stars" width="47" height="9" border="0" />';
        }
        $return .= '</td>
    <td  align="left" valign="top"><strong><font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">Tuber Evaluation</font></strong><br />
<font face="Arial, Helvetica, sans-serif" size="2" color="727272">' . $array['rate'] . '<br /><u>(' . $array['reviews'] . ' reviews)</u></font></td>
</tr>
</table></td></tr>';
    }
    return $return;
}

function shareBrifecaseByEmail($to_email, $globArray) {
    global $twig;
    
    $template = $twig->loadTemplate('shareBrifecaseByEmail.html.twig'); //specify your template here
    global $CONFIG;
    $data['subdomain_suffix'] = $CONFIG['subdomain_suffix'];
    $link = currentServerURL() . '/media/images/emails/';
    $p = 1;
    $blocks = 0;
    $bagDesc = $globArray['bagDesc'];
    $userName = $globArray['ownerName'];
    $data['link'] = $link;
    $data['userName'] = $userName;
    $data['blocks'] = $blocks;
    $data['p'] = $p;
    if (isset($globArray['bag'])) {
        $Arr = $globArray['bag'];
        $data['Arr'] = $Arr;
    }
    $return = '';
    $data['displayTopHead'] = displayTopHead(0);
    if (count($Arr) > 0) {
        $data['displayPartHead'] = displayPartHead($userName, 'TT BAG', $first);
        
        $data['bagDesc'] = $bagDesc;
        
        
        $count = count($Arr);
        
        $poi = array();
        $rest = array();
        $hot = array();
        foreach ($globArray['bag'] as $bag) {
            if ($bag['title'] == 'things to do') {
                $poi[] = $bag;
            } else if ($bag['title'] == 'restaurants') {
                //$rest[] = $bag;
            } else if ($bag['title'] == 'hotels') {
                $hot[] = $bag;
            }
        }
        $data['poi'] = $poi;
        $data['rest'] = $rest;
        $data['hot'] = $hot;

        if (count($hot) > 0) {
            $diplayItemhot = '';
            for ($h = 0; $h < count($hot); $h++) {
                $diplayItemhot .= displayitem($hot[$h]);
            }
            $data['diplayItemhot'] = $diplayItemhot;
        }
        if (count($rest) > 0) {
            $displayitemrest = '';
            for ($h = 0; $h < count($rest); $h++) {
                $displayitemrest .= displayitem($rest[$h]);
            }
            $data['displayitemrest'] = $displayitemrest;
        }
        if (count($poi) > 0) {
            $displayitempoi = '';
            for ($h = 0; $h < count($poi); $h++) {
                $displayitempoi .= displayitem($poi[$h]);
            }
            $data['displayitempoi'] = $displayitempoi;
            $p++;
        }
//
    }
    $data['displayfoot'] = displayfoot(array());
    $return = html_entity_decode($template->render($data));
    $subject = _("Someone shared his brifecase with you");
    return send_email_form($to_email, $return, $subject, 'TouristTube' ,0);
}

function send_email_form($to_email='', $msg='', $subject='', $title='', $priority = 0) {
    return AddEmailData($to_email, $msg, $subject, $title, $priority);
    //$to_email = 'elie@paravision.org';
    /* $to_email = 'rudy.sleiman@gmail.com';
      ini_set('error_reporting',E_ALL);
      ini_set('display_errors',1);
      $mail = new PHPMailer();

      //$mail->IsSMTP(); // telling the class to use SMTP
      $mail->SMTPDebug = 1;	  // enables SMTP debug information (for testing)

      $mail->SetFrom('noreply@touristtube.com', $title );

      $mail->Subject = "$subject";
      $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";

      $mail->MsgHTML($msg);

      $mail->AddAddress($to_email);

      if ( !$mail->Send() ) {
      file_put_contents("mail.log", "Mailer Error: " . $mail->ErrorInfo . "\r\n" , FILE_APPEND);
      return false;
      } else {
      file_put_contents("mail.log", "Mailer Sent\r\n" , FILE_APPEND);
      return true;
      } */
}
/**
 * add email
 * @param string $to_email the email to
 * @param string $msg the message to sent
 * @param string $subject the subject
 * @param string $title the email title
 * @param integer $priority the email priority
 * @return integer | false the newly inserted cms_emails id or false if not inserted
 */
function AddEmailData($to_email, $msg, $subject, $title, $priority) {
    /*if($to_email=='sleimanroudy@paravision.org') $to_email='rudy.sleiman@gmail.com';
    if($to_email!='rudy.sleiman@gmail.com'){
        $to_email ='claude@paravision.org';
    }*/
    if( $to_email!='' ){
	if( $to_email=='user@touristtube.com' ){
	    return true;
	}
        global $dbConn;
        $params  = array();
        $msg = $msg;
        $subject = $subject;
        $title = $title;
        $query = "INSERT INTO cms_emails (to_email, msg,subject,title,priority)
                VALUES (:To_email,:Msg,:Subject,:Title,:Priority)";
        
        $params[] = array(  "key" => ":To_email", "value" =>$to_email);
        $params[] = array(  "key" => ":Msg", "value" =>$msg);
        $params[] = array(  "key" => ":Subject", "value" =>$subject);
        $params[] = array(  "key" => ":Title", "value" =>$title);
        $params[] = array(  "key" => ":Priority", "value" =>$priority);
        
        $insert = $dbConn->prepare($query);
        PDO_BIND_PARAM($insert,$params);
        $res = $insert->execute();
        $spam_record_id = $dbConn->lastInsertId();
        
        return ( $res ) ? $spam_record_id : false;
    } else{
        return false;
    }
    
}

