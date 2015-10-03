<?php
include APICLUDE.'common/db.class.php';
class viewlog extends DB
{
        function __construct($db) 
        {
            parent::DB($db);
        }

        
        ## For fetching user details of customer
        
        /* 
         * Fetch Details of user from tbl_registration
         * fetch details of clicked Product according to product_id passed while clicking
         * 
         * Fill details using two arrays. 1-- udetail  2-- prod
         * 
         *  */
    public function filLog($params)
    {  
        $uid=$params['uid'];
        $udsql="select logmobile,user_name,email from tbl_registration where user_id=".$uid."";
        $udres=$this->query($udsql);
        $chkres=$this->numRows($udres);
        if($chkres=1)
        {
            while($row1=$this->fetchData($udres)) 
            {
                $udetail['mob']=$row1['logmobile'];
                $udetail['uname']=$row1['user_name'];
                $udetail['email']=$row1['email'];
            }
          $isql="INSERT INTO tbl_viewlog(uid,userName,email,product_id,vid,updatedby,udt,cdt)
                   VALUES(".$uid.",'".$udetail['uname']."','".$udetail['email']."',".$params['pid'].",".$params['vid'].",'customer',now(),now())";
            $ires=$this->query($isql);
            if($ires)
            {
                $arr="Log Entry is successfully completed";
                $err=array('Code'=>0,'Msg'=>'Data inserted');
            }
        else
        {
            $arr="Log entry is not done";
            $err=array('Code'=>0,'Msg'=>'Error in completing the operation');
        }
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
    # view log by vendor for his product being viewed
    
    public function viewLog($params)
    {
        # check the products under the requested vendor
        
        $page   = $params['page'];
        $limit  = $params['limit'];
        $viewprod="SELECT uid,userName,email,cdt,product_id from tbl_viewlog where vid=".$params['vid']."";
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $viewprod.=" LIMIT " . $start . ",$limit";
        }
        $viewres=$this->query($viewprod);
        $chkres=$this->numRows($viewres);
        if($chkres>0)
        {   
            while($row=$this->fetchData($viewres))
            {   
                $arr[]=$row;
            }
            $err=array('Code'=>0,'Msg'=>'Values fetched successfully');
        }
        else
        {
            $arr="No one has viewed your products yet";
            $err=array('Code'=>0,'Msg'=>'No Values fetched');
        }
        $result=array('result'=>$arr,'error'=>$err);
        return $result;
    }    
}
                