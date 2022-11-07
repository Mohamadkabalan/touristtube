<?php
    
    $bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 1);
    $path = '';
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );

    include_once ( $path . "inc/functions/videos.php" );
    include_once ( $path . "inc/functions/users.php" );
	
//    $CountryIp = $_SERVER["REMOTE_ADDR"];
    $CountryIp = $request->server->get('REMOTE_ADDR', '');
    $CountryName = getCountryFromIP($CountryIp, "NamE");
    $CountryCode = getCountryFromIP($CountryIp, "code");
    
    $user_id = userGetID();
	$txt_id = intval(UriGetArg('id'));	

    if($user_id == ''){
		header('location: ' . ReturnLink('register') );
    }
	if( $txt_id !=0 && $txt_id !='' ){
		$info_journal = getJournalInfo($txt_id);
		if($info_journal['user_id'] != $user_id ){
			header('Location:' . ReturnLink('/') );
		}
	}
	
	$includes = array("js/jscal2.js","js/jscal2.en.js",'css/upload-journal.css','assets/tuber/js/upload-journal.js','js/autocomplete-city.js','js/jquery.corner.js','css/jscal2.css',
     // Add for responsive 5 May
  'media'=>'css_media_query/media_style.css',
  'media1'=>'css_media_query/favorites_media.css');
	

	if(userIsLogged() && userIsChannel()){
            array_unshift($includes, 'css/channel-header.css');
            tt_global_set('includes', $includes);
            include("TopChannel.php");	
	}else{	
            tt_global_set('includes', $includes);
            include("TopIndex.php");
	}
?>

<script type="text/javascript">
<?php

$location_id = '';
$location_name = '';
$location_nameaccent = _('place taken at...');
$cityname = '';
$citynameaccent = '';
$category_id = -1;
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//if(isset($_REQUEST['location'])){
//	$location_id = intval($_REQUEST['location']);
if(isset($submit_post_get['location'])){
	$location_id = intval($submit_post_get['location']);
	$location = locationGet($location_id);
	if($location != false){
		$location_name = addslashes($location['name']);
                $location_nameaccent = addslashes($location['accent_name']);

                $cityRow = cityGetInfo($location['city_id']);
                $cityname = addslashes($cityRow['name']);
                $citynameaccent = addslashes($cityRow['accent']);
                $CountryCode = strtoupper($cityRow['country_code']);

                $category_id = $location['category_id'];
	}else{
		die('');
	}
}

$country_options = '';
$countries = array();
$country_array = countryGetList(); 
foreach ($country_array as $country_item) { 
    $countries[$country_item['code']] = $country_item['name'];            
}
asort($countries);
foreach($countries as $cc => $name){
	$Selected = '';
	if( $cc == 'ZZ') continue;
	if($CountryCode == $cc){
		$Selected = ' selected="selected"';
	}
	$country_options .= '<option value="'.$cc.'"'.$Selected.'>'.str_replace("'", "\'", $name).'</option>';	
}

$Trips = userGetTrips($user_id);
if($Trips != false){
	$i = 0;
	$Trips2 = array();
	while($i< count($Trips)){
		$Trips2[] = str_replace('"', '\"', $Trips[$i]);
		$i++;
	}
	$Trips = '"' . implode('","',$Trips2) . '"';
}else $Trips = '';

if($location_nameaccent==''){
	$location_nameaccent=_('place taken at...');
}

?>


 var userTrips = [<?php echo $Trips ?>];

