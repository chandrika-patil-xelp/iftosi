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
            $isql= "INSERT INTO tbl_vendor_master(vendor_id,vendor_name,contact_mobile,email,updatedon,cdt,is_complete) VALUES(".$uid.",'".$params['username']."',".$params['mobile'].",'".$params['email']."',now(),now(),0)";
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
               $isql = "INSERT INTO tbl_user_info(user_id,userName,cdt,udt)
                        VALUES(".$uid.",'".$params['username']."',now(),now())";        
               $ires=$this->query($isql);
               if($ires)
               {
                $arr='Registeration is completed';
                $err=array('code'=>0,'msg'=>'Insertion is Done');
               }
               
                else
                {
                $arr='Error in registering user';
                $err=array('code'=>1,'msg'=>'Error in insertion ');
                }
            }
            else
            {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Error in insertion ');
            }
            $result = array('results' =>$arr,'error'=>$err);
            return $result;
        }
        
        public function udtProfile($params) // Update vendor details
        {
            $dt= json_decode($params['dt'],1);
            $detls  = $dt['result'];
            
            $dob=explode(' ',$detls['dob']);
            $detls['dob']=implode('-',$dob);
            
         $sql="SELECT is_vendor,user_id from tbl_registration where logmobile=".$detls['logmobile']."";
          $res=$this->query($sql);
          $row=$this->fetchData($res);
          $isv=$row['is_vendor'];
          $uid=$row['user_id'];
          $isv;
          if($isv==1)
          {
           $vsql = "UPDATE tbl_vendor_master set vendor_name='".$detls['username']."',gender=".$detls['gen'].",alt_email='".$detls['alt_email']."',dob=STR_TO_DATE('".$detls['dob']."','%Y-%m-%d'),working_phone='".$detls['workphone']."',postal_code=".$detls['pincode'].",area='".$detls['area']."', city='".$detls['cityname']."',state='".$detls['state']."',country='".$detls['country']."',address1='".$detls['address1']."',address2='".$detls['address2']."',contact_mobile=".$detls['logmobile'].",landline=".$detls['landline'].",id_proof_type='".$detls['idtype']."',id_proof='".$detls['idproof']."',lat=".$detls['lat'].",lng=".$detls['lng'].",updatedby='".$detls['username']."',is_complete=is_complete WHERE vendor_id=".$uid."";
             $vres=$this->query($vsql);
            if($vres)
            {
                $arr="Vendor table is updated";
                $err=array('code'=>0,'msg'=>'Update operation is done successfully');
            }
          }
         else if($isv==0)
          {
          $vsql = "UPDATE tbl_user_info set userName='".$detls['username']."',gender=".$detls['gen'].",alt_email='".$detls['alt_email']."',dob=STR_TO_DATE('".$detls['dob']."','%Y-%m-%d'),working_phone='".$detls['workphone']."',pincode=".$detls['pincode'].",area='".$detls['area']."', cityname='".$detls['cityname']."',state='".$detls['state']."',country='".$detls['country']."',address1='".$detls['address1']."',address2='".$detls['address2']."',logmobile=".$detls['mobile'].",landline=".$detls['landline'].",id_type='".$detls['idtype']."',id_proof_no='".$detls['idproof']."',lat=".$detls['lat'].",lng=".$detls['lng'].",updatedby='".$detls['username']."',is_complete=is_complete WHERE user_id=".$uid."";
             $vres=$this->query($vsql);
             if($vres)
             {
                $arr="User Profile is updated";
                $err=array('code'=>0,'msg'=>'Update operation is done successfully');
             }
             else
             {
                $arr="profile is not updated";
                $err=array('code'=>0,'msg'=>'Update operation unsuccessfull');
             }
          }
          else 
          {
                $arr="profile user type is not defined";
                $err=array('code'=>0,'msg'=>'Update operation unsuccessfull');
          }
          
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }
                
        public function logUser($params) // USER LOGIN CHECK
        {
            $vsql="SELECT logmobile,password,is_vendor from tbl_registration where logmobile=".$params['mobile']." AND password=MD5('".$params['password']."') AND is_active=1";
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
                $err['error']=array('code'=>1,'msg'=>'Parameters matched');
                }
               else if($ut=1)
                {
                $arr="Welcome and greetings Vendor";
                $err=array('code'=>1,'msg'=>'Parameters matched');
                }
            }   
            else
            {
                $arr="User name or password don't exist";
                $err=array('code'=>1,'msg'=>'Problem in fetching data');
            }
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }
                
        public function actUser($params) // Activate Status
        {   
            $vsql="SELECT is_active from tbl_registration where logmobile='".$params['mobile']."'";
            $vres=$this->query($vsql);
            if($this->numRows($vres)==1) //If user is registered
            {
                $usql="UPDATE tbl_registration set is_active=1 where logmobile=".$params['mobile'];
                $ures=$this->query($usql);
                if($ures)
                {
                    $arr="User profile is activated";
                    $err=array('code'=>1,'msg'=>'Value has been changed');
                }
                else
                {
                $arr="Update operation is not performed";
                $err=array('code'=>1,'msg'=>'Error in updating data');
                }
            }
            else
            {
                $arr="Data Not Found regarding ur requested parameters";
                $err=array('code'=>1,'msg'=>'Problem in fetching data');
            }  // If user is not registered
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }

        public function deactUser($params) // DeActivate Status
        {   
            $vsql="SELECT is_active from tbl_registration where logmobile=".$params['mobile']."";
            $vres=$this->query($vsql);
            if($this->numRows($vres)==1) //If user is registered
            {
            $usql="UPDATE tbl_registration set is_active=0 where logmobile=".$params['mobile'];
            $ures=$this->query($usql);
                if($ures)
                {
                $arr="User profile is deactivated";
                $err=array('code'=>0,'msg'=>'Row has been Updated');
                }
                else
                {
                $arr="Update operation is not performed";
                $err=array('code'=>1,'msg'=>'Error in updating data');
                }
            }
            else
            {
                $arr="Data Not Found regarding ur requested parameters";
                $err=array('code'=>1,'msg'=>'Problem in fetching data');
            }  // If user is not registered
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }

        public function updatePass($params)
        {
          $vsql="SELECT logmobile,user_name,email from tbl_registration where logmobile=".$params['mobile']." AND is_active=1";
            $vres=$this->query($vsql);
            if($this->numRows($vres)==1) //If user is registered
            {
               $usql="UPDATE tbl_registration set password=MD5('".$params['password']."') where logmobile=".$params['mobile'];
                $ures=$this->query($usql);
                if($ures)
                {
                $arr="User profile password is updated";
                $err=array('code'=>0,'msg'=>'Row has been Updated');
                }
                else
                {
                $arr="Password not updated";
                $err=array('code'=>1,'msg'=>'Error in updating data');
                }
            }
            else
            {
                $arr="User Not Exist";
                $err=array('code'=>1,'msg'=>'Problem in fetching data');
            }  // If user is not registered
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }
        
        public function viewAll($params)
        {
            $vsql="SELECT is_vendor,user_id from tbl_registration where user_id=".$params['uid']." AND is_active=1";
            $vres=$this->query($vsql);
            $chkres=$this->numRows($vres);
            if($chkres>0)//If user is registered and is customer
            {  
                while($row1=$this->fetchData($vres))
                {
                    $arr1['isv']=$row1['is_vendor'];
                    $arr1['uid']=$row1['user_id'];
                }

                if($arr1['isv']==0)   // check if it is User
                {  
                  $vensql="SELECT * from tbl_user_info where user_id =".$arr1['uid'];
                  $res=$this->query($vensql);
                  while($row=$this->fetchData($res))
                   {
                      $arr[]=$row;
                  }
                  $err=array('code'=>0,'msg'=>'Values fetched');
                }
                else if($arr1['isv']==1)    // check if it is Vendor
                {
                  $vensql="SELECT * from tbl_vendor_master where vendor_id =".$arr1['uid'];
                  $res=$this->query($vensql);
                  while($row=$this->fetchData($res))
                  {
                      $arr[]=$row;
                  }
                  $err=array('code'=>0,'msg'=>'Data fetched successfully');
                }
                else
                {
                    $arr="Usertyp not Exist";
                    $err=array('code'=>1,'msg'=>'Problem in fetching data');
                }
            }
            else
            {
                $arr="User Not Exist";
                $err=array('code'=>1,'msg'=>'Problem in fetching data');
            }  
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }
    }
    ?>