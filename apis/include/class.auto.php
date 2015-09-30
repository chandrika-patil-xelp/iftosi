<?php
include APICLUDE.'common/db.class.php';
class auto extends DB
{
        function __construct($db) 
        {
                parent::DB($db);
        }

    public function searchbox($params)
    {
	$sql="SELECT product_id,product_display_name,product_model,product_brand,category_id,brand_id FROM tb_master_prd where product_name LIKE '%".$params['srch']."%' ORDER BY product_name DESC LIMIT 0,10";
        $res=$this->query($sql);
        if($this->numRows($res))
        {
            while($row=$this->fetchData($res))
            {
                $arrval['id']       = $row['product_id'];
                $arrval['name']     = $row['product_display_name'];
                $arrval['model']    = $row['product_model'];
                $arrval['brand']    = $row['product_brand'];
                $arrval['catid']    = $row['category_id'];
                $arrval['brandid']  = $row['brand_id'];
                $arr[]              =$arrval;
            }
            $err=array('Code'=> 0,'Msg'=>'Values are fetched');
        }
        else
        {
                $arr = "No records matched";
                $err = array('Code'=> 1,'Msg'=>'Search Query Failed');
        }
    $result=array('results'=>$arr,'error'=>$err);
    return $result;
    }
}
