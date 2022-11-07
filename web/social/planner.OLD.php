<?php
$path = "";
$tpopular = 5;
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/bag.php" );

$user_id = userGetID();

$user_is_logged = 0;
if (userIsLogged()) {
    $user_is_logged = 1;
}

$fr_txt = "";
$to_txt = "";
$country_code ="";
$state_code ="";
$from_value = "dd / mm / yyyy";
$string_search = xss_sanitize(UriGetArg(0));
$string_search_value = isset($string_search) ? $string_search : '';
$count_day = $tpopular;
if ($string_search_value != '') {
    $string_search_arr =    explode('_',$string_search_value);
    $string_search_value = $string_search_arr[0];
    if ( sizeof($string_search_arr) > 1) {
        $country_code = $string_search_arr[1];
        $country_code = ( $country_code !='' ) ? $country_code : '';
        if ($country_code != '' && sizeof($string_search_arr) > 2) {
            $state_code = $string_search_arr[2];
            $state_code = ( $state_code !='' ) ? $state_code : '';
        }
    }    
    $fr_txt = xss_sanitize(UriGetArg(1));
    $fr_txt = isset($fr_txt) ? $fr_txt : '';
    $from_value = date('d / m / Y', strtotime($fr_txt));
    if ($fr_txt != '') {
        $to_txt = xss_sanitize(UriGetArg(2));
        $to_txt = isset($to_txt) ? $to_txt : '';

        $from_date = date('d/m/Y', strtotime($fr_txt));
        $to_date = date('d/m/Y', strtotime($to_txt));
        $count_day = ( $to_date - $from_date ) + 1;
    }
} else {
    $string_search_value = 'Where to go?';
}
if ($count_day > 14)
    $count_day = 14;

//$query = "SELECT id, title FROM discover_cuisine order by title";
//$ret = db_query($query);
//$cuisines_arr = array();
//while($row = db_fetch_array($ret)){
//    $cuisines_arr[] = $row;
//}
$cuisines_arr = getCuisine();

$includes = array('css/jslider.css', "js/jscal2.js", "js/jscal2.en.js", 'css/jslider.tube.css', 'js/jshashtable-2.1_src.js', 'js/jquery.numberformatter-1.2.3.js', 'js/tmpl.js', 'js/jquery.dependClass-0.1.js', 'js/draggable-0.1.js', 'js/jquery.slider.js', 'js/planner.js', 'css/jscal2.css', 'css/planner.css');

$css_lang_file = $CONFIG ['server']['root'].'css/planner_'.  LanguageGet().'.css';

if( file_exists( $css_lang_file ) && LanguageGet() <>'en' ) $includes[] = 'css/planner_'.  LanguageGet().'.css';

tt_global_set('includes', $includes);

include("TopIndex.php");

?>
<!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?v3&sensor=false"></script>-->
<script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script type="text/javascript" src="//google-maps-utility-library-v3.googlecode.com/svn-history/r290/trunk/infobox/src/infobox_packed.js"></script>
<script src="//google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.9/src/markerwithlabel.js" type="text/javascript"></script>


<script type="text/javascript">
    $(document).ready(function() {
        $(".tpopular").css({left: '<?php echo 50 * ($tpopular - 1) + 3; ?>px'});
<?php if ($count_day > 7) { ?>
            $(".plannerAddDay").click();
<?php } ?>
<?php if ($string_search_value != 'Where to go?') { ?>
            $(".plannerValidateButton").click();
<?php } ?>
        resetDailyBudget();
        $(".myCurrencySelect").change(function() {
            var oldLogo = currencyLogo;
            currencyLogo = $(this).val();
            var value = $(".budgetOutput").html();
            value = value.split(oldLogo).join(currencyLogo);
            $(".budgetOutput").html(value);
            resetDailyBudget();
        });
    });
