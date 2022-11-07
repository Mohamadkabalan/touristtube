<?php
$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 1);
$path = '';
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );

$user_id = userGetID();

$includes = array('css/upload.css', 'css/upload-list.css', 
  'media'=>'css_media_query/media_style.css?v='.MQ_MEDIA_STYLE_CSS_V,
  'media1'=>'css_media_query/upload-list.css?v='.MQ_UPLOAD_CSS_V,
  'js/upload-list.js', 'js/autocomplete-city.js');

tt_global_set('includes', $includes);

$userIschannel = userIsChannel();
$userIschannel = ($userIschannel) ? header("Location: " . ReturnLink('register')) : 0;

include("TopIndex.php");

//$CountryIp = $_SERVER["REMOTE_ADDR"];
$CountryIp = $request->server->get('REMOTE_ADDR', '');
$CountryName = getCountryFromIP($CountryIp, "NamE");
$CountryCode = getCountryFromIP($CountryIp, "code");

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
        $location_name = addslashes($location['name']);
        $location_nameaccent = addslashes($location['accent_name']);

        $cityRow = cityGetInfo($location['city_id']);
        $cityname = addslashes($cityRow['name']);
        $citynameaccent = addslashes($cityRow['accent']);
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
    $country_options .= '<option value="' . $cc . '"' . $Selected . '>' . str_replace("'", "\'", $name) . '</option>';
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
    $location_nameaccent = _('for better visibility and online search, please fill here');
}

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
    $album_options.='<option id="' . $mydata['id'] . '" value="' . $mydata['id'] . '">' . addslashes($mydata['catalog_name']) . '</option>';
}
?>
<script type="text/javascript">

    function addanotherappend(newlisting) {

        var anotherlistitem = '<div id="' + newlisting + '" class="formcontainer" style="position:relative; height: 353px; width:870px; margin:0; padding:0;">' +
                '<div class="tableform" style="position:relative; margin:0; padding:0; margin-top:13px;">' +
                '<div class="UploadMediaLeftTF">' +
                '<div id="uploadprivacyinfotitle" class="uploadinfotitle"><?php echo addslashes(_('privacy')); ?></div>' +
                '<label><div id="privacyclass_user' + USER_PRIVACY_PUBLIC + '" class="privacyclass active" data-value="' + USER_PRIVACY_PUBLIC + '"><div class="privacyclassicon_user' + USER_PRIVACY_PUBLIC + ' privacyclassicons" name="' + newlisting + '_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('public')); ?></div></div></label>' +
                '<div class="uploadinfotitle margintop19"><div><?php echo addslashes(_('Title')); ?></div><div class="uploadinfomandatory uploadinfomandatorytitle"><div>*</div><span></span></div></div>' +
                '<input type="text" name="title" id="' + newlisting + 'title" class="inputuploadformTF" maxlength="100" value="<?php echo addslashes(_('title...')); ?>" data-value="<?php echo addslashes(_('title')); ?>..." onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Description')); ?></div><div class="uploadinfomandatory uploadinfomandatorydescription"><div>*</div><span></span></div></div>' +
                '<textarea id="' + newlisting + 'description" name="description" class="inputuploaddescriptionTF" data-value="<?php echo addslashes(_('description...')); ?>" onfocus="removeValue2(this)" onblur="addValue2(this)"><?php echo addslashes(_('description...')); ?></textarea>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Keywords')); ?></div><div class="uploadinfomandatory uploadinfomandatorykeywords"><div>*</div><span></span></div></div>' +
                '<input id="' + newlisting + 'keywords" type="text" style="margin-bottom:0px;" name="keywords" class="inputuploadformTF" value="' + searchdescriptiontext + '" data-value="' + searchdescriptiontext + '" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
                '<div class="uploadinfohint"><?php echo addslashes(_('(e.g. nature, restaurant, food)')); ?></div>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Add to album')); ?></div><div class="uploadinfomandatory uploadinfomandatorykeywords"><div>*</div><span></span></div></div>' +
                '<select name="catalog_id" class="catalog_class inputuploadformTF"><option value="0" ><?php echo addslashes(_("select album")); ?> </option><?php echo $album_options ?></select>' +
                '<div class="uploadinfocheckboxcontainer" style="margin-top:9px;">' +
                '<div class="uploadinfocheckbox uploadinfocheckbox1"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt"> <?php echo addslashes(_('apply the same description to all uploaded entries')); ?></div></div>' +
                '</div>' +
                '<div class="error_valid"></div>' +
                '</div>' +
                '<div class="UploadMediaRightTF">' +
                '<div class="peoplecontainer peoplecontainer_custom formContainer100 margintop2" style="position:absolute; margin-left:1px; width:415px; display:none; top:10px;">' +
                '<div class="emailcontainer emailcontainer_privacy emailcontainer_privacy_custom" style="width:411px;height:55px;">' +
                '<div class="addmore" style="margin: 0px 3px 3px 0;"><input name="addmoretext" id="addmoretext_privacy" type="text" class="addmoretext_css" value="<?php echo addslashes(_('add more')) ?>" data-value="<?php echo addslashes(_('add more')) ?>" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>' +
                '</div>' +
                '<div class="uploadinfotitle margintop61"><div><?php echo addslashes(_("Category")); ?></div><div class="uploadinfomandatory uploadinfomandatorycategory"><div>*</div><span></span></div></div>' +
                '<select name="category" id="' + newlisting + 'category" class="inputuploadformTF"><?php echo $cat_options ?></select>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Country')); ?></div><div class="uploadinfomandatory uploadinfomandatorycountry"><div>*</div><span></span></div></div>' +
                '<select id="' + newlisting + 'country" name="country" class="inputuploadformTF">' +
                '<option value="0"><?php echo addslashes(_('Select Country')); ?></option>' +
                '<?php echo $country_options ?>' +
                '</select>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php print _("City"); ?></div><div class="uploadinfomandatory uploadinfomandatorycitynameaccent"><div>*</div><span></span></div></div>' +
                '<input type="text" name="citynameaccent" id="' + newlisting + 'citynameaccent" class="inputuploadformTF" value="<?php echo $citynameaccent; ?>"/>' +
                '<input type="hidden" name="cityname" id="' + newlisting + 'cityname" class="inputuploadformTF" value="<?php echo $cityname; ?>"/>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Place taken at')); ?></div><div class="uploadinfomandatory uploadinfomandatoryplacetakenat"><div>*</div><span></span></div></div>' +
                '<input type="text" name="placetakenat" id="' + newlisting + 'placetakenat" class="inputuploadformTF" value="<?php echo $location_nameaccent ?>" data-value="' + searchdescriptiontext + '" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
                '<input type="hidden" name="location" id="' + newlisting + 'location" value="<?php echo $location_id ?>"/>' +
                '<input type="hidden" name="location_name" id="' + newlisting + 'location_name" value="<?php echo $location_name ?>"/>' +
                '<div id="' + newlisting + 'save" class="publishbut" style="top:365px;"><div class="LaterButton publishbuttxt"><?php echo addslashes(_('publish later')); ?></div><div id="' + newlisting + 'savein" class="SaveButton publishbuttxt"><?php echo addslashes(_('publish')); ?></div>' +
                '</div>' +
                '<input type="hidden" id="' + newlisting + 'Lat" name="lattitude" value="' + CurrentLocation.latitude + '"/><input type="hidden" id="' + newlisting + 'Long" name="longitude" value="' + CurrentLocation.longitude + '"/>' +
                '<input type="hidden" name="status"/>' +
                '<input type="hidden" name="vid"/>' +
                '<a href="'+generateLangURL('/showthumb.php?videoid=')+'" id="' + newlisting + 'fancy" style="width:0px;height:0px;"></a>' +
                '</div>' +
                '</div>';
        return anotherlistitem;
    }



    function addnewalbumdata(newlisting) {
        var ispblickstr = "";
        var privacyselcted = parseInt($('#uploadlistpopuppublishalbum .formcontainer').attr('data-value'));
        ispblickstr = "privacyclass_user" + privacyselcted;

        var anotherlistitem = '<div id="' + newlisting + '" class="formcontainer" style="position:relative; height: 276px; width:870px; margin:0; padding:0;">' +
                '<div class="tableform" style="position:relative; margin:0; padding:0; margin-top:13px;">' +
                '<div class="UploadMediaLeftTF">' +
                '<label style="display:none"><div id="' + ispblickstr + '" class="privacyclass active" data-value="' + privacyselcted + '"><div class="privacyclassicon_user1 privacyclassicons" name="' + newlisting + '_is_public"></div><div class="privacyclasstxt"></div></div></label>' +
                '<div id="uploadprivacyinfotitleright1" class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Title')); ?></div><div class="uploadinfomandatory uploadinfomandatorytitle"><div>*</div><span></span></div></div>' +
                '<input type="text" name="title" id="' + newlisting + 'title" class="inputuploadformTF"/>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Description')); ?></div><div class="uploadinfomandatory uploadinfomandatorydescription"><div>*</div><span></span></div></div>' +
                '<textarea id="' + newlisting + 'description" name="description" class="inputuploaddescriptionTF" data-value="' + searchdescriptiontext + '" onfocus="removeValue2(this)" onblur="addValue2(this)">' + searchdescriptiontext + '</textarea>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Keywords')); ?></div><div class="uploadinfomandatory uploadinfomandatorykeywords"><div>*</div><span></span></div></div>' +
                '<input id="' + newlisting + 'keywords" type="text" style="margin-bottom:0px;" name="keywords" class="inputuploadformTF" value="' + searchdescriptiontext + '" data-value="' + searchdescriptiontext + '" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
                '<div class="uploadinfohint"><?php echo addslashes(_('(e.g. nature, restaurant, food)')) ?></div>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Move to album')); ?></div><div class="uploadinfomandatory uploadinfomandatorykeywords"><div>*</div><span></span></div></div>' +
                '<select name="catalog_id" class="catalog_class inputuploadformTF"><option value="0" ><?php echo addslashes(_("select album")); ?> </option><?php echo $album_options ?></select>' +
                '<div class="uploadinfocheckboxcontainer">' +
                '<div class="uploadinfocheckbox uploadinfocheckbox1"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt"> <?php echo addslashes(_('apply the same description as album')); ?></div></div>' +
                '<div class="uploadinfocheckbox uploadinfocheckbox2" style="margin-top:7px;"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt"> <?php echo addslashes(_('set as icon for the album')) ?></div></div>' +
                '</div>' +
                '</div>' +
                '<div class="UploadMediaRightTF">' +
                '<div id="uploadprivacyinfotitleright1" class="uploadinfotitle"><div><?php echo addslashes(_("Category")); ?></div><div class="uploadinfomandatory uploadinfomandatorycategory"><div>*</div><span></span></div></div>' +
                '<select name="category" id="' + newlisting + 'category" class="inputuploadformTF"><?php echo $cat_options ?></select>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Country')); ?></div><div class="uploadinfomandatory uploadinfomandatorycountry"><div>*</div><span></span></div></div>' +
                '<select id="' + newlisting + 'country" name="country" class="inputuploadformTF">' +
                '<option value="0"><?php echo addslashes(_('Select Country')) ?></option>' +
                '<?php echo $country_options ?>' +
                '</select>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php print addslashes(_("City")); ?></div><div class="uploadinfomandatory uploadinfomandatorycitynameaccent"><div>*</div><span></span></div></div>' +
                '<input type="text" name="citynameaccent" id="' + newlisting + 'citynameaccent" class="inputuploadformTF" value="<?php echo $citynameaccent; ?>"/>' +
                '<input type="hidden" name="cityname" id="' + newlisting + 'cityname" class="inputuploadformTF" value="<?php echo $cityname; ?>"/>' +
                '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo addslashes(_('Place taken at')) ?></div><div class="uploadinfomandatory uploadinfomandatoryplacetakenat"><div>*</div><span></span></div></div>' +
                '<input type="text" name="placetakenat" id="' + newlisting + 'placetakenat" class="inputuploadformTF" value="<?php echo $location_nameaccent ?>" data-value="' + searchdescriptiontext + '" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
                '<input type="hidden" name="location" id="' + newlisting + 'location" value="<?php echo $location_id ?>"/>' +
                '<input type="hidden" name="location_name" id="' + newlisting + 'location_name" value="<?php echo $location_name ?>"/>' +
                '<div id="' + newlisting + 'save" class="publishbut" style="top:305px"><div class="LaterButton publishbuttxt"><?php echo addslashes(_('publish later')) ?></div><div id="' + newlisting + 'savein" class="SaveButton publishbuttxt"><?php echo addslashes(_('publish')) ?></div></div>' +
                '</div>' +
                '<input type="hidden" id="' + newlisting + 'Lat" name="lattitude" value="' + CurrentLocation.latitude + '"/><input type="hidden" id="' + newlisting + 'Long" name="longitude" value="' + CurrentLocation.longitude + '"/>' +
                '<input type="hidden" name="status"/>' +
                '<input type="hidden" name="vid"/>' +
                '<a href="'+generateLangURL('/showthumb.php?videoid=')+'" id="' + newlisting + 'fancy" style="width:0px;height:0px;" class="showthumblink_album"></a>' +
                '</div>' +
                '</div>';

        return anotherlistitem;
    }
