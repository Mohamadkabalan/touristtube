<!-- <div id="BottomSlider"> -->
<div class="footer_style_blank" data-case="<?php if(userIsLogged()) echo '1'; else echo '2'; ?>"></div>    
   <div class="footer_container_all"> 
    
       <div id="BottomContainer">
    <div class="footer_top_bar">
              <a title="<?php echo _('tourist tube') ?>" href="<?php echo generateLangURL('/'); ?>" class="logo_bottom"></a>
              <nav>
        <ul>
                  <li><a title="<?php echo _('home'); ?>" href="<?php echo generateLangURL('/'); ?>"><?php echo _('home'); ?></a></li>
                </ul>
      </nav>
              <div class="top_scroll">
        <ul>
                  <li>
            <div class="FooterBottomBackTop1"> 
                <a id="BackToTop" style="position: absolute; right: 0px" href="#"><?php echo _('Back To Top'); ?></a>
            </div>
          </li>
                  <li> <a id="FooterCollaspeExpand" href="<?php GetLink('') ?>">
                    <div class="FooterCollaspeExpandOver">
                    <div class="ProfileHeaderOverin"><?php echo _('open menu'); ?></div>
                    <div class="icons-overtik"></div>
                  </div>
                    <div class="BottomFooterButton BottomFooterButtonExpand" id="BottomFooterButton"> </div>
                    </a> </li>
                </ul>
      </div>
            </div>
    <!--end footer top bar --> 
    
  </div>
       <div id="BottomContainer2" style="display:none; height: 0;">
           <div class="BottomContainer2Over">
                    <div class="ProfileHeaderOverin"></div>
                    <div class="icons-overtik"></div>
                </div>
    <div id="BottomContainerLinks">
      <ul>
        <li>
            <h1><?php echo _('touristtube.com') ?> </h1>
<ul>
<li><a title="<?php echo _('About touristtube.com'); ?>" data-title="<?php echo _('About touristtube.com'); ?>" href="<?php GetLink('about-us'); ?>" class="requestinvite ttmenuclass"><?php echo _('about us'); ?></a> </li>
<li><a title="<?php echo _('Things to do'); ?>" data-title="<?php echo _('Things to do'); ?>" href="<?php GetLink('things-to-do'); ?>" class="requestinvite ttmenuclass"><?php echo _('Things to do'); ?></a> </li>
<li><a title="<?php echo _('Access our partners\' channels'); ?>" data-title="<?php echo _('Access our partners\' channels'); ?>" href="<?php echo ReturnLink('channels', null , 0, 'channels'); ?>" class="requestinvite ttmenuclass"><?php echo _('TT channels'); ?></a> </li>
<li><a title="<?php echo _('Tourist Live'); ?>" data-title="<?php echo _('Tourist Live'); ?>" href="<?php GetLink('live'); ?>" class="requestinvite ttmenuclass"> <?php echo _('Tourist Live'); ?> </a></li>

 <li> <a title="<?php echo _('Review and Rate Hotels and Restaurants'); ?>" data-title="<?php echo _('Review and Rate Hotels and Restaurants'); ?>" href="<?php GetLink('review'); ?>" class="requestinvite ttmenuclass"> <?php echo _('review & rate'); ?> </a> </li>
    <?php if( userIsLogged() ){ ?>
<!--        <li> <a title="<?php //echo _('send invitation') ?>" data-title="<?php //echo _('send invitation') ?>" href="<?php //GetLink('account/invite'); ?>" class="requestinvite ttmenuclass"> <?php //echo _('send invitation'); ?> </a> </li>-->
    <?php }else{ ?>
        <li> <a title="<?php echo _('register') ?>" data-title="<?php echo _('register') ?>" href="<?php GetLink('register'); ?>" class="requestinvite ttmenuclass"> <?php echo _('register'); ?> </a> </li>
    <?php } ?>
        <li> <a title="<?php echo _('touristtube blog') ?>" target="_blank" data-title="<?php echo _('touristtube blog') ?>" href="http://blog.touristtube.com" class="requestinvite ttmenuclass"> <?php echo _('touristtube blog') ?> </a> </li>
