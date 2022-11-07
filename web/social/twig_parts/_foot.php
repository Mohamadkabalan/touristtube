<?php

$data['userIsLoggedTxt'] = userIsLogged() ? 'true' : 'false';
$data['isChat'] = (userIsLogged() && !userIsChannel() );

//$data['indexLink'] = ReturnLink('');
//$data['ttTxt'] = _('tourist tube');
//$data['toreplace'] = ReturnLink('media/images/tourist_tube_footer.png');
//$data['footerImg'] = _('tourist tuber logo');
//$data['homeTxt'] = _('Home');
//$data['aboutLink'] = ReturnLink('about-us');
//$data['aboutTxt'] = _('About');
//$data['contactLink'] = ReturnLink('contact');
//$data['contactTxt'] = _('contact');
//$data['topTxt'] = _('Back To Top');
//$data['topImg'] = ReturnLink('media/images/back_to_top.png');

$collapseExpand = '';
$collapseExpandClass = '';
$collapseExpandHide = '';

$collapseExpand = _('open menu');
$collapseExpandClass = 'BottomFooterButtonExpand' ;
$collapseExpandHide = 'style="display:none;"';

$data['collapseExpand'] = $collapseExpand;
$data['collapseExpandClass'] = $collapseExpandClass;
$data['collapseExpandHide'] = $collapseExpandHide;


$data['userIsLogged'] = userIsLogged();
//$data['isOnline'] = (strstr($_SERVER['SERVER_NAME'],'touristtube.com')!=null);
$data['isOnline'] = (strstr($request->server->get('SERVER_NAME', ''),'touristtube.com')!=null);

$this_page = _seoExecutingFile();
$data['pageFileName'] = $this_page;