<?php
/* Array to get Details of the languages  */
$langLogo = array(
    'en'=>array('icon'=>'en.jpg','title'=>'English','shortTitle'=>'en'),
    'fr'=>array('icon'=>'fr.jpg','title'=>'Français','shortTitle'=>'fr')
);
/* -end-  Array to get Details of the languages  */
$data["recentlyViewed"] = _('Recently viewed');
$data["language"] = _('Language');
//$data["currentCurrency"] = isset($_SESSION['currencyName'])?$_SESSION['currencyName']:_('U.S. Dollar');
$data["currentCurrency"] = _('U.S. Dollar');
$data["langIco"] = ReturnLink('media/images/en.jpg');
$data['changeCurLink'] = returnLink("parts/currency.php");;
?>