function addanotherappend(newlisting, isImage){
	var ispblickstr="";
	var privacyselcted=parseInt($('.uploadalbumselected .logalbumelected li .formcontainercreatealbum').attr('data-value'));
	
	switch(privacyselcted){
		case 0:
			ispblickstr="privacyclass5";
		break;
		case 1:
			ispblickstr="privacyclass2";
		break;
		case 2:
			ispblickstr="privacyclass1";
		break;
		case 3:
			ispblickstr="privacyclass4";
		break;
		case 4:
			ispblickstr="privacyclass3";
		break;
	}
	var anotherlistitem = '<div class="formcontainer" style="height: 285px; width:869px;">'+
		'<div class="tableform">'+
			'<div class="UploadMediaLeftTF">'+				
				'<label style="display:none"><div id="'+ispblickstr+'" class="privacyclass active" data-value="'+privacyselcted+'"><div class="privacyclassicon1 privacyclassicons" name="'+newlisting+'_is_public"></div><div class="privacyclasstxt"></div></div></label>'+
				'<div id="uploadprivacyinfotitleright1" class="uploadinfotitle uploadpicinfotxt"><div><?php print addslashes(_("title"));?></div><div class="uploadinfomandatory uploadinfomandatorytitle"><div>*</div><span></span></div></div>'+			
				'<input type="text" name="title" id="'+newlisting+'title" class="inputuploadformTF" value="<?php print addslashes(_("title..."));?>" data-value="<?php print addslashes(_("title..."));?>" onfocus="removeValue2(this)" onblur="addValue2(this)"/>'+
				'<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Description'));?></div><div class="uploadinfomandatory uploadinfomandatorydescription"><div>*</div><span></span></div></div>'+
				'<textarea id="'+newlisting+'description" name="description" class="inputuploaddescriptionTF" data-value="description..." onfocus="removeValue2(this)" onblur="addValue2(this)"><?php echo addslashes(_('description...'));?></textarea>' +				
				'<div class="uploadinfocheckboxcontainer">' +
					'<div class="uploadinfocheckbox uploadinfocheckbox2" style="margin-top:7px;"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt"> <?php echo addslashes(_('set as icon for the journal'));?></div></div>'+
				'</div>'+	
			'</div>'+
			'<div class="UploadMediaRightTF">'+
				'<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Keywords'));?></div><div class="uploadinfomandatory uploadinfomandatorykeywords"><div>*</div><span></span></div><div class="uploadinfohint"><?php echo addslashes(_('(e.g. nature, restaurant, food)'));?></div></div>'+
				'<input id="'+newlisting+'keywords" type="text" style="margin-bottom:0px;" name="keywords" class="inputuploadformTF" value="keywords..." data-value="keywords..." onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
				
				'<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Place taken at'));?></div><div class="uploadinfomandatory uploadinfomandatoryplacetakenat"><div>*</div><span></span></div></div>' +
				'<input type="text" name="placetakenat" id="'+newlisting+'placetakenat" class="inputuploadformTF" value="<?php echo $location_nameaccent ?>" data-value="<?php print addslashes(_("place taken at..."));?>" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +				
				'<div id="'+newlisting+'save" class="publishbut" style="top:253px;"><div id="'+newlisting+'savein" class="SaveButton_journal publishbuttxt"><?php echo addslashes(_('save'));?></div></div>'+
			'</div>'+
			'<input type="hidden" name="filename" id="'+newlisting+'filename" class="fi_name"/>' +
			'<input type="hidden" name="vpath" id="'+newlisting+'vpath" class="v_path"/>' +
			'<input type="hidden" id="'+newlisting+'Lat" name="lattitude" value="'+CurrentLocation.latitude+'"/><input type="hidden" id="'+newlisting+'Long" name="longitude" value="'+CurrentLocation.longitude+'"/>'+
			'<input type="hidden" name="status"/>' + 
			'<input type="hidden" name="vid"/>' +
			'<a href="'+generateLangURL('/showthumb.php?videoid=')+'" id="'+newlisting+'fancy" style="width:0px;height:0px;" class="showthumblink_album"></a>'+
	'</div>'+
	'</div>';

	return anotherlistitem;
}

	var ACTIVE_UPLOADS = 0;

