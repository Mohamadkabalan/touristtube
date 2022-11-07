/*var is_chrome = navigator.userAgent.indexOf('Chrome') > -1;
var is_explorer = navigator.userAgent.indexOf('MSIE') > -1;
var is_firefox = navigator.userAgent.indexOf('Firefox') > -1;
var is_safari = navigator.userAgent.indexOf("Safari") > -1;
var is_Opera = navigator.userAgent.indexOf("Presto") > -1;
if ((is_chrome) && (is_safari)) {
    is_safari = false;
}*/

var ConfirmMessage; 
var _gaq = _gaq || [];
var pagename = "";
var AC_FL_RunContent = 1;
var _SelectedUsers = new Array();
var _SelectedUsersDetailed = new Array();
var _SelectedUsers_zero = new Array();
var _SelectedUsers_first = new Array();
var _SelectedUsers_second = new Array();
var _SelectedUsers_third = new Array();
var _SelectedUsers_forth = new Array();
var _SelectedUsers_fifth = new Array();
var _SelectedConnections = new Array();
var _SelectedSponsors = new Array();
var _SelectedLogs = new Array();
var _SelectedCanJoin = new Array();

var SOCIAL_ENTITY_MEDIA = 1;
/**
 * cms_users entity
 */
var SOCIAL_ENTITY_USER = 2;
/**
 * cms_users_catalogs entity
 */
var SOCIAL_ENTITY_ALBUM = 3;
/**
 * cms_webcams entity
 */
var SOCIAL_ENTITY_WEBCAM = 4;
/**
 * cms_locations entity
 */
var SOCIAL_ENTITY_LOCATION = 5;
/**
 * cms_journals entity
 */
var SOCIAL_ENTITY_JOURNAL = 6;
/**
 * cms_journals_items entity
 */
var SOCIAL_ENTITY_JOURNAL_ITEM = 7;
/**
 * cms_flash entity
 */
var SOCIAL_ENTITY_FLASH = 8;
/**
 * cms_social_comments entity
 */
var SOCIAL_ENTITY_COMMENT = 9;
/**
 * cms_social_shares entity
 */
var SOCIAL_ENTITY_SHARE = 10;
/**
 * cms_channel_news entity
 */
var SOCIAL_ENTITY_NEWS = 11;
/**
 * cms_channel_events entity
 */
var SOCIAL_ENTITY_EVENTS = 12;
/**
 * cms_channel_brochure entity
 */
var SOCIAL_ENTITY_BROCHURE = 13;
/**
 * cms_channel
 */
var SOCIAL_ENTITY_CHANNEL = 14;
/**
 * cms_posts
 */
var SOCIAL_ENTITY_POST = 15;
/**
 * cms_channel_events location
 */
var SOCIAL_ENTITY_EVENTS_LOCATION = 16;
/**
 * cms_channel_events date
 */
var SOCIAL_ENTITY_EVENTS_DATE = 17;
/**
 * cms_channel_events time
 */
var SOCIAL_ENTITY_EVENTS_TIME = 18;
/**
 * cms_channel cover photo
 */
var SOCIAL_ENTITY_CHANNEL_COVER = 19;
/**
 * cms_channel profile image
 */
var SOCIAL_ENTITY_CHANNEL_PROFILE = 20;
/**
 * cms_channel slogan
 */
var SOCIAL_ENTITY_CHANNEL_SLOGAN = 21;
/**
 * cms_channel info
 */
var SOCIAL_ENTITY_CHANNEL_INFO = 22;
/**
 * cms_users_detail profile image
 */
var SOCIAL_ENTITY_USER_PROFILE = 23;
/**
 * cms_users_event 
 */
var SOCIAL_ENTITY_USER_EVENTS = 24;
/**
 * cms_friends 
 */
var SOCIAL_ENTITY_PROFILE_FRIENDS = 25;
/**
 * cms_subscriptions 
 */
var SOCIAL_ENTITY_PROFILE_FOLLOWERS = 26;
/**
 * cms_subscriptions 
 */
var SOCIAL_ENTITY_PROFILE_FOLLOWINGS = 27;
/**
 * discover_hotels 
 */
var SOCIAL_ENTITY_HOTEL = 28;
/**
 * discover_restaurants 
 */
var SOCIAL_ENTITY_RESTAURANT = 29;
/**
 *  discover_poi
 */
var SOCIAL_ENTITY_LANDMARK = 30;
/**
 *  cms_bag
 */
var SOCIAL_ENTITY_BAG = 31;
/**
 * cms_users 
 */
var SOCIAL_ENTITY_PROFILE_ABOUT = 32;
/**
 * cms_users 
 */
var SOCIAL_ENTITY_PROFILE_ACCOUNT = 33;
/**
 * cms_users 
 */
var SOCIAL_ENTITY_PROFILE_LOCATION = 34;
/**
 * cms_users_visited_places 
 */
var SOCIAL_ENTITY_PROFILE_VISITED_PLACES = 35;
/**
 * discover_hotels_reviews 
 */
var SOCIAL_ENTITY_HOTEL_REVIEWS = 36;
/**
 * discover_restaurants_reviews 
 */
var SOCIAL_ENTITY_RESTAURANT_REVIEWS = 37;
/**
 * discover_poi_reviews 
 */
var SOCIAL_ENTITY_LANDMARK_REVIEWS = 38;
/**
 * discover_restaurants 
 */
var SOCIAL_ENTITY_RESTAURANT_CUISINE = 39;
/**
 * discover_restaurants 
 */
var SOCIAL_ENTITY_RESTAURANT_SERVICE = 40;
/**
 * discover_restaurants 
 */
var SOCIAL_ENTITY_RESTAURANT_ATMOSPHERE = 41;
/**
 * discover_restaurants 
 */
var SOCIAL_ENTITY_RESTAURANT_PRICE = 42;
/**
 * discover_restaurants 
 */
var SOCIAL_ENTITY_RESTAURANT_NOISE = 43;
/**
 * discover_restaurants 
 */
var SOCIAL_ENTITY_RESTAURANT_TIME = 44;
/**
 * report a bug 
 */
var SOCIAL_ENTITY_REPORT_BUG = 45;
/**
 * location 
 */
var SOCIAL_ENTITY_VISITED_PLACES = 47;
/**
 * discover_hotels 
 */
var SOCIAL_ENTITY_HOTEL_AIRPOT = 48;
/**
 * discover_hotels 
 */
var SOCIAL_ENTITY_HOTEL_SERVICE = 49;
/**
 * discover_hotels 
 */
var SOCIAL_ENTITY_HOTEL_CLEANLINESS = 50;
/**
 * discover_hotels 
 */
var SOCIAL_ENTITY_HOTEL_INTERIOR = 51;
/**
 * discover_hotels 
 */
var SOCIAL_ENTITY_HOTEL_PRICE = 52;
/**
 * discover_hotels 
 */
var SOCIAL_ENTITY_HOTEL_FOODDRINK = 53;
/**
 * discover_hotels 
 */
var SOCIAL_ENTITY_HOTEL_INTERNET = 54;
/**
 * discover_hotels 
 */
var SOCIAL_ENTITY_HOTEL_NOISE = 55;
/**
 * discover_poi
 */
var SOCIAL_ENTITY_LANDMARK_FOODAVAILABLE = 56;
/**
 * discover_poi
 */
var SOCIAL_ENTITY_LANDMARK_BATHROOMFACILITIES = 57;
/**
 * discover_poi
 */
var SOCIAL_ENTITY_LANDMARK_STAIRS = 58;
/**
 * discover_poi
 */
var SOCIAL_ENTITY_LANDMARK_STORAGE = 59;
/**
 * discover_poi
 */
var SOCIAL_ENTITY_LANDMARK_PARKING = 60;
/**
 * discover_poi
 */
var SOCIAL_ENTITY_LANDMARK_WHEELCHAIR = 61;
/**
 * CUSTOMER SUPPORT
 */
var SOCIAL_ENTITY_CUSTOME_SUPPORT =62;
/**
 *  airport
 */
var SOCIAL_ENTITY_AIRPORT = 63;
/**
 *  airport_reviews
 */
var SOCIAL_ENTITY_AIRPORT_REVIEWS = 64;
/**
 *  airport
 */
var SOCIAL_ENTITY_AIRPORT_LUGGAGE = 65;
/**
 *  airport
 */
var SOCIAL_ENTITY_AIRPORT_RECEPTION = 66;
/**
 *  airport
 */
var SOCIAL_ENTITY_AIRPORT_LOUNGE = 67;
/**
 *  airport
 */
var SOCIAL_ENTITY_AIRPORT_FOOD = 68;
/**
 *  airport
 */
var SOCIAL_ENTITY_AIRPORT_DUTYFREE = 69;
/**
 *  cms_sosial_story
 */
var SOCIAL_ENTITY_STORY = 70;

/**
 * an actual share
 */
var SOCIAL_SHARE_TYPE_SHARE = 1;
/**
 * an invite share
 */
var SOCIAL_SHARE_TYPE_INVITE = 2;
/**
 * a sponsor share
 */
var SOCIAL_SHARE_TYPE_SPONSOR = 3;
/**
 * channel request parent
 */
var CHANNEL_RELATION_TYPE_PARENT = 1;
/**
 * channel request sub
 */
var CHANNEL_RELATION_TYPE_SUB = 2;

/**
 * an actual post text
 */
var SOCIAL_POST_TYPE_TEXT = 1;
/**
 * an actual post photo
 */
var SOCIAL_POST_TYPE_PHOTO = 2;
/**
 * an actual post video
 */
var SOCIAL_POST_TYPE_VIDEO = 3;
/**
 * an actual post link
 */
var SOCIAL_POST_TYPE_LINK = 4;
/**
 * an actual add location
 */
var SOCIAL_POST_TYPE_LOCATION = 5;

/**
 * an actual echo text
 */
var ECHO_TYPE_TEXT = 1;
/**
 * an actual echo link
 */
var ECHO_TYPE_LINK = 2;
/**
 * an actual echo location
 */
var ECHO_TYPE_LOCATION = 3;

/**
 * privacy settings for connections
 */
var PRIVACY_EXTAND_TYPE_CONNECTIONS = 1;
/**
 * privacy settings for sponsors
 */
var PRIVACY_EXTAND_TYPE_SPONSORS = 2;
/**
 * privacy settings for channel log
 */
var PRIVACY_EXTAND_TYPE_LOG = 3;
/**
 * privacy settings for who can join events
 */
var PRIVACY_EXTAND_TYPE_EVENTJOIN = 4;

/**
 * privacy settings kind: public
 */
var PRIVACY_EXTAND_KIND_PUBLIC = 1;
/**
 * privacy settings kind: connections
 */
