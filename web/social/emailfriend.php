<?php
    $path = "";

    $bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 1);
    include_once ( $path . "inc/common.php" );
    include_once ( $path . "inc/bootstrap.php" );
	include_once ( $path . "inc/functions/users.php" );
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TOURIST TUBE | EMAIL FRIEND</title>
<?PHP
	//var_dump($_POST);
//	$senderVtitle = $_GET['title'];
//	$senderVlink = $_GET['link'];
	$senderVtitle = $request->query->get('title','');
	$senderVlink = $request->query->get('link','');
	$userInfo = getUserInfo(userGetID());


?>

<script type="text/javascript">
	var senderVtitle = "<?php echo $senderVtitle ?>";
	var senderVlink = "<?php echo $senderVlink ?>";
	
	function onBlur(el){
		if (el.value == '') el.value = el.defaultValue;
	}
	
	function onFocus(el){
		if (el.value == el.defaultValue) el.value = '';
	}
</script>

<script type="text/javascript" src="/assets/vendor/jquery/dist/jquery-1.9.1.min.js"></script>
        
<script type="text/javascript" src="<?php GetLink("assets/common/js/utils.js") ?>"></script>
<script type="text/javascript" src="<?php GetLink("js/emailfriend.js") ?>"></script>
<link type="text/css" href="<?php GetLink("css/emailstyle.css")?>" rel="stylesheet" />

</head>

<body>
<div id="MainContent">   
	    <div id="borderBlock">
        	<div id="email_fBlock">
            	<div id="email_title" class="titlesClass"><?php echo _('EMAIL A FRIEND');?></div>
                <div id="email_vtitle" class="titlesClass"><?PHP echo $senderVtitle; ?></div>
                <div id="email_vlink" class="titlesClass"><?PHP echo $senderVlink; ?></div>
            </div>
            <div id="email_sBlock">
            	<div id="email_to" class="emailsClass"><?php echo _('TO'); ?></div>
                <div><input type="text" name="mails" class="emailBackinput email_inputTo" id="email_inputTo" value="multiple emails? use commas" onBlur="onBlur(this)" onFocus="onFocus(this)"/></div>
                <div class="email_itaText" id="email_fIta"><!--up to 5 recipients--></div>
                <div class="emailsClass"><?php echo _('FROM');?></div>
                <!--div><input type="text" name="frommail" class="emailBackinput" id="email_inputFrom" value="your email address..." onBlur="onBlur(this)" onFocus="onFocus(this)" readonly/></div-->
                <div><input type="text" name="frommail" class="emailBackinput" id="email_inputFrom" value="<?php echo $userInfo['YourEmail']; ?>" readonly/></div>
                <div class="email_itaText" id="email_fIta"></div>
                <div class="emailsClass"><?php echo _('NOTE (OPTIONAL)');?></div>
                <div><input type="text" name="noteop" class="emailBackinput email_inputTo" id="email_inputNote" value="please write your note" onBlur="onBlur(this)" onFocus="onFocus(this)"/></div>           
                <div class="email_itaText" id="email_sIta"><?php echo _('255 characters limit');?></div>
                <div id="statusText"></div>
                <div class="email_send" id="email_send"><?php echo _('send');?></div>
            </div>
        </div>                        
</div>
<?php include("closing-footer.php");?>