</ul>
        </li>
        <li>
        <h2><?php echo _('Legal') ?></h2>
          <ul>
                  <li><a title="<?php echo _('privacy policy') ?>" data-title="<?php echo _('privacy policy') ?>" href="<?php GetLink('privacy-policy') ?>" class="ttmenuclass"><?php echo _('privacy policy') ?></a> </li>
                  <li><a title="<?php echo _('terms & conditions') ?>" data-title="<?php echo _('terms & conditions') ?>" href="<?php GetLink('terms-and-conditions');?>" class="ttmenuclass"><?php echo _('terms & conditions') ?></a></li>
                  <li><a title="<?php echo _('disclaimer') ?>" data-title="<?php echo _('disclaimer') ?>" href="<?php GetLink('disclaimer') ?>" class="ttmenuclass"><?php echo _('disclaimer') ?></a></li>
          </ul>
        </li>
        <li>
                  <h2><?php echo _('support & contact') ?></h2>
                  <ul>
            <li><a title="<?php echo _('support') ?>" data-title="<?php echo _('support') ?>" href="<?php GetLink('support') ?>" class="ttmenuclass"><?php echo _('support') ?></a></li>
            <li><a title="<?php echo _('contact us') ?>" data-title="<?php echo _('contact us') ?>" href="<?php GetLink('contact') ?>" class="ttmenuclass"><?php echo _('contact us') ?></a></li>
            <li><a title="<?php echo _('help') ?>" data-title="<?php echo _('help') ?>" href="<?php GetLink('help') ?>" class="ttmenuclass"><?php echo _('help') ?></a></li>
            <li><a title="<?php echo _('faq') ?>" data-title="<?php echo _('faq') ?>" href="<?php GetLink('faq') ?>" class="ttmenuclass"><?php echo _('faq') ?></a></li>
          </ul>
       </li>
<li>
<?php if(!userIsLogged()): ?>
<h2><?php echo _('Account Info') ?></h2>
<ul>
<li> <a title="<?php echo _('register') ?>" data-title="<?php echo _('register') ?>" href="<?php GetLink('register') ?>" class="requestinvite ttmenuclass"><?php echo _('register') ?></a> </li>
<li> <a title="<?php echo _('sign in') ?>" data-title="<?php echo _('sign in') ?>" id="footer_sign_in" href="javascript:;" class="ttmenuclass"><?php echo _('sign in') ?></a> </li>
</ul>
<?php endif ?>
</li>
      </ul>
            </div>
  </div>
</div>
<script>
    function BottomFooterButton()
    {
	//update
        $("#BottomContainer2").slideToggle();
    }
</script>


<script type="text/javascript">
  $(document).ready(function() {
      $('body').css('touch-action', 'auto');
  });

<!-- Slider script  -------------------------->

if (typeof swiper !== 'undefined' && $.isFunction(swiper)) {
    $(window).bind('resize',function(){
        var w=$(window).width();
        var slidesPerView = 4 ;
		if(w>300 &&  w<480){slidesPerView =2 ;}
		else if(w>481 &&  w<767){slidesPerView =3 ;}
	    else if(w>768 &&  w<960){slidesPerView =4 ;}
		var swiper = new swiper('.swiper-container', {
		pagination: '.swiper-pagination',
		slidesPerView:slidesPerView,
		paginationClickable: true,
		spaceBetween: 0,
		freeMode: true
            });
        });
        
    $(window).load(function(){
        $(window).trigger('resize');
    });
 
} 

// 17 March **********************************
//------------------------------------------------------------------
     /*$(document).ready(function(){
        var height  =   $(document).height();
        var wid =   jQuery(window).width();
        if(wid<768){
            $('#MiddleInside, #MiddleInsideNormal').css('min-height',height+'px');
        }
    });*/
	
   // New add 23 March **********************************
   
    $(document).ready(function(){
      var wid = jQuery(window).width();
	    if(wid<768){
        $(document).scroll(function () {
            var y = $(this).scrollTop();
             if(y >800 ) {
     			$('.footer_container_all').show();
            } else {
                $('.footer_container_all').hide();
            }
        });
	  }
    });

 
</script>
<?php include("closing-footer.php");?>