<?php

include 'config.php';
$params = array_merge($_GET, $_POST);
$params = array_merge($params, $_FILES);

$action = (!empty($params['action'])) ? trim($params['action']) : '';
$case = (!empty($params['case'])) ? trim($params['case']) : '';
$popup = $_GET['popup'];

switch ($action) {
    case 'ajx':
        switch ($case) {
            case 'sortby':
                $pgno = 1;
                $catid = $_GET['catid'];
                $sortby = $_GET['sortby'];
                $url = APIDOMAIN . 'index.php?action=getPrdByCatid&catid=' . $catid . '&page=' . $pgno . '&sortby=' . $sortby;
                $res = $comm->executeCurl($url, 1);
                echo $res;
                break;

            case 'auto':
                $str = $_GET['str'];
                $pagename = $_GET['pageName'];
                $url = APIDOMAIN . 'index.php?action=suggestAreaCity&str=' . urlencode($str) . '&page=1&limit=5&pageName='.$pagename;
                $res = $comm->executeCurl($url, 1);
                echo $res;
                break;

            case 'vendor':
                $pid = $_GET['productid'];
                $url = APIDOMAIN . "index.php?action=getPrdById&prdid=" . $pid;
                $res = $comm->executeCurl($url);
                $res['results'] = $res['results'][$pid];
                echo json_encode($res);
                break;

            case 'refine':
                $str = $_GET['str'];
                $catid = $_GET['catid'];
                $url = APIDOMAIN . "index.php?action=getPrdByCatid&str=" . $str . "&catid=" . $catid;
                //$res = $comm->executeCurl($url);
                break;

            case 'filter':
                $pgno = $_GET['pgno'];
                $catid = $_GET['catid'];
                $sortby = $_GET['sortby'];
                $slist = $_GET['slist'];
                $clist = $_GET['clist'];
                $tlist = $_GET['tlist'];
                $ilist = $_GET['ilist'];
                $jlist = $_GET['jlist'];
                $ctid = $_GET['ctid'];
                $uid = $_GET['uid'];
                $pid = $_GET['pid'];
                $b2bsort = $_GET['b2bsort'];

                if ($pid) {
                    $url = APIDOMAIN . 'index.php?action=removeFromWishlist&uid=' . $uid . '&pid=' . $pid;
                    $res = $comm->executeCurl($url);
                }

                $url = APIDOMAIN . 'index.php?action=getPrdByCatid&catid=' . $catid . '&page=' . $pgno . '&sortby=' . $sortby . '&slist=' . urlencode($slist) . '&clist=' . urlencode($clist) . '&tlist=' . urlencode($tlist) . '&ilist=' . urlencode($ilist) . '&jlist=' . urlencode($jlist) . '&ctid=' . $ctid . '&uid=' . $uid . '&b2bsort=' . $b2bsort;
                $res = $comm->executeCurl($url);

                if (!empty($jlist)) {
                    $url = APIDOMAIN . 'index.php?action=getSubCat';
                    $res1 = $comm->executeCurl($url);
                    $headcat = $res1['results'];

                    if (count($headcat)) {
                        foreach ($headcat['root'] as $key => $val) {
                            if ($val['catid'] == $_GET['catid']) {
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
                $city = (!empty($_GET['ur_city'])) ? trim(urldecode($_GET['ur_city'])) : '';
                $pid = (!empty($_GET['pid'])) ? trim(urldecode($_GET['pid'])) : '';
                $isV = (!empty($_GET['isVendor'])) ? trim($_GET['isVendor']) : '';
                if(!empty($pid))
                {
                   $userUrl = APIDOMAIN . 'index.php?action=checkUser&mobile='.$mobile.'&email='.$email.'&pid=' . $pid;
                }
                else
                {
                    $userUrl = APIDOMAIN . 'index.php?action=checkUser&mobile=' . $mobile;
                }
                $resp = $comm->executeCurl($userUrl);

                if (!empty($resp) && !empty($resp['error']) && !empty($resp['results']) && empty($resp['error']['Code'])) {
                    if ($resp['results'] == 'User Not yet Registered') {
                        $regUserUrl = APIDOMAIN . 'index.php?action=userReg&mobile='.$mobile.'&username='.urlencode($name).'&email='.urlencode($email).'&cityname='.urlencode($city);
                        $resp = $comm->executeCurl($regUserUrl);
                    }
                }
                echo json_encode($resp);
                break;

            case 'updateStatus':

                $vid = (!empty($_GET['userid'])) ? trim($_GET['userid']) : '';
                $af = (!empty($_GET['af'])) ? trim($_GET['af']) : 0;
                $userUrl = APIDOMAIN . 'index.php?action=actUser&userid=' . $vid . '&af=' . $af;
                $resp = $comm->executeCurl($userUrl);
                echo json_encode($resp);
                break;

            case 'addToWishList':
                $userid = (!empty($_GET['userid'])) ? trim($_GET['userid']) : '';
                $vid = (!empty($_GET['vid'])) ? trim(urldecode($_GET['vid'])) : '';
                $prdid = (!empty($_GET['prdid'])) ? trim(urldecode($_GET['prdid'])) : '';

                $dt = array('result' => array('uid' => $userid, 'pid' => $prdid, 'vid' => $vid));
                $dt = json_encode($dt);
                $userUrl = APIDOMAIN . 'index.php?action=addtowsh&dt=' . $dt;
                $resp = $comm->executeCurl($userUrl, TRUE);
                echo $resp;
                break;

            case 'addToEnquiry':
                $userid = (!empty($_GET['uid'])) ? trim($_GET['uid']) : '';
                $vid = (!empty($_GET['vid'])) ? trim(urldecode($_GET['vid'])) : '';
                $prdid = (!empty($_GET['pid'])) ? trim(urldecode($_GET['pid'])) : '';
                $userUrl = APIDOMAIN . 'index.php?action=filLog&uid=' . $userid . '&pid=' . $prdid . '&vid=' . $vid;
                $resp = $comm->executeCurl($userUrl, TRUE);
                echo $resp;
                break;

            case 'checkWish':
                $uid = (!empty($_GET['userid'])) ? trim(urldecode($_GET['userid'])) : '';
                $vid = (!empty($_GET['vid'])) ? trim(urldecode($_GET['vid'])) : '';
                $prdid = (!empty($_GET['prdid'])) ? trim(urldecode($_GET['prdid'])) : '';

                if (!empty($uid)) {
                    $url = APIDOMAIN . 'index.php?action=checkWish&uid=' . $uid . '&vid=' . $vid . '&prdid=' . $prdid;
                    $res = $comm->executeCurl($url, TRUE);
                    echo $res;
                } else {
                    $res = array();
                    $error = array('Code' => 1, 'Msg' => 'Error fetching wishlist');
                    $resp = array('results' => $res, 'error' => $error);
                    echo json_encode($resp);
                }
                break;

            case 'getUserDet':
                $regResp = array();
                $uid = (!empty($_GET['uid'])) ? trim($_GET['uid']) : '';
                if (!empty($uid)) {
                    $url = APIDOMAIN . 'index.php?action=viewAll&uid=' . $uid;
                    $res = $comm->executeCurl($url, TRUE);
                    echo $res;
                } else {
                    $res = array();
                    $error = array('Code' => 1, 'Msg' => 'Error fetching wishlist');
                    $resp = array('results' => $res, 'error' => $error);
                    echo json_encode($resp);
                }
                break;

            case 'sendDetailsToUser':
                $resp = array();
                $usrName = (!empty($params['usrName']) && $params['usrName'] !== 'null' && $params['usrName'] !== 'undefined') ? trim(urldecode($params['usrName'])) : '';
                $usrMobile = (!empty($params['usrMobile']) && $params['usrMobile'] !== 'null' && $params['usrMobile'] !== 'undefined') ? trim(urldecode($params['usrMobile'])) : '';
                $usrEmail = (!empty($params['usrEmail']) && $params['usrEmail'] !== 'null' && $params['usrEmail'] !== 'undefined') ? trim(urldecode($params['usrEmail'])) : '';
                $prdid = (!empty($params['prdid']) && $params['prdid'] !== 'null' && $params['prdid'] !== 'undefined') ? trim(urldecode($params['prdid'])) : '';
                $vid = (!empty($params['vid']) && $params['vid'] !== 'null' && $params['vid'] !== 'undefined') ? trim(urldecode($params['vid'])) : '';
                $uid = (!empty($params['uid']) && $params['uid'] !== 'null' && $params['uid'] !== 'undefined') ? trim(urldecode($params['uid'])) : '';

                if (!empty($usrName) && !empty($usrMobile) && !empty($usrEmail) && !empty($prdid)) {
                    $apiUrl = APIDOMAIN . 'index.php?action=sendDetailsToUser';
                    $params = array('usrName' => $usrName, 'usrMobile' => $usrMobile, 'usrEmail' => $usrEmail, 'prdid' => $prdid, 'vid' => $vid, 'uid' => $uid);
                    $resp = $comm->executeCurl($apiUrl, false, false, $params);
                    if (empty($resp)) {
                        $resp = array('results' => array(), 'error' => array('Code' => 1, 'Msg' => 'Some error occured while sending details'));
                    }
                } else {
                    $resp = array('results' => array(), 'error' => array('Code' => 1, 'Msg' => 'Some parameters are missing'));
                }
                echo json_encode($resp);
                break;
            case 'getWishListCount':
                $uid = (!empty($_GET['userid'])) ? trim(urldecode($_GET['userid'])) : '';
                $total = 0;
                if (!empty($uid)) {
                    $url = APIDOMAIN . "index.php?action=getPrdByCatid&uid=" . $uid;
                    $res = $comm->executeCurl($url);

                    if (!empty($res) && !empty($res['error']) && empty($res['error']['errCode'])) {
                        if (!empty($res['results']['total'])) {
                            $total = $res['results']['total'];
                        }
                    }
                }
                echo $total;
                break;

			case 'getImages':
				$prdIds= (!empty($params['prdIds']) && !stristr($params['prdIds'], 'undefined') && !stristr($params['prdIds'], 'null')) ? trim(urldecode($params['prdIds'])) : '';
				if(!empty($prdIds))
				{
					$url = APIDOMAIN . "index.php?action=getPrdImgsByIds&prdIds=" . urlencode($prdIds);
                    $res = $comm->executeCurl($url);
				}
				else
				{
					$res = array('results' => array(), 'error' => array('code' => 0, 'msg' => 'No Product IDs found'));
				}
				echo json_encode($res);
			break;
        }
        break;

    default:

        $url = APIDOMAIN . 'index.php?action=getSubCat';
        $res = $comm->executeCurl($url);
        $headcat = $res['results'];

		$leftMenuUrl = APIDOMAIN . 'index.php?action=getCatList&page=1&limit=3';
		$leftMenuRes = $comm->executeCurl($leftMenuUrl);
		$leftMenuData = $leftMenuRes['results'];

        if (count($headcat)) {
            foreach ($headcat['root'] as $key => $val) {
                if ($val['catid'] == $_GET['catid'] || strtolower($case) == strtolower($val['cat_name'])) {
                    $showcat = $val;
                    $bulcat = $val;
                }
            }
        }

        //echo "<pre>";print_r($showcat);die;

        if (count($headcat)) {
            foreach ($headcat['root'] as $key => $val) {
                $showhead[$val['catid']] = $val;
            }
        }

        //echo "<pre>";print_r($showhead);die;

        switch ($case) {
            case 'signup':
                $page = 'signup';
                //include 'template/signup.html';

                /*if(stristr(DOMAIN, 'demo.iftosi.com') || stristr(DOMAIN, 'localhost') || stristr(DOMAIN, 'live.iftosi.com') || stristr(DOMAIN, 'beta.xelpmoc.in/iftosi') || stristr(REQURI, 'beta'))
                {*/
                        include 'template/signup.html';
                /*}
                else
                {
                        include 'template/comingsoon.html';
                }*/
                break;

            case 'vsignup':
                $page = 'signup';
                include 'template/vSignUp.html';
                break;
            case 'forgot':
                $page = 'forgot';
                include 'template/forgotPsw.html';
                break;
             case 'diamond_shapes':
                $page = 'diamond_shapes';
                include 'template/diamond.html';
                break;

             case 'jewellery_tips':
                $page = 'jewellery_tips';
                include 'template/jewellery_tips.html';
                break;
             case 'education_round':
                $page = 'education_round';
                include 'template/education_round.html';
                break;
            case 'education_pear':
                $page = 'education_pear';
                include 'template/education_pear.html';
                break;
            case 'education_oval':
                $page = 'education_oval';
                include 'template/education_oval.html';
                break;
            case 'education_emerald':
                $page = 'education_emerald';
                include 'template/education_emerald.html';
                break;
             case 'education_clarity':
                $page = 'education_clarity';
                include 'template/education_clarity.html';
                break;
            case 'education_asscher':
                $page = 'education_asscher';
                include 'template/education_asscher.html';
                break;
             case 'education_heart':
                $page = 'education_heart';
                include 'template/education_heart.html';
                break;
            case 'education_marq':
                $page = 'education_marq';
                include 'template/education_marq.html';
                break;
            case 'education_radiant':
                $page = 'education_radiant';
                include 'template/education_radiant.html';
                break;
            case 'e_certification':
                $page = 'e_certification';
                include 'template/e_certification.html';
                break;
            case 'e_certification1':
                $page = 'e_certification1';
                include 'template/e_certification1.html';
                break;
            case 'e_certification2':
                $page = 'e_certification2';
                include 'template/e_certification2.html';
                break;
            case 'e_carat_weight':
                $page = 'e_carat_weight';
                include 'template/e_carat_weight.html';
                break;
             case 'education_cushion':
                $page = 'education_cushion';
                include 'template/education_cushion.html';
                break;
            case 'education_princess':
                $page = 'education_princess';
                include 'template/education_princess.html';
                break;
            case 'e_color':
                $page = 'e_color';
                include 'template/e_color.html';
                break;
             case 'e_color1':
                $page = 'e_color1';
                include 'template/e_color1.html';
                break;
             case 'e_cut':
                $page = 'e_cut';
                include 'template/e_cut.html';
                break;
             case 'e_cut1':
                $page = 'e_cut1';
                include 'template/e_cut1.html';
                break;
            case 'e_cut2':
                $page = 'e_cut2';
                include 'template/e_cut2.html';
                break;
            case 'e_cut3':
                $page = 'e_cut3';
                include 'template/e_cut3.html';
                break;
            case 'e_cut4':
                $page = 'e_cut4';
                include 'template/e_cut4.html';
                break;
            case 'e_cut5':
                $page = 'e_cut5';
                include 'template/e_cut5.html';
                break;
            case 'e_glossary':
                $page = 'e_glossary';
                include 'template/e_glossary.html';
                break;
            case 'e_shapes':
                $page = 'e_shapes';
                include 'template/e_shapes.html';
                break;
            case 'privacy':
                $page = 'privacy';
                include 'template/privacy.html';
                break;

            case 'vendor_privacy':
                $page = 'vendor_privacy';
                include 'template/vprivacy.html';
                break;
             case 'customerDtls':
                $page = 'customerDtls';
                include 'template/customerDtls.html';
                break;
            case 'inactive_vendor':
                $page = 'inactive_vendor';
                $uid = $_GET['uid'];
                $url = APIDOMAIN . 'index.php?action=viewAll&uid='.$uid;
                $res = $comm->executeCurl($url);
                $data = $res['results'][1];
                //echo "<pre>";print_r($data);die;
                include 'template/inactiveVendor.html';
                break;

            case 'expiredSub_vendor':
                $page = 'expiredSub_vendor';
                $uid = $_GET['uid'];
                $url = APIDOMAIN . 'index.php?action=viewAll&uid='.$uid;
                $res = $comm->executeCurl($url);
                $data = $res['results'][1];
                //echo "<pre>";print_r($data);die;
                include 'template/expiredSub_vendor.html';
                break;

             case 'faq_sellers':
                $page = 'faq_sellers';
                include 'template/faq_sellers.html';
                break;
            case 'approachOption':
                $page = 'approachOption';
                $uid = $_GET['uid'];
                $url = APIDOMAIN . 'index.php?action=viewAll&uid=' . $uid;
                $res = $comm->executeCurl($url);
                $data = $res['results'][1];
                include 'template/approachOption.html';
                break;

            case 'changepwd':
//                if(isset($params['pr_cpass'])) {
//                    $uid=$params['uid'];
//                    $pr_cpass=$params['pr_cpass'];
//                    $pr_npass=$params['pr_npass'];
//                    $pr_rpass=$params['pr_rpass'];
//                    $uid=$params['uid'];
//
//                    $curl = APIDOMAIN . 'index.php?action=changepwd&cpass='.$pr_cpass.'&npass='.$pr_npass.'&rpass='.$pr_rpass.'&uid='.$uid;
//                    $cres = $comm->executeCurl($curl);
//                    $errCode = $cres['error']['Code'];
//                    echo $errMsg = $cres['error']['Msg'];
//                    die();
//                }
                $page = 'Change Password';
                $urlkey = $_GET['key'];
                $urlkey = explode(' ',$urlkey);
                $urlkey = $urlkey[1];
                include 'template/changepwd.html';
                break;
            case 'login':
                $page = 'login';
                include 'template/login.html';
                break;

            case 'policy':
                $page = 'policy';
                include 'template/privacy.html';
                break;

            case 'loginsel':
                $page = 'loginsel';
                $uid = $_GET['uid'];
                include 'template/new.html';
                break;

            case 'upload':
                $pid = $_GET['pid'];
                $page = 'upload';
                setcookie("back", $_SERVER['HTTP_REFERER']);
                //echo "<pre>";print_r($_SERVER);die;
                include 'image-upload/index.html';
                break;

            case 'wishlist':
                $page = 'wishlist';

                $uid = $_GET['uid'];
                $pgno = ($_GET['pgno'] ? $_GET['pgno'] : 1);

                $curl = APIDOMAIN . 'index.php?action=getCatList&page=1&limit=3';
                $cres = $comm->executeCurl($curl);
                $cdata = $cres['results'];
                $firstid = $_GET['catid'] = $cdata[0]['category_id'];

                $curl = APIDOMAIN . 'index.php?action=catCountWish&uid=' . $uid;
                $carr = $comm->executeCurl($curl);

                $url = APIDOMAIN . 'index.php?action=getPrdByCatid&uid=' . $uid . '&page=' . $pgno . '&catid=' . $firstid;
                $res = $comm->executeCurl($url);
                $data = $res['results']['products'];
                $vrate = $data['vdetail'];
                $total = $res['results']['total'];
                $catname = $res['results']['catname'];
                $totalCnt = $total;
                $lastpg = floor($total / 16);
                $adjacents = 2;

                //echo "<pre>";print_r($carr);die;

                include 'template/wishlist.html';
                break;

            case 'b2bproducts':
                $dummypage = $case;
                $page = 'diamonds';
                $slist = $_GET['slist'];
                $pgno = ($_GET['pgno'] ? $_GET['pgno'] : 1);
                $catid = $_GET['catid'];
                $url = APIDOMAIN . 'index.php?action=getPrdByCatid&catid=' . $catid . '&page=' . $pgno . '&slist=' . $slist;
                $res = $comm->executeCurl($url);
                $data = $res['results']['products'];
                $total = $res['results']['total'];
                $catname = $res['results']['catname'];


                $url = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=' . $catid . '&case=b2bproducts';
                $res = $comm->executeCurl($url);
                $fil = $res['results']['attributes'];

                $totalCnt = $total;
                $lastpg = ceil($total / 15);
                $adjacents = 2;
                //echo "<pre>";print_r($total);die;
                if($totalCnt > 0)
                {
                        include 'template/resultsb2b.html';
                }
                else
                {
                        include 'template/comingsoon.html';
                }
                //include 'template/resultsb2b.html';
                break;

            case 'diamonds':
                $page = 'diamonds';
                $slist = $_GET['slist'];
                $pgno = ($_GET['pgno'] ? $_GET['pgno'] : 1);
                $catid = $_GET['catid'];
                $url = APIDOMAIN . 'index.php?action=getPrdByCatid&catid=' . $catid . '&page=' . $pgno . '&slist=' . $slist;
                $res = $comm->executeCurl($url);
                $data = $res['results']['products'];
                $total = $res['results']['total'];
                $catname = $res['results']['catname'];


                $url = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=' . $catid;
                $res = $comm->executeCurl($url);
                $fil = $res['results']['attributes'];

                $totalCnt = $total;
                $lastpg = ceil($total / 15);
                $adjacents = 2;
                //echo "<pre>";print_r($total);die;
                //include 'template/results.html';
                if($totalCnt > 0)
                {
                        include 'template/results.html';
                }
                else
                {
                        include 'template/comingsoon.html';
                }
                break;

            case 'jewellery':
                $page = 'jewellery';
                $pgno = (!empty($_GET['pgno']) ? $_GET['pgno'] : 1);
                $catid = $_GET['catid'];
                $url = APIDOMAIN . 'index.php?action=getPrdByCatid&catid=' . $catid . '&page=' . $pgno;
                $res = $comm->executeCurl($url);
                $data = $res['results']['products'];
                $total = $res['results']['total'];
                $catname = $res['results']['catname'];

                $url = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=' . $catid;
                $res = $comm->executeCurl($url);
                $fil = $res['results']['attributes'];

                $totalCnt = $total;
                $lastpg = ceil($total / 15);
                $adjacents = 2;
                for ($i = 0; $i < count($data); $i++) {
                    $pid = $data[$i]['pid']; //die;
                    $url1 = APIDOMAIN . 'index.php?action=imagedisplay&pid=' . $pid;
                    $res1 = $comm->executeCurl($url1);
                    $data1 = $res1['results'];
                    $datacnt = $res1['count'];
                    //echo '<pre>';print_r($data1);
                }
                //echo "<pre>";print_r($data);die;
                //include 'template/jewellery_results.html';
                /*if(stristr(DOMAIN, 'demo.iftosi.com') || stristr(DOMAIN, 'localhost') || stristr(DOMAIN, 'live.iftosi.com') || stristr(REQURI, 'beta'))
                {*/
                        include 'template/jewellery_results.html';
                /*}
                else
                {
                        include 'template/comingsoon.html';
                }*/
                break;
            case 'bullion':
                $page = 'bullion';
                $pgno = ($_GET['pgno'] ? $_GET['pgno'] : 1);
                $catid = $_GET['catid'];
                $url = APIDOMAIN . 'index.php?action=getPrdByCatid&catid=' . $catid . '&page=' . $pgno;
                $res = $comm->executeCurl($url);
                $data = $res['results']['products'];
                $total = $res['results']['total'];
                $catname = $res['results']['catname'];

                $url = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=' . $catid;
                $res = $comm->executeCurl($url);
                $fil = $res['results']['attributes'];

                //echo "<pre>";print_r($data);die;

                $totalCnt = $total;
                $lastpg = ceil($total / 15);
                $adjacents = 2;
                //include 'template/bullion_results.html';
                if($totalCnt > 0)
                {
                        include 'template/bullion_results.html';
                }
                else
                {
                        include 'template/comingsoon.html';
                }
                break;


            case 'b2bdetails':
                $page = 'diamond_details';
                $prdList = $pid = $_GET['productid'];
                $uid = $_GET['userid'];
                $url = APIDOMAIN . 'index.php?action=getPrdById&prdid=' . $pid;
                $res = $comm->executeCurl($url);
                $prdVars = $data = $prdInfo = $res['results'][$pid];

                $vndrInfo = $prdInfo['vendor_details'];

                foreach ($vndrInfo as $key => $value) {
                    $vndrId = $key;
                    $vndrDtls = $value;
                }

                if ((empty($vndrDtls['latitude']) || empty($vndrDtls['longitude'])) && !empty($vndrDtls['area']) && !empty($vndrDtls['city'])) {
                    $latLngAreaURL = APIDOMAIN . 'index.php?action=getLatLngByArea&area=' . urlencode($vndrDtls['area']) . '&city=' . urlencode($vndrDtls['city']);
                    $latLngArea = $comm->executeCurl($latLngAreaURL);
                    if (!empty($latLngArea) && !empty($latLngArea['error']) && empty($latLngArea['error']['errCode'])) {
                        $vndrDtls['latitude'] = $latLngArea['results']['latitude'];
                        $vndrDtls['longitude'] = $latLngArea['results']['longitude'];
                    }
                }

                $vndrDtls['fulladdress'] = explode(",", $vndrDtls['fulladdress']);
                foreach ($vndrDtls['fulladdress'] as $key => $value) {
                    $vndrDtls['fulladdress'][$key] = trim($value);
                }

                if (!empty($uid)) {
                    $url = APIDOMAIN . 'index.php?action=checklist&uid=' . $uid . '&vid=' . $vndrId . '&prdid=' . $pid;
                    $res = $comm->executeCurl($url);
                    $wish = $res['error'];
                }

                $vndrDtls['fulladdress'] = implode(', ', $vndrDtls['fulladdress']);
                $vndrAddr = explode(',', $vndrDtls['fulladdress']);
                //echo "<pre>";print_r($vndrDtls);die;
                $certificate_url = $data['attr_details']['certificate_url'];
                $certificate_url = explode('/', $certificate_url);
                $certificate_url = $certificate_url[count($certificate_url) - 1];

                $url1 = APIDOMAIN . 'index.php?action=imagedisplay&pid='.$pid.'&vid='.$vndrDtls['vid'];
                $res1 = $comm->executeCurl($url1);
                $data1 = $res1['results'];
                $datacnt = $res1['count'];

                $prdVars = $prdVars['attr_details'];
                $sug   = APIDOMAIN."index.php?action=suggestProducts&pid=".$pid."&catid=10000&clarity=".urlencode($prdVars['clarity'])."&carat=".$prdVars['carat']."&shape=".urlencode($prdVars['shape'])."&cut=".urlencode($prdVars['cut'])."&color=".urlencode($prdVars['color'])."&fluo=".urlencode($prdVars['fluorescence'])."&sym = ".urlencode($prdVars['symmetry'])."&polish=".urlencode($prdVars['polish']);
                $res3  = $comm->executeCurl($sug);
                $data3 = $res3['results'];
                $sugTotal = 0;

                $ValArr=array('EX'=>'Excellent','VG'=>'Very Good','GD'=>"Good",'FAIR'=>'Fair','NN'=>'None','MED'=>'Medium','FNT'=>'Faint','STG'=>'Strong','VSTG'=>'Very Strong');
                foreach($ValArr as $ky=>$value)
                {
                    if($data['attr_details']['cut'] == $ky)
                    {
                       $prdVars['cut'] = $ValArr[$ky];
                    }
                }

                $desurl   = APIDOMAIN."index.php?action=showDescription&pid=".$pid."&catid=10000&color=".urlencode($prdVars['color'])."&cut=".urlencode($prdVars['cut'])."&clarity=".urlencode($prdVars['clarity'])."&shape=".urlencode($prdVars['shape']);
                $desres  = $comm->executeCurl($desurl);
                $des = $desres['results'];
                $totalDes = $res3['total'];
                //echo "<pre>";print_r($des); die;
                include 'template/diamond_detailsb2b.html';
                break;



            case 'diamond_details':
                $page = 'diamond_details';
                $prdList = $pid = $_GET['productid'];
                $uid = $_GET['userid'];
                $url = APIDOMAIN . 'index.php?action=getPrdById&prdid=' . $pid;
                $res = $comm->executeCurl($url);
                $prdVars = $data = $prdInfo = $res['results'][$pid];

                $vndrInfo = $prdInfo['vendor_details'];

                foreach ($vndrInfo as $key => $value) {
                    $vndrId = $key;
                    $vndrDtls = $value;
                }

                if ((empty($vndrDtls['latitude']) || empty($vndrDtls['longitude'])) && !empty($vndrDtls['area']) && !empty($vndrDtls['city'])) {
                    $latLngAreaURL = APIDOMAIN . 'index.php?action=getLatLngByArea&area=' . urlencode($vndrDtls['area']) . '&city=' . urlencode($vndrDtls['city']);
                    $latLngArea = $comm->executeCurl($latLngAreaURL);
                    if (!empty($latLngArea) && !empty($latLngArea['error']) && empty($latLngArea['error']['errCode'])) {
                        $vndrDtls['latitude'] = $latLngArea['results']['latitude'];
                        $vndrDtls['longitude'] = $latLngArea['results']['longitude'];
                    }
                }

                $vndrDtls['fulladdress'] = explode(",", $vndrDtls['fulladdress']);
                foreach ($vndrDtls['fulladdress'] as $key => $value) {
                    $vndrDtls['fulladdress'][$key] = trim($value);
                }

                if (!empty($uid)) {
                    $url = APIDOMAIN . 'index.php?action=checklist&uid=' . $uid . '&vid=' . $vndrId . '&prdid=' . $pid;
                    $res = $comm->executeCurl($url);
                    $wish = $res['error'];
                }

                $vndrDtls['fulladdress'] = implode(', ', $vndrDtls['fulladdress']);
                $vndrAddr = explode(',', $vndrDtls['fulladdress']);
                //echo "<pre>";print_r($vndrDtls);die;
                $certificate_url = $data['attr_details']['certificate_url'];
                $certificate_url = explode('/', $certificate_url);
                $certificate_url = $certificate_url[count($certificate_url) - 1];

                $url1 = APIDOMAIN . 'index.php?action=imagedisplay&pid='.$pid.'&vid='.$vndrDtls['vid'];
                $res1 = $comm->executeCurl($url1);
                $data1 = $res1['results'];
                $datacnt = $res1['count'];

                $prdVars = $prdVars['attr_details'];
                $sug   = APIDOMAIN."index.php?action=suggestProducts&pid=".$pid."&catid=10000&clarity=".urlencode($prdVars['clarity'])."&carat=".$prdVars['carat']."&shape=".urlencode($prdVars['shape'])."&cut=".urlencode($prdVars['cut'])."&color=".urlencode($prdVars['color'])."&fluo=".urlencode($prdVars['fluorescence'])."&sym = ".urlencode($prdVars['symmetry'])."&polish=".urlencode($prdVars['polish']);
                $res3  = $comm->executeCurl($sug);
                $data3 = $res3['results'];
                $sugTotal = $res3['total'];

                $ValArr=array('EX'=>'Excellent','VG'=>'Very Good','GD'=>"Good",'FAIR'=>'Fair','NN'=>'None','MED'=>'Medium','FNT'=>'Faint','STG'=>'Strong','VSTG'=>'Very Strong');
                foreach($ValArr as $ky=>$value)
                {
                    if($data['attr_details']['cut'] == $ky)
                    {
                       $prdVars['cut'] = $ValArr[$ky];
                    }
                }

                $desurl   = APIDOMAIN."index.php?action=showDescription&pid=".$pid."&catid=10000&color=".urlencode($prdVars['color'])."&cut=".urlencode($prdVars['cut'])."&clarity=".urlencode($prdVars['clarity'])."&shape=".urlencode($prdVars['shape']);
                $desres  = $comm->executeCurl($desurl);
                $des = $desres['results'];
                $totalDes = $res3['total'];
                //echo "<pre>";print_r($des); die;
                //include 'template/diamond_details.html';
                /*if(stristr(DOMAIN, 'demo.iftosi.com') || stristr(DOMAIN, 'localhost') || stristr(DOMAIN, 'live.iftosi.com') || stristr(REQURI, 'beta'))
                {*/
                        include 'template/diamond_details.html';
                /*}
                else
                {
                        include 'template/comingsoon.html';
                }*/
                break;

            case 'bullion_details':
                $page = 'bullion_details';
                $prdInfo = array();
                $prdList = $prdId = $orgPrdId = (!empty($_GET['productid'])) ? $_GET['productid'] : '';

                if (!empty($prdId)) {
                    $prdId = explode(' ', $prdId);
                    $prdId = $pid = $prdId[0];
                    $prdInfoUrl = APIDOMAIN . 'index.php?action=getPrdById&prdid=' . $prdId;
                     $prdInfo = $comm->executeCurl($prdInfoUrl);
                    if (!empty($prdInfo) && !empty($prdInfo['results']) && !empty($prdInfo['error']) && empty($prdInfo['error']['errCode'])) {
                       $prdVars = $prdInfo = $prdInfo['results'][$prdId];
                        $vndrInfo = $prdInfo['vendor_details'];
                        foreach ($vndrInfo as $key => $value) {
                            $vndrId = $key;
                            $vndrDtls = $value;
                        }
                        $vndrDtls['fulladdress'] = explode(",", $vndrDtls['fulladdress']);
                        foreach ($vndrDtls['fulladdress'] as $key => $value) {
                            $vndrDtls['fulladdress'][$key] = trim($value);
                        }
                        $vndrDtls['fulladdress'] = implode(', ', $vndrDtls['fulladdress']);
                        $vndrAddr = explode(',', $vndrDtls['fulladdress']);
                    }
                }
                $url1 = APIDOMAIN . 'index.php?action=imagedisplay&pid=' . $pid;
                $res1 = $comm->executeCurl($url1);
                $data1 = $res1['results'];

                $prdVars = $prdVars['attr_details'];
                $sug   = APIDOMAIN."index.php?action=suggestProducts&pid=".$pid."&catid=10002&metal=".urlencode($prdVars['metal'])."&type=".urlencode($prdVars['type']);
                $res3  = $comm->executeCurl($sug);
                $data3 = $res3['results'];
                $sugTotal = $res3['total'];

                $desurl   = APIDOMAIN."index.php?action=showDescription&pid=".$pid."&catid=10002&metal=".urlencode($prdVars['metal'])."&type=".strtolower(urlencode($prdVars['type']));
                $desres  = $comm->executeCurl($desurl);
                $des = $desres['results'];
                $totalDes = $res3['total'];

                //echo "<pre>".print_r($data3); die;
                //include 'template/bullion_details.html';
                /*if(stristr(DOMAIN, 'demo.iftosi.com') || stristr(DOMAIN, 'localhost') || stristr(DOMAIN, 'live.iftosi.com') || stristr(REQURI, 'beta'))
                {*/
                        include 'template/bullion_details.html';
                /*}
                else
                {
                        include 'template/comingsoon.html';
                }*/
                break;

            case 'jewellery_details':
                $page = 'jewellery_details';
                $prdInfo = array();
                $prdName = (!empty($_GET['productname'])) ? $_GET['productname'] : '';
                $prdId = $orgPrdId = (!empty($_GET['productid'])) ? $_GET['productid'] : '';
                if (!empty($prdId))
                {
                    $prdId = explode(' ', $prdId);
                    $prdList = $prdId = $pid = $prdId[1];
                    $prdInfoUrl = APIDOMAIN . 'index.php?action=getPrdById&prdid=' . $prdId;
                    $prdInfo = $comm->executeCurl($prdInfoUrl);
                    if (!empty($prdInfo) && !empty($prdInfo['results']) && !empty($prdInfo['error']) && empty($prdInfo['error']['errCode']))
                    {
                        $prdDet = $prdInfo = $prdInfo['results'][$prdId];
                        $vndrInfo = $prdInfo['vendor_details'];
                        foreach ($vndrInfo as $key => $value)
                        {
                            $vndrId = $key;
                            $vndrDtls = $value;
                        }
                        $vndrDtls['fulladdress'] = explode(",", $vndrDtls['fulladdress']);
                        foreach ($vndrDtls['fulladdress'] as $key => $value)
                        {
                            $vndrDtls['fulladdress'][$key] = trim($value);
                        }
                        $vndrDtls['fulladdress'] = implode(', ', $vndrDtls['fulladdress']);
                        $vndrAddr = explode(',', $vndrDtls['fulladdress']);
                    }
                }
                $url1 = APIDOMAIN . 'index.php?action=imagedisplay&pid=' . $pid;
                $res1 = $comm->executeCurl($url1);
                $data1 = $res1['results'];

                $gemsUrl = APIDOMAIN . 'index.php?action=getGemstoneTypes';
                $gemsRes = $comm->executeCurl($gemsUrl);
                $gemsAttrs = $gemsRes['results'];

                foreach($gemsAttrs as $key => $value)
                {
                    if(strtolower($prdInfo['attr_details']['gemstone_type']) == $value['name'])
                    {
                        $prdInfo['attr_details']['gemstone_type'] = $value['display_name'];
                        break;
                    }
                }
                $diamondsShape = explode('|!|', $prdInfo['attr_details']['diamond_shape']);
                $diamondsShape = array_unique($diamondsShape);

                $prdInfo['attr_details']['diamond_shape'] = implode('|!|', $diamondsShape);

                $diamondsClarity = explode('|!|', $prdInfo['attr_details']['clarity']);
                $diamondsClarity = array_unique($diamondsClarity);


                $tempcheck1 = strpos($prdInfo['attr_details']['color'],'~');
                if($tempcheck1 == true)
                {
                    $diamondsColor = explode('~',$prdInfo['attr_details']['color']);
                }
                else
                {
                    $diamondsColor = explode('|!|', $prdInfo['attr_details']['color']);
                }

                $diamondsColor = array_unique($diamondsColor);


                $metal = $prdInfo['attr_details']['metal'];
                $tempcheck2 = strpos($metal,'~');
                if($tempcheck2 == true)
                {
                    $metal = explode('~',$metal);
                }
                else
                {
                    $metal = explode('|!|', $prdInfo['attr_details']['metal']);
                }
                $metal = array_unique($metal);

                $prdInfo['attr_details']['clarity'] = implode('|!|', $diamondsClarity);

                $gemstoneType = explode('|!|', $prdInfo['attr_details']['gemstone_type']);
                $gemstoneType = array_unique($gemstoneType);
                $prdInfo['attr_details']['gemstone_type'] = implode('|!|', $gemstoneType);

                $gemstoneColor = explode('|!|', $prdInfo['attr_details']['gemstone_color']);
                $gemstoneColor = array_unique($gemstoneColor);
                $prdInfo['attr_details']['gemstone_color'] = implode('|!|', $gemstoneColor);

                $prdVars = $prdInfo['attr_details'];

                $diamondsShape  = implode(',',$diamondsShape);
                $diamondsClarity  = implode(',',$diamondsClarity);
                $diamondsColor  = implode(',',$diamondsColor);
                $metal = implode(',',$metal);
                $gemstoneType = implode(',',$gemstoneType);

                $desurl   = APIDOMAIN."index.php?action=showDescription&pid=".$pid."&catid=10001&gemstone_type=".strtolower(urlencode($gemstoneType))."&metal=".strtolower(urlencode($metal))."&clarity=".urlencode($diamondsClarity)."&colour=".urlencode($diamondsColor)."&cert=".strtolower($prdVars['certified'])."&shape=".$diamondsShape."";
                $desres  = $comm->executeCurl($desurl);
                $des = $desres['results'];
                $totalDes = $res3['total'];

                $sug   = APIDOMAIN."index.php?action=suggestProducts&pid=".$pid."&catid=10001&metal=".strtolower(urlencode($metal))."&purity=".$prdVars['gold_purity']."&gwt=".$prdVars['gold_weight']."&shape=".urlencode($diamondsShape)."&certified=".strtolower(urlencode($prdVars['certified']));
                $res3  = $comm->executeCurl($sug);
                $data3 = $res3['results'];
                $sugTotal = $res3['total'];

                foreach($data3 as $imagekey=>$imgval)
                {
                    $urlI = APIDOMAIN . 'index.php?action=imagedisplay&pid='.$imagekey;
                    $resI = $comm->executeCurl($urlI);
                    $dataI[] = $resI['results'];
                    $smPrdList[] .= $imagekey;
                }
                $smPrdList = implode(',',$smPrdList);

                //echo "<pre>"; print_r($des); die;
                //include 'template/jewellery_details.html';
                /*if(stristr(DOMAIN, 'demo.iftosi.com') || stristr(DOMAIN, 'localhost') || stristr(DOMAIN, 'live.iftosi.com') || stristr(REQURI, 'beta'))
                {*/
                        include 'template/jewellery_details.html';
                /*}
                else
                {
                        include 'template/comingsoon.html';
                }*/
                break;

            case 'diamond_Form':
                $page = 'diamond-Form';
                $vid = $_GET['vid'];
                $catid = $_GET['catid'];
                $pid = $_GET['prdid'];
                $dt = $_POST['dt'];

                if (!empty($catid) && !empty($dt) && !empty($vid)) {
                    $url = APIDOMAIN . 'index.php?action=addNewproduct&category_id=' . $catid . '&dt=' . $dt . '&vid=' . $vid;
                    $res = $comm->executeCurl($url);
                    $data = $res['results'][$dt];
                }
                if (!empty($catid) && !empty($pid)) {
                    $url = APIDOMAIN . 'index.php?action=getPrdById&category_id=' . $catid . '&prdid=' . $pid;
                    $res = $comm->executeCurl($url);
                    $result = $res['results'];
                }

                $url = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=' . $catid;
                $res = $comm->executeCurl($url);
                $fil = $res['results'];

                $attr = $result[$pid]['attr_details'];
                $pdet = $result[$pid];
        				$certificate_url = $attr['certificate_url'];
        				$certificate_url = explode('/', $certificate_url);
        				$certificate_url = $certificate_url[count($certificate_url) - 1];
                //echo "<pre>";print_r($attr);die;
                include 'template/diamondForm.html';
                break;


            case 'jewellery_Form':
                $page = 'jewellery-Form';
                $vid = $_GET['vid'];
                $catid = $_GET['catid'];
                $pid = $_GET['prdid'];
                $dt = $_POST['dt'];

                if (!empty($catid) && !empty($dt) && !empty($vid)) {
                    $url = APIDOMAIN . 'index.php?action=addNewproduct&category_id=' . $catid . '&dt=' . $dt . '&vid=' . $vid;
                    $res = $comm->executeCurl($url);
                    $data = $res['results'][$dt];
                }
                if (!empty($catid) && !empty($pid)) {
                    $url = APIDOMAIN . 'index.php?action=getPrdById&catid=' . $catid . '&prdid=' . $pid;
                    $res = $comm->executeCurl($url);
                    $result = $res['results'];
                }
                //echo "<pre>";print_r($result);die;
                $url = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=' . $catid;
                $res = $comm->executeCurl($url);
                $fil = $res['results']['attributes'];
                //echo "<pre>";print_r($fil);die;
                $url = APIDOMAIN . 'index.php?action=categoryHeir&catid=' . $catid;
                $res = $comm->executeCurl($url);
                $cat = $res['result'];

                $catres = $cat['subcat'][0]['attr'];
                $attr = $result[$pid]['attr_details'];
                $colors = $attr['color'];
                $clarity = $attr['clarity'];
                $pdet = $result[$pid];

                $certificate_url = $attr['certificate_url'];
        				$certificate_url = explode('/', $certificate_url);
        				$certificate_url = $certificate_url[count($certificate_url) - 1];

                $shapeUrl = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=10000';
                $shapeRes = $comm->executeCurl($shapeUrl);
                $shapeAttrs = $shapeRes['results'];

                $gemsUrl = APIDOMAIN . 'index.php?action=getGemstoneTypes';
                $gemsRes = $comm->executeCurl($gemsUrl);
                $gemsAttrs = $gemsRes['results'];

                include 'template/jewelleryForm.html';
                break;

            case 'bullion_Form':
                $page = 'bullion-Form';
                $vid = $_GET['vid'];
                $catid = $_GET['catid'];
                $pid = $_GET['prdid'];
                $dt = $_POST['dt'];

                if (!empty($catid) && !empty($dt) && !empty($vid)) {
                    $url = APIDOMAIN . 'index.php?action=addNewproduct&category_id=' . $catid . '&dt=' . $dt . '&vid=' . $vid;
                    $res = $comm->executeCurl($url);
                    $data = $res['results'][$dt];
                }

                if (!empty($catid) && !empty($pid)) {
                    $url = APIDOMAIN . 'index.php?action=getPrdById&category_id=' . $catid . '&prdid=' . $pid . '&vid=' . $vid;
                    $res = $comm->executeCurl($url);
                    $result = $res['results'];
                }
                $url = APIDOMAIN . 'index.php?action=categoryHeir&catid=' . $catid;
                $res = $comm->executeCurl($url);
                $cat = $res['result'];
                $url = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=' . $catid;
                $res = $comm->executeCurl($url);
                $fil = $res['results'];
                $attr = $result[$pid]['attr_details'];
                $pdet = $result[$pid];
                include 'template/bullionForm.html';
                break;

            case 'vendor_Form':
                $page = 'vendor-Form';
                $uid = $_GET['uid'];
                $url = APIDOMAIN . 'index.php?action=viewAll&uid=' . $uid;
                $res = $comm->executeCurl($url);
                $data = $res['results'][1];
                $banker = $data['banker'];
                //echo "<pre>";print_r($banker);die;
                include 'template/vendorDetails.html';
                break;

            case 'vcontact_Form':
                $page = 'vcontact-Form';
                include 'template/vcontactDetails.html';
                break;
            case 'vProduct_diamonds':
                $page = 'Products-Diamond';
                include 'template/vProducts_Diamond.html';
                break;
            case 'vProduct_jewellery':
                $page = 'Products-Jewellery';
                include 'template/vProducts_jewellery.html';
                break;
            case 'vProduct_bullion':
                $page = 'Products-Bullion';
                include 'template/vProducts_bullion.html';
                break;
            case 'vendor_landing':
                $page = 'Products';
                $pgno = (!empty($_GET['pgno']) ? $_GET['pgno'] : 1);
                $catid = (!empty($_GET['catid']) ? $_GET['catid'] : '');
                $vid = $_GET['userid'];

                $url = APIDOMAIN . 'index.php?action=getVPrdByCatid&catid=' . $catid . '&page=' . $pgno;
                $catid = $_GET['catid'];
                $url = APIDOMAIN . 'index.php?action=getVPrdByCatid&catid=' . $catid . '&page=' . $pgno;
                $res = $comm->executeCurl($url);
                $data = $res['results']['products'];
                $total = $res['results']['total'];
                $catname = $res['results']['catname'];
                $vdet = $res['results'];

                $url = APIDOMAIN . 'index.php?action=profileComplete&uid=' . $vid;
                $res1 = $comm->executeCurl($url);
                $isC = $res1;

                $url = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=' . $catid;
                $res = $comm->executeCurl($url);
                $fil = $res['results']['attributes'];
                //echo "<pre>";print_r($vdet);die;

                $totalCnt = $total;
                $lastpg = ceil($total / 15);
                $adjacents = 2;
                include 'template/vendor_landing_page.html';
                break;

            case 'vendor_enquiries':
                $page = 'Enquiries';
                $uid = $_GET['uid'];
                $catid = (!empty($_GET['catid']) ? $_GET['catid'] : '');

                $url = APIDOMAIN . 'index.php?action=viewLog&vid=' . $uid;
                $res = $comm->executeCurl($url);

                $total = $res['total_enqs'];
                $lastpg = ceil($total / 15);
                $adjacents = 2;

                include 'template/vendor_enquiries.html';
                break;

            case 'pending':
                $page = 'Pending';
                $uid = $_GET['uid'];
                $catid = (!empty($_GET['catid']) ? $_GET['catid'] : '');
                $pgno = (!empty($_GET['pgno']) ? $_GET['pgno'] : 1);
                include 'template/pendingProducts.html';
                break;



            case 'vendorList':
                $page = 'vendorList';
                $pgno = (!empty($_GET['pgno']) ? $_GET['pgno'] : 1);
                $limit = (!empty($_GET['limit']) ? $_GET['limit'] : 2);
                $url = APIDOMAIN . 'index.php?action=vendorlist&pgno='.$pgno.'&limit='.$limit;
                $res = $comm->executeCurl($url);
                $data = $res['results']['vendors'];
                $total = $res['results']['total_vendors'];
                $totalCnt = $total;
                $lastpg = ceil($total / 15);
                $adjacents = 2;
                //echo "<pre>";print_r($data);die;
                include 'template/vendorList.html';
                break;

            case 'about_us':
                $page = 'about_us';
                include 'template/about_us.html';
                break;

            case 'team':
                $page = 'team';
                include 'template/team.html';
                break;

            case 'comingsoon':
                $page = 'comingsoon';
                include 'template/comingsoon.html';
                break;

            case 'contact_us':
                $page = 'contact_us';
                include 'template/contact_us.html';
                break;

            case 'terms_conditions':
                $page = 'terms_conditions';
                include 'template/terms_conditions.html';
                break;

            case 'terms_of_listing':
                $page = 'terms_of_listing';
                include 'template/terms_of_listing.html';
                break;

            case 'vterms_of_listing':
                $page = 'vterms_of_listing';
                include 'template/vterms_of_listing.html';
                break;


            case 'faq':
                $page = 'faq';
                include 'template/faq.html';
                break;

            case 'faqs_seller':
                $page = 'faq';
                include 'template/faq_sellers.html';
                break;
            case 'education':
                $page = 'education';
                include 'template/education.html';
                break;
            case 'membership':
                $page = 'membership';
                include 'template/membership.html';
                break;
            case 'shapes':
                $page = 'shapes';
                include 'template/shapes.html';
                break;

            case 'product_list':
                $page = 'product_list';
                $pgno = (!empty($_GET['pgno']) ? $_GET['pgno'] : 1);
                $url = APIDOMAIN . 'index.php?action=getProdList&page='.$pgno;
                $res = $comm->executeCurl($url);
                $data = $res['results']['products'];
                $total = $res['results']['total_products'];
                $totalCnt = $total;
                $lastpg = ceil($total / 15);
                $adjacents = 2;
                include 'template/product_list.html';
                break;

            case 'thumbnail':
                $url = APIDOMAIN . 'index.php?action=getImgByProd&pid='.$params['pid'];
                $res = $comm->executeCurl($url);
                $result = $res['results'];
//                echo '<pre>';                print_r($result);die();
                $page = 'thumbnail';
                include 'template/thumbnail.html';
                break;

            case 'testMail':
                $page = 'testMail';
                include 'template/testMail.html';
                break;

            default:
                $page = 'index';

                $url = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=10000';
                $res = $comm->executeCurl($url);
                $fil = $res['results']['attributes'];

                $url = APIDOMAIN . 'index.php?action=getCatList&page=1&limit=3';
                $res = $comm->executeCurl($url);
                $data = $res['results'];
                //include 'template/index.html';
                        include 'template/index.html';
                break;
        }
        break;
}
?>
