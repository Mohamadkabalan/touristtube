<?php
$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 1);
$path = '';
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );

//$CountryIp = $_SERVER["REMOTE_ADDR"];
$CountryIp = $request->server->get('REMOTE_ADDR', '');
$CountryName = getCountryFromIP($CountryIp, "NamE");
$CountryCode = getCountryFromIP($CountryIp, "code");

$user_id = userGetID();

if ($user_id == '') {
    header('location: ' . ReturnLink('register'));
}

$txt_id_init = intval(UriGetArg('id'));
$txt_id = ($txt_id_init == 0) ? null : $txt_id_init;

if ($txt_id != 0 && $txt_id != '') {
    $info_album = userCatalogGet($txt_id);
    if ($info_album['user_id'] != $user_id) {
        header('Location:' . ReturnLink('register'));
    }
}

/*$includes = array('media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,'media1'=>'css_media_query/upload.css?v='.MQ_UPLOAD_CSS_V,'css/upload.css', 'js/upload-behavior.js', 'js/autocomplete-city.js', 'js/jquery.corner.js');*/

$includes = array('css/upload.css', 
    'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,
    'media1'=>'css_media_query/upload-album.css?v='.MQ_UPLOAD_CSS_V,
    'css/real_uploader/style.css',
    /*'js/upload-behavior.js',*/
    'js/autocomplete-city.js',
    'js/jquery.corner.js',
    'js/real_ajax_uploader/realAjaxUploader.js',
    'js/real_ajax_uploader/uploader.js'
);

tt_global_set('includes', $includes);

$userIschannel = userIsChannel();
$userIschannel = ($userIschannel) ? header("Location: " . ReturnLink('register')) : 0;

include("TopIndex.php");

$srch_options2 = array(
    'user_id' => $user_id,
    'orderby' => 'id',
    'is_owner' => 1,
    'order' => 'd'
);
$albumarray = userCatalogSearch($srch_options2);
$len = count($albumarray);
$album_options = '';
for ($i = 0; $i < $len; $i++) {
    $mydata = $albumarray[$i];
    $album_options.='<option id="' . $mydata['id'] . '" value="' . $mydata['id'] . '">' . htmlEntityDecode($mydata['catalog_name']) . '</option>';
}
?>
<script type="text/javascript">
<?php
$location_id = '';
$location_name = '';
$location_nameaccent = '';
$cityname = '';
$citynameaccent = '';
$category_id = -1;
$submit_post_get = array_merge($request->query->all(),$request->request->all());

