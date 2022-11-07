var MarqueeTool;
var maxWidth = 800;
var maxHeight = 256;

jQuery.noConflict();

function updateImg(x,y,w,h){
    var thisImg = jQuery("#sampleid");
    var imageWidth = thisImg.width();
    var imageHeight = thisImg.height();
    var image_xRatio = imageWidth /w;
    var image_yRatio = imageHeight / h;

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
    
	
jQuery(document).ready(function(){
    if(filetype!=''){
        switch((filetype)){
            case 1:
            case '1':
            case 'editChannelRight_data5':
                    //maxWidth=125;
                    //maxHeight=125;
                    maxWidth=100;
                    maxHeight=100;
            break;
            case 'photos_posts':
            case 'TT_photos_posts':
            case 'postMediaPV':
            case 'CHpostMediaPV':
                    maxWidth=355;
                    maxHeight=197;
            break;
            case 'flashPhoto':
                    maxWidth=400;
                    maxHeight=300;
            break;
            case 'uploadReview_Img':
                    maxWidth=585;
                    maxHeight=334;
            break;
            case 'account_pic':
            case 'changePP':
                    maxWidth=130;
                    maxHeight=130;
            break;
            case 2:
            case '2':
            case 'editChannelRight_data6':
                    maxWidth=800;
                    maxHeight=256;
            break;
            case 'event':
            case 'edit_event':
            case 'edit_userevent':
            case 'uploadBtnevent':
            case 'TT_uploadBtnevent':
                    maxWidth=510;
                    maxHeight=333;
            break;
            case 'brochure':
            case 'uploadBtnbrochure':
            case 'editChannelRight_data10':
                    maxWidth=55;
                    maxHeight=76;
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
    jQuery(".accountbut2").click(function(){
        var coord_wreal = jQuery("#coord_w").val();
        var coord_hreal = jQuery("#coord_h").val();
        if( coord_wreal==0 ) coord_wreal = maxWidth;
        if( coord_hreal==0 ) coord_hreal = maxHeight;	
        var senddata = 'coord_x='+jQuery("#coord_x").val()+'&coord_y='+jQuery("#coord_y").val()+'&coord_w='+coord_wreal+'&coord_h='+coord_hreal+'&filename='+curname+'&pathto='+pathto+'&code='+code+'&filetype='+filetype+'&discover_nam='+discover_nam;

        jQuery.ajax({
            url: ReturnLink("/createImage.php"),
            type: "GET",
            data: senddata,
            processData: false,
            contentType: false,
            success: function (res){
                if(res){
                    var newstrimage='<img src="'+res+ '?x=' + Math.random() + '"/>';
                    if(filetype=="uploadBtnbrochure" || filetype=="uploadBtnevent" || filetype=="TT_uploadBtnevent"){
                        parent.window.updateImage_Add_Brochure(newstrimage,curname,filetype);
                    }else if(filetype=="account_pic"){  
                        newstrimage='<a class="userAvatarLink" href="'+ReturnLink('/media/tubers/'+res)+ '"><img id="AccountOverviewImage" src="'+ReturnLink('/media/tubers/Profile_'+res)+ '?x=' + Math.random() + '"/></a>';
                        parent.window.updateImage(newstrimage,res,filetype);
                    }else if(filetype=="changePP"){  
//                        newstrimage='<a class="userAvatarLink" href="'+ReturnLink('/media/tubers/'+res)+ '"><img id="AccountOverviewImage" src="'+ReturnLink('/media/tubers/Profile_'+res)+ '?x=' + Math.random() + '"/></a>';
                        parent.window.updatePP(res);
                    }else if(filetype=="edit_userevent"){  
                        newstrimage='<img width="510" height="333" src="'+res+ '?x=' + Math.random() + '"/>';
                        parent.window.updateImage(newstrimage,curname,filetype);
                    }else{
                        parent.window.updateImage(newstrimage,curname,filetype);
                    }
                }
            }
        });		
    });
    jQuery(".accountbut_post").click(function(){
        var coord_wreal = jQuery("#coord_w").val();
        var coord_hreal = jQuery("#coord_h").val();
        if( coord_wreal==0 ) coord_wreal = maxWidth;
        if( coord_hreal==0 ) coord_hreal = maxHeight;

        var senddata = 'coord_x='+jQuery("#coord_x").val()+'&coord_y='+jQuery("#coord_y").val()+'&coord_w='+coord_wreal+'&coord_h='+coord_hreal+'&filename='+curname+'&code='+code+'&pathto='+pathto;

        jQuery.ajax({
            url: ReturnLink("/createImage.php"),
            type: "GET",
            data: senddata,
            processData: false,
            contentType: false,
            success: function (res){
                if(res){
                    if(filetype=="photos_posts" || filetype=="TT_photos_posts" ){
                        parent.window.updateImage_Add_Posts(curname,filetype);
                    }else if(filetype=="postMediaPV" || filetype=="CHpostMediaPV"){
                        newstrimage='<img src="'+res+ '?x=' + Math.random() + '" width="86" height="48"/>';
                        parent.window.updateImagePOST(newstrimage,curname,pathto,filetype,0);
                    }
                }
            }
        });

    });
    jQuery("#CreateImage").click(function(){
        var coord_wreal = jQuery("#coord_w").val();
        var coord_hreal = jQuery("#coord_h").val();
        if( coord_wreal==0 ) coord_wreal = maxWidth;
        if( coord_hreal==0 ) coord_hreal = maxHeight;
        var senddata = 'coord_x='+jQuery("#coord_x").val()+'&coord_y='+jQuery("#coord_y").val()+'&coord_w='+coord_wreal+'&coord_h='+coord_hreal+'&filename='+curname+'&filetype='+filetype+'&code='+code+'&pathto='+pathto;

        jQuery.ajax({
            url: ReturnLink("/createImage.php"),
            type: "GET",
            data: senddata,
            processData: false,
            contentType: false,
            success: function (res){
                if(res){
                    var newstrimage='<img src="'+res+ '?x=' + Math.random() + '"/>';
                    if( (""+filetype) =="uploadBtnbrochure"){
                        parent.window.updateImage_Add_Brochure(newstrimage,curname,filetype);
                    }else if( (""+filetype) =="uploadBtnevent" || filetype=="TT_uploadBtnevent"){
                        parent.window.updateImage_Add_Brochure(newstrimage,curname,filetype);
                    }else if(filetype=="flashPhoto"){  
                        newstrimage='<img src="'+res+ '?x=' + Math.random() + '" width="86" height="48"/>';
                        parent.window.updateImage(newstrimage,curname,pathto,filetype);
                    }else{
                        parent.window.updateImage("#uploadDropDiv"+filetype+" .ImageStanInside",newstrimage,curname);
                    }
                }
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