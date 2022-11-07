 <meta http-equiv="Content-Type" content="text/html; 
charset=UTF-8" />

 <body onload="hide()" style="background-color: #333; color: #ccc;">

<?php 
error_reporting(e_all);
$zstate = $_GET['zstate'];
$wstate = $_GET['wstate'];
$conn = mysqli_connect("localhost","root","tt","tt");
if (!mysqli_set_charset($conn , "utf8")) {
    printf("Error loading character set utf8: %s\n", mysqli_error($conn));
} else {
    printf("Current character set: %s\n", mysqli_character_set_name($conn));
}  

if(isset($_POST['submit']))
{

if(!empty($_POST['checked']))
{
foreach($_POST['checked'] as $ad) 
{
 echo $ad;
 $selected=$_POST["check_value$ad"];
 
$new_sel= explode('_',$selected);
 $nw_id=$new_sel[0];
 $nd_id=$new_sel[1];

 
 $Query="update tt.zipcodes_new set city_id=$nw_id,status=10 where id=$nd_id";
 if (mysqli_query($conn, $Query ))
      {
    echo " $nd_id New record updated successfully-$nw_id<br>";
      } 
      else
      {
   echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
   }
  }
 }
}

if(isset($_POST['notmatch']))
{

if(!empty($_POST['checked']))
{
foreach($_POST['checked'] as $ad)
{
 echo $ad;
 $selected=$_POST["check_value$ad"];
 echo $selected; 
$new_sel= explode('_',$selected);
 $nw_id=$new_sel[0];
 $nd_id=$new_sel[1];

 

 $Query="update tt.zipcodes_new set status=90 where id=$nd_id";
 if (mysqli_query($conn, $Query ))
      {
    echo " $nd_id Not matched record updated successfully-$nw_id<br>";
      } 
      else
      {
   echo "Error: " . $Query  . "<br>" . mysqli_error($conn);
   }

}
}
}


print "<form action='#' method='post'>";
$sql= "SELECT w.id AS w_id,z.id AS z_id, w.name AS w_name, z.admin_city AS z_admin_city,w.latitude as w_lat, z.latitude as z_lat, z.longitude as z_long, w.longitude as w_long, z.state as z_state,w.state_code as w_state, z.country_code as z_country FROM `zipcodes_new` AS z JOIN webgeocities AS w ON w.country_code = z.country_code and w.name LIKE CONCAT('%',z.admin_city,'%') WHERE z.city_id=0 and z.latitude<>0 and z.state<>'' and z.country_code='BE' and w.state_code = z.state_code LIMIT 500";
        
        
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
$i=0;
print "<table border=1  >
<tr><td>w_id</td>
<td>z_id</td>
<td>w_name</td>
<td>z_admin_city</td>
<td>w_state</td>
<td>z_state</td>
<td>w_lat</td>
<td>z_lat</td>
<td>w_long</td>
<td>z_long</td>
<td>check</td></tr>";
while($row = mysqli_fetch_array($result1))   
{
  $w_id         =   $row['w_id'];
  $z_id         =   $row['z_id'];
  $w_name       =   $row['w_name'];
  $z_admin_city =   $row['z_admin_city'];
  $z_lat        =   $row['z_lat'];
  $w_lat        =   $row['w_lat']; 
  $z_long       =   $row['z_long'];
  $w_long       =   $row['w_long'];
  $z_state      =   $row['z_state'];
  $w_state      =   $row['w_state'];
  //$s_state      =   $row['s_state'];
  $check_value  =   $w_id."_".$z_id; 
  $z_country    =   $row["z_country"];
  
 print "<tr><td>$w_id</td>"
         . "<td>$z_id</td>"
         . "<td>$w_name</td>"
         . "<td>$z_admin_city</td>"
         . "<td>($z_country)$w_state</td>"
         . "<td>$z_state</td>"
         . "<td>$w_lat</td>"
         . "<td>$z_lat</td>"
         . "<td>$w_long</td>"
         . "<td>$z_long</td>"
         . "<td><input type= checkbox name=checked[$i] value=$i><textarea name=check_value$i id='check' type=hidden style='display:none;'>$check_value</textarea>
</td>
</tr>";
 $i++;
}
print "</table>";

?>

<input type="submit" name="submit" value="Submit"/>
<input type="text" name="check_val" value="<?php echo $i;?>"/>
<input type="submit" name="notmatch" value="notmatch"/>

</form>
<script> /*
init();
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
function init(){

 addRowHandlers('rowCtr');
 
}
function addRowHandlers(tableId) {
 if(document.getElementById(tableId)!=null){
  var table = document.getElementById(tableId);
  var rows = table.getElementsByTagName('tr');
  var lat = '';
        var lng = '';
       
  for ( var i = 1; i < rows.length; i++) {
   
   rows[i].i = i;
   rows[i].onclick = function() {
                
    lat = table.rows[this.i].cells[5].innerHTML;    
    lng = table.rows[this.i].cells[7].innerHTML;
    var url="https://maps.googleapis.com/maps/api/geocode/json?latlng="+
        lat+","+lng+"&key=AIzaSyC_1oEMHTvITs8LCblWTrIDC6CkCZASTpU";
    var xhr = createCORSRequest('POST', url);
           if (!xhr) {
             alert('CORS not supported');
           }
         
           xhr.onload = function() { 
        
            var data =JSON.parse(xhr.responseText);
            
            if(data.results.length>0)
            {
            
            var locationDetails=data.results [0].formatted_address;
            
            alert(locationDetails);
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
                               
   };
  }
 }
 

}
*/
</script>
</body>