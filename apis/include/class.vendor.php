<?php
include APICLUDE.'common/db.class.php';
class vendor extends DB
{
    function __construct($db) 
    {
        parent::DB($db);
    }
    public function addVendorPrdInfo($params)
    {
        $dt= json_decode($params['dt'],1);
        $detls  = $dt['result'];
        
        $sql="SELECT city from tbl_vendor_master where vendor_id=\"".$detls['vid']."\"";
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
    
    public function getVproducts($params)
    {              
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $vsql.=" LIMIT " . $start . ",$limit";
        }
        
        $total_products = 0;
        
        $cnt_sql = "SELECT COUNT(1) as cnt FROM tbl_vendor_product_mapping  WHERE vendor_id =".$params['vid'];
        $cnt_res = $this->query($cnt_sql); //checking number of products registered under vendor id provided
        
        $chkcnt=$this->numRows($cnt_res);
         if($chkcnt>0)
        {
            $vsql="select product_id,vendor_price,vendor_quantity,vendor_currency,city,active_flag from tbl_vendor_product_mapping where vendor_id=".$params['vid'];
            $vres=$this->query($vsql);
            $prsql.=" LIMIT " . $start . ",$limit";
            
            $i=-1;
            $vpmap=array();
            while($row1=$this->fetchData($vres)) 
            {   $i++;    
                $vpmap['product_id'][$i]=$row1['product_id'];
                $vpmap['vendor_price'][$i]=$row1['vendor_price'];
                $vpmap['vendor_quantity'][$i]=$row1['vendor_quantity'];
                $vpmap['vendor_currency'][$i]=$row1['vendor_currency'];
                $vpmap['vendor_city'][$i]=$row1['city'];
                $vpmap['active_flag'][$i]=$row1['active_flag'];
                $vmap[]=$vpmap;
            }
            $vmapProd=implode(',',$vmap[$i]['product_id']);

            $prsql="SELECT product_id,product_name,product_display_name,product_model,product_brand,prd_img,desname 
                    FROM tbl_product_master WHERE product_id IN(".$vmapProd.")";
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
    
    /*   
    public function getVproductsByName($params)
    {
        
     $sql1="SELECT product_id,product_display_name,product_model,product_brand,prd_img,IF(product_name LIKE '".$params['prname']."%',1,0) AS startwith,IF(product_name LIKE '".$params['prname']."%',1,0) AS phrasematch,desname FROM tbl_product_master where MATCH(product_name) AGAINST(\"'" . $params['prname'] . "*'\" IN BOOLEAN MODE) ORDER BY startwith DESC,phrasematch DESC";
     $page=$params['page'];
     $limit=$params['limit'];
     if (!empty($page))
    {
        $start = ($page * $limit) - $limit;
        $sql1.=" LIMIT " . $start . ",$limit";
    }
     
     $res1=$this->query($sql1);
     $chkcnt=$this->numRows($res1);
     if($chkcnt>0)
     {
        $i=-1;
        while($row=$this->fetchData($res1))
        {   $i++;
            $pdet='';
            $pdet1['pid'][$i]=$row['product_id'];
            $pdet['prod_display_name']=$row['product_display_name'];
            $pdet['prod_model']=$row['product_model'];
            $pdet['prod_brand']=$row['product_brand'];
            $pdet['prod_img']=$row['prd_img'];
            $prDetails[]=$pdet;
        }
        $prId=implode(',',$pdet1['pid']);
       
        $sql2="SELECT vendor_price,vendor_quantity,vendor_currency,active_flag from tbl_vendor_product_mapping WHERE vendor_id =".$params['vid']." and product_id IN(".$prId.")";
        $sql2.=" LIMIT " . $start . ",$limit";
        $res2=$this->query($sql2);
        if($this->numRows($res2)>0)
        {
        $j=-1;
            while ($row1=$this->fetchData($res2)) 
            {
            $j++;
            $vreslt='';
            $vreslt['vendor_price'] = $row1['vendor_price'];
            $vreslt['vendor_currency'] = $row1['vendor_currency'];
            $vreslt['vendor_quantity'] = $row1['vendor_quantity'];
            $vreslt['vendor_status'] = $row1['active_flag'];
            $vresults[] = $vreslt;
            }
            $arr=array('productdet'=>$prDetails,'vendorProduct'=>$vresults);    
            $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
        }
        else
        {
            $arr=array('there is no product with starting with such name in vendor_product list');    
            $err = array('Code' => 0, 'Msg' => 'No Match Found');
        }
     }
    else
    {
        $arr='No product with this name in list';    
        $err = array('Code' => 0, 'Msg' => 'No Match Found');
    }
        $result = array('results' => $arr,'error' => $err);
        return $result;
        
    }*/
    
    public function updateProductInfo($params)
    {        
        $sql = "UPDATE tbl_vendor_product_mapping SET vendor_price=".$params['vp'].",
                vendor_quantity=".$params['vq'].",updatedby='vendor',
                active_flag=".$params['af']." WHERE vendor_id=".$params['vid']." AND
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
        
        $sql1="SELECT * FROM tbl_vendor_product_mapping where product_id=".$params['pid']." AND vendor_id=".$params['vid']." ORDER BY date_time ASC";
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
        
        $sql1="SELECT * FROM tbl_vendor_product_mapping where product_id=".$params['pid']." ORDER BY date_time ASC";
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

    public function getVProductsByCatid($params) {
        $page = ($params['page'] ? $params['page'] : 1);
        $catid = ($params['catid'] ? $params['catid'] : 10000);
        $limit = ($params['limit'] ? $params['limit'] : 15);
        $total_pages = $chkcnt = $total_products = 0;

        $sql = "select a.product_id AS id, a.vendor_price AS price, c.barcode, c.date_time, d.color, d.carat, d.shape, d.certified AS cert, d.clarity from tbl_vendor_product_mapping as a, tbl_product_category_mapping as b, tbl_product_master as c, tbl_product_search as d where a.product_id=b.product_id and b.product_id=c.product_id and c.product_id=d.product_id and b.category_id=" . $catid . " and a.vendor_id=" . $params['vid'] . " order by a.product_id asc ";
        $res = $this->query($sql);
        $total_products = $this->numRows($res);
        
        if (!empty($params['page'])) {
            $start = ($page * $limit) - $limit;
            $total_pages = $total_products/$limit;
            $sql.=" LIMIT " . $start . ",$limit";
            $res = $this->query($sql);
            $chkcnt = $this->numRows($res);
        }
        //echo $sql;
        if ($chkcnt > 0) {
            while ($row = $this->fetchData($res)) {
                $arr1[] = $row;
            }
            $arr = array('total_products' => $total_products, 'total_pages' => $total_pages, 'products' => $arr1);
            $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
        } else {
            $arr = array();
            $err = array('Code' => 1, 'Msg' => 'No Match Found');
        }
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }
}
?>
