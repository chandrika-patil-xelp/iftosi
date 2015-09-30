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
  
        $udsql="select mobile,user_name,email from tbl_registration where mobile=".$params['mobile']."";
        $udres=$this->query($udsql);
        $chkres=$this->numRows($udres);
        $j=-1;
        if($chkres)
        {
            while($row1=$this->fetchData($udres)) 
            {       $j++;
                    $udetail[]=$row1;
                    
            }
        
        $pdsql="select product_id,vendormobile from tbl_vendor_product_mapping where product_id=".$params['product_id']." AND active_flag=1";
        $pdres=$this->query($pdsql);
        $i=-1;
        while($row2 = $this->fetchData($pdres)) 
        {       $i++;
                $prod[]=$row2;
        }
        $isql="INSERT INTO viewlog(umob,userName,email,product_id,vendormobile,updatedby,udt,cdt)
               VALUES(".$udetail[$j]['mobile'].",'".$udetail[$j]['user_name']."','".$udetail[$j]['email']."',".$prod[$i]['product_id'].",".$prod[$i]['vendormobile'].",'customer',now(),now())";
        
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
       $viewprod="SELECT umob,userName,email,cdt,product_id,vendormobile from viewlog where vendormobile=".$params['logmobile'];
        $viewres=$this->query($viewprod);
        $chkres=$this->numRows($viewres);
        if($chkres>0)
        {   
            while($row=$this->fetchData($viewres))
            {   
                $arrL['username']=$row['userName'];
                $arrL['mobile']=$row['umob'];
                $arrL['email']=$row['email'];
                $arrL['cdt']=$row['cdt'];                
                $arrL['product_id']=$row['product_id'];
                $arr[]=$arrL;
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
                