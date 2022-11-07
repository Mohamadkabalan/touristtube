<?php
$path = "";
$tpopular = 5;
$bootOptions = array("loadDb" => 1, "loadLocation" => 0, "requireLogin" => 0);
include_once ( $path . "inc/common.php" );
include_once ( $path . "inc/bootstrap.php" );

include_once ( $path . "inc/functions/videos.php" );
include_once ( $path . "inc/functions/users.php" );

$includes = array('css/pagenotfound.css',);

tt_global_set('includes', $includes);

include("TopIndex.php");
?>
<div class="middle_body">
    <div class = "pagenotfound_txtbody">
        <div class = "pagentfnd_txt">
            <h2>Page Not Found</h2>
        </div>
    </div>
</div>

<?php
include("BottomIndex.php");
