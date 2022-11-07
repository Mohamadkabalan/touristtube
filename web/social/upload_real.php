<?php

$bootOptions = array("loadDb" => 1, "loadLocation" => 1, "requireLogin" => 1);
$path = '';
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );

$CountryIp = $_SERVER["REMOTE_ADDR"];
$CountryName = getCountryFromIP($CountryIp, "NamE");
$CountryCode = getCountryFromIP($CountryIp, "code");
//debug($CountryName);
//debug($CountryCode);
$user_id = userGetID();

if($user_id == ''){
    header('location: ' . ReturnLink('register') );
}
$userIschannel=userIsChannel();
$userIschannel = ($userIschannel) ? header("Location: " . ReturnLink('register')) : 0;

$session_id = $_SESSION['ssid'];
//$query='select * from cms_social_newsfeed ';
//$cur_date = date('Y-m-d'); debug($cur_date);
$includes = array('media'=>'css_media_query/media_style.css',
    'media1'=>'css_media_query/upload.css',
    'css/jquery.fancybox-new.css',
    'css/upload.css',
    'css/real_uploader/style.css',
    'js/autocomplete-city.js',
    'js/jquery.corner.js',
    'js/real_ajax_uploader/realAjaxUploader.js',
    'js/real_ajax_uploader/uploader.js');

    //'js/real_ajax_uploader/uploader.js');
    //'js/real_ajax_uploader/real_ajaxupoald.js');

tt_global_set('includes', $includes);

