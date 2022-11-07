/**
 * some global varaibles
 */
var bottom1_h,bottom2_h, bottomt_h;//the size of the footer depends on the logged state
$(document).ready(function() {
    bottom1_h = 60;
    bottom2_h = USER_IS_LOGGED ? 124 : 148;//the size of the footer depends on the logged state
    bottomt_h = bottom1_h + bottom2_h;
	$('#FooterCollaspeExpand').click(function(event) {
		event.stopImmediatePropagation();
		event.preventDefault();
		if ($('.BottomFooterButton').hasClass('BottomFooterButtonCollapse')) {
			$('.BottomFooterButton').removeClass('BottomFooterButtonCollapse').addClass('BottomFooterButtonExpand');
			$('.FooterCollaspeExpandOver .ProfileHeaderOverin').html(t('open menu'));
			$('#BottomContainer2').animate({
				height: 0 + 'px'
			},
			{
				complete: function() {
					$('#BottomContainer2').hide();
					FixFooter();
				},
				step: function(now) {
					$("html, body").animate({scrollTop: $(document).height()}, 1);
				}
			});
		} else {
			$('.BottomFooterButton').removeClass('BottomFooterButtonExpand').addClass('BottomFooterButtonCollapse');
			$('.FooterCollaspeExpandOver .ProfileHeaderOverin').html(t('close menu'));
			$('#BottomContainer2').show().animate({
				height: bottom2_h + 'px'
			}, {
				complete: function() {
					FixFooter();
				},
				step: function(now) {
					$("html, body").animate({scrollTop: $(document).height()}, 1);
				}
			});
		}
		return false;
	});
	
	FixFooter();

	$(window).scroll(function() {
		//console.log('scroll');
		FixFooter();
	}).resize(function() {
		//console.log('resize');
		FixFooter();
	});
});

function fixChatBar() {
	var top = $(document).scrollTop();
	var pos = $('#BottomContainer').offset();
	var chat_not_fixed_at = pos.top - $(window).height();
}

function getDocHeight() {
	var D = document;
	return Math.max(
			Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
			Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
			Math.max(D.body.clientHeight, D.documentElement.clientHeight)
			);
}

function FooterGetHeight() {
	bottomt_h = $('#BottomContainer2').is(':visible') ? bottom1_h + bottom2_h : bottom1_h;

	return bottomt_h;
}

function FixFooter() {

	FooterGetHeight();

	var _bottom2_h = $('#BottomContainer2').is(':visible') ? bottom2_h : 0;

	//var pos = $('#BottomContainer2').offset();
	//var body_height = pos.top + bottom2_h;
	var body_height = getDocHeight();
	var wh = $(window).height();
	if ($.browser.msie)
		wh += 4;
	
        if( $('.BottomFooterButton').hasClass('BottomFooterButtonCollapse') ){
            $('.footer_style_blank').css('height','230px');
        }else{
            $('.footer_style_blank').css('height','106px');
        }
	/*if (body_height <= (wh)) {
		$('#BottomContainer2').css({position: 'fixed', left: '0px', 'bottom': '0px'});
		$('#BottomContainer').css({position: 'fixed', left: '0px', 'bottom': _bottom2_h + 'px'});
		$('#chatContainer').css({position: 'fixed', left: '0px', 'bottom': bottomt_h + 'px'});
	} else {
		$('#BottomContainer2').css({position: '', left: '0px', 'bottom': ''});
		$('#BottomContainer').css({position: '', left: '0px', 'bottom': ''});
		fixChatBar();
	}*/
}