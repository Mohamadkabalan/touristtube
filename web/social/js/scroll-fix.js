
//fixes the middleLayout in case contents are being shown under the floating footer


function setMiddleLayout(){
	//var margin = 46 + 60 + 98; //all footer height
	var margin = 300;
	try{
		margin = FooterGetHeight();
	}catch(e){
		
	}
	//console.log(margin);
	var $middle = $('#MiddleInsideNormal'); //add other selectors that define the "middle" or implement a golbal middle in TopIndex.php and BottomIndex.php
	
	if( $middle.length === 0 ) return ;
	//$middle.css('padding-bottom', '');
	var current_padding = $middle.innerHeight() - $middle.height();
	//console.log( "cp = " + current_padding );
	var vertical_space_on_bottom_without_padding = $(window).height() - ($middle.offset().top + $middle.height());
	var vertical_space_on_bottom_with_padding = vertical_space_on_bottom_without_padding + current_padding;
	//console.log( "vs1 = " + vertical_space_on_bottom_without_padding );
	//console.log( "vs2 = " + vertical_space_on_bottom_with_padding );
	if( vertical_space_on_bottom_without_padding < margin ){
		$middle.css('padding-bottom', (vertical_space_on_bottom_without_padding + 5) + 'px');
		//$middle.css('padding-bottom','');
	}else{
		$middle.css('padding-bottom','');
	}
}

$(document).ready(function(){
	setMiddleLayout();
	
	$(window).resize(function(){
		setMiddleLayout();
	}).scroll(function(){
		setMiddleLayout();
	});
});