<?php
	include("heart.php");
//	echo videoGetInfo($_GET['l']);
	echo videoGetInfo($request->query->get('l',''));