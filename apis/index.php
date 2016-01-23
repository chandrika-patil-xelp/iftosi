<?php
include '../config.php';
$res['results'] = array();
$params = array_merge($_GET, $_POST);
$params = array_merge($params, $_FILES);
$action = $_GET['action'];

switch($action)
    {

//----------------------------Check Owner--------------------------------

//  localhost/iftosi/apis/index.php?action=getOwnerCheck&uid=3&pid=2

    case 'getOwnerCheck':
        $uid=(!empty($params['uid'])) ? trim($params['uid']) : '';
        $pid=(!empty($params['pid'])) ? trim($params['pid']) : '';
        if(empty($pid) || empty($uid))
        {
            $arr = array();
            $err = array('Code' => 1, 'Msg' => 'Some Parameters missing');
            $result = array('results'=>$arr, 'error' => $err);
            $res=$result;
            break;
        }
        include APICLUDE.'class.vendor.php';
        $obj	= new vendor($db['iftosi']);
        $result	= $obj->getOwnerCheck($params);
        $res = $result;
        break;
//----------------------------Change Pass--------------------------------

//  localhost/iftosi/apis/index.php?action=changePassUrl&mobile=7309290529&uid=3&email=shubham.bajpai@xelpmoc.in

    case 'changePassUrl':
        $mobile=(!empty($params['mobile'])) ? trim($params['mobile']) : '';
        $email=(!empty($params['email'])) ? trim(urldecode($params['email'])) : '';
        $uid=(!empty($params['uid'])) ? trim($params['uid']) : '';
        $url=(!empty($params['url'])) ? trim(urldecode($params['url'])) : '';
        if(empty($mobile) || empty($email) || empty($uid))
        {
            $arr = array();
            $err = array('Code' => 1, 'Msg' => 'Some Parameters missing');
            $result = array('results'=>$arr, 'error' => $err);
            $res=$result;
            break;
        }
        include APICLUDE.'class.urlmaster.php';
        $obj	= new urlmaster($db['iftosi']);
        $result	= $obj->changePassUrl($params);
        $res = $result;
        break;

//  localhost/iftosi/apis/index.php?action=getUserDet&key=uvwxyz
    case 'getUserDet':
        $url=(!empty($params['key'])) ? trim($params['key']) : '';
        if(empty($url))
        {
            $arr = array();
            $err = array('Code' => 1, 'Msg' => 'Some Parameters missing');
            $result = array('results'=>$arr, 'error' => $err);
            $res=$result;
            break;
        }
        include APICLUDE.'class.urlmaster.php';
        $obj	= new urlmaster($db['iftosi']);
        $result	= $obj->getUserDet($params);
        $res = $result;
        break;

//----------------------------User--------------------------------

//  localhost/iftosi/apis/index.php?action=checkUser&mobile=9987867578

    case 'vendorlist':
      include APICLUDE.'class.vendor.php';
      $obj	= new vendor($db['iftosi']);
      $result	= $obj->vendorList($params);
      $res = $result;
    break;

		case 'imageupdate':
			include APICLUDE.'class.product.php';
			$obj	= new product($db['iftosi']);
			$result	= $obj->imageUpdate($params);
			$res= $result;
		break;

		case 'imageremove':
			include APICLUDE.'class.product.php';
			$obj	= new product($db['iftosi']);
			$result	= $obj->imageRemove($params);
			$res= $result;
		break;

		case 'imagedisplay':
			include APICLUDE.'class.product.php';
			$obj	= new product($db['iftosi']);
			$result	= $obj->imageDisplay($params);
			$res= $result;
		break;

        case 'checkUser':
            include APICLUDE.'class.user.php';
            $mobile=(!empty($params['mobile'])) ? trim($params['mobile']) : '';
            $email=(!empty($params['email'])) ? trim($params['email']) : '';
            if(empty($mobile))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Some Parameters missing');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new user($db['iftosi']);
            $result= $obj->checkUser($params);
            $res= $result;
            break;

   case 'sendRateMail':
        include APICLUDE.'class.user.php';
        $vid  = (!empty($params['vid'])) ? trim($params['vid']) : '';
        if(empty($vid))
        {
            $resp = array();
            $error = array('errCode' => 1, 'errMsg' => 'Invalid parameters');
            $result = array('results' => $resp, 'error' => $error);
            break;
        }
        $obj= new user($db['iftosi']);
        $result = $obj->sendRateMail($params);
        $res = $result;
        break;

        case 'sendDeactMailSms':
        include APICLUDE.'class.user.php';
        $username  = (!empty($params['username'])) ? trim($params['username']) : '';
        $mobile  = (!empty($params['mobile'])) ? trim($params['mobile']) : '';
        $email  = (!empty($params['email'])) ? trim(urlencode($params['email'])) : '';
        $isVendor  = (!empty($params['isVendor'])) ? trim($params['isVendor']) : '';
        if(empty($mobile) || empty($email) || empty($username) || empty($isVendor))
        {
            $resp = array();
            $error = array('errCode' => 1, 'errMsg' => 'Invalid parameters');
            $result = array('results' => $resp, 'error' => $error);
            break;
        }
        $obj= new user($db['iftosi']);
        $result = $obj->sendRateMail($params);
        $res = $result;
        break;

   case 'sendWelcomeMailSMS':
        include APICLUDE.'class.user.php';
        $username  = (!empty($params['username'])) ? trim($params['username']) : '';
        $email  = (!empty($params['email'])) ? trim($params['email']) : '';
        $mobile  = (!empty($params['mobile'])) ? trim($params['mobile']) : '';
        $isV  = (!empty($params['isVendor'])) ? trim($params['isVendor']) : '';
        if(empty($username) && empty($email) && empty($mobile) && empty($isV))
        {
            $resp = array();
            $error = array('errCode' => 1, 'errMsg' => 'Invalid parameters');
            $result = array('results' => $resp, 'error' => $error);
            break;
        }
        $obj= new user($db['iftosi']);
        $result = $obj->sendWelcomeMailSMS($params);
        $res = $result;
        break;

   case 'sendEnqMailSMS':
        include APICLUDE.'class.user.php';
        $username  = (!empty($params['username'])) ? trim($params['username']) : '';
        $email  = (!empty($params['email'])) ? trim($params['email']) : '';
        $mobile  = (!empty($params['mobile'])) ? trim($params['mobile']) : '';
        $useremail  = (!empty($params['useremail'])) ? trim($params['useremail']) : '';

        if(empty($username) && empty($email) && empty($mobile) && empty($useremail))
        {
            $resp = array();
            $error = array('errCode' => 1, 'errMsg' => 'Invalid parameters');
            $result = array('results' => $resp, 'error' => $error);
            break;
        }
        $obj= new user($db['iftosi']);
        $result = $obj->sendEnqMailSMS($params);
        $res = $result;
        break;

    case 'sendOTP':
        include APICLUDE.'class.user.php';
        $mb = (!empty($params['mb'])) ? trim($params['mb']) : '';
        if(empty($mb))
        {
            $resp = array();
            $error = array('errCode' => 1, 'errMsg' => 'Invalid parameters');
            $result = array('results' => $resp, 'error' => $error);
            break;
        }
        $obj= new user($db['iftosi']);
        $result = $obj->sendOTP($params);
        $res = $result;
        break;


    case 'validOTP':
        include APICLUDE.'class.user.php';
        $mobile = (!empty($params['mobile'])) ? trim($params['mobile']) : '';
        $vc = (!empty($params['vc'])) ? trim($params['vc']) : '';
        $user_name= (!empty($params['name'])) ? trim($params['name']) : '';
        if(empty($mobile) && empty($vc))
        {
            $resp = array();
            $error = array('errCode' => 1, 'errMsg' => 'Invalid parameters');
            $result = array('results' => $resp, 'error' => $error);
            break;
        }
        $obj= new user($db['iftosi']);
        $result = $obj->validOTP($params);
        $res = $result;
        break;

// localhost/iftosi/apis/index.php?action=userReg&username=Shushrut Kumar&password=mishra1.234&mobile=7309290529&email=shubham.bajpai@xelpmoc.in&isvendor=1
        case 'userReg':
            include APICLUDE.'class.user.php';
            $obj= new user($db['iftosi']);
            $username=(!empty($params['username'])) ? trim(urldecode($params['username'])) : '';
            $password=(!empty($params['password'])) ? trim(urldecode($params['password'])) : '';
            $mobile=(!empty($params['mobile'])) ? trim($params['mobile']) : '';
            $email=(!empty($params['email'])) ?  trim(urldecode($params['email'])) : '';
            $isvendor = $params['isvendor'] =(!empty($params['isvendor'])) ? trim($params['isvendor']) : 0;
            if(empty($password))
            {
				$tmp_params = array('length' => 6, 'mobile' => $mobile);
				$password = $obj->generatePassword($tmp_params);	// Password Length. Numeric.
			}
            if(empty($username)  ||  empty($password)  ||  empty($mobile)  ||  empty($email))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Some Parameters missing');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }
            $result= $obj->userReg($params);
            $res= $result;
            break;

//  localhost/iftosi/apis/index.php?action=udtProfile&dt={%22result%22:{%22logmobile%22:7309290529,%22username%22:%22jummanji%22,%22gen%22:1,%22alt_email%22:%22singharun@gmail.com%22,%22dob%22:%221990-10-08%22,%22workphone%22:%229696969696%22,%22pincode%22:223232,%22area%22:%22janakpuri%20west%22,%22cityname%22:%22DELHI%22,%22state%22:%22Delhi%22,%22country%22:%22india%22,%22address1%22:%22sfwfe%20ewf%20wef%20wfe%22,%22address2%22:%22sfwfe%20ewf%20wef%20wfe%22,%22mobile%22:34235235,%22landline%22:%223242425225%22,%22idtype%22:%22323222%22,%22idproof%22:%22323222%22,%22lat%22:10.224113,%22lng%22:23.74756363245}}
        case 'udtProfile':
            include APICLUDE.'class.user.php';
            $dt=(!empty($params['dt'])) ?  trim(urldecode($params['dt'])) : '';
            if(empty($dt))
            {
                //echo "here";
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Some parameters missing');
                $result = array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new user($db['iftosi']);
            $result= $obj->udtProfile($params);
            $res= $result;
            break;

//  localhost/iftosi/apis/index.php?action=logUser&mobile=7309290529&password=mishra1.234
        case 'logUser':
            include  APICLUDE.'class.user.php';
            $mobile=(!empty($params['mobile'])) ?  trim($params['mobile']) : '';
            $password=(!empty($params['password'])) ?  trim(urldecode($params['password'])) : '';
            if(empty($mobile)  &&  empty($password))
            {
                $arr=array();
                $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
                $result=array('results'=> $arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj = new user($db['iftosi']);
            $result = $obj->logUser($params);
            $res= $result;
            break;

// localhost/iftosi/apis/index.php?action=updatePass&mobile=9975887206&password=bajpai123
        case 'updatePass':
            include APICLUDE.'class.user.php';
            $password=(!empty($params['passowrd'])) ?  trim(urldecode($params['password'])) : '';
            $mobile=(!empty($params['mobile'])) ?  trim($params['mobile']) : '';
            if(empty($password)  &&  empty($mobile))
            {
                $arr=array();
                $err=array('code'=>1,'Msg'=>'Some Parameters missing');
                $result=array('results'=> $arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new user($db['iftosi']);
            $result =$obj->updatePass($params);
            $res= $result;
            break;

// localhost/iftosi/apis/index.php?action=forgotPwd&email=9655338337
        case 'forgotPwd':
            include_once APICLUDE . 'class.user.php';
            $email = (!empty($params['email'])) ? trim($params['email']) : '';

            if (empty($email)) {
                $resp = array();
                $error = array('Code' => 1, 'Msg' => 'Invalid parameters');
                $res = array('results' => $resp, 'error' => $error);
                break;
            }
            $obj=new user($db['iftosi']);
            $tmp_params = array('email' => $email);
            $res = $obj->forgotPwd($tmp_params);
            break;

// localhost/iftosi/apis/index.php?action=forgotPwd&email=9655338337
        case 'statusChecker':
            include_once APICLUDE . 'class.user.php';
            $uid = (!empty($params['uid'])) ? trim($params['uid']) : '';

            if (empty($uid)) {
                $resp = array();
                $error = array('Code' => 1, 'Msg' => 'Invalid parameters');
                $res = array('results' => $resp, 'error' => $error);
                break;
            }
            $obj=new user($db['iftosi']);
            $tmp_params = array('uid' => $uid);
            $res = $obj->statusChecker($tmp_params);
            break;

// localhost/iftosi/apis/index.php?action=changepwd&cpass=123456&npass=654321&rpass=654321
        case 'changepwd':
            include_once APICLUDE . 'class.user.php';
            $uid = (!empty($params['uid'])) ? trim($params['uid']) : '';
            $cpass = (!empty($params['cpass'])) ? trim($params['cpass']) : '';
            $npass = (!empty($params['npass'])) ? trim($params['npass']) : '';
            $rpass = (!empty($params['rpass'])) ? trim($params['rpass']) : '';

            if (empty($npass) || empty($rpass)) {
                $resp = array();
                $error = array('Code' => 1, 'Msg' => 'Invalid parameters');
                $res = array('results' => $resp, 'error' => $error);
                break;
            }
            if ($rpass !== $npass) {
                $resp = array();
                $error = array('Code' => 1, 'Msg' => 'New Passwords not Matching');
                $res = array('results' => $resp, 'error' => $error);
                break;
            }
            $obj=new user($db['iftosi']);
            $res = $obj->changePwd($params);
            break;

// localhost/iftosi/apis/index.php?action=deactUser&mobile=9975887206
        case 'deactUser':
            include APICLUDE.'class.user.php';
            $userid=(!empty($params['userid'])) ?  trim($params['userid']) : '';
            if(empty($userid))
            {
            $arr=array();
            $err=array('code'=>1,'Msg'=>'Invalid Parameters');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj=new user($db['iftosi']);
            $result =  $obj->deactUser($params);
            $res= $result;
            break;

// localhost/iftosi/apis/index.php?action=actUser&mobile=9975887206
        case 'actUser':
            include APICLUDE.'class.user.php';
            $userid=(!empty($params['userid'])) ?  trim($params['userid']) : '';
            if(empty($userid))
            {
            $arr=array();
            $err=array('code'=>1,'Msg'=>'Invalid Parameter');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj=new user($db['iftosi']);
            $result =  $obj->actUser($params);
            $res= $result;
            break;

// localhost/iftosi/apis/index.php?action=activateVendor&user_id=3
        case 'activateVendor':
            include APICLUDE.'class.user.php';
            $userid=(!empty($params['user_id'])) ?  trim($params['user_id']) : '';
            if(empty($userid))
            {
                $arr=array();
                $err=array('code'=>1,'Msg'=>'Invalid Parameter');
                $result=array('results'=> $arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new user($db['iftosi']);
            $result =  $obj->activateVendor($params);
            $res= $result;
            break;

//  localhost/iftosi/apis/index.php?action=viewAll&uid=6
        case 'viewAll':
            include APICLUDE.'class.user.php';
            $uid=(!empty($params['uid']))?trim($params['uid']) : '';
            if(empty($uid))
            {
                $arr=array();
                $err=array('code'=>1,'Msg'=>'Some Parameters missing');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new user($db['iftosi']);
            $result=$obj->viewAll($params);
            $res=$result;
            break;

//      localhost/iftosi/apis/index.php?action=profileComplete&uid=3
        case 'profileComplete':
            include APICLUDE.'class.user.php';
            $uid=(!empty($params['uid']))?trim($params['uid']) : '';
            if(empty($uid))
            {
                $arr=array();
                $err=array('code'=>1,'Msg'=>'Some Parameters missing');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new user($db['iftosi']);
            $result=$obj->profileComplete($params);
            $res=$result;
            break;

//-----------------------------Product Enquiry----------------------------


            # IP address is optionall
//  localhost/iftosi/apis/index.php?action=filLog&uid=7&pid=7&vid=6&ipaddress=192.168.1.1&dflag=1
        case 'filLog':
            include APICLUDE.'class.enquiry.php';
            $uid=(!empty($params['uid'])) ? trim($params['uid']) : '';
            $pid=(!empty($params['pid'])) ? trim($params['pid']) : '';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            if(empty($vid) && empty($pid)  &&  empty($uid))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new enquiry($db['iftosi']);
            $result= $obj->filLog($params);
            $res = $result;
            break;

//  localhost/iftosi/apis/index.php?action=viewLog&vid=7878787878&page=&limit=
        case 'viewLog':
            include APICLUDE.'class.enquiry.php';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            if(empty($vid))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new enquiry($db['iftosi']);
            $result= $obj->viewLog($params);
            $res = $result;
            break;

//-----------------------------Vendor-----------------------------

//  localhost/iftosi/apis/index.php?action=addVendorPrdInfo&dt={%22result%22:%20{%22pid%22:%201,%22vid%22:%207,%22vp%22:%207309290529,%22vq%22:%201,%22vc%22:%20%22INR%22,%22vr%22:%204.21,%22af%22:%201}}
        case 'addVendorPrdInfo':
            include APICLUDE.'class.vendor.php';
            $dt=(!empty($params['dt'])) ? trim($params['dt']) : '';
            if(empty($dt))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new vendor($db['iftosi']);
            $result= $obj->addVendorPrdInfo($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=getVproducts&vid=9975887206&page=&limit=
        case 'getVproducts':
            include APICLUDE.'class.vendor.php';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            if(empty($vid))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj=new vendor($db['iftosi']);
            $result=$obj->getVProductsByCatid($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=getVproductsByName&vid=7&prname=blue&page=1&limit=1
        case 'getVproductsByName':
            include APICLUDE.'class.vendor.php';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : ''; //user session mobile
            $prname=(!empty($params['prname'])) ? trim(urldecode($params['prname'])) : '';
            if(empty($vid) && empty($prname))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj=new vendor($db['iftosi']);
            $result=$obj->getVproductsByName($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=updateProductInfo&vid=1&logmobile=9975887206&pid=7&vp=93323823&vq=10&af=1
        case 'updateProductInfo':
            include APICLUDE.'class.vendor.php';
            $vendor_id=(!empty($params['vid'])) ? trim($params['vid']) : '';
            $product_id=(!empty($params['pid'])) ? trim($params['pid']) : '';
            $vendor_price=(!empty($params['vp'])) ? trim($params['vp']) : '';
            $vendor_quantity=(!empty($params['vq'])) ? trim($params['vq']) : '';
            $active_flag=(!empty($params['af'])) ? trim($params['af']) : '';
            if(empty($vendor_id) && empty($product_id) && empty($vendor_price) && empty($vendor_quantity) && empty($active_flag))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new vendor($db['iftosi']);
            $result= $obj->updateProductInfo($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=getVDetailByPid&pid=1&page=1&limit=1
        case 'getVDetailByPid':
            include APICLUDE.'class.vendor.php';
            $product_id=(!empty($params['pid'])) ? trim($params['pid']) : '';
            if(empty($product_id))
            {
                $arr=array();
                $err=array('Code'=>0,'Msg'=>'Invalid Parameter');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new vendor($db['iftosi']);
            $result=$obj->getVDetailByPid($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=getVDetailByVidPid&pid=1&vid=7&page=1&limit=1
        case 'getVDetailByVidPid':
            include APICLUDE.'class.vendor.php';
            $product_id=(!empty($params['pid'])) ? trim($params['pid']) : '';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            if(empty($product_id) /*&& empty($vid)*/)
            {
                $arr=array();
                $err=array('Code'=>0,'Msg'=>'Invalid Parameter');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new vendor($db['iftosi']);
            $result=$obj->getVDetailByVidPid($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=vDeletePrd&prdid=1&vid=2
        case 'vDeletePrd':
            include APICLUDE.'class.vendor.php';
            $prdid=(!empty($params['prdid'])) ? trim($params['prdid']) : '';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            if(empty($prdid) && empty($vid))
            {
                $arr=array();
                $err=array('code'=>1,'Invalid Parameter');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new vendor($db['iftosi']);
            $result= $obj->deletePrd($params);
            $res= $result;
            break;

//  localhost/iftosi/apis/index.php?action=getVProductsByBcode&bcode=q&page=1&limit=15&vid=1&catid=10000
        case 'getVProductsByBcode':
            include APICLUDE.'class.vendor.php';
            $barcode=(!empty($params['bcode'])) ? trim(urldecode($params['bcode'])):'';
            if(empty($barcode))
            {
                $arr=array();
                $err=array('Code'=>1,'Invalid Parameter');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
           }
           $obj = new vendor($db['iftosi']);
           $result= $obj->getVProductsByBcode($params);
           $res=$result;
           break;

//  localhost/iftosi/apis/index.php?action=togglePrdstatus&prdid=1&vid=2
        case 'togglePrdstatus':
            include APICLUDE.'class.vendor.php';
            $prdid=(!empty($params['prdid'])) ? trim($params['prdid']) : '';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            if(empty($prdid) && empty($vid))
            {
                $arr=array();
                $err=array('code'=>1,'Invalid Parameter');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new vendor($db['iftosi']);
            $result= $obj->toggleactive($params);
            $res= $result;
            break;

//  localhost/iftosi/apis/index.php?action=bulkInsertProducts&vid=2
        case 'bulkInsertProducts':
            include APICLUDE.'class.vendor.php';
            require APICLUDE . 'PHPExcelReader/excel_reader2.php';
            require APICLUDE . 'PHPExcelReader/SpreadsheetReader.php';
            $file=(!empty($_FILES['up_file'])) ? $_FILES['up_file'] : '';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
//            $upload = move_uploaded_file($_FILES['up_file']['tmp_name'], $filename);
//            print_r($upload);
//            if($upload) {
//                echo 'sd';
//            $params['data'] = file_get_contents($_FILES['up_file']['tmp_name']);
//            }
//
            $alloweExt=array('xlsx','xls','csv');
            $up_file =$file['name'];
            $fileExt=end((explode('.',$up_file)));
            $arr=array();
            if(empty($file) && empty($vid))
            {
                $err=array('Code'=>1,'Msg' => 'Invalid Parameter');
                $result=array('result'=>$arr,'error'=>$err);
            }
            else if($_FILES['up_file']['size']>3145728) {
                $err=array('Code'=>1,'Msg' => 'Max File Size 3MB');
                $result=array('result'=>$arr,'error'=>$err);

            } else if (!in_array($fileExt, $alloweExt)) {
                $err=array('Code'=>1,'Msg' => 'File Type not Valid');
                $result=array('result'=>$arr,'error'=>$err);
            } else {
                if($fileExt=='csv') {
                    $params['data'] = file_get_contents($_FILES['up_file']['tmp_name']);
                } else {
                    $path = WEBROOT.'upload/';
                    $filename = $path.$vid.'-'.date('d-m-Y').'.'.$fileExt;
                    $upload = move_uploaded_file($_FILES['up_file']['tmp_name'], $filename);

                    if ($upload) {
                        $Reader = new SpreadsheetReader($filename);
                        $Sheets = $Reader->Sheets();

                        $Reader->ChangeSheet(0);
                        foreach ($Reader as $Key => $Row) {
                            $params['data'][] = $Row;
                        }
                    } else {
                        $err = array('Code' => 1, 'Msg' => 'File Upload Failed');
                        $result = array('result' => $arr, 'error' => $err);
                    }
                }
                $params['type']=$fileExt;
                $obj = new vendor($db['iftosi']);
                $result = $obj->bulkInsertProducts($params);
            }
            $res= $result;
            break;

// localhost/iftosi/apis/index.php?action=updateDollerRate&vid=2&dolRate=50.50
        case 'updateDollerRate':
            include APICLUDE.'class.vendor.php';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            $dolRate=(!empty($params['dolRate'])) ? trim($params['dolRate']) : '';
            if(!empty($vid) && !empty($dolRate)) {
                $obj = new vendor($db['iftosi']);
                $result = $obj->updateDollerRate($params);
            } else {
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => array(), 'error' => $err);
            }
            $res= $result;
        break;

// localhost/iftosi/apis/index.php?action=getDollerRate&vid=2
        case 'getDollerRate':
            include APICLUDE.'class.vendor.php';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            if(!empty($vid)) {
                $obj = new vendor($db['iftosi']);
                $result = $obj->getDollerRate($params);
            } else {
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => array(), 'error' => $err);
            }
            $res= $result;
        break;

// localhost/iftosi/apis/index.php?action=updateSilverRate&vid=2&dolRate=50.50
        case 'updateSilverRate':
            include APICLUDE.'class.vendor.php';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            $silverRate=(!empty($params['silRate'])) ? trim($params['silRate']) : '';
            if(!empty($vid) && !empty($silverRate)) {
                $obj = new vendor($db['iftosi']);
                $result = $obj->updateSilverRate($params);
            } else {
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => array(), 'error' => $err);
            }
            $res= $result;
        break;

// localhost/iftosi/apis/index.php?action=getSilverRate&vid=2
        case 'getSilverRate':
            include APICLUDE.'class.vendor.php';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            if(!empty($vid)) {
                $obj = new vendor($db['iftosi']);
                $result = $obj->getSilverRate($params);
            } else {
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => array(), 'error' => $err);
            }
            $res= $result;
        break;

// localhost/iftosi/apis/index.php?action=getAllRatesByVID&vid=2
        case 'getAllRatesByVID':
            include APICLUDE.'class.vendor.php';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            if(!empty($vid)) {
                $obj = new vendor($db['iftosi']);
                $result = $obj->getAllRatesByVID($params);
            } else {
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => array(), 'error' => $err);
            }
            $res= $result;
        break;

// localhost/iftosi/apis/index.php?action=updateGoldRate&vid=2&dolRate=50.50
        case 'updateGoldRate':
            include APICLUDE.'class.vendor.php';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            $goldRate=(!empty($params['goldRate'])) ? trim($params['goldRate']) : '';
            if(!empty($vid) && !empty($goldRate)) {
                $obj = new vendor($db['iftosi']);
                $result = $obj->updateGoldRate($params);
            } else {
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => array(), 'error' => $err);
            }
            $res= $result;
        break;

// localhost/iftosi/apis/index.php?action=getGoldRate&vid=2
        case 'getGoldRate':
            include APICLUDE.'class.vendor.php';
            $vid=(!empty($params['vid'])) ? trim($params['vid']) : '';
            if(!empty($vid)) {
                $obj = new vendor($db['iftosi']);
                $result = $obj->getGoldRate($params);
            } else {
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => array(), 'error' => $err);
            }
            $res= $result;
        break;


//-------------------------Location---------------------------------

// localhost/iftosi/apis/index.php?action=addCity&cname=Pakistan&sname=Punjab&cityname=lahore
        case 'checkArea':
            include APICLUDE.'class.location.php';
            $city=(!empty($params['city'])) ? trim(urldecode($params['city'])) : '';
            $state=(!empty($params['state'])) ? trim(urldecode($params['state'])) : '';
            $area=(!empty($params['area'])) ? trim(urldecode($params['area'])) : '';
            $fulladd=(!empty($params['fulladd'])) ? trim(urldecode($params['fulladd'])) : '';
            if(empty($city) || empty($city) || empty($area) || empty($state))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new location($db['iftosi']);
            $result= $obj->checkArea($params);
            $res=$result;
            break;

// localhost/iftosi/apis/index.php?action=addCity&cname=Pakistan&sname=Punjab&cityname=lahore
        case 'addCity':
            include APICLUDE.'class.location.php';
            $cityname=(!empty($params['cityname'])) ? trim(urldecode($params['cityname'])) : '';
            $sname=(!empty($params['sname'])) ? trim(urldecode($params['sname'])) : '';
            $cname=(!empty($params['cname'])) ? trim(urldecode($params['cname'])) : '';
            if(empty($cname) && empty($cityname) && empty($sname))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new location($db['iftosi']);
            $result= $obj->addCity($params);
            $res=$result;
            break;

// localhost/iftosi/apis/index.php?action=viewbyCity&cityname=bangalore
        case 'viewbyCity':
            include APICLUDE.'class.location.php';
            $cityname=(!empty($params['cityname'])) ? trim(urldecode($params['cityname'])) : '';
            if(empty($cityname))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new location($db['iftosi']);
            $result= $obj->viewbyCity($params);
            $res= $result;
            break;

//  localhost/iftosi/apis/index.php?action=viewbyState&sname=punjab&cname=pakistan&page=1&limit=2
        case 'viewbyState':
            include APICLUDE.'class.location.php';
            $sname=(!empty($params['sname'])) ? trim(urldecode($params['sname'])) : '';
            $cname=(!empty($params['cname'])) ? trim(urldecode($params['cname'])) : '';
            if(empty($sname) && empty($cname))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results'=>$arr,'error' => $err);
                $res=$result;
                break;
            }
            $obj= new location($db['iftosi']);
            $result= $obj->viewbyState($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=viewbyCountry&cname=pakistan&page=1&limit=2
        case 'viewbyCountry':
            include APICLUDE.'class.location.php';
            $cname=(!empty($params['cname'])) ? trim(urldecode($params['cname'])) : '';
            if(empty($cname))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new location($db['iftosi']);
            $result= $obj->viewbyCountry($params);
            $res=$result;
            break;


//  localhost/iftosi/apis/index.php?action=viewbyPincode&code=380001
        case 'viewbyAreaPincode':
            include APICLUDE.'class.location.php';

            $area=(!empty($params['area'])) ? trim(urldecode($params['area'])) : '';
            if(empty($area))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new location($db['iftosi']);
            $result= $obj->viewbyAreaPincode($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=viewbyPincode&code=380001
        case 'viewbyPincode':
            include APICLUDE.'class.location.php';
            $cname=(!empty($params['code'])) ? trim(urldecode($params['code'])) : '';
            if(empty($cname))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new location($db['iftosi']);
            $result= $obj->viewbyPincode($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=updatecity&newcityname=Delhi&oldcityname=lahore&sname=delhi&cname=India
        case 'updatecity':
            include APICLUDE.'class.location.php';
            $newcityname=(!empty($params['newcityname'])) ? trim(urldecode($params['newcityname'])) : '';
            $oldcityname=(!empty($params['oldcityname'])) ? trim(urldecode($params['oldcityname'])) : '';
            $sname=(!empty($params['sname'])) ? trim(urldecode($params['sname'])) : '';
            $cname=(!empty($params['cname'])) ? trim(urldecode($params['cname'])) : '';
            if(empty($cname) &&  empty($newcityname)  &&  empty($sname)  &&  empty($oldcityname))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new location($db['iftosi']);
            $result= $obj->updateCity($params);
            $res=$result;
            break;

//-------------------------CategoryInfo-----------------------------

//   localhost/iftosi/apis/index.php?action=getCatList&page=1&limit=2
        case 'getCatList':
            include APICLUDE.'class.categoryInfo.php';
            $obj= new categoryInfo($db['iftosi']);
            $result= $obj->getCatList($params);
            $res=$result;
            break;

//   localhost/iftosi/apis/index.php?action=getSubCat&catid=10000
		 case 'getSubCat':
            include APICLUDE.'class.categoryInfo.php';
            $obj= new categoryInfo($db['iftosi']);
			$catid = $_GET['catid'];
            $result= $obj->getSubCat($catid);
            $res['results'] = $result;
            break;

// localhost/iftosi/apis/index.php?action=getCatName&catid=1
        case 'getCatName':
            include APICLUDE.'class.categoryInfo.php';
            $catid=(!empty($params['catid'])) ? trim($params['catid']) : '';
            if(empty($catid))
            {
                $arr=array();
                $err=array('code'=>1,'Invalid Parameter');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new categoryInfo($db['iftosi']);
            $result= $obj->getCatName($params);
            $res= $result;
            break;

// localhost/iftosi/apis/index.php?action=getCatId&catName=bullion
        case 'getCatId':
            include APICLUDE.'class.categoryInfo.php';
            $catName=(!empty($params['catName'])) ? trim(urldecode($params['catName'])) : '';
            if(empty($catName))
            {
                $arr=array();
                $err=array('code'=>1,'Invalid Parameter');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new categoryInfo($db['iftosi']);
            $result= $obj->getCatId($params);
            $res= $result;
            break;

// localhost/iftosi/apis/index.php?action=addCat&catName=Diamond
        case 'addCat':
            include APICLUDE.'class.categoryInfo.php';
            $catName=(!empty($params['catName'])) ? trim(urldecode($params['catName'])) : '';
            if(empty($catName))
            {
                $arr=array();
                $err=array('code'=>1,'Invalid Parameter');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new categoryInfo($db['iftosi']);
            $result=$obj->addCat($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=deleteCat&catid=1
        case 'deleteCat':
            include APICLUDE.'class.categoryInfo.php';
            $catid=(!empty($params['catid'])) ? trim($params['catid']) : '';
            if(empty($catid))
            {
                $arr=array();
                $err=array('code'=>1,'Invalid Parameter');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new categoryInfo($db['iftosi']);
            $result= $obj->deleteCat($params);
            $res= $result;
            break;

//  localhost/iftosi/apis/index.php?action=updateCat&catid=1&catName=diamond
        case 'updateCat':
            include APICLUDE.'class.categoryInfo.php';
            $catid=(!empty($params['catid'])) ? trim($params['catid']) : '';
            $catName=(!empty($params['catName'])) ? trim(urldecode($params['catName'])) : '';
            if(empty($catid)  &&  empty($catName))
            {
                $arr=array();
                $err=array('code'=>1,'Invalid Parameter');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new categoryInfo($db['iftosi']);
            $result= $obj->updateCat($params);
            $res=$result;
            break;

//----------------------BrandInfo------------------------------

// localhost/iftosi/apis/index.php?action=getBrandList&page=&limit=
        case 'getBrandList':
            include APICLUDE.'class.brandInfo.php';
            $obj= new brandInfo($db['iftosi']);
            $result= $obj->getBrandList($params);
            $res=$result;
            break;

//-------------------------Auto---------------------------------

//  localhost/iftosi/apis/index.php?action=searchbox&srch=b&page=1&limit=1
        case 'searchbox':
            include APICLUDE.'class.auto.php';
            $srch=(!empty($params['srch'])) ? trim(urldecode($params['srch'])) : '';
            if(empty($srch))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj = new auto($db['iftosi']);
            $result=$obj->searchbox($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=suggestCity&str=de&page=1&limit=3
        case 'suggestCity':
            include APICLUDE.'class.auto.php';
            $srch=(!empty($params['str'])) ? trim(urldecode($params['str'])) : '';
            if(empty($srch))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj = new auto($db['iftosi']);
            $result=$obj->suggestCity($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=suggestAreaCity&str=de&page=1&limit=3
        case 'suggestAreaCity':
            include APICLUDE.'class.auto.php';
            $srch=(!empty($params['str'])) ? trim(urldecode($params['str'])) : '';
            if(empty($srch))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj = new auto($db['iftosi']);
            $result=$obj->suggestAreaCity($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=suggestBrand&str=p&page=1&limit=1
        case 'suggestBrand':
            include APICLUDE.'class.auto.php';
            $srch=(!empty($params['str'])) ? trim(urldecode($params['str'])) : '';
            if(empty($srch))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj = new auto($db['iftosi']);
            $result=$obj->suggestBrand($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=suggestCat&str=2&page=1&limit=2
        case 'suggestCat':
            include APICLUDE.'class.auto.php';
            $srch=(!empty($params['str'])) ? trim(urldecode($params['str'])) : '';
            if(empty($srch))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj = new auto($db['iftosi']);
            $result=$obj->suggestCat($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=suggestVendor&str=p&page=1&limit=1
        case 'suggestVendor':
            include APICLUDE.'class.auto.php';
            $srch=(!empty($params['str'])) ? trim(urldecode($params['str'])) : '';
            if(empty($srch))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj = new auto($db['iftosi']);
            $result=$obj->suggestVendor($params);
            $res=$result;
            break;

//--------------------Product-----------------------------------

//  localhost/iftosi/apis/index.php?action=addNewproduct&dt={%22result%22:%20{%22product_name%22:%20%22bluediamond%22,%22brandid%22:%2011,%22lotref%22:%201123,%22barcode%22:%20%22qw211111%22,%22lotno%22:%201133,%22product_display_name%22:%20%22marveric%20blue%20silver%20diamond%22,%22product_model%22:%20%22rw231%22,%22product_brand%22:%20%22orra%22,%22product_price%22:%2012211223.02,%22product_currency%22:%20%22INR%22,%22product_keywords%22:%20%22blue,silver,diamond%22,%22product_desc%22:%20%22a%20clear%20cut%20solitaire%20diamond%20in%20the%20vault%22,%22product_wt%22:%20223.21,%22prd_img%22:%20%22abc.jpeg%22,%22category_id%22:%201,%22product_warranty%22:%20%221%20year%22},%22attributes%22:%20[[111,3,%22green%22,1]],%22design%22:{%22desname%22:%22jackdeniel%22},%22error%22:%20{%22errCode%22:%200}}
        case 'addNewproduct':
            include APICLUDE.'class.product.php';
            $dt=(!empty($params['dt'])) ? trim(urldecode($params['dt'])) : '';
            if(empty($dt))
            {

            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj=new product($db['iftosi']);
            $result=$obj->addNewproduct($params);
            $res=$result;
            break;

        case 'Vpactive':
            include APICLUDE.'class.vendor.php';
            $vid=(!empty($params['vid'])) ? trim(urldecode($params['vid'])) : '';
            $af=(!empty($params['af'])) ? trim(urldecode($params['af'])) : '';

            if(empty($vid) && empty($af))
            {
                $arr=array();
                $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
                $result=array('results'=> $arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new vendor($db['iftosi']);
            $result=$obj->Vpactive($params);
            $res=$result;
            break;

        /*case 'imageUpdate':
            include APICLUDE.'class.product.php';
            $dt=(!empty($params['dt'])) ? trim(urldecode($params['dt'])) : '';
            if(empty($dt))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err['error']);
            $res=$result;
            break;
            }
            $obj=new product($db['iftosi']);
            $result=$obj->imageUpdate($params);
            $res=$result;
            break; */

//  localhost/iftosi/apis/index.php?action=getPrdByName&prname=blue&page=1&limit=1
        case 'getPrdByName':
            include APICLUDE.'class.product.php';
            $prname=(!empty($params['prname'])) ? trim(urldecode($params['prname'])) : '';
            if(empty($prname))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj=new product($db['iftosi']);
            $result=$obj->getPrdByName($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=getPrdByCatid&catid=1&page=1&limit=1
        case 'getPrdByCatid':
            include APICLUDE.'class.product.php';
            $catid	=(!empty($params['catid'])) ? trim($params['catid']) : '';
            $uid	=(!empty($params['uid'])) ? trim($params['uid']) : '';
            if(empty($catid) && empty($uid))
            {
				$arr=array();
				$err=array('code'=> 1,'Msg'=> 'Invalid parameter');
				$result=array('results'=> $arr,'error'=>$err);
				$res=$result;
				break;
            }
            $obj=new product($db['iftosi']);
            $result=$obj->getPrdByCatid($params);
            $res=$result;
			//print_r($res);die;
            break;

//  localhost/iftosi/apis/index.php?action=getPrdById&prdid=2&catid=3&page=1&limit=1
        case 'getPrdById':
            include APICLUDE.'class.product.php';
            $prdid=(!empty($params['prdid'])) ? trim($params['prdid']):'';
            $catid=(!empty($params['prdid'])) ? trim($params['catid']):'';
            if(empty($prdid) && empty($catid))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj=new product($db['iftosi']);
            $result=$obj->getPrdById($params);
            $res= $result;
            break;

//  localhost/iftosi/apis/index.php?action=getList&page=1&limit=1
        case 'getList':
            include APICLUDE.'class.product.php';
            $obj=new product($db['iftosi']);
            $result=$obj->getList($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=productByCity&cityname=DELHI&page=1&limit=1
        case 'productByCity':
            include APICLUDE.'class.product.php';
            $cityname=(!empty($params['cityname'])) ? trim($params['cityname']): "";
            if(empty($cityname))
            {
                $arr=array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new product($db['iftosi']);
            $result=$obj->productByCity($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=productByBrand&bname=orra&page=1&limit=1
        case 'productByBrand':
            include APICLUDE.'class.product.php';
            $bname=(!empty($params['bname'])) ? trim($params['bname']): "";
            if(empty($bname))
            {
                $arr=array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new product($db['iftosi']);
            $result=$obj->productByBrand($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=productByDesigner&desname=shiamak&page=1&limit=1
        case 'productByDesigner':
            include APICLUDE.'class.product.php';
            $desname=(!empty($params['desname'])) ? trim($params['desname']): "";
            if(empty($desname))
            {
                $arr=array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new product($db['iftosi']);
            $result=$obj->productByDesigner($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=suggestProducts
        case 'suggestProducts':
            include APICLUDE.'class.product.php';
            $obj= new product($db['iftosi']);
            $result=$obj->suggestProducts($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=showDescription
        case 'showDescription':
            include APICLUDE.'class.product.php';
            $obj= new product($db['iftosi']);
            $result=$obj->showDescription($params);
            $res=$result;
            break;

//------------------------------Suggestions str and table name---------------

     /*   case 'getsuggestions':
            include APICLUDE.'class.product.php';
            $tblname=(!empty($params['tname']))? trim($params['tname']):'';
            $str=(!empty($params['str']))? trim($params['str']):'';
            if(empty($tblname) && empty($tblname))
            {
                $arr = array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new product($db['iftosi']);
            $result=$obj->getsuggestions($params);
            $res=$result;
            break;          */

//----------------------Attribute-----------------------------------------------

 // localhost/iftosi/apis/index.php?action=get_attrList&page=1&limit=1
        case 'get_attrList':
            include APICLUDE.'class.attribute.php';
            $obj = new attribute($db['iftosi']);
            $result=$obj->get_attrList($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=set_attributes_details&name=flurocent&dname=luminous&unit=10&flag=1&upos=2&vals={10,20,30,40,50,60,70}&range=10
        case 'set_attributes_details':
            include APICLUDE.'class.attribute.php';
            $name=(!empty($params['name'])) ? trim($params['name']):'';
            $dname=(!empty($params['dname'])) ? trim($params['dname']):'';
            $unit=(!empty($params['unit'])) ? trim($params['unit']):'';
            $flag=(!empty($params['flag'])) ? trim($params['flag']):'';
            $upos=(!empty($params['upos'])) ? trim(urldecode($params['upos'])):'';
            $vals=(!empty($params['vals'])) ? trim($params['vals']) : '';

            if(empty($name)  &&  empty($dname)  &&  empty($unit)  &&  empty($flag)  &&  empty($upos)  &&  empty($vals))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj = new attribute($db['iftosi']);
            $result=$obj->set_attributes_details($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=fetch_attributes_details&attribid=1
        case 'fetch_attributes_details':
            include APICLUDE.'class.attribute.php';
            $attrid=(!empty($params['attribid'])) ? trim($params['attribid']):'';
            if(empty($attrid))
            {
                $arr=array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new attribute($db['iftosi']);
            $result=$obj->fetch_attributes_details($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=set_category_mapping&aid=43&dflag=1&dpos=999&fil_flag=1&fil_pos=999&aflag=1&catid=3
        case 'set_category_mapping':
            include APICLUDE.'class.attribute.php';
            $aid =(!empty($params['aid'])) ? trim($params['aid']):'';
            $dflag =(!empty($params['dflag'])) ? trim($params['dflag']):'';
            $dpos =(!empty($params['dpos'])) ? trim($params['dpos']):'';
            $fil_flag =(!empty($params['fil_flag'])) ? trim($params['fil_flag']):'';
            $fil_pos =(!empty($params['fil_pos'])) ? trim($params['fil_pos']):'';
            $aflag =(!empty($params['aflag'])) ? trim($params['aflag']):'';
            $catid =(!empty($params['catid'])) ? trim($params['catid']):'';
            if(empty($aid)  &&  empty($dflag)  &&  empty($dpos)  &&  empty($fil_flag)  &&  empty($fil_pos)  &&  empty($aflag)  &&  empty($catid))
            {
                $arr=array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new attribute($db['iftosi']);
            $result=$obj->set_category_mapping($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=fetch_category_mapping&catid=3
        case 'fetch_category_mapping':
            include APICLUDE.'class.attribute.php';
            $catid =(!empty($params['catid'])) ? trim($params['catid']):'';
            if(empty($catid))
            {
                $arr=array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new attribute($db['iftosi']);
            $result=$obj->fetch_category_mapping($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=unset_category_mapping&catid=3&aid=12
        case 'unset_category_mapping':
            include APICLUDE.'class.attribute.php';
            $id   =(!empty($params['aid'])) ? trim($params['aid']): "";
            $catid=(!empty($params['catid'])) ? trim($params['catid']): "";
            if(empty($id) &&  empty($catid))
            {
                $arr=array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('result'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new attribute($db['iftosi']);
            $result=$obj->unset_category_mapping($params);
            $res=$result;
            break;
//------------------------------Filter--------------------------------------

//  localhost/iftosi/apis/index.php?action=get_filters&category_id=3&page=1&limit=1
     /*   case 'get_filters':
            include APICLUDE.'class.filter.php';
            $category_id=(!empty($params['category_id'])) ? trim($params['category_id']):'';
            if(empty($category_id))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameter');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new filter($db['iftosi']);
            $result=$obj->get_filters($params);
            $res=$result;
            break;
*/

/*  HAve to be changed as per the requirement        */
            /*
        case 'refine':
            include APICLUDE.'class.filter.php';
echo '<pre>';
print_r($params);
echo '</pre>';
            $page=(!empty($params['page'])) ? trim(urldecode($params['page'])) : '';
            $limit=(!empty($params['limit'])) ? trim(urldecode($params['limit'])) : '';
            $dt=(!empty($params['dt'])) ? trim($params['dt']):'';
            if(empty($dt) && empty($limit) && empty($page))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameter');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }

            $obj= new filter($db['iftosi']);
            $result=$obj->refine($params);
            $res=$result;
            break;
            */

//------------------------Wishlist-------------------------------------------

//  localhost/iftosi/apis/index.php?action=addtowsh&page=1&limit=1&dt={"result": {"uid": 0,"pid": 0,"vid": 9975887206,"wf": 12}}
        case 'catCountWish':
			include APICLUDE.'class.wishlist.php';
			$uid	= $_GET['uid'];
			$obj	= new wishlist($db['iftosi']);
            $result	= $obj->catCountWish($uid);
			$res=$result;
		break;
        case 'addtowsh':
            include APICLUDE.'class.wishlist.php';
            $dt=(!empty($params['dt']))? trim($params['dt']):'';
            if(empty($dt))
            {
                $arr = array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new wishlist($db['iftosi']);
            $result=$obj->addtowsh($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=viewsh&page=1&limit=1&uid=7
        case 'viewsh':
            include APICLUDE.'class.wishlist.php';
            $uid=(!empty($params['uid']))? trim($params['uid']):'';
            if(empty($uid))
            {
                $arr = array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new wishlist($db['iftosi']);
            $result=$obj->viewsh($params);
            $res=$result;
            break;

        case 'checklist':
            include APICLUDE.'class.wishlist.php';
            $uid=(!empty($params['uid']))? trim($params['uid']):'';
            $vid=(!empty($params['vid']))? trim($params['vid']):'';
            $pid=(!empty($params['prdid']))? trim($params['prdid']):'';
            if(empty($uid) && empty($vid) && empty($pid))
            {
                $arr = array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new wishlist($db['iftosi']);
            $result=$obj->checklist($params);
            $res=$result;
            break;

		case 'checkWish':
			include APICLUDE.'class.wishlist.php';
            $uid=(!empty($params['uid']))? trim($params['uid']):'';
            $vid=(!empty($params['vid']))? trim($params['vid']):'';
            $pid=(!empty($params['prdid']))? trim($params['prdid']):'';
            if(empty($uid) && empty($vid) && empty($pid))
            {
                $arr = array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj = new wishlist($db['iftosi']);
            $result = $obj->checkWish($params);
            $res=$result;
            break;
		break;
//  localhost/iftosi/apis/index.php?action=removeFromWishlist&uid=4&pid=100003
        case 'removeFromWishlist':
            include APICLUDE.'class.wishlist.php';
            $uid=(!empty($params['uid']))? trim($params['uid']):'';
            $vid=(!empty($params['vid']))? trim($params['vid']):'';
            $pid=(!empty($params['pid']))? trim($params['pid']):'';
            if(empty($uid) || empty($pid))
            {
                $arr = array();
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new wishlist($db['iftosi']);
            $result=$obj->removeFromWishlist($params);
            $res=$result;
            break;

//-------------------------Helpdesk--------------------------------------

//  localhost/iftosi/apis/index.php?action=askhelp&dt={%22result%22:%20{%22uid%22:%206,%22cname%22:%20%22Insane%20Rider%22,%22cemail%22:%20%22rider.insane@motorbikes.com%22,%22logmobile%22:%207309290529,%22cquery%22:%20%22qdqd%20wedwdw%20wcec%20wwwedd%20wdewd%22},%22error%22:%20{%22errCode%22:%200}}
        case 'askhelp':
            include APICLUDE.'class.helpdesk.php';
            $dt=(!empty($params['dt'])) ? trim(urldecode($params['dt'])) : '';
            if(empty($dt))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Some Parameters are missing');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new helpdesk($db['iftosi']);
            $result=$obj->askhelp($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=viewhelp&page=1&limit=1
        case 'viewhelp':
            include APICLUDE.'class.helpdesk.php';
            $obj= new helpdesk($db['iftosi']);
            $result=$obj->viewhelp($params);
            $res=$result;
            break;
//----------------------Customer Speaks-------------------------------------

//  localhost/jzeva/apis/index.php?action=addCDes&dt=
        case 'addCom':
            include APICLUDE.'class.speaks.php';
            $dt=(!empty($params['dt'])) ? trim(urldecode($params['dt'])) : '';
            if(empty($dt))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Some Parameters are missing');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new speaks($db['iftosi']);
            $result=$obj->addCom($params);
            $res=$result;
            break;

//  localhost/jzeva/apis/index.php?action=viewCom
        case 'viewCom':
            include APICLUDE.'class.speak.php';
            $obj= new speak($db['iftosi']);
            $result=$obj->viewCom();
            $res=$result;
            break;
//-------------------------Product Details------------------------------------

        case 'productInsert':
            include APICLUDE.'class.prdInsert.php';
            $catid=(!empty($params['category_id'])) ? trim(urldecode($params['category_id'])) : '';
            if(empty($catid))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Some Parameters are missing');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }

            $obj= new prdInsert($db['iftosi']);

            $result=$obj->productInsert($params);
            $res=$result;
            break;

        case 'categoryHeir':
            include APICLUDE.'class.prdInsert.php';

            $catid=(!empty($params['catid'])) ? trim(urldecode($params['catid'])) : '';
            if(empty($catid))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Some Parameters are missing');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }

            $obj= new prdInsert($db['iftosi']);

            $result=$obj->categoryHeir($params);
            $res=$result;
            break;

        case 'product_category_mapping':
            include APICLUDE.'class.prdInsert.php';
            $obj= new prdInsert($db['iftosi']);
            $result=$obj->product_category_mapping();
            $res=$result;
            break;

        case 'productInsert':
            include APICLUDE.'class.prdInsert.php';

            $catid=(!empty($params['category_id'])) ? trim(urldecode($params['category_id'])) : '';
            if(empty($catid))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Some Parameters are missing');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }

            $obj= new prdInsert($db['iftosi']);

            $result=$obj->productInsert($params);
            $res=$result;
            break;

        case 'categoryHeir':
            include APICLUDE.'class.prdInsert.php';

            $catid=(!empty($params['catid'])) ? trim(urldecode($params['catid'])) : '';
            if(empty($catid))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Some Parameters are missing');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }

            $obj= new prdInsert($db['iftosi']);

            $result=$obj->categoryHeir($params);
            $res=$result;
            break;

        case 'product_category_mapping':
            include APICLUDE.'class.prdInsert.php';
            $obj= new prdInsert($db['iftosi']);
            $result=$obj->product_category_mapping();
            $res=$result;
            break;

		case 'sendDetailsToUser':
			include_once APICLUDE . 'class.product.php';
			$obj = new product($db['iftosi']);
			$res = $obj->sendDetailsToUser($params);
		break;

//--------------------vendor landing page----------------------------------------
        //  localhost/iftosi/apis/index.php?action=getVPrdByCatid&catid=1&page=1&limit=1
        case 'getVPrdByCatid':
            include APICLUDE.'class.vendor.php';
            $catid=(!empty($params['catid'])) ? trim($params['catid']) : '';
            if(empty($catid))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameter');
            $result=array('results'=> $arr,'error'=>$err);
            $res=$result;
            break;
            }
            $obj=new vendor($db['iftosi']);
            $result=$obj->getVPrdByCatid($params);
            $res=$result;
			//print_r($res);die;
            break;
//---------------------------------------------------------------------------


//  localhost/iftosi/apis/index.php?action=citySuggest&name=bangalo
        case 'citySuggest':
            include APICLUDE.'class.location.php';
            $obj=new location($db['iftosi']);
            $result=$obj->suggestCity($params);
            $res=$result;
            break;

        //  localhost/iftosi/apis/index.php?action=citySuggest&name=bangalo
        case 'cityName':
            include APICLUDE.'class.location.php';
            $obj=new location($db['iftosi']);
            $result=$obj->cityName($params);
            $res=$result;
            break;


//  localhost/iftosi/apis/index.php?action=stateSuggest&name=tami
        case 'stateSuggest':
            include APICLUDE.'class.location.php';
            $obj=new location($db['iftosi']);
            $result=$obj->suggestState($params);
            $res=$result;
            break;

//  localhost/iftosi/apis/index.php?action=areaSuggest&name=tami
        case 'areaSuggest':
            include APICLUDE.'class.location.php';
            $obj=new location($db['iftosi']);
            $result=$obj->suggestArea($params);
            $res=$result;
            break;



//  localhost/iftosi/apis/index.php?action=getProdList
        case 'getProdList':
            include APICLUDE.'class.admin.php';
            $obj=new admin($db['iftosi']);
            $res=$obj->getProdList($params);
            break;

//  localhost/iftosi/apis/index.php?action=getImgByProd&pid=12000
        case 'getImgByProd':
            include APICLUDE.'class.admin.php';
            $obj=new admin($db['iftosi']);
            $res=$obj->getImgByProd($params);
            break;

//  localhost/iftosi/apis/index.php?action=updateImageData&id=1&rea=dsdsd&seq=2
        case 'updateImageData':
            include APICLUDE.'class.admin.php';
            $obj=new admin($db['iftosi']);
            $res=$obj->updateImageData($params);
            break;

		case 'getLatLngByArea':
			include APICLUDE . 'class.location.php';
			$obj = new location($db['iftosi']);
			$res = $obj->getLatLngByArea($params);
		break;

		case 'uploadCertificate':
			include APICLUDE . 'class.product.php';
			$obj = new product($db['iftosi']);
			$res = $obj->uploadCertificate($params);
		break;

		case 'getGemstoneTypes':
			include APICLUDE . 'class.product.php';
			$obj = new product($db['iftosi']);
			$res = $obj->getGemstoneTypes();
		break;

		case "getPrdImgsByIds":
			include APICLUDE . 'class.product.php';
			$obj = new product($db['iftosi']);
			$res = $obj->getPrdImgsByIds($params);
		break;

		case "sendMail":
			include APICLUDE . 'class.sendingMail.php';
                        $to=(!empty($params['to'])) ? trim(urldecode($params['to'])) : '';
                        $str=(!empty($params['str'])) ? trim(urldecode($params['str'])) : '';
                        $obj = new sendingMail($db['iftosi']);
                        $res = $obj->sendMail($params);
    break;

        default :

        break;
    }
echo json_encode($res);
exit;
?>
