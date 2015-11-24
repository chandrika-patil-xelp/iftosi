<?php
include '../../config.php';
$params	= array_merge($_GET,$_POST);
if(isset($params['file']) && isset($params['pid']))
{
	$pid 	= $params['pid'];
	$url = APIDOMAIN.'index.php?action=imageremove&pid='.$pid.'&file='.$_POST['file'];
	$res = $comm->executeCurl($url,1);
	echo $res;
}
?>