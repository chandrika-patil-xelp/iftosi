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
        $sql="INSERT INTO tbl_vendor_product_mapping(product_id,vendormobile,vendor_price,vendor_quantity,vendor_currency,vendor_remarks,active_flag,updatedby,updatedon,backendupdate)";
        $sql.="VALUES(".$params['product_id'].",".$params['logmobile'].",".$params['vendor_price'].",".$params['vendor_quantity'].",'".$params['vendor_currency']."','".$params['vendor_remarks']."',".$params['active_flag'].",'vendor',now(),now())";
        $res = $this->query($sql);
        if($res)
        {
            $arr="Product mapping is done successfully";
            $err = array('Code' => 0, 'Msg' => 'Product details added successfully!');
        }
        else
        {
            $arr="Product mapping is  already done";
            $err = array('Code' => 0, 'Msg' => 'Product details Not Added!');            
        }
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }
    
    public function getVproducts($params)
    {      
        $total_products = 0;
        
        $cnt_sql = "SELECT COUNT(1) as cnt FROM tbl_vendor_product_mapping  WHERE vendormobile =".$params['vendormobile'];
        $cnt_res = $this->query($cnt_sql); //checking number of products registered under vendor id provided
        
        $vsql="select product_id,vendor_price,vendor_quantity,vendor_currency,active_flag from tbl_vendor_product_mapping where vendormobile=".$params['vendormobile'];
        $vres=$this->query($vsql);
        $chkcnt=$this->numRows($vres);
             if($chkcnt>0)
            {
        $i=-1;
        while($row1=$this->fetchData($vres)) 
        {   $i++;    
                $vpmap['product_id'][$i]=$row1['product_id'];
                $vpmap['vendor_price'][$i]=$row1['vendor_price'];
                $vpmap['vendor_quantity'][$i]=$row1['vendor_quantity'];
                $vpmap['vendor_currency'][$i]=$row1['vendor_currency'];
                $vpmap['active_flag'][$i]=$row1['active_flag'];
                $vmap[]=$vpmap;
        }
        $vmapProd=implode(',',$vmap[$i]['product_id']);
        
        $prsql="SELECT product_id,product_name,product_display_name,product_model,product_brand,prd_img 
                FROM tb_master_prd WHERE product_id IN(".$vmapProd.")";
        $pres=$this->query($prsql);
        $j=-1;
            while ($prow = $this->fetchData($pres)) 
            {       $j++;
                    
                    $preslt['product_name'][$j]        = $prow['product_name'];
                    $preslt['product_display_name'][$j] = $prow['product_display_name'];
                    $preslt['product_model'][$j]        = $prow['product_model'];
                    $preslt['product_brand'][$j]        = $prow['product_brand'];
                    $preslt['product_image'][$j]        = $prow['prd_img'];
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
            $arr='There is not product mapped with this vendor';
            $err=array('Code'=>1,'Msg'=>'Error in fetching data');
         }
        $result = array('results'=>$arr,'error'=> $err);
        return $result;
    }
    
    public function getVproductsByName($params)
    {
     $sql1="SELECT product_id,product_display_name,product_model,product_brand,prd_img FROM tb_master_prd where product_name LIKE '".$params['prname']."%' ORDER BY product_name DESC";
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
       
        $sql2="SELECT vendor_price,vendor_quantity,vendor_currency,active_flag from tbl_vendor_product_mapping WHERE vendormobile =".$params['vendormobile']." and product_id IN(".$prId.")";
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
        
    }
    
    public function updateProductInfo($params)
    {        
        $sql = "UPDATE tbl_vendor_product_mapping SET vendor_price=".$params['vendor_price'].",
                vendor_quantity=".$params['vendor_quantity'].",updatedby='vendor',updatedon=now(),
                active_flag=".$params['active_flag']." WHERE vendor_id=".$params['vendor_id']." AND
                product_id=".$params['product_id']." AND vendormobile=".$params['logmobile'];
        $res=$this->query($sql);
        if($res) 
        {   $arr="Vendor Product Map table updated";
            $err = array('Code' => 0, 'Msg' => 'Details updated successfully');
        }
        $result = array('results'=>$arr,'error'=>$err);  
        return $result;
    }
    
    public function getVDetailByPid($params)
    {
     $sql1="SELECT * FROM tbl_vendor_product_mapping where product_id=".$params['product_id']." ORDER BY updatedon DESC";
     $res1=$this->query($sql1);
     $chkcnt=$this->numRows($res1);
     if($chkcnt>0)
     {
        $i=0;
        while($row=$this->fetchData($res1))
        {   $vdet='';
            $vdet1['vmob'][$i]=$row['vendormobile'];
            $vdet['vprice']=$row['vendor_price'];
            $vdet['vquant']=$row['vendor_quantity'];
            $vdet['vcur']=$row['vendor_currency'];
            $vdet['vremarks']=$row['vendor_remarks'];
             $vdetls[]=$vdet;
        $i++;   
             }

        $vmob=implode(',',$vdet1['vmob']);        
        $sql2="SELECT vendor_name,email,contact_mobile,mobile,landline,area,address1,address2,city,postal_code,state,country from tbl_vendor_master WHERE contact_mobile IN(".$vmob.")";
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
            $arr=array('there is no product with starting with such name in vendor_product list');    
            $err = array('Code' => 1, 'Msg' => 'No Match Found');
        }
     }
    else
    {
        $arr='No such product with this id';    
        $err = array('Code' => 0, 'Msg' => 'No Match Found');
    }
        $result = array('results' => $arr,'error' => $err);
        return $result;
        
    }
    
}
?>