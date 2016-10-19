<?php
include APICLUDE . 'common/db.class.php';
class filter extends DB
{
    function __construct($db) 
    {
        parent::DB($db);
        
    }
    
    public function get_filters($params)
    {
      $sql = "
			SELECT 
				attribute_id, 
				attr_display_position 
			FROM 
				tbl_attribute_category_mapping 
			WHERE 
				category_id=".$params['category_id']." 
			AND 
				attr_filter_flag=1 
			ORDER BY 
				attr_filter_position ASC";	
        $page=$params['page'];
        $limit=$params['limit'];
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $sql.=" LIMIT " . $start . ",$limit";
        }
      
      $res = $this->query($sql);
        
        $chkres=$this->numRows($res);
        if($chkres)
        {   
            while($row1=$this->fetchData($res))
            {
               $attr1=''; 
               $attr1['attribute_id']=$row1['attribute_id'];
               $attr1['d_position']=$row1['attr_display_position'];
               $aid[]= $attr1['attribute_id'];
               $arr['Attribute'][]=$attr1;
               
            }
            
            $attr_id = implode(",", $aid);
            $sql1="SELECT attr_id,attr_name,attr_display_name,attr_unit,attr_unit_pos from tbl_attribute_master WHERE attr_id IN(".$attr_id.") ORDER BY attr_display_name ASC";
            
            if (!empty($page))
            {
                $start = ($page * $limit) - $limit;
                $sql1.=" LIMIT " . $start . ",$limit";
            }
            
            $res2=$this->query($sql1);
            $chkres2=$this->numRows($res2);
            if($chkres2>0)
            {   
                while($row2=$this->fetchData($res2))
                {
                    $attr2='';
                    $attr2['attr_name']=$row2['attr_name'];
                    $attr2['attr_display_name']=$row2['attr_display_name'];
                    $attr2['attr_unit']=$row2['attr_unit'];
                    $attr2['attr_unit_pos']=$row2['attr_unit_pos'];
                    $arr['attribute_des'][]=$attr2;
                }
                
            
                $sql3 = "SELECT   attribute_id,value, count(1) as cnt FROM tbl_product_attributes WHERE category_id =".$params['category_id']." AND attribute_id IN(".$attr_id.") AND active_flag=1 group by attribute_id,value order by attribute_id, count(1) desc";
                if (!empty($page))
                {
                    $start = ($page * $limit) - $limit;
                    $sql3.=" LIMIT " . $start . ",$limit";
                }    
                $res3 = $this->query($sql3);
                $chkres3=$this->numRows($res3);
                if($chkres3>0)
                {
                    while($row3=$this->fetchData($res3))
                    {
                        $attr3='';
                        $attr3['attribute_id']=$row3['attribute_id'];
			//$attributes[$attr_id]['id'] =$row['attribute_id'];
			$attr3['value']= $row3['value'];
                        $attr3['count']= $row3['cnt'];
                        $arr['Specifications'][]=$attr3;
                    }
                    $err=array('Code'=>0,'Msg'=>'Values fetched');
                }
            }   
       }       $result=array('results'=>$arr,'error'=>$err);
               return $result;
       }
         
    public function refine($params)
    {
		$dt = json_decode($params['dt'],1);

		$detls = $dt['result'];

		foreach($detls as $key => $value)
		{
			$detls[$key] = trim($value);
		}

		$catSql = "SELECT product_id FROM tbl_product_category_mapping WHERE category_id = " . $params['catid'];
		$catRes = $this->query($catSql);

		$prdIdArr = array();

		if($catRes)
		{
			while($catRow = $this->fetchData($catRes))
			{
				$prdIdArr[] = $catRow['product_id'];
			}
		}

		$prdIdStr = implode(',', $prdIdArr);

		if($prdIdStr)
		{
			$sql="SELECT product_id,barcode,lotref,lotno,product_name,product_display_name,product_brand,product_model,prd_img,prd_price AS prc,prd_wt,product_currency,product_keyword,product_desc,product_warranty,desname from tbl_product_master WHERE product_id IN (".$prdIdStr.")";

			$flg=$detls['filter_flg'];

			if($flg == 0)
			{
				$pres = $this->query($sql);
				$chkres = $this->numRows($pres);

				if($chkres > 0)
				{
					while($row=$this->fetchData($pres))
					{
						$arr[] = $row;
					}
					$err = array('Code' => 0, 'Msg' => 'Prodcut result is obtained');
				}
			}
			else if($flg==1)
			{
				if(!empty($detls['price']))
				{
					$pr = " AND prd_price = " . $detls['price'];
					$sql .= $pr;
				}            
				else
				{
					$price = '';
					if(isset($detls['pfrm']) && isset($detls['pto']))
					{
						$price = " AND prd_price >= " . $detls['pfrm'] . " AND prd_price <= " . $detls['pto'];
					}

					$sql .= $price;
				}

				if(!empty($detls['brname']))
				{
					$brname = str_replace(',', "','", $detls['brname']);
					$brand = " AND product_brand IN ('".$brname."')";
					$sql .= $brand;
				}

				$page = $params['page'];
				$limit=$params['limit'];

				if(!empty($page))
				{
					$start = ($page * $limit) - $limit;
					$sql .= " LIMIT " . $start . ", $limit";
				}
echo $sql . "<br/>";
				$finalres = $this->query($sql);
				$chkres=$this->numRows($finalres);
				if($chkres>0)
				{
					while($rows=$this->fetchData($finalres))
					{
						$arr[] = $rows;
					}

					$err=array('Code'=>0,'Msg'=>'Product List fetched');
				}
				else
				{
					$arr=array();
					$err=array('Code'=>1,'Msg'=>'No records found');
				}
			}
			else
			{
				$arr=array();
				$err=array('Code'=>1,'Msg'=>'Error in passing values');
			}
		}
		else
		{
			$arr=array();
			$err=array('Code'=>1,'Msg'=>'No product found in the category');
		}

        $result=array('result'=>$arr,'error'=>$err);
        return $result;
    }   

