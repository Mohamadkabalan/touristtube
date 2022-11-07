var columnReadyCounter = 0;
var $allContentBoxes = $(".content-box"),
	$allTabs = $(".tabs li a"),
	$el, $colOne, $colTwo, $colThree,
	hrefSelector = "",
	speedOne = 1000,
	speedTwo = 2000,
	speedThree = 1500;
var which_tab = -1;
var n_tabs = 6;
var slideT ;
// This is the callback function for the "hiding" animations
// it gets called for each animation (since we don't know which is slowest)
// the third time it's called, then it resets the column positions
function ifReadyThenReset() {	
    columnReadyCounter++;	
    if (columnReadyCounter == 3) {
        //$(".col").not(".current .col").css("top", 298);
        $(".col").not(".current .col").css({top: '298px'})
        columnReadyCounter = 0;
    }
};
function upperSlideTab()
{
	slideT = setInterval(function(){
		if( flagme == 0){
			which_tab++;
			if(which_tab == n_tabs) which_tab = 0;
			$('#tabs a').eq(which_tab).click();
		}
	},5500);	
}


function initSlotMachineTabs()
{
	$(".content-box").show();
	$("#slot-machine-tabs").delegate(".tabs a", "click", function(event) {
		
		
		which_tab=$(this).parent().index();
		
		event.stopImmediatePropagation();
		
		
	
		$el = $(this);
		
		if ( (!$el.hasClass("current")) && ($(":animated").length == 0 ) ) {
			
			
			
			
			// current tab correctly set
			$(".tabs li a").removeClass("current");
			$el.addClass("current");
			
			// optional... random speeds each time.
			speedOne = /*Math.floor(Math.random()*1000) +*/ 500;
			speedTwo = /*Math.floor(Math.random()*1000) +*/ 750;
			speedThree = /*Math.floor(Math.random()*1000) +*/ 1000;
		
			// each column is animated upwards to hide
			// kind of annoyingly redudundant code
			$colOne = $(".box-wrapper .current .col-one");
			$colOne.animate({
				"top": -$colOne.height()
			}, speedOne);
		
			$colTwo = $(".box-wrapper .current .col-two");
			$colTwo.animate({
				"top": -320//-$colTwo.height()
			}, speedTwo);
		
			$colThree = $(".box-wrapper .current .col-three");
			$colThree.animate({
				"top": -281//-$colThree.height()
			}, speedThree);
			
			// new content box is marked as current
			$(".content-box").removeClass("current");
			if($el.hasClass('tabsone')) hrefSelector = 'one';
			else if($el.hasClass('tabstwo')) hrefSelector = 'two';
			else if($el.hasClass('tabsthree')) hrefSelector = 'three';
			else if($el.hasClass('tabsfour')) hrefSelector = 'four';
			else if($el.hasClass('tabsfive')) hrefSelector = 'five';
			else if($el.hasClass('tabssix')) hrefSelector = 'six';
			hrefSelector = '#' + hrefSelector;
			$(hrefSelector).addClass("current");
		
			// columns from new content area are moved up from the bottom
			// also annoying redundant and triple callback seems weird
			$(".box-wrapper .current .col-one").animate({
				"top": 0
			}, speedOne, function() {
				ifReadyThenReset();
			});
	
			$(".box-wrapper .current .col-two").animate({
				"top": 0
			}, speedTwo, function() {
				ifReadyThenReset();
			});
		
			$(".box-wrapper .current .col-three").animate({
				"top": 0
			}, speedThree, function() {
				ifReadyThenReset();
			});
		
			
			clearInterval(slideT);
			upperSlideTab();
		
		};
			
	});
		
}

// When the DOM is ready
$(document).ready(function() {

	

	
	initSlotMachineTabs();	
	upperSlideTab();
	
	
	// first tab and first content box are default current
	$(".tabs li:first-child a, .content-box:first").addClass("current");
	$(".box-wrapper .current .col").css({top: '0px'});
	
	

});