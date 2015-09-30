<?php

include APICLUDE.'common/db.class.php';
class wishlist extends DB
{
    
    function __construct($db)
    {
        parent::DB($db);
    }
    
   public function addtowsh($params)
   {
       $dt     = json_decode($params['dt'],1);
       $detls  = $dt['result'];
       $sql="INSERT INTO tbl_wishlist(uid,pid,vid,wf,cdt,udt) VALUES(".$detls['uid'].",".$detls['pid'].",".$detls['vid'].",1,now(),now())";
       $res=$this->query($sql);
       if($res)
       {
           $arr="Product inserted into wishlist";
           $err=array('Code'=>0,'Msg'=>'Insert operation done');
       }
       else
       {
           $arr="Product inserted into wishlist";
           $err=array('Code'=>0,'Msg'=>'Insert operation done');
       }
       $result=array('results'=>$arr,'error'=>$err);
       return $result;
   }
   
   public function viewsh($params)
   {
       $dt     = json_decode($params['dt'],1);
       $detls  = $dt['result'];
       $vsql="SELECT pid from tbl_wishlist where uid=".$detls['uid']."";
       $vres=$this->query($vsql);
       if($vres)
       {
           while($row=$this->fetchData($vres))
           {
               $pid[]=$row['pid'];
           }
            $pid=implode(',',$pid);
            
            $pres="SELECT product_name,product_display_name,product_model,prd_price from tbl_product_master where product_id IN(".$pid.")";    
            $pres=$this->query($pres);
            if($pres)
            {
                $arr="Product List is shown";
                $err=array('Code'=>0,'Msg'=>'Select operation done');
            }
            else
            {
                $arr="Error in fetching data";
                $err=array('Code'=>0,'Msg'=>'Select operation failed');
            }
       }
       else
       {
            $arr="No record found";
            $err=array('Code'=>0,'Msg'=>'Select operation failed');
       }       
       $result=array('result'=>$arr,'error'=>$err);
       return $result;
   }
    
}