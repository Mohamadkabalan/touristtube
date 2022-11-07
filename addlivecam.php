<?php
// input action

if(isset($_POST['submit'])) {
	
    $title = $_POST['title'];
    $cam_url = $_POST['live-url'];
    $city = $_POST['city'];
    $country = $_POST['country'];
	
    // Google api to get latitude and longitude based on country name and state name
    $address = $country.'+'.$city;
    $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$country";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response);
    $latitude = $response_a->results[0]->geometry->location->lat;
    $longitude = $response_a->results[0]->geometry->location->lng;
	
    $Message    .= 'Title : '.$title.', Public Url : '.$cam_url.'\n';
    $Message    .=  $country.', '.$city.', Latitude: '.$latitude.', Longitude: '.$longitude;
	
    mail('charbel@paravision.org','User Live Cam credential',$Message);
	
    $msg = 'successfully added';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Live cam - Add your own live cam.</title>
</head>
<body>
<form name="liv-cam" id="live-cam" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

    <div class="row">
        <label for="title">Title :</label>
        <input name="title" value="" placeholder="Title.." required/>
    </div>

    <div class="row">
        <label for="live-url">Live URL : </label>
        <input name="live-url" value="" placeholder="Live public Camera Address.." required/>
    </div>

    <!--<div class="row">
        <label for="geo-location">Geo location :</label>
        <input name="geo-location" value="" placeholder="latitude.." required size="10"/>&nbsp;&nbsp;
        <input name="longitude" placeholder="longitude.." value="" size="10">
    </div>-->

    <div class="row">
        <label for="Address">Address :</label>
        <input name="city" value="" placeholder="City.." required/> &nbsp;&nbsp;
        <input name="country" value="" placeholder="Country.." required>
    </div>


    <button type="submit" name="submit">Add Cam</button>

    <div><?php if(isset($msg)) echo $msg; ?></div>
</form>
<script>


</script>
</body>
</html>
