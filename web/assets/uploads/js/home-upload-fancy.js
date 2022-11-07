var MarqueeTool;
var maxWidth = 800;
var maxHeight = 256;

jQuery.noConflict();

function updateImg(x,y,w,h){
    var thisImg = jQuery("#sampleid");
    var imageWidth = thisImg.width();
    var imageHeight = thisImg.height();
    var image_xRatio = imageWidth /(w-maxWidth);
    var image_yRatio = imageHeight / (h-maxHeight);
    var max_X = (imageWidth - w);
    var max_Y = (imageHeight - h);

    var x1 = x * image_xRatio;
    var y1 = y * image_yRatio;

    if( x1 > 0 ) x1 = max_X;
    if( y1 > max_Y ) y1 = max_Y;
    if( max_X < 0 ) x1 = 0;
    if( max_Y < 0 ) y1 = 0;
    thisImg.css({
            top: '-'+y1+'px',
            left: '-'+x1+'px'
    });

    var coords_x = x1 + x;
    var coords_y = y1 + y;

    $('coord_x').value = coords_x;
    $('coord_y').value = coords_y;
}

function onMarqueeUpdate() {
    var coords = MarqueeTool.getCoords();    
    if(coords.width!=0 && coords.height!=0){
        $('coord_x').value = coords.x1;
        $('coord_y').value = coords.y1;
        $('coord_w').value = coords.width;
        $('coord_h').value = coords.height;
        updateImg(coords.x1, coords.y1, 1024, 600);
    }else{
        onWindowLoad(maxWidth, maxHeight);
        setTimeout(function(){        
            onMarqueeUpdate();
        },500);
    }
}

function onWindowLoad(ww, hh){
    var ratios=ww/hh;
    new PreviewToolTip('element_container', {id: 'preview'});
    MarqueeTool = new Marquee('sampleid', {
        coords: {x1: 0, y1: 0, width: ww, height: hh},
        preview: '', 
        color: '#333',
        opacity: 0.75,
        previewWidth: 120,
        previewHeight: 120,
        ratio: ratios
    });            
    MarqueeTool.setOnUpdateCallback(onMarqueeUpdate);
}   

jQuery(document).ready(function()
{
    if(filetype!='')
    {
        switch((filetype))
	{
            case 'channel_profile':
		maxWidth=230;
		maxHeight=230;
            break;
            case 'account_pic':
		maxWidth=230;
		maxHeight=230;
            break;
            case 'channel_cover':
		maxWidth=800;
		maxHeight=256;
            break;
        }
    }
    
    setTimeout(function(){        
        onWindowLoad(maxWidth, maxHeight);        
        setTimeout(function(){        
            onMarqueeUpdate();
        },500);
    },1000);
    
    jQuery(".accountbutBRCancel").click(function(){
        parent.window.closeFancyBox();
    });
    
    jQuery(".accountbut2").click(function()
    {
        var coord_wreal = jQuery("#coord_w").val();
        var coord_hreal = jQuery("#coord_h").val();
        if( coord_wreal==0 ) coord_wreal = maxWidth;
        if( coord_hreal==0 ) coord_hreal = maxHeight;	
        var senddata = 'coord_x='+jQuery("#coord_x").val()+'&coord_y='+jQuery("#coord_y").val()+'&coord_w='+coord_wreal+'&coord_h='+coord_hreal+'&filename='+curname+'&pathto='+pathto+'&code='+code+'&filetype='+filetype+'&discover_nam='+discover_nam;

        jQuery.ajax({
            url: generateLangURL("/create-image", 'empty'),
            type: "GET",
            data: senddata,
            processData: false,
            contentType: false,
            success: function (data)
	    {
		var jres = null;
		try {
		    jres = data;
		    var status = jres.status;
		} catch (Ex) {
		}
		
		var newstrimage='<img src="'+jres.link+ '?x=' + Math.random() + '"/>';
                    
		if(filetype=="account_pic")
		{  
		    newstrimage='<a class="userAvatarLink" href="'+generateMediaURL('/media/tubers/'+jres.link)+ '"><img id="AccountOverviewImage" src="'+generateMediaURL('/media/tubers/Profile_'+jres.link+ '?x=' + Math.random() ) + '"/></a>';
		    parent.window.updateImage( newstrimage, jres.link, filetype );
		}else{
		    parent.window.updateImage( newstrimage, curname, filetype );
		}		
            }
        });		
    });

    jQuery("#CreateImage").click(function()
    {
        var coord_wreal = jQuery("#coord_w").val();
        var coord_hreal = jQuery("#coord_h").val();
        if( coord_wreal==0 ) coord_wreal = maxWidth;
        if( coord_hreal==0 ) coord_hreal = maxHeight;
        var senddata = 'coord_x='+jQuery("#coord_x").val()+'&coord_y='+jQuery("#coord_y").val()+'&coord_w='+coord_wreal+'&coord_h='+coord_hreal+'&filename='+curname+'&filetype='+filetype+'&code='+code+'&pathto='+pathto;

        jQuery.ajax({
            url: generateLangURL("/create-image", 'empty'),
            type: "GET",
            data: senddata,
            processData: false,
            contentType: false,
            success: function (data)
	    {
		var jres = null;
		try {
		    jres = data;
		    var status = jres.status;
		} catch (Ex) {
		}
		
		var newstrimage='<img src="'+jres.link+ '?x=' + Math.random() + '"/>';
		parent.window.updateImage( "#uploadDropDiv"+filetype+" .ImageStanInside", newstrimage, curname );
            }
        });

    });
});
jQuery(document).keydown(function(ev) { 
    if(ev.keyCode == 13) {
        if(jQuery("#CreateImage").length>0){
            jQuery("#CreateImage").click();
        }
        if(jQuery(".accountbut_post").length>0){
            jQuery(".accountbut_post").click();
        }
        if(jQuery(".accountbut2").length>0){
            jQuery(".accountbut2").click();
        }
    } 
});