function addnewalbumdata(newlisting){
        <?php 
            $date = returnSocialTimeFormat( date('Y-m-d') ,4);
            $date1 = returnSocialTimeFormat( date('Y-m-d') ,5);
        ?>
	var anotherlistitem ='<div class="formcontainercreatealbum"><?php echo addslashes(_('CREATE JOURNAL'));?></div>'+ 
	'<div class="formcontainer" style="height: 360px; width:869px; top:49px;">'+
		'<div class="tableform" style="height:100%; width:100%;">'+
			'<div class="UploadMediaLeftTF">'+
				'<div id="uploadprivacyinfotitle" class="uploadinfotitle"><?php echo addslashes(_('privacy'));?></div>'+				
				'<label><div id="privacyclass_user'+USER_PRIVACY_PUBLIC+'" class="privacyclass active" data-value="'+USER_PRIVACY_PUBLIC+'"><div class="privacyclassicon_user'+USER_PRIVACY_PUBLIC+' privacyclassicons" name="'+newlisting+'_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('public'));?></div></div></label><label><div id="privacyclass_user'+USER_PRIVACY_COMMUNITY+'" class="privacyclass" data-value="'+USER_PRIVACY_COMMUNITY+'"><div class="privacyclassicon_user'+USER_PRIVACY_COMMUNITY+' privacyclassicons" name="'+newlisting+'_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('friends'));?></div></div></label><label><div id="privacyclass_user'+USER_PRIVACY_PRIVATE+'" class="privacyclass" data-value="'+USER_PRIVACY_PRIVATE+'"><div class="privacyclassicon_user'+USER_PRIVACY_PRIVATE+' privacyclassicons" name="'+newlisting+'_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('private'));?></div></div></label><label><div id="privacyclass_user'+USER_PRIVACY_SELECTED+'" class="privacyclass" data-value="'+USER_PRIVACY_SELECTED+'"><div class="privacyclassicon_user'+USER_PRIVACY_SELECTED+' privacyclassicons" name="'+newlisting+'_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('custom'));?></div></div></label>' +
				'<div class="uploadinfotitle margintop19"><div><?php print addslashes(_("title"));?></div><div class="uploadinfomandatory uploadinfomandatorytitle"><div>*</div><span></span></div></div>'+			
				'<input type="text" name="title" id="'+newlisting+'title" class="inputuploadformTF" maxlength="100" value="<?php echo addslashes(_('title...'));?>" data-value="<?php print addslashes(_("title..."));?>" onfocus="removeValue2(this)" onblur="addValue2(this)"/>'+
				'<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Description'));?></div><div class="uploadinfomandatory uploadinfomandatorydescription"><div>*</div><span></span></div></div>'+
				'<textarea id="'+newlisting+'description" name="description" class="inputuploaddescriptionTF" data-value="description..." onfocus="removeValue2(this)" onblur="addValue2(this)"><?php echo addslashes(_('description...'));?></textarea>' +	
				'<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('date'));?></div></div>'+			
				'<input type="text" name="date" id="'+newlisting+'date" class="inputuploadformTFDate" readonly="readonly" value="<?php echo $date1; ?>" data-cal="<?php echo $date; ?>"/>'+		
			'</div>'+
			'<div class="UploadMediaRightTF">'+
			
			'<div class="peoplecontainer peoplecontainer_custom formContainer100 margintop2" style="position:absolute; margin-left:1px; width:415px; display:none; top:10px;">'+
					'<div class="emailcontainer emailcontainer_privacy emailcontainer_privacy_custom" style="width:411px;height:55px;">'+
						'<div class="addmore" style="margin: 0px 3px 3px 0;"><input name="addmoretext" id="addmoretext_privacy" type="text" class="addmoretext_css" data-value="<?php print addslashes(_("add more"));?>" value="<?php print addslashes(_("add more"));?>" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>'+
				'</div>'+
				
				'<div class="uploadinfotitle margintop88"><div>'+$.i18n._('Keywords')+'</div><div class="uploadinfomandatory uploadinfomandatorykeywords"><div>*</div><span></span></div><div class="uploadinfohint"><?php echo addslashes(_('(e.g. nature, restaurant, food)'));?></div></div>'+
				'<input id="'+newlisting+'keywords" type="text" style="margin-bottom:0px;" name="keywords" class="inputuploadformTF" value="<?php echo addslashes(_('keywords...'));?>" data-value="keywords..." onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +	
				
				'<div class="uploadinfotitle uploadpicinfotxt"><div>'+$.i18n._('Country')+'</div><div class="uploadinfomandatory uploadinfomandatorycountry"><div>*</div><span></span></div></div>' +
				'<select id="'+newlisting+'country" name="country" class="inputuploadformTF">' +
						'<option value="0">'+$.i18n._('Select Country')+'</option>'+
						'<?php echo $country_options ?>' +
				'</select>' +
				'<div class="uploadinfotitle uploadpicinfotxt"><div><?php print addslashes(_("City"));?></div><div class="uploadinfomandatory uploadinfomandatorycitynameaccent"><div>*</div><span></span></div></div>' +
				'<input type="text" name="citynameaccent" id="'+newlisting+'citynameaccent" class="inputuploadformTF" value="<?php echo $citynameaccent; ?>"/>' +
				'<input type="hidden" name="cityname" id="'+newlisting+'cityname" class="inputuploadformTF" value="<?php echo $cityname; ?>"/>' +
				'<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Place taken at'));?></div><div class="uploadinfomandatory uploadinfomandatoryplacetakenat"><div>*</div><span></span></div></div>' +
				'<input type="text" name="placetakenat" id="'+newlisting+'placetakenat" class="inputuploadformTF" value="<?php echo $location_nameaccent ?>" data-value="'+$.i18n._("place taken at...")+'" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
				'<input type="hidden" name="location" id="'+newlisting+'location" class="inputuploadformTF" value="<?php echo  $location_id ?>"/>' +
				'<input type="hidden" name="location_name" id="'+newlisting+'location_name" class="inputuploadformTF" value="<?php echo  $location_name ?>"/>' +
				'<div id="'+newlisting+'save" class="publishbut" style="top:318px;"><div class="ClearNewAlbumButton publishbuttxt"><?php echo addslashes(_('clear'));?></div><div id="'+newlisting+'savein" class="SaveNewAlbumButton publishbuttxt"><?php echo addslashes(_('save'));?></div></div>'+
			'</div>'+
	'</div>'+
	'</div>';

	return anotherlistitem;
}
</script>

