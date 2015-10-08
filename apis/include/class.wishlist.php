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
       $vsql="SELECT pid from tbl_wishlist where uid=".$params['uid']."";
       $page=$params['page'];
       $limit=$params['limit'];
       if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $vsql.=" LIMIT " . $start . ",$limit";
        } 
         $vsql;
       $vres=$this->query($vsql);
       $chkres=$this->numRows($vres);
       
       if($chkres>0)
       {   $i=0;
       
           while($row=$this->fetchData($vres))
            {
               
            $prid[$i]=$row['pid'];
            
            }

            $pid=implode(',',$prid);
            
           $pres="SELECT product_name,product_display_name,product_model,prd_price,desname from tbl_product_master where product_id IN(".$pid.")";    
            $pres.=" LIMIT " . $start . ",$limit";
           
           $pres=$this->query($pres);
            
            if($pres)
            {
                while($row=$this->fetchData($pres))
                {
                    $arr=$row;
                }   
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