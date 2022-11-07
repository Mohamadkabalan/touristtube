<?php
/**
 * Created by PhpStorm.
 * User: rishav
 * Date: 19/5/15
 * Time: 3:44 PM
 */

$beforeTime =   microtime(true);
$conn = mysqli_connect('localhost','root','tt','tt');


error_reporting(E_ALL);

$ob =   new Memcached();

$ob->addServer('localhost','11211');


$query  =   "SELECT * from cms_users";

$userArray  =   $ob->get('userArray');
if(empty($userArray)){
    if($rs  =   mysqli_query($conn ,$query)){
        while($row  =   mysqli_fetch_assoc($rs)){
            //echo "executing query";
            $userArray[]  =$row;
        }
        $ob->set('userArray',$userArray);
    }
}
else
    echo "showing data from memcached";

echo "<pre>".print_r($userArray)."</pre><br><br>";

echo $timeDiff = microtime(true) - $beforeTime;