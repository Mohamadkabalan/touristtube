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

//$value = array(1,2,3,4,5,6,7,8,9);
//$arrayuser = array_rand($value);
$randomNumber = mt_rand(0, 10);

$query  =   "SELECT * from cms_users";

$userArray  =   $ob->get('userArray'.$randomNumber);
if(empty($userArray)){
        echo "Showing data from query";
    if($rs  =   mysqli_query($conn ,$query)){
        while($row  =   mysqli_fetch_assoc($rs)){
            $userArray[]  =$row;
        }
        $ob->set('userArray'.$randomNumber,$userArray);
    }
}
else
        echo "showing result from memcached having key : "."userArray".$randomNumber;

echo "<pre>".print_r($userArray)."</pre><br><br>";

echo $timeDiff = microtime(true) - $beforeTime;