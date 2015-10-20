<?php
include APICLUDE.'common/db.class.php';
class enquiry extends DB
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
        $udsql="SELECT 
                                logmobile,
                                user_name,
                                email
                FROM 
                                tbl_registration
                WHERE
                                user_id=".$uid."";
        $udres=$this->query($udsql);
        $chkres=$this->numRows($udres);
        if($chkres=1)
        {
            while($row1=$this->fetchData($udres)) 
            {
                $udetail['umobile']=$row1['logmobile'];
                $udetail['uname']=$row1['user_name'];
                $udetail['uemail']=$row1['email'];
            }
          $isql="INSERT
                 INTO 
                                    tbl_product_enquiry
                                   (user_id,
                                   user_name,
                                   user_mobile,
                                   user_email,
                                   product_id,
                                   vendor_id,
                                   user_ip_address,
                                   display_flag,
                                   updatedby,
                                   date_time)
                   VALUES
                                (".$uid.",
                                '".$udetail['uname']."',
                                '".$udetail['umobile']."',    
                                '".$udetail['uemail']."',
                                ".$params['pid'].",
                                ".$params['vid'].",
                               '".$params['ipaddress']."',
                               '".$params['dflag']."',
                                 'customer',
                                  now())";
            $ires=$this->query($isql);
            if($ires)
            {
                $arr="Log Entry is successfully completed";
                $err=array('Code'=>0,'Msg'=>'Data inserted');
            }
        else
        {
            $arr=array();
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
        
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);
        $viewprod="SELECT
                                   user_id,
                                   user_name,
                                   user_mobile,
                                   user_email,
                                   product_id,
                                   vendor_id,
                                   user_ip_address,
                                   display_flag,
                                   updatedby,
                                   date_time
                   FROM
                                    tbl_product_enquiry
                   WHERE
                                    vendor_id=".$params['vid']."";
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