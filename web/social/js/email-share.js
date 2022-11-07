function viewEmailShareForm(title,vlink){
	
	var href = ReturnLink('/emailfriend.php?title=' + title + '&link=' + vlink);
	
	$.fancybox({
		padding	:0,
		margin	:0,
		transitionIn: 'none',//'elastic',
		transitionOut: 'none',//'elastic',
		/*speedIn: 500,
		speedOut: 300,
		centerOnScroll: false,*/
		autoScale: false,
		autoDimensions: false,
		href: href,
		width: 405,
		minWidth: 405,
		maxWidth: 405,
		height: 365,
		minHeight: 365,
		maxHeight: 365,
		type: 'iframe',
		scrolling: 'no'
	});
	
}