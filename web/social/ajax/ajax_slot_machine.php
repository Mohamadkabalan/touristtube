<?php
$path = "../";
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );
include_once ( $path . "inc/functions/bag.php" );
$ret = '';
$ret .= '
<div id="one" class="content-box current">
    <div class="col-one col" style="top: 0;">
        <img src="'.ReturnLink('media/images/topslider/top1.png').'" alt="Top1"/>
    </div>
    <div class="col-two col customtwo" style="top: 0;">
        '.t( _('Hello!%brWelcome to %span1Tourist Tube...%span2%brThe online tourism sharing site.%brJOIN OUR COMMUNITY%br') , 
                array(
                    '%br'=> '<br/>' ,
                    '%span1'=> '<span>',
                    '%span2'=> '</span>'
                    ) ) .'
    </div>
    <div class="col-three col customthree" style="top: 0;">
        <a href="'.ReturnLink('register').'" class="requestinvite"><img src="'.ReturnLink('media/images/'.LanguageGet().'/topslider/btn_top1.png').'" style="border: none" alt="'._('register').'"/></a>
    </div>
</div>

<div id="two" class="content-box">
    <div class="col-one col">
        <img src="'.ReturnLink('media/images/topslider/top2.png').'"  alt="Top2"/>
    </div>
    <div class="col-two col customtwo">
        '._('<span>Upload Everywhere</span><br/>Share the fun <br>with your loved ones.').'

    </div>
    <div class="col-three col customthree">
        <a href="'. ReturnLink('upload').'" class="requestinvite"><img src="'.ReturnLink('media/images/'.LanguageGet().'/topslider/btn_top2.png').'" style="border: none" alt="'._('upload').'"/></a>
    </div>
</div>

<div id="three" class="content-box">
    <div class="col-one col">
        <img src="'.ReturnLink('media/images/'.LanguageGet().'/topslider/top3.png').'" alt="Top3"/>
    </div>
    <div class="col-two col customtwo">
        '._('<span>Love Traveling?</span><br/>Find the top places to visit <br/>Get the experts\' opinion.').'
    </div>
    <div class="col-three col customthree">
        <a href="'.ReturnLink('upload').'" class="requestinvite"><img src="'.ReturnLink('media/images/'.LanguageGet().'/topslider/btn_top2.png').'" style="border: none" alt="'. _('upload').'"/></a>
    </div>
</div>

<div id="four" class="content-box">
    <div class="col-one col">
        <img src="'.ReturnLink('media/images/topslider/top6.png').'" alt="Top6"/>
    </div>
    <div class="col-two col customsix">
        '._('<span>Looking for a date?</span><br/>Find your significant other<br> and prepare to meet with them.').'
    </div>
    <div class="col-three col customthree">
        <a href="'.ReturnLink('register').'" class="requestinvite"><img src="'.ReturnLink('media/images/'.LanguageGet().'/topslider/btn_top1.png').'" style="border: none" alt="'._('register').'"/></a>
    </div>
</div>

<div id="five" class="content-box">
    <div class="col-one col">
        <img src="'.ReturnLink('media/images/topslider/top4.png').'" alt="Top4"/>
    </div>
    <div class="col-two col customtwo">
        '._('<span>Stay in Touch</span><br/>See the news and what they <br>have to say about it.<br> Add up with your comments.').'
    </div>
    <div class="col-three col customthree">
        <a href="'.ReturnLink('register').'" class="requestinvite"><img src="'.ReturnLink('media/images/'.LanguageGet().'/topslider/btn_top1.png').'" style="border: none" alt="'. _('register').'"/></a>
    </div>
</div>

<div id="six" class="content-box">
    <div class="col-one col">
        <img src="'.  ReturnLink('media/images/topslider/top5.png').'" alt="Top5"/>
    </div>
    <div class="col-two col customtwo">
        '._('<span>Connect with your Mobile</span><br/>Download our mobile application <br>and get the full package!').'
    </div>
    <div class="col-three col customthree">
        <a href="'.ReturnLink('register').'" class="requestinvite"><img src="'.ReturnLink('media/images/'.LanguageGet().'/topslider/btn_top1.png').'" style="border: none" alt="'._('register').'"/></a>
    </div>
</div>';
echo $ret;