var PRIVACY_EXTAND_KIND_CONNECTIONS = 2;
/**
 * privacy settings kind: sponsors
 */
var PRIVACY_EXTAND_KIND_SPONSORS = 3;
/**
 * privacy settings kind: private
 */
var PRIVACY_EXTAND_KIND_PRIVATE = 4;
/**
 * privacy settings kind: custom
 */
var PRIVACY_EXTAND_KIND_CUSTOM = 5;


/**
 * the object is private  
 */
var USER_PRIVACY_PRIVATE = 0;
/**
 * the object can be shared with friends
 */
var USER_PRIVACY_COMMUNITY = 1;
/**
 * the object can be shared with the public
 */
var USER_PRIVACY_PUBLIC = 2;
/**
 * the object can be shaed with the friends of friends
 */
var USER_PRIVACY_COMMUNITY_EXTENDED = 3;
/**
 * the object will be shared with custom
 */
var USER_PRIVACY_SELECTED = 4;
/**
 * the object will be shared with followers
 */
var USER_PRIVACY_FOLLOWERS = 5;

/**
 * an actual channel cover photo
 */
var CHANNEL_DETAIL_COVER = 1;
/**
 * an actual channel profile photo
 */
var CHANNEL_DETAIL_PROFILE = 2;
/**
 * an actual channel slogan
 */
var CHANNEL_DETAIL_SLOGAN = 3;
/**
 * an actual channel info
 */
var CHANNEL_DETAIL_INFO = 4;
/**
 * an actual user profile photo
 */
var USER_DETAIL_PROFILE = 1;

   
function decodeURL(inUrl) {
    return inUrl.replace(/&amp;/g, '&');
}

function setConfirmUnload(on, whichvar) {

    if (on) {
        $('a').attr('target', '_blank');
        ConfirmMessage = whichvar;
        $(window).bind('beforeunload', function() {
            return ConfirmMessage;
        });
    } else {
        $('a').removeAttr('target');
        $(window).unbind('beforeunload');
    }

    //window.onbeforeunload = (on) ? unloadMessage : null;
}
function returnIframeDimensions(){
    var ww= window.innerWidth  ? window.innerWidth  : $(window).width();
    ww = Math.round(ww*80/100)-15;
    var hh= window.innerHeight  ? window.innerHeight  : $(window).height();
    hh = Math.round(hh*80/100)-15;
    var pagedimensions = new Array( ww , hh );
    return pagedimensions;
}
function GetUnloadMessage() {
    return ConfirmMessage;
}
function unloadMessage(e) {
    e.returnValue = GetUnloadMessage();
    return GetUnloadMessage();
}

Array.prototype.remove = function(from, to) {
    var rest = this.slice((to || from) + 1 || this.length);
    this.length = from < 0 ? this.length + from : from;
    return this.push.apply(this, rest);
};
function TTAlert(in_options) {
    if (pagename == "popup_view") {
        return;
    }
    var options = $.extend({
        msg: t('default msg'),
        type: 'action',
        btn1: '',
        btn2: '',
        btn2Callback: null
    }, in_options);

    switch (options.type.toLowerCase()) {
        case 'alert':
            $('#TTAlertContainer .TTAlert_buts1').attr('data-type', 'close');
            $('#TTAlertContainer .TTAlert_buts2').hide();
            $('#TTAlertContainer .TTAlert_seperator').hide();
            break;
        case 'action':
            $('#TTAlertContainer .TTAlert_buts1').attr('data-type', 'close');
            $('#TTAlertContainer .TTAlert_buts2').html(options.btn2);
            $('#TTAlertContainer .TTAlert_buts2').show();
            $('#TTAlertContainer .TTAlert_seperator').show();
            break;
    }
    $("html, body").animate({scrollTop: 0}, 1);

    $('#TTAlertContainer .TTAlert_buts1').html(options.btn1);
    $('#TTAlertContainer .TTAlert_text').html(options.msg);
    $('#TTAlertContainer #TTAlertContainerInside_data').css('top', "-80px");
    $('#TTAlertContainer').stop().show();
    var textTop = (60 - $('#TTAlertContainer .TTAlert_text').height()) / 2;
    $('#TTAlertContainer .TTAlert_text').css('top', textTop + "px");
    $('#TTAlertContainer #TTAlertContainerInside_data').stop().animate({'top': 0}, 500);

    $('#TTAlertContainer .TTAlert_buts1').unbind('click', TTAlertCBbuts1).click({arg: options.btn2Callback}, TTAlertCBbuts1);
    $('#TTAlertContainer .TTAlert_buts2').unbind('click', TTAlertCBbuts2).click({arg: options.btn2Callback}, TTAlertCBbuts2);
}
function addTTAlertBttons() {
    try{
        if($('#TTAlertContainer').length>0){
            $('#TTAlertContainer #TTAlert_close').click(function () {
                $('#TTAlertContainer .TTAlert_buts1').click();
            });
        }
    } catch (e) {

    }
}

function TTAlertCBbuts2(e) {
    $('#TTAlertContainer').hide();
    if (e.data.arg != null)
        e.data.arg(true);
}
function TTAlertCBbuts1(e) {
    $('#TTAlertContainer').hide();
    if (e.data.arg != null) {
        e.data.arg(false);
    }
}
$(document).ready(function(){    
    addTTAlertBttons();
});
/**
 * the path to the files
 **/
var AbsolutePath = '';
var GlobalLanguage = '';
var paths;

/**
 * the url parameters json array
 * @type array
 */
var _GlobalParameters = new Array();      
try {
    if (window.location.href.toString().indexOf('touristtube.com') == -1) {
        paths = window.location.href.toString().split('/');
        if (paths[3] == 'ttsvn1')
            AbsolutePath = '/ttsvn1';//+ paths[3];


        GlobalLanguage = 'en';
        var lgcook = $.cookie('lang');
//        if (lgcook != '' && lgcook != 'undefined' && lgcook != 'null'){
        if (typeof lgcook !== 'undefined' && lgcook && lgcook !== ''){
            //GlobalLanguage = $.cookie('lang');
            GlobalLanguage = lgcook;            
        }
            
        var i = 5;
        while (i < paths.length) {
            if (paths[i].length != 0)
                _GlobalParameters.push(paths[i]);
            i++;
        }
    } else {
       
        paths = window.location.href.toString().split('/');

        GlobalLanguage = 'en';
        var lgcook = $.cookie('lang');
        //var lgcook = getCookie('lang'); 
//        if ( lgcook != '' && lgcook != 'undefined' && lgcook != 'null'){
        if (typeof lgcook !== 'undefined' && lgcook && lgcook !== ''){
            //GlobalLanguage = $.cookie('lang');
            GlobalLanguage = lgcook;  
        }

        var i = 4;
        while (i < paths.length) {
            if (paths[i].length != 0)
                _GlobalParameters.push(paths[i]);
            i++;
        }
    }
} catch (e) {

}


/**
 * removes elements from an array
 * @param {Integer} from
 * @param {Integer} to
 * @returns {Array}
 */
Array.prototype.remove = function(from, to) {
    var rest = this.slice((to || from) + 1 || this.length);
    this.length = from < 0 ? this.length + from : from;
    return this.push.apply(this, rest);
};

/**
 * removes an element from an array by value
 * @param {String} elementString
 * @returns {Array}
 */
Array.prototype.removeElement = function(elementValue) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] === elementValue) {
            return this.remove(i);
        }
    }
};

/**
 * checks if an element exists in an array
 * @param {String} elementString
 * @returns {Array}
 */
Array.prototype.elementExists = function(elementValue) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] === elementValue) {
            return true;
        }
    }
    return false;
};

/**
 * clones an array
 * @returns {Array}
 */
Array.prototype.clone = function() {
    return this.slice(0);
};

/**
 * returns the argument
 * @param string val
 * @returns string
 */
function UriGetArg(val) {
    var i = 0;
    var integer_val = parseInt(val);
    if (integer_val == val) {
        if (integer_val < _GlobalParameters.length)
            return _GlobalParameters[integer_val];
        else
            return null;
    }
    while (i < _GlobalParameters.length - 1) {
        if (_GlobalParameters[i] == val) {
            return _GlobalParameters[i + 1];
        }
        i++;
    }
    return null;
}

/**
 * returns the argument
 * @param object args_obj the json object containing the variables
 * @returns string|null
 */
function UriArgEdit(args_obj) {
    for (var key in args_obj) {
        var val = args_obj[key];
        var found = false;
        var i = 0;
        while (i < _GlobalParameters.length - 1) {
            if (_GlobalParameters[i] === key) {
                found = true;
                if (val !== null)
                    _GlobalParameters[i + 1] = val;
                else {
                    _GlobalParameters.remove(i);
                    _GlobalParameters.remove(i);
                }
                break;
            }
            i++;
        }
        if (!found && (_GlobalParameters[i] === key)) {
            _GlobalParameters.push(val);
            found = true;
        }
        if (!found && (val !== null)) {
            _GlobalParameters.push(key);
            _GlobalParameters.push(val);
        }
    }

    return ReturnLink(_GlobalParameters.join('/'));
}

/**
 * returs the global language variable
 */
function LanguageGlobalGet() {
    return GlobalLanguage;
}

/**
 * call the touristtube api.
 * @param in_options object. object containing the options which include: <br/>
 * <b>ret</b> string return type 'json', 'html', 'xml'. default: 'json'<br/>
 * <b>callback</b> function. the function to callback on success<br/>
 * <b>what</b> {String}. the touristttube operation identifier<br/>
 * <b>data</b> object} the data to send<br/>
 */
