<?php
        include APICLUDE.'common/db.class.php';
	class user extends DB
    {
		function __construct($db) 
                {
			parent::DB($db);
		}

        public function checkUser($params)
        {
            $csql="select logmobile from tbl_registration where mobile=".$params['mobile']."";
            $cres=$this->query($csql);
            $cnt1 = $this->numRows($cres);
            if($cnt1==0)
            {
                $arr='User Not yet Registered';
                $err=array('Code'=>0,'Msg'=>'No Data matched');
            }
            else 
            {
            $arr='User is already Registered';
            $err=array('Code'=>0,'Msg'=>'No Data matched');
            }
            $result = array('results' => $arr, 'error' => $err);
            return $result;
        }

        public function userReg($params) // USER LOGIN PROCESS
        {   
           $isql = "INSERT INTO tbl_registration(user_name,password,logmobile,email,is_vendor,is_active,date_time,update_time,updated_by)
                    VALUES('".$params['username']."',MD5('".$params['password']."'),".$params['mobile'].",'".$params['email']."',".$params['isvendor'].",0,now(),now(),'".$params['username']."')";
            $ires=$this->query($isql);
            $uid=$this->lastInsertedId();
            
            if($params['isvendor']=1)
            {
            $isql= "INSERT INTO tbl_vendor_master(vendor_id,email,date_time,is_complete) VALUES(".$uid.",'".$params['email']."',now(),0)";
            $res=$this->query($isql);
                if($res)
                {
                $arr="SignUp process Is Complete";
                $err=array('code'=>0,'msg'=>"Insert Operation Done");
                }
                else
                {       
                    $arr="Problem in SignUp";
                    $err=array('code'=>1,'msg'=>"Error in insert operation");
                }
            }
           else if($params['isvendor']=0)
            {
                $arr="SignUp process Is Complete";
                $err=array('code'=>0,'msg'=>"Insert Operation Done");            
            }
            else
            {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Error in insertion ');
            }
            $result = array('results' =>$arr, 'userid' => $uid,'error'=>$err);
            return $result;
        }
        
        public function udtProfile($params) // Update vendor details
        {
            $dt= json_decode($params['dt'],1);
            $detls  = $dt['result'];
            
            $sql="SELECT is_vendor,user_id from tbl_registration where user_id=".$detls['uid']."";
            $res=$this->query($sql);
            $row=$this->fetchData($res);
            $isv=$row['is_vendor'];
            $uid=$row['user_id'];
          
            if($isv==1)
            {
              /*
           $vsql = "UPDATE 
                                        tbl_vendor_master 
                    SET 
                                        orgName='".$detls['orgname']."',
                                        fulladdress=".$detls['fulladd'].",
                                        address1='".$detls['add1']."',
                                        area='".$detls['area']."',
                                        postal_code='".$detls['pincode']."',
                                        city='".$detls['city']."',
                                        country='".$detls['country']."',
                                        state='".$detls['state']."',
                                        telephones='".$detls['tel']."',
                                        alt_email='".$detls['altmail']."',
                                        officecity='".$detls['ofcity']."',
                                        officecountry='".$detls['ofcountry']."', 
                                        contact_person='".$detls['cperson']."',
                                        position='".$detls['position']."',
                                        contact_mobile=".$detls['cmobile'].",
                                        email='".$detls['email']."',
                                        memship_Cert='".$detls['memcert']."',
                                        bdbc='".$detls['bdbc']."',
                                        other_bdbc='".$detls['othbdbc']."',
                                        vatno=".$detls['vat'].",
                                        website='".$detls['wbst']."',
                                        landline='".$detls['landline']."',
                                        mdbw='".$detls['mdbw']."',
                                        banker='".$detls['banker']."',
                                        pancard='".$detls['pan']."',
                                        turnover='".$detls['tovr']."',
                                        lat=".$detls['lat'].",
                                        lng=".$detls['lng'].",
                                        updatedby='vendor',
                                        is_complete=is_complete 
                WHERE 
                                                vendor_id=".$uid."";
               
               */
            $vsql='UPDATE tbl_vendor_master SET ';
            if (!empty($detls['orgname'])) {
                $vsql .= " orgName = '".$detls['orgname']."', ";
            }

            if (!empty($detls['fulladd'])) {
                $vsql .= " fulladdress = '".$detls['fulladd']."',";
            }
            if (!empty($detls['add1'])) {
                $vsql .= " address1 = '".$detls['add1']."',";
            }
            if (!empty($detls['area'])) {
                $vsql .= " area = '".$detls['area']."',";
            }
            if (!empty($detls['pincode'])) {
                $vsql .= " postal_code = '".$detls['pincode']."',";
            }
            if (!empty($detls['city'])) {
                $vsql .= " city = '".$detls['city']."',";
            }
            if (!empty($detls['country'])) {
                $vsql .= " country = '".$detls['country']."',";
            }
            if (!empty($detls['state'])) {
                $vsql .= " state = '".$detls['state']."',";
            }
            if (!empty($detls['tel'])) {
                $vsql .= " telephones = '".$detls['tel']."',";
            }
            if (!empty($detls['altmail'])) {
                $vsql .= " alt_email = '".$detls['altmail']."',";
            }
            if (!empty($detls['ofcity'])) {
                $vsql .= " officecity = '".$detls['ofcity']."',";
            }
            if (!empty($detls['ofcountry'])) {
                $vsql .= " officecountry = '".$detls['ofcountry']."',";
            }
            if (!empty($detls['cperson'])) {
                $vsql .= " contact_person = '".$detls['cperson']."',";
            }
            if (!empty($detls['position'])) {
                $vsql .= " position = '".$detls['position']."',";
            }
            if (!empty($detls['cmobile'])) {
                $vsql .= " contact_mobile = '".$detls['cmobile']."',";
            }
            if (!empty($detls['alt_cmobile'])) {
                $vsql .= " alt_cmobile = '".$detls['alt_cmobile']."',";
            }
            if (!empty($detls['email'])) {
                $vsql .= " email = '".$detls['email']."',";
            }
            if (!empty($detls['memcert'])) {
                $vsql .= " memship_Cert = '".$detls['memcert']."',";
            }
            if (!empty($detls['bdbc'])) {
                $vsql .= " bdbc = '".$detls['bdbc']."',";
            }
            if (!empty($detls['othdbaw'])) {
                $vsql .= " other_dbaw = '".$detls['othdbaw']."',";
            }
            if (!empty($detls['showroomname'])) {
                $vsql .= " showroom_name = '".$detls['showroomname']."',";
            }
            if (!empty($detls['showroomno'])) {
                $vsql .= " no_showrooms = '".$detls['showroomno']."',";
            }
            if (!empty($detls['vat'])) {
                $vsql .= " vatno = '".$detls['vat']."',";
            }
            if (!empty($detls['wbst'])) {
                $vsql .= " website = '".$detls['wbst']."',";
            }
            if (!empty($detls['landline'])) {
                $landline=str_replace(' ', '-', $detls['landline']);
                $vsql .= " landline = '".$landline."',";
            }
            if (!empty($detls['mdbw'])) {
                $vsql .= " mdbw = '".$detls['mdbw']."',";
            }
            if (!empty($detls['bul_mdbw'])) {
                $vsql .= " bullion_mdbw = '".$detls['bul_mdbw']."',";
            }
            if (!empty($detls['banker'])) {
                $vsql .= " banker = '".$detls['banker']."',";
            }
            if (!empty($detls['pan'])) {
                $vsql .= " pancard = '".$detls['pan']."',";
            }
            if (!empty($detls['tovr'])) {
                $vsql .= " turnover = '".$detls['tovr']."',";
            }
            if (!empty($detls['busiType'])) {
                $vsql .= " business_type = '".$detls['busiType']."',";
            }
            if (!empty($detls['lat'])) {
                $vsql .= " lat = '".$detls['lat']."',";
            }
            if (!empty($detls['lng'])) {
                $vsql .= " lng = '".$detls['lng']."',";
            }
            $vsql.=" updatedby='vendor', is_complete=is_complete  WHERE vendor_id=".$uid."";

            $vres = $this->query($vsql);
            if ($vres) {
                $arr = "Vendor table is updated";
                $err = array('code' => 0, 'msg' => 'Update operation is done successfully');
            } else {
                $arr = array();
                $err = array('code' => 1, 'msg' => 'Update operation unsuccessfull');
            }
        }
        else if($isv==0)
          {
             $vsql = "UPDATE tbl_registration 
                      SET 
                                            user_name='".$detls['username']."',
                                            email='".$detls['email']."',
                                            updatedby='".$detls['username']."',
                                            is_complete=is_complete
                     WHERE 
                                            user_id=".$uid."";
             $vres=$this->query($vsql);
             if($vres)
             {
                $arr="User Profile is updated";
                $err=array('code'=>0,'msg'=>'Update operation is done successfully');
             }
             else
             {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Update operation unsuccessfull');
             }
          }
          else 
          {
                $arr=array();
                $err=array('code'=>0,'msg'=>'Update operation unsuccessfull');
          }
          
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }
                
        public function logUser($params) // USER LOGIN CHECK
        {
            $vsql="SELECT 
                          logmobile,
                          password,
                          is_vendor
                   FROM 
                          tbl_registration
                   WHERE
                          logmobile=".$params['mobile']." 
                   AND 
                          password=MD5('".$params['password']."')
                   AND
                          is_active=1";
            $vres=$this->query($vsql);
            $cntres=$this->numRows($vres);
            if($cntres=1)
            {
                while($row=$this->fetchData($vres))
                {
                    $arr['utype']=$row['is_vendor'];
                }
                $ut=$arr['utype'];
                if($ut=0)
                {
                    $arr="Welcome and greetings user";
                    $err=array('code'=>1,'msg'=>'Parameters matched');
                }
                else if($ut=1)
                {
                    $arr="Welcome and greetings Vendor";
                    $err=array('code'=>1,'msg'=>'Parameters matched');
                }
            }   
            else
            {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Problem in fetching data');
            }
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }
                
        public function actUser($params) // Activate Status
        {   
            $vsql="SELECT
                                is_active 
                   FROM 
                                tbl_registration 
                   WHERE 
                                logmobile=".$params['mobile']."";
            $vres=$this->query($vsql);
            if($this->numRows($vres)==1) //If user is registered
            {
                $usql="UPDATE
                                    tbl_registration 
                       SET
                                    is_active=1 
                       WHERE 
                                    logmobile=".$params['mobile'];
                $ures=$this->query($usql);
                if($ures)
                {
                    $arr="User profile is activated";
                    $err=array('code'=>0,'msg'=>'Value has been changed');
                }
                else
                {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Error in updating data');
                }
            }
            else
            {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Problem in fetching data');
            }  // If user is not registered
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }

        public function deactUser($params) // DeActivate Status
        {   
            $vsql="SELECT 
                                is_active 
                   FROM 
                                tbl_registration 
                   WHERE 
                                logmobile=".$params['mobile']."";
            $vres=$this->query($vsql);
            if($this->numRows($vres)==1) //If user is registered
            {
            $usql="UPDATE 
                                tbl_registration 
                   SET 
                                is_active=0 
                   WHERE 
                                logmobile=".$params['mobile'];
            $ures=$this->query($usql);
                if($ures)
                {
                $arr="User profile is deactivated";
                $err=array('code'=>0,'msg'=>'Row has been Updated');
                }
                else
                {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Error in updating data');
                }
            }
            else
            {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Problem in fetching data');
            }  // If user is not registered
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }

        public function updatePass($params)
        {
          $vsql="SELECT
                            logmobile,
                            user_name,
                            email 
                 FROM 
                            tbl_registration 
                 WHERE 
                            logmobile=".$params['mobile']."
                  AND 
                            is_active=1";
            $vres=$this->query($vsql);
            if($this->numRows($vres)==1) //If user is registered
            {
               $usql="UPDATE
                                tbl_registration 
                      SET 
                                password=MD5('".$params['password']."')
                      WHERE 
                                logmobile=".$params['mobile'];
                $ures=$this->query($usql);
                if($ures)
                {
                    $arr="User profile password is updated";
                    $err=array('code'=>0,'msg'=>'Row has been Updated');
                }
                else
                {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Error in updating data');
                }
            }
            else
            {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Problem in fetching data');
            }  // If user is not registered
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }
        
//        public function viewAll($params)
//        {
//            $vsql="SELECT 
//                                    is_vendor,
//                                    user_id 
//                   FROM 
//                                    tbl_registration 
//                   WHERE 
//                                    user_id=".$params['uid']." 
//                   AND
//                                    is_active=1";
//            $vres=$this->query($vsql);
//            $chkres=$this->numRows($vres);
//            if($chkres>0)//If user is registered and is customer
//            {
//                while($row1=$this->fetchData($vres))
//                {
//                    $arr1['isv']=$row1['is_vendor'];
//                    $arr1['uid']=$row1['user_id'];
//                }
//
//                if($arr1['isv']==0)   // check if it is User
//                {  
//                  $vensql="SELECT 
//                                            user_name,
//                                            logmobile,
//                                            email 
//                           FROM 
//                                            tbl_registration
//                           WHERE 
//                                            user_id =".$arr1['uid'];
//                  $res=$this->query($vensql);
//                  while($row=$this->fetchData($res))
//                   {
//                      $arr[]=$row;
//                  }
//                  $err=array('code'=>0,'msg'=>'Values fetched');
//                }
//                else if($arr1['isv']==1)    // check if it is Vendor
//                {
//                  $vensql="SELECT 
//                                            orgName,
//                                            email,
//                                            fulladdress,
//                                            contact_person,
//                                            contact_mobile 
//                           FROM 
//                                            tbl_vendor_master 
//                           WHERE
//                                            vendor_id =".$arr1['uid'];
//                  $res=$this->query($vensql);
//                  while($row=$this->fetchData($res))
//                  {
//                      $arr[]=$row;
//                  }
//                  $err=array('code'=>0,'msg'=>'Data fetched successfully');
//                }
//                else
//                {
//                    $arr=array();
//                    $err=array('code'=>1,'msg'=>'Problem in fetching data');
//                }
//            }
//            else
//            {
//                $arr=array();
//                $err=array('code'=>1,'msg'=>'Problem in fetching data');
//            }  
//            $result = array('results'=>$arr,'error'=>$err);
//            return $result;
//        }
    public function viewAll($params) {
        $vsql = "SELECT 
                                    is_vendor,
                                    user_id,
                                    user_name,
                                    logmobile,
                                    email 
                   FROM 
                                    tbl_registration 
                   WHERE 
                                    user_id=" . $params['uid'] . " 
                   AND
                                    is_active=1";
        $vres = $this->query($vsql);
        $chkres = $this->numRows($vres);
        if ($chkres > 0) {//If user is registered and is customer
            while ($row1 = $this->fetchData($vres)) {
                $arr1['isv'] = $row1['is_vendor'];
                $arr1['uid'] = $row1['user_id'];
                $arr[] = $row1;
                $err = array('code' => 0, 'msg' => 'Values fetched');
            }

            if ($arr1['isv'] == 1) {    // check if it is Vendor
                $vensql = "SELECT * FROM tbl_vendor_master WHERE vendor_id =" . $arr1['uid'];
                $res = $this->query($vensql);
                while ($row = $this->fetchData($res)) {
                    //$arr[] = $row;
                    array_push($arr, $row);
                }
                $err = array('code' => 0, 'msg' => 'Data fetched successfully');
            }
        } else {
            $arr = array();
            $err = array('code' => 1, 'msg' => 'Problem in fetching data');
        }
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }

	public function generatePassword($params)
	{
		$pwdLen = $params['length'];
		$pwd = '';
		if(empty($pwdLen))
		{
			$pwdLen = 6;
		}

		$i = 0;
		while($i < $pwdLen)
		{
			$pwd .= mt_rand(1, 9);
			$i++;
		}

		return $pwd;
	}
}
?>
