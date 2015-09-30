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
            $csql="select mobile from tbl_registration where mobile=".$params['mobile']."";
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
           $isql = "INSERT INTO tbl_registration(user_name,password,mobile,email,is_vendor,is_active,date_time,update_time,updated_by)
                    VALUES('".$params['username']."',MD5('".$params['password']."'),".$params['mobile'].",'".$params['email']."',".$params['isvendor'].",0,now(),now(),'".$params['username']."')";
            $ires=$this->query($isql);
            if($params['isvendor']==1)
            {
            $isql= "INSERT INTO tbl_vendor_master(vendor_name,contact_mobile,email,updatedon,backendupdate,is_complete) VALUES('".$params['username']."',".$params['mobile'].",'".$params['email']."',now(),now(),0)";                     
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
           else if($params['isvendor']==0)
            {
                $arr='Registeration is completed';
                $err=array('code'=>0,'msg'=>'Insertion is Done');
            }
            else
            {
                 $arr='number already registered';
                $err=array('code'=>1,'msg'=>'This number is aready registered');
            }
            $result = array('results' =>$arr,'error'=>$err);
            return $result;
        }
        
        public function udtVProfile($params) // Update vendor details
        {
          $vsql = "UPDATE tbl_vendor_master set vendor_name='".$params['vname']."', postal_code=".$params['pincode'].",area='".$params['area']."', city='".$params['city']."',state='".$params['state']."',country='".$params['country']."',address1='".$params['add1']."',address2='".$params['add2']."',mobile=".$params['mobile'].",landline=".$params['landline'].",active_flag=0,updatedby='".$params['vname']."',is_complete=is_complete";
          $vres=$this->query($vsql);
                if($vres)
                {
                    $arr="Vendor table is updated";
                    $err['error']=array('code'=>0,'msg'=>'Update operation is done successfully');
                }
            
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }
                
        public function logUser($params) // USER LOGIN CHECK
        {
           $vsql="SELECT user_name,password from tbl_registration where mobile='".$params['mobile']."' AND password=MD5('".$params['password']."') AND is_active=1";
           
           $vres=$this->query($vsql);
            if($this->numRows($vres)==1)
            {
                $arr="Welcome and greetings user";
                $err['error']=array('code'=>1,'msg'=>'Parameters matched');
            }
            else
            {
                $arr="No user with this mobile is registered";
                $err['error']=array('code'=>1,'msg'=>'Problem in fetching data');
            }
            $result = array('results'=>$arr,'error'=>$err['error']);
            return $result;
        }
                
        public function actUser($params) // Activate Status
        {   
            $vsql="SELECT is_active from tbl_registration where mobile='".$params['mobile']."'";
            $vres=$this->query($vsql);
            if($this->numRows($vres)==1) //If user is registered
            {
                $usql="UPDATE tbl_registration set is_active=1 where mobile=".$params['mobile'];
                $ures=$this->query($usql);
                if($ures)
                {
                    $usql="UPDATE tbl_vendor_master set active_flag=1 where contact_mobile=".$params['mobile'];
                    $ures=$this->query($usql);
                    
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
            $vsql="SELECT is_active from tbl_registration where mobile=".$params['mobile']."";
            $vres=$this->query($vsql);
            if($this->numRows($vres)==1) //If user is registered
            {
            $usql="UPDATE tbl_registration set is_active=0 where mobile=".$params['mobile'];
            $ures=$this->query($usql);
                if($ures)
                {
                $usql="UPDATE tbl_vendor_master set active_flag=0 where contact_mobile=".$params['mobile'];
                    $ures=$this->query($usql);
                    
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
            $vsql="SELECT mobile,user_name,email from tbl_registration where mobile=".$params['mobile']." AND is_active=1";
            $vres=$this->query($vsql);
            if($this->numRows($vres)==1) //If user is registered
            {
                $usql="UPDATE tbl_registration set password=MD5('".$params['password']."') where mobile=".$params['mobile'];
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
            $vsql="SELECT user_name,password,mobile,email,is_vendor,date_time,update_time from tbl_registration where mobile=".$params['mobile']." AND is_active=1";
            $vres=$this->query($vsql);
            $chkres=$this->numRows($vres);
            if($chkres>0)//If user is registered and is customer
            {   $i=-1;
                while($row=$this->fetchData($vres))
                {
                    $arr1[]=$row;
                    $i++;
                }

                if($arr1[$i]['is_vendor'] == 0)
                {   
                    $arr[]=$arr1;   
                    $err=array('code'=>0,'msg'=>'Values fetched');
                }
                else if($arr1[$i]['is_vendor'] == 1)
                {
                  $vensql="SELECT * from tbl_vendor_master where contact_mobile =".$params['mobile'];
                  $res=$this->query($vensql);
                  while($row=$this->fetchData($res))
                   {
                      $arr2[]=$row;
                  }
                  $arr[]=$arr2;
                  $err=array('code'=>0,'msg'=>'Data fetched successfully');
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