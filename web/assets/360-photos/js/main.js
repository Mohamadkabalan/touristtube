var selectedItem;
function onLoad() {
	document.addEventListener("deviceready", onDeviceReady, false);
	$("#loader-wrapper").fadeOut("slow");
}

function onDeviceReady() {
	setTimeout(init, 100);
	$("#myPanel").panel("open");
}

function init()
{
	if (!Modernizr.fullscreen) {
	    $("#fullscreen-button").remove();
	}

	if (Modernizr.touchevents) {
	    $("#page-1-desktop").remove();
	    $("#page-1-touch").css("visibility", "visible");
	} else {
	    $("#page-1-touch").remove();
	}

	window.addEventListener("devicemotion", function (event) {
	    if (event.rotationRate.alpha || event.rotationRate.beta || event.rotationRate.gamma) {
	    } else {
	    	$("#gyro").remove();
	    }
	});

	$("#listview").on('tap', 'li[id^="li"]*', onMenuClick);
	$("#menu_list_view").on('tap', 'li[id^="li"]', onMenuClick);

	selectProject($("#menu_list_view li[id^='li']").first());

	if (getMobileOperatingSystem() == "Android") {
	    $("#ttMobileButton").attr('href', 'https://play.google.com/store/apps/details?id=com.touristtube.android.mobile&hl=en');
	    $("#mobileAppIcon").attr('src', 'assets/android-icon.png');
	} else if (getMobileOperatingSystem() == "iOS") {
	    $("#ttMobileButton").attr('href', 'https://appsto.re/lb/Sfifkb.i');
	    $("#mobileAppIcon").attr('src', 'assets/iOS-icon.png');
	} else {//Win32
	    $("#ttButton").css("width", "100%");
	    $("#ttMobileButton").hide();
	}
}

/* ------------------------------------*/
/* ----------  Fullscreen METHODS ---------- */
/* ------------------------------------*/

function toggleFullScreen() {
	if (Modernizr.fullscreen) {
	    // fullscreen supported
	    if ((document.fullScreenElement && document.fullScreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen && !document.msFullscreenElement)) {
			if (document.documentElement.requestFullScreen) {
			    document.documentElement.requestFullScreen();
			} else if (document.documentElement.mozRequestFullScreen) {
			    document.documentElement.mozRequestFullScreen();
			} else if (document.documentElement.webkitRequestFullScreen) {
			    document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
			} else if (document.documentElement.msRequestFullscreen) {
			    document.documentElement.msRequestFullscreen();
			}
			document.getElementById("fullscreen-button").className = 'fullscreen-exit';
	    } else {
			if (document.cancelFullScreen) {
			    document.cancelFullScreen();
			} else if (document.mozCancelFullScreen) {
			    document.mozCancelFullScreen();
			} else if (document.webkitCancelFullScreen) {
			    document.webkitCancelFullScreen();
			} else if (document.msExitFullScreen) {
			    document.msExitFullScreen();
			}
			document.getElementById("fullscreen-button").className = 'fullscreen-enter';
	    }
	} else {
	}
}

/* ------------------------------------*/
/* ----------  HELP METHODS ---------- */
/* ------------------------------------*/

function closeHelp() {
	$("#help").removeClass("visible");
	$("#help").addClass("hidden");
}

function showHelp() {
	$("#help").toggleClass("hidden");
	$("#help").toggleClass("visible");
}

function activateHelpPage(pageNum) {
	if (pageNum == 1) {
	    document.getElementById("page-1").className = "scrollable visible";
	    document.getElementById("page-2").className = "hidden";
	    document.getElementById("num-1").className = "paginationNumber active";
	    document.getElementById("num-2").className = "paginationNumber";
	} else {
	    document.getElementById("page-1").className = "hidden";
	    document.getElementById("page-2").className = "scrollable visible";
	    document.getElementById("num-1").className = "paginationNumber";
	    document.getElementById("num-2").className = "paginationNumber  active";
	}
}

function getMobileOperatingSystem() {
	var userAgent = navigator.userAgent || navigator.vendor || window.opera;

	if (userAgent.match(/iPad/i) || userAgent.match(/iPhone/i) || userAgent.match(/iPod/i))
	{
	    return 'iOS';
	} else if (userAgent.match(/Android/i))
	{
	    return 'Android';
	} else {
	    return 'unknown';
	}
}

/* ------------------------------------*/
/* ----------  ACTION METHODS ---------- */
/* ------------------------------------*/

function onMenuClick(e) {
	//var ref = $(this).data("ref");
	//
	selectProject($(this));
}

function highlightMenuItem(item) {

	if (typeof (item) == "string")
	    item = $("#menu_list_view li[data-ref='" + item + "']");
	//
	$("#menu_list_view li[id^='li'] a").removeClass("ui-btn-active");
	$(item).find('a').click();

}

function selectProject(ref, startupScene) {

	var name, type, entity_name, country, id, cat_id, division_id, sub_division_id;

	if (typeof (ref) == "object") {
	    name = ref.data("name");
	    type = ref.data("type");
	    entity_name = ref.data("entityname");
	    country = ref.data("country") || "";
	    country = country.toLowerCase()
	    id = ref.data("id");
	    cat_id = ref.data("cat_id");
	    division_id = ref.data("division_id");
	    sub_division_id = ref.data("sub_division_id");
	}

	highlightMenuItem(ref);
	loadTTVtour(name, type, entity_name, country, id, cat_id, division_id, sub_division_id, startupScene);
}

function loadTTVtour(name, type, entity_name, country, id, cat_id, division_id, sub_division_id, startupScene) {
	var ifr = document.getElementById("external");
	if (null != startupScene && typeof startupScene != "undefined")
	    startupScene = "startscene" + startupScene;
	else
	    startupScene = "";
	//
	var vtourURL  ="/360-photos/tour/" + type + "/" + entity_name +  "/" + country + "/" + id + "/" + cat_id + "/" + division_id /*+ "/" + sub_division_id*/;
	ifr.contentWindow.location.replace(vtourURL);

	/*var ifr = document.getElementById("external");
    if (null != startupScene && typeof startupScene != "undefined") 
	    startupScene = "startscene" + startupScene;
    else startupScene = "";
    //
    ifr.contentWindow.location.replace(window.location.pathname.substring(0, window.location.pathname.lastIndexOf("/"))+'/vtours/'+projectDir+'/index.html?' + startupScene);*/
}