/*     

#   Filtering basic basis are as following:
#    1=>PRICE RANGE 2=>PURITY 3=>CATEGORY        
#        
#   Get price and weight from main product on the basis of category type 
#   this will be just a condition
#   However we are about to display product details at the front.
#   We have attributes (type,metal,color,shape,clarity,sharpness,measurements,stones,designers)                
#   We have prd details(model,barcode,lotref,product weight,product price,name)

public function refine1($params)
    {  

        $dt     = json_decode($params['dt'],1);
        $detls  = $dt['result'];
       $val  = $dt['value'];
        $sql="SELECT product_id,barcode,lotref,lotno,product_name,product_display_name,product_brand,product_model,prd_img,prd_price AS prc,lineage,prd_wt,category_id,product_currency,product_keyword,product_desc,product_warranty from tbl_product_master where category_id=".$params['catid']."";
        $flg=$detls['filter_flg'];
        
        if($flg=0)
        {
            $pres=$this->query($psql);
            $chkres=$this->numRows($pres);
            if($chkres>0)
            {   
                while($row=$this->fetchData($pres))
                {   
                    $arr[]=$row;
                }
                $err=array('Code'=>0,'Msg'=>'Prodcut result is obtained');
            }
        }
        else if($flg=1)
        {   

            $aid=explode(',',$detls['aid']);
            $plsql="SELECT product_id from tbl_product_attributes where ";
            for($i=0;$i<count($aid);$i++)       /*   Loop for attributes aid segment                 
            {   
               $vals_arr=explode(',',$val[$aid[$i]]);
               
               for($j=0;$j<count($vals_arr);$j++)  /* Values of each attribute according to the presence in value array 
               {
                   $plsql="SELECT product_id from tbl_product_attributes where attribute_id=".$aid[$i]." AND value IN('".$vals_arr[$j]."')";
                   $plres=$this->query($plsql);
                   while($row=$this->fetchData($plres))
                    {
                        $pids[]=$row['product_id'];
                    }
                }
            }
           $prid=implode(',',$pids);
    
           $sql.=" product_id IN(".$prid.")";
           $finalres=$this->query($sql);
           $chkres=$this->numRows($finalres);
           if($chkres>0)
           {
               while($rows=$this->fetchData($finalres))
               {
                   $arr[]=$rows;
               }
               $err=array('Code'=>0,'Msg'=>'Product List fetched');
           }
           else
           {
               $arr=array();
               $err=array('Code'=>1,'Msg'=>'No records found');
           }
        }
        else
        {
            $arr=array();
            $err=array('Code'=>1,'Msg'=>'Error in passing values');
        }
        $result=array('result'=>$arr,'error'=>$err);
        return $result;
    }
 */      
       
}
?>
