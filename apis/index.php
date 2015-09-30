<?php
include '../config.php';
$res['results'] = array();
$params = array_merge($_GET, $_POST);
$action = $_GET['action'];
        
switch($action)
    {        
//----------------------------User--------------------------------

//  localhost/iftosisb/apis/index.php?action=checkUser&mobile=9987867578                
        case 'checkUser':
            include APICLUDE.'class.user.php';
            $mobile=(!empty($params['mobile'])) ? trim($params['mobile']) : '';
            if(empty($mobile))
            {   
                $arr = "Some Parameters missing";
                $err = array('Code' => 1, 'Msg' => 'Some Parameters missing');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new user($db['iftosi']);
            $result= $obj->checkUser($params);
            $res= $result;
            break;
            
// localhost/iftosisb/apis/index.php?action=userReg&username=Shushrut Kumar&password=mishra1.234&mobile=7309290529&email=shubham.bajpai@xelpmoc.in&isvendor=1                                        
        case 'userReg':
            include APICLUDE.'class.user.php';
            $username=(!empty($params['username'])) ? trim(urldecode($params['username'])) : '';
            $password=(!empty($params['password'])) ? trim(urldecode($params['password'])) : '';
            $mobile=(!empty($params['mobile'])) ? trim($params['mobile']) : '';
            $email=(!empty($params['email'])) ?  trim(urldecode($params['email'])) : '';
            $isvendor=(!empty($params['isvendor'])) ? trim($params['isvendor']) : '';
            if(empty($username) || empty($password) || empty($mobile) || empty($email) || empty($isvendor))
            {   
                $arr = "Some Parameters missing";
                $err = array('Code' => 1, 'Msg' => 'Some Parameters missing');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }

            $obj= new user($db['iftosi']);
            $result= $obj->userReg($params);
            $res= $result;
            break;
            
// localhost/iftosisb/apis/index.php?action=udtVProfile&vname=Shushrut Kumar&mobile=7457464635&pincode=232322&area=janakpuri&city=Delhi&state=delhi&country=india&add1=pahadganj&add2=groverbaug&landline=05222362707            
        case 'udtVProfile':
            include APICLUDE.'class.user.php';
            $vname=(!empty($params['vname'])) ?  trim(urldecode($params['vname'])) : '';
            $altermobile=(!empty($params['mobile'])) ?  trim(urldecode($params['mobile'])) : '';
            $pincode=(!empty($params['pincode'])) ? trim($params['pincode']) : '';
            $area=(!empty($params['area'])) ? trim(urldecode($params['area'])) : '';
            $city=(!empty($params['city'])) ?  trim(urldecode($params['city'])) : '';
            $state=(!empty($params['state'])) ?  trim(urldecode($params['state'])) : '';
            $country=(!empty($params['country'])) ? trim(urldecode($params['country'])) : '';
            $add1=(!empty($params['add1'])) ?  trim(urldecode($params['add1'])) : '';
            $add2=(!empty($params['add2'])) ?  trim(urldecode($params['add2'])) : '';                
            $landline=(!empty($params['landline'])) ?  trim(urldecode($params['landline'])) : '';                
            
            if(empty($vname) || empty($pincode) || empty($area) || empty($city) || empty($altermobile) || empty($state) || empty($country) || empty($add1) || empty($add2) || empty($landline))
            {   
                //echo "here";
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Some parameters missing');
                $result = array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj= new user($db['iftosi']);
            $result= $obj->udtVProfile($params);
            $res= $result;
            break;                
            
// localhost/iftosisb/apis/index.php?action=logUser&mobile=9975887206&password=bajpai123            
        case 'logUser':
            include  APICLUDE.'class.user.php';
            $mobile=(!empty($params['mobile'])) ?  trim($params['mobile']) : '';
            $password=(!empty($params['password'])) ?  trim(urldecode($params['password'])) : '';
            if(empty($mobile) || empty($password))
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

// localhost/iftosisb/apis/index.php?action=updatePass&mobile=9975887206&password=bajpai123            
        case 'updatePass':
            include APICLUDE.'class.user.php';
            $password=(!empty($params['passowrd'])) ?  trim(urldecode($params['password'])) : '';
            $mobile=(!empty($params['mobile'])) ?  trim($params['mobile']) : '';
            if(empty($password) || empty($mobile))
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

// localhost/iftosisb/apis/index.php?action=deactUser&mobile=9975887206            
        case 'deactUser':
            include APICLUDE.'class.user.php';
            $mobile=(!empty($params['mobile'])) ?  trim($params['mobile']) : '';
            if(empty($params['mobile']))
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

// localhost/iftosisb/apis/index.php?action=actUser&mobile=9975887206                        
        case 'actUser':
            include APICLUDE.'class.user.php';
            $mobile=(!empty($params['mobile'])) ?  trim($params['mobile']) : '';
            if(empty($params['mobile']))
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

// localhost/iftosisb/apis/index.php?action=viewAll&mobile=9975887206            
        case 'viewAll':
            include APICLUDE.'class.user.php';
            $mobile=(!empty($params['mobile'])) ?  trim($params['mobile']) : '';
            if(empty($mobile))
            {
                $arr=array();
                $err=array('code'=>1,'Msg'=>'Some Parameters missing');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new user($db['iftosi']);
            $result =  $obj->viewAll($params);
            $res= $result;
            break;

//-----------------------------ViewLog----------------------------                

//  localhost/iftosisb/apis/index.php?action=filLog&mobile=9975887206&product_id=7            
        case 'filLog':
            include APICLUDE.'class.viewlog.php';
            $mobile=(!empty($params['mobile'])) ? trim($params['mobile']) : '';
            $product_id=(!empty($params['product_id'])) ? trim($params['product_id']) : '';
            if(empty($product_id) || empty($mobile))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new viewlog($db['iftosi']);
            $result= $obj->filLog($params);
            $res = $result;
            break;

//  localhost/iftosisb/apis/index.php?action=viewLog&logmobile=7878787878                        
        case 'viewLog':
            include APICLUDE.'class.viewlog.php';
            $logmobile=(!empty($params['logmobile'])) ? trim($params['logmobile']) : '';
            if(empty($logmobile))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new viewlog($db['iftosi']);
            $result= $obj->viewLog($params);
            $res = $result;
            break;
            
//-----------------------------Vendor-----------------------------

//  localhost/iftosisb/apis/index.php?action=addVendorPrdInfo&product_id=7&logmobile=9975887206&vendor_price=3300023&vendor_quantity=3&vendor_currency=INR&vendor_remarks=Excellent&active_flag=1            
        case 'addVendorPrdInfo':
            include APICLUDE.'class.vendor.php';            
            $product_id=(!empty($params['product_id'])) ? trim($params['product_id']) : '';
            $logmobile=(!empty($params['logmobile'])) ? trim($params['logmobile']) : '';
            $vendor_price=(!empty($params['vendor_price'])) ? trim($params['vendor_price']) : '';
            $vendor_quantity=(!empty($params['vendor_quantity'])) ? trim($params['vendor_quantity']) : '';
            $vendor_currency=(!empty($params['vendor_currency'])) ? trim(urldecode($params['vendor_currency'])) : '';
            $vendor_remarks=(!empty($params['vendor_remarks'])) ? trim(urldecode($params['vendor_remarks'])) : '';
            $active_flag=(!empty($params['active_flag'])) ? trim($params['active_flag']) : '';
            if(empty($product_id)||empty($logmobile)||empty($vendor_price)||empty($vendor_quantity)||empty($vendor_currency)||empty($vendor_remarks)||empty($active_flag))
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

//  localhost/iftosisb/apis/index.php?action=getVproducts&vendormobile=9975887206  
        case 'getVproducts':
            include APICLUDE.'class.vendor.php';
            $vendormobile=(!empty($params['vendormobile'])) ? trim($params['vendormobile']) : '';
            if(empty($vendormobile))
            {
                $arr = array();
                $err = array('Code' => 1, 'Msg' => 'Invalid parameters');
                $result = array('results' => $arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj=new vendor($db['iftosi']);
            $result=$obj->getVproducts($params);
            $res=$result;
            break;

//  localhost/iftosisb/apis/index.php?action=getVproductsByName&vendormobile=9975887206&prname=blue            
        case 'getVproductsByName':
            include APICLUDE.'class.vendor.php';
            $vendormobile=(!empty($params['vendormobile'])) ? trim($params['vendormobile']) : ''; //user session mobile
            $prname=(!empty($params['prname'])) ? trim(urldecode($params['prname'])) : '';
            if(empty($vendormobile)||empty($vendormobile))
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
                        
//  localhost/iftosisb/apis/index.php?action=updateProductInfo&vendor_id=1&logmobile=9975887206&product_id=7&vendor_price=93323823&vendor_quantity=10&active_flag=1
        case 'updateProductInfo':
            include APICLUDE.'class.vendor.php';
            $vendor_id=(!empty($params['vendor_id'])) ? trim($params['vendor_id']) : '';
            $logmobile=(!empty($params['logmobile'])) ? trim($params['logmobile']) : '';
            $product_id=(!empty($params['product_id'])) ? trim($params['product_id']) : '';
            $vendor_price=(!empty($params['vendor_price'])) ? trim($params['vendor_price']) : '';
            $vendor_quantity=(!empty($params['vendor_quantity'])) ? trim($params['vendor_quantity']) : '';
            $active_flag=(!empty($params['active_flag'])) ? trim($params['active_flag']) : '';
            if(empty($vendor_id)||empty($product_id)||empty($logmobile)||empty($vendor_price)||empty($vendor_quantity)||empty($active_flag))
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
            
//  localhost/iftosi/apis/index.php?action=getVDetailByPid&product_id=7
        case 'getVDetailByPid':
            include APICLUDE.'class.vendor.php';
            $product_id=(!empty($params['product_id'])) ? trim($params['product_id']) : '';
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

//-------------------------Location---------------------------------               
            
// localhost/iftosisb/apis/index.php?action=addCity&cname=Pakistan&sname=Punjab&cityname=lahore            
        case 'addCity':
            include APICLUDE.'class.location.php';
            $cityname=(!empty($params['cityname'])) ? trim(urldecode($params['cityname'])) : '';
            $sname=(!empty($params['sname'])) ? trim(urldecode($params['sname'])) : '';
            $cname=(!empty($params['cname'])) ? trim(urldecode($params['cname'])) : '';
            if(empty($cname)||empty($cityname)||empty($sname))
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
            
// localhost/iftosisb/apis/index.php?action=viewbyCity&cityname=bangalore            
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
                        
// localhost/iftosisb/apis/index.php?action=viewbyState&sname=haryana&cname=India                 
        case 'viewbyState': 
            include APICLUDE.'class.location.php';
            $sname=(!empty($params['sname'])) ? trim(urldecode($params['sname'])) : '';
            $cname=(!empty($params['cname'])) ? trim(urldecode($params['cname'])) : '';
            if(empty($sname)||empty($cname))
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
        
//  localhost/iftosisb/apis/index.php?action=viewbyCountry&cname=Delhi            
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
            
// localhost/iftosisb/apis/index.php?action=updatecity&newcityname=Delhi&oldcityname=delhi&sname=delhi&cname=India            
        case 'updatecity':
            include APICLUDE.'class.location.php';
            $newcityname=(!empty($params['newcityname'])) ? trim(urldecode($params['newcityname'])) : '';
            $oldcityname=(!empty($params['oldcityname'])) ? trim(urldecode($params['oldcityname'])) : '';
            $sname=(!empty($params['sname'])) ? trim(urldecode($params['sname'])) : '';
            $cname=(!empty($params['cname'])) ? trim(urldecode($params['cname'])) : '';            
            if(empty($cname)|| empty($newcityname) || empty($sname) || empty($oldcityname))
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
        
// localhost/iftosisb/apis/index.php?action=getCatList        
        case 'getCatList': 
            include APICLUDE.'class.categoryInfo.php';
            $obj= new categoryInfo($db['iftosi']);
            $result= $obj->getCatList();
            $res=$result;
            break;
        
// localhost/iftosisb/apis/index.php?action=getCatName&catid=3        
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

// localhost/iftosisb/apis/index.php?action=getCatId&catName=jwellery            
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

// localhost/iftosisb/apis/index.php?action=addCat&catName=Diamond       
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
        
// localhost/iftosisb/apis/index.php?action=deleteCat&catid=3                        
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

// localhost/iftosisb/apis/index.php?action=updateCat&catid=2&catName=jwellery            
        case 'updateCat':
            include APICLUDE.'class.categoryInfo.php';
            $catid=(!empty($params['catid'])) ? trim($params['catid']) : '';
            $catName=(!empty($params['catName'])) ? trim(urldecode($params['catName'])) : '';
            if(empty($catid) || empty($catName))
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

// localhost/iftosisb/apis/index.php?action=getBrandList           
        case 'getBrandList':
            include APICLUDE.'class.brandInfo.php';
            $obj= new brandInfo($db['iftosi']);
            $result= $obj->getBrandList();
            $res=$result;
            break;

//-------------------------Auto---------------------------------  

// localhost/iftosisb/apis/index.php?action=searchbox&srch=p        
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

//--------------------Product-----------------------------------           

//  localhost/iftosisb/apis/index.php?action=addNewproduct&dt={%22result%22:%20{%22product_name%22:%20%22bluediamond%22,%22brandid%22:%2011,%22lotref%22:%201123,%22barcode%22:%20%22qw211111%22,%22lotno%22:%201133,%22product_display_name%22:%20%22marveric%20blue%20silver%20diamond%22,%22product_model%22:%20%22rw231%22,%22product_brand%22:%20%22orra%22,%22product_price%22:%2012211223.02,%22product_currency%22:%20%22INR%22,%22product_keywords%22:%20%22blue,silver,diamond%22,%22product_desc%22:%20%22a%20clear%20cut%20solitaire%20diamond%20in%20the%20vault%22,%22product_wt%22:%20223.21,%22prd_img%22:%20%22abc.jpeg%22,%22category_id%22:%201,%22product_warranty%22:%20%221%20year%22},%22attributes%22:%20[[111,3,%22green%22,1]],%22error%22:%20{%22errCode%22:%200}}
        case 'addNewproduct':
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
            $result=$obj->addNewproduct($params);
            $res=$result;
            break;
            
        /*        case 'addProduct':
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
            $result=$obj->addProduct($params);
            $res=$result;
            break; */

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
            
//  localhost/iftosisb/apis/index.php?action=getPrdByName&prname=blue            
        case 'getPrdByName':
            include APICLUDE.'class.product.php';
            $prname=(!empty($params['prname'])) ? trim(urldecode($params['prname'])) : '';
            if(empty($prname))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err['error']);
            $res=$result;
            break;
            }
            $obj=new product($db['iftosi']);
            $result=$obj->getPrdByName($params);
            $res=$result;
            break;

//  localhost/iftosisb/apis/index.php?action=getPrdByCatid&catid=1         
        case 'getPrdByCatid':
            include APICLUDE.'class.product.php';
            $catid=(!empty($params['catid'])) ? trim($params['catid']) : '';
            if(empty($catid))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameter');
            $result=array('results'=> $arr,'error'=>$err['error']);
            $res=$result;
            break;
            }
            $obj=new product($db['iftosi']);
            $result=$obj->getPrdByCatid($params);
            $res=$result;
            break;

//  localhost/iftosisb/apis/index.php?action=getPrdById&prdid=7          
        case 'getPrdById':            
            include APICLUDE.'class.product.php';
            $prdid=(!empty($params['prdid'])) ? trim($params['prdid']):'';
            if(empty($prdid))
            {
            $arr=array();
            $err=array('code'=> 1,'Msg'=> 'Invalid parameters');
            $result=array('results'=> $arr,'error'=>$err['error']);
            $res=$result;
            break;
            }
            $obj=new product($db['iftosi']);
            $result=$obj->getPrdById($params);
            $res= $result;
            break;
            
//  localhost/iftosisb/apis/index.php?action=getList
        case 'getList':
            include APICLUDE.'class.product.php';
            $obj=new product($db['iftosi']);
            $result=$obj->getList($params);
            $res=$result;
            break;

//  localhost/iftosisb/apis/index.php?action=productByCity&cityname=lucknow            
        case 'productByCity':
            include APICLUDE.'class.product.php';
            $cityname=(!empty($params['cityname'])) ? trim($params['cityname']): "";
            //$pcode=(!empty($params['product_id'])) ? trim($params['product_id']): "";
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
            
//   localhost/iftosisb/apis/index.php?action=productByBrand&bname=jewel
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
            
//----------------------Attribute-----------------------------------------------

 // localhost/iftosisb/apis/index.php?action=get_attrList            
        case 'get_attrList':
            include APICLUDE.'class.attribute.php';
            $obj = new attribute($db['iftosi']);
            $result=$obj->get_attrList();
            $res=$result;
            break;
        
//  localhost/iftosisb/apis/index.php?action=set_attributes_details&name=flurocent&dname=luminous&unit=10&flag=1&upos=2&vals={10,20,30,40,50,60,70}&range=10        
        case 'set_attributes_details':
            include APICLUDE.'class.attribute.php';
            $name=(!empty($params['name'])) ? trim($params['name']):'';
            $dname=(!empty($params['dname'])) ? trim($params['dname']):'';
            $unit=(!empty($params['unit'])) ? trim($params['unit']):'';
            $flag=(!empty($params['flag'])) ? trim($params['flag']):'';                
            $upos=(!empty($params['upos'])) ? trim(urldecode($params['upos'])):'';
            $vals=(!empty($params['vals'])) ? trim($params['vals']) : '';
            $range=(!empty($params['range'])) ? trim(urldecode($params['range'])):'';
            if(empty($name) || empty($dname) || empty($unit) || empty($flag) || empty($upos) || empty($vals) || empty($range))
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

//  localhost/iftosisb/apis/index.php?action=fetch_attributes_details&attribid=100012        
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

//  localhost/iftosisb/apis/index.php?action=set_category_mapping&aid=43&dflag=1&dpos=999&fil_flag=1&fil_pos=999&aflag=1&catid=3
        case 'set_category_mapping':
            include APICLUDE.'class.attribute.php';
            $aid =(!empty($params['aid'])) ? trim($params['aid']):'';
            $dflag =(!empty($params['dflag'])) ? trim($params['dflag']):'';
            $dpos =(!empty($params['dpos'])) ? trim($params['dpos']):'';
            $fil_flag =(!empty($params['fil_flag'])) ? trim($params['fil_flag']):'';
            $fil_pos =(!empty($params['fil_pos'])) ? trim($params['fil_pos']):'';
            $aflag =(!empty($params['aflag'])) ? trim($params['aflag']):'';
            $catid =(!empty($params['catid'])) ? trim($params['catid']):'';
            if(empty($aid) || empty($dflag) || empty($dpos) || empty($fil_flag) || empty($fil_pos) || empty($aflag) || empty($catid))
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
            
//  localhost/iftosisb/apis/index.php?action=fetch_category_mapping&catid=3            
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

//  localhost/iftosisb/apis/index.php?action=unset_category_mapping&catid=3&aid=12            
        case 'unset_category_mapping':
            include APICLUDE.'class.attribute.php';
            $id   =(!empty($params['aid'])) ? trim($params['aid']): "";
            $catid=(!empty($params['catid'])) ? trim($params['catid']): "";
            if(empty($id)|| empty($catid))
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
            
        case 'get_filters':
            include APICLUDE.'class.filter.php';
            $category_id=(!empty($params['category_id'])) ? trim($params['category_id']):'';
            if(empty($category_id))
            {   
                $arr = "Parameter is missing";
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameter');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new filter($db['iftosi']);
            $result=$obj->get_filters($params);
            $res=$result;
            break;
                        
        case 'refine': 
            include APICLUDE.'class.filter.php';
            $dt=(!empty($params['dt'])) ? trim($params['dt']):'';
            if(empty($dt))
            {   
                $arr = "Parameter is missing";
                $err = array('Code' => 1, 'Msg' => 'Invalid Parameter');
                $result = array('results'=>$arr, 'error' => $err);
                $res=$result;
                break;
            }
            $obj= new filter($db['jzeva']);
            $result=$obj->refine($params);
            $res=$result;
            break;
            
//-------------------------Lineage----------------------------------------

        case 'set_lineage':
            include APICLUDE.'class.categories.php';
            $dt=(!empty($params['dt']))? trim($params['dt']):'';
            if(empty($dt))
            {
                $arr="Parameters missing";
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new lineage($db['jzeva']);
            $result=$obj->set_lineage($params);
            $res=$result;
            break;
            
        case 'upd_prd_lineage':
            include APICLUDE.'class.categories.php';
            $dt=(!empty($params['dt']))? trim($params['dt']):'';
            if(empty($dt))
            {
                $arr="Parameters missing";
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new lineage($db['jzeva']);
            $result=$obj->udt_prd_lineage($params);
            $res=$result;
            break;    
            
//------------------------Wishlist-------------------------------------------
        
        case 'addtowsh':
            include APICLUDE.'class.wishlist.php';
            $dt=(!empty($params['dt']))? trim($params['dt']):'';
            if(empty($dt))
            {
                $arr="Parameters missing";
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new lineage($db['jzeva']);
            $result=$obj->addtowsh($params);
            $res=$result;
            break;
            
        case 'viewsh':
            include APICLUDE.'class.wishlist.php';
            $dt=(!empty($params['dt']))? trim($params['dt']):'';
            if(empty($dt))
            {
                $arr="Parameters missing";
                $err=array('Code'=>1,'Msg'=>'Invalid Parameters');
                $result=array('results'=>$arr,'error'=>$err);
                $res=$result;
                break;
            }
            $obj=new lineage($db['jzeva']);
            $result=$obj->viewwsh($params);
            $res=$result;
            break;    
            
//---------------------------------------------------------------------------                    
 
//---------------------------------------------------------------------------            
        default :
        break;
    }    
echo json_encode($res);
exit;
?>