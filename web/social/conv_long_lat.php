<?php
set_time_limit ( 0 );
ini_set('display_errors',0);
$database_name = "tt";


mysql_connect('localhost','root','tt');
mysql_select_db($database_name);


 function DMStoDD($input)
{
    $deg = " " ;
    $min = " " ;
    $sec = " " ;  
    $inputM = " " ;        
	$dir_sec= explode('"', $input);
	$dir_sec[1];

  //  print "<br> Input is ".$input." <br>";

    for ($i=0; $i < strlen($input); $i++) 
    {                     
        $tempD = $input[$i];
         //print "<br> TempD [$i] is : $tempD"; 

        if ($tempD == iconv("UTF-8", "ISO-8859-1//TRANSLIT", '°') ) 
        { 
            $newI = $i + 1 ;
            //print "<br> newI is : $newI"; 
            $inputM =  substr($input, $newI, -1) ;
            break; 
        }//close if degree

        $deg .= $tempD ;                    
    }//close for degree

     //print "InputM is ".$inputM." <br>";

    for ($j=0; $j < strlen($inputM); $j++) 
    { 
        $tempM = $inputM[$j];
         //print "<br> TempM [$j] is : $tempM"; 

        if ($tempM == "'")  
         {                     
            $newI = $j + 1 ;
             //print "<br> newI is : $newI"; 
            $sec =  substr($inputM, $newI, -1) ;
            break; 
         }//close if minute
         $min .= $tempM ;                    
    }//close for min

        $result =  $deg+( (( $min*60)+($sec) ) /3600 );
if($dir_sec[1]==" S" || $dir_sec[1]==" W")
{
$result1=-1*$result;
//print "<br> converted " ;
}
else {

$result1=$result;
}
/*
 print "<br> Direction ". $dir_sec[1] ;
       print "<br> Degree is ". $deg*1 ;
        print "<br> Minutes is ". $min ;
        print "<br> Seconds is ". $sec ;
        print "<br> Result is ". $result1 ;*/


return $result1;

   }
   
$notfound0 =0;
$found0 =0;
$found1 =0;

$sql="SELECT country_abbreviation as country, city, id,longitude,latitude,state_code FROM airport ";
$results = mysql_query($sql) or die( mysql_error());
$total =mysql_num_rows($results);
 while($r = mysql_fetch_array($results, MYSQL_ASSOC)) {
   
	$id=$r['id'];
	$lati = DMStoDD($r['latitude']);
	//echo "</br>";
	$longi= DMStoDD($r['longitude']);

	echo $update = "update airport set longitude_dec='$longi', latitude_dec='$lati' where id=$id";
	$result = mysql_query($update);
	if ($update) {
    print "Record updated successfully $id </br> ";
} else {
    print "Error updating record: $id";
}	

	}