</script>

<div class="upload-overlay-loading-fix"><div></div></div>
<div class="upload-overlay-loading-fix-file"><div class="loadingicon" data-id=""></div><span><?php echo _('In process'); ?></span></div>
<div id="uploadlistpopuppublish" style="display:none"></div>
<div id="uploadlistpopuppublishalbum" style="display:none"></div>
<div id="uploadlistpopuppublishalbumhidden" style="display:none"></div>
<div id="uploadlistpopuppublishalbumpic" style="display:none"></div>
<div id="uploadlistfiletosave" style="display:none"></div>
<div id="MiddleInsideNormal" style="height:auto;">
    <div id="InsideNormalup">
        <div id="InsideNormal">
            <div id="uploadheadbar">
                <div id="uploadinsidettlo"><?php echo _('upload'); ?></div>
                <a id="uploadinsidemenu1" class="uploadinsidemenu" href="<?php echo ReturnLink('upload');?>"><?php echo _('image or video');?></a>
                <a id="uploadinsidemenu2" class="uploadinsidemenu" href="<?php echo ReturnLink('upload');?>"><?php echo _('album');?></a>
                <a id="uploadinsidemenu3" class="uploadinsidemenu active" href="<?php echo ReturnLink('upload');?>"><?php echo _('view my pending list');?></a>
                <div id="uploadinsideicon"></div>
            </div>  
            <div id="uploadlistmenucontainer">
                <div id="uploadlistmenuicon"></div>
                <div id="uploadlistmenuimg"></div>
                <div id="uploadlistmenubk"></div>
                <div id="uploadlistmenucontent">
                    <div id="1" class="uploadlistmenu"><?php echo _('my pending photos'); ?></div>
                    <div class="uploadlistmenuseperator"></div>
                    <div id="2" class="uploadlistmenu"><?php echo _('my pending videos'); ?></div>
                    <div class="uploadlistmenuseperator"></div>
                    <div id="3" class="uploadlistmenu"><?php echo _('my pending albums'); ?></div>            	
                </div>
            </div>
            <div id="uploadlistalbumContent" style="display:none;">
                <select id="albumlist" name="albumlist" class="inputuploadformTF" style="width:356px;" onchange="dolistalbumdatadisplay(this);">

                </select>
                <div class="closeSignHint"> = <?php echo _('remove'); ?></div>
                <div id="uploadlistalbumContentpics">

                </div>
            </div>
            <div id="uploadlistcontainer">            	
            </div>    
        </div>
    </div>
</div>
<?php include("closing-footer.php");?>