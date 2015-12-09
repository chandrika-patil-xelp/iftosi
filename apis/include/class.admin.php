<?php

include APICLUDE.'common/db.class.php';
class admin extends DB
{
    
    function __construct($db)
    {
        parent::DB($db);
    }
	
    public function getProdList($params)
    {
        $page = (!empty($params['page']) ? $params['page'] : 1);
        $limit = (!empty($params['limit']) ? $params['limit'] : 15);

        $subQuery = "SELECT 
                product_id AS id,
                count(product_id) AS total_img,
                sum(active_flag = 0) as pend_img,
                sum(active_flag = 1) as appr_img,
                sum(active_flag = 3) as rej_img
            FROM
                tbl_product_image_mapping
            group by product_id
            order by product_id";
        
        $cntRes = $this->query($subQuery);
        $total_products = $this->numRows($cntRes);
        if (!empty($page)) {
            $start = ($page * $limit) - $limit;
            $subQuery.=" LIMIT " . $start . ",$limit";
        }
        $subQueryRes = $this->query($subQuery);
        if($this->numRows($subQueryRes)>0) {
            while ($row = $this->fetchData($subQueryRes)) {
                $productIDs[] = $row['product_id'];
                $isql = "SELECT barcode, update_time FROM tbl_product_master  WHERE product_id = ".$row['id'];
                $ires = $this->query($isql);
                if( $this->numRows($ires)>0) {
                    $irow = $this->fetchData($ires);
                    $row['barcode']=$irow['barcode'];
                    $row['update_time']=$irow['update_time'];
                }
                $result[]=$row;
            }
            $results=array('products'=>$result,"total_products"=>$total_products);
            $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
        } else {
            $results = '';
            $err = array('Code' => 1, 'Msg' => 'Error in fetching data');
        }
        $result=array('results'=>$results,'error'=>$err);
        return $result;
    }
    
    public function getImgByProd($params)
    {
        $query="SELECT * FROM tbl_product_image_mapping WHERE product_id = ".$params['pid']." ORDER BY image_sequence ";
        $res = $this->query($query);
        $total=$this->numRows($res);
        if($total>0) {
            while ($row = $this->fetchData($res)) {
                
                $result[]=$row;
            }
            $results=array('imgs'=>$result,"total_imgs"=>$total);
            $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
        } else {
            $results = '';
            $err = array('Code' => 1, 'Msg' => 'Error in fetching data');
        }
        $result=array('results'=>$results,'error'=>$err);
        return $result;
    }
    public function updateImageData($params)
    {
        $query="UPDATE tbl_product_image_mapping SET ";
        
        if(!empty($params['seq'])) {
            $query .="image_sequence='".$params['seq']."', ";
        }
        if(!empty($params['flag'])) {
            $query .="active_flag='".$params['flag']."', ";
        }
        if(!empty($params['rea'])) {
            $query .="reason='".$params['rea']."', ";
        }
        $query .=" update_date=NOW() WHERE id = ".$params['id'];
        $res = $this->query($query);
        if($res) {
            $results=array();
            $err = array('Code' => 0, 'Msg' => 'Updated successfully');
        } else {
            $results=array();
            $err = array('Code' => 1, 'Msg' => 'Error in Updating data');
        }
        $result=array('results'=>$results,'error'=>$err);
        return $result;
    }
}