<script type="text/javascript">
	var txt_id = '<?php echo $txt_id; ?>';
	var currentsection="upload-album";
	var currentlogalbumaddid="logalbumadd";
	$(document).ready(function(){
		var mystr=addnewalbumdata(currentlogalbumaddid);
		$('.uploadalbum .'+currentlogalbumaddid+' li').html(mystr);
		Calendar.setup({
			inputField : "logalbumadddate",
                noScroll  	 : true,
			trigger    : "logalbumadddate",
			align:"B",
			onSelect   : function() {
				var date = Calendar.intToDate(this.selection.get());
				$('#logalbumadddate').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));
				this.hide();
			},
			dateFormat : "%d-%m-%Y"
		});
		addAutoComplete(currentlogalbumaddid);
	});
		
</script>
    <?php 
	$userInfo = getUserInfo( userGetID() );
        $creator_avatar_link = userProfileLink($userInfo) . '/TTpage';
        $section = 'upload-journal';
        $realUser = true;
        include('parts/profile_header.php');
    ?>
		<div class="upload-overlay-loading-fix"><div></div></div>
        <div class="upload-overlay-loading-fix-file"><div class="loadingicon" data-id=""></div><span><?php echo _('In process');?></span></div>
        <div class="getalbumuploaddatapics" style="display:none"></div>
        <div class="albumuploaddescription" style="display:none"></div>
		<div id="MiddleInsideNormal" style="height:auto;">
			
			<div id="flash_err" style="display: none; position: relative; top: 30px; width: 600px; margin-left: auto; margin-right: auto;">
				<?php echo _('Get the latest version of adobe flash') ?> <a href="http://get.adobe.com/flashplayer/">get.adobe.com/flashplayer/</a>
			</div>
			<div id="InsideNormalup">
                <div id="InsideNormal">
                    <div id="uploadheadbar">
                        <div id="uploadinsidettlo"><?php echo _('CREATE');?></div>
                        <div id="uploadinsidemenu1" class="uploadinsidemenu active"><?php echo _('journal');?></div>
                        <div id="uploadinsideicon"></div>
                    </div>
                    <div id="uploadalbumContent">
                        <div id="uploadnewalbum"></div>
                        <div id="uploadnewalbumover">
                            <div id="lefticonover"></div>
                            <div id="uploadnewalbumovertxt"><?php echo _('add journal');?></div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="userID" value="<?php echo $user_id; ?>" id="userID"/>
                    
                      <ul style="margin-top:-2px;">
                      	  <li class="uploadalbum" style="height:410px; overflow:hidden;">
                                <ol id="log_2" class="logalbumadd">
                                	<li style="height:410px; margin:0;">
                                    	
                                    </li>
                                </ol>
                          </li>
                          <li class="uploadalbumselected" style="display:none; overflow:hidden;">
                                <ol id="log_2" class="logalbumelected">
                                	<li style="height:76px; margin:0;">
                                    	
                                    </li>
                                </ol>
                          </li>
                          <li class="upload1_2" id="uploadSection" style="display:none">
    
                          <input type="hidden" name="allfiles" id="allfiles" value="">
                          <div class="deleteddiv"></div>
                            <div class="uploadinsidetxt1"><?php echo _('Choose images or videos to upload. <span>(Choose more than one file by pressing the "Ctrl" key while selecting files).');?></span></div>
                            <div class="selectuploadbtn_new" id="swfupload-control1"><input type="button" id="button1" class="UploadButton" value="<?php echo _('Select files from your device') ?>"/></div>
                            <div class="uploadinsidetxt2_new" id="dropbox"><?php echo _('or drag and drop videos and photos anywhere on this page to start uploading');?></div>
                          </li>
                          <li>
                                <div class="upload-loading-txt-file"></div>
                          </li>
                          <li class="upload2">
                                <ol id="log"></ol>
                          </li>
                      </ul>
					
              </div>
          </div>
			
</div>
<?php include("closing-footer.php");?>