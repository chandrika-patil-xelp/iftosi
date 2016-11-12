<?php
include APICLUDE.'common/db.class.php';
class vendor extends DB
{
    function __construct($db)
    {
        parent::DB($db);
    }
    public function vendorList($params)
    {  
        $page   = ($params['pgno'] ? $params['pgno'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 50);


        $total_products = 0;
        $sql = "SELECT *,date_format(profile_expiry_date,'%D %M, %Y') as expiry_show FROM tbl_vendor_master WHERE is_complete=2 ORDER BY date_time DESC";

        if (!empty($page)) {
            $start = ($page * $limit) - $limit;
            $sql.=" LIMIT " . $start . ",$limit";
        }
       // echo $sql;die;
        $res = $this->query($sql);
        $total = $this->numRows($res);

        $arr = array();
        if($res)
        {
            while($row = $this->fetchData($res))
            {
                if(empty($row['profile_expiry_date']) || $row['profile_expiry_date'] == '0000-00-00 00:00:00')
                {
                    $row['expiry_show'] = 'Not Available';
                }

                $arr[] = $row;
            }
            $results=array('vendors'=>$arr,"total_vendors"=>$total);
            $err = array('Code' => 0, 'Msg' => 'Vendor details fetched successfully!');
        }
        else {
            $err = array('Code' => 1, 'Msg' => 'Something went wrong');
        }
        $result = array('results' => $results, 'error' => $err);
        return $result;
    }

    public function getVendorBySearch($params)
    {
        $page   = ($params['pgno'] ? $params['pgno'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 50);


        $total_vendors = 0;

        $sql = "    SELECT
                                *,
                                date_format(profile_expiry_date,'%D %M, %Y') as expiry_show
                    FROM
                                tbl_vendor_master
                    WHERE
                                (
                                    MATCH(orgName) AGAINST('" . urldecode($params['srchTxt']) . "*' IN BOOLEAN MODE)
                    OR
                                    MATCH(email) AGAINST('" . urldecode($params['srchTxt']) . "*' IN BOOLEAN MODE)
                    OR
                                    MATCH(contact_mobile) AGAINST('" . urlencode($params['srchTxt']) . "*' IN BOOLEAN MODE)
                    OR
                                    MATCH(city) AGAINST('" . urlencode($params['srchTxt']) . "*' IN BOOLEAN MODE)
                                )
                    AND
                                    is_complete = 2
                    ORDER BY
                                date_time
                    DESC";

        if (!empty($page)) {
            $start = ($page * $limit) - $limit;
            $sql.=" LIMIT " . $start . ",$limit";
        }

        $res = $this->query($sql);
        $total = $this->numRows($res);

        $arr = array();
        if($res)
        {
            while($row = $this->fetchData($res))
            {
                if(empty($row['profile_expiry_date']) || $row['profile_expiry_date'] == '0000-00-00 00:00:00')
                {
                    $row['expiry_show'] = 'Not Available';
                }

                $arr[] = $row;
            }
            $results=array('vendors'=>$arr,"total_vendors"=>$total);
            $err = array('Code' => 0, 'Msg' => 'Vendor details fetched successfully!');
        }
        else {
            $err = array('Code' => 1, 'Msg' => 'Something went wrong');
        }

        $result = array('results' => $results, 'error' => $err);
        return $result;
    }

    public function addVendorPrdInfo($params)
    {
        $dt= json_decode($params['dt'],1);
        $detls  = $dt['result'];

        $sql="SELECT city from tbl_vendor_master where active_flag=1 and vendor_id=\"".$detls['vid']."\"";
        $res=$this->query($sql);
        $row=$this->fetchData($res);
        $city=$row['city'];


        $sql="INSERT INTO tbl_vendor_product_mapping(product_id,vendor_id,vendor_price,vendor_quantity,vendor_currency,vendor_remarks,city,active_flag,updatedby,date_time)";
        $sql.="VALUES(".$detls['pid'].",".$detls['vid'].",".$detls['vp'].",".$detls['vq'].",'".$detls['vc']."','".$detls['vr']."',".$city.",".$detls['af'].",'vendor',now())";
        $res = $this->query($sql);
        if($res)
        {
            $arr="Product mapping is done successfully";
            $err = array('Code' => 0, 'Msg' => 'Product details added successfully!');
        }
        else
        {
            $arr=array();
            $err = array('Code' => 1, 'Msg' => 'Product details Not Added!');
        }
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }

    public function getOwnerCheck($params)
    {
        $sql="  SELECT
                        vendor_id
                FROM
                        tbl_vendor_product_mapping
                WHERE
                        product_id = \"".$params['pid']."\"
                AND
                        vendor_id=\"".$params['uid']."\"";

        $res=$this->query($sql);
        $row=$this->fetchData($res);

        if($row['vendor_id'] == $params['uid'])
        {
            $err = array('code' => 1, 'msg' => 'Product is of same vendor');
        }
        else
        {
            $err = array('code' => 0, 'msg' => 'Product and vendor are different');
        }
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }

    public function getVproducts($params)
    {
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);


        $total_products = 0;

        $cnt_sql = "SELECT
                            COUNT(1) as cnt
                    FROM
                            tbl_vendor_product_mapping
                    WHERE
                            active_flag!=2
                    AND
                            vendor_id =".$params['vid'];
        $cnt_res = $this->query($cnt_sql);

        $chkcnt=$this->numRows($cnt_res);
         if($chkcnt>0)
        {
            $vsql="SELECT
                            product_id,
                            vendor_price,
                            vendor_quantity,
                            vendor_currency,
                            city,
                            active_flag
                   from
                            tbl_vendor_product_mapping
                   WHERE
                            active_flag <> 2
                   AND
                            vendor_id=".$params['vid'];
            if (!empty($page))
            {
                $start = ($page * $limit) - $limit;
                $vsql.=" LIMIT " . $start . ",$limit";
            }
            $vres=$this->query($vsql);
            $prsql.=" LIMIT " . $start . ",$limit";

            $i=-1;
            $vpmap=array();
            while($row1=$this->fetchData($vres))
            {   
                $i++;
                $vpmap['product_id'][$i]=$row1['product_id'];
                $vpmap['vendor_price'][$i]=$row1['vendor_price'];
                $vpmap['vendor_quantity'][$i]=$row1['vendor_quantity'];
                $vpmap['vendor_currency'][$i]=$row1['vendor_currency'];
                $vpmap['vendor_city'][$i]=$row1['city'];
                $vpmap['active_flag'][$i]=$row1['active_flag'];
                $vmap[]=$vpmap;
            }
            $vmapProd=implode(',',$vmap[$i]['product_id']);

            $prsql="SELECT
                            product_id,
                            product_name,
                            product_display_name,
                            product_model,
                            product_brand,
                            prd_img,
                            desname
                    FROM
                            tbl_product_master
                    WHERE
                            product_id IN(".$vmapProd.")
                    AND
                            active_flag=1
                    OR
                            active_flag=0";
            $prsql.=" LIMIT " . $start . ",$limit";

            $pres=$this->query($prsql);
            $j=-1;
            $preslt=array();
            while ($prow = $this->fetchData($pres))
            {       $j++;

                    $preslt['product_name'][$j]         = $prow['product_name'];
                    $preslt['product_display_name'][$j] = $prow['product_display_name'];
                    $preslt['product_model'][$j]        = $prow['product_model'];
                    $preslt['product_brand'][$j]        = $prow['product_brand'];
                    $preslt['product_image'][$j]        = $prow['prd_img'];
                    $preslt['desname'][$j]              = $prow['desname'];
                    $presults[] = $preslt;
            }
            if($cnt_res)
            {
                $cnt_row = $this->fetchData($cnt_res);
                if($cnt_row && !empty($cnt_row['cnt']))
                {
                $total_products = $cnt_row['cnt'];
                }
            }
            $arr=array('productdet'=>$presults[$j],'vendor_prod'=>$vmap[$i],'total_products'=>$total_products);
            $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
          }
             else
             {
                $arr=array();
                $err=array('Code'=>1,'Msg'=>'Error in fetching data');
             }
            $result = array('results'=>$arr,'error'=> $err);
            return $result;
    }


    public function getVProductsByBcode($params)
    {
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);
        $catid = ($params['catid'] ? $params['catid'] : 10000);
        $total_pages = $chkcnt = $total_products = 0;
        $sql1="SELECT
                      silver_rate,
                      gold_rate,
                      dollar_rate
               FROM
                      tbl_vendor_master
               WHERE
                      vendor_id='".$params['vid']."'";
        $res1=$this->query($sql1);
        if ($res1)
        {
            $rates = $this->fetchData($res1);
            $dollarValue=dollarValue;
            if(!empty($rates['dollar_rate']) && $rates['dollar_rate']!='0.00')
            {
                $dollarValue = $rates['dollar_rate'];
            }
            $goldRate=goldRate;
            if(!empty($rates['gold_rate']) && $rates['gold_rate']!='0.00')
            {
                $goldRate = $rates['gold_rate'];
            }
            $silverRate=silverRate;
            if(!empty($rates['silver_rate']) && $rates['silver_rate']!='0.00')
            {
                $silverRate = $rates['silver_rate'];
            }
        }

        $chkpidsinvid="SELECT
                                product_id
                       FROM
                                tbl_vendor_product_mapping
                       WHERE
                                vendor_id=".$params['vid']."";
        $respidsinvid=$this->query($chkpidsinvid);
        $chkcnt=$this->numRows($respidsinvid);
        if($chkcnt>0)
        {
            $k=0;
            while($row=$this->fetchData($respidsinvid))
            {
                $pdet1[]=$row['product_id'];
                $prDetails[]=$pdet;
            }
            $pId = implode(',',$pdet1);
            $psql='';
            if($catid == 10000)
            {
                $psql='d.color, d.carat, d.shape, d.certified AS cert, d.clarity,d.price,d.b2b_price';
            }
            else if($catid == 10001)
            {
                $psql='d.shape,d.metal,c.lotref,d.price,d.gold_weight,d.dwt as dwt';
            }
            else if($catid == 10002)
            {
                $psql='d.type, d.metal,d.bullion_design as bullion_design,d.gold_purity as gold_purity, d.gold_weight as gold_weight';
            }
                $sql = "SELECT
                                    DISTINCT a.product_id AS id,
                                    c.product_name,
                                    a.vendor_price AS price,
                                    c.barcode,
                                    c.update_time,
                                    c.active_flag,
                                    ".$psql."
                        FROM
                                    tbl_vendor_product_mapping AS a,
                                    tbl_product_category_mapping AS b,
                                    tbl_product_master AS c,
                                    tbl_product_search AS d
                        WHERE
                                    a.product_id=b.product_id
                        AND
                                    a.product_id=c.product_id
                        AND
                                    a.product_id=d.product_id
                        AND
                                    b.category_id = " . $catid . "
                        AND
                                    a.vendor_id=" . $params['vid'] . "
                        AND
                                  (
                                      MATCH(c.barcode) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(d.shape) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      d.color LIKE '" . $params['bcode'] . "%'
                                    OR
                                      MATCH(d.clarity) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(dwt) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(d.metal) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(d.carat) AGAINST('".$params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      d.price LIKE '".$params['bcode']."%'
                                    OR
                                      d.b2b_price LIKE '".$params['bcode']."%'
                                    OR
                                      MATCH(d.type) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(bullion_design) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(gold_purity) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(gold_weight) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      d.certified LIKE '" . $params['bcode'] . "%'
                                    OR
                                      d.product_id = '".$params['bcode']."'
                                  )
                        AND
                                  d.active_flag <> 2
                        AND
                                  a.product_id IN(".$pId.")
                        ORDER BY
                                    id";
		$res = $this->query($sql);
		$total_products = $this->numRows($res);
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $sql.=" LIMIT " . $start . ",$limit";
        }
        $res = $this->query($sql);

        if($total_products>0)
        {
            $j=0;
            while ($row1=$this->fetchData($res))
            {
                if ($catid == 10001)
                {
                    $csql="SELECT
                                    p_catid,
                                    cat_name
                           FROM
                                    tbl_category_master
                           WHERE
                                    catid in (SELECT category_id FROM `tbl_product_category_mapping` WHERE `product_id`=".$row1['id']." )
                           ORDER BY
                                    p_catid DESC";
                    $cres = $this->query($csql);
                    if($this->numRows($cres)>0)
                    {
                        $row2=array();
                        while ($crow = $this->fetchData($cres))
                        {
                            array_push($row2, $crow);
                        }
                        $row1['category']=$row2;
                    }
                }
                if ($catid == 10000)
                {
                    $price= $row1['price'];
                    $priceb2b= $row1['b2b_price'];
                }
                if ($catid == 10001)
                {
                    $price1 = ceil($row1['price']);
                    $price = $this->IND_money_format($price1);
                }
                if ($catid == 10002)
                {
                    $purity=$row1['gold_purity'];
                    $metal=strtolower($row1['metal']);
                    $weight=$row1['gold_weight'];
                    $metalRate=$silverRate;
                    if($metal=='gold')
                    {
                        $metalRate=$goldRate;
                        $rate = ((($metalRate/995)*$purity)/10)*$weight;
                        $price1 = ceil($rate);
                        $price = $this->IND_money_format($price1);
                    }
                    else if($metal=='silver')
                    {
                        $metalRate = $silverRate;
                        $finalRate = ($metalRate/1000)*($purity/999);
                        $price1 = ceil($finalRate*$weight);
                        $price = $this->IND_money_format($price1);
                    }
                }
                $arr1[$j]['id']=$row1['id'];
                $arr1[$j]['color']=$row1['color'];
                $arr1[$j]['carat']=$row1['carat'];
                $arr1[$j]['shape']=$row1['shape'];
                $arr1[$j]['cert']=$row1['cert'];
                $arr1[$j]['clarity']=$row1['clarity'];
                $arr1[$j]['shape']=$row1['shape'];
                $arr1[$j]['metal']=$row1['metal'];
                $arr1[$j]['lotref']=$row1['lotref'];
                $arr1[$j]['gold_weight']=$row1['gold_weight'];
                $arr1[$j]['dwt']=$row1['dwt'];
                $arr1[$j]['type']=$row1['type'];
                $arr1[$j]['gold_purity']=$row1['gold_purity'];
                $arr1[$j]['gold_weight']=$row1['gold_weight'];
                $arr1[$j]['update_time']=$row1['update_time'];
                $arr1[$j]['active_flag']=$row1['active_flag'];
                $arr1[$j]['price']=$price;
                $arr1[$j]['b2b_price']=$priceb2b;
                $arr1[$j]['product_name']=$row1['product_name'];
                $arr1[$j]['barcode']=$row1['barcode'];
                $arr1[$j]['bullion_design']=$row1['bullion_design'];
                $j++;
            }
            $arr = array('total_products' => $total_products, 'total_pages' => $total_pages, 'products' => $arr1);
            $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
        }
        else
        {
            $arr=array('total_products' => $total_products, 'total_pages' => $total_pages);
            $err = array('Code' => 0, 'Msg' => 'No Match Found');
        }
     }
     else
      {
          $arr = array('total_products' => $total_products, 'total_pages' => $total_pages);
          $err = array('Code' => 0, 'Msg' => 'No Match Found');
      }

        $result = array('results' => $arr,'error' => $err);
        return $result;
    }

    public function getVPendingSearch($params)
    {
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);
        $catid = ($params['catid'] ? $params['catid'] : 10000);
        $total_pages = $chkcnt = $total_products = 0;

        $sql1="SELECT
                      silver_rate,
                      gold_rate,
                      dollar_rate
               FROM
                      tbl_vendor_master
               WHERE
                      vendor_id='".$params['vid']."'";
        $res1=$this->query($sql1);
        if ($res1)
        {
            $rates = $this->fetchData($res1);
            $dollarValue=dollarValue;
            if(!empty($rates['dollar_rate']) && $rates['dollar_rate']!='0.00')
            {
                $dollarValue = $rates['dollar_rate'];
            }
            $goldRate=goldRate;
            if(!empty($rates['gold_rate']) && $rates['gold_rate']!='0.00')
            {
                $goldRate = $rates['gold_rate'];
            }
            $silverRate=silverRate;
            if(!empty($rates['silver_rate']) && $rates['silver_rate']!='0.00')
            {
                $silverRate = $rates['silver_rate'];
            }
        }

