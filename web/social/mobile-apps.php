<?php
	
	$path = "";
	
	$bootOptions = array ( "loadDb" => 0, "loadLocation" => 0, "requireLogin" => 0 );
	include_once ( $path . "inc/common.php" );
	include_once ( $path . "inc/bootstrap.php" ); 
	include_once ( $path . "inc/functions/users.php" );
	
	include("TopIndex.php");

?>

<div id="MiddleBody">
	<div class="StaticBody">
		<div class="StaticTitle"><?php echo _('Mobile Apps');?></div>
		<?php echo _('Here you will get:');?>
		<ul>
			<li><?php echo _('Instantaneous and direct upload of photos and videos from the mobile device');?></li>
			<li><?php echo _('Real-time touring experience messaging and chatting with Friends using Tourist Tweet&copy;');?></li>
			<li><?php echo _('A large choice of popular and enjoyable mobile applications available for you to download');?></li>
			<li><?php echo _('Compatible with Android and IOS on most mobile devices and at different resolutions');?></li>
			<li><?php echo _('Interactive menu with Grid View of available downloads and customizable display settings');?></li>
			<li><?php echo _('Geographic links to locations of uploaded videos with Built in map to help you easily find countries');?></li>
			<li>
				<?php echo _('Instant GPS positioning upon Log in or Sign in with map location of all willing Tourist Tubers within a given perimeter. This will:');?>
				<ul>
					<li><?php echo _('Empower you to find directions - within a specified range - to nearby places of interest such as Subways; Restaurants; Hotels; and Museums');?></li>
					<li><?php echo _('Perform trip tracking of your visiting patterns and update your profile according to your preferences');?></li>
					<li><?php echo _('Show you a map with locations of all accepting Tourist Tubers - within a given perimeter, filtered with specified parameters (sex, age, country of origin)');?></li>
				</ul>
			</li>
		</ul>
	</div>
</div>
	
<?php include("BottomIndex.php"); ?>