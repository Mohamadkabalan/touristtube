 <meta http-equiv="Content-Type" content="text/html; 
charset=UTF-8" />

 <body onload="hide()" style="background-color: #333; color: #ccc;">

<?php 
error_reporting(e_all);

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

 
 $Query="update tt.discover_poi_latest set city_id=$nw_id,status=77 where id=$nd_id";
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
 
$new_sel= explode('_',$selected);
 $nw_id=$new_sel[0];
 $nd_id=$new_sel[1];

 

 $Query="update tt.prague_poi set status=9 where id=$nd_id";
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
$sql="SELECT d.id AS d_id, d.name AS d_poi,d.country as d_country,d.state_code AS d_state, d.city AS d_city, d.n_city_local as n_city, w.name AS w_city, d.state AS d_statename, d.latitude AS d_lat, w.latitude AS w_lat, d.longitude AS d_long, w.longitude AS w_long, d.city_id AS d_cityid, w.id AS w_id, w.state_code FROM `discover_poi_latest` AS d JOIN webgeocities AS w ON w.name like concat('%',d.n_city_local,'%') AND d.country = w.country_code  AND d.status =10 AND d.n_city_local not in ('','Null')ORDER BY d.id limit 500 "; 
$result1 = mysqli_query($conn,$sql) or die( mysqli_error());
$i=0;
print "<table border=1  >
<tr><td>d_id</td><td>d_poi</td><td>d_city</td><td>w_city</td><td>check</td><td>d_lat</td><td>w_lat</td><td>d_long</td><td>w_long</td><td>d_cityid</td><td>w_id</td></tr>";
while($row = mysqli_fetch_array($result1))   
{
  $d_id  =  $row['d_id'];
  $d_country =  $row['d_country'];
  $d_statename=  $row['d_statename'];
  $d_poi  =  $row['d_poi'];
  $d_city =  $row['d_city'];
  $n_city =  $row['n_city'];
  $w_city = $row['w_city'];
  $d_lat = $row['d_lat'];
  $w_lat =   $row['w_lat']; 
  $d_long =  $row['d_long'];
  $w_long = $row['w_long'];
  $d_cityid = $row['d_cityid'];
  $w_id  = $row['w_id'];
  $statecode= $row['state_code'];
  $check_value= $w_id."_".$d_id ; 
  $d_state_code=$row['d_state'];
  
 print "<tr><td>$d_id</td><td>$d_poi, $d_country </td><td> $d_city, $n_city($d_state_code)</td><td>$w_city($statecode)</td><td><input type= checkbox name=checked[$i] value=$i><textarea name=check_value$i id='check' type=hidden style='display:none;'>$check_value</textarea>
</td><td>$d_lat</td><td>$w_lat</td><td>$d_long	</td><td>$w_long</td><td>$d_cityid</td><td>$w_id</td></tr>";
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