        $chkpidsinvid="SELECT
                                product_id
                       FROM
                                tbl_vendor_product_mapping
                       WHERE
                                vendor_id=".$params['vid']."";
        $respidsinvid=$this->query($chkpidsinvid);
        $chkcnt=$this->numRows($respidsinvid);
        if($chkcnt>0)
        {
            $k=0;
            while($row=$this->fetchData($respidsinvid))
            {
                $pdet1[]=$row['product_id'];
                $prDetails[]=$pdet;
            }
            $pId = implode(',',$pdet1);
            $psql='';
            if($catid == 10000)
            {
                $psql='d.color, d.carat, d.shape, d.certified AS cert, d.clarity,d.price,d.b2b_price';
            }
            else if($catid == 10001)
            {
                $psql='d.shape,d.metal,c.lotref,d.price,d.gold_weight,d.dwt as dwt';
            }
            else if($catid == 10002)
            {
                $psql='d.type, d.metal,d.bullion_design as bullion_design,d.gold_purity as gold_purity, d.gold_weight as gold_weight';
            }
                $sql = "SELECT
                                    DISTINCT a.product_id AS id,
                                    c.product_name,
                                    a.vendor_price AS price,
                                    c.barcode,
                                    c.update_time,
                                    c.active_flag,
                                    ".$psql."
                        FROM
                                    tbl_vendor_product_mapping AS a,
                                    tbl_product_category_mapping AS b,
                                    tbl_product_master AS c,
                                    tbl_product_search AS d
                        WHERE
                                    a.product_id=b.product_id
                        AND
                                    a.product_id=c.product_id
                        AND
                                    a.product_id=d.product_id
                        AND
                                    b.category_id = " . $catid . "
                        AND
                                    a.vendor_id=" . $params['vid'] . "
                        AND
                                  (
                                      MATCH(c.barcode) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(d.shape) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      d.color LIKE '" . $params['bcode'] . "%'
                                    OR
                                      MATCH(d.clarity) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(dwt) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(d.metal) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(d.carat) AGAINST('".$params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      d.price LIKE '".$params['bcode']."%'
                                    OR
                                      d.b2b_price LIKE '".$params['bcode']."%'
                                    OR
                                      MATCH(d.type) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(bullion_design) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(gold_purity) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      MATCH(gold_weight) AGAINST('" . $params['bcode'] . "*' IN BOOLEAN MODE)
                                    OR
                                      d.certified LIKE '" . $params['bcode'] . "%'
                                    OR
                                      d.product_id = '".$params['bcode']."'
                                  )
                        AND
                                  d.active_flag <> 2
                        AND
                                  d.complete_flag = 0
                        AND
                                  a.product_id IN(".$pId.")
                        ORDER BY
                                    id";
		$res = $this->query($sql);
		$total_products = $this->numRows($res);
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $sql.=" LIMIT " . $start . ",$limit";
        }
        $res = $this->query($sql);

        if($total_products>0)
        {
            $j=0;
            while ($row1=$this->fetchData($res))
            {
                if ($catid == 10001)
                {
                    $csql="SELECT
                                    p_catid,
                                    cat_name
                           FROM
                                    tbl_category_master
                           WHERE
                                    catid in (SELECT category_id FROM `tbl_product_category_mapping` WHERE `product_id`=".$row1['id']." )
                           ORDER BY
                                    p_catid DESC";
                    $cres = $this->query($csql);
                    if($this->numRows($cres)>0)
                    {
                        $row2=array();
                        while ($crow = $this->fetchData($cres))
                        {
                            array_push($row2, $crow);
                        }
                        $row1['category']=$row2;
                    }
                }
                if ($catid == 10000)
                {
                    $price= $row1['price'];
                    $priceb2b= $row1['b2b_price'];
                }
                if ($catid == 10001)
                {
                    $price1 = ceil($row1['price']);
                    $price = $this->IND_money_format($price1);
                }
                if ($catid == 10002)
                {
                    $purity=$row1['gold_purity'];
                    $metal=strtolower($row1['metal']);
                    $weight=$row1['gold_weight'];
                    $metalRate=$silverRate;
                    if($metal=='gold')
                    {
                        $metalRate=$goldRate;
                        $rate = ((($metalRate/995)*$purity)/10)*$weight;
                        $price1 = ceil($rate);
                        $price = $this->IND_money_format($price1);
                    }
                    else if($metal=='silver')
                    {
                        $metalRate = $silverRate;
                        $finalRate = ($metalRate/1000)*($purity/999);
                        $price1 = ceil($finalRate*$weight);
                        $price = $this->IND_money_format($price1);
                    }
                }
                $arr1[$j]['id']=$row1['id'];
                $arr1[$j]['color']=$row1['color'];
                $arr1[$j]['carat']=$row1['carat'];
                $arr1[$j]['shape']=$row1['shape'];
                $arr1[$j]['cert']=$row1['cert'];
                $arr1[$j]['clarity']=$row1['clarity'];
                $arr1[$j]['shape']=$row1['shape'];
                $arr1[$j]['metal']=$row1['metal'];
                $arr1[$j]['lotref']=$row1['lotref'];
                $arr1[$j]['gold_weight']=$row1['gold_weight'];
                $arr1[$j]['dwt']=$row1['dwt'];
                $arr1[$j]['type']=$row1['type'];
                $arr1[$j]['gold_purity']=$row1['gold_purity'];
                $arr1[$j]['gold_weight']=$row1['gold_weight'];
                $arr1[$j]['update_time']=$row1['update_time'];
                $arr1[$j]['active_flag']=$row1['active_flag'];
                $arr1[$j]['price']=$price;
                $arr1[$j]['b2b_price']=$priceb2b;
                $arr1[$j]['product_name']=$row1['product_name'];
                $arr1[$j]['barcode']=$row1['barcode'];
                $arr1[$j]['bullion_design']=$row1['bullion_design'];
                $j++;
            }
            $arr = array('total_products' => $total_products, 'total_pages' => $total_pages, 'products' => $arr1);
            $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
        }
        else
        {
            $arr=array('total_products' => $total_products, 'total_pages' => $total_pages);
            $err = array('Code' => 0, 'Msg' => 'No Match Found');
        }
     }
     else
      {
          $arr = array('total_products' => $total_products, 'total_pages' => $total_pages);
          $err = array('Code' => 0, 'Msg' => 'No Match Found');
      }

