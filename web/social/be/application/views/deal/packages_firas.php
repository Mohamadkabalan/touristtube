<div class="title">Amazing China Packages </div>
<br />

<?php           
    if(isset($packages) && !empty($packages) ){  
//        echo '<pre>';
//        var_dump($packages);
//        echo '</pre>';
        ?>
        <table border="1">
        <tr>
        <th>Product Code</th>
        <th>Product Name</th>
        <th>Category</th>
        <th>Price Exclusions</th>
        <th>Themes</th>
        <th>Image</th>
        </tr>
        <?php foreach($packages as $package){ ?>
        <tr>
        <td><?= $package->ProductCode;?></td>    
        <td><?= $package->ProductName;?></td>
        <td><?= $package->Category;?></td>    
        <td><?= $package->PriceExclusions;?></td>  
        
        <td><?= $package->Themes;?></td> 
        <td><?php foreach($package->ProductImages as $productImage){ $pic = $productImage->ImagesURL;echo '<br>';?><a href="<?php echo $pic; ?>"><?php echo $pic; ?></a><?php } ?></td> 
        </tr> 
        <?php  } ?>
        </table>
<?php
    }else{
        echo "packages not returned from API";
    }
?>
