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
                //echo $res;
                break;

            case 'auto':
                $str = $_GET['str'];
                $pagename = $_GET['pageName'];
                $url = APIDOMAIN . 'index.php?action=suggestAreaCity&str=' . urlencode($str) . '&page=1&limit=5&pageName=' . $pagename;
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

                //if ($sortby) {
                  if($pid)  {
                    $url = APIDOMAIN . 'index.php?action=removeFromWishlist&uid=' . $uid . '&pid=' . $pid;
                    $res = $comm->executeCurl($url);
                }


                $url = APIDOMAIN . 'index.php?action=getPrdByCatid&catid=' . $catid . '&page=' . $pgno . '&sortby=' . $sortby . '&slist=' . urlencode($slist) . '&clist=' . urlencode($clist) . '&tlist=' . urlencode($tlist) . '&ilist=' . urlencode($ilist) . '&jlist=' . urlencode($jlist) . '&ctid=' . $ctid . '&uid=' . $uid . '&b2bsort=' . $b2bsort;
                $res = $comm->executeCurl($url);


                if (!empty($jlist))
                {
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
                if (!empty($pid)) {
                    $userUrl = APIDOMAIN . 'index.php?action=checkUser&mobile=' . $mobile . '&email=' . $email . '&pid=' . $pid;
                } else {
                    $userUrl = APIDOMAIN . 'index.php?action=checkUser&mobile=' . $mobile;
                }
                $resp = $comm->executeCurl($userUrl);

                if (!empty($resp) && !empty($resp['error']) && !empty($resp['results']) && empty($resp['error']['Code'])) {
                    if ($resp['results'] == 'User Not yet Registered') {
                        $regUserUrl = APIDOMAIN . 'index.php?action=userReg&mobile=' . $mobile . '&username=' . urlencode($name) . '&email=' . urlencode($email) . '&cityname=' . urlencode($city);
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
                $prdIds = (!empty($params['prdIds']) && !stristr($params['prdIds'], 'undefined') && !stristr($params['prdIds'], 'null')) ? trim(urldecode($params['prdIds'])) : '';
                if (!empty($prdIds)) {
                    //echo'<pre>';print_r($params);
                   $url = APIDOMAIN . "index.php?action=getPrdImgsByIds&prdIds=" . urlencode($prdIds);
                   $res = $comm->executeCurl($url);
                } else {
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
                $meta_title = 'Sign Up';
                //include 'template/signup.html';

                /* if(stristr(DOMAIN, 'demo.iftosi.com') || stristr(DOMAIN, 'localhost') || stristr(DOMAIN, 'live.iftosi.com') || stristr(DOMAIN, 'beta.xelpmoc.in/iftosi') || stristr(REQURI, 'beta'))
                  { */
                include 'template/signup.html';
                /* }
                  else
                  {
                  include 'template/comingsoon.html';
                  } */
                break;

            case 'vsignup':
                $page = 'signup';
                $meta_title = 'Jeweller Sign Up';
                include 'template/vSignUp.html';
                break;
            case 'forgot':
                $page = 'forgot';
                $meta_title = 'Forgot Password';
                include 'template/forgotPsw.html';
                break;
            case 'diamond_shapes':
                $page = 'diamond_shapes';
                $meta_title = 'Diamond Shapes';
                include 'template/diamond.html';
                break;

            case 'jewellery_tips':
                $page = 'jewellery_tips';
                $meta_title = 'Jewellery Tips';
                include 'template/jewellery_tips.html';
                break;
            case 'education_round':
                $page = 'education_round';
                $meta_title = 'Education Round';
                include 'template/education_round.html';
                break;
            case 'education_pear':
                $page = 'education_pear';
                $meta_title = 'Education Pear';
                include 'template/education_pear.html';
                break;
            case 'education_oval':
                $page = 'education_oval';
                $meta_title = 'Education Oval';
                include 'template/education_oval.html';
                break;
            case 'education_emerald':
                $page = 'education_emerald';
                $meta_title = 'Education Emerald';
                include 'template/education_emerald.html';
                break;
            case 'education_clarity':
                $page = 'education_clarity';
                $meta_title = 'Education Clarity';
                include 'template/education_clarity.html';
                break;
            case 'education_asscher':
                $page = 'education_asscher';
                $meta_title = 'Education Asscher';
                include 'template/education_asscher.html';
                break;
            case 'education_heart':
                $page = 'education_heart';
                $meta_title = 'Education Heart';
                include 'template/education_heart.html';
                break;
            case 'education_marq':
                $page = 'education_marq';
                include 'template/education_marq.html';
                break;
            case 'education_radiant':
                $page = 'education_radiant';
                $meta_title = 'Education Radiant';
                include 'template/education_radiant.html';
                break;
            case 'e_certification':
                $page = 'e_certification';
                $meta_title = 'Education Certification';
                include 'template/e_certification.html';
                break;
            case 'e_certification1':
                $page = 'e_certification1';
                $meta_title = 'Education Certification';
                include 'template/e_certification1.html';
                break;
            case 'e_certification2':
                $page = 'e_certification2';
                include 'template/e_certification2.html';
                break;
            case 'e_carat_weight':
                $page = 'e_carat_weight';
                $meta_title = 'Education Carat Weight';
                include 'template/e_carat_weight.html';
                break;
            case 'education_cushion':
                $page = 'education_cushion';
                $meta_title = 'Education Cushion';
                include 'template/education_cushion.html';
                break;
            case 'education_princess':
                $page = 'education_princess';
                $meta_title = 'Education Princess';
                include 'template/education_princess.html';
                break;
            case 'e_color':
                $page = 'e_color';
                $meta_title = 'Education Colour';
                include 'template/e_color.html';
                break;
            case 'e_color1':
                $page = 'e_color1';
                include 'template/e_color1.html';
                break;
            case 'e_cut':
                $page = 'e_cut';
                $meta_title = 'Education Cut';
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
              case 'diamondguide':
                $page = 'diamondguide';
                $meta_title = 'Diamond Guide';
                include 'template/diamondguide.html';
                break;

            case 'e_glossary':
                $page = 'e_glossary';
                $meta_title = 'Education Glossary';
                include 'template/e_glossary.html';
                break;
            case 'e_shapes':
                $page = 'e_shapes';
                $meta_title = 'Education Shapes';
                include 'template/e_shapes.html';
                break;
            case 'privacy':
                $page = 'privacy';
                $meta_title = 'Privacy, IFtoSI, best marketplace | diamond, jewellery & bullion';
                $meta_description="Policy discloses IFtoSI’s information practices including the type of information being collected, method, use and sharing of such information.";
                $meta_keywords ="privacy policy, information practices, method of information collection, information sharing, IFtoSI.";
                include 'template/privacy.html';
                break;

            case 'vendor_privacy':
                $page = 'IFtoSI - Jeweller Privacy';
                $meta_title = 'Jeweller Privacy';
                include 'template/vprivacy.html';
                break;
            case 'customerDtls':
                $page = 'customerDtls';
                include 'template/customerDtls.html';
                break;
            case 'inactive_vendor':
                $page = 'inactive_vendor';
                $uid = $_GET['uid'];
                $url = APIDOMAIN . 'index.php?action=viewAll&uid=' . $uid;
                $res = $comm->executeCurl($url);
                $data = $res['results'][1];
                //echo "<pre>";print_r($data);die;
                include 'template/inactiveVendor.html';
                break;

            case 'expiredSub_vendor':
                $page = 'expiredSub_vendor';
                $uid = $_GET['uid'];
                $url = APIDOMAIN . 'index.php?action=viewAll&uid=' . $uid;
                $res = $comm->executeCurl($url);
                $data = $res['results'][1];
                //echo "<pre>";print_r($data);die;
                include 'template/expiredSub_vendor.html';
                break;

            case 'faq_sellers':
                $page = 'faq_sellers';
                $meta_title = 'Jeweller FAQs';
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
                $urlkey = explode(' ', $urlkey);
                $urlkey = $urlkey[1];
                include 'template/changepwd.html';
                break;
            case 'login':
                $page = 'login';
                $meta_title = 'Login';
                include 'template/login.html';
                break;

            case 'policy':
                $page = 'policy';
                $meta_title = 'Policy, IFtoSI, best marketplace | diamond, jewellery & bullion';
                $meta_description="Policy discloses IFtoSI’s information practices including the type of information being collected, method, use and sharing of such information.";
                $meta_keywords ="privacy policy, information practices, method of information collection, information sharing, IFtoSI.";
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
                $meta_title = 'Customer Wishlist';
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
                if ($totalCnt > 0) {
                    include 'template/resultsb2b.html';
                } else {
                    include 'template/comingsoon.html';
                }
                //include 'template/resultsb2b.html';
                break;

            case 'diamonds':
                $page = 'diamonds';
                $meta_title = 'Buy diamond solitaires at best prices from trusted dealers';
                $meta_description="Buy 100% certified diamond jewellery online from your favorite jeweller. Compare product prices among best of merchants, book online and collect from store.";
                $meta_keywords ="buy diamond jewellery online, online diamond jewellery, diamond jewellery online, diamond jewellery designs, diamond jewellery price, diamond jewellery india, online diamond jewellery shopping, online jewellery shopping store, gold jewellery, online jewellery india.";

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
                if ($totalCnt > 0)
                {
                    include 'template/results.html';
                }
                else
                {
                    if(empty($slist))
                    {
                        include 'template/comingsoon.html';
                    }
                    else
                    {
                        include 'template/results.html';
                    }

                }
                break;

            case 'jewellery':
                $page = 'jewellery';
                $meta_title = 'Fine jewellery online from trusted brands at low prices';
                $meta_description="Buy jewellery conveniently from most famous jewellery retailers in India. Comprehensive range of selections in rings, earrings, pendants, necklace and bangles.";
                $meta_keywords ="jewellery online India, diamond jewellery, gold jewellery, fine jewellery, solitaire rings, online jewellery, gold jewellery online, online diamond buying, jewellery online shop, best way to buy jewellery, solitaire pendant, diamond necklace, gold bangles.";

                $pgno = (!empty($_GET['pgno']) ? $_GET['pgno'] : 1);
                $catid = $_GET['catid'];

                if($catid==10003)
                {
                    $meta_title = 'Buy gold and diamond rings online at best prices in India';
                    $meta_description="IFtoSI brings to you an unmatched collection of diamond, gold & silver rings. The collection includes some of the best designs and combinations available with most renowned merchants in India.";
                    $meta_keywords ="gold rings, diamond rings, silver rings, office wear, daily wear, party wear, gemstone, cocktail, white gold, only diamonds, best gold ring design, popular diamond ring design, platinum design, polki design, rings for man, best diamond ring online.";
                }
                else if($catid==10004)
                {
                    $meta_title = 'Best gold and diamond earrings online at best prices in India';
                    $meta_description="IFtoSI brings together the best collection of diamond and gold earrings available in India. Choose from the widest range from retailers in different cities, compare prices and book online.";
                    $meta_keywords ="designer earring, daily wear earring, office purpose earring, earrings design, earrings for party wear, earrings from trusted sellers, branded gold earrings, diamond earrings from trusted dealers, best earring design in India, gold earrings at best prices.";
                }
                else if($catid==10005)
                {
                    $meta_title='Buy gold and diamond pendants online at best prices from famous sellers in India';
                    $meta_description="Order gold and diamond pendants online from the most trusted retialers in India. Best prices and quality guaranteed as you get to compare products from different sellers in India.";
                    $meta_keywords ="pendants for women, online order pendants, buy pendants online, diamond pendants, gold pendants, best pendants in India, party wear pendants, daily wear pendant designs, office wear pendants, formal pendant designs, simple pendant designs, pendants with gorgeous design, fashion pendants, pendants with discounts, pendant set online.";
                }
                else if($catid==10006)
                {
                    $meta_title='Best necklace designs in gold and diamond, buy online in India';
                    $meta_description="Choose gold and diamond necklace sets online from the most trusted dealers in India. Exquite designs that give gorgeous looks. Satisfaction guaranteed as you get to compare necklaces from multiple sellers in India.";
                    $meta_keywords ="latest necklace design, gold necklace from trusted sellers, diamond necklace with international certification, fashion necklaces, best necklace design in India, order necklace sets online, best collection of necklace online, trusted necklace seller, casual wear necklace, party wear necklace.";
                }
                else if($catid==10007)
                {
                    $meta_title='Unique collection of gold and diamond bangles & bracelets online in India';
                    $meta_description="Unmatched collection Bangles and Bracelets online at best prices in India. Diamond and gold bangles, bracelets with fancy and classy design to complement your personality.";
                    $meta_keywords ="bangles online shopping, online buy bangles, bangles for women, bangles online, bangles design, gold bangles, bangle bracelets, bangles, designer bangles, fancy bangles online, bangles set, ladies bangles, bangles jewellery, traditional bangles, gold bracelet for men, best bracelet design.";
                }
                else if($catid==100040)
                {
                    $meta_title='Buy nosepin online from top sellers in India. Quality and low prices guaranteed.';
                    $meta_description="Buy nosepins from India's best seller. Book online from a wide selection that comes with latest designs. Talk to the seller directly and pick up from his store.";
                    $meta_keywords ="gold & diamond nosepins, precious nosepins, nosepins online, designer nose pins online, diamond nosepins, gold nose pins, designer nose pins, buy nose pins online, best nose pin design, silver nose pins online india, silver nose pins, nose pins silver, silver nose pins online india.";
                }
                else if($catid==100041)
                {
                    $meta_title='A fantastic collection of adorable diamond and gold mangalsutras';
                    $meta_description="All types of mangalsutras are available with IFtoSI. Top mangalsutra sellers in India showcase their products in the platform. Choose the one you like, contact the dealer and collect after paying the agreed amount.";
                    $meta_keywords ="mangalsutra chain online india, diamond mangalsutras, mangalsutra in diamond, gold mangalsutra in India, best mangalsutra design in india, best place to buy mangalsutra in india, order mangalsutra online, see the best collection of mangalsutra, mangalsutra, mangalsutra online, online mangalsutra, buy mangalsutra online, artificial mangalsutra, mangalsutra designs, traditional mangalsutra, gold mangalsutra designs.";
                }
                else if($catid==100042)
                {
                    $meta_title='Buy polki jewellery online at best prices from famous sellers in India';
                    $meta_description="Buy polki jewellery in India directly from dealers. Polki from your favorite sellers are listed in iftosi.com Place order online and pay after see and confirm the product.";
                    $meta_keywords ="polki jewellery online india, polki sets online, traditional jewellery online, polki earring, polki set, polki pendant, doamond polki design, polki design in gold, best silver polki design, jadau jewellery in india, polki nosepin, jadau mangalsutra, jadau sets, kundan sets, traditional polki jewellery online purchase.";
                }
                else
                {
                    $meta_title = 'Fine jewellery online from trusted brands at low prices';
                    $meta_description="Buy jewellery conveniently from most famous jewellery retailers in India. Comprehensive range of selections in rings, earrings, pendants, necklace and bangles.";
                    $meta_keywords ="jewellery online India, diamond jewellery, gold jewellery, fine jewellery, solitaire rings, online jewellery, gold jewellery online, online diamond buying, jewellery online shop, best way to buy jewellery, solitaire pendant, diamond necklace, gold bangles.";
                }
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
                /* if(stristr(DOMAIN, 'demo.iftosi.com') || stristr(DOMAIN, 'localhost') || stristr(DOMAIN, 'live.iftosi.com') || stristr(REQURI, 'beta'))
                  { */
                include 'template/jewellery_results.html';
                /* }
                  else
                  {
                  include 'template/comingsoon.html';
                  } */
                break;
                
            case 'swarovski':
                $page = 'jewellery';
                $pgno = (!empty($_GET['pgno']) ? $_GET['pgno'] : 1);
                $catid = '10001';
                $meta_title = 'Fine jewellery online from trusted brands at low prices';
                $meta_description="Buy jewellery conveniently from most famous jewellery retailers in India. Comprehensive range of selections in rings, earrings, pendants, necklace and bangles.";
                $meta_keywords ="jewellery online India, diamond jewellery, gold jewellery, fine jewellery, solitaire rings, online jewellery, gold jewellery online, online diamond buying, jewellery online shop, best way to buy jewellery, solitaire pendant, diamond necklace, gold bangles.";
                $url = APIDOMAIN . 'index.php?action=getPrdByCatid&catid=' . $catid . '&clist=combination_44|~|combination_GOLD_00_SWAROVSKI_ZIRCONIA|@|combination_SILVER_00_SWAROVSKI_ZIRCONIA&page=' . $pgno;
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
                include 'template/jewellery_results.html';
                break; 
                
            case 'bullion':
                $page = 'bullion';
                $meta_title = 'Buy bullion online in India from the most respected sellers in the country';
                $meta_description="IFtoSI brings together the bullion dealers and famous jewellery retailers in India. Book your bullion online from your favorite merchant and collect from store after you satisfied about the purity and design.";
                $meta_keywords ="buy bullion online india, gold bullion online, gold bullion online india, buy gold coin online, book gold biscuit online, gold bar, bullion bar, bullion trading online, trusted bullion seller, best pricing for bullion, book gold bullion online, how to buy bullion online.";

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
                // if ($totalCnt > 0)
                // {
                    include 'template/bullion_results.html';
                /*}
                else
                {
                    include 'template/comingsoon.html';
                }*/
                break;


            case 'b2bdetails':
                $page = 'diamond_details';
                $prdList = $pid = $_GET['productid'];
                $uid = $_GET['userid'];
                $url = APIDOMAIN . 'index.php?action=getPrdById&prdid=' . $pid;
                $res = $comm->executeCurl($url);
                $prdVars = $data = $prdInfo = $res['results'][$pid];
                $meta_title = '';
                if(!empty($prdDet['attr_details']))
                {
                      if(!empty($prdDet['attr_details']['certified']))
                      {
                          $meta_title .= $prdDet['attr_details']['certified'].' Certified ';
                      }
                      if(!empty($prdDet['attr_details']['shape']))
                      {
                          $meta_title .= $prdDet['attr_details']['shape'].' Shaped ';
                      }

                      if(!empty($meta_title))
                      {
                         $meta_title = $meta_title.' Diamond With ';
                      }

                      if(!empty($prdDet['attr_details']['cut']))
                      {
                          $meta_title .= $prdDet['attr_details']['cut'].' Cut, ';
                      }
                      if(!empty($prdDet['attr_details']['carat']))
                      {
                          $meta_title .= intval($prdDet['attr_details']['carat']).' Carat, ';
                      }
                      if(!empty($prdDet['attr_details']['clarity']))
                      {
                          $meta_title .= $prdDet['attr_details']['clarity'].' Clarity ';
                      }
                      if(!empty($prdDet['attr_details']['color']))
                      {
                          $meta_title .= 'and '.$prdDet['attr_details']['color'].' Colour';
                      }
                      // if(!empty($prdDet['attr_details']['polish']))
                      // {
                      //     $meta_title .= 'Having '.$prdDet['attr_details']['polish'].' Polish ';
                      // }
                      // if(!empty($prdDet['attr_details']['polish']))
                      // {
                      //     $meta_title .= 'With '.$prdDet['attr_details']['symmetry'].' Symmetry ';
                      // }

                }

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

                $url1 = APIDOMAIN . 'index.php?action=imagedisplay&pid=' . $pid . '&vid=' . $vndrDtls['vid'];
                $res1 = $comm->executeCurl($url1);
                $data1 = $res1['results'];
                $datacnt = $res1['count'];

                $prdVars = $prdVars['attr_details'];
                $sug = APIDOMAIN . "index.php?action=suggestProducts&pid=" . $pid . "&catid=10000&clarity=" . urlencode($prdVars['clarity']) . "&carat=" . $prdVars['carat'] . "&shape=" . urlencode($prdVars['shape']) . "&cut=" . urlencode($prdVars['cut']) . "&color=" . urlencode($prdVars['color']) . "&fluo=" . urlencode($prdVars['fluorescence']) . "&sym = " . urlencode($prdVars['symmetry']) . "&polish=" . urlencode($prdVars['polish']);
                $res3 = $comm->executeCurl($sug);
                $data3 = $res3['results'];
                $sugTotal = 0;

                $ValArr = array('EX' => 'Excellent', 'VG' => 'Very Good', 'GD' => "Good", 'FAIR' => 'Fair', 'NN' => 'None', 'MED' => 'Medium', 'FNT' => 'Faint', 'STG' => 'Strong', 'VSTG' => 'Very Strong');
                foreach ($ValArr as $ky => $value) {
                    if ($data['attr_details']['cut'] == $ky) {
                        $prdVars['cut'] = $ValArr[$ky];
                    }
                }

                $desurl = APIDOMAIN . "index.php?action=showDescription&pid=" . $pid . "&catid=10000&color=" . urlencode($prdVars['color']) . "&cut=" . urlencode($prdVars['cut']) . "&clarity=" . urlencode($prdVars['clarity']) . "&shape=" . urlencode($prdVars['shape']);
                $desres = $comm->executeCurl($desurl);
                $des = $desres['results'];
                $totalDes = $res3['total'];

                $url4 = APIDOMAIN . "index.php?action=getPrdMoreInfo&prdid=".$pid."";
                $res4 = $comm->executeCurl($url4);
                $data4 = $res4['results'];


                //echo "<pre>";print_r($des); die;
                include 'template/diamond_detailsb2b.html';
                break;



            case 'diamond_details':
                $page = 'diamond_details';
                $prdList = $pid = $_GET['productid'];
                $uid = $_GET['userid'];
                $url = APIDOMAIN . 'index.php?action=getPrdById&prdid=' . $pid;
                $res = $comm->executeCurl($url);
                $prdVars = $data = $prdInfo = $prdDet = $res['results'][$pid];
                $meta_title = '';
                if(!empty($prdDet['attr_details']))
                {
                      if(!empty($prdDet['attr_details']['certified']))
                      {
                          $meta_title .= $prdDet['attr_details']['certified'].' Certified ';
                      }
                      if(!empty($prdDet['attr_details']['shape']))
                      {
                          $meta_title .= $prdDet['attr_details']['shape'].' Shaped ';
                      }

                      if(!empty($meta_title))
                      {
                         $meta_title = $meta_title.' Diamond With ';
                      }

                      if(!empty($prdDet['attr_details']['cut']))
                      {
                          $meta_title .= $prdDet['attr_details']['cut'].' Cut, ';
                      }
                      if(!empty($prdDet['attr_details']['carat']))
                      {
                          $meta_title .= intval($prdDet['attr_details']['carat']).' Carat, ';
                      }
                      if(!empty($prdDet['attr_details']['clarity']))
                      {
                          $meta_title .= $prdDet['attr_details']['clarity'].' Clarity ';
                      }
                      if(!empty($prdDet['attr_details']['color']))
                      {
                          $meta_title .= 'and '.$prdDet['attr_details']['color'].' Colour';
                      }
                      // if(!empty($prdDet['attr_details']['polish']))
                      // {
                      //     $meta_title .= 'Having '.$prdDet['attr_details']['polish'].' Polish ';
                      // }
                      // if(!empty($prdDet['attr_details']['polish']))
                      // {
                      //     $meta_title .= 'With '.$prdDet['attr_details']['symmetry'].' Symmetry ';
                      // }

                }
                $vndrInfo = $prdInfo['vendor_details'];
                foreach ($vndrInfo as $key => $value)
                {
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

                $url1 = APIDOMAIN . 'index.php?action=imagedisplay&pid=' . $pid . '&vid=' . $vndrDtls['vid'];
                $res1 = $comm->executeCurl($url1);
                $data1 = $res1['results'];
                $datacnt = $res1['count'];

                $prdVars = $prdVars['attr_details'];
                $sug = APIDOMAIN . "index.php?action=suggestProducts&pid=" . $pid . "&catid=10000&clarity=" . urlencode($prdVars['clarity']) . "&carat=" . $prdVars['carat'] . "&shape=" . urlencode($prdVars['shape']) . "&cut=" . urlencode($prdVars['cut']) . "&color=" . urlencode($prdVars['color']) . "&fluo=" . urlencode($prdVars['fluorescence']) . "&sym = " . urlencode($prdVars['symmetry']) . "&polish=" . urlencode($prdVars['polish']);
                $res3 = $comm->executeCurl($sug);
                $data3 = $res3['results'];
                $sugTotal = $res3['total'];

                $ValArr = array('EX' => 'Excellent', 'VG' => 'Very Good', 'GD' => "Good", 'FAIR' => 'Fair', 'NN' => 'None', 'MED' => 'Medium', 'FNT' => 'Faint', 'STG' => 'Strong', 'VSTG' => 'Very Strong');
                foreach ($ValArr as $ky => $value) {
                    if ($data['attr_details']['cut'] == $ky) {
                        $prdVars['cut'] = $ValArr[$ky];
                    }
                }

                $desurl = APIDOMAIN . "index.php?action=showDescription&pid=" . $pid . "&catid=10000&color=" . urlencode($prdVars['color']) . "&cut=" . urlencode($prdVars['cut']) . "&clarity=" . urlencode($prdVars['clarity']) . "&shape=" . urlencode($prdVars['shape']);
                $desres = $comm->executeCurl($desurl);
                $des = $desres['results'];
                $totalDes = $res3['total'];
                //echo "<pre>";print_r($des); die;
                //include 'template/diamond_details.html';
                /* if(stristr(DOMAIN, 'demo.iftosi.com') || stristr(DOMAIN, 'localhost') || stristr(DOMAIN, 'live.iftosi.com') || stristr(REQURI, 'beta'))
                  { */
                include 'template/diamond_details.html';
                /* }
                  else
                  {
                  include 'template/comingsoon.html';
                  } */
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
                        $prdVars = $prdInfo = $prdDet = $prdInfo['results'][$prdId];
                        if(!empty($prdDet['attr_details']))
                        {
                              if(!empty($prdDet['attr_details']['gold_weight']))
                              {
                                  $meta_title .= intval($prdDet['attr_details']['gold_weight']).' Gram ';
                              }
                              if(!empty($prdDet['attr_details']['metal']))
                              {
                                  $meta_title .= $prdDet['attr_details']['metal'].' ';
                              }
                              if(!empty($prdDet['attr_details']['type']))
                              {
                                  $meta_title .= $prdDet['attr_details']['type'].' ';
                              }
                              if(!empty($prdDet['attr_details']['bullion_design']))
                              {
                                  $meta_title .= ' with '.$prdDet['attr_details']['bullion_design'].' Design';
                              }
                        }

                        $vndrInfo = $prdInfo['vendor_details'];
                        foreach ($vndrInfo as $key => $value)
                        {
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
                $sug = APIDOMAIN . "index.php?action=suggestProducts&pid=" . $pid . "&catid=10002&metal=" . urlencode($prdVars['metal']) . "&type=" . urlencode($prdVars['type']);
                $res3 = $comm->executeCurl($sug);
                $data3 = $res3['results'];
                $sugTotal = $res3['total'];

                $desurl = APIDOMAIN . "index.php?action=showDescription&pid=" . $pid . "&catid=10002&metal=" . urlencode($prdVars['metal']) . "&type=" . strtolower(urlencode($prdVars['type']));
                $desres = $comm->executeCurl($desurl);
                $des = $desres['results'];
                $totalDes = $res3['total'];

                //echo "<pre>".print_r($data3); die;
                //include 'template/bullion_details.html';
                /* if(stristr(DOMAIN, 'demo.iftosi.com') || stristr(DOMAIN, 'localhost') || stristr(DOMAIN, 'live.iftosi.com') || stristr(REQURI, 'beta'))
                  { */
                include 'template/bullion_details.html';
                /* }
                  else
                  {
                  include 'template/comingsoon.html';
                  } */
                break;

            case 'jewellery_details':
                $page = 'jewellery_details';
                $prdInfo = array();
                
                $url = APIDOMAIN . 'index.php?action=getswarovskiData';
                $res = $comm->executeCurl($url);
                $sav1 = $res['results'];
                $sav2 = $res['results1'];
               
                $prdName = (!empty($_GET['productname'])) ? $_GET['productname'] : '';
                $prdId = $orgPrdId = (!empty($_GET['productid'])) ? $_GET['productid'] : '';
                if (!empty($prdId))
                {
                    $prdId = explode(' ', $prdId);
                    $prdList = $prdId = $pid = $prdId[1];
                    $prdInfoUrl = APIDOMAIN . 'index.php?action=getPrdById&prdid=' . $prdId;
                    $prdInfo = $comm->executeCurl($prdInfoUrl);
                    if (!empty($prdInfo) && !empty($prdInfo['results']) && !empty($prdInfo['error']) && empty($prdInfo['error']['errCode']))
                    {   //echo "<pre>";print_r( $prdInfo);die;
                        $prdDet = $prdInfo = $prdInfo['results'][$prdId];

                        $vndrInfo = $prdInfo['vendor_product_details'];
                        foreach ($vndrInfo as $key => $value)
                        {
                            $vndrId = $key;
                        }
                        $vndrDtls = $prdInfo['vendor_details'][$vndrId];
                        $vndrDtls['fulladdress'] = explode(",", $vndrDtls['fulladdress']);

                        foreach ($vndrDtls['fulladdress'] as $key => $value)
                        {
                            $vndrDtls['fulladdress'][$key] = trim($value);
                        }
                        $vndrDtls['fulladdress'] = implode(', ', $vndrDtls['fulladdress']);
                        $vndrAddr = explode(',', $vndrDtls['fulladdress']);
                    }
                   //echo "<pre>";print_r( $prdInfo['vendor_details'][$vndrId][vid]);die;
                   //$vid='';
                    if(!empty($prdDet['attr_details']))
                    {
                          if(!empty($prdDet['attr_details']['gold_purity']))
                          {
                              $meta_title .= $prdDet['attr_details']['gold_purity'].' Karats ';
                          }
                          if(!empty($prdDet['attr_details']['metal']))
                          {
                              $meta_title .= $prdDet['attr_details']['metal'].' ';
                          }
                          if(!empty($prdDet['attr_details']['shape']))
                          {
                              $meta_title .= $prdDet['attr_details']['shape'].' ';
                          }
                          if(!empty($prdDet['attr_details']['diamond_shape']))
                          {
                              $diamonds =  implode(', ',array_unique(explode('|!|',$prdInfo['attr_details']['diamond_shape'])));
                              $meta_title .= 'With '.$diamonds.' Diamonds';
                          }
                          if(!empty($prdDet['attr_details']['certified']) && $prdDet['attr_details']['certified'] !== 'Other')
                          {
                              $meta_title .= ' Certified By '.$prdDet['attr_details']['certified'];
                          }
                    }

                }
                $url1 = APIDOMAIN . 'index.php?action=imagedisplay&pid='.$pid.'&vid='.urlencode($prdInfo['vendor_details'][$vndrId][vid]).'&status=2';
                $res1 = $comm->executeCurl($url1);
                $data1 = $res1['results'];

                $gemsUrl = APIDOMAIN . 'index.php?action=getGemstoneTypes';
                $gemsRes = $comm->executeCurl($gemsUrl);
                $gemsAttrs = $gemsRes['results'];

                foreach ($gemsAttrs as $key => $value) {
                    if (strtolower($prdInfo['attr_details']['gemstone_type']) == $value['name']) {
                        $prdInfo['attr_details']['gemstone_type'] = $value['display_name'];
                        break;
                    }
                }
                $diamondsShape = explode('|!|', $prdInfo['attr_details']['diamond_shape']);
                $diamondsShape = array_unique($diamondsShape);

                $prdInfo['attr_details']['diamond_shape'] = implode('|!|', $diamondsShape);

                $diamondsClarity = explode('|!|', $prdInfo['attr_details']['clarity']);
                $diamondsClarity = array_unique($diamondsClarity);


                $tempcheck1 = strpos($prdInfo['attr_details']['color'], '~');
                if ($tempcheck1 == true) {
                    $diamondsColor = explode('~', $prdInfo['attr_details']['color']);
                } else {
                    $diamondsColor = explode('|!|', $prdInfo['attr_details']['color']);
                }

                $diamondsColor = array_unique($diamondsColor);


                $metal = $prdInfo['attr_details']['metal'];
                $tempcheck2 = strpos($metal, '~');
                if ($tempcheck2 == true) {
                    $metal = explode('~', $metal);
                } else {
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

                $diamondsShape = implode(',', $diamondsShape);
                $diamondsClarity = implode(',', $diamondsClarity);
                $diamondsColor = implode(',', $diamondsColor);
                $metal = implode(',', $metal);
                $gemstoneType = implode(',', $gemstoneType);

                $desurl = APIDOMAIN . "index.php?action=showDescription&pid=" . $pid . "&catid=10001&gemstone_type=" . strtolower(urlencode($gemstoneType)) . "&metal=" . strtolower(urlencode($metal)) . "&clarity=" . urlencode($diamondsClarity) . "&colour=" . urlencode($diamondsColor) . "&cert=" . strtolower($prdVars['certified']) . "&shape=" . $diamondsShape . "";
                $desres = $comm->executeCurl($desurl);
                $des = $desres['results'];
                $totalDes = $res3['total'];

                $sug = APIDOMAIN . "index.php?action=suggestProducts&pid=" . $pid . "&catid=10001&metal=" . strtolower(urlencode($metal)) . "&purity=" . $prdVars['gold_purity'] . "&gwt=" . $prdVars['gold_weight'] . "&shape=" . urlencode($diamondsShape) . "&certified=" . strtolower(urlencode($prdVars['certified']));
                $res3 = $comm->executeCurl($sug);
                $data3 = $res3['results'];
                $sugTotal = $res3['total'];

                foreach ($data3 as $imagekey => $imgval) {
                    $urlI = APIDOMAIN . 'index.php?action=imagedisplay&pid=' . $imagekey;
                    $resI = $comm->executeCurl($urlI);
                    $dataI[] = $resI['results'];
                    $smPrdList[] .= $imagekey;
                }
                $smPrdList = implode(',', $smPrdList);

                //echo "<pre>"; print_r($des); die;
                //include 'template/jewellery_details.html';
                /* if(stristr(DOMAIN, 'demo.iftosi.com') || stristr(DOMAIN, 'localhost') || stristr(DOMAIN, 'live.iftosi.com') || stristr(REQURI, 'beta'))
                  { */
                include 'template/jewellery_details.html';
                /* }
                  else
                  {
                  include 'template/comingsoon.html';
                  } */
                
                
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

            /* case 'check_Vendor_Form':
                $page = 'vendor-Form';
                $uid = $_GET['uid'];
                $isAdmin=$_GET['isAdmin'];
                $url = APIDOMAIN . 'index.php?action=viewAll&uid=' . $uid.'&isAdmin='. $isAdmin;
                $res = $comm->executeCurl($url);
                $data = $res['results'][1];
                $banker = $data['banker'];
                //echo "<pre>";print_r($banker);die;
                include 'template/vendorDetails.html';
                break;
            */

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
                $url = APIDOMAIN . 'index.php?action=vendorlist&pgno=' . $pgno . '&limit=' . $limit;
                $res = $comm->executeCurl($url);
                $data = $res['results']['vendors'];
                $total = $res['results']['total_vendors'];
                $totalCnt = $total;
                $lastpg = ceil($total / 15);
                $adjacents = 2;
                //echo "<pre>";print_r($data);die;
                include 'template/vendorList.html';
                break;


            case 'vendorDetails':
                $page = 'vendorList';
                $vid = (!empty($_GET['vid']) ? $_GET['vid'] : 0);
                $url = APIDOMAIN . 'index.php?action=vendorDetails&vid=' . $vid;
                $res = $comm->executeCurl($url);
                $data1 = $res['results']['vendor'];
                //print_r($data1);die;
                //echo "<pre>";print_r($data1);die;
                include 'template/vendorList.html';
                break;

            case 'about_us':
                $page = 'about-us';
                $meta_title = 'The best platform to buy diamond, fine jewellery and bullion.';
                $meta_description="IFtoSI is an online marketplace for diamond, jewellery & bullion purchases. We bring together best of sellers to present you with wide selection at best prices.";
                $meta_keywords ="About us, online jewellery marketplace, online jewellery seller, about IFtoSI, what is IFtoSI.";

                include 'template/about_us.html';
                break;

             case 'ratechange':
                $page = 'ratechange';
                include 'template/ratechange.html';
                break;

            case 'contact_us':
                $page = 'contact-Us';
                $meta_title='Contact IFtoSI, marketplace for diamond, jewellery & bullion';
                $meta_description="Click the link above to contact IFtoSI - The best platform to buy diamond, fine jewellery and bullion. We bring to you best sellers with wide selection at best prices..";
                $meta_keywords ="Contact IFtoSI, Call IFtoSI, email IFtoSI.";

                include 'template/contactUs.html';
                break;

            case 'team':
                $page = 'team';
                $meta_title = 'The best platform to buy diamond, fine jewellery and bullion.';
                $meta_description="IFtoSI is an online marketplace for diamond, jewellery & bullion purchases. We bring together best of sellers to present you with wide selection at best prices.";
                $meta_keywords ="About us, online jewellery marketplace, online jewellery seller, about IFtoSI, what is IFtoSI";
                include 'template/team.html';
                break;

            case 'comingsoon':
                $page = 'comingsoon';
                include 'template/comingsoon.html';
                break;

            case 'terms_conditions':
                $page = 'terms-and-conditions';
                $meta_title = 'IFtoSI terms of serve that constitute how you use the services';
                $meta_description="Terms of Service constitute a contract between IFtoSI and you as a registered user on the Website. If you do not agree to these Terms you must not register yourself.";
                $meta_keywords ="terms of service, IFtoSI, terms & conditions.";

                include 'template/terms_conditions.html';
                break;

            case 'terms_of_listing':
                $page = 'terms_of_listing';
                $meta_title='IFtoSI terms of serve that constitute how dealers must use the services';
                $meta_description="IFtoSI enables sellers of diamonds, jewellery, and bullion to list and market their goods, and connect with prospective buyers thereby connecting Merchants and Customers.";
                $meta_keywords ="seller agreement, marketplace regulations, marketplace rules, IFtoSI agreement with sellers.";

                include 'template/terms_of_listing.html';
                break;

            case 'vterms_of_listing':
                $page = 'IFtoSI - Vendor Terms Of Listing';
                include 'template/vterms_of_listing.html';
                break;


            case 'faq':
                  $page = 'faq';
                  $meta_title = 'Answers to you questions on IFtoSI online jewellery purchase';
                 $meta_description="Here we have listed answers to all questions about jewellery online purchase from IFtoSI. Questions range from credibility of sellers to payment method to home delivery.";
                 $meta_keywords ="FAQ, FAQs, online jewellery purchase, IFtoSI, answers to jewellery procedures.";

                include 'template/faq.html';
                break;

            case 'faqs_seller':
                $page = 'IFtoSI - Seller FAQs';
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
                $url = APIDOMAIN . 'index.php?action=getProdList&page=' . $pgno;
                $res = $comm->executeCurl($url);
                $data = $res['results']['products'];
                $total = $res['results']['total_products'];
                $totalCnt = $total;
                $lastpg = ceil($total / 15);
                $adjacents = 2;
                include 'template/product_list.html';
                break;

            case 'thumbnail':
                $url = APIDOMAIN . 'index.php?action=getImgByProd&pid=' . $params['pid'];
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
                $meta_title="Diamond Jewellery & Bullion Online at India's Best Marketplace";
                $meta_description="Your favourite jeweller now has a new address, IFtoSI. We bring together best of India's jewellery & diamond merchants on one platform. Book Online & pay Offline.";
                $meta_keywords ="jewellery, fine jewellery, bullion, fashion jewellery, diamond jewellery, gold jewellery, indian jewellery, designer jewellery, online jewellery shopping, online jewellery shopping india, jewellery buying websites, best jewellery shop";
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