        $result = array('results' => $arr,'error' => $err);
        return $result;
    }

    public function IND_money_format($money)
    {
        $len = strlen($money);
        $m = '';
        $money = strrev($money);
        for($i=0;$i<$len;$i++)
        {
            if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i != $len)
            {
                $m .=',';
            }
            $m .=$money[$i];
        }
        return strrev($m);
    }


    public function updateProductInfo($params)
    {
        $sql = "UPDATE
                        tbl_vendor_product_mapping
                SET
                        vendor_price=".$params['vp'].",
                        vendor_quantity=".$params['vq'].",updatedby='vendor',
                        active_flag=".$params['af']."
                WHERE
                        vendor_id=".$params['vid']."
                AND
                        product_id=".$params['pid']."";
        $res=$this->query($sql);
        if($res)
        {
            $arr="Vendor Product Map table updated";
            $err = array('Code' => 0, 'Msg' => 'Details updated successfully');
        }
        else
        {
            $arr=array();
            $err = array('Code' => 1, 'Msg' => 'Update unsuccessfull');
        }
        $result = array('results'=>$arr,'error'=>$err);
        return $result;
    }

    public function getVDetailByVidPid($params)
    {
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);

        $sql1="SELECT
                      *
               FROM
                      tbl_vendor_product_mapping
               WHERE
                      product_id=".$params['pid']."
               AND
                      vendor_id=".$params['vid']."
               AND
                      active_flag=1
               ORDER BY
                      date_time ASC";
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $sql1.=" LIMIT " . $start . ",$limit";
        }
        $res1=$this->query($sql1);
        $chkcnt=$this->numRows($res1);
        if($chkcnt>0)
        {
            while($row=$this->fetchData($res1))
            {
                $vdet['vendor_price']=$row['vendor_price'];
                $vdet['vendor_quantity']=$row['vendor_quantity'];
                $vdet['vendor_currency']=$row['vendor_currency'];
                $vdet['vendor_remarks']=$row['vendor_remarks'];
                $vdet['vendor_city']=$row['city'];
                $vdetls[]=$vdet;
            }
            $sql2="SELECT
                            orgName as OrganisationName,
                            fulladdress,
                            postal_code,
                            telephones,
                            alt_email,
                            officecity as office_city,
                            officecountry as office_country,
                            contact_person,
                            position,
                            contact_mobile,
                            email,
                            memship_Cert as Membership_Certificate,
                            bdbc as bharat_Diamond_Bource_Certificate,
                            other_bdbc as other_Bharat_Diamond_Bource_Certificate,
                            vatno as Vat_Number,
                            landline,
                            mdbw as membership_of_other_diamond_bourses_around_world,
                            website,
                            banker as bankers,
                            pancard,
                            turnover,
                            lat as latitude,
                            lng as longitude

                    FROM
                                tbl_vendor_master
                    WHERE
                                vendor_id =".$params['vid']."";
            if (!empty($page))
            {
                $start = ($page * $limit) - $limit;
                $sql2.=" LIMIT " . $start . ",$limit";
            }
            $res2=$this->query($sql2);

            if($this->numRows($res2)>0)
            {
                while ($row1=$this->fetchData($res2))
                {
                    $vresult[] = $row1;
                }
                $arr=array('Vendor-Detail'=>$vresult,'Vendor-Product'=>$vdetls);
                $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
            }
            else
            {
                $arr=array();
                $err = array('Code' => 1, 'Msg' => 'No Match Found');
            }
        }
        else
        {
            $arr=array();
            $err = array('Code' => 0, 'Msg' => 'No Match Found');
        }
        $result = array('results'=>$arr,'error'=>$err);
        return $result;
    }

    public function getVDetailByPid($params)
    {
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);

        $sql1="SELECT
                      *
               FROM
                      tbl_vendor_product_mapping
               WHERE
                      product_id=".$params['pid']."
               active_flag=1
                      AND
               ORDER BY
                      date_time ASC";
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $sql1.=" LIMIT " . $start . ",$limit";
        }
        $res1=$this->query($sql1);
        $chkcnt=$this->numRows($res1);
        if($chkcnt>0)
        {
            while($row=$this->fetchData($res1))
            {
                $vid[] = $row['vendor_id'];
                $vdet['vendor_id'] = $row['vendor_id'];
                $vdet['vendor_price']=$row['vendor_price'];
                $vdet['vendor_quantity']=$row['vendor_quantity'];
                $vdet['vendor_currency']=$row['vendor_currency'];
                $vdet['vendor_remarks']=$row['vendor_remarks'];
                $vdet['vendor_city']=$row['city'];
                $vdetls[]=$vdet;
            }

            $vid = implode(',',$vid);

            $sql2="SELECT
                            vendor_id AS vid,
                            orgName as OrganisationName,
                            fulladdress,
                            postal_code,
                            telephones,
                            alt_email,
                            officecity as office_city,
                            officecountry as office_country,
                            contact_person,
                            position,
                            contact_mobile,
                            email,
                            memship_Cert as Membership_Certificate,
                            bdbc as bharat_Diamond_Bource_Certificate,
                            other_bdbc as other_Bharat_Diamond_Bource_Certificate,
                            vatno as Vat_Number,
                            landline,
                            mdbw as membership_of_other_diamond_bourses_around_world,
                            website,
                            banker as bankers,
                            pancard,
                            turnover,
                            lat as latitude,
                            lng as longitude

                    FROM
                                tbl_vendor_master
                    WHERE
                                vendor_id IN(".$vid.")
                    ORDER BY
                                field(vendor_id,".$vid.")";
            if (!empty($page))
            {
            $start = ($page * $limit) - $limit;
            $sql2.=" LIMIT " . $start . ",$limit";
            }

            $res2=$this->query($sql2);
            if($this->numRows($res2) > 0)
            {
                while ($row2=$this->fetchData($res2))
                {
                    $arr1[$row2['vid']] = $row2;
                }
                $arr=array('Vendor-Detail'=>$arr1,'Vendor-Product'=>$vdetls);
                $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
            }
            else
            {
                $arr=array();
                $err = array('Code' => 1, 'Msg' => 'No Match Found');
            }
        }
        else
        {
            $arr=array();
            $err = array('Code' => 0, 'Msg' => 'No Match Found');
        }
        $result = array('results'=>$arr,'error'=>$err);
        return $result;
    }

    public function getVProductsByCatid($params)
    {
        global $comm;
        $page = ($params['page'] ? $params['page'] : 1);
        $catid = ($params['catid'] ? $params['catid'] : 10000);
        $limit = ($params['limit'] ? $params['limit'] : 15);
        $total_pages = $chkcnt = $total_products = 0;
        $sql="SELECT
                      silver_rate,
                      gold_rate,
                      dollar_rate,
                      platinum_rate
              FROM
                      tbl_vendor_master
              WHERE
                      vendor_id='".$params['vid']."'";
        $res=$this->query($sql);
        if ($res)
        {
            $rates = $this->fetchData($res);

            $dollarValue=dollarValue;
            if(!empty($rates['dollar_rate']) && $rates['dollar_rate']!='0.00')
            {
                $dollarValue = $rates['dollar_rate'];
            }

            $goldRate=goldRate;
            if(!empty($rates['gold_rate']) && $rates['gold_rate']!='0.00')
            {
                $goldRate = $rates['gold_rate'];
            }

            $silverRate=silverRate;
            if(!empty($rates['silver_rate']) && $rates['silver_rate']!='0.00')
            {
                $silverRate = $rates['silver_rate'];
            }
            $platinumRate=platinumRate;
            if(!empty($rates['platinum_rate']) && $rates['platinum_rate']!='0.00')
            {
                $platinumRate = $rates['platinum_rate'];
            }
        }
       // $cate_ids=  $this->getSubCat($catid);
        $psql='';
        if($catid == 10000)
        {
            $psql='d.color, d.carat, d.shape, d.certified AS cert, d.clarity, d.b2b_price';
        }
        else if($catid == 10001)
        {
            $psql='d.shape,d.metal,d.gold_weight,d.dwt,d.gold_purity,d.certified as cert';
        }
        else if($catid == 10002)
        {
            $psql='d.type, d.metal, d.gold_purity, d.gold_weight,d.bullion_design';
        }

        $sql = "select
                                    DISTINCT product_id AS pid,

                                    (SELECT product_id FROM tbl_product_category_mapping WHERE product_id = pid AND category_id = ".$catid.") AS id,
                                    (SELECT vendor_price FROM tbl_vendor_product_mapping WHERE product_id = id) AS price,
                                    (SELECT vendor_id FROM tbl_vendor_product_mapping WHERE product_id = id) AS vendor_id,
                                    (SELECT barcode FROM tbl_product_master WHERE product_id = id) AS barcode,
                                    (SELECT lotref FROM tbl_product_master WHERE product_id = id) AS lotref,
                                    (SELECT product_name FROM tbl_product_master WHERE product_id = id) AS product_name,
                                    (SELECT update_time FROM tbl_product_master WHERE product_id = id) AS update_time,
                                    (SELECT active_flag FROM tbl_product_master WHERE product_id = id) AS active_flag,
                                    d.complete_flag,
                                    ".$psql."
                FROM
                                    tbl_product_search AS d
                WHERE
                                    d.complete_flag = 1
                HAVING
                                    vendor_id=" . $params['vid'] . "
                AND
                                    active_flag <> 2
                ORDER BY
                                    update_time
                DESC ";
        $res = $this->query($sql);

        $total_products = $this->numRows($res);
        if (!empty($params['page']))
        {
            $start        = ($page * $limit) - $limit;
            $total_pages  = ceil($total_products/$limit);
            $sql.=" LIMIT " . $start . ",$limit";
            $res          = $this->query($sql);
            $chkcnt       = $this->numRows($res);
        }
        if ($chkcnt > 0)
        {
            while ($row = $this->fetchData($res))
            {
                if ($catid == 10001)
                {
                    $csql=" SELECT
                                  p_catid,
                                  cat_name
                            FROM
                                  tbl_category_master
                            WHERE
                                  catid IN (SELECT category_id FROM `tbl_product_category_mapping` WHERE `product_id`=".$row['id'].")
                            ORDER BY
                                  p_catid
                            DESC";
                    $cres = $this->query($csql);
                    if($this->numRows($cres)>0)
                    {
                        $row1=array();
                        while ($crow = $this->fetchData($cres))
                        {
                            array_push($row1, $crow);
                        }
                        $row['category']=$row1;
                    }
                }
                if ($catid == 10000)
                {
                    $row['price']= $row['price'];
                }
                if ($catid == 10002)
                {
                    $purity = $row['gold_purity'];
                    $metal  = strtolower($row['metal']);
                    $weight = $row['gold_weight'];

                    $metalRate=$silverRate;

                    if($metal=='gold')
                    {
                        $metalRate = $goldRate;
                        $rate = ((($metalRate/995)*$purity)/10)*$weight;
                        $price = ceil($rate);
                        $row['price'] = $this->IND_money_format($price);
                    }
                    if($metal=='silver')
                    {
                        $metalRate=$silverRate;
                        $finalRate=($metalRate/1000)*($purity/999);
                        $price=ceil($finalRate*$weight);
                        $row['price'] = $this->IND_money_format($price);
                    }
                    if($metal == 'platinum')
                    {
                        $metalRate = $platinumRate;
                        $rate = ((($metalRate/999)*$purity)/10)*$weight;
                        $price = ceil($rate);
                        $row['price'] = $this->IND_money_format($price);
                    }
                }
                if($catid == 10001)
                {
                    $price = ceil($row['price']);
                    $row['price'] = $this->IND_money_format($price);
                }
                        $arr1[] = $row;
             }
            $arr = array('total_products' => $total_products, 'total_pages' => $total_pages, 'products' => $arr1);
            $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
        }
        else
        {
            $arr = array('total_products' => $total_products, 'total_pages' => $total_pages);
            $err = array('Code' => 1, 'Msg' => 'No Match Found');
        }
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }

    public function getVPendingProducts($params)
    {
        global $comm;
        $cntDiamond = $cntBullion = $cntJewellery = 0;
        $page = ($params['page'] ? $params['page'] : 1);
        $catid = ($params['catid'] ? $params['catid'] : 10000);
        $limit = ($params['limit'] ? $params['limit'] : 15);
        $total_pages = $chkcnt = $total_products = 0;

        $sql="SELECT
                    silver_rate,
                    gold_rate,
                    dollar_rate,
                    platinum_rate
               FROM
                    tbl_vendor_master
               WHERE
                    vendor_id='".$params['vid']."'";

        $res=$this->query($sql);
        if ($res)
        {
            $rates = $this->fetchData($res);
            $dollarValue=dollarValue;

            if(!empty($rates['dollar_rate']) && $rates['dollar_rate']!='0.00')
            {
                $dollarValue = $rates['dollar_rate'];
            }
            $goldRate=goldRate;

            if(!empty($rates['gold_rate']) && $rates['gold_rate']!='0.00')
            {
                $goldRate = $rates['gold_rate'];
            }
            $silverRate=silverRate;
            if(!empty($rates['silver_rate']) && $rates['silver_rate']!='0.00')
            {
                $silverRate = $rates['silver_rate'];
            }
            $platinumRate=platinumRate;
            if(!empty($rates['platinum_rate']) && $rates['platinum_rate'] != '0.00')
            {
                $platinumRate = $rates['platinum_rate'];
            }
        }
       // $cate_ids=  $this->getSubCat($catid);
        $psql='*';
        $sql = "select
                        product_id AS id,
                        (SELECT product_id from tbl_product_category_mapping where product_id = id and category_id = ".$params['catid'].") as pid,
                        (SELECT product_name FROM tbl_product_master WHERE product_id = pid) AS product_name,
                        (SELECT vendor_price FROM tbl_vendor_product_mapping WHERE product_id = pid) AS price,
                        (SELECT vendor_id FROM tbl_vendor_product_mapping WHERE product_id = pid) AS vendor_id,
                        (SELECT barcode FROM tbl_product_master WHERE product_id = pid) AS barcode,
                        (SELECT update_time FROM tbl_product_master WHERE product_id = pid) AS update_time,
                        (SELECT active_flag FROM tbl_product_master WHERE product_id = pid) AS active_flag,
                        d.".$psql."
                FROM
                        tbl_product_search AS d
                WHERE
                        d.complete_flag = 0
                HAVING
                        vendor_id IN (" . $params['vid'] . ")
                AND
                        active_flag <> 2
                ORDER BY
                        update_time
                DESC ";
        $res = $this->query($sql);

        $total_products = $this->numRows($res);
        if (!empty($params['page']))
        {
            $start = ($page * $limit) - $limit;
            $total_pages = ceil($total_products/$limit);
            $sql.=" LIMIT " . $start . ",$limit";
            $res = $this->query($sql);
            $chkcnt = $this->numRows($res);
        }
        if ($chkcnt > 0)
        {
            while ($row = $this->fetchData($res))
            {
                //if ($catid == $row['catid'])
                //{
                    $csql=" SELECT
                                  p_catid,
                                  cat_name
                            FROM
                                  tbl_category_master
                            WHERE
                                  catid IN (SELECT category_id FROM `tbl_product_category_mapping` WHERE `product_id`=".$row['id'].")
                            ORDER BY
                                  p_catid
                            DESC";
                    $cres = $this->query($csql);

                    if($this->numRows($cres)>0)
                    {
                        $row1=array();
                        while ($crow = $this->fetchData($cres))
                        {
                            array_push($row1, $crow);
                        }
                        $row['category']=$row1;
                    }
                //}
                if ($catid == 10000)
                {
                    $row['price'] = $row['price'];
                    $price        = ceil($row['price']);
                    $row['price'] = $this->IND_money_format($price);
                }
                if ($catid == 10002)
                {
                    $purity=$row['gold_purity'];
                    $metal=strtolower($row['metal']);
                    $weight=$row['gold_weight'];
                    $metalRate=$silverRate;

                    if($metal=='gold')
                    {
                        $metalRate=$goldRate;
                        $rate = ((($metalRate/995)*$purity)/10)*$weight;
                        $price = ceil($rate);
                        $row['price'] = $this->IND_money_format($price);
                    }
                    else if($metal=='silver')
                    {
                        $metalRate=$silverRate;
                        $finalRate=($metalRate/1000)*($purity/999);
                        $price=ceil($finalRate*$weight);
                        $row['price'] = $this->IND_money_format($price);
                    }
                    else if($metal=='platinum')
                    {
                        $metalRate=$platinumRate;
                        $finalRate=($metalRate/10)*($purity/999);
                        $price=ceil($finalRate*$weight);
                        $row['price'] = $this->IND_money_format($price);
                    }
                }
                if($catid == '10000')
                {
                    $arr1[] = $row;
                    $cntDiamond++;
                }
                if($catid == '10002')
                {
                        $arr2[] = $row;
                        $cntBullion++;
                }
                if($catid == '10001')
                {
                    $arr3[] = $row;
                    $cntJewellery++;
                }
                $arr = array('total_products' => $total_products, 'total_pages' => $total_pages,'Dproducts' => $arr1,'Jproducts' => $arr3,'Bproducts' => $arr2,'Dcnt' => $cntDiamond,'Bcnt' => $cntBullion,'Jcnt' => $cntJewellery);
                $err = array('code' => 0, 'msg' => 'Details fetched successfully');
             }
        }
        else
        {
            $arr = array('total_products' => $total_products, 'total_pages' => $total_pages);
            $err = array('code' => 1, 'msg' => 'No Match Found');
        }
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }


    public function validateJewel($cols,$cntJ)
    {
        if(empty($cols['combination']))
        {
            $rest['result'] = $cols;
            $cntJ++;
        }
        else
        {
            switch($cols['combination'])
            {
                case 'GOLD & DIAMONDS':
                      if(empty($cols['certified']) || empty($cols['certificate_url']) || empty($cols['metal']) || empty($cols['gold_type']) || empty($cols['diamond_shape']) || empty($cols['color']) || empty($cols['clarity']) || empty($cols['dwt']) || empty($cols['nofd']) || empty($cols['price_per_carat']) || empty($cols['diamondsvalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                      {
                          $rest['result'] = $cols;
                          $cntJ++;
                      }
                      break;
                case 'PLATINUM & DIAMONDS':
                      if(empty($cols['certified']) || empty($cols['certificate_url']) || empty($cols['metal']) || empty($cols['diamond_shape']) || empty($cols['color']) || empty($cols['clarity']) || empty($cols['dwt']) || empty($cols['nofd']) || empty($cols['price_per_carat']) || empty($cols['diamondsvalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                      {
                          $rest['result'] = $cols;
                          $cntJ++;
                      }
                      break;
                case 'SILVER & DIAMONDS':
                        if(empty($cols['certified']) || empty($cols['certificate_url']) || empty($cols['metal']) || empty($cols['diamond_shape']) || empty($cols['color']) || empty($cols['clarity']) || empty($cols['dwt']) || empty($cols['nofd']) || empty($cols['price_per_carat']) || empty($cols['diamondsvalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'GOLD, DIAMONDS & GEMSTONES':
                        if(empty($cols['certified']) || empty($cols['certificate_url']) || empty($cols['metal']) || empty($cols['diamond_shape']) || empty($cols['color']) || empty($cols['clarity']) || empty($cols['dwt']) || empty($cols['nofd']) || empty($cols['price_per_carat']) || empty($cols['diamondsvalue']) || empty($cols['gemstone_type']) || empty($cols['gemstone_color']) || empty($cols['gemwt']) || empty($cols['num_gemstones'])  || empty($cols['gprice_per_carat']) || empty($cols['gemstonevalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'PLATINUM, DIAMONDS & GEMSTONES':
                        if(empty($cols['certified']) || empty($cols['certificate_url']) || empty($cols['metal']) || empty($cols['diamond_shape']) || empty($cols['color']) || empty($cols['clarity']) || empty($cols['dwt']) || empty($cols['nofd']) || empty($cols['price_per_carat']) || empty($cols['diamondsvalue']) || empty($cols['gemstone_type']) || empty($cols['gemstone_color']) || empty($cols['gemwt']) || empty($cols['num_gemstones'])  || empty($cols['gprice_per_carat']) || empty($cols['gemstonevalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'SILVER, DIAMONDS & GEMSTONES':
                        if(empty($cols['certified']) || empty($cols['certificate_url']) || empty($cols['metal']) || empty($cols['diamond_shape']) || empty($cols['color']) || empty($cols['clarity']) || empty($cols['dwt']) || empty($cols['nofd']) || empty($cols['price_per_carat']) || empty($cols['diamondsvalue']) || empty($cols['gemstone_type']) || empty($cols['gemstone_color']) || empty($cols['gemwt']) || empty($cols['num_gemstones'])  || empty($cols['gprice_per_carat']) || empty($cols['gemstonevalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'GOLD & GEMSTONES':
                        if(empty($cols['certified']) || empty($cols['certificate_url']) || empty($cols['metal']) || empty($cols['gold_type']) ||  empty($cols['gemstone_type']) || empty($cols['gemstone_color']) || empty($cols['gemwt']) || empty($cols['num_gemstones'])  || empty($cols['gprice_per_carat']) || empty($cols['gemstonevalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'SILVER & GEMSTONES':
                        if(empty($cols['metal']) ||  empty($cols['gemstone_type']) || empty($cols['gemstone_color']) || empty($cols['gemwt']) || empty($cols['num_gemstones'])  || empty($cols['gprice_per_carat']) || empty($cols['gemstonevalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'GOLD & SWAROVSKI ZIRCONIA':
                        if(empty($cols['certified']) || empty($cols['certificate_url']) || empty($cols['metal']) || empty($cols['gold_type']) ||  empty($cols['gemstone_type']) || empty($cols['gemstone_color']) || empty($cols['gemwt']) || empty($cols['num_gemstones'])  || empty($cols['gprice_per_carat']) || empty($cols['gemstonevalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'SILVER & SWAROVSKI ZIRCONIA':
                        if(empty($cols['metal']) ||  empty($cols['gemstone_type']) || empty($cols['gemstone_color']) || empty($cols['gemwt']) || empty($cols['num_gemstones'])  || empty($cols['gprice_per_carat']) || empty($cols['gemstonevalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'GOLD & CZ':
                        if(empty($cols['certified']) || empty($cols['certificate_url']) || empty($cols['metal']) || empty($cols['gold_type']) ||  empty($cols['gemstone_type']) || empty($cols['gemstone_color']) || empty($cols['gemwt']) || empty($cols['num_gemstones'])  || empty($cols['gprice_per_carat']) || empty($cols['gemstonevalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'SILVER & CZ':
                        if(empty($cols['metal']) ||  empty($cols['gemstone_type']) || empty($cols['gemstone_color']) || empty($cols['gemwt']) || empty($cols['num_gemstones'])  || empty($cols['gprice_per_carat']) || empty($cols['gemstonevalue']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'PLAIN GOLD':
                        if(empty($cols['certified']) || empty($cols['certificate_url']) || empty($cols['metal']) || empty($cols['gold_type'])  || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'PLAIN PLATINUM':
                        if(empty($cols['metal']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'PLAIN SILVER':
                        if(empty($cols['metal']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        break;
                case 'GOLD & POLKI':
                        if(!empty($cols['certified']) && $cols['certified'] !== 'None' && empty($cols['certificate_url']))
                        {
                            $rest['result'] = $cols;
                            $cntJ++;
                        }
                        else
                        {
                            if(empty($cols['gold_type']) || empty($cols['polki_color']) || empty($cols['polki_quality']) || empty($cols['polki_weight']) || empty($cols['polkino']) || empty($cols['polki_price_per_carat']) || empty($cols['polki_value']) || empty($cols['gold_purity']) || empty($cols['barcode']) || empty($cols['gold_weight']) || empty($cols['gold_value']) || empty($cols['grossweight']) || empty($cols['price']))
                            {
                                $rest['result'] = $cols;
                                $cntJ++;
                            }
                        }
                        break;
                  default :
                        $rest['result'] = 0;
                        $cntJ;
                        break;
            }
        }
        $res = array('result'=>$rest,'count'=>$cntJ);
        return $res;
    }

    public function bulkInsertProducts($params)
    {
       
        $vid= $params['vid'];
        $data=$params['data'];
        $type=$params['type'];
        $defaultColNames = array
                                (
                                    "Stock #",
                                    "Availability",
                                    "Shape",
                                    "Weight",
                                    "Color",
                                    "Clarity",
                                    "Cut Grade",
                                    "Polish",
                                    "Symmetry",
                                    "Fluorescence Intensity",
                                    "Fluorescence Color",
                                    "Measurements",
                                    "Lab",
                                    "Report #",
                                    "Treatment",
                                    "Rapnet Price",
                                    "Rapnet  Discount %",
                                    "Cash Price",
                                    "Cash Price Discount %",
                                    "Fancy Color",
                                    "Fancy Color Intensity",
                                    "Fancy Color Overtone",
                                    "Depth %",
                                    "Table %",
                                    "Girdle Thin",
                                    "Girdle Thick",
                                    "Girdle %",
                                    "Girdle Condition",
                                    "Culet Size",
                                    "Culet Condition",
                                    "Crown Height",
                                    "Crown Angle",
                                    "Pavilion Depth",
                                    "Pavilion Angle",
                                    "Laser Inscription",
                                    "Cert Comment",
                                    "Country",
                                    "State",
                                    "City",
                                    "Is Matched Pair Separable",
                                    "Pair Stock #",
                                    "Allow RapLink Feed",
                                    "Parcel Stones",
                                    "Report Filename",
                                    "Diamond Image",
                                    "Sarine Loupe",
                                    "Trade Show",
                                    "Key to symbols",
                                    "Shade ",
                                    "Star Length",
                                    "Center Inclusion",
                                    "Black Inclusion",
                                    "Milky",
                                    "Member Comment",
                                    "Report Issue Date",
                                    "Report Type",
                                    "Lab Location",
                                    "Brand"
                                );

        $sql="
                SELECT
                        city
                FROM
                        tbl_vendor_master
                WHERE
                        vendor_id=\"".$vid."\"";

        $res=$this->query($sql);

        $row=$this->fetchData($res);

        $city=$row['city'];

        if($type=='csv')
        {
            $rdv = explode("\n", $data);
            $colName = explode(",", $rdv[0]);
            $len = count($rdv) - 1;
        }
        else
        {
            $rdv=$data;
            $colName=$data[0];
            $len = count($rdv);
        }

        $validFormat=TRUE;
        if (count($colName) == count($defaultColNames))
        {
            for ($i = 0; $i < count($defaultColNames); $i++)
            {
                if (strtoupper(trim($defaultColNames[$i])) != strtoupper(trim($colName[$i])))
                {
                    $validFormat = FALSE;
                }
            }
        }
        else
        {
            $validFormat = FALSE;
        }

        $i = $totlIns = 0;
        if ($validFormat)
        {
            while ($i < $len)
            {
                if($type=='csv')
                {
                    $value = explode(",", $rdv[$i]);
                }
                else
                {
                    $value=$rdv[$i];
                }

                if ($i != 0)
                {
                    $ts = date('Y-m-d H:i');
                    $query = "
                                INSERT
                                INTO
                                        `tbl_productid_generator`
                                        (
                                            `product_name`,
                                            date_time
                                        )
                                VALUES
                                        (
                                            'Diamond',
                                            '" . $ts . "'
                                        )";

                    $res = $this->query($query);
                    if ($res)
                    {

                        $b2b_price = $value[8]*(ltrim($value[12],'-')/100);

                        $pro_id = mysql_insert_id();
                        $sql = "
                                    INSERT
                                    INTO
                                            `tbl_product_category_mapping`
                                            (
                                                product_id,
                                                category_id,
                                                price,
                                                b2bprice,
                                                date_time
                                            )
                                    VALUES
                                            (
                                                '" . $pro_id . "',
                                                     '10000',
                                                '" . $value[9] . "',
                                                '" . $b2b_price . "',
                                                '" . $ts . "'
                                            )";

                        $res = $this->query($sql);
                        $sql = "
                                    INSERT
                                    INTO
                                            `tbl_product_master`
                                            (
                                                product_id,
                                                barcode,
                                                lotref,
                                                lotno,
                                                prd_price,
                                                b2bprice,
                                                date_time
                                            )
                                    VALUES
                                            (
                                                '" . $pro_id . "',
                                                '" . $value[0] . "',
                                                '" . $value[1] . "',
                                                '" . $value[2] . "',
                                                '" . $value[9] . "',
                                                '" . $b2b_price . "',
                                                '" . $ts . "'
                                            )";

                        $res = $this->query($sql);
                        $sql = "
                                    INSERT
                                    INTO
                                            `tbl_vendor_product_mapping`
                                            (
                                                product_id,
                                                vendor_id,
                                                vendor_price,
                                                b2bprice,
                                                city,
                                                vendor_currency,
                                                date_time
                                            )
                                    VALUES
                                            (
                                                '" . $pro_id . "',
                                                '" . $vid . "',
                                                '" . $value[9] . "',
                                                '" . $b2b_price . "',
                                                '" . $city . "',
                                                    'USD',
                                                '" . $ts . "'
                                            )";

                        $res = $this->query($sql);
                        $srch_val = "'" . $pro_id . "', ";
                        for ($j = 3; $j < count($value); $j++)
                        {
                            if($j==4)
                            {
                                $shape=$value[$j];
                                $value[$j]=$this->getAbbrValue($value[13]);
                            }

                            if($j>=13 && $j<=16)
                            {
                                $value[$j]=$this->getAbbrValue($value[$j]);
                            }

                            if($j == 11)
                            {
                                $value[$j] = str_replace('-', '', $value[$j]);
                            }

                            if($j == 12)
                            {
                                $value[$j] = str_replace('-', '', $value[$j]);
                            }

                            $srch_val .= "'" . $value[$j] . "', ";
                        }

                        $srch_val .="'".$shape."'";
                        $srch_val .=",'".$b2b_price."'";

                        $sql = "
                                    INSERT
                                    INTO
                                            `tbl_product_search`
                                            (
                                                product_id,
                                                certified,
                                                cut,
                                                carat,
                                                color,
                                                clarity,
                                                base,
                                                price,
                                                value,
                                                p_disc,
                                                p_discb2b,
                                                prop,
                                                polish,
                                                symmetry,
                                                fluo,
                                                td,
                                                tabl,
                                                measurement,
                                                cno,
                                                pa,
                                                cr_hgt,
                                                cr_ang,
                                                girdle,
                                                pd,
                                                shape,
                                                b2b_price
                                            )
                                    VALUES
                                            (" . rtrim($srch_val, ', ') . ")";

                        $res = $this->query($sql);
                        $totlIns++;
                    }
                }
                $i++;
            }
            if ($res)
            {
                $totlRecrds = $len - 1;
                $arr = array
                            (
                                'suc' => $totlIns,
                                'fail' => $totlRecrds - $totlIns
                            );
                $err = array
                            (
                                'Code' => 0,
                                'Msg' => 'Products are updated Successfully'
                            );
            }
            else
            {
                $arr = array();
                $err = array
                            (
                                'Code' => 1,
                                'Msg' => 'Products are Failed to Update'
                            );
            }
        }
        else
        {
            $arr = array();
            $err = array
                        (
                            'Code' => 1,
                            'Msg' => 'Invalid File Format'
                        );
        }
        $result = array
                        (
                            'results' => $arr,
                            'error' => $err
                        );
        return $result;
    }

    public function generateId()
    {
        $cdt = date('Ymd');
        $mcrTime = microtime();
        $mcrTime = explode(' ', $mcrTime);

        if(!empty($mcrTime[1]))
        {
          unset($mcrTime[1]);
        }

        $mcrTime = $mcrTime[0];

        $mcrTime = explode('.', $mcrTime);
        if(!empty($mcrTime[0]))
        {
          unset($mcrTime[0]);
        }

        if(!empty($mcrTime[1]))
        {
          $mcrTime = $mcrTime[1];
        }

        if(!empty($mcrTime))
        {
          return $cdt.$mcrTime;
        }
        else
        {
          $curdate = date('YmdHis');
          $rNo = mt_rand(11, 99);

          $genId = $rNo . $curdate;
          return $genId;
        }
    }

    public function uploadJewelleryProducts($params)
    {  
        $vid=$params['vid'];
        $data=$params['data'];
        $type=$params['type'];
        $defaultColNames = array
                                (
                                    'Category',
                                    'Design No',
                                    'Combination',
                                    'Certficate',
                                    'Other Certificate',
                                    'Metal',
                                    'Gold Type',
                                    'Polki Color',
                                    'Polki Quality',
                                    'Polki Weight (in CTS)',
                                    'Polki Price / Carat',
                                    'No Of Polki',
                                    'Diamonds',
                                    'Clarity1',
                                    'Clarity2',
                                    'Colour1',
                                    'Colour2',
                                    'Colour3',
                                    'Carat',
                                    'No Of Diamond',
                                    'Diamond Price / Carat',
                                    'Color Stone Type',
                                    'Stone Color',
                                    'Color Stone Wt',
                                    'Stone Price / Carat',
                                    'No. Of Stones',
                                    'Other Material (in Grams)',
                                    'Labour Charge / Gram',
                                    'Hallmark',
                                    'Collection Name',
                                    'Purity(in KT)',
                                    'Net Weight (in Grams)',
                                    'Making Charge'
                                );
       
        if($type=='csv')
        {
            $rdv = explode("\n", $data);
            $colName = explode(",", $rdv[0]);
            $len = count($rdv) - 1;
        }
        else
        {
            $rdv = $data;
            $data[0] = array_slice($data[0],0,33);
            $colName = $data[0];
            
            $len = count($rdv);
        }
        $validFormat=TRUE;
       
        if(count($colName) == count($defaultColNames))
        {
            for ($i = 0; $i < count($defaultColNames); $i++)
            {
                if (strtoupper(trim($defaultColNames[$i])) != strtoupper(trim($colName[$i])))
                {
                    $validFormat = FALSE;
                }
            }
        }
        else
        {
            $validFormat = FALSE;
        }
                $validFormat=TRUE;
        $i = $totlIns = 0;
        if ($validFormat)
        {
            while ($i < $len)
            {
                if($type=='csv')
                {
                    $value = explode(",", $rdv[$i]);
                }
                else
                {
                    $value=$rdv[$i];
                    $isBlank = false;
                    if(!array_sum($value))
                    {
                       $isBlank = true;
                    }
                }
                if(!empty($value[0]))
                {

                  if ($i != 0 && $isBlank == false)
                  {
                      $isbugget == 'False';
                      $ts = date('Y-m-d H:i');
                      $pro_id = $this->generateId();
                      $query = "
                                  INSERT
                                  INTO
                                          `tbl_productid_generator`
                                          (
                                              product_id,
                                              `product_name`,
                                              date_time
                                          )
                                  VALUES
                                          (
                                              ".$pro_id.",
                                              'Jewellery',
                                              '" . $ts . "'
                                          )";
                      $res = $this->query($query);
                      if ($res)
                      {
                          $sql = "
                                    SELECT
                                            gold_rate,
                                            platinum_rate,
                                            silver_rate
                                    FROM
                                            tbl_vendor_master
                                    WHERE
                                            vendor_id = \"".$vid."\"";
                          $vRes = $this->query($sql);
                          if($vRes)
                          {
                              $purityArr = array('25'=>999,'24'=>958,'23'=>916,'22'=>875,'19'=>750,'18'=>708,'15'=>585,'10'=>417,'9'=>375,'8'=>333);
                              $row = $this->fetchData($vRes);
                              if(empty($purityArr[$value[30]]))
                              {
                                  $purityArr[$value[30]] = 995;
                              }
                              if($value[5] == 'Gold')
                              {
                                  $rate = ($row['gold_rate']/10)*($purityArr[$value[30]]/995);
                                  $rate = round($rate, 2);
                              }
                              if($value[5] == 'Silver')
                              {
                                  $rate = $row['silver_rate']/1000;
                              }
                              if($value[5] == 'Platinum')
                              {
                                  $rate = $row['Platinum_rate'];
                              }
                          }
                          $totalDmdVal = $value[20]*$value[18];
                          $totalDCarat = $value[18];
                          $totalDPPC   = $value[20];
                          $totalNumDmd = $value[19];

                          $totalGemVal = $value[23]*$value[24];
                          $totalNOG    = $value[25];
                          $totalGWt    = $value[23];
                          $totalGPPC   = $value[24];
                          $totalPWt    =  0;
                          $calGem      = true;
                          $calDmd      = true;
                          $cnt = 0;
                          for($j=$i+1;$j<$len;$j++)
                          {
                              if(empty($rdv[$j][0]) && $cnt !== 1)
                              {
                                  if(!empty($rdv[$j][12]))
                                  {
                                      if($rdv[$j][11] == 'Baguette')
                                      {
                                        $isbugget = 'True';
                                      }
                                      $value[11].= '|!|'.$rdv[$j][11];
                                  }
                                  if(!empty($rdv[$j][13]) || !empty($rdv[$j][14]))
                                  {
                                      $value[13].= '|!|'.$rdv[$j][13].'-'.$rdv[$j][14];
                                  }
                                  if(!empty($rdv[$j][15]) || !empty($rdv[$j][16]) || !empty($rdv[$j][17]))
                                  {
                                      $value[15].= '|!|'.$rdv[$j][15].'-'.$rdv[$j][16].'-'.$rdv[$j][17];
                                  }
                                  if(!empty($rdv[$j][21]))
                                  {
                                      $value[21].= '|!|'.$rdv[$j][21];
                                  }
                                  if(!empty($rdv[$j][22]))
                                  {
                                      $value[22].= '|!|'.$rdv[$j][22];
                                  }
                                  if(!empty($rdv[$j][18]))
                                  {
                                      $totalDCarat = $totalDCarat+$rdv[$j][18];
                                      $totalNumDmd = $totalNumDmd+$rdv[$j][18];
                                  }
                                  if(!empty($rdv[$j][18]) && !empty($rdv[$j][20]))
                                  {
                                      $calDmd = false;
                                      $totalDmdVal = $totalDmdVal + ($rdv[$j][17]*$rdv[$j][19]);
                                  }
                                  if(!empty($rdv[$j][23]) && !empty($rdv[$j][24]))
                                  {
                                      $calGem = false;
                                      $totalGemVal = $totalGemVal + ($rdv[$j][23]*$rdv[$j][24]);
                                  }
                                  if(!empty($rdv[$j][23]))
                                  {
                                      $totalGWt = $totalGWt + $rdv[$j][23];
                                  }
                                  if(!empty($rdv[$j][25]))
                                  {
                                      $totalNOG = $totalNOG + $rdv[$j][25];
                                  }
                                   if(!empty($rdv[$j][9]) && !empty($rdv[$j][10]))
                                  {
                                      
                                      $totalPWt = $totalPWt + ($rdv[$j][10]*$rdv[$j][9]);
                                  }
                              }
                              else
                              {
                                  $cnt = 1;
                              }
                          }
                          if($calGem == false)
                          {
                              $totalGPPC = $totalGemVal/$totalGWt;
                          }
                          if($calDmd == false)
                          {
                              $totalDPPC = $totalDmdVal/$totalDCarat;
                          }
                          $total_price = $this->calculatePrice($value,$rate,$totalDmdVal,$totalGemVal);
                          $total_weight= $this->calculateWeight($value);

                          $sql = "
                                      INSERT
                                      INTO
                                              `tbl_product_category_mapping`
                                              (
                                                  product_id,
                                                  category_id,
                                                  price,
                                                  b2bprice,
                                                  date_time
                                              )
                                      VALUES
                                              (
                                                  '" . $pro_id . "',
                                                      '10001',
                                                    ".$total_price.",
                                                    ".$total_price.",
                                                  '" . $ts . "'
                                              )";
                          $res = $this->query($sql);
                          $sql = "
                                      INSERT
                                      INTO
                                              `tbl_vendor_product_mapping`
                                              (
                                                  product_id,
                                                  vendor_id,
                                                  vendor_price,
                                                  b2bprice,
                                                  city,
                                                  vendor_currency,
                                                  date_time
                                              )
                                      VALUES
                                              (
                                                  '" . $pro_id . "',
                                                  '" . $vid . "',
                                                   ".$total_price.",
                                                   ".$total_price.",
                                                      (SELECT city FROM tbl_vendor_master WHERE vendor_id=".$vid."),
                                                      'USD',
                                                  '" . $ts . "'
                                              )";
                          $res = $this->query($sql);
                          $sql = "
                                      INSERT
                                      INTO
                                              `tbl_product_master`
                                              (
                                                  product_id,
                                                  prd_price,
                                                  b2bprice,
                                                  barcode,
                                                  product_brand,
                                                  date_time
                                              )
                                      VALUES
                                              (
                                                  '" . $pro_id    . "',
                                                   ".$total_price.",
                                                   ".$total_price.",
                                                  '" . $value[1]  . "',
                                                  '" . $value[29] . "',
                                                  '" . $ts . "'
                                              )";

                          $res = $this->query($sql);

                          $complete_flag = $this->validateJewelFields($value);
                          if(!empty($value[9]) && !empty($value[10]))
                          {
                              $polkiValue = floatval($value[9])*floatval($value[10]);
                          }

                          if($value[5] == 'Gold' && empty($value[26]) && !empty($value[31]))
                          {
                              $value[26] = floatval($value[31])/floatval($value[30]);
                          }

                          $value[13] = $value[13].'-'.$value[14];
                          $value[15] = $value[15].'-'.$value[16].'-'.$value[17];

                          if($value[12] == 'Baguette')
                          {
                              $isbugget = 'True';
                          }

                          $value[12] = rtrim($value[12],',');
                          $value[13] = str_replace(',-','',rtrim($value[13],','));
                          $value[15] = str_replace(',-','',rtrim($value[15],','));
                          $value[21] = rtrim($value[21],',');
                          if(empty($value[9]))
                          {
                              $value[9] = 0.00;
                          }
                          if(empty($value[10]))
                          {
                              $value[10] = 0.00;
                          }
                          if(empty($polkiValue))
                          {
                              $polkiValue = 0.00;
                          }
                          if(empty($value[18]))
                          {
                              $value[18] = 0.00;
                          }
                          if(empty($value[19]))
                          {
                              $value[19] = 0;
                          }
                          if(empty($value[20]))
                          {
                              $value[20] = 0.00;
                          }
                          if(empty($value[23]))
                          {
                              $value[23] = 0.00;
                          }
                          if(empty($value[24]))
                          {
                              $value[24] = 0.00;
                          }
                          if(empty($value[25]))
                          {
                              $value[25] = 0;
                          }
                          if(empty($value[26]))
                          {
                              $value[26] = 0;
                          }
                          // if(!empty($value[0]) && $value[0] == 'Bangle/Bracelet')
                          // {
                          //     $value[0] = 'Bangle';
                          // }
                          $sql = "
                                      INSERT
                                      INTO
                                              `tbl_product_search`
                                      SET
                                              product_id            =   '".$pro_id."',
                                              shape                 =   '".$value[0]."',
                                              combination           =   '".$this->getAbbrValue($value[2])."',
                                              certified             =   '".$value[3]."',
                                              other_certificate     =   '".$value[4]."',
                                              metal                 =   '".$value[5]."',
                                              gold_type             =   '".$value[6]." Gold',
                                              polki_color           =   '".$value[7]."',
                                              polki_quality         =   '".$value[8]."',
                                              polki_weight          =   ".$value[9].",
                                              polki_price_per_carat =   '".$value[10]."',
                                              polki_value           =   '".$polkiValue."',
                                              polkino               =   '".$value[11]."',
                                              diamond_shape         =   '".$value[12]."',
                                              clarity               =   '".$value[13]."',
                                              color                 =   '".$value[15]."',
                                              dwt                   =   '".$totalDCarat."',
                                              nofd                  =   '".$totalNumDmd."',
                                              price_per_carat       =   '".$totalDPPC."',
                                              diamondsvalue         =   '".round($totalDmdVal,2)."',
                                              gemstone_type         =   '".$value[21]."',
                                              gemstone_color        =   '".$value[22]."',
                                              gemwt                 =   '".$totalGWt."',
                                              gemstonevalue         =   '".round($totalGemVal,2)."',
                                              gprice_per_carat      =   '".$totalGPPC."',
                                              num_gemstones         =   '".$totalNOG."',
                                              othermaterial         =   '".$value[26]."',
                                              labour_charge         =   '".$value[27]."',
                                              gold_purity           =   '".$value[30]."',
                                              gold_weight           =   '".$value[31]."',
                                              gold_value            =   '".round($value[31]*$rate,2)."',
                                              grossweight           =    ".$total_weight.",
                                              price                 =    ".$total_price.",
                                              isBugget              =    '".$isbugget."',
                                              complete_flag         =      $complete_flag";
                          $res = $this->query($sql);
                          $totlIns++;
                      }
                  }
                  else
                  {
                      $res = false;
                  }
                  $i++;
                }
                else
                {
                    $i++;
                }
            }
            if ($res)
            {
                $totlRecrds = $len - 1;
                $arr = array
                            (
                                'suc' => $totlIns,
                                'fail' => $totlRecrds - $totlIns
                            );
                $err = array
                            (
                                'Code' => 0,
                                'Msg' => 'Products are updated Successfully'
                            );
            }
            else if($res == false)
            {
              $arr = array();
              $err = array
                          (
                              'Code' => 0,
                              'Msg' => 'Products are updated Successfully with blank values'
                          );
            }
            else
            {
                $arr = array();
                $err = array
                            (
                                'Code' => 1,
                                'Msg' => 'Products are Failed to Update'
                            );
            }
        }
        else
        {
            $arr = array();
            $err = array
                        (
                            'Code' => 1,
                            'Msg' => 'Invalid File Format'
                        );
        }
        $result = array
                        (
                            'results' => $arr,
                            'error' => $err
                        );
        return $result;
    }

    public function calculatePrice($params,$rate,$totalDmdVal,$totalGemVal)
    {
        $price = 0;
        if(!empty($params[18]) && !empty($params[20]))
        {
            if(!empty($totalDmdVal))
            {
                $price = $price + $totalDmdVal;
            }
            else
            {
                $price = $price + ((floatval($params[18])/5)*$params[20]);
            }
        }
        
        
        if(!empty($params[9]) && !empty($params[10]))
        {
            if(!empty($totalPWt))
            {
                $price = $price + $totalPWt;
            }
            else
            {
                $price = $price + ((floatval($params[9]))*$params[10]);
            }
        }
        
        if(!empty($params[23]) && !empty($params[24]))
        {
          if(!empty($totalGemVal))
          {
              $price = $price + $totalGemVal;
          }
          else
          {
              $price = $price + ((floatval($params[23])/5)*$params[24]);
          }
        }
        if(!empty($params[31]))
        {
            $price = $price + floatval($params[31])*floatval($rate);
        }

        if(!empty($params[32]) && empty($params[27]))
        {
            $price  = $price + floatval($params[32]);
        }
        else
        {
            if(!empty($params[31]))
            {
                $price  = $price + (floatval($params[27])*floatval($params[31]));
            }
            else
            {
                $price  = $price + floatval($params[27]);
            }
        }
        
        return $price;
    }

    public function calculateWeight($params,$rate)
    {
        $weight = 0;
        if(!empty($params[18]))
        {
            $weight = $weight + (floatval($params[18])/5);
        }
        if(!empty($params[23]))
        {
            $weight = $weight + (floatval($params[23])/5);
        }
        if(!empty($params[26]))
        {
            $weight = $weight + floatval($params[26]);
        }
        if(!empty($params[31]))
        {
            $weight = $weight + floatval($params[31]);
        }
        return $weight;
    }

    public function uploadBullionProducts($params)
    {
        
        $vid=$params['vid'];
        $city=$params['city'];
        $data=$params['data'];
        $type=$params['type'];
        $defaultColNames = array
                                (
                                    'Type',
                                    'Metal',
                                    'Purity Type',
                                    'Purity',
                                    'Weight Type',
                                    'Weight',
                                    'Bullion Design',
                                    'Other Design Name',
                                    'Design Number'
                                );

        
        if($type=='csv')
        {
            $rdv = explode("\n", $data);
            $colName = explode(",", $rdv[0]);
            $len = count($rdv) - 1;
        }
        else
        {
            $rdv=$data;
            $colName=$data[0];
            $len = count($rdv);
        }
        $validFormat=TRUE;
        if(count($colName) == count($defaultColNames))
        {
            for ($i = 0; $i < count($defaultColNames); $i++)
            {
                if (strtoupper(trim($defaultColNames[$i])) != strtoupper(trim($colName[$i])))
                {
                    $validFormat = FALSE;
                }
            }
        }
        else
        {
            $validFormat = FALSE;
        }
        $i = $totlIns = 0;
        if($validFormat)
        {
            while ($i < $len)
            {
                if($type=='csv')
                {
                    $value = explode(",", $rdv[$i]);
                }
                else
                {

                    $isBlank = false;
                    $value=$rdv[$i];
                    if(!array_sum($value))
                    {
                       $isBlank = true;
                    }
                }
                if ($i != 0 && $isBlank == false)
                {
                    $ts = date('Y-m-d H:i');
               
                    $query = "
                                INSERT
                                INTO
                                        `tbl_productid_generator`
                                        (
                                            `product_name`,
                                             date_time
                                        )
                                VALUES
                                        (
                                            'Bullion',
                                            '" . $ts . "'
                                        )";

                    $res = $this->query($query);
                   
                   if ($res)
                    {
                        $pro_id = mysql_insert_id();

                        if($value['1'] == 'Gold' && $value['0'] == 'Coin')
                        {
                            $shape = 'gCoins';
                            $catNo = '10037';
                        }
                        if($value['1'] == 'Gold' && $value['0'] == 'Bar')
                        {
                            $shape = 'gBars';
                            $catNo = '10038';
                        }
                        if($value['1'] == 'Silver' && $value['0'] == 'Coin')
                        {
                            $shape = 'sCoins';
                            $catNo = '10037';
                        }
                        if($value['1'] == 'Silver' && $value['0'] == 'Bar')
                        {
                            $shape = 'sBars';
                            $catNo = '10038';
                        }
                        if($value['1'] == 'Platinum' && $value['0'] == 'Coin')
                        {
                            $shape = 'pCoins';
                            $catNo = '10037';
                        }
                        if($value['1'] == 'Platinum' && $value['0'] == 'Bar')
                        {
                            $shape = 'pBars';
                            $catNo = '10038';
                        }

                        $complete_flag = 1;

                        if(empty($value['0']) || empty($value['1']) || empty($value['3']) || empty($value['5']))
                        {
                            $complete_flag = 0;
                        }


                        $sql = "
                                    INSERT
                                    INTO
                                            `tbl_product_category_mapping`
                                            (
                                                product_id,
                                                category_id,
                                                date_time
                                            )
                                    VALUES
                                            (
                                                '" . $pro_id . "',
                                                     '10002',
                                                '" . $ts . "'
                                            ),
                                            (
                                                '" . $pro_id . "',
                                                 ".$catNo.",
                                                '" . $ts . "'
                                            )";
                        $res = $this->query($sql);
                        $sql = "
                                    INSERT
                                    INTO
                                            `tbl_product_master`
                                            (
                                                product_id,
                                                barcode,
                                                date_time
                                            )
                                    VALUES
                                            (
                                                '" . $pro_id . "',
                                                '".  $value[8]."',
                                                '" . $ts . "'
                                            )";

                        $res = $this->query($sql);
                        $sql = "
                                    INSERT
                                    INTO
                                            `tbl_vendor_product_mapping`
                                            (
                                                product_id,
                                                vendor_id,
                                                city,
                                                vendor_currency,
                                                date_time
                                            )
                                    VALUES
                                            (
                                                '" . $pro_id . "',
                                                '" . $vid . "',
                                                    (SELECT city FROM tbl_vendor_master WHERE vendor_id=".$vid."),
                                                    'USD',
                                                '" . $ts . "'
                                            )";

                        $res = $this->query($sql);
                        if($value['4'] == 'Other' && !empty($value['5']))
                        {
                            $value['4'] = $value['5'];
                        }
                        $sql = "
                                    INSERT
                                    INTO
                                            tbl_product_search
                                    SET
                                            product_id      =   '".$pro_id."',
                                            type            =   '".$value[0]."',
                                            metal           =   '".$value[1]."',
                                            gold_purity     =   '".$value[3]."',
                                            gold_weight     =   '".$value[5]."',
                                            bullion_design  =   '".$value[6]."',
                                            shape           =   '".$shape."',
                                            complete_flag   =   '".$complete_flag."'";
                        
                        $res = $this->query($sql);
                        $totlIns++;
                        
                    }
                }
                else
                {
                    $res = false;
                }
                $i++;
            }
            if ($res)
            {
                $totlRecrds = $len - 1;
                $arr = array
                            (
                                'suc' => $totlIns,
                                'fail' => $totlRecrds - $totlIns
                            );
                $err = array
                            (
                                'Code' => 0,
                                'Msg' => 'Products are updated Successfully'
                            );
            }
            else if($res == false)
            {
                $arr = array
                            ();
                $err = array
                            (
                                'Code' => 0,
                                'Msg' => 'Products are updated Successfully with some blank fields'
                            );
            }
            else
            {
                $arr = array();
                $err = array
                            (
                                'Code' => 1,
                                'Msg' => 'Products are Failed to Update'
                            );
            }
        }
        else
        {   
            $arr = array();
            $err = array
                        (
                            'Code' => 1,
                            'Msg' => 'Invalid File Format'
                        );
        }
        $result = array
                        (
                            'results' => $arr,
                            'error' => $err
                        );
        return $result;
    }

    public function uploadDiamondProducts($params)
    {
        $vid=$params['vid'];
        $data=$params['data'];
        $type=$params['type'];

        $defaultColNames = array
                                (
                                    'Stock #',
                                    'Availability',
                                    'Shape',
                                    'Weight(in CTS)',
                                    'Color',
                                    'Clarity',
                                    'Cut Grade',
                                    'Polish',
                                    'Symmetry',
                                    'Fluorescence',
                                    'Measurements',
                                    'Lab',
                                    'Report #',
                                    'Treatment',
                                    'IFtoSI Price(in $)',
                                    'IFtoSI B2B Discount',
                                    'IFtoSI B2C Discount',
                                    'Fancy Color',
                                    'Fancy Color Intensity',
                                    'Fancy Color Overtone',
                                    'Depth %',
                                    'Table %',
                                    'Girdle',
                                    'Culet Condition',
                                    'Crown Height',
                                    'Crown Angle',
                                    'Pavilion Depth',
                                    'Pavilion Angle',
                                    'Laser Inscription',
                                    'Cert Comment',
                                    'Country',
                                    'State',
                                    'City',
                                    'Is Matched Pair Separable',
                                    'Pair Stock #',
                                    'Report Filename',
                                    'Diamond Image',
                                    'Sarine Loupe',
                                    'Center Inclusion',
                                    'Black Inclusion',
                                    'Milky',
                                    'Member Comment',
                                    'Report Issue Date',
                                    'Report Type',
                                    'Lab Location'
                                );

        if($type=='csv')
        {
            $rdv = explode("\n", $data);
            $colName = explode(",", $rdv[0]);
            $len = count($rdv) - 1;
        }
        else
        {
            $rdv=$data;
            $colName=$data[0];
            $len = count($rdv);
        }
       
        $validFormat=TRUE;
        if (count($colName) == count($defaultColNames))
        {
            for ($i = 0; $i < count($defaultColNames); $i++)
            {
                if (strtoupper(trim($defaultColNames[$i])) != strtoupper(trim($colName[$i])))
                {

                    $validFormat = FALSE;
                }
            }
        }
        else
        {
            $validFormat = FALSE;
        }

        $i = $totlIns = 0;
        if ($validFormat)
        {
            while ($i < $len)
            {
                if($type=='csv')
                {
                    $value = explode(",", $rdv[$i]);
                }
                else
                {
                    $value=$rdv[$i];
                    $isBlank = false;
                    if(!array_sum($value))
                    {
                       $isBlank = true;
                    }
                }
                if(!empty($value[15]) && !empty($value[14]))
                {
                    $b2bprice = $value[14]-($value[14]*($value[15]/100));
                }
                else
                {
                    $b2bprice = 0;
                }

                if(!empty($value[16]) && !empty($value[14]))
                {
                    $b2cprice = $value[14]-($value[14]*($value[16]/100));
                }
                else
                {
                    $b2cprice = 0;
                }

                if ($i != 0 && $isBlank == false)
                {
                    $ts = date('Y-m-d H:i');
                    $query = "  INSERT
                                INTO
                                        `tbl_productid_generator`
                                        (
                                            `product_name`,
                                            date_time
                                        )
                                VALUES
                                        (
                                            'Diamond',
                                          '".$ts."'
                                        )";

                    $res = $this->query($query);
                    if ($res)
                    {
                        $pro_id = mysql_insert_id();

                        
                        $sql = "
                                    INSERT
                                    INTO
                                            `tbl_product_category_mapping`
                                            (
                                               product_id,
                                               category_id,
                                               price,
                                               b2bprice,
                                               display_flag,
                                               date_time
                                            )
                                    VALUES
                                            (
                                                '" . $pro_id . "',
                                                    '10000',
                                                 " . $b2cprice . ",
                                                 " . $b2cprice . ",
                                                     1,
                                                '" . $ts . "'
                                            )";
                
                        $res = $this->query($sql);
                        $sql = "
                                    INSERT
                                    INTO
                                            `tbl_vendor_product_mapping`
                                            (
                                                product_id,
                                                vendor_id,
                                                vendor_price,
                                                b2bprice,
                                                city,
                                                vendor_currency,
                                                date_time
                                            )
                                    VALUES
                                            (
                                                '" . $pro_id . "',
                                                '" . $vid . "',
                                                 " . $b2cprice . ",
                                                 " . $b2cprice . ",
                                                '" . $value[33] . "',
                                                    'USD',
                                                '" . $ts . "'
                                            )";

                        $res = $this->query($sql);
                        $sql = "    INSERT
                                    INTO
                                            `tbl_product_master`
                                            (
                                                product_id,
                                                barcode,
                                                prd_price,
                                                b2bprice,
                                                date_time
                                            )
                                    VALUES
                                            (
                                                '" . $pro_id . "',
                                                '" . $value[0]."',
                                                 " . $b2cprice . ",
                                                 " . $b2cprice . ",
                                                '" . $ts . "'
                                            )";

                        $res = $this->query($sql);
                        if(empty($value[2]) || empty($value[3]) || empty($value[4]) || empty($value[5]) || empty($value[6]) || empty($value[7]) || empty($value[8]) || empty($value[9]) || empty($value[10]) || empty($value[11]) || empty($value[14]) || empty($value[15]) || empty($value[16]) || empty($value[17]) || empty($value[18]) || empty($value[23]) || empty($value[24]) || empty($value[28]))
                        {
                            $complete_flag = 0;
                        }
                        else
                        {
                            $complete_flag = 1;
                        }

                        $sql = "    INSERT
                                    INTO
                                            `tbl_product_search`
                                    SET
                                            product_id    = ".$pro_id.",
                                            shape         = '".$value[2]."',
                                            carat         = '".$value[3]."',
                                            certified     = '".$value[11]."',
                                            cno           = '".$value[12]."',
                                            color         = '".$value[4]."',
                                            clarity       = '".$value[5]."',
                                            cut           = '".$value[6]."',
                                            girdle        = '".$value[22]."',
                                            fluo          = '".$value[9]."',
                                            polish        = '".$value[7]."',
                                            symmetry      = '".$value[8]."',
                                            measurement   = '".$value[10]."',
                                            tabl          = '".$value[21]."',
                                            cr_hgt        = '".$value[24]."',
                                            cr_ang        = '".$value[25]."',
                                            pd            = '".$value[26]."',
                                            pa            = '".$value[27]."',
                                            price         =  ".$b2cprice.",
                                            base          = '".$value[14]."',
                                            b2b_price     =  ".$b2bprice.",
                                            p_disc        = '".$value[16]."',
                                            complete_flag =    $complete_flag,
                                            p_discb2b     = '".$value[15]."'";
                        $res = $this->query($sql);
                        $sql = "    INSERT
                                    INTO
                                            `tbl_product_more_info`
                                    SET
                                            product_id              = ".$pro_id.",
                                            stock_no                = '".$value[0]."',
                                            availability            = '".$value[1]."',
                                            fluorescence_color      = '".$value[9]."',
                                            lab                     = '".$value[11]."',
                                            report_no               = '".$value[12]."',
                                            treatment               = '".$value[13]."',
                                            rapnet_price            = '".$value[14]."',
                                            rapnet_discount_percent = '".$value[15]."',
                                            fancy_color             = '".$value[17]."',
                                            fancy_color_intensity   = '".$value[18]."',
                                            fancy_color_overtone    = '".$value[19]."',
                                            depth_percent           = '".$value[20]."',
                                            girdle_condition        = '".$value[22]."',
                                            culet_size              = '".$value[23]."',
                                            culet_condition         = '".$value[24]."',
                                            laser_inscription       = '".$value[29]."',
                                            certified               = '".$value[12]."',
                                            country                 = '".$value[30]."',
                                            state                   = '".$value[31]."',
                                            city                    = '".$value[32]."',
                                            matched_pair            = '".$value[33]."',
                                            pair_stock              = '".$value[34]."',
                                            parcel_stones           = '".$value[35]."',
                                            report_filename         = '".$value[36]."',
                                            diamond_image           = '".$value[37]."',
                                            sarine_loupe            = '".$value[38]."',
                                            trade_show              = '".$value[39]."',
                                            key_to_symbols          = '".$value[40]."',
                                            shade                   = '".$value[41]."',
                                            star_length             = '".$value[42]."',
                                            center_inclusion        = '".$value[43]."',
                                            black_inclusion         = '".$value[44]."',
                                            milky                   = '".$value[45]."',
                                            member_comment          = '".$value[46]."',
                                            report_date             = '".$value[47]."',
                                            report_type             = '".$value[48]."',
                                            lab_location            = '".$value[49]."',
                                            brand                   = '".$value[50]."'";
                        $res = $this->query($sql);
                        $totlIns++;
                    }
                }
                else
                {
                    $res = false;
                }
                $i++;
            }
            if ($res)
            {
                $totlRecrds = $len - 1;
                $arr = array
                            (
                                'suc' => $totlIns,
                                'fail' => $totlRecrds - $totlIns
                            );

                $err = array
                            (
                                'Code' => 0,
                                'Msg' => 'Products are updated Successfully'
                            );
            }
            else if($res == false)
            {
              $arr = array();
              $err = array
                          (
                              'Code' => 0,
                              'Msg' => 'Products are updated Successfully'
                          );
            }
            else
            {
                $arr = array();
                $err = array
                            (
                                'Code' => 1,
                                'Msg' => 'Products are Failed to Update'
                            );
            }
        }
        else
        {
            $arr = array();
            $err = array
                        (
                            'Code' => 1,
                            'Msg' => 'Invalid File Format'
                        );
        }
        $result = array
                        (
                            'results' => $arr,
                            'error' => $err
                        );
        return $result;
    }

    private function getSubCat($catid, $catarr = array()) {
        $sql = "SELECT p_catid, catid FROM tbl_category_master where p_catid in (" . $catid . ") order by catid ASC";

        $res = $this->query($sql);
        if ($res) {
            while ($row = $this->fetchData($res)) {
                if ($row['p_catid'] != 0) {
                    $catid .= ','.$this->getSubCat($row['catid']);
                }
            }
        }
        return $catid;
    }

    public function deletePrd($params)
    {

        $sql1 = "UPDATE tbl_vendor_product_mapping SET active_flag=2 WHERE product_id=" . $params['prdid']." AND vendor_id=" . $params['vid'];
        $res = $this->query($sql1);

        $sql2 = "UPDATE tbl_product_search SET active_flag=2 WHERE product_id=" . $params['prdid'];
        $res1 = $this->query($sql2);

        $sql3 = "UPDATE tbl_product_master SET active_flag=2 WHERE product_id=" . $params['prdid'];
        $res2 = $this->query($sql3);

        $sql4 = "UPDATE tbl_productid_generator SET active_flag=2 WHERE product_id=" . $params['prdid'];
        $res3 = $this->query($sql4);

        $sql5 = "UPDATE tbl_product_category_mapping SET display_flag=2 WHERE product_id=" . $params['prdid'];
        $res4 = $this->query($sql5);

        $sql6 = "UPDATE tbl_product_enquiry SET active_flag=2 WHERE product_id=" . $params['prdid'];
        $res5 = $this->query($sql6);

        $upsql="UPDATE tbl_wishlist SET wf=2 WHERE pid=".$params['prdid']."";
        $upres=$this->query($upsql);

        if ($res5) {
            $arr = array();
            $err = array('Code' => 0, 'Msg' => 'Product deleted successfully!');
        } else {
            $arr = array();
            $err = array('code' => 1, 'msg' => 'Error in fetching data');
        }
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }

        public function toggleactive($params)
        {
            $sql = "SELECT active_flag from tbl_vendor_master WHERE vendor_id=".$params['vid'];
            $res = $this->query($sql);
            $cntres = $this->numRows($res);
            if($cntres > 0)
            {
                $rows = $this->fetchData($res);
                $flag = $rows['active_flag'];
            }
            if($params['flag'] == 1)
            {
                $sql1 = "UPDATE tbl_vendor_product_mapping SET active_flag=".$flag." WHERE product_id=" . $params['prdid']." AND vendor_id=" . $params['vid'];
                $res = $this->query($sql1);

                $sql2 = "UPDATE tbl_product_search SET active_flag=".$flag." WHERE product_id=" . $params['prdid'];
                $res1 = $this->query($sql2);

                $sql3 = "UPDATE tbl_product_master SET active_flag=".$flag." WHERE product_id=" . $params['prdid'];
                $res2 = $this->query($sql3);

                $sql4 = "UPDATE tbl_productid_generator SET active_flag=".$flag." WHERE product_id=" . $params['prdid'];
                $res3 = $this->query($sql4);

                $sql5 = "UPDATE tbl_product_category_mapping SET display_flag=".$flag." WHERE product_id=" . $params['prdid'];
                $res4 = $this->query($sql5);

                $sql6 = "UPDATE tbl_designer_product_mapping SET active_flag=".$flag." WHERE product_id=".$params['prdid'];
                $res5 = $this->query($sql6);

                $sql7 = "UPDATE tbl_product_enquiry SET active_flag=".$flag." WHERE product_id=".$params['prdid'];
                $res7 = $this->query($sql7);

            }
            else
            {
                $sql1 = "UPDATE tbl_vendor_product_mapping SET active_flag=".$params['flag']." WHERE product_id=" . $params['prdid']." AND vendor_id=" . $params['vid'];
                $res = $this->query($sql1);

                $sql2 = "UPDATE tbl_product_search SET active_flag=".$params['flag']." WHERE product_id=" . $params['prdid'];
                $res2 = $this->query($sql2);

                $sql3 = "UPDATE tbl_product_master SET active_flag=".$params['flag']." WHERE product_id=" . $params['prdid'];
                $res3 = $this->query($sql3);

                $sql4 = "UPDATE tbl_productid_generator SET active_flag=".$params['flag']." WHERE product_id=" . $params['prdid'];
                $res4 = $this->query($sql4);

                $sql5 = "UPDATE tbl_product_category_mapping SET display_flag=".$params['flag']." WHERE product_id=" . $params['prdid'];
                $res5 = $this->query($sql5);

                $sql6 = "UPDATE tbl_designer_product_mapping SET active_flag=".$params['flag']." WHERE product_id=".$params['prdid'];
                $res6 = $this->query($sql6);

                $sql7 = "UPDATE tbl_product_enquiry SET active_flag=".$params['flag']." WHERE product_id=".$params['prdid'];
                $res7 = $this->query($sql7);
            }

            if($res7)
            {
                $arr = array();
                $err = array('Code' => 0, 'Msg' => 'Product status changed!');
            }
            else
            {
                $arr = array();
                $err = array('code' => 1, 'msg' => 'Error in fetching data');
            }
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }

    public function getVPrdByCatid($params)
        {

			$page   = ($params['page'] ? $params['page'] : 1);
			$limit  = ($params['limit'] ? $params['limit'] : 15);

			$sql = "select
                      cat_name AS name
              from
                      tbl_category_master
              where
                      catid=\"".$params['catid']."\"";
			$res = $this->query($sql);
			if($res)
			{
				$row = $this->fetchData($res);
				$catname = $row['name'];
			}

      if(!empty($params['ilist']))
			{
				$ilist = str_replace('|@|',',',$params['ilist']);
				$where = " WHERE category_id in (".$ilist.") ";
			}
			else if(!empty($params['jlist']))
			{
				$expd = explode('|@|',$params['jlist']);
				foreach($expd as $key => $val)
				{
					$exd = explode('_',$val);
					$ids[] = $exd[0];
				}
				$ilist = implode(',',$ids);
				$where = " WHERE category_id in (".$ilist.") ";
			}
			else
			{
				$where = " WHERE category_id in (".$params['catid'].") ";
			}

			$sql = "SELECT
						count(distinct product_id) as cnt
					FROM
						tbl_product_category_mapping
					".$where."
					AND
              display_flag<>2
                                ";


			$res = $this->query($sql);
			if($res)
			{
				$row = $this->fetchData($res);
				$total = $row['cnt'];
			}

			$sql = "SELECT
						distinct product_id as pid,
						price
					FROM
						tbl_product_category_mapping
					".$where."
					AND display_flag<>2";

			switch($params['sortby'])
			{
				case 'pasc':
					$sql.="
							ORDER BY
								price ASC
						";
				break;

				case 'pdesc':
					$sql.="
							ORDER BY
								price DESC
						";
				break;

				case 'rate':
					$sql.="
							ORDER BY
								rating DESC
						";
				break;

				default:
					$sql.="
							ORDER BY
								product_id ASC
						";
				break;
			}

			$res = $this->query($sql);
			$cres=$this->numRows($res);
			if($cres>0)
			{
				while ($row = $this->fetchData($res))
				{
					$pid[] = $row['pid'];
				}



				if(!empty($params['slist']))
				{
					$sarr = explode('|@|',$params['slist']);
					$extn = " AND shape in ('".implode("','",$sarr)."') ";
				}

				if(!empty($params['tlist']))
				{
					$sarr = explode('|$|',$params['tlist']);
					foreach($sarr as $key => $val)
					{
						$expd = explode('|~|',$val);
						$exd = explode(';',$expd[1]);
						if($expd[0] == 'priceRange' && $params['catid'] == 10000)
						{
							$exd[0] = $exd[0]/dollarValue;
							$exd[1] = $exd[1]/dollarValue;
						}
						$extn .= " AND ".str_replace('Range','',$expd[0])." between \"".$exd[0]."\" AND \"".$exd[1]."\"";
					}
					//$extn = " AND shape in ('".implode("','",$sarr)."') ";
				}

				if(!empty($params['clist']))
				{
					$sarr = explode('|$|',$params['clist']);

					foreach($sarr as $key => $val)
					{
						$expd = explode('|~|',$val);
						$exd = explode('|@|',$expd[1]);
						$inarr = array();
						foreach($exd as $ky => $vl)
						{
							$ex = explode('_',$vl);
							$inarr[] = $ex[count($ex)-1];
							unset($ex[count($ex)-1]);
							$field = implode('_',$ex);
						}
						$extn .= " AND ".$field." in ('".implode("','",$inarr)."') ";
					}
				}

				$allpids = $pid = implode(',',$pid);

				if($params['ctid'])
				{
					$sqlct = "SELECT cityname
							FROM tbl_city_master
							WHERE cityid = ".$params['ctid'];
					$resct = $this->query($sqlct);
					if($resct)
					{
						$rowct = $this->fetchData($resct);

						$sqlpct = "
									SELECT
										product_id as pid,
                                                                                vendor_id as vid

									FROM
										tbl_vendor_product_mapping
									WHERE
										product_id in (".$allpids.")
									AND
                                                                                active_flag=1 OR active_flag=0
										city=\"".$rowct['cityname']."\"";
						$respct = $this->query($sqlpct);
						if($respct)
						{
							while ($rowpct = $this->fetchData($respct))
							{
								$pids[] = $rowpct['pid'];
                                                                $vid=$rowpct['vid'];
							}
							$allpids = $pid = implode(',',$pids);
						}
					}
				}

				$page   = ($params['page'] ? $params['page'] : 1);
				$limit  = ($params['limit'] ? $params['limit'] : 15);

				if($pid)
				{

					$sql = "SELECT
								count(distinct product_id) as cnt
							FROM
								tbl_product_search
							WHERE
								product_id IN(".$pid.")
                                                        AND
                                                                active_flag<>2
                                                        ".$extn."
							";
					$res = $this->query($sql);
					if($res)
					{
						$row = $this->fetchData($res);
						$total = $row['cnt'];
					}

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
								metal,
                                                                bullion_design
							FROM
								tbl_product_search
							WHERE
								product_id IN(".$pid.")
                                                        AND
                                                                active_flag<>2
                                                        ".$extn."
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
						unset($row2['product_id']);
						$attr[$pid]['attributes']=$row2;
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
                                                                active_flag<>2
                                                        ORDER BY
								field(product_id,".$pid.");
							";
					$pres=$this->query($psql);
					while($row1=$this->fetchData($pres))
					{
						$pid = $row1['pid'];
						$arr1[$pid]=$row1;
						$arr1[$pid]['attributes'] = $attr[$pid]['attributes'];
					}

					/* For filters */

					$sql = "
						SELECT
							attribute_id,
							attr_values,
							attr_range,
							attr_unit,
							attr_unit_pos
						FROM
							tbl_attribute_category_mapping
						WHERE
							category_id=".$params['catid']."
						AND
							attr_filter_flag=1
						ORDER BY
							attr_filter_position ASC";
					$res = $this->query($sql);
					if($res)
					{
						while($row 		= $this->fetchData($res))
						{
							$attrid[] = $row['attribute_id'];
							$attrmap[$row['attribute_id']] = $row;
						}
						$attrids 	= implode(',',$attrid);
					}
                                            $sql="
						SELECT
							attr_id,
							attr_name,
							attr_display_name,
							attr_type_flag
						FROM
							tbl_attribute_master
						WHERE
							attr_id IN(".$attrids.")
						ORDER BY
							attr_display_name ASC";

					$res = $this->query($sql);

					if($res)
					{
						$i=0;
						while($row = $this->fetchData($res))
						{
							switch ($row['attr_type_flag'])
							{
								case 6: //$pids
									$qry = "SELECT
												MIN(".$row['attr_name'].") AS minval,
												MAX(".$row['attr_name'].") AS maxval
											FROM
												tbl_product_search
											WHERE
												product_id IN(".$allpids.")
                                                                                        AND
                                                                                                active_flag<>2
                                                                                        ";
									$res1 = $this->query($qry);
									if($res1)
									{
										$row1 = $this->fetchData($res1);
										$data[$i]['range']['id'] 		= $row['attr_id'];
										$data[$i]['range']['name'] 		= $row['attr_name'];
										$data[$i]['range']['dname'] 	= $row['attr_display_name'];
										$data[$i]['range']['value'] 	= $row1['minval'].';'.$row1['maxval'];
										$data[$i]['range']['ovalue'] 	= $attrmap[$row['attr_id']]['attr_range'];
										$i++;
									}
								break;

								case 7:

									$qry = "SELECT
												group_concat(DISTINCT ".$row['attr_name'].") as name
											FROM
												tbl_product_search
											WHERE
												product_id IN(".$allpids.")
                                                                                        AND
                                                                                                active_flag<>2
											";
									$res1 = $this->query($qry);
									if($res1)
									{
										$arr = array();
										$row1 = $this->fetchData($res1);
										$expd = explode(',',$attrmap[$row['attr_id']]['attr_values']);
										$expd1 = explode(',',$row1['name']);
										foreach($expd1 as $key=>$val)
										{
											$arr[array_search($val,$expd)] = $val;
										}
										ksort($arr);
										$arr = array_values($arr);
										$data[$i]['checkbox']['id'] 	= $row['attr_id'];
										$data[$i]['checkbox']['name'] 	= $row['attr_name'];
										$data[$i]['checkbox']['dname'] 	= $row['attr_display_name'];
										$data[$i]['checkbox']['value'] 	= implode(',',$arr);
										$data[$i]['checkbox']['ovalue'] = $attrmap[$row['attr_id']]['attr_values'];
										$i++;
									}
								break;

								case 8:
								break;
							}
						}
					}

					/* *********** */

					$tmp_arr1 = (!empty($arr1)) ? (array_values($arr1)) : null;
					$arr1 = array('filters'=>$data,'products'=>$tmp_arr1,'total'=>$total,'getdata'=>$params,'catname'=>$catname,'vid'=>$vid);
					$err = array('errCode'=>0,'errMsg'=>'Details fetched successfully');
				}
				else
				{
					$arr1 = array();
					$err = array('errCode'=>1,'errMsg'=>'No records found');
				}
			}
			else
			{
				$arr1 = array();
				$err = array('errCode'=>1,'errMsg'=>'No records found');
			}
            $result = array('results'=>$arr1,'error'=>$err);
            return $result;
        }

    public function updateDollerRate($params)
    {
        $rates = $this->getAllRatesByVID($params['vid']);
        $emailsql = "SELECT email from tbl_registration WHERE user_id =".$params['vid'];
        $emailres = $this->query($emailsql);
        $emailcnt = $this->numRows($emailres);
        if($emailcnt > 0 )
        {
           $row = $this->fetchData($emailres);
        }

        $prevsql = "SELECT dollar_rate from tbl_vendor_master WHERE vendor_id =".$params['vid'];
        $prevres = $this->query($prevsql);
        $prevrow = $this->fetchData($prevres);

        if(floatval($params['dolRate']) !== floatval($prevrow['dollar_rate']))
        {   
            $sql="UPDATE tbl_vendor_master SET dollar_rate=".$params['dolRate']." WHERE vendor_id=".$params['vid'];
            $res=$this->query($sql);
            if ($res)
            {
                $temp = array('rate'=>'dollar_rate','vid'=>$params['vid'],'type'=>'Dollar','prevRate'=>floatval($prevrow['dollar_rate']),'to'=>$row['email']);
                $mail = $this->sendRateMail($temp);
                if($mail == 1)
                {
                    $arr = array();
                    $err = array('code' => 0, 'msg' => 'Dollar Rate Updated Successfully!');
                }
                else
                {
                    $arr = array();
                    $err = array('code' => 1, 'msg' => 'Error in Updating Dollar Rate');
                }
            }
            else
            {
                $arr = array();
                $err = array('code' => 1, 'msg' => 'Error in Updating Dollar Rate');
            }
        }
        else
        {
            $arr = array('There is no change in the rate');
            $err = array('code' => 0, 'msg' => 'Dollar Rate Updated successfully!');
        }
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }

    public function getDollerRate($params) {
        $sql="SELECT dollar_rate FROM tbl_vendor_master WHERE vendor_id='".$params['vid']."'";
        $res=$this->query($sql);
        if ($res) {
            $row = $this->fetchData($res);
            $arr = $row;
            $err = array('code' => 0, 'msg' => 'Dollar Rate Updated successfully!');
        } else {
            $arr = array();
            $err = array('code' => 1, 'msg' => 'Error in Updating Dollar Rate');
        }
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }

        public function updateSilverRate($params)
        {
            $emailsql = "SELECT email from tbl_registration WHERE user_id =".$params['vid'];
            $emailres = $this->query($emailsql);
            $emailcnt = $this->numRows($emailres);
            if($emailcnt > 0 )
            {
                $row = $this->fetchData($emailres);
            }

            $prevsql = "SELECT silver_rate from tbl_vendor_master WHERE vendor_id =".$params['vid'];
            $prevres = $this->query($prevsql);
            $prevrow = $this->fetchData($prevres);

            if(floatval($params['silRate']) !== floatval($prevrow['silver_rate']))
            {
                $sql="UPDATE tbl_vendor_master SET silver_rate=".$params['silRate']." WHERE vendor_id=".$params['vid'];
                $res=$this->query($sql);
                if($res)
                {
                    $temp = array('rate'=>'silver_rate','vid'=>$params['vid'],'type'=>'Silver','prevRate'=>$prevrow['silver_rate'],'to'=>$row['email']);
                    $mail = $this->sendRateMail($temp);
                    if($mail == 1)
                    {
                        $arr = array();
                        $err = array('code' => 0, 'msg' => 'Silver Rate Updated successfully!');
                    }
                    else
                    {
                        $arr = array();
                        $err = array('code' => 1, 'msg' => 'Error in Updating Silver Rate');
                    }
                }
                else
                {
                    $arr = array();
                    $err = array('code' => 1, 'msg' => 'Error in Updating Silver Rate');
                }
            }
            else
            {
                $arr = array('There is no change in the rate');
                $err = array('code' => 0, 'msg' => 'Silver Rate Updated successfully!');
            }
            $result = array('results' => $arr, 'error' => $err);
            return $result;
        }

        public function updatePlatinumRate($params)
        {
            $emailsql = "SELECT
                                email
                         FROM
                                tbl_registration
                         WHERE
                                user_id =".$params['vid'];

            $emailres = $this->query($emailsql);
            $emailcnt = $this->numRows($emailres);

            if($emailcnt > 0 )
            {
                $row = $this->fetchData($emailres);
            }

            $prevsql = "SELECT
                                platinum_rate
                        FROM
                                tbl_vendor_master
                        WHERE
                                vendor_id =".$params['vid'];
            $prevres = $this->query($prevsql);
            $prevrow = $this->fetchData($prevres);

            if(floatval($params['platRate']) !== floatval($prevrow['platinum_rate']))
            {
                $sql="UPDATE
                              tbl_vendor_master
                      SET
                              platinum_rate=".$params['platRate']."
                      WHERE
                              vendor_id=".$params['vid'];
                $res=$this->query($sql);
                if($res)
                {
                    $temp = array
                                (
                                    'rate'=>'platinum_rate',
                                    'vid'=>$params['vid'],
                                    'type'=>'Platinum',
                                    'prevRate'=>$prevrow['platinum_rate'],
                                    'to'=>$row['email']
                                );
                    $mail = $this->sendRateMail($temp);
                    if($mail == 1)
                    {
                        $arr = array();
                        $err = array
                                    (
                                        'code' => 0,
                                        'msg' => 'Silver Rate Updated successfully!'
                                    );
                    }
                    else
                    {
                        $arr = array();
                        $err = array
                                    (
                                        'code' => 1,
                                        'msg' => 'Error in Updating Silver Rate'
                                    );
                    }
                }
                else
                {
                    $arr = array();
                    $err = array
                                (
                                    'code' => 1,
                                    'msg' => 'Error in Updating Silver Rate'
                                );
                }
            }
            else
            {
                $arr = array
                            (
                                'There is no change in the rate'
                            );
                $err = array
                            (
                                'code' => 0,
                                'msg' => 'Silver Rate Updated successfully!'
                            );
            }
            $result = array
                            (
                                'results' => $arr,
                                'error' => $err
                            );
            return $result;
        }

        public function getSilverRate($params)
        {
            $sql="SELECT
                          silver_rate
                  FROM
                          tbl_vendor_master
                  WHERE
                          vendor_id='".$params['vid']."'";
            $res=$this->query($sql);
            if ($res)
            {
                $row = $this->fetchData($res);
                $arr = $row;
                $err = array('code' => 0, 'msg' => 'Silver Rate Updated successfully!');
            }
            else
            {
                $arr = array();
                $err = array('code' => 1, 'msg' => 'Error in Updating Silver Rate');
            }
            $result = array('results' => $arr, 'error' => $err);
            return $result;
        }

        public function updateGoldRate($params)
        {
            $emailsql = "SELECT
                                email
                         FROM
                                tbl_registration
                         WHERE
                                user_id =".$params['vid'];
            $emailres = $this->query($emailsql);
            $emailcnt = $this->numRows($emailres);
            $row = $this->fetchData($emailres);

            $prevsql = "SELECT
                                gold_rate
                        FROM
                                tbl_vendor_master
                        WHERE
                                vendor_id =".$params['vid'];
            $prevres = $this->query($prevsql);
            $prevrow = $this->fetchData($prevres);
            $temp = array
                          (
                              'rate'=>'gold_rate',
                              'vid'=>$params['vid'],
                              'type'=>'Gold',
                              'prevRate'=>$prevrow['gold_rate'],
                              'to'=>$row['email']
                          );
            if(floatval($params['goldRate']) !== floatval($prevrow['gold_rate']))
            {
                $sql="  UPDATE
                                    tbl_vendor_master
                        SET
                                    gold_rate=".$params['goldRate']."
                        WHERE
                                    vendor_id=".$params['vid'];
                $res=$this->query($sql);
                if ($res)
                {
                    $mail = $this->sendRateMail($temp);
                    if($mail == 1)
                    {
                        $arr = array();
                        $err = array('code' => 0, 'msg' => 'Gold Rate Updated Successfully!');
                    }
                    else
                    {
                        $arr = array();
                        $err = array('code' => 1, 'msg' => 'Error in Updating Gold Rate');
                    }
                }
                else
                {
                    $arr = array();
                    $err = array('code' => 1, 'msg' => 'Error in Updating Gold Rate');
                }
            }
            else
            {
                $arr = array('There is no change in the rate');
                $err = array('code' => 0, 'msg' => 'Gold Rate Updated Successfully!');
            }
            $result = array('results' => $arr, 'error' => $err);
            return $result;
        }

        public function getGoldRate($params)
        {
            $sql="SELECT
                          gold_rate,
                          silver_rate
                  FROM
                          tbl_vendor_master
                  WHERE
                          vendor_id=".$params['vid'];
            $res=$this->query($sql);
            if ($res)
            {
                $row = $this->fetchData($res);
                $arr = $row;
                $err = array('code' => 0, 'msg' => 'Gold Rate Updated successfully!');
            }
            else
            {
                $arr = array();
                $err = array('code' => 1, 'msg' => 'Error in Updating Gold Rate');
            }
            $result = array('results' => $arr, 'error' => $err);
            return $result;
        }

        public function getAllRatesByVID($params)
        {
            $sql="SELECT
                          silver_rate,
                          gold_rate,
                          dollar_rate,
                          platinum_rate
                   FROM
                          tbl_vendor_master
                   WHERE
                          vendor_id='".$params['vid']."'";
            $res=$this->query($sql);
            if ($res)
            {
                $row = $this->fetchData($res);
                $arr = $row;
                $err = array('code' => 0, 'msg' => 'Rates fetched successfully!');
            }
            else
            {
                $arr = array();
                $err = array('code' => 1, 'msg' => 'Error in fetching Rates');
            }
            $result = array('results' => $arr, 'error' => $err);
            return $result;
        }

        /*public function Vpactive($params)
        {

            $vprds="SELECT
                            product_id
                    FROM
                            tbl_vendor_product_mapping
                    WHERE
                            vendor_id=".$params['vid'];
            $vprdsres=$this->query($vprds);
            $cntvpres=$this->numRows($vprdsres);

            if($cntvpres>0)
            {
                while($chkrow=$this->fetchData($chkactres))
                {
                    $pid[] = $chkrow['product_id'];
                }
                $prid=implode(',',$pid);

                $sql1 = "UPDATE
                                  tbl_vendor_product_mapping
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  vendor_id=".$params['vid']."
                         AND
                                  active_flag NOT IN(2,3)";
                $res = $this->query($sql1);

                $sql2 = "UPDATE
                                  tbl_product_search
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  active_flag NOT IN(2,3)";
                $res1 = $this->query($sql2);
                $sql3 = "UPDATE
                                  tbl_product_master
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  active_flag NOT IN(2,3)";
                $res2 = $this->query($sql3);

                $sql4 = "UPDATE
                                  tbl_productid_generator
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  active_flag NOT IN(2,3)";
                $res3 = $this->query($sql4);

                $sql5 = "UPDATE
                                  tbl_product_category_mapping
                         SET
                                  display_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  display_flag NOT IN(2,3)";
                $res4 = $this->query($sql5);

                $sql6 = "UPDATE
                                  tbl_designer_product_mapping
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  active_flag NOT IN(2,3)";
                $res5 = $this->query($sql6);

                $sql7 = "UPDATE
                                  tbl_product_enquiry
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  active_flag NOT IN(2,3)";
                $res6 = $this->query($sql7);

                $arr=array();
                $err=array('code'=>0,'msg'=>'Product status changed too');
            }
            else
            {
                $arr=array();
                $err=array('code'=>0,'msg'=>'Product status changed too');
            }
            $result=array('result'=>$arr,'error'=>$err);
            return $result;
        }*/

        public function Vpactive($params)
        {

            $vprds="SELECT
                            product_id
                    FROM
                            tbl_vendor_product_mapping
                    WHERE
                            vendor_id=".$params['vid'];
            $vprdsres=$this->query($vprds);
            $cntvpres=$this->numRows($vprdsres);

            if($cntvpres>0)
            {
                while($chkrow=$this->fetchData($chkactres))
                {
                    $pid[] = $chkrow['product_id'];
                }
                $prid=implode(',',$pid);

                $sql1 = "UPDATE
                                  tbl_vendor_product_mapping
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  vendor_id=".$params['vid']."
                         AND
                                  active_flag NOT IN(2,3)";
                $res = $this->query($sql1);

                $sql2 = "UPDATE
                                  tbl_product_search
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  active_flag NOT IN(2,3)";
                $res1 = $this->query($sql2);
                $sql3 = "UPDATE
                                  tbl_product_master
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  active_flag NOT IN(2,3)";
                $res2 = $this->query($sql3);

                $sql4 = "UPDATE
                                  tbl_productid_generator
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  active_flag NOT IN(2,3)";
                $res3 = $this->query($sql4);

                $sql5 = "UPDATE
                                  tbl_product_category_mapping
                         SET
                                  display_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  display_flag NOT IN(2,3)";
                $res4 = $this->query($sql5);

                $sql6 = "UPDATE
                                  tbl_designer_product_mapping
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  active_flag NOT IN(2,3)";
                $res5 = $this->query($sql6);

                $sql7 = "UPDATE
                                  tbl_product_enquiry
                         SET
                                  active_flag=".$params['af']."
                         WHERE
                                  product_id IN(".$prid.")
                         AND
                                  active_flag NOT IN(2,3)";
                $res6 = $this->query($sql7);
                
                $sql8=  "SELECT
                                user_name ,
                                email,
                                logmobile
                        FROM
                                tbl_registration 
                        WHERE 
                                user_id=".$params['vid']." ";
                $res8    =  $this->query($sql8);   
                $rowData = $this->fetchData($res8);
               
                if($params['af']==0)
                {
                    $subject .= 'Vendor profile deactivation in IFtoSI';
                    $message .=$this->sendDeactivateMailByAdmin($rowData);
                    $headers .= "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: <info@iftosi.com>' . "\r\n";

                    $smsText .= "Vendor profile deactivation in IFtoSI";
                    $smsText .= "\r\n\r\n";
                    $smsText .= "Dear ".urldecode($rowData[user_name]).", Your Profile has been deactivated.For more information contact admin.";
                    $smsText .= "\r\n\r\n";
                    $smsText .= "For any assistance, call: 91-22-41222241/42. Email: info@iftosi.com";
                    $smsText .= "\r\n\r\n";
                    $smsText .= "Team IFtoSI";
                    mail(urldecode($rowData['email']), $subject, $message, $headers);
                    $smsText = urlencode($smsText);
                    $sendSMS = str_replace('_MOBILE', urldecode($rowData['logmobile']), SMSAPI);
                    $sendSMS = str_replace('_MESSAGE', $smsText, $sendSMS);
                    $res = $comm->executeCurl($sendSMS, true);
                    if($res)
                    {
                    $arr = array('success');
                    $err = array('code'=>0,'msg'=>'SMS & EMAIL sent to the user');
                    }
                    else
                    {
                    $arr = array('failure');
                    $err = array('code'=>0,'msg'=>'SMS & EMAIL is not sent to the user');
                    }
                }
            
                $arr=array();
                $err=array('code'=>0,'msg'=>'Product status changed too');
            }
            else
            {
                $arr=array();
                $err=array('code'=>0,'msg'=>'Product status changed too');
            }
            $result=array('result'=>$arr,'error'=>$err);
            return $result;
        }
        
        public function sendDeactivateMailByAdmin($params)
        {
            $message='<html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                            <meta name="viewport" content="width=device-width, user-scalable=no" >
                            <title>deactivate</title>
                        </head>
                        <body style="margin:0; padding: 0; background-color: #171334;">
                            <center>
                                <div style="text-align: center; height: auto; font-size: 1em; margin:0; max-width: 500px; color:#666;-webkit-font-smoothing: antialiased;font-family: Open Sans, Roboto, Helvetica, Arial;">
                                <a href="'.DOMAIN.'"><div style="vertical-align: top; height: auto; display: inline-block; padding:15px 0 15px 0; text-align: center;color: #d00000; text-transform: uppercase"><img src="'.DOMAIN.'tools/img/iftosi.png" style="width:100%;"></div></a>
                                <div style="height: auto; border-radius: 0px;box-shadow: 0 0 30px 5px rgba(0,0,0,0.4);background: #fff;">
                                <div  style="font-size: 20px; padding: 40px 10px 5px 10px; color:#333;text-transform: capitalize;">vendor profile deactivation</div>
                                <a href="'.DOMAIN.'"><div style="vertical-align: top; height: auto; display: inline-block; padding:20px 0 20px 0;text-align: center;color: #d00000; text-transform: uppercase;padding: 20px 0 20px 0;"><img src="'.DOMAIN.'tools/img/common/Deactivation.png" style="width:50px;"></div></a>
                                <center style="padding: 0px 50px 0px 50px;line-height: 30px;    font-size: 23px;    padding-top: 30px;    font-weight: 100;    color: #333;">
                                    <div style="font-size: 20px; padding: 50px 10px 7px 10px;color:#333;">Dear '.$params['user_name'].'</div>
                                    Your Profile has been deactivated.For more information contact admin.
                            </center>
                            <center style="padding-top: 50px;">
                                <img src="'.DOMAIN.'tools/img/common/diamond.jpg" width="50">
                                <img src="'.DOMAIN.'tools/img/common/jewellery.jpg" width="50">
                                <img src="'.DOMAIN.'tools/img/common/bullions.jpg" width="50">
                            </center>
                            <div style="height:auto;line-height: 22px; color:#333; font-size: 13px;padding: 25px 15px 40px 15px;">For any assistance, <br>Call: <a href="tel:91-22-41222241/42" style="text-transform: uppercase; width:auto;display: inline-block; font-weight: bold; color:#333; text-decoration: none; ">91-22-41222241 (42)</a> | Email: <b>neeraj@iftosi.com</b></div>
                            </div>
                            <div style="color:#fff;font-size:15px;padding: 20px 0">Team <b>IF</b>to<b>SI</b>.com</div>
                            </div>
                            </center>
                            </body>
                            </html>';
            return $message;


        }
        
        
        
        
        private function getAbbrValue($val)
        {
            $propValArr=array(''=>'','GD/DIA'=>'GOLD & DIAMONDS','PT/DIA'=>'PLATINUM & DIAMONDS','SL/DIA'=>'SILVER & DIAMONDS','GD/DIA/CS'=>'GOLD, DIAMONDS & GEMSTONES','PT/DIA/CS'=>'PLATINUM, DIAMONDS & GEMSTONES','SL/DIA/CS'=>'SILVER, DIAMONDS & GEMSTONES','GD/CS'=>'GOLD & GEMSTONES','SL/CS'=>'SILVER & GEMSTONES','GD/SWCZ'=>'GOLD & SWAROVSKI ZIRCONIA','SL/SWCZ'=>'SILVER & SWAROVSKI ZIRCONIA','PLAIN GD'=>'GOLD & CZ','SL/CZ'=>'SILVER & CZ','PLAIN GD'=>'PLAIN GOLD','PLAIN PT'=>'PLAIN PLATINUM','PLAIN SL'=>'PLAIN SILVER','GD/POL'=>'GOLD & POLKI');
            return $propValArr[$val];
        }

        private function getShapeAbbrValue($val)
        {
            $propValArr=array(''=>'','MQ'=>'Marquise','Marquise'=>'Marquise','Asscher'=>'Asscher','Round'=>'Round','RO' => 'Round','RBC' => 'Round','BR'=> 'Round','RD'=> 'Round','RND'=> 'Round','B'=> 'Round','RB'=> 'Round','PRN' => 'Princess','Princess'=>'Princess','PR'=> 'Princess','PRIN'=> 'Princess','PN'=> 'Princess','PC'=> 'Princess','MDSQB'=> 'Princess','SMB'=> 'Princess','PS' => 'Pear','Pear'=>'Pear','PSH'=> 'Pear','PB'=> 'Pear','PM'=> 'Pear','HS' => 'Heart','Heart'=>'Heart','HT'=> 'Heart','MHRC'=> 'Heart','OV' => 'Oval','Oval'=>'Oval','OMB' => 'Oval','EM' => 'Emerald','EC' => 'Emerald','Radiant'=>'Radiant','Cushion' => 'Cushion','CUBR' => 'CUSHION','CUMOD' => 'CUSHION');
            return $propValArr[$val];
        }

        public function sendRateMail($params)
        {
            global $comm;
            $sql = "SELECT
                            orgName,
                            contact_person,
                            ".$params['rate']."
                    FROM
                            tbl_vendor_master
                    WHERE
                            vendor_id = ".$params['vid'];
            $res = $this->query($sql);
            $chkV = $this->numRows($res);
            if($chkV == 1)
            {
                while($row = $this->fetchData($res))
                {
                    $vDet['org_name'] = $row['orgName'];
                    $vDet['C_person'] = $row['contact_person'];
                    $vDet['cur_rate'] = $row[$params['rate']];
                }
                $message = $this->sendRateMailTemplate($vDet,$message,$params['type'],$params);
                $subject = $params['type'].' Rate for IFtoSI';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: <info@iftosi.com>' . "\r\n";
                if(!empty($params['to']))
                {
                    mail($params['to'], $subject, $message, $headers);
                    return 1;
                }
                else
                {
                    return 0;
                }

            }
            else
            {
                return 0;
            }
    }

        public function sendRateMailTemplate($vDet,$subject,$type,$params)
        {
           $message  ='<html>
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                                <meta name="viewport" content="width=device-width, user-scalable=no" >
                                <title>IFtoSI E-mail</title>
                            </head>
                            <body style="margin:0; padding: 0; background-color: #171334;">
                              <center>
                                <div style="text-align: center; height: auto; font-size: 1em; margin:0; max-width: 500px; letter-spacing: -0.02em; color:#666;-webkit-font-smoothing: antialiased;font-family: Open Sans, Roboto, Helvetica, Arial;">
                                    <a href="'.DOMAIN.'">
                                    <div style="vertical-align: top; height: auto; display: inline-block; padding:15px 0 15px 0; text-align: center;color: #d00000; text-transform: uppercase"><img src="'.DOMAIN.'tools/img/iftosi.png" style="width:100%;"></div>
                                    </a>
                                    <div style="height: auto; border-radius: 0px;box-shadow: 0 0 30px 5px rgba(0,0,0,0.4);background: #fff;">
                                        <div  style="font-size: 20px;letter-spacing: -0.03em;    padding: 40px 10px 5px 10px; color:#333;text-transform: capitalize;">change in price</div>
                                        <a><div style="vertical-align: top; height: auto; display: inline-block; padding:20px 0 20px 0;text-align: center;color: #d00000; text-transform: uppercase">';
                                        if($type !== 'Dollar')
                                        {
                                         
                                            $message.='<img src="'.DOMAIN.'tools/img/common/01.png" style="width:70%;"></div></a>'; // for silver,gold,platinum
                                        }
                                        else
                                        {
                                            $message.='<img src="'.DOMAIN.'tools/img/common/02.png" style="width:70%;"></div></a>'; // for diamond
                                        }

                                        $message.='<div style="font-size: 18px;letter-spacing: -0.03em;    padding: 15px 10px 10px 10px; color:#8A0044;">Dear '.$vDet["C_person"].',</div>
                                        <div style="font-family: Open Sans, Roboto, Helvetica, Arial;font-size: 18px; color: #333;padding: 0px 15px 40px 15px;">Your '.strtolower($params["type"]).' rate has changed from</div>
                                        <center style="padding: 0px 30px 20px 30px;">';
                                            
                                        if($type !== 'Dollar')
                                        {
                                              $message.=  '<div style="width: 41%;display: inline-block; border-right: 1px solid #f0f0f0;">
                                                <div style="font-size: 18px;text-transform: capitalize;color: #666;padding-bottom:5PX;font-family: Open Sans, Roboto, Helvetica, Arial;">previous rate</div>
                                                <span style="font-size: 20px;text-transform: capitalize;padding-bottom:5PX;color: #8A0044;font-weight: bold;"><img src="'.DOMAIN.'tools/img/common/Rupee15.png" align="middle" style="width:25px;vertical-align:bottom;height:25px;">'.$params["prevRate"].'</span>
                                            </div>
                                             <div style="width: 41%;display: inline-block;">
                                                <div style="font-size: 18px;text-transform: capitalize;color: #666;padding-bottom:5PX;font-family: Open Sans, Roboto, Helvetica, Arial;">current rate</div>
                                                <span style="font-size: 20px;text-transform: capitalize;padding-bottom:5PX;color: #8A0044;font-weight: bold;"><img src="'.DOMAIN.'tools/img/common/Rupee15.png" align="middle" style="width: 25px;vertical-align:bottom;height:25px;">'.$vDet['cur_rate'].'</span>
                                            </div>
                                            </center>';
                                            }
                                        else
                                        {
                                              $message.=  '<div style="width: 41%;display: inline-block;    border-right: 1px solid #f0f0f0;">
                                                <div style="font-size: 18px;text-transform: capitalize;color: #666;padding-bottom:5PX;font-family: Open Sans, Roboto, Helvetica, Arial;">previous rate</div>
                                                <span style="font-size: 20px;text-transform: capitalize;padding-bottom:5PX;color: #8A0044;font-weight: bold;"><img src="'.DOMAIN.'tools/img/common/dollar.png" align="middle" style="width:25px;vertical-align:bottom;height:25px;">'.$params["prevRate"].'</span>
                                            </div>
                                             <div style="width: 41%;display: inline-block;">
                                                <div style="font-size: 18px;text-transform: capitalize;color: #666;padding-bottom:5PX;font-family: Open Sans, Roboto, Helvetica, Arial;">current rate</div>
                                                <span style="font-size: 20px;text-transform: capitalize;padding-bottom:5PX;color: #8A0044;font-weight: bold;"><img src="'.DOMAIN.'tools/img/common/dollar.png" align="middle" style="width: 25px;vertical-align:bottom;height:25px;">'.$vDet['cur_rate'].'</span>
                                            </div>
                                            </center>';
                                            }
                                        
                                            $message.='<center style="padding-top: 50px;">
                                            <img src="'.DOMAIN.'tools/img/common/diamond.jpg" width="50">
                                            <img src="'.DOMAIN.'tools/img/common/jewellery.jpg" width="50">
                                            <img src="'.DOMAIN.'tools/img/common/bullions.jpg" width="50">
                                        </center>
                                        <div style="height:auto;line-height: 22px; color:#333; font-size: 13px;padding: 25px 15px 40px 15px;">For any assistance, <br>Call: <a href="tel:91-22-41222241/42" style="text-transform: uppercase; width:auto;display: inline-block; font-weight: bold; color:#333; text-decoration: none; letter-spacing: 0.02em;">91-22-41222241 (42)</a> | Email: <b>neeraj@iftosi.com</b></div>
                                    </div>
                                    <div style="color:#fff;font-size:15px;padding: 20px 0">Team <b>IF</b>to<b>SI</b>.com</div>
                                </div>
                            </center>
                        </body>
                  </html>';
          return $message;
        }
        
        
    public function validateJewelFields($params)
    {
        $c = 1;
        if(!empty($params[0]) || !empty($params[2]) || !empty($params[5]) || !empty($params[27]) || !empty($params[28]) || !empty($params[29]) || !empty($params[30]) || !empty($params[31]))
        {
            $c = 0;
        }
        else if(!empty($params[0]) && !empty($params[2]) && !empty($params[5]) && !empty($params[27]) && !empty($params[28]) && !empty($params[29]) && !empty($params[30]) && !empty($params[31]))
        {
            switch($params['2'])
            {
              case 'GOLD & DIAMONDS':
                      if(!empty($params[3]) || !empty($params[5]) || !empty($params[11]) || !empty($params[12]) || !empty($params[13]) || !empty($params[14]) || !empty($params[15]) || !empty($params[16]) || !empty($params[17]))
                      {
                          $c = 0;
                      }
                      break;
              case 'PLATINUM & DIAMONDS':
                      if(!empty($params[3]) || !empty($params[11]) || !empty($params[12]) || !empty($params[13]) || !empty($params[14]) || !empty($params[15]) || !empty($params[16]) || !empty($params[17]))
                      {
                          $c = 0;
                      }
                      break;
              case 'SILVER & DIAMONDS':
                      if(!empty($params[3]) || !empty($params[11]) || !empty($params[12]) || !empty($params[13]) || !empty($params[14]) || !empty($params[15]) || !empty($params[16]) || !empty($params[17]))
                      {
                          $c = 0;
                      }
                      break;
              case 'GOLD, DIAMONDS & GEMSTONES':
                      if(!empty($params[3]) || !empty($params[5]) || !empty($params[11]) || !empty($params[12]) || !empty($params[13]) || !empty($params[14]) || !empty($params[15]) || !empty($params[16]) || !empty($params[17]) || !empty($params[18]) || !empty($params[19]) || !empty($params[20]) || !empty($params[21]) || !empty($params[22]))
                      {
                          $c = 0;
                      }
                      break;
              case 'PLATINUM, DIAMONDS & GEMSTONES':
                      if(!empty($params[3]) || !empty($params[11]) || !empty($params[12]) || !empty($params[13]) || !empty($params[14]) || !empty($params[15]) || !empty($params[16]) || !empty($params[17]) || !empty($params[18]) || !empty($params[19]) || !empty($params[20]) || !empty($params[21]) || !empty($params[22]))
                      {
                          $c = 0;
                      }
                      break;
              case 'SILVER, DIAMONDS & GEMSTONES':
                      if(!empty($params[3]) || !empty($params[11]) || !empty($params[12]) || !empty($params[13]) || !empty($params[14]) || !empty($params[15]) || !empty($params[16]) || !empty($params[17]) || !empty($params[18]) || !empty($params[19]) || !empty($params[20]) || !empty($params[21]) || !empty($params[22]))
                      {
                          $c = 0;
                      }
                      break;
              case 'GOLD & GEMSTONES':
                      if(!empty($params[3]) || !empty($params[5]) || !empty($params[18]) || !empty($params[19]) || !empty($params[20]) || !empty($params[21]) || !empty($params[22]))
                      {
                          $c = 0;
                      }
                      break;
              case 'SILVER & GEMSTONES':
                      if(!empty($params[18]) || !empty($params[19]) || !empty($params[20]) || !empty($params[21]) || !empty($params[22]))
                      {
                          $c = 0;
                      }
                      break;
              case 'GOLD & SWAROVSKI ZIRCONIA':
                      if(!empty($params[3]) || !empty($params[5]) || !empty($params[18]) || !empty($params[19]) || !empty($params[20]) || !empty($params[21]) || !empty($params[22]))
                      {
                          $c = 0;
                      }
                      break;
              case 'SILVER & SWAROVSKI ZIRCONIA':
                      if(!empty($params[18]) || !empty($params[19]) || !empty($params[20]) || !empty($params[21]) || !empty($params[22]))
                      {
                          $c = 0;
                      }
                      break;
              case 'GOLD & CZ':
                      if(!empty($params[3]) || !empty($params[5]) || !empty($params[18]) || !empty($params[19]) || !empty($params[20]) || !empty($params[21]) || !empty($params[22]))
                      {
                          $c = 0;
                      }
                      break;
              case 'SILVER & CZ':
                      if(!empty($params[18]) || !empty($params[19]) || !empty($params[20]) || !empty($params[21]) || !empty($params[22]))
                      {
                          $c = 0;
                      }
                      break;
              case 'PLAIN GOLD':
                      if(!empty($params[3]) || !empty($params[5]))
                      {
                          $c = 0;
                      }
                      break;
              default:
                      break;
            }
        }
        return $c;
    }
    
    
     public function getVendorList()
    {
          $sql = "SELECT
                            orgName,
                            fulladdress,
                            city,
                            country,
                            state,
                            telephones,
                            officecity,
                            contact_person,
                            contact_mobile,
                            memship_Cert,
                            showroom_name,
                            no_showrooms
                           
                    FROM
                            tbl_vendor_master
                    WHERE
                            active_flag=1
                    AND  
                            is_complete=2 ";
           
           $res = $this->query($sql);
           $row = $this->numRows($res);
           $data= $this->fetchData($res);
           
            if($row>0)
            {    
                $arr = array('Success:');
                $err = array('code'=>0,'msg'=>'Details Fetched Successfully !');
            }
            else 
            {
                $arr = array('Error:');
                $err = array('code'=>0,'msg'=>'Vendor Not Found !');
            }
        
            $result=array('result'=>$arr,'error'=>$err);
            return $result;    
            
        }

        public function showVendorList($params)
        {
             $uid = (!empty($params['uid'])) ? trim(urldecode($params['uid'])) : '';
           
           if($uid)
           {
                    $sql= "SELECT
                                        user_name,
                                        logmobile,
                                        email,
                                        city
                                FROM 
                                        tbl_registration
                                WHERE 
                                        is_active=1
                                AND
                                       user_id= " . $params['uid'] . "";
                               
                        $res = $this->query($sql);
                        $row = $this->numRows($res);
                            if($row>0)
                            {
                                $arr = array('Error:');
                                $err = array('code'=>0,'msg'=>'User Details Fetched Sucessfully !');
                            }
                            else
                            {
                                $arr = array('Error:');
                                $err = array('code'=>0,'msg'=>'Error in fetching user details !');
                            }
            }
            else
            {
                
                $arr = array('Error:');
                $err = array('code'=>0,'msg'=>'User Not Found !');
            }
         
            $result=array('result'=>$arr,'error'=>$err);
            return $result;  
            
        } 
        
         public function showVendorProductList($params)
        {
             $vid = (!empty($params['vid'])) ? trim(urldecode($params['vid'])) : '';
           
           if($vid)
           {
                    $sql= "select
                                                                                product_id,
                                    						diamond_shape,
                                    						carat,
                                    						color,
                                    						certified,
                                                                                other_certificate,
                                                                                certificate_url,
                                    						metal,
                                    						shape,
                                    						clarity,
                                    						price,
                                    						polish,
                                    						symmetry,
                                    						cno,
                                    						cut,
                                    						nofd,
                                    						gemwt,
                                    						gold_purity,
                                    						(dwt-baguette_weight) AS dwt,
                                    						fluo as fluorescence,
                                    						measurement,
                                    						td as tab,
                                    						gold_weight,
                                    						gemstone_color,
                                    						gemstone_type,
                                    						quality,
                                    						cr_ang as crownangle,
                                    						girdle,
                                    						base as baseprice,
                                    						p_disc as discount,
                                    						p_discb2b as discountb2b,
                                    						b2b_price as b2bprice,
                                    						type,
                                                                                combination,
                                    						bullion_design,
                                    						tabl as tab,
                                    						num_gemstones,
                                    						certificate_url,
                                                                                polki_color,
                                                                                polki_quality,
                                                                                polki_weight,
                                                                                polkino,
                                                                                polki_price_per_carat,
                                                                                polki_value,

                                baguette_color,
                                baguette_quality,
                                baguette_weight,
                                baguette_no,
                                baguette_price_per_carat,
                                baguette_value,



                                                is_plain_jewellery,
                                                price_per_carat,
                                                othermaterial,
                                                labour_charge,
                                                grossweight,
                                                gprice_per_carat,
                                                diamondsvalue,
                                                gold_value,
                                                gold_type,
                                                gemstonevalue,
                                                isBugget
                                                
                            from 
                                    tbl_product_search 
                            where 
                                    active_flag = 1
                            AND
                                    product_id 
                            IN         
                                (SELECT
                                            product_id
                                from 
                                            tbl_vendor_product_mapping
                                where
                                            active_flag=1
                                AND
                                            vendor_id= " . $params['vid'] . ") ";
                               
                        $res = $this->query($sql);
                        $row = $this->numRows($res);
                        
                        if($row>0)
                        {
                                    $sql1 ="     select 
                                                             product_image,
                                                             image_sequence
                                                 from            
                                                             tbl_product_image_mapping
                                                 where 
                                                             active_flag=1
                                                 AND
                                                             product_id 
                                                 IN         
                                                             (SELECT
                                                                         product_id
                                                             from 
                                                                         tbl_vendor_product_mapping
                                                             where
                                                                         active_flag=1
                                                             AND
                                                                         vendor_id= " . $params['vid'] . ") ";
                        $res1 = $this->query($sql1);
                        $row1 = $this->numRows($res1);

                            if($row1>0)
                            {
                                $arr = array('Error:');
                                $err = array('code'=>0,'msg'=>'product Image Fetched Sucessfully !');
                            }
                            else
                            {
                                $arr = array('Error:');
                                $err = array('code'=>0,'msg'=>'Error in fetching product Image !');
                            }
                        }
                        else 
                        {
                                $arr = array('Error:');
                                $err = array('code'=>0,'msg'=>'Error in fetching vendor details !');
                        }
            }
            else
            {
                
                $arr = array('Error:');
                $err = array('code'=>0,'msg'=>'vendor Not Found !');
            }
         
            $result=array('result'=>$arr,'error'=>$err);
            return $result;  
            
        } 
    }




?>