//if (isset($_REQUEST['location'])) {
//    $location_id = intval($_REQUEST['location']);
if (isset($submit_post_get['location'])) {
    $location_id = intval($submit_post_get['location']);
    $location = locationGet($location_id);
    if ($location != false) {
        $location_name = $location['name'];
        $location_nameaccent = $location['accent_name'];

        $cityRow = cityGetInfo($location['city_id']);
        $cityname = $cityRow['name'];
        $citynameaccent = $cityRow['accent'];
        $CountryCode = strtoupper($cityRow['country_code']);

        $category_id = $location['category_id'];
    } else {
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
foreach ($countries as $cc => $name) {
    $Selected = '';
    if ($cc == 'ZZ')
        continue;
    if ($CountryCode == $cc) {
        $Selected = ' selected="selected"';
    }
    $country_options .= '<option value="' . $cc . '"' . $Selected . '>' . addslashes($name) . '</option>';
}

$cats = categoryGetHash(array('hide_all' => true));
$cat_options = '';
$cat_options .= '<option value="0" selected="selected">'._('Select Category').'</option>';
foreach ($cats as $id => $title) {
    $selected = ($category_id == $id) ? ' selected="selected" ' : '';
    $cat_options .= "<option value=\"{$id}\" {$selected}>" . addslashes($title) . '</option>';
}

$Trips = userGetTrips($user_id);
if ($Trips != false) {
    $i = 0;
    $Trips2 = array();
    while ($i < count($Trips)) {
        $Trips2[] = str_replace('"', '\"', $Trips[$i]);
        $i++;
    }
    $Trips = '"' . implode('","', $Trips2) . '"';
} else
    $Trips = '';

if ($location_nameaccent == '') {
    $location_nameaccent = _("for better visibility and online search, please fill here");
}
?>

    var userTrips = [<?php echo $Trips ?>];

    function addanotherappend(newlisting, isImage) {
        var ispblickstr = "";
        var privacyselcted = parseInt($('.uploadalbumselected .logalbumelected li .formcontainercreatealbum').attr('data-value'));
        ispblickstr = "privacyclass_user" + privacyselcted;

        var anotherlistitem = '<div class="formcontainer" style="height: 285px; width:869px;">' +
                '<div class="tableform">' +
                '<div class="UploadMediaLeftTF">' +
                '<label style="display:none"><div id="' + ispblickstr + '" class="privacyclass active" data-value="' + privacyselcted + '"><div class="privacyclassicon_user1 privacyclassicons" name="' + newlisting + '_is_public"></div><div class="privacyclasstxt"></div></div></label>' +
                '<div id="uploadprivacyinfotitleright1" class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Title'); ?></div><div class="uploadinfomandatory uploadinfomandatorytitle"><div>*</div><span></span></div></div>' +
                '<input type="text" name="title" id="' + newlisting + 'title" class="inputuploadformTF"/>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Description'); ?></div><div class="uploadinfomandatory uploadinfomandatorydescription"><div>*</div><span></span></div></div>' +
                '<textarea id="' + newlisting + 'description" name="description" class="inputuploaddescriptionTF" data-value="' + searchdescriptiontext + '" onfocus="removeValue2(this)" onblur="addValue2(this)">' + searchdescriptiontext + '</textarea>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Keywords'); ?></div><div class="uploadinfomandatory uploadinfomandatorykeywords"><div>*</div><span></span></div></div>' +
                '<input id="' + newlisting + 'keywords" type="text" style="margin-bottom:0px;" name="keywords" class="inputuploadformTF" value="' + searchdescriptiontext + '" data-value="' + searchdescriptiontext + '" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
                '<div class="uploadinfohint"><?php echo _('(e.g. nature, restaurant, food)'); ?></div>' +
                '<div class="uploadinfocheckboxcontainer">' +
                '<div class="uploadinfocheckbox uploadinfocheckbox1"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt"> <?php echo _('apply the same description as album'); ?></div></div>' +
                '<div class="uploadinfocheckbox uploadinfocheckbox2" style="margin-top:7px;"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt"> <?php echo _('set as icon for the album'); ?></div></div>' +
                '</div>' +
                '</div>' +
                '<div class="UploadMediaRightTF">' +
                '<div id="uploadprivacyinfotitleright1" class="uploadinfotitle"><div><?php echo _('Category'); ?></div><div class="uploadinfomandatory uploadinfomandatorycategory"><div>*</div><span></span></div></div>' +
                '<select name="category" id="' + newlisting + 'category" class="inputuploadformTF"><?php echo $cat_options ?></select>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Country'); ?></div><div class="uploadinfomandatory uploadinfomandatorycountry"><div>*</div><span></span></div></div>' +
                '<select id="' + newlisting + 'country" name="country" class="inputuploadformTF">' +
                '<option value="0"><?php echo _('Select Country'); ?></option>' +
                '<?php echo $country_options ?>' +
                '</select>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php print _("City"); ?></div><div class="uploadinfomandatory uploadinfomandatorycitynameaccent"><div>*</div><span></span></div></div>' +
                '<input type="text" name="citynameaccent" id="' + newlisting + 'citynameaccent" class="inputuploadformTF" value="<?php echo $citynameaccent; ?>"/>' +
                '<input type="hidden" name="cityname" id="' + newlisting + 'cityname" class="inputuploadformTF" value="<?php echo $cityname; ?>"/>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div>'+ t("Place taken at") +'</div><div class="uploadinfomandatory uploadinfomandatoryplacetakenat"><div>*</div><span></span></div></div>' +
                '<input type="text" name="placetakenat" id="' + newlisting + 'placetakenat" class="inputuploadformTF" value="<?php echo $location_nameaccent ?>" data-value="' + searchdescriptiontext + '" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
                '<input type="hidden" name="location" id="' + newlisting + 'location" value="<?php echo $location_id ?>"/>' +
                '<input type="hidden" name="location_name" id="' + newlisting + 'location_name" value="<?php echo $location_name ?>"/>' +
                '<div id="' + newlisting + 'save" class="SaveButton publishbut" style="top:253px;"><div class="publishbuttxt"><?php echo _('publish'); ?></div></div>' +
                '<div id="' + newlisting + 'save" class="LaterButton publishbut" style="top:253px;"><div class="publishbuttxt"><?php echo _('publish later'); ?></div></div>' +
                '</div>' +
                '<input type="hidden" id="' + newlisting + 'Lat" name="lattitude" value="' + CurrentLocation.latitude + '"/><input type="hidden" id="' + newlisting + 'Long" name="longitude" value="' + CurrentLocation.longitude + '"/>' +
                '<input type="hidden" name="status"/>' +
                '<input type="hidden" name="vid"/>' +
                '<a href="'+generateLangURL('/showthumb.php?videoid=')+'" id="' + newlisting + 'fancy" style="width:0px;height:0px;" class="showthumblink_album"></a>' +
                '</div>' +
                '</div>';

        return anotherlistitem;
    }

    var ACTIVE_UPLOADS = 0;

    function addnewalbumdata(newlisting) {

        var anotherlistitem = '<div class="formcontainercreatealbum">'+t("CREATE ALBUM")+'</div>' +
                '<div class="formcontainer" style="height: 353px; width:869px; top:77px;">' +
                '<div class="tableform" style="height:100%; width:100%;">' +
                '<div class="UploadMediaLeftTF">' +
                '<div id="uploadprivacyinfotitle" class="uploadinfotitle"><?php echo _('privacy'); ?></div>' +
                '<label><div id="privacyclass_user' + USER_PRIVACY_PUBLIC + '" class="privacyclass active" data-value="' + USER_PRIVACY_PUBLIC + '"><div class="privacyclassicon_user' + USER_PRIVACY_PUBLIC + ' privacyclassicons" name="' + newlisting + '_is_public"></div><div class="privacyclasstxt"><?php print addslashes(_("public")); ?></div></div></label><label><div id="privacyclass_user' + USER_PRIVACY_COMMUNITY + '" class="privacyclass" data-value="' + USER_PRIVACY_COMMUNITY + '"><div class="privacyclassicon_user' + USER_PRIVACY_COMMUNITY + ' privacyclassicons" name="' + newlisting + '_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('friends')); ?></div></div></label><label><div id="privacyclass_user' + USER_PRIVACY_PRIVATE + '" class="privacyclass" data-value="' + USER_PRIVACY_PRIVATE + '"><div class="privacyclassicon_user' + USER_PRIVACY_PRIVATE + ' privacyclassicons" name="' + newlisting + '_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('private')); ?></div></div></label><label><div id="privacyclass_user' + USER_PRIVACY_SELECTED + '" class="privacyclass" data-value="' + USER_PRIVACY_SELECTED + '"><div class="privacyclassicon_user' + USER_PRIVACY_SELECTED + ' privacyclassicons" name="' + newlisting + '_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('custom')); ?></div></div></label>' +
                '<div class="uploadinfotitle margintop19"><div><?php echo _('Title'); ?></div><div class="uploadinfomandatory uploadinfomandatorytitle"><div>*</div><span></span></div></div>' +
                '<input type="text" name="title" id="' + newlisting + 'title" class="inputuploadformTF" maxlength="100" value="<?php echo _('title'); ?>..." data-value="<?php echo _('title'); ?>..." onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Description'); ?></div><div class="uploadinfomandatory uploadinfomandatorydescription"><div>*</div><span></span></div></div>' +
                '<textarea id="' + newlisting + 'description" name="description" class="inputuploaddescriptionTF" data-value="description..." onfocus="removeValue2(this)" onblur="addValue2(this)"><?php echo _('description...'); ?></textarea>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Keywords'); ?></div><div class="uploadinfomandatory uploadinfomandatorykeywords"><div>*</div><span></span></div></div>' +
                '<input id="' + newlisting + 'keywords" type="text" style="margin-bottom:0px;" name="keywords" class="inputuploadformTF" value="' + searchdescriptiontext + '" data-value="' + searchdescriptiontext + '" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
                '<div class="uploadinfohint"><?php echo _('(e.g. nature, restaurant, food)'); ?></div>' +
                '</div>' +
                '<div class="UploadMediaRightTF">' +
                '<div class="peoplecontainer peoplecontainer_custom formContainer100 margintop2" style="position:absolute; margin-left:1px; width:415px; display:none; top:10px;">' +
                '<div class="emailcontainer emailcontainer_privacy emailcontainer_privacy_custom" style="width:411px;height:55px;">' +
                '<div class="addmore" style="margin: 0px 3px 3px 0;"><input name="addmoretext" id="addmoretext_privacy" type="text" class="addmoretext_css" data-value="<?php print _("add more"); ?>" value="<?php print _("add more"); ?>" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>' +
                '</div>' +
                '<div class="uploadinfotitle margintop88"><div><?php echo _('Category'); ?></div><div class="uploadinfomandatory uploadinfomandatorycategory"><div>*</div><span></span></div></div>' +
                '<select name="category" id="' + newlisting + 'category" class="inputuploadformTF"><?php echo $cat_options ?></select>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Country'); ?></div><div class="uploadinfomandatory uploadinfomandatorycountry"><div>*</div><span></span></div></div>' +
                '<select id="' + newlisting + 'country" name="country" class="inputuploadformTF">' +
                '<option value="0"><?php echo _('Select Country'); ?></option>' +
                '<?php echo $country_options ?>' +
                '</select>' +
                '<div class="uploadinfotitle margintop15"><div><?php print _("City"); ?></div><div class="uploadinfomandatory uploadinfomandatorycitynameaccent"><div>*</div><span></span></div></div>' +
                '<input type="text" name="citynameaccent" id="' + newlisting + 'citynameaccent" class="inputuploadformTF" value="<?php echo $citynameaccent; ?>"/>' +
                '<input type="hidden" name="cityname" id="' + newlisting + 'cityname" class="inputuploadformTF" value="<?php echo $cityname; ?>"/>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div>'+ t('Place taken at') +'</div><div class="uploadinfomandatory uploadinfomandatoryplacetakenat"><div>*</div><span></span></div></div>' +
                '<input type="text" name="placetakenat" id="' + newlisting + 'placetakenat" class="inputuploadformTF" value="<?php echo $location_nameaccent ?>" data-value="' + searchdescriptiontext + '" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
                '<input type="hidden" name="location" id="' + newlisting + 'location" class="inputuploadformTF" value="<?php echo $location_id ?>"/>' +
                '<input type="hidden" name="location_name" id="' + newlisting + 'location_name" class="inputuploadformTF" value="<?php echo $location_name ?>"/>' +
                '<div id="' + newlisting + 'save" class="SaveNewAlbumButton publishbut" style="top:328px;"><div class="publishbuttxt"><?php echo _('save'); ?></div></div>' +
                '<div id="' + newlisting + 'save" class="ClearNewAlbumButton publishbut" style="top:328px;"><div class="publishbuttxt"><?php echo _('clear'); ?></div></div>' +
                '</div>' +
                '</div>' +
                '</div>';

        return anotherlistitem;
    }
