<?php $controller = $this->router->class ?>

<p class="linksContainer"><?php if(isset($links)) echo $links; ?><img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/></p>
<table class="table">
	<tr>
		<th>#</th>
		<th>PNR</th>
		<th>Name of Passenger</th>
		<th>Email</th>
		<th>Status</th>
		<th></th>
		<th></th>
	</tr>

	<?php foreach($pnrs as $pnr): ?>
		<tr>
			<td><?php echo $pnr->id;?></td>    
			<td><?php echo $pnr->pnr;?></td>
			<td><?php echo ucfirst( $pnr->first_name ). " " . ucfirst( $pnr->surname );?></td>
			<td><?php echo $pnr->email;?></td>
			<td><?php echo $pnr->status;?></td>	
			<td><?= anchor("$controller/view/".$pnr->id, "Detail");?></td>    
		</tr> 
	<?php endforeach;  ?>
</table>

<p class="linksContainer"><?php if(isset($links)) echo $links; ?></p>