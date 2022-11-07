var ThumbPath = '';

function EmbedFlashJW(where, res_list, regWidth, regHeight, ThumbPath , videorelated_id,show_related,is_post,auto_start){
    var res_names = new Array('240 p','360 p','480 p','HD 720 p','HD 1080 p');
    var res_listarr = res_list.split('/*/');
    var res_real=new Array();
    var mobj;
    for(var i=0; i<res_listarr.length; i++){
        mobj = {"file":ReturnLink(res_listarr[i],1) , "label":res_names[i]};
        res_real.push(mobj);
    }
    if(videorelated_id==0){
        jwplayer(where).setup({
            sources: res_real,
            image: ThumbPath,
            ga: {} ,
            stretching: "bestfit",
            autostart: auto_start,                
            logo: {
                file: '',
                link: 'https://www.touristtube.com/'
            },
            skin: {
                name: "glow"
            },
            startparam: "ec_seek",
            height: regHeight,
            width: regWidth,
            mode:"html",
            hlshtml:true,
            primary:"html5",
            controlbar:"bottom"
        });
    }else if(show_related==1){
        jwplayer(where).setup({ 
            sources: res_real,
            image: ThumbPath,
            ga: {} ,
            stretching: "bestfit",
            autostart: auto_start,                
            logo: {
                file: '',
                link: 'https://www.touristtube.com/'
            },
            skin: {
                name: "glow"
            },
            related: {
                file: generateLangURL("/ajax/getVideoRelatedXML?id="+videorelated_id),
                onclick: "link"
            },
            tracks: [{ 
                file: generateLangURL("/ajax/getThumbRelatedVTT?no_cach=" +Math.random()+"&id="+videorelated_id+"&is_post="+is_post), 
                kind: "thumbnails"
            }],
            //startparam: "start",
            startparam: "ec_seek",
            height: regHeight,
            width: regWidth,
            mode:"html",
            hlshtml:true,
            primary:"html5",
            controlbar:"bottom"
        });
    }else{
        jwplayer(where).setup({
            sources: res_real,
            image: ThumbPath,
            ga: {} ,
            stretching: "bestfit",
            autostart: auto_start,                
            logo: {
                file: '',
                link: 'https://www.touristtube.com/'
            },
            skin: {
                name: "glow"
            },           
            tracks: [{ 
                file: generateLangURL("/ajax/getThumbRelatedVTT?no_cach=" +Math.random()+"&id="+videorelated_id+"&is_post="+is_post), 
                kind: "thumbnails"
            }],
            //startparam: "start",
            startparam: "ec_seek",
            height: regHeight,
            width: regWidth,
            mode:"html",
            hlshtml:true,
            primary:"html5",
            controlbar:"bottom"
        });
    }
}
function pauseMyVideo(){
    try{
        jwplayer().pause(true);
    }catch(e){
    }
}