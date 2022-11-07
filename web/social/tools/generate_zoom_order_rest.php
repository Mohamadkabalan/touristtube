<pre><?php

$database_name = "touristtube";


//mysql_connect( "192.168.2.5" , "root" , "7mq17psb" );
mysql_connect('localhost','root','mysql_root');
mysql_select_db($database_name);
$query = "select * from webgeocities where"; 
$result = mysql_query($query);

while ($row=  mysql_fetch_array($result)){
    $pad = 0.05;
    $long1=$row['longitude']-$pad;
    $long2=$row['longitude']+$pad;
    $lat1=$row['latitude']-$pad;
    $lat2=$row['latitude']+$pad;
    
    $sql="SELECT id FROM discover_hotels WHERE longitude BETWEEN $long1 AND $long2 AND latitude BETWEEN $lat1 AND $lat2 ORDER BY RAND()";
    $res1 = mysql_query($sql);
    $num =mysql_num_rows($res1);
    $i = 0; 
    while ($r1=  mysql_fetch_array($res1)){ 
        if($i%20==0) $myArr1[] = $r1['id'];
        if($i%20==1||$i%20==2) $myArr2[] = $r1['id'];
        if($i%20==3||$i%20==4||$i%20==5) $myArr3[] = $r1['id'];
        if($i%20==6||$i%20==7||$i%20==8||$i%20==9) $myArr4[] = $r1['id'];
        if($i%20>9) $myArr5[] = $r1['id'];
        $i++;
    }
    if( $num>0 ) {
        mysql_query ("UPDATE discover_hotels SET zoom_order = 1 WHERE id IN (".  implode(',', $myArr1).")");
        mysql_query ("UPDATE discover_hotels SET zoom_order = 2 WHERE id IN (".  implode(',', $myArr2).")");
        mysql_query ("UPDATE discover_hotels SET zoom_order = 3 WHERE id IN (".  implode(',', $myArr3).")");
        mysql_query ("UPDATE discover_hotels SET zoom_order = 4 WHERE id IN (".  implode(',', $myArr4).")");
        mysql_query ("UPDATE discover_hotels SET zoom_order = 5 WHERE id IN (".  implode(',', $myArr5).")");
    }
}