include("TopIndex.php");
?>
<script type="text/javascript">
    var searchdescriptiontext="<?php echo _('for better visibility and online search, please fill here');?>";
    <?php

    $location_id = '';
    $location_name = '';
    $location_nameaccent = '';
    $cityname = '';
    $citynameaccent = '';
    $category_id = -1;

    if(isset($_REQUEST['location'])){
        $location_id = intval($_REQUEST['location']);
        $location = locationGet($location_id);
        if($location != false){
            $location_name = $location['name'];
            $location_nameaccent = $location['accent_name'];

            $cityRow = cityGetInfo($location['city_id']);
            $cityname = $cityRow['name'];
            $citynameaccent = $cityRow['accent'];
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

    $cats = categoryGetHash(array('hide_all' => true));
    $cat_options = '';
    $cat_options .= '<option value="0" selected="selected">'._('Select Category').'</option>';
    foreach($cats as $id => $title){
        $selected = ($category_id == $id) ? ' selected="selected" ' : '';
        $cat_options .= "<option value=\"{$id}\" {$selected}>".addslashes($title).'</option>';
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
        $location_nameaccent=_('for better visibility and online search, please fill here');
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

    var userTrips = [<?php echo $Trips ?>];

    function addanotherappend(newlisting, isImage){

        var saveButtonText;
        if(isImage){
            saveButtonText = 'Save Photo Info';
        }else{
            saveButtonText = 'Save Video Info';
        }
        //'+CurrentLocation.address+' value from placetakenat
        //'+CurrentLocation.address+' value from keywords
        var anotherlistitem = '<div class="formcontainer" style="height: 405px; width:869px; top:77px;">'+
            '<div class="tableform" style="height:100%; width:100%;">'+
            '<div class="UploadMediaLeftTF">'+
            '<div id="uploadprivacyinfotitle" class="uploadinfotitle"><?php echo _('privacy');?></div>'+
            '<label><div id="privacyclass_user'+USER_PRIVACY_PUBLIC+'" class="privacyclass active" data-value="'+USER_PRIVACY_PUBLIC+'"><div class="privacyclassicon_user'+USER_PRIVACY_PUBLIC+' privacyclassicons" name="'+newlisting+'_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('public'));?></div></div></label><label><div id="privacyclass_user'+USER_PRIVACY_COMMUNITY+'" class="privacyclass" data-value="'+USER_PRIVACY_COMMUNITY+'"><div class="privacyclassicon_user'+USER_PRIVACY_COMMUNITY+' privacyclassicons" name="'+newlisting+'_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('friends'));?></div></div></label><label><div id="privacyclass_user'+USER_PRIVACY_PRIVATE+'" class="privacyclass" data-value="'+USER_PRIVACY_PRIVATE+'"><div class="privacyclassicon_user'+USER_PRIVACY_PRIVATE+' privacyclassicons" name="'+newlisting+'_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('private'));?></div></div></label><label><div id="privacyclass_user'+USER_PRIVACY_SELECTED+'" class="privacyclass" data-value="'+USER_PRIVACY_SELECTED+'"><div class="privacyclassicon_user'+USER_PRIVACY_SELECTED+' privacyclassicons" name="'+newlisting+'_is_public"></div><div class="privacyclasstxt"><?php echo addslashes(_('custom'));?></div></div></label>' +
            '<div class="uploadinfotitle margintop19"><div><?php print _("title");?></div><div class="uploadinfomandatory uploadinfomandatorytitle"><div>*</div><span></span></div></div>'+
            '<input type="text" name="title" id="'+newlisting+'title" class="inputuploadformTF" maxlength="100" value="<?php print _("title...");?>" data-value="<?php print _("title...");?>" onfocus="removeValue2(this)" onblur="addValue2(this)"/>'+
            '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Description');?></div><div class="uploadinfomandatory uploadinfomandatorydescription"><div>*</div><span></span></div></div>'+
            '<textarea id="'+newlisting+'description" name="description" class="inputuploaddescriptionTF" data-value="<?php echo _('description...');?>" onfocus="removeValue2(this)" onblur="addValue2(this)"><?php print _("description...");?></textarea>' +
            '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Keywords');?></div><div class="uploadinfomandatory uploadinfomandatorykeywords"><div>*</div><span></span></div></div>'+
            '<input id="'+newlisting+'keywords" type="text" style="margin-bottom:0px;" name="keywords" class="inputuploadformTF" value="'+searchdescriptiontext+'" data-value="'+searchdescriptiontext+'" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
            '<div class="uploadinfohint"><?php echo _('(e.g. nature, restaurant, food)');?></div>' +
            '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Add to album'); ?></div><div class="uploadinfomandatory uploadinfomandatorykeywords"><div>*</div><span></span></div></div>'+
            '<select name="catalog_id" class="catalog_class inputuploadformTF"><option value="0" ><?php echo _("select album"); ?> </option><?php echo $album_options ?></select>'+
            '<div class="uploadinfocheckboxcontainer" style="margin-top:24px;">' +
            '<div class="uploadinfocheckbox uploadinfocheckbox_apply4 displanone"><div class="uploadinfocheckboxpic"></div><div class="uploadinfocheckboxtxt"> <?php print _("apply the same description to all checked entries");?></div></div>'+
            '</div>'+
            '</div>'+
            '<div class="UploadMediaRightTF">'+
            '<div class="peoplecontainer peoplecontainer_custom formContainer100 margintop2" style="position:absolute; margin-left:1px; width:415px; display:none; top:10px;">'+
            '<div class="emailcontainer emailcontainer_privacy emailcontainer_privacy_custom" style="width:411px;height:55px;">'+
            '<div class="addmore" style="margin: 0px 3px 3px 0;"><input name="addmoretext" id="addmoretext_privacy" type="text" class="addmoretext_css" value="<?php echo _('add more');?>" data-value="<?php echo _('add more');?>" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div></div>'+
            '</div>'+
            '<div class="uploadinfotitle margintop88"><div><?php echo _("Category");?></div><div class="uploadinfomandatory uploadinfomandatorycategory"><div>*</div><span></span></div></div>'+
            '<select name="category" id="'+newlisting+'category" class="inputuploadformTF"><?php echo $cat_options ?></select>'+
            '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Country');?></div><div class="uploadinfomandatory uploadinfomandatorycountry"><div>*</div><span></span></div></div>' +
            '<select id="'+newlisting+'country" name="country" class="inputuploadformTF">' +
            '<option value="0"><?php echo _('Select Country');?></option>'+
            '<?php echo $country_options ?>' +
            '</select>' +
            '<div class="uploadinfotitle margintop15"><div><?php echo _("City");?></div><div class="uploadinfomandatory uploadinfomandatorycitynameaccent"><div>*</div><span></span></div></div>' +
            '<input type="text" name="citynameaccent" id="'+newlisting+'citynameaccent" class="inputuploadformTF" value="<?php echo $citynameaccent; ?>"/>' +
            '<input type="hidden" name="cityname" id="'+newlisting+'cityname" class="inputuploadformTF" value="<?php echo $cityname; ?>"/>' +
            '<div class="uploadinfotitle uploadpicinfotxt"><div><?php echo _('Place taken at');?></div><div class="uploadinfomandatory uploadinfomandatoryplacetakenat"><div>*</div><span></span></div></div>' +
            '<input type="text" name="placetakenat" id="'+newlisting+'placetakenat" class="inputuploadformTF" value="<?php echo $location_nameaccent ?>" data-value="'+searchdescriptiontext+'" onfocus="removeValue2(this)" onblur="addValue2(this)"/>' +
            '<input type="hidden" name="location" id="'+newlisting+'location" value="<?php echo  $location_id ?>"/>' +
            '<input type="hidden" name="location_name" id="'+newlisting+'location_name" value="<?php echo  $location_name ?>"/>' +
                //'<input style="position: relative; top:20px;" type="button" id="'+newlisting+'save" value="'+saveButtonText+'" disabled="disabled" class="SaveButton"/>'+
            '<div id="'+newlisting+'save" class="SaveButton publishbut" style="top:350px;"><div class="publishbuttxt"><?php echo _('publish');?></div></div>'+
            '<div id="'+newlisting+'save" class="LaterButton publishbut" style="top:350px;"><div class="publishbuttxt"><?php echo _('publish later');?></div></div>'+
            '</div>'+
            '<input type="hidden" id="'+newlisting+'Lat" name="lattitude" value="'+CurrentLocation.latitude+'"/><input type="hidden" id="'+newlisting+'Long" name="longitude" value="'+CurrentLocation.longitude+'"/>'+
            '<input type="hidden" name="status"/>' +
            '<input type="hidden" name="vid"/>' +
            '<a href="'+generateLangURL('/showthumb.php?videoid=')+'" id="'+newlisting+'fancy" style="width:0px;height:0px;" class="showthumblink_album"></a>'+
            '</div>'+
            '</div>';

        return anotherlistitem;
    }

    var ACTIVE_UPLOADS = 0;

