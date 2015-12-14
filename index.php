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
                $url = APIDOMAIN . 'index.php?action=suggestAreaCity&str=' . urlencode($str) . '&page=1&limit=5';
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

                if ($pid) {
                    $url = APIDOMAIN . 'index.php?action=removeFromWishlist&uid=' . $uid . '&pid=' . $pid;
                    $res = $comm->executeCurl($url);
                }

                $url = APIDOMAIN . 'index.php?action=getPrdByCatid&catid=' . $catid . '&page=' . $pgno . '&sortby=' . $sortby . '&slist=' . urlencode($slist) . '&clist=' . urlencode($clist) . '&tlist=' . urlencode($tlist) . '&ilist=' . urlencode($ilist) . '&jlist=' . urlencode($jlist) . '&ctid=' . $ctid . '&uid=' . $uid;
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

                $userUrl = APIDOMAIN . 'index.php?action=checkUser&mobile=' . $mobile;
                $resp = $comm->executeCurl($userUrl);

                if (!empty($resp) && !empty($resp['error']) && !empty($resp['results']) && empty($resp['error']['Code'])) {
                    if ($resp['results'] == 'User Not yet Registered') {
                        $regUserUrl = APIDOMAIN . 'index.php?action=userReg&mobile=' . $mobile . '&username=' . urlencode($name) . '&email=' . urlencode($email);
                        $resp = $comm->executeCurl($regUserUrl);
                    }
                }
                echo json_encode($resp);
                break;

            case 'updateStatus':

                $vid = (!empty($_GET['uid'])) ? trim($_GET['uid']) : '';
                $af = (!empty($_GET['af'])) ? trim($_GET['af']) : 0;
                $userUrl = APIDOMAIN . 'index.php?action=actUser&userid=' . $vid . '&active_flag=' . $af;
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
                include 'template/signup.html';
                break;
            case 'forgot':
                $page = 'forgot';
                include 'template/forgotPsw.html';
                break;
             case 'diamond_shapes':
                $page = 'diamond_shapes';
                include 'template/diamond.html';
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
                include 'template/changepwd.html';
                break;
            case 'login':
                $page = 'login';
                include 'template/login.html';
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
                $total = $res['results']['total'];
                $catname = $res['results']['catname'];
                $totalCnt = $total;
                $lastpg = ceil($total / 16);
                $adjacents = 2;

                //echo "<pre>";print_r($data);die;

                include 'template/wishlist.html';
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
                //echo "<pre>";print_r($data);die;

                $url = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=' . $catid;
                $res = $comm->executeCurl($url);
                $fil = $res['results']['attributes'];

                $totalCnt = $total;
                $lastpg = ceil($total / 15);
                $adjacents = 2;

                include 'template/results.html';
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
                //echo "<pre>";print_r($data[4]['images']);die;
                include 'template/jewellery_results.html';
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
                include 'template/bullion_results.html';
                break;

            case 'diamond_details':
                $page = 'diamond_details';
                $pid = $_GET['productid'];
                $uid = $_GET['userid'];

                $url = APIDOMAIN . 'index.php?action=getPrdById&prdid=' . $pid;
                $res = $comm->executeCurl($url);
                $data = $prdInfo = $res['results'][$pid];

                $url1 = APIDOMAIN . 'index.php?action=imagedisplay&pid=' . $pid;
                $res1 = $comm->executeCurl($url1);
                $data1 = $res1['results'];
                $datacnt = $res1['count'];

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
                //echo "<pre>";print_r($data);die;
                include 'template/diamond_details.html';
                break;
            case 'bullion_details':
                $page = 'bullion_details';
                $prdInfo = array();
                $prdId = $orgPrdId = (!empty($_GET['productid'])) ? $_GET['productid'] : '';

                if (!empty($prdId)) {
                    $prdId = explode(' ', $prdId);
                    $prdId = $pid = $prdId[1];
                    $prdInfoUrl = APIDOMAIN . 'index.php?action=getPrdById&prdid=' . $prdId;
                    $prdInfo = $comm->executeCurl($prdInfoUrl);
                    if (!empty($prdInfo) && !empty($prdInfo['results']) && !empty($prdInfo['error']) && empty($prdInfo['error']['errCode'])) {
                        $prdInfo = $prdInfo['results'][$prdId];
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
                //echo "<pre>".print_r($data1); die;
                include 'template/bullion_details.html';
                break;
            case 'jewellery_details':
                $page = 'jewellery_details';
                $prdInfo = array();

                $prdName = (!empty($_GET['productname'])) ? $_GET['productname'] : '';
                $prdId = $orgPrdId = (!empty($_GET['productid'])) ? $_GET['productid'] : '';

                if (!empty($prdId)) {
                    $prdId = explode(' ', $prdId);
                    $prdId = $pid = $prdId[1];
                    $prdInfoUrl = APIDOMAIN . 'index.php?action=getPrdById&prdid=' . $prdId;
                    $prdInfo = $comm->executeCurl($prdInfoUrl);
                    if (!empty($prdInfo) && !empty($prdInfo['results']) && !empty($prdInfo['error']) && empty($prdInfo['error']['errCode'])) {
                        $prdInfo = $prdInfo['results'][$prdId];
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
                //echo "<pre>".print_r($prdInfo); die;
                include 'template/jewellery_details.html';
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

                $url = APIDOMAIN . 'index.php?action=categoryHeir&catid=' . $catid;
                $res = $comm->executeCurl($url);
                $cat = $res['result'];
                $catres = $cat['subcat'][0]['attr'];
                $attr = $result[$pid]['attr_details'];
                $pdet = $result[$pid];
                //echo "<pre>";print_r($attr['gemstone_color']);die;

				$shapeUrl = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=10000';
                $shapeRes = $comm->executeCurl($shapeUrl);
                $shapeAttrs = $shapeRes['results'];

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
              //echo "<pre>";print_r($pdet);die;
                include 'template/bullionForm.html';
                break;

            case 'vendor_Form':
                $page = 'vendor-Form';
                $uid = $_GET['uid'];
                $url = APIDOMAIN . 'index.php?action=viewAll&uid=' . $uid;
                $res = $comm->executeCurl($url);
                $data = $res['results'][1];
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
                $catid = (!empty($_GET['catid']) ? $_GET['catid'] : '');
                include 'template/vendor_enquiries.html';
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
                $lastpg = ceil($total / 2);
                $adjacents = 2;
                //echo "<pre>";print_r($data);die;
                include 'template/vendorList.html';
                break;

            case 'about_us':
                $page = 'about_us';
                include 'template/about_us.html';
                break;

            case 'contact_us':
                $page = 'contact_us';
                include 'template/contact_us.html';
                break;

            case 'terms_conditions':
                $page = 'terms_conditions';
                include 'template/terms_conditions.html';
                break;

            case 'faq':
                $page = 'faq';
                include 'template/faq.html';
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
            default:
                $page = 'index';

                $url = APIDOMAIN . 'index.php?action=fetch_category_mapping&catid=10000';
                $res = $comm->executeCurl($url);
                $fil = $res['results']['attributes'];

                $url = APIDOMAIN . 'index.php?action=getCatList&page=1&limit=3';
                $res = $comm->executeCurl($url);
                $data = $res['results'];
                include 'template/index.html';
                break;
        }
        break;
}
?>