</script>

<script type="text/javascript">
    var txt_id = '<?php echo $txt_id_init; ?>';
    var currentsection = "upload-album";
    var currentlogalbumaddid = "logalbumadd";
    $(document).ready(function() {
        var mystr = addnewalbumdata(currentlogalbumaddid);
        $('.uploadalbum .' + currentlogalbumaddid + ' li').html(mystr);
        addAutoComplete(currentlogalbumaddid);
    });

</script>

<div class="upload-overlay-loading-fix"><div></div></div>
<div class="upload-overlay-loading-fix-file"><div class="loadingicon" data-id=""></div><span><?php echo _('In process'); ?></span></div>
<div class="getalbumuploaddatapics" style="display:none"></div>
<div class="albumuploaddescription" style="display:none"></div>
<div id="MiddleInsideNormal" style="height:auto;">

    <div id="flash_err" style="display: none; position: relative; top: 30px; width: 600px; margin-left: auto; margin-right: auto;">
        <?php echo _('Get the latest version of adobe flash') ?> <a href="http://get.adobe.com/flashplayer/">get.adobe.com/flashplayer/</a>
    </div>
    <div id="InsideNormalup">
        <div id="InsideNormal">
            <div id="uploadheadbar">
                <div id="uploadinsidettlo"><?php echo _('upload'); ?></div>
                <a id="uploadinsidemenu1" class="uploadinsidemenu" href="<?php echo ReturnLink('upload');?>"><?php echo _('image or video');?></a>
                <a id="uploadinsidemenu2" class="uploadinsidemenu active" href="<?php echo ReturnLink('upload-album');?>"><?php echo _('album');?></a>
                <a id="uploadinsidemenu3" class="uploadinsidemenu" href="<?php echo ReturnLink('upload-list');?>"><?php echo _('view my pending list');?></a>
                <div id="uploadinsideicon"></div>
            </div>
            <div id="uploadalbumContent">
                <select id="albumlist" name="albumlist" class="inputuploadformTF" style="width:356px;" onchange="doalbumdatadisplay(this);">
                    <option value="0"><?php echo _('existing (and created) albums'); ?></option>
                    <?php echo $album_options ?>
                </select>
                <div id="uploadnewalbum"></div>
                <div id="uploadnewalbumover">
                    <div id="lefticonover"></div>
                    <div id="uploadnewalbumovertxt"><?php echo _('add album'); ?></div>
                </div>
            </div>
            <form name="UploadVideo" id="UploadVideo" enctype="multipart/form-data">
                <input type="hidden" name="userID" value="<?php echo $user_id; ?>" id="userID"/>
                <input type="hidden" name="S" value="<?php echo session_id(); ?>" id="S"/>

                <ul style="margin-top:-2px;">
                    <li class="uploadalbum" style="display:none; height:0; overflow:hidden;">
                        <ol id="log_2" class="logalbumadd">
                            <li style="height:450px; margin:0;">

                            </li>
                        </ol>
                    </li>
                    <li class="uploadalbumselected" style="display:none; overflow:hidden;">
                        <ol id="log_2" class="logalbumelected">
                            <li style="height:76px; margin:0;">

                            </li>
                        </ol>
                    </li>

                    <div id="real_upload" style="display:none;">
                        <input type="hidden" name="allfiles" id="allfiles" value="">
                    </div>

                    <!--<li class="upload1_2" id="uploadSection" style="display:none">

                        <input type="hidden" name="allfiles" id="allfiles" value="">
                        <div class="deleteddiv"></div>
                        <div class="uploadinsidetxt1"><?php /*echo _('Choose images or videos to upload.'); */?> <span><?php /*echo _('(Choose more than one file by pressing the "Ctrl" key while selecting files).'); */?></span></div>
                        <div class="selectuploadbtn_new" id="swfupload-control1"><input type="button" id="button1" class="UploadButton" value="<?php /*echo _('Select files from your device') */?>"/></div>
                        <div class="uploadinsidetxt2_new" id="dropbox"><?php /*echo _('or drag and drop videos and photos anywhere on this page to start uploading'); */?></div>
                    </li>-->
                    <!--<li>
                            <div class="upload-loading-txt-file marginright10"></div>
                            <div class="upload-loading-sep"> - </div>
                            <div class="stop_all_upload marginleft10"><?php /*echo _('cancel pending uploads');*/?></div>
                    </li>-->
                    <!--<li class="upload2">
                        <ol id="log"></ol>
                    </li>-->
                </ul>
            </form>

        </div>
    </div>

</div>
<?php include("closing-footer.php");?>