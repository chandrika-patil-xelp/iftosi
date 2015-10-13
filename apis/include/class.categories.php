<?php

include APICLUDE.'common/db.class.php';
class categories extends DB
{
    function __construct($db) 
    {
            parent::DB($db);
    }

    public function set_lineage($params)
    {  
        $dt     = json_decode($params['dt'],1);
        $detls  = $dt['result'];
        
            $isql="INSERT INTO tbl_lineage(p_catid,cat_name,cat_lvl,lineage,product_flag) VALUES(".$detls['p_catid'].",'".$detls['catname']."',".$detls['lvl'].",'".$detls['lineage']."',0)";
            $ires=$this->query($isql);
            $catid=$this->lastInsertedId($ires);
            if($ires)
            {
                $ipsql="INSERT INTO tbl_product_category_mapping(product_id,category_id,pflag,date_time) VALUES(".$detls['pid'].",".$catid.",1,now())";
                $ipres=$this->query($ipsql);
                if($ipres)
                {
                   $upsql="UPDATE tbl_product_master set lineage='".$detls['lineage']."' WHERE product_id=".$detls['pid']."";
                    $upres=$this->query($upsql);
                    if($upres)
                    {
                        $arr="category product mapping is done";
                        $err=array('Code'=>0,'Msg'=>'Insert operation completed');
                    }
                    else
                    {
                        $arr="mapping Incomplete";
                        $err=array('Code'=>0,'Msg'=>'Error in updating');
                    }
                }
                else
                {
                    $arr="Insertion Incomplete";
                    $err=array('Code'=>0,'Msg'=>'Error in Insert');
                }
            }
            else
            {
                $arr="Insertion Incomplete";
                $err=array('Code'=>0,'Msg'=>'Error in Insert');
            }
            
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
    public function upd_prd_lineage($params)
    {
        $dt     = json_decode($params['dt'],1);
        $detls  = $dt['result'];
        
    $usql="UPDATE tbl_lineage SET p_catid='".$detls['pcatid']."',cat_name='".$detls['catname']."',cat_lvl=".$detls['lvl'].",lineage='".$detls['lineage']."',product_flag=".$detls['pflag']." WHERE catid=".$detls['catid']."";
        $ures=$this->query($usql);
        if($ures)
        {
           $csql="select product_id from tbl_product_category_mapping where category_id=".$detls['catid']."";
            $cres=$this->query($csql);
            if($cres)
            {
                while($row=$this->fetchData($cres))
                {
                    $pid[]=$row['product_id'];
                }
                $pid=implode(',',$pid);

                $upsql="UPDATE tbl_product_master set lineage='".$detls['lineage']."' WHERE product_id IN(".$pid.")";
                $upres=$this->query($upsql);
                if($upres)
                {
                    $arr="category-product mapping is Updated";
                    $err=array('Code'=>0,'Msg'=>'UPDATE operation completed');
                }
                else
                {
                    $arr="mapping not updated";
                    $err=array('Code'=>0,'Msg'=>'Error in updating');
                }
        }
        else
        {
            $arr="mapping Incomplete";
            $err=array('Code'=>0,'Msg'=>'Error in updating');
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
        
    }
}


}
?>
