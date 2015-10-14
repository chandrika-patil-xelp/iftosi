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
				
				case 'filter':
					$pgno 	= 1;
					$catid 	= $_GET['catid'];
					$sortby	= $_GET['sortby'];
					$slist	= $_GET['slist'];
					$clist	= $_GET['clist'];
					$tlist	= $_GET['tlist'];
					$url 	= APIDOMAIN.'index.php?action=getPrdByCatid&catid='.$catid.'&page='.$pgno.'&sortby='.$sortby.'&slist='.urlencode($slist).'&clist='.urlencode($clist).'&tlist='.urlencode($tlist);
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
					
					$url 	= APIDOMAIN.'index.php?action=fetch_category_mapping&catid='.$catid;
					$res 	= $comm->executeCurl($url);
					$fil	= $res['results']['attributes'];
					
					//echo "<pre>";print_r($fil);die;
					
					include 'template/results.html';
				break;
				
				case 'jewellery':
					$page='jewellery';
					include 'template/jewellery_results.html';
				break;
				
				case 'diamond_details':
					$page='diamond_details';
					include 'template/diamond_details.html';
				break;
				case 'jewellery_details':
					$page='jewellery_details';
					include 'template/jewellery_details.html';
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