</script>
<div class="upload-overlay-loading-fix"><div></div></div>
<div class="tocenter">
    <div class="planerContainer">
        <div class="plannerFlightContainer">
            <div class="plannerItinerary" id="plannerItinerary">
                <div class="plannerTitle"><?php print _("Your Destination");?></div>
                <input type="text" id="plannerStationSelect" class="plannerStationSelect" value="<?php echo _($string_search_value); ?>" data-state-code="<?php echo $state_code; ?>" data-code="<?php echo $country_code; ?>" data-city="" data-value="<?php print _("Where to go?");?>" onfocus="removeValue2(this)" onblur="addValue2(this)"/>
                <div class="plannerTitle marginTop8"><?php print _("Your Currency");?></div>
                <select class="myCurrencySelect" name="currency">
                    <option value="$" selected><?php print _("USD");?></option>
                    <option value="&euro;"><?php print _("Euro");?></option>
                </select>
                <div class="plannerTitle marginTop8"><?php print _("Your Budget");?></div>
                <!--<input type="text" data-slider="true" data-slider-theme="tt" data-slider-range="500,19500" data-slider-step="500">-->
                <div class="layout-slider" style="width: 100%">
                    <span style="display: inline-block; width: 198px; padding: 0 5px;"><input id="budgetSlider" type="slider" name="price" value="0;1000" /></span> 
                </div>
                <span class="budgetOutput" data-value="0"></span>
            </div>
            <div class="plannerDate">
                <div class="departDateContainer">
                    <div class="plannerTitleLeft"><?php print _("Departure Date");?></div>
                    <div class="fromDateLabel"><?php print _("from");?></div>
                    <div class="departdate" id="fromtxt" data-value="<?php echo $fr_txt; ?>"><?php echo $from_value; ?></div>
                </div>
                <div class="budgetContainer">
                    <div class="plannerTitleRight"><?php print _("Daily Budget");?></div>
                    <div class="plannerDescRight"></div>
                </div>
                
                <div class="plannerDateValueCont" id="plannerDateValueCont">
                    <div class="plannerTitle"><?php print _("Number of days");?></div>
                    <div class="plannerNbDaysBigContainer">
                        <div class="plannerNbDaysContainer">
                            <div class="plannerNbDaysContainerBlock">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i == $count_day) {
                                        echo '<div class="plannerADay plannerADaySel" data-value="' . $i . '">' . $i . '</div>';
                                    } else {
                                        echo '<div class="plannerADay" data-value="' . $i . '">' . $i . '</div>';
                                    }
                                }
                                ?>
                                <div class="plannerAddDay">+</div>
                            </div>
                            <div class="tpopular"><?php print _("T popular");?></div>
                        </div>
                        <div class="plannerNbDaysContainer2">
                            <div class="plannerNbDaysContainerBlock">
                                <?php
                                for ($i = 6; $i <= 10; $i++) {
                                    if ($i == $count_day) {
                                        echo '<div class="plannerADay plannerADaySel" data-value="' . $i . '">' . $i . '</div>';
                                    } else {
                                        echo '<div class="plannerADay" data-value="' . $i . '">' . $i . '</div>';
                                    }
                                }
                                ?>
                                <div class="plannerRemDay">-</div>
                            </div>
                        </div>
                        <div class="plannerNbDaysContainer3">
                            <div class="plannerNbDaysContainerBlock">
                                <?php
                                for ($i = 11; $i <= 15; $i++) {
                                    if ($i == $count_day) {
                                        echo '<div class="plannerADay plannerADaySel" data-value="' . $i . '">' . $i . '</div>';
                                    } else {
                                        echo '<div class="plannerADay" data-value="' . $i . '">' . $i . '</div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="plannerThemes">
                <div class="plannerTitle" style="margin-bottom: 3px;"><?php print _("Themes");?></div>
                <div class="plannerDesc"><?php print _("What is your interest?");?></div>
                <div class="plannerThemesOptionContainer">
                    <div class="plannerThemesOption" data-value="1"><?php print _("With kids");?></div>
                    <div class="plannerThemesOption" data-value="2"><?php print _("Outdoors");?></div>
                    <div class="plannerThemesOption" data-value="3"><?php print _("Best of");?> </div>
                    <div class="plannerThemesOption" data-value="4"><?php print _("Business");?></div>
                    <div class="plannerThemesOption" data-value="5"><?php print _("Culture");?></div>
                    <div class="plannerThemesOption" data-value="6"><?php print _("Adventure");?></div>
                    <div class="plannerThemesOption" data-value="7"><?php print _("Romantic");?></div>
                    <div class="plannerThemesOption" data-value="8"><?php print _("Gastronomy");?></div>
                </div>
            </div>
        </div>
        <div class="plannerHotelContainer">
            <div class="plannerHotel">
                <div class="plannerHotelInsideContainer">
                    <div class="plannerTitle"><?php print _("Your Hotel");?></div>
                    <div class="plannerDesc"><?php print _("How many stars for your hotel ?");?></div>
                    <div class="starBlock" id="starBlock1" data-value="1">
                        <img src="<?php echo ReturnLink('images/planner/star.png'); ?>" class="star" alt="" />
                    </div>
                    <div class="starBlock" id="starBlock2" data-value="2">
                        <img src="<?php echo ReturnLink('images/planner/star.png'); ?>" class="star" alt=""/><img src="<?php echo ReturnLink('images/planner/star.png'); ?>" alt="" class="star" />
                    </div>
                    <div class="starBlock" id="starBlock3" data-value="3">
                        <img src="<?php echo ReturnLink('images/planner/star.png'); ?>" class="star" alt=""/><img src="<?php echo ReturnLink('images/planner/star.png'); ?>" alt="" class="star" /><img src="<?php echo ReturnLink('images/planner/star.png'); ?>" alt="" class="star" />
                    </div>
                    <div class="starBlock" id="starBlock4" data-value="4">
                        <img src="<?php echo ReturnLink('images/planner/star.png'); ?>" class="star" alt=""/><img src="<?php echo ReturnLink('images/planner/star.png'); ?>" alt="" class="star" /><img src="<?php echo ReturnLink('images/planner/star.png'); ?>" alt="" class="star" /><img src="<?php echo ReturnLink('images/planner/star.png'); ?>" alt="" class="star" />
                    </div>
                    <div class="starBlock" id="starBlock5" data-value="5">
                        <img src="<?php echo ReturnLink('images/planner/star.png'); ?>" class="star" alt=""/><img src="<?php echo ReturnLink('images/planner/star.png'); ?>" alt="" class="star" /><img src="<?php echo ReturnLink('images/planner/star.png'); ?>" alt="" class="star" /><img src="<?php echo ReturnLink('images/planner/star.png'); ?>" alt="" class="star" /><img src="<?php echo ReturnLink('images/planner/star.png'); ?>" alt="" class="star" />
                    </div>
                </div>
            </div>
            <div class="plannerResto">
                <div class="plannerRestoInsideLeftContainer">
                    <div class="plannerTitle"><?php print _("Type of cuisine");?></div>
                    <select style="display:none;" name="foodTypeSelect" id="foodTypeSelect" multiple>
<!--                        <option value="Italian">Italian</option>
                        <option value="Asian">Asian</option>
                        <option value="Lebanese">Lebanese</option>
                        <option value="French">French</option>
                        <option value="Mexican">Mexican</option>-->
                        <?php foreach($cuisines_arr as $cuisine) {?>
                        <option value="<?= $cuisine['id'] ?>"><?= htmlEntityDecode($cuisine['title']) ?></option>
                        <?php } ?>
                    </select>
                    <div class="foodTypeSelect">
                        <?php foreach($cuisines_arr as $cuisine) {?>
                        <div class="foodTypeOpt" data-value="<?= $cuisine['id'] ?>"><?= htmlEntityDecode($cuisine['title']) ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="plannerRestoInsideRightContainer">
                    <div class="plannerDesc"><?php print _("Food Type");?></div>
                    <div class="foodTypeOptionDispCont"></div>
                </div>
            </div>
        </div>
        <div class="plannerValidateButton_container">
            <div class="plannerValidateButton"></div>
            <a href="<?php echo ReturnLink('bag'); ?>" title="<?php print _("bag");?>" target="_blank" class="current_bag" style="display:none"><div class="bagcontainer_planner">
                    <div class="bag_count"></div>
                </div></a>
        </div>

        <div class="search_result_container" data-bag=""></div>

        <div class="plannerBottomBlockContainer">
            <div class="plannerSavedTripsCont">
                <div class="plannerSavedTrips">
                    <div class="plannerBottomBlockContainerTitle"><?php print _("My Saved Trips");?></div>
                    <div class="plannerBottomTrips">Paris by night edit | delete</div>
                    <div class="plannerBottomTrips">Paris by night edit | delete</div>
                    <div class="plannerBottomTrips">India edit | delete</div>
                    <div class="plannerBottomTrips">India edit | delete</div>
                    <div class="plannerBottomTrips">Test edit | delete</div>
                    <div class="plannerBottomTrips">Test edit | delete</div>
                </div>
            </div>
            <div class="plannerSuggestedFriendsTripsCont">
                <div class="plannerSuggestedFriendsTrips">
                    <div class="plannerBottomBlockContainerTitle"><?php print _("Suggested Friends trips");?></div>
                    <div class="plannerBottomTrips">Paris by night view</div>
                    <div class="plannerBottomTrips">Paris by night view</div>
                    <div class="plannerBottomTrips">India view</div>
                    <div class="plannerBottomTrips">India view</div>
                    <div class="plannerBottomTrips">Test view</div>
                    <div class="plannerBottomTrips">Test view</div>
                </div>
            </div>
        </div>
        <div class="sharePopUp" id="sharePopUp">
            <div class="sharePopUpRapper">
                <div class="sharePopUpTitle"><?php print _("Share your saved plan with friends");?></div>
                <div class="sharePopUpSeperator"></div>
                <div class="sharePopUpEnableShareComment">
                    <div class="overdatabutenable log_top_button" data-status="1">
                        <div class="overdatabutntficon"></div>
                        <div class="overdatabutntftxt marginleft12"><?php print _("enable shares & comments");?></div>
                    </div>
                </div>
                <div class="sharePopUpLabel marginTop17"><?php print _("title");?></div>
                <input type="text" name="title" class="sharePopUpTextField" value="paris" />
                <div class="sharePopUpShareBtn"><?php echo _('share'); ?></div>
                <div class="sharePopUpLabel marginTop17"><?php print _("write something");?></div>
                <textarea class="sharePopUpTextArea" onfocus="removeValue2(this)" onblur="addValue2(this)" data-value="<?php print _("write something ...");?>"><?php print _("write something ...");?></textarea>
                <div class="sharePopUpLabel marginTop17"><?php print _("add people (T tubers, emails)");?></div>

                <div id="privacy_picker" class="privacy_picker displaynone">
                    <div class="peoplecontainer formContainer100 marginTop4">
                        <div class="emailcontainer emailcontainer_privacy">
                            <div class="addmore"><input name="addmoretext" id="addmoretext_privacy" type="text" class="addmoretext_css" data-value="<?php print _("add more");?>" value="<?php print _("add more");?>" onfocus="removeValue2(this)" onblur="addValue2(this)" data-id=""/></div>
                        </div>                        
                    </div>
                </div>
                <div class="account-btn-container">
                    <div class="account-btn-cancel" onclick="" ><?php echo _('cancel'); ?></div>
                    <div class="account-btn-seperator"></div>
                    <div class="account-btn-save" id="saveabout"><?php echo _('save'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include("BottomIndex.php");