function TTCallAPI(in_options) {
    var options = $.extend({
        ret: 'json',
        callback: null,
        what: null,
        data: null
    }, in_options);
    
    var called_link = options.what;
    if (called_link.indexOf('.php') != -1) {
        //old call method
    } else if (called_link.indexOf('/uapi') == -1) {
        //new call method
        //trim slashes
        called_link = called_link.replace(/^\/|\/$/g, '')
        //get full link
        called_link = ReturnLink('/uapi/' + called_link);
    }
    $.ajax({
        //url: ReturnLink('api.php?op=' + options.what ),
        url: called_link,
        data: options.data,
        type: 'post',
        cache:false,
        error: function() {
            
            TTAlert({
                msg: t("Couldn't Process Request. Please try again later."),
                type: 'alert',
                btn1: t('ok'),
                btn2: '',
                btn2Callback: null
            });
        },
        success: function(resp) {
            
            if (options.callback === null)
                return;
            if (options.ret === 'html')
                options.callback(resp);
            else if (options.ret === 'json') {
                var Jresponse;
                try {
                    Jresponse = $.parseJSON(resp);
                } catch (Ex) {
                    TTAlert({
                        msg: t("Couldn't Process Request. Please try again later."),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                if (!Jresponse) {
                    TTAlert({
                        msg: t("Couldn't Process Request. Please try again later."),
                        type: 'alert',
                        btn1: t('ok'),
                        btn2: '',
                        btn2Callback: null
                    });
                    return;
                }
                options.callback(Jresponse);
            } else if (options.ret === 'xml') {
                var xmlDoc = $.parseXML(resp);
                var $xml = $(xmlDoc);
                options.callback($xml);
            }
        }
    });
}

/**
 * returns the proper link
 * @param link string the relative link
 * @return string a proper link
 */
function ReturnLink(link) {
    var append = '&';
    if (link.indexOf('?') == -1)
        append = '?';

    //get only url without '?'
    var onlyLink = link.split('?');
    onlyLink = onlyLink[0];

    var beginging = (onlyLink[0] == '/') ? '' : '/';

    if (window.location.href.toString().indexOf('touristtube.com') != -1) {
        if ((onlyLink.indexOf('.js') != -1 && onlyLink.indexOf('.json') == -1) || (onlyLink.indexOf('images/') != -1) || (onlyLink.indexOf('media/') != -1 ) || (onlyLink.indexOf('.css') != -1) || (onlyLink.indexOf('css/') != -1) || (onlyLink.indexOf('js/') != -1)) {
            var prefix = (onlyLink.indexOf('.js') != -1 || onlyLink.indexOf('.css') != -1 || (onlyLink.indexOf('css/') != -1) || (onlyLink.indexOf('js/') != -1)) ? 'static' : 'static1';
            //prefix = 'www';
            AbsolutePath = '//' + prefix + '.touristtube.com';
        } else {
            AbsolutePath = '';
        }
    }
    beginging ="/social"+beginging;
    if ((onlyLink.indexOf('jwplayer/') != -1) || (onlyLink.indexOf('in_18/') != -1) || (onlyLink.indexOf('.swf') != -1) || (onlyLink.indexOf('/images') != -1) || (onlyLink.indexOf('media/') != -1) || (onlyLink.indexOf('/css') != -1) ) {
        return AbsolutePath + beginging + link;
    } else if ((onlyLink.indexOf('.php') != -1)) {
        return AbsolutePath + beginging + link + append + 'lang=' + GlobalLanguage;
    } else {
       if( GlobalLanguage == 'en')  return AbsolutePath + beginging + link; else  return AbsolutePath + '/' + GlobalLanguage + beginging + link;
    }
}
//function getCookie(cname) {
//    var name = cname + "=";
//    var ca = document.cookie.split(';');
//    for(var i=0; i<ca.length; i++) {
//        var c = ca[i];
//        while (c.charAt(0)==' ') c = c.substring(1);
//        if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
//    }
//    return "";
//}
function LanguageGet(val) {
    
   return t(val);
    
}
function isRTL(s){
     var ltrChars        = 
'A-Za-z\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u02B8\u0300-\u0590\u0800-\u1FFF'+'\u2C00-\uFB1C\uFDFE-\uFE6F\uFEFD-\uFFFF',
         rtlChars        = '\u0591-\u07FF\uFB1D-\uFDFD\uFE70-\uFEFC',
         rtlDirCheck     = new RegExp('^[^'+ltrChars+']*['+rtlChars+']');

     return rtlDirCheck.test(s);
};

function GUID()
{
    var S4 = function() {
        return Math.floor(
                Math.random() * 0x10000 /* 65536 */
                ).toString(16);
    };

    return (
            S4() + S4() + "-" +
            S4() + "-" +
            S4() + "-" +
            S4() + "-" +
            S4() + S4() + S4()
            );
}

var delay = (function() {
    var timer = 0;
    return function(callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();


function isAlphaNumeric(val) {
    var alphanum = /^[0-9a-bA-B]+$/; //This contains A to Z , 0 to 9 and A to B
    if (val.match(alphanum)) {
        return true;
    } else {
        return false;
    }
}
function isPhoneNumeric(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;   
    if ( charCode == 32 ) return true;
    if ( charCode > 31 && (charCode < 48 || charCode > 57))
    return false;

    return true;
}
function ValidatePhoneNumber(inputtxt) {  
    var phoneno = /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;  
    if(inputtxt.value.match(phoneno)){  
        return true;  
    }else{ 
          return false;  
    }  
} 

//////////////////
var defaultDiacriticsRemovalMap = [
    {'base': 'A', 'letters': /[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g},
    {'base': 'AA', 'letters': /[\uA732]/g},
    {'base': 'AE', 'letters': /[\u00C6\u01FC\u01E2]/g},
    {'base': 'AO', 'letters': /[\uA734]/g},
    {'base': 'AU', 'letters': /[\uA736]/g},
    {'base': 'AV', 'letters': /[\uA738\uA73A]/g},
    {'base': 'AY', 'letters': /[\uA73C]/g},
    {'base': 'B', 'letters': /[\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181]/g},
    {'base': 'C', 'letters': /[\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E]/g},
    {'base': 'D', 'letters': /[\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779]/g},
    {'base': 'DZ', 'letters': /[\u01F1\u01C4]/g},
    {'base': 'Dz', 'letters': /[\u01F2\u01C5]/g},
    {'base': 'E', 'letters': /[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g},
    {'base': 'F', 'letters': /[\u0046\u24BB\uFF26\u1E1E\u0191\uA77B]/g},
    {'base': 'G', 'letters': /[\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E]/g},
    {'base': 'H', 'letters': /[\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D]/g},
    {'base': 'I', 'letters': /[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g},
    {'base': 'J', 'letters': /[\u004A\u24BF\uFF2A\u0134\u0248]/g},
    {'base': 'K', 'letters': /[\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2]/g},
    {'base': 'L', 'letters': /[\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780]/g},
    {'base': 'LJ', 'letters': /[\u01C7]/g},
    {'base': 'Lj', 'letters': /[\u01C8]/g},
    {'base': 'M', 'letters': /[\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C]/g},
    {'base': 'N', 'letters': /[\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4]/g},
    {'base': 'NJ', 'letters': /[\u01CA]/g},
    {'base': 'Nj', 'letters': /[\u01CB]/g},
    {'base': 'O', 'letters': /[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g},
    {'base': 'OI', 'letters': /[\u01A2]/g},
    {'base': 'OO', 'letters': /[\uA74E]/g},
    {'base': 'OU', 'letters': /[\u0222]/g},
    {'base': 'P', 'letters': /[\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754]/g},
    {'base': 'Q', 'letters': /[\u0051\u24C6\uFF31\uA756\uA758\u024A]/g},
    {'base': 'R', 'letters': /[\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782]/g},
    {'base': 'S', 'letters': /[\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784]/g},
    {'base': 'T', 'letters': /[\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786]/g},
    {'base': 'TZ', 'letters': /[\uA728]/g},
    {'base': 'U', 'letters': /[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g},
    {'base': 'V', 'letters': /[\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245]/g},
    {'base': 'VY', 'letters': /[\uA760]/g},
    {'base': 'W', 'letters': /[\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72]/g},
    {'base': 'X', 'letters': /[\u0058\u24CD\uFF38\u1E8A\u1E8C]/g},
    {'base': 'Y', 'letters': /[\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE]/g},
    {'base': 'Z', 'letters': /[\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762]/g},
    {'base': 'a', 'letters': /[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g},
    {'base': 'aa', 'letters': /[\uA733]/g},
    {'base': 'ae', 'letters': /[\u00E6\u01FD\u01E3]/g},
    {'base': 'ao', 'letters': /[\uA735]/g},
    {'base': 'au', 'letters': /[\uA737]/g},
    {'base': 'av', 'letters': /[\uA739\uA73B]/g},
    {'base': 'ay', 'letters': /[\uA73D]/g},
    {'base': 'b', 'letters': /[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/g},
    {'base': 'c', 'letters': /[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/g},
    {'base': 'd', 'letters': /[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/g},
    {'base': 'dz', 'letters': /[\u01F3\u01C6]/g},
    {'base': 'e', 'letters': /[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g},
    {'base': 'f', 'letters': /[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/g},
    {'base': 'g', 'letters': /[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/g},
    {'base': 'h', 'letters': /[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/g},
    {'base': 'hv', 'letters': /[\u0195]/g},
    {'base': 'i', 'letters': /[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g},
    {'base': 'j', 'letters': /[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/g},
    {'base': 'k', 'letters': /[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/g},
    {'base': 'l', 'letters': /[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/g},
    {'base': 'lj', 'letters': /[\u01C9]/g},
    {'base': 'm', 'letters': /[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/g},
    {'base': 'n', 'letters': /[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/g},
    {'base': 'nj', 'letters': /[\u01CC]/g},
    {'base': 'o', 'letters': /[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g},
    {'base': 'oi', 'letters': /[\u01A3]/g},
    {'base': 'ou', 'letters': /[\u0223]/g},
    {'base': 'oo', 'letters': /[\uA74F]/g},
    {'base': 'p', 'letters': /[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/g},
    {'base': 'q', 'letters': /[\u0071\u24E0\uFF51\u024B\uA757\uA759]/g},
    {'base': 'r', 'letters': /[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/g},
    {'base': 's', 'letters': /[\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/g},
    {'base': 't', 'letters': /[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/g},
    {'base': 'tz', 'letters': /[\uA729]/g},
    {'base': 'u', 'letters': /[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g},
    {'base': 'v', 'letters': /[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/g},
    {'base': 'vy', 'letters': /[\uA761]/g},
    {'base': 'w', 'letters': /[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/g},
    {'base': 'x', 'letters': /[\u0078\u24E7\uFF58\u1E8B\u1E8D]/g},
    {'base': 'y', 'letters': /[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/g},
    {'base': 'z', 'letters': /[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/g}
];
var changes;
function removeDiacritics(str) {
    if (!changes) {
        changes = defaultDiacriticsRemovalMap;
    }
    for (var i = 0; i < changes.length; i++) {
        str = str.replace(changes[i].letters, changes[i].base);
    }
    return str;
}
////////////////////////

/**
 * upper case the first letters of each word
 * @param {String} in_string
 * @returns {String}
 */
function ucwords(in_string) {
    return in_string.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
}


/**
 * the global channel id global variable
 */
var _tt_global_channel_id = null;

/**
 * gets or sets the channel global id
 * @param {Integer} gid the global channel id
 * @return {Integer} the global channel id or null if not found
 */
function channelGlobalID(gid) {
    if (typeof gid != 'undefined') {
        _tt_global_channel_id = gid;
    } else {
        return _tt_global_channel_id;
    }
}
/**
 * the global user id global variable
 */
var _tt_global_user_id = null;

/**
 * gets or sets the user global id
 * @param {Integer} gid the global user id
 * @return {Integer} the global user id or null if not found
 */
function userGlobalID(gid) {
    if (typeof gid != 'undefined') {
        _tt_global_user_id = gid;
    } else {
        return _tt_global_user_id;
    }
}
var _tt_global_dis_name = null;
function userGlobalDISName(str) {
    if (typeof str != 'undefined') {
        _tt_global_dis_name = str;
    } else {
        return _tt_global_dis_name;
    }
}
function closeFancyBox() {
    $(".fancybox-close").click();
}
function unscrollIframe() {
    $('.fancybox-iframe').attr("scrolling", "no");
    $('.fancybox-iframe').attr("allowfullscreen", "allowfullscreen");
    $('.fancybox-inner').css("overflow", "hidden");
    //consoleLog();
}
function MediaPageFix() {
    //$('#MiddleInside').height(Math.max($('#RightInsideContainer').height() + 50, $('#InsideContainer').height() + 150));
}
function validateEmail(elementValue) {   
    var emailPattern = /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i;
    return emailPattern.test(elementValue);
}
function RequestifyLink() {
    $('a.requestinvite_index').unbind('click').click(function(event) {
        if (userIsLogged == 1)
            return;
        event.preventDefault();
        event.stopImmediatePropagation();
        TTAlert({
            msg: $.i18n._('you have to sign in, in order to access a tuber page'),
            type: 'action',
            btn1: $.i18n._('sign in'),
            btn2: $.i18n._('register'),
            btn2Callback: function(data) {
                if (data) {
                    window.location.href = ReturnLink('/register');
                } else {
                    SignInTO = setTimeout(function() {
                    $('#SignInDiv').fadeIn();
                    signflag = 1;
                    }, 300);
                }
            }
         });
        /*
        $.fancybox({
            'autoScale': true,
            'transitionIn': 'elastic',
            'transitionOut': 'elastic',
            'speedIn': 500,
            'speedOut': 300,
            'autoDimensions': true,
            'centerOnScroll': true,
            'padding': 0,
            'margin': 0,
            'href': '#requestinvitationcontainer',
            beforeLoad: function() {
                $('.requestinvitationcontainerforminput').removeClass('mandatory');
                $('input,select,textarea', $('.requestinvitationcontainerforminput')).each(function() {
                    $(this).val('');
                    $(this).blur();
                });
            }
        });*/
        return false;
    });
    /*$('.TopRegisterLink').click(function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        $.fancybox({
            'autoScale': true,
            'transitionIn': 'elastic',
            'transitionOut': 'elastic',
            'speedIn': 500,
            'speedOut': 300,
            'autoDimensions': true,
            'centerOnScroll': true,
            'padding': 0,
            'margin': 0,
            'href': '#requestinvitationcontainer'
        });
    });*/
    $('.formbuts_submit').click(function(event) {
        var rusername = getObjectData($(".requestinvitation_register #rusername"));
        var rpassword = getObjectData($(".requestinvitation_register #rpassword"));
        var crpassword = getObjectData($(".requestinvitation_register #crpassword"));
        var remail = getObjectData($(".requestinvitation_register #remail"));
        $('.requestinvitation_register .inputtxt').removeClass('InputErr');
        $('.requestinvitation_register .requestinvitation_wrong_credentials').html('');
        if (remail.length == 0 || !(rusername.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $(".requestinvitation_register #remail").focus().addClass('InputErr');
            return false;
        } else if (!validateEmail(remail)) {
            $(".requestinvitation_register #remail").focus().addClass('InputErr');
            $('.requestinvitation_register .requestinvitation_wrong_credentials').html(t('please insert a correct email.'));
            return false;
        } else if (rusername.length == 0 || !(rusername.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $(".requestinvitation_register #rusername").focus().addClass('InputErr');
            return false;
        } else if (rpassword.length == 0 || !(rpassword.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $(".requestinvitation_register #rpassword").focus().addClass('InputErr');
            return false;
        } else if (rpassword.length < 6) {
            $(".requestinvitation_register #rpassword").focus().addClass('InputErr');
            $('.requestinvitation_register .requestinvitation_wrong_credentials').html(t('please your password should be at least 6 characters long.'));
            return false;
        } else if (crpassword.length == 0 || !(crpassword.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $(".requestinvitation_register #crpassword").focus().addClass('InputErr');
            return false;
        } else if (crpassword != rpassword) {
            $(".requestinvitation_register #crpassword").focus().addClass('InputErr');
            $('.requestinvitation_register .requestinvitation_wrong_credentials').html(t('password does not match.'));
            return false;
        } else {
            $('.upload-overlay-loading-fix').show();
            $.ajax({
                url: ReturnLink('/ajax/ajax_register.php'),
                data: {fname: rusername, lname: '', YourEmail: remail, YourUserName: rusername, YourPassword: rpassword, YourCPassword: crpassword, YourBday: '0000-00-00', gender: 'O', register_type: 'popup'},
                type: 'post',
                success: function(data) {
                    var jres = null;
                    try {
                        jres = $.parseJSON(data);
                    } catch (Ex) {
                        $('.upload-overlay-loading-fix').hide();
                        return;
                    }
                    if (jres.error) {
                        $('.upload-overlay-loading-fix').hide();
                        if (jres.YourUserName) {
                            $("#YourUserName").val(jres.YourUserName);
                        }
                        $('.requestinvitation_register .requestinvitation_wrong_credentials').html(jres.error);
                    } else {
                        $('.upload-overlay-loading-fix').hide();
                        window.top.location.href = ReturnLink('register-success');
                    }
                }
            });
        }
    });
    $('.formbuts_login').click(function(event) {
        var EmailField = getObjectData($(".requestinvitation_sign #susername"));
        var PasswordField = getObjectData($(".requestinvitation_sign #spassword"));
        $('.requestinvitation_sign .inputtxt').removeClass('InputErr');
        $('.requestinvitation_sign .requestinvitation_wrong_credentials').html('');
        if (EmailField.length == 0 || !(EmailField.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $(".requestinvitation_sign #susername").focus().addClass('InputErr');
            return false;
        } else if (PasswordField.length == 0 || !(PasswordField.match(/([\<])([^\>]{1,})*([\>])/i) == null)) {
            $(".requestinvitation_sign #spassword").focus().addClass('InputErr');
            return false;
        } else {
            var dataString = 'EmailField=' + EmailField + '&PasswordField=' + PasswordField;
            $.ajax({
                type: "POST",
                url: ReturnLink("/ajax/process.php"),
                data: dataString,
                success: function(data) {
                    var Jresponse;
                    try {
                        Jresponse = $.parseJSON(data);
                    } catch (Ex) {
                        return;
                    }
                    if (Jresponse.status == 'ok') {
                        window.location.reload();
                    } else {
                        $('.requestinvitation_sign .requestinvitation_wrong_credentials').html(t('Invalid credentials'));
                    }
                }
            });
        }
    });
    $('.formbuts_cancel').click(function(event) {
        closeFancyBox();
    });
}
function checkSubmitSign(e) {
    if (e && e.keyCode == 13) {
        $('.formbuts_login').click();
    }
}
function checkSubmitRegister(e) {
    if (e && e.keyCode == 13) {
        $('.formbuts_submit').click();
    }
}
function addValue2(obj) {
    if ($(obj).attr('value') == '')
        $(obj).attr('value', $(obj).attr('data-value'));
}
function removeValue2(obj) {
    if ($(obj).attr('value') == $(obj).attr('data-value'))
        $(obj).attr('value', '');
}
function getObjectData(obj) {
    var mystr = "" + obj.val();
    if (mystr == $(obj).attr('data-value')) {
        mystr = "";
    }
    return mystr;
}
function validateUserName(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //if((charCode>47 && charCode<58) || (charCode>64 && charCode<91) || (charCode>96 && charCode<123) || charCode==8 || charCode==46 || charCode==39 || charCode==37 || charCode==64 || charCode==95 || charCode==45)        
        return true;
	
    return false;    
 }
function ValidURL(str) {
    var pattern = new RegExp('^((ftp|http|https)?:\\/\\/)?' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
    if (pattern.test(str)) {
        return true;
    }
    return false;
}

/**
 * tracks an ajax request with google analytics
 * @param string url the ajax request to be tracked
 */
function TrackAjaxRequest(url) {
    if (typeof _gaq != 'undefined')
        _gaq.push(['_trackPageview', url]);
}
function SelectedUsers(_object) {
    _object = typeof _object !== 'undefined' ? _object : null;
    if (_object == null) {
        return _SelectedUsers;
    } else if (_object.attr('id') == "addmoretext_connections") {
        return _SelectedConnections;
    } else if (_object.attr('id') == "addmoretext_sponsors") {
        return _SelectedSponsors;
    } else if (_object.attr('id') == "addmoretext_log") {
        return _SelectedLogs;
    } else if (_object.attr('id') == "addmoretext_canjoin") {
        return _SelectedCanJoin;
    } else if (_object.attr('id') == "addmoretext_custum_privacy_detailed") {
        return _SelectedUsersDetailed;
    } else if (_object.attr('id') == "addmoretext_zero") {
        return _SelectedUsers_zero;
    } else if (_object.attr('id') == "addmoretext_first") {
        return _SelectedUsers_first;
    } else if (_object.attr('id') == "addmoretext_second") {
        return _SelectedUsers_second;
    } else if (_object.attr('id') == "addmoretext_third") {
        return _SelectedUsers_third;
    } else if (_object.attr('id') == "addmoretext_forth") {
        return _SelectedUsers_forth;
    } else if (_object.attr('id') == "addmoretext_fifth") {
        return _SelectedUsers_fifth;
    } else {
        return _SelectedUsers;
    }
}

function SelectedUsersAdd(uid, _object) {
    _object = typeof _object !== 'undefined' ? _object : null;
    if (_object == null) {
        _SelectedUsers.push(uid);
    } else if (_object.attr('id') == "addmoretext_connections") {
        _SelectedConnections.push(uid);
    } else if (_object.attr('id') == "addmoretext_sponsors") {
        _SelectedSponsors.push(uid);
    } else if (_object.attr('id') == "addmoretext_log") {
        _SelectedLogs.push(uid);
    } else if (_object.attr('id') == "addmoretext_canjoin") {
        _SelectedCanJoin.push(uid);
    } else if (_object.attr('id') == "addmoretext_custum_privacy_detailed") {
        _SelectedUsersDetailed.push(uid);
    } else if (_object.attr('id') == "addmoretext_zero") {
        _SelectedUsers_zero.push(uid);
    } else if (_object.attr('id') == "addmoretext_first") {
        _SelectedUsers_first.push(uid);
    } else if (_object.attr('id') == "addmoretext_second") {
        _SelectedUsers_second.push(uid);
    } else if (_object.attr('id') == "addmoretext_third") {
        _SelectedUsers_third.push(uid);
    } else if (_object.attr('id') == "addmoretext_forth") {
        _SelectedUsers_forth.push(uid);
    } else if (_object.attr('id') == "addmoretext_fifth") {
        _SelectedUsers_fifth.push(uid);
    } else {
        _SelectedUsers.push(uid);
    }

}

function resetSelectedUsers(_object) {
    _object = typeof _object !== 'undefined' ? _object : null;
    if (_object == null) {
        _SelectedUsers = new Array();
    } else if (_object.attr('id') == "addmoretext_connections") {
        _SelectedConnections = new Array();
    } else if (_object.attr('id') == "addmoretext_sponsors") {
        _SelectedSponsors = new Array();
    } else if (_object.attr('id') == "addmoretext_log") {
        _SelectedLogs = new Array();
    } else if (_object.attr('id') == "addmoretext_canjoin") {
        _SelectedCanJoin = new Array();
    } else if (_object.attr('id') == "addmoretext_custum_privacy_detailed") {
        _SelectedUsersDetailed = new Array();
    } else if (_object.attr('id') == "addmoretext_zero") {
        _SelectedUsers_zero = new Array();
    } else if (_object.attr('id') == "addmoretext_first") {
        _SelectedUsers_first = new Array();
    } else if (_object.attr('id') == "addmoretext_second") {
        _SelectedUsers_second = new Array();
    } else if (_object.attr('id') == "addmoretext_third") {
        _SelectedUsers_third = new Array();
    } else if (_object.attr('id') == "addmoretext_forth") {
        _SelectedUsers_forth = new Array();
    } else if (_object.attr('id') == "addmoretext_fifth") {
        _SelectedUsers_fifth = new Array();
    } else {
        _SelectedUsers = new Array();
    }
}
function SelectedUsersDelete(uid, _object) {
    _object = typeof _object !== 'undefined' ? _object : null;
    if (_object == null) {
        _SelectedUsers.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_connections") {
        _SelectedConnections.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_sponsors") {
        _SelectedSponsors.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_log") {
        _SelectedLogs.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_canjoin") {
        _SelectedCanJoin.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_custum_privacy_detailed") {
        _SelectedUsersDetailed.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_zero") {
        _SelectedUsers_zero.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_first") {
        _SelectedUsers_first.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_second") {
        _SelectedUsers_second.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_third") {
        _SelectedUsers_third.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_forth") {
        _SelectedUsers_forth.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_fifth") {
        _SelectedUsers_fifth.removeElement(uid);
    } else {
        _SelectedUsers.removeElement(uid);
    }
}

// Part specific to adding channels where it is needed to add channels and users in the same box.
var _SelectedChannels = new Array();
var _SelectedChannelsConnections = new Array();
var _SelectedChannelsSponsors = new Array();
var _SelectedChannelsLogs = new Array();
var _SelectedChannelsCanJoin = new Array();

function SelectedChannels(_object) {
    _object = typeof _object !== 'undefined' ? _object : null;
    if (_object == null) {
        return _SelectedChannels;
    } else if (_object.attr('id') == "addmoretext_connections") {
        return _SelectedChannelsConnections;
    } else if (_object.attr('id') == "addmoretext_sponsors") {
        return _SelectedChannelsSponsors;
    } else if (_object.attr('id') == "addmoretext_log") {
        return _SelectedChannelsLogs;
    } else if (_object.attr('id') == "addmoretext_canjoin") {
        return _SelectedChannelsCanJoin;
    } else {
        return _SelectedChannels;
    }
}

function SelectedChannelsAdd(uid, _object) {
    _object = typeof _object !== 'undefined' ? _object : null;
    if (_object == null) {
        _SelectedChannels.push(uid);
    } else if (_object.attr('id') == "addmoretext_connections") {
        _SelectedChannelsConnections.push(uid);
    } else if (_object.attr('id') == "addmoretext_sponsors") {
        _SelectedChannelsSponsors.push(uid);
    } else if (_object.attr('id') == "addmoretext_log") {
        _SelectedChannelsLogs.push(uid);
    } else if (_object.attr('id') == "addmoretext_canjoin") {
        _SelectedChannelsCanJoin.push(uid);
    } else {
        _SelectedChannels.push(uid);
    }
}

function resetSelectedChannels(_object) {
    _object = typeof _object !== 'undefined' ? _object : null;
    if (_object == null) {
        _SelectedChannels = new Array();
    } else if (_object.attr('id') == "addmoretext_connections") {
        _SelectedChannelsConnections = new Array();
    } else if (_object.attr('id') == "addmoretext_sponsors") {
        _SelectedChannelsSponsors = new Array();
    } else if (_object.attr('id') == "addmoretext_log") {
        _SelectedChannelsLogs = new Array();
    } else if (_object.attr('id') == "addmoretext_canjoin") {
        _SelectedChannelsCanJoin = new Array();
    } else {
        _SelectedChannels = new Array();
    }
}
function SelectedChannelsDelete(uid, _object) {
    _object = typeof _object !== 'undefined' ? _object : null;
    if (_object == null) {
        _SelectedChannels.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_connections") {
        _SelectedChannelsConnections.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_sponsors") {
        _SelectedChannelsSponsors.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_log") {
        _SelectedChannelsLogs.removeElement(uid);
    } else if (_object.attr('id') == "addmoretext_canjoin") {
        _SelectedChannelsCanJoin.removeElement(uid);
    } else {
        _SelectedChannels.removeElement(uid);
    }
}

// End of the channel part.

function addmoreusersautacomplete() {
    $('#addmoretext').autocomplete({
        appendTo: ".emailcontainer",
        delay: 5,
        search: function(event, ui) {
            var su = SelectedUsers();
            $('#addmoretext').autocomplete('option', 'source', ReturnLink('/ajax/channel-autocomplete-users.php') + "&ds=" + su.join(','));
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var selectedUsername1 = ui.item.username;
            var selectedUserID1 = ui.item.user_id;

            SelectedUsersAdd(selectedUserID1);

            var friendstr = '<div class="peoplesdata formttl" data-email="" data-id="' + selectedUserID1 + '"><div class="peoplesdatainside">' + selectedUsername1 + '</div><div class="peoplesdataclose"></div></div>';
            $('.emailcontainer').prepend(friendstr);
            //$('.emailcontainer .peoplesdata').first().css("width", ($('.emailcontainer .peoplesdata').first().find('.peoplesdatainside').width() + 20) + "px");
            $('#addmoretext').val('');
            $('#addmoretext').blur();
            var height = $('#inviteForm .emailcontainer').height() + 81 + 262;
            $('#inviteForm').css('height', height + "px");
            $('#inviteForm .formContainerglob').css('height', (height - 81) + "px");
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13) {
            if (validateEmail($('#addmoretext').val())) {
                var friendstr = '<div class="peoplesdata formttl" data-id="" data-email="' + $('#addmoretext').val() + '"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">' + $('#addmoretext').val() + '</div><div class="peoplesdataclose"></div></div>';
                $('.emailcontainer').prepend(friendstr);
                //$('.emailcontainer .peoplesdata').first().css("width", ($('.emailcontainer .peoplesdata').first().find('.peoplesdatainside').width() + 38) + "px");
                $('#addmoretext').val('');
                $('#addmoretext').blur();
                var height = $('#inviteForm .emailcontainer').height() + 81 + 262;
                $('#inviteForm').css('height', height + "px");
                $('#inviteForm .formContainerglob').css('height', (height - 81) + "px");
                event.preventDefault();
            }
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if ($('.emailcontainer .ui-autocomplete').height() < 144) {
            $('.emailcontainer .ui-autocomplete').css('overflow', 'hidden');
        } else {
            $('.emailcontainer .ui-autocomplete').css('overflow', 'auto');
        }
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
    }

}
function addshareuserautocomplete(auto_object) {
    if (auto_object.length == 0)
        return;
    // Get the current channel id.
    current_channel_id = channelGlobalID();
    var email_container_privacy = auto_object.parent().parent();

    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        search: function(event, ui) {
            var su = SelectedUsers();
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/channel-autocomplete-share.php') + "&ds=" + su.join(',') + '&cid=' + current_channel_id);
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var selectedUsername1 = ui.item.username;
            var selectedUserID1 = ui.item.user_id;

            SelectedUsersAdd(selectedUserID1);

            var friendstr = '<div class="peoplesdata formttl" data-email="" data-id="' + selectedUserID1 + '"><div class="peoplesdatainside">' + selectedUsername1 + '</div><div class="peoplesdataclose"></div></div>';
            email_container_privacy.prepend(friendstr);
            //email_container_privacy.find('.peoplesdata').first().css("width", (email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width() + 20) + "px");
            auto_object.val('');
            auto_object.blur();
            var height = email_container_privacy.height() + 81 + 262;
            email_container_privacy.closest('#inviteForm').css('height', height + "px");
            email_container_privacy.closest('#inviteForm .formContainerglob').css('height', (height - 81) + "px");
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13) {
            if (validateEmail(auto_object.val())) {
                var friendstr = '<div class="peoplesdata formttl" data-id="" data-email="' + auto_object.val() + '"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">' + auto_object.val() + '</div><div class="peoplesdataclose"></div></div>';
                email_container_privacy.prepend(friendstr);
                //email_container_privacy.find('.peoplesdata').first().css("width", (email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width() + 38) + "px");
                auto_object.val('');
                auto_object.blur();
                var height = email_container_privacy.height() + 81 + 262;
                email_container_privacy.closest('#inviteForm').css('height', height + "px");
                email_container_privacy.closest('#inviteForm .formContainerglob').css('height', (height - 81) + "px");
                event.preventDefault();
            }
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (email_container_privacy.find('.ui-autocomplete').height() < 144) {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'hidden');
        } else {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'auto');
        }
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
    }
}

function addsponsorautocomplete(auto_object) {
    if (auto_object.length == 0)
        return;
    // Get the current channel id.
    current_channel_id = channelGlobalID();
    var email_container_privacy = auto_object.parent().parent();

    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        search: function(event, ui) {
            var su = SelectedUsers();
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/channel-autocomplete-sponsor.php') + "&ds=" + su.join(',') + '&cid=' + current_channel_id);
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var selectedUsername1 = ui.item.username;
            var selectedUserID1 = ui.item.user_id;

            SelectedUsersAdd(selectedUserID1);

            var friendstr = '<div class="peoplesdata formttl" data-email="" data-id="' + selectedUserID1 + '"><div class="peoplesdatainside">' + selectedUsername1 + '</div><div class="peoplesdataclose"></div></div>';
            email_container_privacy.prepend(friendstr);
            //email_container_privacy.find('.peoplesdata').first().css("width", (email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width() + 20) + "px");
            auto_object.val('');
            auto_object.blur();
            var height = email_container_privacy.height() + 81 + 262;
            email_container_privacy.find('#inviteForm').css('height', height + "px");
            email_container_privacy.find('#inviteForm .formContainerglob').css('height', (height - 81) + "px");
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13) {
            if (validateEmail(auto_object.val())) {
                var friendstr = '<div class="peoplesdata formttl" data-id="" data-email="' + auto_object.val() + '"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">' + auto_object.val() + '</div><div class="peoplesdataclose"></div></div>';
                email_container_privacy.prepend(friendstr);
                //email_container_privacy.find('.peoplesdata').first().css("width", (email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width() + 38) + "px");
                auto_object.val('');
                auto_object.blur();
                var height = email_container_privacy.height() + 81 + 262;
                email_container_privacy.find('#inviteForm').css('height', height + "px");
                email_container_privacy.find('#inviteForm .formContainerglob').css('height', (height - 81) + "px");
                event.preventDefault();
            }
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (email_container_privacy.find('.ui-autocomplete').height() < 144) {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'hidden');
        } else {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'auto');
        }
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
    }
}

function autocompleteDiscover(auto_object) {
    if (auto_object.length == 0)
        return;
    var email_container_privacy = auto_object.parent();

    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        search: function(event, ui) {
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/discover-autocomplete.php'));
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var value_display = ucwords("" + ui.item.value_display);
            var code = ucwords("" + ui.item.code);
            var id = ui.item.id;
            var state_code = ucwords("" + ui.item.state_code);
            var is_country = ui.item.is_country;

            auto_object.val(value_display);
            auto_object.attr('data-code', code);
            auto_object.attr('data-id', id);
            auto_object.attr('data-state-code', state_code);
            auto_object.attr('data-iscountry', is_country);
            event.preventDefault();
            if ($('#mapsearchbutton').length > 0) {
                $('#mapsearchbutton').click();
            }
            if ($('.discover_button').length > 0) {
                $('.discover_button').click();
            }
            if ($('.search-button').length > 0) {
                $('.search-button').click();
            }
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);

        if (code === 13 || code === 9) {
            if (AutocompleteCitiesExists($citynameaccent.val()) === null) {
                event.preventDefault();
                return;
            }
            if (code === 13) {
                $(this).blur();
            }
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + ucwords(item.value) + "</a>")
                .appendTo(ul);
    }
}

function autocompleteMedia(auto_object) {
    if (auto_object.length == 0)
        return;
    var container = auto_object.parent();
    auto_object.autocomplete({
        appendTo: container,
        delay: 5,
        search: function(event, ui) {
            var type = $("#t").val();
            var append = "&t=" + type;
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/media-autocomplete.php') + append);
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var value_display = ucwords("" + ui.item.value_display);
            auto_object.val(value_display);
            event.preventDefault();
            if ($("#t").val() == "u") {
                document.location.href = ui.item.us_link;
            } else {
                $('.SearchSubmit').click();
            }
        }
    })
            .keydown(function(event) {
                var code = (event.keyCode ? event.keyCode : event.which);
                if (code === 13) {
                    $("#c").val('');
                }
            }
            )
            .data("ui-autocomplete")._renderItem = function(ul, item) {
        if ($("#t").val() == "u") {
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a class='auto_tuber'>" + item.label + "</a>")
                    .appendTo(ul);
        } else {
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a title='" + item.value + "'><span class='searchttl'>" + item.value + "</span></a>")
                    .appendTo(ul);
        }
    }
}
function autocompleteReviews(auto_object) {
    if (auto_object.length == 0)
        return;
    var email_container_privacy = auto_object.parent();
    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 500,
        search: function(event, ui) {
            var type = auto_object.attr('data-type');
            var append = "&t=" + type;
            if( auto_object.hasClass("nameOfPlace") && auto_object.hasClass("ui-autocomplete-input") ){
                auto_object.autocomplete('option', 'source', '/ajax/review_autocomplete?' + append);
            }else{
                auto_object.autocomplete('option', 'source', ReturnLink('/ajax/review-autocomplete.php') + append);
            }
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var link_page = "" + ui.item.link;
            document.location.href = link_page;
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13 || code === 9) {
            if (AutocompleteCitiesExists($citynameaccent.val()) === null) {
                event.preventDefault();
                return;
            }
            if (code === 13) {
                $(this).blur();
            }
        } else {
            auto_object.attr('data-code', '');
            auto_object.attr('data-hotel', '');
            auto_object.attr('data-city', '');
            auto_object.attr('data-state-code', '');
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    }
}
function autocompleteLiveCam(auto_object) {
    if (auto_object.length == 0)
        return;
    var email_container_privacy = auto_object.parent();
    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        search: function(event, ui) {
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/live-autocomplete.php') );
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var link_page = "" + ui.item.title;
            auto_object.val(link_page);
            $('.liveMapSearchIcon').click();
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13 || code === 9) {
            if (AutocompleteCitiesExists($citynameaccent.val()) === null) {
                event.preventDefault();
                return;
            }
            if (code === 13) {
                //$('.liveMapSearchIcon').click();
            }
        } 
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.value + "</a>")
                .appendTo(ul);
    }
}
function autocompleteHotels(auto_object) {
    if (auto_object.length == 0)
        return;
    var email_container_privacy = auto_object.parent();

    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        search: function(event, ui) {
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/hotel-autocomplete.php'));
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var value_display = ucwords("" + ui.item.value_display);
            var code = ucwords("" + ui.item.code);
            var state_code = ucwords("" + ui.item.state_code);
            var h_id = ui.item.id;
            var city_code = ui.item.city_code;
            auto_object.val(value_display);
            auto_object.attr('data-code', code);
            auto_object.attr('data-hotel', h_id);
            auto_object.attr('data-city', city_code);
            auto_object.attr('data-state-code', state_code);
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13 || code === 9) {
            if (AutocompleteCitiesExists($citynameaccent.val()) === null) {
                event.preventDefault();
                return;
            }
            if (code === 13) {
                $(this).blur();
            }
        } else {
            auto_object.attr('data-code', '');
            auto_object.attr('data-hotel', '');
            auto_object.attr('data-city', '');
            auto_object.attr('data-state-code', '');
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + ucwords(item.value) + "</a>")
                .appendTo(ul);
    }
}
function autocompleteRestaurants(auto_object) {
    if (auto_object.length == 0)
        return;
    var email_container_privacy = auto_object.parent();

    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        search: function(event, ui) {
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/restaurant-autocomplete.php'));
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var value_display = ucwords("" + ui.item.value_display);
            var code = ucwords("" + ui.item.code);
            var state_code = ucwords("" + ui.item.state_code);
            var h_id = ui.item.id;
            var city_code = ui.item.city_code;
            auto_object.val(value_display);
            auto_object.attr('data-code', code);
            auto_object.attr('data-hotel', h_id);
            auto_object.attr('data-city', city_code);
            auto_object.attr('data-state-code', state_code);
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13 || code === 9) {
            if (AutocompleteCitiesExists($citynameaccent.val()) === null) {
                event.preventDefault();
                return;
            }
            if (code === 13) {
                $(this).blur();
            }
        } else {
            auto_object.attr('data-code', '');
            auto_object.attr('data-hotel', '');
            auto_object.attr('data-city', '');
            auto_object.attr('data-state-code', '');
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + ucwords(item.value) + "</a>")
                .appendTo(ul);
    }
}
function autocompletePois(auto_object) {
    if (auto_object.length === 0)
        return;
    var email_container_privacy = auto_object.parent();

    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        search: function(event, ui) {
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/poi-autocomplete.php'));
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var value_display = ucwords("" + ui.item.value_display);
            var code = ucwords("" + ui.item.code);
            var state_code = ucwords("" + ui.item.state_code);
            var h_id = ui.item.id;
            var city_code = ui.item.city_code;
            auto_object.val(value_display);
//            auto_object.attr('data-code', code);
            auto_object.attr('data-hotel', h_id);
//            auto_object.attr('data-city', city_code);
//            auto_object.attr('data-state-code', state_code);
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13 || code === 9) {
            if (code === 13) {
                $(this).blur();
            }
        } else {
//            auto_object.attr('data-code', '');
            auto_object.attr('data-hotel', '');
//            auto_object.attr('data-city', '');
//            auto_object.attr('data-state-code', '');
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + ucwords(item.value) + "</a>")
                .appendTo(ul);
    };
}


function addmoreusersautocomplete_connections() {
    $('#addmoretext').autocomplete({
        appendTo: ".emailcontainer",
        delay: 5,
        //source: ReturnLink('/ajax/channel-autocomplete-users.php'),
        search: function(event, ui) {
            var su = SelectedUsers();
            $('#addmoretext').autocomplete('option', 'source', ReturnLink('/ajax/channel-autocomplete-connections.php') + "&ds=" + su.join(','));
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var selectedUsername1 = ui.item.username;
            var selectedUserID1 = ui.item.user_id;

            SelectedUsersAdd(selectedUserID1);

            var friendstr = '<div class="peoplesdata formttl" data-email="" data-id="' + selectedUserID1 + '"><div class="peoplesdatainside">' + selectedUsername1 + '</div><div class="peoplesdataclose"></div></div>';
            $('.emailcontainer').prepend(friendstr);
            //$('.emailcontainer .peoplesdata').first().css("width", ($('.emailcontainer .peoplesdata').first().find('.peoplesdatainside').width() + 20) + "px");
            $('#addmoretext').val('');
            $('#addmoretext').blur();
            var height = $('#inviteForm .emailcontainer').height() + 81 + 262;
            $('#inviteForm').css('height', height + "px");
            $('#inviteForm .formContainerglob').css('height', (height - 81) + "px");
            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13) { //Enter keycode or tab
            if (validateEmail($('#addmoretext').val())) {
                var friendstr = '<div class="peoplesdata formttl" data-id="" data-email="' + $('#addmoretext').val() + '"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">' + $('#addmoretext').val() + '</div><div class="peoplesdataclose"></div></div>';
                $('.emailcontainer').prepend(friendstr);
                //$('.emailcontainer .peoplesdata').first().css("width", ($('.emailcontainer .peoplesdata').first().find('.peoplesdatainside').width() + 38) + "px");
                $('#addmoretext').val('');
                $('#addmoretext').blur();
                var height = $('#inviteForm .emailcontainer').height() + 81 + 262;
                $('#inviteForm').css('height', height + "px");
                $('#inviteForm .formContainerglob').css('height', (height - 81) + "px");
                event.preventDefault();
            }
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if ($('.emailcontainer .ui-autocomplete').height() < 144) {
            $('.emailcontainer .ui-autocomplete').css('overflow', 'hidden');
        } else {
            $('.emailcontainer .ui-autocomplete').css('overflow', 'auto');
        }
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
    }

}
function addmoreusersautocomplete_parent(auto_object, channel_id) {
    var email_container_privacy = auto_object.closest('#InsideNormal');
    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        search: function(event, ui) {
            auto_object.attr('data-id', '');
            var prval=1;
            if( auto_object.attr('id')=='parent_input' ) prval=2;
            var selected_channels = SelectedChannels(auto_object);
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/channel-autocomplete-parent.php') + "&dsc=" + selected_channels.join(',') + "&pr=" + prval + "&channel_id=" + channel_id);
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var wrong = ui.item.wrong;
            var right = ui.item.right;
            //var oldVal = $('#SearchField').val();
            event.preventDefault();
            right = right.replace(/(<([^>]+)>)/ig, "");
            auto_object.val(right);
            auto_object.attr('data-id', ui.item.id);
            auto_object.attr('data-url', ui.item.url);
            auto_object.attr('data-logo', ui.item.logo);
            auto_object.attr('data-str', ui.item.name_cr);
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (email_container_privacy.find('.ui-autocomplete').height() < 144) {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'hidden');
        } else {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'auto');
        }
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a title='" + item.right + "'>" + item.label + "<span class='searchttl'>" + item.name + "</span></a>")
                .appendTo(ul);
    }

}
function addmoreusersautocomplete_privacy(auto_object, channel_id) {
    var email_container_privacy = auto_object.parent().parent();
    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        //source: ReturnLink('/ajax/channel-autocomplete-users.php'),
        search: function(event, ui) {
            var selected_users = SelectedUsers(auto_object);
            var selected_channels = SelectedChannels(auto_object);
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/channel-autocomplete-privacy.php') + "&dsu=" + selected_users.join(',') + "&dsc=" + selected_channels.join(',') + "&channel_id=" + channel_id);
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var selectedUsername1 = ui.item.username;
            var data_channel = ui.item.data_channel;
            var selectedUserID1 = ui.item.user_id; // The user's id
            var selected_channel_id = ui.item.channel_id; // The channel's id

            // Either the uesr's id or the channel's id is filled, check which is and call the corresponding function.
            if (selected_channel_id == '') {
                selected_id = selectedUserID1;
                SelectedUsersAdd(selectedUserID1, auto_object);
            } else {
                selected_id = selected_channel_id;
                SelectedChannelsAdd(selected_channel_id, auto_object);
            }


            var friendstr = '<div class="peoplesdata formttl" data-email="" data-id="' + selected_id + '" data-channel="' + data_channel + '"><div class="peoplesdatainside">' + selectedUsername1 + '</div><div class="peoplesdataclose"></div></div>';
            email_container_privacy.prepend(friendstr);
            //email_container_privacy.find('.peoplesdata').first().css("width", (email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width() + 20) + "px");
            auto_object.val('');
            auto_object.blur();
            //var height = email_container_privacy.height()+81+262;
            //$('#inviteForm').css('height',height+"px");
            //$('#inviteForm .formContainerglob').css('height',(height-81)+"px");
            event.preventDefault();
        }
    })/*.keydown(function(event){
     var code = (event.keyCode ? event.keyCode : event.which);		
     if(code === 13) { //Enter keycode or tab
     if(validateEmail(auto_object.val())){
     var friendstr='<div class="peoplesdata formttl" data-id="" data-email="'+auto_object.val()+'"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">'+auto_object.val()+'</div><div class="peoplesdataclose"></div></div>';
     email_container_privacy.prepend(friendstr);
     email_container_privacy.find('.peoplesdata').first().css("width",(email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width()+38)+"px");
     auto_object.val('');
     auto_object.blur();
     var height = email_container_privacy.height()+81+262;
     $('#inviteForm').css('height',height+"px");
     $('#inviteForm .formContainerglob').css('height',(height-81)+"px");
     event.preventDefault();
     }
     }
     })*/
            .data("ui-autocomplete")._renderItem = function(ul, item) {
        if (email_container_privacy.find('.ui-autocomplete').height() < 144) {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'hidden');
        } else {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'auto');
        }
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
    }

}
function addmoreusersautocomplete_privacy_join(auto_object, channel_id) {
    var email_container_privacy = auto_object.parent().parent();
    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        //source: ReturnLink('/ajax/channel-autocomplete-users.php'),
        search: function(event, ui) {
            var selected_users = SelectedUsers(auto_object);
            var selected_channels = SelectedChannels(auto_object);
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/channel-autocomplete-privacy.php') + "&dsu=" + selected_users.join(',') + "&dsc=" + selected_channels.join(',') + "&channel_id=" + channel_id + "&join=1");
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var selectedUsername1 = ui.item.username;
            var data_channel = ui.item.data_channel;
            var selectedUserID1 = ui.item.user_id; // The user's id
            var selected_channel_id = ui.item.channel_id; // The channel's id

            // Either the uesr's id or the channel's id is filled, check which is and call the corresponding function.
            if (selected_channel_id == '') {
                selected_id = selectedUserID1;
                SelectedUsersAdd(selectedUserID1, auto_object);
            } else {
                selected_id = selected_channel_id;
                SelectedChannelsAdd(selected_channel_id, auto_object);
            }


            var friendstr = '<div class="peoplesdata formttl" data-email="" data-id="' + selected_id + '" data-channel="' + data_channel + '"><div class="peoplesdatainside">' + selectedUsername1 + '</div><div class="peoplesdataclose"></div></div>';
            email_container_privacy.prepend(friendstr);
            //email_container_privacy.find('.peoplesdata').first().css("width", (email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width() + 20) + "px");
            auto_object.val('');
            auto_object.blur();

            event.preventDefault();
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (email_container_privacy.find('.ui-autocomplete').height() < 144) {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'hidden');
        } else {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'auto');
        }
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
    }

}

function addmoreusersautocomplete_privacy_journal(auto_object) {
    var email_container_privacy = auto_object.parent().parent().parent();

    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        //source: ReturnLink('/ajax/channel-autocomplete-users.php'),
        search: function(event, ui) {
            var selected_users = SelectedUsers(auto_object);
            var selected_channels = SelectedChannels(auto_object);
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/journal_autocomplete_privacy.php') + "&dsu=" + selected_users.join(',') + "&dsc=" + selected_channels.join(','));
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var selectedUsername1 = ui.item.username;
            var data_channel = ui.item.data_channel;
            var selectedUserID1 = ui.item.user_id; // The user's id
            var selected_channel_id = ui.item.channel_id; // The channel's id

            selected_id = selectedUserID1;
            SelectedUsersAdd(selectedUserID1, auto_object);


            var friendstr = '<div class="peoplesdata formttl" data-email="" data-id="' + selected_id + '" data-channel="' + data_channel + '"><div class="peoplesdatainside">' + selectedUsername1 + '</div><div class="peoplesdataclose"></div></div>';
            email_container_privacy.find('.emailcontainer').prepend(friendstr);
            //email_container_privacy.find('.peoplesdata').first().css("width", (email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width() + 20) + "px");
            auto_object.val('');
            auto_object.blur();

            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13) { //Enter keycode or tab
            if (validateEmail(auto_object.val())) {
                var friendstr = '<div class="peoplesdata formttl" data-id="" data-email="' + auto_object.val() + '"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">' + auto_object.val() + '</div><div class="peoplesdataclose"></div></div>';
                email_container_privacy.find('.emailcontainer').prepend(friendstr);
                //email_container_privacy.find('.peoplesdata').first().css("width", (email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width() + 38) + "px");
                auto_object.val('');
                auto_object.blur();
            }
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (email_container_privacy.find('.ui-autocomplete').height() < 144) {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'hidden');
        } else {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'auto');
        }
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
    }

}
function incrementObjectsViews(entity_type,data_id) {
    $.ajax({
        url: ReturnLink('/ajax/increment_objects_views.php?no_cach=' + Math.random()),
        data: {entity_type: entity_type,id:data_id},
        type: 'post'
    });
}
function addmoreusersautocomplete_custom_journal(auto_object) {
    var email_container_privacy = auto_object.parent().parent().parent();

    auto_object.autocomplete({
        appendTo: email_container_privacy,
        delay: 5,
        //source: ReturnLink('/ajax/channel-autocomplete-users.php'),
        search: function(event, ui) {
            var selected_users = SelectedUsers(auto_object);
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/journal_autocomplete_privacy.php') + "&dsu=" + selected_users.join(','));
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var selectedUsername1 = ui.item.username;
            var selectedUserID1 = ui.item.user_id; // The user's id

            // Either the uesr's id or the channel's id is filled, check which is and call the corresponding function.
            selected_id = selectedUserID1;
            SelectedUsersAdd(selectedUserID1, auto_object);

            var friendstr = '<div class="peoplesdata" data-email="" data-id="' + selected_id + '"><div class="peoplesdatainside">' + selectedUsername1 + '</div><div class="peoplesdataclose"></div></div>';
            //email_container_privacy.prepend(friendstr);
            email_container_privacy.find('.emailcontainer div:eq(0)').after(friendstr);
            //email_container_privacy.find('.peoplesdata').first().css("width", (email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width() + 20) + "px");
            auto_object.val('');
            auto_object.blur();

            event.preventDefault();
        }
    }).keydown(function(event) {
        var code = (event.keyCode ? event.keyCode : event.which);
        if (code === 13) { //Enter keycode or tab                    
            if (validateEmail(auto_object.val())) {
                var friendstr = '<div class="peoplesdata formttl" data-id="" data-email="' + auto_object.val() + '"><div class="peoplesdataemail_icon"></div><div class="peoplesdatainside">' + auto_object.val() + '</div><div class="peoplesdataclose"></div></div>';
                email_container_privacy.find('.emailcontainer div:eq(0)').after(friendstr);
                //email_container_privacy.find('.peoplesdata').first().css("width", (email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width() + 38) + "px");
                auto_object.val('');
                auto_object.blur();                
            }
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (email_container_privacy.find('.ui-autocomplete').height() < 144) {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'hidden');
        } else {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'auto');
        }
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
    }

}
function addmoreusersautocomplete_share_places(auto_object) {
    var email_container_privacy = auto_object.parent().parent().parent();
    var data_type = email_container_privacy.closest('.sharepopup_container').attr('data-type');
    var share_places = "share_places";
    auto_object.autocomplete({
        appendTo: auto_object.parent().parent(),
        delay: 5,
        search: function(event, ui) {
            var selected_users = SelectedUsers(auto_object);
            auto_object.autocomplete('option', 'source', ReturnLink('/ajax/journal_autocomplete_privacy.php') + "&dsu=" + selected_users.join(',') + "&data_type=" + data_type + "&type=" + share_places);
        },
        focus: function(event, ui) {
            return false;
        },
        select: function(event, ui) {
            var selectedUsername1 = ui.item.username;
            var data_channel = ui.item.data_channel;
            var selectedUserID1 = ui.item.user_id; // The user's id
            var selected_channel_id = ui.item.channel_id; // The channel's id

            selected_id = selectedUserID1;
            SelectedUsersAdd(selectedUserID1, auto_object);


            var friendstr = '<div class="peoplesdata formttl" data-email="" data-id="' + selected_id + '" data-channel="' + data_channel + '"><div class="peoplesdatainside">' + selectedUsername1 + '</div><div class="peoplesdataclose"></div></div>';
            email_container_privacy.find('.emailcontainer').prepend(friendstr);
            //email_container_privacy.find('.peoplesdata').first().css("width", (email_container_privacy.find('.peoplesdata').first().find('.peoplesdatainside').width() + 20) + "px");
            auto_object.val('');
            auto_object.blur();

            event.preventDefault();
        }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        if (email_container_privacy.find('.ui-autocomplete').height() < 144) {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'hidden');
        } else {
            email_container_privacy.find('.ui-autocomplete').css('overflow', 'auto');
        }
        return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
    }

}
/*window.onbeforeunload = function() {
 return "you have unsaved changes, if you leave they will be lost";
 }
 */
/*window.addEventListener("beforeunload", function (e) {
 var confirmationMessage = "\o/";
 
 (e || window.event).returnValue = confirmationMessage; //Gecko + IE
 return confirmationMessage;                            //Webkit, Safari, Chrome
 });*/
/*window.onbeforeclose = function (evt) {  
 var message = 'Are you sure you want to leave?';  
 if (typeof evt == 'undefined') {  
 evt = window.event;  
 }  
 if (evt) {  
 evt.returnValue = message;  
 }  
 return message;  
 }*/
function t(str) {
    
    return $.i18n._(str);
    
}



function sprintf( format, args )
{
  for( var i=0; i < args.length; i++ ) {
    format = format.replace( /%s/, args[i] );
  }
  return format;
}




;

try {
    (function($) {

        $.alerts = {
            // These properties can be read/written by accessing $.alerts.propertyName from your scripts at any time

            verticalOffset: -75, // vertical offset of the dialog from center screen, in pixels
            horizontalOffset: 0, // horizontal offset of the dialog from center screen, in pixels/
            repositionOnResize: true, // re-centers the dialog on window resize
            overlayOpacity: .7, // transparency level of overlay
            overlayColor: '#000000', // base color of overlay
            draggable: false, // make the dialogs draggable (requires UI Draggables plugin)
            okButton: '&nbsp;ok&nbsp;', // text for the OK button
            cancelButton: '&nbsp;cancel&nbsp;', // text for the Cancel button
            dialogClass: null, // if specified, this class will be applied to all dialogs

            // Public methods

            alert: function(message, title, callback) {
                if (title == null)
                    title = 'Alert';
                $.alerts._show(title, message, null, 'alert', function(result) {
                    if (callback)
                        callback(result);
                });
            },
            confirm: function(message, title, callback) {
                if (title == null)
                    title = 'Confirm';
                $.alerts._show(title, message, null, 'confirm', function(result) {
                    if (callback)
                        callback(result);
                });
            },
            prompt: function(message, value, title, callback) {
                if (title == null)
                    title = 'Prompt';
                $.alerts._show(title, message, value, 'prompt', function(result) {
                    if (callback)
                        callback(result);
                });
            },
            // Private methods

            _show: function(title, msg, value, type, callback) {

                $.alerts._hide();
                $.alerts._overlay('show');

                $("BODY").append(
                        '<div id="popup_container">' +
                        '<h1 id="popup_title"></h1>' +
                        '<div id="popup_close"></div>' +
                        '<div id="popup_content">' +
                        '<div id="popup_message"></div>' +
                        '</div>' +
                        '</div>');

                if ($.alerts.dialogClass)
                    $("#popup_container").addClass($.alerts.dialogClass);

                // IE6 Fix
                var pos = ($.browser.msie && parseInt($.browser.version) <= 6) ? 'absolute' : 'fixed';

                $("#popup_container").css({
                    position: pos,
                    zIndex: 99999,
                    padding: 0,
                    margin: 0
                });

                $("#popup_title").text(title);
                $("#popup_content").addClass(type);
                $("#popup_message").text(msg);
                $("#popup_message").html($("#popup_message").text().replace(/\n/g, '<br />'));
                /*if($('#popup_message').height()>50){
                 $('#popup_message').css("height","60px");
                 $('#popup_message').css("bottom","26px");				
                 }else{
                 $('#popup_message').css("height","auto");	
                 $('#popup_message').css("bottom","43px");
                 }*/
                $('#popup_message').css("top", ((122 - $('#popup_message').height()) / 2) + "px");


                $("#popup_container").css({
                    minWidth: $("#popup_container").outerWidth(),
                    maxWidth: $("#popup_container").outerWidth()
                });
                $("#popup_close, #popup_overlay").click(function() {
                    $.alerts._hide();
                    callback(false);
                });
                $.alerts._reposition();
                $.alerts._maintainPosition(true);

                //$("#popup_message").after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /> <input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /></div>');
                switch (type) {
                    case 'alert':
                        $("#popup_message").after('<div id="popup_panel"><div id="popup_ok" class="active">' + $.alerts.okButton + '</div></div>');
                        $("#popup_ok").click(function() {
                            $.alerts._hide();
                            callback(true);
                        });
                        $("#popup_ok").focus().keypress(function(e) {
                            if (e.keyCode == 13 || e.keyCode == 27)
                                $("#popup_ok").trigger('click');
                        });
                        break;
                    case 'confirm':
                        $("#popup_message").after('<div id="popup_panel"><div id="popup_ok">' + $.alerts.okButton + '</div><div id="popup_seperator"></div><div id="popup_cancel">' + $.alerts.cancelButton + '</div></div>');
                        $("#popup_ok").click(function() {
                            $.alerts._hide();
                            if (callback)
                                callback(true);
                        });
                        $("#popup_cancel").click(function() {
                            $.alerts._hide();
                            if (callback)
                                callback(false);
                        });
                        $("#popup_ok").focus();
                        $("#popup_ok, #popup_cancel").keypress(function(e) {
                            if (e.keyCode == 13)
                                $("#popup_ok").trigger('click');
                            if (e.keyCode == 27)
                                $("#popup_cancel").trigger('click');
                        });
                        break;
                    case 'prompt':
                        $("#popup_message").append('<br /><input type="text" size="30" id="popup_prompt" />').after('<div id="popup_panel"><div id="popup_ok">' + $.alerts.okButton + '</div><div id="popup_seperator"></div><div id="popup_cancel">' + $.alerts.cancelButton + '</div></div>');
                        $("#popup_prompt").width($("#popup_message").width());
                        $("#popup_ok").click(function() {
                            var val = $("#popup_prompt").val();
                            $.alerts._hide();
                            if (callback)
                                callback(val);
                        });
                        $("#popup_cancel").click(function() {
                            $.alerts._hide();
                            if (callback)
                                callback(null);
                        });
                        $("#popup_prompt, #popup_ok, #popup_cancel").keypress(function(e) {
                            if (e.keyCode == 13)
                                $("#popup_ok").trigger('click');
                            if (e.keyCode == 27)
                                $("#popup_cancel").trigger('click');
                        });
                        if (value)
                            $("#popup_prompt").val(value);
                        $("#popup_prompt").focus().select();
                        break;
                }
                /*if($('#popup_message').height()>50){
                 $('#popup_panel').css("top","79px");
                 }else{
                 $('#popup_panel').css("top","70px");
                 }*/
                $('#popup_panel').css("bottom", "11px");
                // Make draggable
                if ($.alerts.draggable) {
                    try {
                        $("#popup_container").draggable({handle: $("#popup_title")});
                        $("#popup_title").css({cursor: 'move'});
                    } catch (e) { /* requires jQuery UI draggables */
                    }
                }
            },
            _hide: function() {
                $("#popup_container").remove();
                $.alerts._overlay('hide');
                $.alerts._maintainPosition(false);
            },
            _overlay: function(status) {
                switch (status) {
                    case 'show':
                        $.alerts._overlay('hide');
                        $("BODY").append('<div id="popup_overlay"></div>');
                        $("#popup_overlay").css({
                            position: 'absolute',
                            zIndex: 99998,
                            top: '0px',
                            left: '0px',
                            width: '100%',
                            height: $(document).height(),
                            background: $.alerts.overlayColor,
                            opacity: $.alerts.overlayOpacity
                        });

                        break;
                    case 'hide':
                        $("#popup_overlay").remove();
                        break;
                }
            },
            _reposition: function() {
                var top = (($(window).height() / 2) - ($("#popup_container").outerHeight() / 2)) + $.alerts.verticalOffset;
                var left = (($(window).width() / 2) - ($("#popup_container").outerWidth() / 2)) + $.alerts.horizontalOffset;
                if (top < 0)
                    top = 0;
                if (left < 0)
                    left = 0;

                // IE6 fix
                if ($.browser.msie && parseInt($.browser.version) <= 6)
                    top = top + $(window).scrollTop();

                $("#popup_container").css({
                    top: top + 'px',
                    left: left + 'px'
                });
                $("#popup_overlay").height($(document).height());
            },
            _maintainPosition: function(status) {
                if ($.alerts.repositionOnResize) {
                    switch (status) {
                        case true:
                            $(window).bind('resize', function() {
                                $.alerts._reposition();
                            });
                            break;
                        case false:
                            $(window).unbind('resize');
                            break;
                    }
                }
            }

        }

        // Shortuct functions
        jAlert = function(message, title, callback) {
            $.alerts.alert(message, title, callback);
        }

        jConfirm = function(message, title, callback) {
            $.alerts.confirm(message, title, callback);
        };

        jPrompt = function(message, value, title, callback) {
            $.alerts.prompt(message, value, title, callback);
        };

    })(jQuery);
} catch (e) {

}