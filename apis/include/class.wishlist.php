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
       $sql="INSERT INTO tbl_wishlist(uid,pid,vid,wf,date_time) VALUES(\"".$detls['uid']."\",\"".$detls['pid']."\",\"".$detls['vid']."\",1,now())";
       $res=$this->query($sql);
       if($res)
       {
           $arr="Product inserted into wishlist";
           $err=array('Code'=>0,'Msg'=>'Insert operation done');
       }
       else
       {
           $arr="Product not inserted in wishlist";
           $err=array('Code'=>0,'Msg'=>'Insert operation Failed');
       }
       $result=array('results'=>$arr,'error'=>$err);
       return $result;
   }
   
   public function viewsh($params)
   {
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);
        $vsql="SELECT distinct pid from tbl_wishlist where uid=".$params['uid']."";
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $vsql.=" LIMIT " . $start . ",$limit";
        } 
        $vres=$this->query($vsql);
        $chkres=$this->numRows($vres);
        if($chkres>0)
        {    $i=0;
            while($row=$this->fetchData($vres))
            {
                $prid[$i]=$row['pid'];

            }
            $totalwishproduct=  count($prid);
           
            $pid=implode(',',$prid);
            $patsql="
                    SELECT
                            distinct product_id,
                            carat,
                            color,
                            certified,
                            shape,
                            clarity,
                            price,
                            polish,
                            symmetry,
                            cno,
                            gold_weight,
                            type,
                            metal
                    FROM 
                            tbl_product_search 
                    WHERE 
                            product_id IN(".$pid.")
                    AND            
                            active_flag=1
                    ORDER BY
                            field(product_id,".$pid.")
                    ";

            if (!empty($page))
            {
                    $start = ($page * $limit) - $limit;
                    $patsql.=" LIMIT " . $start . ",$limit";
            }
            $patres=$this->query($patsql);
            while($row2=$this->fetchData($patres))
            {
                    $prodid[] = $row2['product_id'];
                    $pid = $row2['product_id'];
                    $arr[$pid]['productsearch']=$row2;
            }
            if(!empty($prodid))
            {
                    $pid = $pids = implode(',',$prodid);
            }
            else
            {
                    $pid = $pids = '';
            }

            $psql = "
                            SELECT
                                    product_id as pid,
                                    barcode as pcode,
                                    product_name as pname,
                                    product_display_name as pdname,
                                    product_model as pmodel,
                                    product_brand as pbrand,
                                    prd_price as pprice,
                                    product_currency as pcur,
                                    prd_img as pimg
                            FROM 
                                    tbl_product_master 
                            WHERE 
                                    product_id IN(".$pid.")
                            AND
                                    active_flag=1
                            ORDER BY
                                    field(product_id,".$pid.");
                            ";
            $pres=$this->query($psql);
            while($row1=$this->fetchData($pres))
            {
                    $pid = $row1['pid'];
                    $arr[$pid]['productmaster']=$row1;
                    $arr[$pid]['totalcount'] = $totalwishproduct;
            }
        }
        else
        {
            $arr=array();
            $err=array('Code'=>1,'Msg'=>'Select operation failed');
        }
        $result=array('result'=>$arr,'error'=>$err);
        return $result;
   }
    
   public function checklist($params)
   {
       $sql="SELECT wid,wf FROM tbl_wishlist WHERE uid=".$params['uid']." AND pid=".$params['prdid']."&vid=".$params['vid']."";
       $res=$this->query($sql);
       if($res)
       {
           while($row=$this->fetchData($res))
           {
               $wid=$row['wid'];
               $wf=$row['wf'];
           }
           if(($wf==0)||($wf==2))
           {
               $upsql="UPDATE tbl_wishlist set wf=1 WHERE uid=".$params['uid']." AND pid=".$params['prdid'];
               $upres=$this->query($upsql);
               $arr="Product is now in wishlist";
               $err=array('Code'=>0,'Msg'=>'True');
           }
           else
           {
               $arr="Product is already in wishlist";
               $err=array('Code'=>0,'Msg'=>'True');
           }
       }
       else
       {
           $arr="Product is not in wishlist";
           $err=array('Code'=>0,'Msg'=>'False');
       }
       $result=array('results'=>$arr,'error'=>$err);
       return $result;
   }
   public function removeFromWishlist($params)
   {
       if(!$params['vid'])
           $sql="SELECT wid,wf FROM tbl_wishlist WHERE uid=".$params['uid']." AND pid=".$params['pid']."";
       else
           $sql="SELECT wid,wf FROM tbl_wishlist WHERE uid=".$params['uid']." AND vid=".$params['vid']." AND pid=".$params['pid']."";
       $res=$this->query($sql);
       if($res)
       {
           while($row=$this->fetchData($res))
           {
               $wid=$row['wid'];
               $wf=$row['wf'];
           }
           if($wf==1)
           {
                if(!$params['vid'])
                $upsql="UPDATE tbl_wishlist SET wf=2 WHERE uid=".$params['uid']."  AND pid=".$params['pid']."";
                else
                $upsql="UPDATE tbl_wishlist SET wf=2 WHERE uid=".$params['uid']." AND vid=".$params['vid']." AND pid=".$params['pid']."";
               $upres=$this->query($upsql,1);
               $arr="Product is removed from wishlist";
           }
           else
           {
               $arr="Product is not in wishlist";
           }
           $err=array('Code'=>0,'Msg'=>'Product removal from wishlist complete');
       }
       else
       {
           $arr="Product is not in wishlist";
           $err=array('Code'=>0,'Msg'=>'False');
       }
       $result=array('results'=>$arr,'error'=>$err);
       return $result;
   }

	public function checkWish($params)
	{
		$uid = (!empty($params['uid'])) ? trim(urldecode($params['uid'])) : '';
		$prdid = (!empty($params['prdid'])) ? trim(urldecode($params['prdid'])) : '';
		$vid = (!empty($params['vid'])) ? trim(urldecode($params['vid'])) : '';

		if(empty($uid))
		{
			$results = array();
			$error = array('Code' => 1, 'Msg' => 'user id is missing');
			$results = array('results' => $result, 'error' => $error);
			return $results;
		}

		$sql = "SELECT * FROM tbl_wishlist WHERE uid='$uid'";

		if(!empty($prdid))
		{
			$sql .= " AND pid='$prdid'";
		}

		if(!empty($vid))
		{
			$sql .= " AND vid='$vid'";
		}

		$res = $this->query($sql);

		$results = array();
		if($res)
		{
			while($row = $this->fetchData($res))
			{
				$results[] = $row;
			}
			$error = array('Code' => 0, 'Msg' => 'Wishlist fetched successfully');
		}
		else
		{
			$error = array('Code' => 1, 'Msg' => 'Error fetching wishlist');
		}

		$result = array('results' => $results, 'error' => $error);

		return $result;
	}
}
