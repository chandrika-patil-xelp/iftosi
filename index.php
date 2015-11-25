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
				
				case 'auto':
					$str = $_GET['str'];
					$url 	= APIDOMAIN.'index.php?action=suggestCity&str='.urlencode($str).'&page=1&limit=5';
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
					$pgno 	= $_GET['pgno'];
					$catid 	= $_GET['catid'];
					$sortby	= $_GET['sortby'];
					$slist	= $_GET['slist'];
					$clist	= $_GET['clist'];
					$tlist	= $_GET['tlist'];
					$ilist	= $_GET['ilist'];
					$jlist	= $_GET['jlist'];
					$ctid	= $_GET['ctid'];
					$uid	= $_GET['uid'];
					$url 	= APIDOMAIN.'index.php?action=getPrdByCatid&catid='.$catid.'&page='.$pgno.'&sortby='.$sortby.'&slist='.urlencode($slist).'&clist='.urlencode($clist).'&tlist='.urlencode($tlist).'&ilist='.urlencode($ilist).'&jlist='.urlencode($jlist).'&ctid='.$ctid.'&uid='.$uid;
					$res 	= $comm->executeCurl($url);
					
					if(!empty($jlist))
					{
						$url 	= APIDOMAIN.'index.php?action=getSubCat';
						$res1 	= $comm->executeCurl($url);
						$headcat= $res1['results'];
						
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
						$res['results']['treedata'] = $showcat;
					}
					
					echo json_encode($res);
				break;
				case 'userCheck':
					$regResp = array();
					$mobile = (!empty($_GET['mobile'])) ? trim($_GET['mobile']) : '';
					$name = (!empty($_GET['name'])) ? trim(urldecode($_GET['name'])) : '';
					$email = (!empty($_GET['email'])) ? trim(urldecode($_GET['email'])) : '';

					$userUrl = APIDOMAIN . 'index.php?action=checkUser&mobile='.$mobile;
					$resp = $comm->executeCurl($userUrl);
					if(!empty($resp) && !empty($resp['error']) && !empty($resp['results']) && empty($resp['error']['Code']))
					{
						if($resp['results'] == 'User Not yet Registered')
						{
							$regUserUrl = APIDOMAIN . 'index.php?action=userReg&mobile='.$mobile.'&username='.urlencode($name).'&email='.urlencode($email);
							$regResp = $comm->executeCurl($regUserUrl);
						}
					}
					echo json_encode($regResp);
				break;
				case 'addToWishList':
					$userid = (!empty($_GET['userid'])) ? trim($_GET['userid']) : '';
					$vid = (!empty($_GET['vid'])) ? trim(urldecode($_GET['vid'])) : '';
					$prdid = (!empty($_GET['prdid'])) ? trim(urldecode($_GET['prdid'])) : '';

					$dt = array('result' => array('uid' => $userid, 'pid' => $prdid, 'vid' => $vid));
					$dt = json_encode($dt);
					$userUrl = APIDOMAIN . 'index.php?action=addtowsh&dt='.$dt;
					$resp = $comm->executeCurl($userUrl, TRUE);
					echo $resp;
				break;
                                case 'addToEnquiry':
					$userid = (!empty($_GET['uid'])) ? trim($_GET['uid']) : '';
					$vid = (!empty($_GET['vid'])) ? trim(urldecode($_GET['vid'])) : '';
					$prdid = (!empty($_GET['pid'])) ? trim(urldecode($_GET['pid'])) : '';
					$userUrl = APIDOMAIN . 'index.php?action=filLog&uid='.$userid.'&pid='.$prdid.'&vid='.$vid;
					$resp = $comm->executeCurl($userUrl, TRUE);
					echo $resp;
                                        break;
                        }
			break;
			
		default:
		
			$url 	= APIDOMAIN.'index.php?action=getSubCat';
			$res 	= $comm->executeCurl($url);
			$headcat= $res['results'];
			
			if(count($headcat))
			{
				foreach($headcat['root'] as $key => $val)
				{
					if($val['catid'] == $_GET['catid'] || strtolower($case) == strtolower($val['cat_name']))
					{
						$showcat = $val;
						$bulcat = $val;
					}
				}
			}
			
			//echo "<pre>";print_r($showcat);die;
			
			if(count($headcat))
			{
				foreach($headcat['root'] as $key => $val)
				{
					$showhead[$val['catid']] = $val;
				}
			}
			
			//echo "<pre>";print_r($showhead);die;
			
			switch ($case)
			{
				case 'signup':
					$page='signup';
					include 'template/signup.html';
				break;
				
				case 'upload':
					$pid = $_GET['pid'];
					$page='upload';
					include 'image-upload/index.html';
				break;
				
				case 'wishlist':
					$page='wishlist';
					
					$uid 		= $_GET['uid'];
					$pgno 		= ($_GET['pgno'] ? $_GET['pgno'] : 1);
					$url 		= APIDOMAIN.'index.php?action=getPrdByCatid&uid='.$uid.'&page='.$pgno;
					$res 		= $comm->executeCurl($url);
					$data 		= $res['results']['products'];
					$total		= $res['results']['total'];
					$catname	= $res['results']['catname'];
					$totalCnt 	= $total;
					$lastpg 	= ceil($total/16);
					$adjacents 	= 2;
					
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
					$catname= $res['results']['catname'];
					//echo "<pre>";print_r($res);die;
					
					$url 	= APIDOMAIN.'index.php?action=fetch_category_mapping&catid='.$catid;
					$res 	= $comm->executeCurl($url);
					$fil	= $res['results']['attributes'];
					
					$totalCnt = $total;
					$lastpg = ceil($total/15);
					$adjacents = 2;
					
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
					$catname= $res['results']['catname'];
					
					$url 	= APIDOMAIN.'index.php?action=fetch_category_mapping&catid='.$catid;
					$res 	= $comm->executeCurl($url);
					$fil	= $res['results']['attributes'];
					
					$totalCnt = $total;
					$lastpg = ceil($total/15);
					$adjacents = 2;
					
					include 'template/jewellery_results.html';
				break;
				case 'bullion':
					$page='bullion';
					$pgno 	= ($_GET['pgno'] ? $_GET['pgno'] : 1);
					$catid 	= $_GET['catid'];
					$url 	= APIDOMAIN.'index.php?action=getPrdByCatid&catid='.$catid.'&page='.$pgno;
					$res 	= $comm->executeCurl($url);
					$data 	= $res['results']['products'];
					$total	= $res['results']['total'];
					$catname= $res['results']['catname'];
					
					$url 	= APIDOMAIN.'index.php?action=fetch_category_mapping&catid='.$catid;
					$res 	= $comm->executeCurl($url);
					$fil	= $res['results']['attributes'];
					
					//echo "<pre>";print_r($fil);die;
					
					$totalCnt = $total;
					$lastpg = ceil($total/15);
					$adjacents = 2;
					include 'template/bullion_results.html';
				break;
				
				case 'diamond_details':
					$page='diamond_details';
					$pid 	= $_GET['productid'];
                                        $uid 	= $_GET['userid'];
                                        
					$url 	= APIDOMAIN.'index.php?action=getPrdById&prdid='.$pid;
					$res 	= $comm->executeCurl($url);
					$data = $prdInfo = $res['results'][$pid];
					$vndrInfo = $prdInfo['vendor_details'];
					foreach($vndrInfo as $key => $value)
					{
						$vndrId = $key;
						$vndrDtls = $value;
					}
					$vndrDtls['fulladdress'] = explode(",", $vndrDtls['fulladdress']);
					foreach($vndrDtls['fulladdress'] as $key => $value)
					{
						$vndrDtls['fulladdress'][$key] = trim($value);
					}
                                        if(!empty($uid))
                                        {
                                            $url 	= APIDOMAIN.'index.php?action=checklist&uid='.$uid.'&vid='.$vndrId.'&prdid='.$pid;
                                            $res 	= $comm->executeCurl($url);
                                            $wish = $res['error'];
                                            //echo "<pre>$uid ";print_r($wish); die;
                                        }
					$vndrDtls['fulladdress'] = implode(', ', $vndrDtls['fulladdress']);
					$vndrAddr = explode(',', $vndrDtls['fulladdress']);
					include 'template/diamond_details.html';
				break;
				case 'bullion_details':
					$page='bullion_details';
					$prdInfo = array();

					$prdId = $orgPrdId = (!empty($_GET['productid'])) ? $_GET['productid'] : '';

					if(!empty($prdId))
					{
						$prdId = explode(' ', $prdId);
						$prdId = $pid = $prdId[1];
						$prdInfoUrl = APIDOMAIN . 'index.php?action=getPrdById&prdid='.$prdId;
						$prdInfo = $comm->executeCurl($prdInfoUrl);
						if(!empty($prdInfo) && !empty($prdInfo['results']) && !empty($prdInfo['error']) && empty($prdInfo['error']['errCode']))
						{
							$prdInfo = $prdInfo['results'][$prdId];
							$vndrInfo = $prdInfo['vendor_details'];
							foreach($vndrInfo as $key => $value)
							{
								$vndrId = $key;
								$vndrDtls = $value;
							}
							$vndrDtls['fulladdress'] = explode(",", $vndrDtls['fulladdress']);
							foreach($vndrDtls['fulladdress'] as $key => $value)
							{
								$vndrDtls['fulladdress'][$key] = trim($value);
							}
							$vndrDtls['fulladdress'] = implode(', ', $vndrDtls['fulladdress']);
							$vndrAddr = explode(',', $vndrDtls['fulladdress']);
						}
					}
					include 'template/bullion_details.html';
				break;
				case 'jewellery_details':
					$page='jewellery_details';
					$prdInfo = array();

					$prdName = (!empty($_GET['productname'])) ? $_GET['productname'] : '';
					$prdId = $orgPrdId = (!empty($_GET['productid'])) ? $_GET['productid'] : '';

					if(!empty($prdId))
					{
						$prdId = explode(' ', $prdId);
						$prdId = $pid = $prdId[1];
						$prdInfoUrl = APIDOMAIN . 'index.php?action=getPrdById&prdid='.$prdId;
						$prdInfo = $comm->executeCurl($prdInfoUrl);
						if(!empty($prdInfo) && !empty($prdInfo['results']) && !empty($prdInfo['error']) && empty($prdInfo['error']['errCode']))
						{
							$prdInfo = $prdInfo['results'][$prdId];
							$vndrInfo = $prdInfo['vendor_details'];
							foreach($vndrInfo as $key => $value)
							{
								$vndrId = $key;
								$vndrDtls = $value;
							}
							$vndrDtls['fulladdress'] = explode(",", $vndrDtls['fulladdress']);
							foreach($vndrDtls['fulladdress'] as $key => $value)
							{
								$vndrDtls['fulladdress'][$key] = trim($value);
							}
							$vndrDtls['fulladdress'] = implode(', ', $vndrDtls['fulladdress']);
							$vndrAddr = explode(',', $vndrDtls['fulladdress']);
						}
					}

					include 'template/jewellery_details.html';
				break;
                                case 'diamond_Form':
					$page='diamond-Form';
                                        $vid   =  $_GET['vid'];
                                        $catid =  $_GET['catid'];
                                        $pid   =  $_GET['prdid'];
                                        $dt    =  $_POST['dt'];
                                        
                                        if(!empty($catid) && !empty($dt) && !empty($vid))
                                        {  
                                        $url   = APIDOMAIN.'index.php?action=addNewproduct&category_id='.$catid.'&dt='.$dt.'&vid='.$vid;
                                        $res   = $comm->executeCurl($url);
                                        $data  = $res['results'][$dt];
                                        }
                                        if(!empty($catid)&& !empty($pid))
                                        {
                                            $url   = APIDOMAIN.'index.php?action=getPrdById&category_id='.$catid.'&prdid='.$pid;
                                            $res   = $comm->executeCurl($url);
                                            $result  = $res['results'];
                                        }

                                        $url 	= APIDOMAIN.'index.php?action=fetch_category_mapping&catid='.$catid;
					$res 	= $comm->executeCurl($url);
					$fil 	= $res['results'];

                                        $attr   = $result[$pid]['attr_details'];
                                        $pdet   = $result[$pid];
                                        //echo "<pre>";print_r($attr);die;
					include 'template/diamondForm.html';
				break;
                               
                            
                                case 'jewellery_Form':
					$page='jewellery-Form';
                                        $vid   =  $_GET['vid'];
                                        $catid =  $_GET['catid'];
                                        $pid   =  $_GET['prdid'];
                                        $dt    =  $_POST['dt'];
                                        
                                        if(!empty($catid) && !empty($dt) && !empty($vid))
                                        {
                                            $url   = APIDOMAIN.'index.php?action=addNewproduct&category_id='.$catid.'&dt='.$dt.'&vid='.$vid;
                                            $res   = $comm->executeCurl($url);
                                            $data  = $res['results'][$dt];
                                        }
                                        if(!empty($catid) && !empty($pid))
                                        {
                                            $url   = APIDOMAIN.'index.php?action=getPrdById&catid='.$catid.'&prdid='.$pid;
                                            $res   = $comm->executeCurl($url);
                                            $result  = $res['results'];
                                        }
                                        
                                        $url 	= APIDOMAIN.'index.php?action=categoryHeir&catid='.$catid;
					$res 	= $comm->executeCurl($url);
					$cat 	= $res['result'];
                                        
                                        $attr   = $result[$pid]['attr_details'];
                                        $pdet   = $result[$pid];
                                        //echo "<pre>";print_r($pdet);die;
					include 'template/jewelleryForm.html';
				break;
                            
                                case 'bullion_Form':
                                        $page='bullion-Form';
                                        $vid   =  $_GET['vid'];
                                        $catid =  $_GET['catid'];
                                        $pid   =  $_GET['prdid'];
                                        $dt    =  $_POST['dt'];
                                        
                                        if(!empty($catid) && !empty($dt) && !empty($vid))
                                        {
                                            $url   = APIDOMAIN.'index.php?action=addNewproduct&category_id='.$catid.'&dt='.$dt.'&vid='.$vid;
                                            $res   = $comm->executeCurl($url);
                                            $data  = $res['results'][$dt];
                                        }

                                        if(!empty($catid) && !empty($pid))
                                        {
                                            $url   = APIDOMAIN.'index.php?action=getPrdById&category_id='.$catid.'&prdid='.$pid.'&vid='.$vid;
                                            $res   = $comm->executeCurl($url);
                                            $result  = $res['results'];
                                        }
                                        $url 	= APIDOMAIN.'index.php?action=categoryHeir&catid='.$catid;
										$res 	= $comm->executeCurl($url);
										$cat 	= $res['result'];
                                        $url 	= APIDOMAIN.'index.php?action=fetch_category_mapping&catid='.$catid;
                                        $res 	= $comm->executeCurl($url);
                                        $fil 	= $res['results'];
                                        
                                        $attr   = $result[$pid]['attr_details'];
                                        $pdet   = $result[$pid];
//                                        echo "<pre>";print_r($fil);die;
                                       //echo "<pre>";print_r($result[$pid]['attr_details']['type']);die;
                                        include 'template/bullionForm.html';
				break;
                                
                                case 'vendor_Form':
					$page='vendor-Form';
					$uid 	= $_GET['uid'];
					$url 	= APIDOMAIN.'index.php?action=viewAll&uid='.$uid;
					$res 	= $comm->executeCurl($url);
                                        $data = $res['results'][1];
					include 'template/vendorDetails.html';
				break;
                            
                                case 'vcontact_Form':
					$page='vcontact-Form';
					include 'template/vcontactDetails.html';
				break;
				case 'vProduct_diamonds':
					$page='Products-Diamond';
					include 'template/vProducts_Diamond.html';
				break;
				case 'vProduct_jewellery':
					$page='Products-Jewellery';
					include 'template/vProducts_jewellery.html';
				break;
				case 'vProduct_bullion':
					$page='Products-Bullion';
					include 'template/vProducts_bullion.html';
				break;
				case 'vendor_landing':
					$page='Products';
                    $pgno 	= (!empty($_GET['pgno']) ? $_GET['pgno'] : 1);
					$catid 	= $_GET['catid'];
					$url 	= APIDOMAIN.'index.php?action=getVPrdByCatid&catid='.$catid.'&page='.$pgno;
					$res 	= $comm->executeCurl($url);
					$data 	= $res['results']['products'];
					$total	= $res['results']['total'];
					$catname= $res['results']['catname'];
					
					$url 	= APIDOMAIN.'index.php?action=fetch_category_mapping&catid='.$catid;
					$res 	= $comm->executeCurl($url);
					$fil	= $res['results']['attributes'];
					
					//echo "<pre>";print_r($fil);die;
					
					$totalCnt = $total;
					$lastpg = ceil($total/15);
					$adjacents = 2;
					include 'template/vendor_landing_page.html';
				break;
				case 'vendor_enquiries':
					$page='Enquiries';
					include 'template/vendor_enquiries.html';
				break;
				case 'about_us':
					$page='about_us';
					include 'template/about_us.html';
				break;

				case 'contact_us':
					$page='contact_us';
					include 'template/contact_us.html';
				break;
                            
				case 'terms_conditions':
						$page='terms_conditions';
						include 'template/terms_conditions.html';
				break;
			
				case 'faq':
					   $page='faq';
					   include 'template/faq.html';
				break;
				case 'education':
					   $page='education';
					   include 'template/education.html';
				break;
				case 'membership':
					   $page='membership';
					   include 'template/membership.html';
				break;
				case 'shapes':
					   $page='shapes';
					   include 'template/shapes.html';
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
