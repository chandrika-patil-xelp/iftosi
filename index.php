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
				
				case 'vendor':
					$pid = $_GET['productid'];
					$url = APIDOMAIN."index.php?action=getPrdById&prdid=".$pid;
					$res = $comm->executeCurl($url);
					$res['results'] = $res['results'][$pid];
					echo json_encode($res);
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
		
			$url 	= APIDOMAIN.'index.php?action=getSubCat';
			$res 	= $comm->executeCurl($url);
			$headcat= $res['results'];
			//print_r($headcat);die;
			
			if(count($headcat))
			{
				foreach($headcat['root'] as $key => $val)
				{
					if($val['catid'] == $_GET['catid'])
					{
						$showcat = $val;
					}
				}
			}
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
					//echo "<pre>";print_r($res);die;
					
					$url 	= APIDOMAIN.'index.php?action=fetch_category_mapping&catid='.$catid;
					$res 	= $comm->executeCurl($url);
					$fil	= $res['results']['attributes'];
					
					//echo "<pre>";print_r($fil);die;
					
					include 'template/results.html';
				break;
				
				case 'jewellery':
					$page='jewellery';
					$pgno 	= ($_GET['pgno'] ? $_GET['pgno'] : 1);
					$catid 	= $_GET['catid'];
					$url 	= APIDOMAIN.'index.php?action=getPrdByCatid&catid='.$catid.'&page='.$pgno;
					$res 	= $comm->executeCurl($url);
					$data 	= $res['results']['products'];
					$total	= $res['results']['total'];
					
					$url 	= APIDOMAIN.'index.php?action=fetch_category_mapping&catid='.$catid;
					$res 	= $comm->executeCurl($url);
					$fil	= $res['results']['attributes'];
					
					include 'template/jewellery_results.html';
				break;
				case 'bullion':
					$page='bullion';
					include 'template/bullion_results.html';
				break;
				
				case 'diamond_details':
					$page='diamond_details';
					$pid 	= $_GET['productid'];
					$url 	= APIDOMAIN.'index.php?action=getPrdById&prdid='.$pid;
					$res 	= $comm->executeCurl($url);
					$data 	= $res['results'][$pid];
					include 'template/diamond_details.html';
				break;
				case 'bullion_details':
					$page='bullion_details';
					include 'template/bullion_details.html';
				break;
				case 'jewellery_details':
					$page='jewellery_details';
					include 'template/jewellery_details.html';
				break;
                                case 'diamond_Form':
					$page='diamond-Form';
					include 'template/diamondForm.html';
				break;
                                case 'jewellery_Form':
					$page='jewellery-Form';
					include 'template/jewelleryForm.html';
				break;
                                case 'bullion_Form':
					$page='bullion-Form';
					include 'template/bullionForm.html';
				break;
                                
                                case 'vendor_Form':
					$page='vendor-Form';
					include 'template/vendorDetails.html';
				break;
                            
                                case 'vcontact_Form':
					$page='vcontact-Form';
					include 'template/vcontactDetails.html';
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