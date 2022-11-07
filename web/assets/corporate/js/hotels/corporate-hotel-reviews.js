$(document).ready(function () {
    $(".tab-list li a").click(function (event) {
	event.preventDefault();
	$(".tab-list li a").removeClass('active');
	$(".image-div").removeClass('active');
	$(this).addClass('active');
	$(this).next('div.image-div').addClass('active');
	var $id = $(this).attr('id');
	var $sub = $('#sub_' + $id);
	$('.contenttabs').hide();
	$sub.show();
    });

    initHotelsReviewMap();
    $(window).resize(function () {
	initHotelsReviewMap();
    });
});

function initHotelsReviewMap() {
    if ($('.imagefirstbut').length > 0) {
	var pagedimensions = parent.window.returnIframeDimensions();
	$(".imagefirstbut").unbind('click');
	$(".imagefirstbut").bind("click", function (event) {
	    event.preventDefault();
	    var $href = $(this).attr('href');
	    if (pagedimensions[0] > 1148)
		pagedimensions[0] = 1148;
	    if (pagedimensions[0] < 768)
		pagedimensions[0] = '100%';
	    $.fancybox({
		width: pagedimensions[0],
		href: $href,
		closeBtn: true,
		autoSize: false,
		autoScale: true,
		autoHeight: true,
		transitionIn: 'elastic',
		transitionOut: 'fadeOut',
		type: 'iframe',
		padding: 0,
		margin: 0,
		scrolling: 'no',
		helpers: {
		    overlay: {closeClick: true}
		}
	    });
	});
    }
}
