<?php
	/*-Simple debug output -*/
	$res = print_r($_REQUEST,true);
	file_put_contents(time()."-".rand(999,10000).".txt",$res);