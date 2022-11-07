$(document).ready(function () {
    $(".menuestyle_leftmenue-click").click(function () {
	$("#leftmenuecorpcontainer").show();
	$(".downarrow_menuefilters").hide();
	$(".menuestyle_leftmenue-click").hide();
    });
    $(".uparrow_menuefilters-click").click(function () {
	$("#leftmenuecorpcontainer").hide();
	$(".downarrow_menuefilters").show();
	$(".menuestyle_leftmenue-click").show();
    });
    $(".menuestyle_filter-click").click(function () {
	$(".filtersearchtogglecontainer").show();
	$(".downarrow_filter").hide();
	$(".menuestyle_filter-click").hide();
    });
    $(".uparrow_filter-click").click(function () {
	$(".filtersearchtogglecontainer").hide();
	$(".downarrow_filter").show();
	$(".menuestyle_filter-click").show();
    });
    if( $('#priceslider').length>0 ){
	var priceslider = new Slider("#priceslider", {
	    min: 0,
	    max: 10,
	    value: [0, 10],
	    labelledby: ['priceslider_low', 'priceslider_high']
	});
    }
    if( $('#distanceslider').length>0 ){
	var distanceslider = new Slider("#distanceslider", {
	    min: 0,
	    max: 10,
	    value: [0, 10],
	    labelledby: ['distanceslider_low', 'distanceslider_high']
	});
    }
    $(document).on('click',".changeSearch" ,function(){
	$(this).hide();
	$('.cancelSearch').show();
	$('.formsearch').show();	
    });
    $(document).on('click',".cancelSearch" ,function(){
	$(this).hide();
	$('.formsearch').hide();
	$('.changeSearch').show();	
    });
});