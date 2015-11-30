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
            
            $chksql="SELECT user_id FROM tbl_product_enquiry where user_id=".$uid." AND product_id=".$params['pid']." and vendor_id=".$params['pid'].""; 
            $chkres=$this->query($chksql);
            $numchk=$this->numRows($chkres);
                if($numchk<1)
                 {
                 $isql="INSERT
                 INTO 
                                    tbl_product_enquiry
                                   (user_id,
                                   user_name,
                                   user_mobile,
                                   user_email,
                                   product_id,
                                   vendor_id,
                                   type_flag,
                                   updatedby,
                                   date_time)
                   VALUES
                                (".$uid.",
                                \"".$udetail['uname']."\",
                                \"".$udetail['umobile']."\",    
                                \"".$udetail['uemail']."\",
                                \"".$params['pid']."\",
                                \"".$params['vid']."\",
                                  1,
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
        $viewprod=" SELECT
                                   user_id,
                                   user_name,
                                   user_mobile,
                                   user_email,
                                   product_id,
                                   vendor_id,
                                   user_ip_address,
                                   type_flag,
                                   updatedby,
                                   update_time
                    FROM
                                    tbl_product_enquiry
                    WHERE
                                    vendor_id=".$params['vid']."
                    ORDER BY
                                    update_time
                ";
        
        
        $viewres=$this->query($viewprod);
        $totalEnqs=$this->numRows($viewres);
        $arr['total_enqs']=$totalEnqs;
        $total_pages = ceil($totalEnqs/$limit);
        $arr['total_pages']=$total_pages;
        
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
                $csql='SELECT barcode, product_name, prd_price FROM tbl_product_master WHERE product_id='.$row['product_id'].'';
                $cres=$this->query($csql);
                if($this->numRows($cres)>0) {
                    while($crow=$this->fetchData($cres))
                    {
                        $row['pro_dtls']=$crow;
                    }
                }
                $csql='SELECT b.cat_name,b.catid FROM tbl_product_category_mapping AS a, tbl_category_master AS b WHERE a.product_id='.$row['product_id'].' AND b.catid=a.category_id AND b.p_catid in (0,10000,10001,10002)';
                $cres=$this->query($csql);
                if($this->numRows($cres)>0) {
                    while($crow=$this->fetchData($cres))
                    {
                        $row['pro_dtls']['cat_name'][]=$crow['cat_name'];
                        if($crow['catid']==10000 || $crow['catid']==10001 || $crow['catid']==10002) {
                            $row['pro_dtls']['mCatid']=$crow['catid'];
                        }
                    }
                }
                $arr['enq'][]=$row;
            }
            $err=array('Code'=>0,'Msg'=>'Values fetched successfully');
        }
        else
        {
            $arr=array('total_enqs' => $totalEnqs, 'total_pages' => $total_pages);
            $err=array('Code'=>0,'Msg'=>'No Values fetched');
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }    
}