<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="refresh" content="5">
    </head>
    <body>
    <?php
    $conn = mysqli_connect("localhost","root","tt","zomato");

    $sql= "select count(`status`) as count from zomato.zomato_in_restaurant where status=".$_GET['status']."";

    $rs = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($rs);

    echo "<h2>Count till now is ".$row['count']."</h2>";

    ?>
    </body>
</html>