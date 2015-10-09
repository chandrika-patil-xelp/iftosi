<?php
	include 'config.php';
	$params = array_merge($_GET, $_POST);

	$action = (!empty($params['action'])) ? trim($params['action']) : '';
	$case = (!empty($params['case'])) ? trim($params['case']) : '';

	switch($action)
	{
		case 'ajx':
			switch($case)
			{
				case 'sortby':
					$pgno 	= 1;
					$catid 	= $_GET['catid'];
					$sortby	= $_GET['sortby'];
					$url 	= APIDOMAIN.'index.php?action=getPrdByCatid&catid='.$catid.'&page='.$pgno.'&sortby='.$sortby;
					$res 	= $comm->executeCurl($url,1);
					echo $res;
				break;
			}
			break;
		default:
			switch ($case)
			{
				case 'signup':
					$page='signup';
					include 'template/signup.html';
				break;
				
				case 'wishlist':
					$page='wishlist';
					include 'template/wishlist.html';
				break;
				
				case 'diamonds':
					$page='diamonds';
					$pgno 	= ($_GET['pgno'] ? $_GET['pgno'] : 1);
					$catid 	= $_GET['catid'];
					$url 	= APIDOMAIN.'index.php?action=getPrdByCatid&catid='.$catid.'&page='.$pgno;
					$res 	= $comm->executeCurl($url);
					$data 	= $res['results']['products'];
					$total	= $res['results']['total'];
					include 'template/results.html';
				break;
				
				case 'jewellery':
					$page='jewellery';
					include 'template/jewellery_results.html';
				break;
				
				case 'details':
					$page='product_details';
					include 'template/product_details.html';
				break;
				
				default:
					$page='index';
					$url = APIDOMAIN.'index.php?action=getCatList&page=1&limit=3';
					$res = $comm->executeCurl($url);
					$data = $res['results'];
					include 'template/index.html';
				break;
			}
			break;
	}
?>