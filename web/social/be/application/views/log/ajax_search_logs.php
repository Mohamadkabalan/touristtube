<?php $controller = $this->router->class ?>
<div >
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>
    <table class="table">
    <tr>
    <th>Term</th>
    <th>Count</th>
    </tr>
    <?php 
	$i=1;
	if(count($logs->all) >0){
		foreach($logs as $log){ ?>
		<tr>    
		<td><?= $log->search_string;?></td>
		<td><?= $log->cnt;?></td>      
		</tr> 
    <?php $i++; } } else { ?>
	
		<tr>
		<td colspan="3">No records found</td>
		</tr>
		<?php }	?>
    </table>
<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>
</div>