</script>

<script type="text/javascript">
    var currentsection="upload";
    var txt_id = 0;
    $(document).ready(function(){
        var file,type,fid,item,response,thumb;
    });

</script>



<div class="upload-overlay-loading-fix"><div></div></div>
<div class="upload-overlay-loading-fix-file"><div class="loadingicon" data-id=""></div><span><?php echo _('In process');?></span></div>
<div id="MiddleInsideNormal" style="height:auto;">

    <div id="flash_err" style="display: none; position: relative; top: 30px; width: 600px; margin-left: auto; margin-right: auto;">
        <?php echo _('Get the latest version of adobe flash') ?> <a href="http://get.adobe.com/flashplayer/">get.adobe.com/flashplayer/</a>
    </div>
    <div id="InsideNormalup">
        <div id="InsideNormal">
            <div id="uploadheadbar">
                <div id="uploadinsidettlo"><?php echo _('upload');?></div>
                <a id="uploadinsidemenu1" class="uploadinsidemenu active" href="<?php echo ReturnLink('upload');?>"><?php echo _('image or video');?></a>
                <a id="uploadinsidemenu2" class="uploadinsidemenu" href="<?php echo ReturnLink('upload-album');?>"><?php echo _('album');?></a>
                <a id="uploadinsidemenu3" class="uploadinsidemenu" href="<?php echo ReturnLink('upload-list');?>"><?php echo _('view my pending list');?></a>
                <div id="uploadinsideicon"></div>
            </div>

            <form name="UploadVideo" id="UploadVideo" enctype="multipart/form-data">
                <input type="hidden" name="userID" value="<?php echo $user_id; ?>" id="userID"/>
                <input type="hidden" name="S" value="<?php echo session_id(); ?>" id="S"/>

                <div id="real_upload">
                    <input type="hidden" name="allfiles" id="allfiles" value="">
                </div>

                <!--<ul style="display: none;">
                    <li class="upload1_2" id="uploadSection">

                        <input type="hidden" name="allfiles" id="allfiles" value="">
                        <div class="deleteddiv"></div>-->


                        <!--<div class="uploadinsidetxt1"><?php /*echo _('Choose images or videos to upload. <span>(Choose more than one file by pressing the "Ctrl" key while selecting files).');*/?></span></div>-->


                        <!--<div class="selectuploadbtn_new" id="swfupload-control1">
                            <input type="button" id="button1" class="UploadButton" value="<?php /*echo _('Select files from your computer') */?>"/>
                        </div>-->



                        <!--<div class="uploadinsidetxt2_new" id="dropbox"><?php /*echo _('or drag and drop videos and photos anywhere on this page to start uploading');*/?></div>-->
                    <!--</li>
                    <li>
                        <div class="upload-loading-txt-file marginright10"></div>
                        <div class="upload-loading-sep"> - </div>
                        <div class="stop_all_upload marginleft10"><?php /*echo _('cancel pending uploads');*/?></div>
                    </li>
                    <li class="upload2">
                        <ol id="log"></ol>
                    </li>
                </ul>-->
            </form>
        </div>
    </div>

</div>
<style>
    .ax-main-container a.ax-upload-all, a.ax-clear{
        display:none;
    }
</style>
<script>
    /**
     * Created by rishav on 27/7/15.
     */
</script>
<?php include("closing-footer.php");?>
