<?php

	set_time_limit(0);

	$max = 40000000;
	
	$i = 0;
	$t1 = microtime(true);
	while($i < $max){
		$x = $i % 2;
		$i++;
	}
	$t2 = microtime(true);
	printf('Mod: t1 = ' . ($t2 - $t1) );
	
	echo "<br/>\n";
	
	$i = 0;
	$t1 = microtime(true);
	while($i < $max){
		$x = $i & 1;
		$i++;
	}
	$t2 = microtime(true);
	
	printf('Bitwise: t2 = ' . ($t2 - $t1));
?>
