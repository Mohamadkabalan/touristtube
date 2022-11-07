<style type="text/css">
<!--
.coverPageJournal{
	margin:0;
	padding:0;
	border:0;
	width:793px;
	height:1122px;
	background:#1e1e1e;
}
.contentPageJournal1{
	margin:0;
	padding:0;
	border:0;
	width:793px;
	height:1122px;
	background:#1e1e1e;
}
.contentPageJournal2{
	margin:0;
	padding:0;
	border:0;
	width:793px;
	height:1122px;
	background:#1e1e1e;
}
#logoJournal{
	position:absolute;
	top:141px;
	left:49px;
	width:266px;
	height:213px;
	background:transparent url(../journalPdfTemplate/img/coverLogoJournal.png) no-repeat 0px 0px;
}
#shareJournal{
	position:absolute;
	top:379px;
	left:106px;
	width:165px;
	height:61px;
	background:transparent url(../journalPdfTemplate/img/coverShareJournal.png) no-repeat 0px 0px;
	padding:14px 0 0 42px;
	color:#f5f5f5;
	font:16px Arial, Helvetica, sans-serif;
}
#titleJournalImg{
	position:absolute;
	top:539px;
	left:65px;
	width:61px;
	height:49px;
	background:transparent url(../journalPdfTemplate/img/coverTitleJournal.png) no-repeat 0px 0px;
}
#titleJournalTxt{
	position:absolute;
	display:block;
	top:569px;
	left:148px;
	width:565px;
	min-height:18px;
	max-height:45px;
	line-height:18px;
	background:transparent;
	color:#E9C21C;
	font:18px Arial, Helvetica, sans-serif;
}
.coverImageJournal{
	position:absolute;
	display:block;
	overflow:hidden;
	top:601px;
	left:148px;
	max-width:566px;
	max-height:377px;
	border:0;
}
.contentImageJournal_class{
	display:block;
	overflow:hidden;
	position:absolute;
	max-width:566px;
	max-height:377px;
	border:0;
}
.contentImage1Journal1{
	left:190px;
	top:148px;
}
.contentImage2Journal1{
	left:190px;
	top:600px;
}
.contentImage1Journal2{
	left:38px;
	top:148px;
}
.contentImage2Journal2{
	left:38px;
	top:600px;
}
.contentTextJournal_class{
	color:#ffffff;
	font:18px Arial, Helvetica, sans-serif;
	display:block;
	position:absolute;
	min-height:18px;
	max-height:45px;
	line-height:18px;
	width:566px;
}
.contentText1Journal2{
	left:38px;
	top:532px;
}
.contentText2Journal2{
	left:38px;
	top:985px;
}
.contentText1Journal1{
	left:190px;
	top:532px;
}
.contentText2Journal1{
	left:190px;
	top:985px;
}
-->
</style>
<page backtop="0mm" backbottom="0mm" backleft="0mm" backright="0mm" class="coverPageJournal" backcolor="#1e1e1e">
<div id="logoJournal"></div>
<div id="shareJournal">Share <br />your precious moments <br />with your friends</div>
<div id="titleJournalImg"></div>
<div id="titleJournalTxt"><?php echo @$options['items'][$options['default_item']]['text'];?></div>
<img src="<?php echo @$options['items'][$options['default_item']]['cover'];?>" class="coverImageJournal" alt=""/>
</page>
<?php
$item_loop = 0;
$done = false;
$max_items = count($options['items']);
$pg = '1';
$content='';
while( !$done ){
	//each page
	$content .= '<page backtop="0mm" backbottom="0mm" backleft="0mm" backright="0mm" class="contentPageJournal'.$pg.'"  backimg="../journalPdfTemplate/img/bg0-'.$pg.'.jpg">
	<img src="'.$options['items'][$item_loop]['image'].'" alt="" class="contentImageJournal_class contentImage1Journal'.$pg.'"/><div class="contentTextJournal_class contentText1Journal'.$pg.'">'.$options['items'][$item_loop]['text'].'</div>';
	$item_loop++;
	if( $item_loop < $max_items ){
		$content .= '<img src="'.$options['items'][$item_loop]['image'].'" alt="" class="contentImageJournal_class contentImage2Journal'.$pg.'"/><div class="contentTextJournal_class contentText2Journal'.$pg.'">'.$options['items'][$item_loop]['text'].'</div>';
		$item_loop++;
	}
	$content .= '</page>'."\n";
	if($pg == '1'){
		$pg='2';
	}else if($pg == '2'){
		$pg='1';
	}	
	if($item_loop >= $max_items) $done = true;
}
echo $content;
