<?php
	include 'config.php';
	$params = array_merge($_GET, $_POST);

	$action = (!empty($params['action'])) ? trim($params['action']) : '';
	$case = (!empty($params['case'])) ? trim($params['case']) : '';

	switch($action)
	{
		case 'ajx':
			
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
					include 'template/index.html';
				break;
			}
			break;
	}
?>