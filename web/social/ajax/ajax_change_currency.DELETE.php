<?php
$path = "../";

$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" =>0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );
include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );

$user_id = userGetID();
$user_is_logged=0;
if(userIsLogged()){
    $user_is_logged=1;
}

//$rate = xss_sanitize(@$_POST['rate']);
$rate = $request->request->get('rate', '');
//$symb = xss_sanitize(@$_POST['symb']);
//$symb = @$_POST['symb'];
//$name = xss_sanitize(@$_POST['name']);
$symb = $request->request->get('symb', '');
$name = $request->request->get('name', '');
$_SESSION['currencyRate'] = $rate;
$_SESSION['currencySymbol'] = $symb;
$_SESSION['currencyName'] = $name;
$str = '';
$budget = array();
$budget[0]["value"] = '1';
$budget[1]["value"] = '2';
$budget[2]["value"] = '3';
$budget[3]["value"] = '4';
$budget[4]["value"] = '5';

$val75 = ceil(75 /$rate);
$val124 = ceil(124 /$rate);
$val125 = ceil(125 /$rate);
$val199 = ceil(199 /$rate);
$val200 = ceil(200 /$rate);
$val299 = ceil(299 /$rate);
$val300 = ceil(300 /$rate);

$budget[0]["display"] = _('LESS THAN ').$symb.$val75;
$budget[1]["display"] = $symb.$val75._(' TO ').$symb.$val124;
$budget[2]["display"] = $symb.$val125._(' TO ').$symb.$val199;
$budget[3]["display"] = $symb.$val200._(' TO ').$symb.$val299;
$budget[4]["display"] = $symb.$val300.' +';

foreach( $budget as $bd){
    $str .= '<div class="budgetCheckbox" data-value="'.$bd['value'].'"><div class="blockLabel">'.$bd['display'].'</div></div>';
}
$budget2 = array('High<br>'.ceil(500 /$rate).'-'.ceil(1000 /$rate),'Medium<br>'.ceil(200 /$rate).'-'.ceil(500 /$rate),'Low<br>0-'.ceil(200 /$rate));

$Result['checkbox'] = $str;
$Result['hotelBudgetOption1'] = $budget2[0];
$Result['hotelBudgetOption2'] = $budget2[1];
$Result['hotelBudgetOption3'] = $budget2[2];
$Result['currName'] = $name;

echo json_encode( $Result );