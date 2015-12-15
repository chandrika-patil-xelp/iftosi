<?php 
	include '../../config.php';
	$params	= array_merge($_GET,$_POST);
	$pid 	= $params['pid'];
	
	$url = APIDOMAIN.'index.php?action=imagedisplay&pid='.$pid."&af=".urlencode('0,1,3');
	$res = $comm->executeCurl($url,1);

	echo $res;
?>