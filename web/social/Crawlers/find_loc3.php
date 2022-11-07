<?php 
$conn = mysqli_connect("localhost","root","tt","tt");
if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($conn));
}

if(isset($_POST['update']))
{
$nw_id 	= $_POST['w_id'];
$nn_id	= $_POST['nn_id'];
$Query="update tt.discover_poi_new set city_id=$nw_id,status=15 where id=$nn_id";
 if (mysqli_query($conn, $Query ))
		    {
				echo " $nn_id New record updated successfully-$nw_id<br>";
		    
			
			} 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
}

if(isset($_POST['submit']))
{
 $n_city = $_POST['location'];
 $n_city = str_replace("'","''",trim($n_city));
 $n_details=$_POST['details'];
 $add_old=$_POST['add_old'];
 $n_details = str_replace("'","''",trim($n_details));
 $n_id 	= $_POST['poi_id'];
 $sql1="Select id,name from webgeocities where country_code='US' and name='$n_city' limit 1"; 
$result2 = mysqli_query($conn,$sql1) or die( mysqli_error());
while($row1 = mysqli_fetch_array($result2))
{
$w_id=$row1['id'];
$w_name=$row1['name'];
}

if ($add_old=="")
{
$Query="update tt.discover_poi_new set city='$n_city',address='$n_details',status=11 where id=$n_id";
 if (mysqli_query($conn, $Query ))
		    {
				echo " $n_id New record updated successfully-$n_city<br>";
		    
			
			} 
		    else 
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
}
else 
{

$Query="update tt.discover_poi_new set city='$n_city',status=11 where id=$n_id";
 if (mysqli_query($conn, $Query ))
		    {
				echo " $n_id New record updated successfully witout address-$n_city<br>";
		    
			
			} 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
}

}
 
if(isset($_POST['not_match']))
{
$n_city = $_POST['location'];
 $n_city = str_replace("'","''",trim($n_city));
 $n_details=$_POST['details'];
 $add_old=$_POST['add_old'];
 $n_details = str_replace("'","''",trim($n_details));
 $n_id 	= $_POST['poi_id'];

$Query="update tt.discover_poi_new set city='$n_city',address='$n_details',status=22 where id=$n_id";
 if (mysqli_query($conn, $Query ))
		    {
				echo " $n_id not matched<br>";
		    } 
		    else
		    {
			echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
			}
}



$sql="Select id,name as poi_name,latitude as poi_latitude,longitude as poi_longitude,address from tt.discover_poi_new where country='US' and city_id=0 and status=0 order by id ASC limit 1"; 
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());

print "<table border=1>
<tr><td>p_id</td><td>poi_name</td><td>lat</td><td>long</td></tr>";
while($row = mysqli_fetch_array($result1))
{
  $id 		= 	$row['id'];
  $poi_name	= 	$row['poi_name'];
  $lat		=	$row['poi_latitude'];
  $long		=	$row['poi_longitude'];
  $address_old	=	$row['address'];
  
 print "<tr><td>$id</td><td>$poi_name</td><td>$lat</td><td>$long</td></tr>";
 

}
print "</table>";?>

 <script>
var country,state,city,pinCode;
function createCORSRequest(method, url) {
var xhr = new XMLHttpRequest();

  if ("withCredentials" in xhr) {
    // XHR for Chrome/Firefox/Opera/Safari.
    xhr.open(method, url, true);

  } else if (typeof XDomainRequest != "undefined") {
    // XDomainRequest for IE.
    xhr = new XDomainRequest();
    xhr.open(method, url);

  } else {
    // CORS not supported.
    xhr = null;
  }
  return xhr;
}

function getLocationDetails()
{
hide();
latitude1=document.getElementById("latitude").value;
longitude1=document.getElementById("longitude").value;

var url="https://maps.googleapis.com/maps/api/geocode/json?latlng="+
        latitude1+","+longitude1+"&sensor=true";
    var xhr = createCORSRequest('POST', url);
           if (!xhr) {
             alert('CORS not supported');
           }
         
           xhr.onload = function() {
        
            var data =JSON.parse(xhr.responseText);
            
            if(data.results.length>0)
            {
            
            var locationDetails=data.results[0].formatted_address;
            var  value=locationDetails.split(",");
            
            count=value.length;
            
             country=value[count-1];
             state=value[count-2]; 
             city=value[count-3];
			 city1=city.trim();
             pin=state.split(" ");
             pinCode=pin[pin.length-1];
             state=state.replace(pinCode,' ');         
             document.getElementById("val").value=city1;
			 document.getElementById("country").value=country;
			 document.getElementById("details").value=locationDetails;
			 
            }
            else
            {
             document.getElementById("messageBox").style.visibility="visible";
             document.getElementById("message").innerHTML=
               "No location available for provided details.";
            }
            
           };

           xhr.onerror = function() {
               alert('Woops, there was an error making the request.');
               
           };
        xhr.send();
	
	/*$.post('process.php', {id:city}, function(data) {
	alert(data);
  
  return false; //prevent from reloading the page
});*/
        
}

function hide()
{
document.getElementById("messageBox").style.visibility="hidden";
}

</script>


<form name="find_loc" method="post" action='#'>
 <body onload="hide()" style="background-color: #333; color: #ccc;">
<div >

</div><br><br>
<div>
Latitude:<input type="text" id="latitude" value="<?php echo $lat; ?>"><br><br>
Longitude:<input type="text" id="longitude" value="<?php echo $long; ?>"><br>
<br>
<input type="button" name="butt" value="Get Location" 
onclick="getLocationDetails()"></button>

<br><br>
<input type="text" id="val" name="location">
<input type="text" id="country" name="country">
<input type="text" id="add_old" name="add_old" value="<?php echo $address_old; ?>">
<input type="text" id="poi_id" name="poi_id" value="<?php echo $id; ?>">

</div>
<div id="messageBox" 
  style="position:fixed;top:30%;left:30%;
  width:200px;height:100px;border-radius:15px;text-align:center;
background-color:skyblue;
">
<div style="position:absolute;margin-top:0px;left:2px;
height:20px;width:98%;border-radius:5px;overflow:hidden;
background-color:pink; 
"><label id="title" style="color:blue;">Skin Advisor</label></div>
<div style="position:absolute;margin:2px;top:25px;height:80px;width:100%;">
<label id="message" style="
color:blue;font-family:Helvetica;"></label></div>
</div>

<textarea rows="4" cols="50" id="details" name="details">
</textarea><br>
<input type="submit" name="submit"><input type="submit" name="not_match" value="not match"><br>
<input type="text" id="w_id" name="w_id" value="<?php echo $w_id; ?>">
<input type="text" id="w_name" name="w_name" value="<?php echo $w_name; ?>">
<input type="text" id="nn_id" name="nn_id" value="<?php echo $n_id; ?>">
<br>
<input type="submit" name="update" value="update">
<form>

</body>