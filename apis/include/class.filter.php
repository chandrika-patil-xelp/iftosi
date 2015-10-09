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
      $sql = "SELECT attribute_id, attr_display_position FROM tbl_attribute_mapping WHERE category_id=".$params['category_id']."
                AND attr_filter_flag=1 ORDER BY attr_filter_position ASC";	
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

        $dt     = json_decode($params['dt'],1);
        $detls  = $dt['result'];
// When No condition is applied 
  
#   WHEN FILTER FLAG IS NOT SET THEN       
        $sql="SELECT product_id,barcode,lotref,lotno,product_name,product_display_name,product_brand,product_model,prd_img,prd_price AS prc,lineage,prd_wt,category_id,product_currency,product_keyword,product_desc,product_warranty,desname from tbl_product_master WHERE category_id=".$params['catid']."";

//  If filter flag is active/inactive        
        $flg=$detls['filter_flg'];
        
        if($flg==0)
        {
            $pres=$this->query($sql);
           
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
        
#   Since filter flag valriable is active now so check according to the cases
#   psql is the prime query. This will adjoins with several sql variable strings accordingly
        else if($flg==1)
        {
#   SHOWS PRODUCT WITH A CERTAIN PRICE ONLY            
            if(!empty($detls['price']))
           {
            $pr=" AND prd_price=".$detls['price']."";
            $sql.=$pr;
           }
            
#   ADDS PRICE RANGE  [pfrm]         [pto]        WITH THE PROVIDED QUERY
           if(!empty($detls['pfrm']) || !empty($detls['pto']))
           {
            $price=" AND prd_price>=".$detls['pfrm']." AND prd_price<= ".$detls['pto']."";
            $sql.=$price;
           }

#   FETCHES PRODUCT ACCORDING TO BRAND NAME                   
           if(!empty($detls['bname']))
           {
             $bname= str_replace(',', "','", $detls['bname']);
             $brand=" AND product_brand IN('".$bname."')";  
             $sql.=$brand;  
           }
           
#PRODUCT Type
#   get the id of type from attribute master and search for the record
#   in product_attribute_mapp where value='' and attrid = typeid                   
           if(!empty($detls['type']))
           {
               $type= str_replace(',', "','", $detls['type']);

               $tsql="SELECT attr_id from tbl_attribute_master where attr_name='type'";
               $tres=$this->query($tsql);
               $trow=$this->fetchData($tres);
               $typeid=$trow['attr_id'];
               $types="SELECT product_id from tbl_product_attributes WHERE attribute_id=".$typeid." AND value IN('".$type."')";
               $tpres=$this->query($types);
               if($tpres)
               {
                   while($row=$this->fetchData($tpres))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['typid']=implode(',',$pid);
                   
                   $sql.=" AND product_id IN(".$pid.")";
               }
           }

#PRODUCT Gender
#  SEARCHES FORPRODUCT GENDER ATTRIBUTE ID 
#  FETCHES PRODUCT ID IN CONTEXT OF ATTRIBUTE VALUE AGAINST FETCHED GENDER TYPE
#  GENERATES QUERY AGAINST PRODUCT FIELD QUERY HAVING PRODUCT ID IN OBTAINED PID
           if(!empty($detls['pgen']))
           {
               $gsql="SELECT attr_id from tbl_attribute_master where attr_name='gender'";
               $gres=$this->query($gsql);
               $grow=$this->fetchData($gres);
               $aid=$grow['attr_id'];

               $jgsql="SELECT product_id from tbl_product_attributes WHERE attribute_id=".$aid." AND value IN('".$detls['pgen']."')";
               $jgres=$this->query($jgsql);
               if($jgres)
               {
                   while($row=$this->fetchData($jgres))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['gid']=implode(',',$pid);
                   
#   HANDLE [AND] OR [WHERE]
                   $sql.="AND product_id IN(".$pid.")";
               }
           }

#PRODUCT METAL
#  SEARCHES FOR PRODUCT METAL ATTRIBUTE ID 
#  FETCHES PRODUCT ID IN CONTEXT OF ATTRIBUTE VALUE AGAINST FETCHED METAL CATEGORY
#  GENERALIZED QUERY AGAINST PRODUCT FIELDS QUERY HAVING PRODUCT ID IN OBTAINED PID           
           if(!empty($detls['metal']))
           {
            //   $met= implode(',', $detls['metal']);
               $mtsql="SELECT attr_id from tbl_attribute_master where attr_name='metal'";
               $mtres=$this->query($mtsql);
               $mtrow=$this->fetchData($mtres);
               $aid=$mtrow['attr_id'];

               $msql="SELECT product_id from tbl_product_attributes WHERE attribute_id=".$aid." AND value IN('".$detls['metal']."')";
               $mres=$this->query($msql);
               if($mres)
               {
                   while($row=$this->fetchData($mres))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['mtid']=implode(',',$pid);
#   HANDLE [AND] OR [WHERE]
                   $sql.="AND product_id IN(".$pid.")";
               }
           }
           
#PRODUCT GEMSTONE COLOR
#  SEARCHES FOR PRODUCT COLOR ATTRIBUTE ID 
#  FETCHES PRODUCT ID IN CONTEXT OF ATTRIBUTE VALUE AGAINST FETCHED SHAPE CATEGORY
#  GENERALIZED QUERY AGAINST PRODUCT FIELDS QUERY HAVING PRODUCT ID IN OBTAINED PID
           if(!empty($detls['color']))
           {
              // $col= implode(',', $detls['color']);
               $colsql="SELECT attr_id from tbl_attribute_master where attr_name='color'";
               $colres=$this->query($colsql);
               $colrow=$this->fetchData($colres);
               $aid=$colrow['attr_id'];

               $clrsql="SELECT product_id from tbl_product_attributes WHERE attribute_id=".$aid." AND value IN('".$detls['color']."')";
               $clres=$this->query($clrsql);
               if($clres)
               {
                   while($row=$this->fetchData($clres))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['col']=implode(',',$pid);
#   HANDLE [AND] [OR] [WHERE]
                   $sql.=" AND product_id IN(".$pid.")";
               }
           }

#PRODUCT SHAPE
#  SEARCHES FOR PRODUCT SHAPE ATTRIBUTE ID 
#  FETCHES PRODUCT ID IN CONTEXT OF ATTRIBUTE VALUE AGAINST FETCHED SHAPE CATEGORY
#  GENERALIZED QUERY AGAINST PRODUCT FIELDS QUERY HAVING PRODUCT ID IN OBTAINED PID
           if(!empty($detls['shape']))
           {
              // $shape= implode(',', $detls['shape']);
               $shpsql="SELECT attr_id from tbl_attribute_master where attr_name='shape'";
               $shpres=$this->query($shpsql);
               $shprow=$this->fetchData($shpres);
               $aid=$shprow['attr_id'];

               $spsql="SELECT product_id from tbl_product_attributes WHERE attribute_id=".$aid." AND value IN('".$detls['shape']."')";
               $spres=$this->query($spsql);
               if($spres)
               {
                   while($row=$this->fetchData($spres))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['shpid']=implode(',',$pid);
#   HANDLE [AND] [OR] [WHERE]
                   $sql.=" AND product_id IN(".$pid.")";
               }
           }
           
#PRODUCT STYLE
#  SEARCHES FOR PRODUCT STYLE ATTRIBUTE ID 
#  FETCHES PRODUCT ID IN CONTEXT OF ATTRIBUTE VALUE AGAINST FETCHED STYLE CATEGORY
#  GENERALIZED QUERY AGAINST PRODUCT FIELDS QUERY HAVING PRODUCT ID IN OBTAINED PID
           if(!empty($detls['style']))
           {
              // $style= implode(',', $detls['style']);
               $stsql="SELECT attr_id from tbl_attribute_master where attr_name='style'";
               $stres=$this->query($stsql);
               $strow=$this->fetchData($stres);
               $aid=$strow['attr_id'];

               $stlsql="SELECT product_id from tbl_product_attributes WHERE attribute_id=".$aid." AND value IN('".$detls['style']."')";
               $stlres=$this->query($stlsql);
               if($stlres)
               {
                   while($row=$this->fetchData($stlres))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['stlid']=implode(',',$pid);
#   HANDLE [AND] [OR] [WHERE]
                   $sql.=" AND product_id IN(".$pid.")";
               }
           }           

#PRODUCT PURITY
#  SEARCHES FOR PRODUCT PURITY ATTRIBUTE ID 
#  FETCHES PRODUCT ID IN CONTEXT OF ATTRIBUTE VALUE AGAINST FETCHED PURITY CATEGORY
#  GENERALIZED QUERY AGAINST PRODUCT FIELDS QUERY HAVING PRODUCT ID IN OBTAINED PID           
           if(!empty($detls['purity']))
           {
               //$purity= implode(',', $detls['purity']);
               $ptysql="SELECT attr_id from tbl_attribute_master where attr_name='purity'";
               $ptyres=$this->query($ptysql);
               $ptyrow=$this->fetchData($ptyres);
               $aid=$ptyrow['attr_id'];

               $prtysql="SELECT product_id from tbl_product_attributes WHERE attribute_id=".$aid." AND value IN('".$detls['purity']."')";
               $prtyres=$this->query($prtysql);
               if($prtyres)
               {
                   while($row=$this->fetchData($prtyres))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['prtyid']=implode(',',$pid);
#   HANDLE [AND] [OR] [WHERE]
                   $sql.=" AND product_id IN(".$pid.")";
               }
           }
           
#PRODUCT SIZE
#  SEARCHES FOR PRODUCT SIZE ATTRIBUTE ID 
#  FETCHES PRODUCT ID IN CONTEXT OF ATTRIBUTE VALUE AGAINST FETCHED SIZE CATEGORY
#  GENERALIZED QUERY AGAINST PRODUCT FIELDS QUERY HAVING PRODUCT ID IN OBTAINED PID           
           if(!empty($detls['size']))
           {
              // $size= implode(',', $detls['size']);
               $szsql="SELECT attr_id from tbl_attribute_master where attr_name='size'";
               $szres=$this->query($szsql);
               $szrow=$this->fetchData($szres);
               $aid=$szrow['attr_id'];

               $sizesql="SELECT product_id from tbl_product_attributes WHERE attribute_id=".$aid." AND value IN('".$detls['size']."')";
               $sizeres=$this->query($sizesql);
               if($sizeres)
               {
                   while($row=$this->fetchData($sizeres))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['szid']=implode(',',$pid);
#   HANDLE [AND] [OR] [WHERE]
                   $sql.=" AND product_id IN(".$pid.")";
               }
           }
           
#PRODUCT CLARITY
#  SEARCHES FOR PRODUCT CLARITY ATTRIBUTE ID 
#  FETCHES PRODUCT ID IN CONTEXT OF ATTRIBUTE VALUE AGAINST FETCHED CLARITY CATEGORY
#  GENERALIZED QUERY AGAINST PRODUCT FIELDS QUERY HAVING PRODUCT ID IN OBTAINED PID           
           if(!empty($detls['clarity']))
           {
              // $size= implode(',', $detls['size']);
               $clsql="SELECT attr_id from tbl_attribute_master where attr_name='clarity'";
               $clres=$this->query($clsql);
               $clrow=$this->fetchData($clres);
               $aid=$clrow['attr_id'];

               $clasql="SELECT product_id from tbl_product_attribute WHERE attribute_id=".$aid." AND value IN('".$detls['clarity']."')";
               $clares=$this->query($clasql);
               if($clares)
               {
                   while($row=$this->fetchData($clares))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['clid']=implode(',',$pid);
#   HANDLE [AND] [OR] [WHERE]
                   $sql.=" AND product_id IN(".$pid.")";
               }
           }
 
           
#PRODUCT CARATS RANGE
#  SEARCHES FOR PRODUCT CARATS ATTRIBUTE ID 
#  FETCHES PRODUCT ID IN CONTEXT OF ATTRIBUTE VALUE AGAINST FETCHED CARATS CATEGORY
#  GENERALIZED QUERY AGAINST PRODUCT FIELDS QUERY HAVING PRODUCT ID IN OBTAINED PID           
           
           if(!empty($detls['mncar']) && !empty($detls['mxcar']))
           {
              // $size= implode(',', $detls['size']);
               $crsql="SELECT attr_id from tbl_attribute_master where attr_name='carats'";
               $crres=$this->query($crsql);
               $crrow=$this->fetchData($crres);
               $aid=$crrow['attr_id'];

               $caratsql="SELECT product_id from tbl_product_attribute WHERE attribute_id=".$aid." AND value BETWEEN ".$detls['mncar']." AND ".$detls['mxcar']."";
               $caratres=$this->query($caratsql);
               if($caratres)
               {
                   while($row=$this->fetchData($caratres))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['carid']=implode(',',$pid);
#   HANDLE [AND] [OR] [WHERE]
                   $sql.=" AND product_id IN(".$pid.")";
               }
           }
           
#PRODUCT CARATS 
#  SEARCHES FOR PRODUCT CARATS ATTRIBUTE ID 
#  FETCHES PRODUCT ID IN CONTEXT OF ATTRIBUTE VALUE AGAINST FETCHED CARATS CATEGORY
#  GENERALIZED QUERY AGAINST PRODUCT FIELDS QUERY HAVING PRODUCT ID IN OBTAINED PID           
           
           if(!empty($detls['carats']))
           {
               $crsql="SELECT attr_id from tbl_attribute_master where attr_name='carats'";
               $crres=$this->query($crsql);
               $crrow=$this->fetchData($crres);
               $aid=$crrow['attr_id'];
               $caratsql="SELECT product_id from tbl_product_attribute WHERE attribute_id=".$aid." AND value IN(".$detls['carats'].")";
               $caratres=$this->query($caratsql);
               if($caratres)
               {
                   while($row=$this->fetchData($caratres))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['carid']=implode(',',$pid);
#   HANDLE [AND] [OR] [WHERE]
                   $sql.=" AND product_id IN(".$pid.")";
               }
           }           
           
#PRODUCT POLISH
#  SEARCHES FOR PRODUCT POLISH ATTRIBUTE ID 
#  FETCHES PRODUCT ID IN CONTEXT OF ATTRIBUTE VALUE AGAINST FETCHED POLISH CATEGORY
#  GENERALIZED QUERY AGAINST PRODUCT FIELDS QUERY HAVING PRODUCT ID IN OBTAINED PID           
           if(!empty($detls['polish']))
           {
              // $size= implode(',', $detls['size']);
               $polql="SELECT attr_id from tbl_attribute_master where attr_name='clarity'";
               $polres=$this->query($polsql);
               $polrow=$this->fetchData($polres);
               $aid=$polrow['attr_id'];

               $plsql="SELECT product_id from tbl_product_attribute WHERE attribute_id=".$aid." AND value IN('".$detls['polish']."')";
               $plres=$this->query($plsql);
               if($plres)
               {
                   while($row=$this->fetchData($plres))
                   {
                       $pid[]=$row['product_id'];
                   }
                   $pid['clid']=implode(',',$pid);
#   HANDLE [AND] [OR] [WHERE]
                   $sql.=" AND product_id IN(".$pid.")";
               }
           }

/*           for($i=0;$i<=count($detls['aid']);$i++)
           {
               $plsql="SELECT product_id from tbl_product_attributes where attribute_id IN(".$detls['aid'].")";
               $plres=$this->query($plsql);
               while($row=$this->fetchData($plres))
               {
                 $pids=$row['pid']; 
               }
           }    
               $prid='';
               $prid=implode(',',$pids);
           $sql.=" AND product_id IN(".$pid.")";

 */
           
 //-----------------------------FINALLY QUERY EXECUTES-------------------------------------------------------------------------------           
       $page=$params['page'];
        $limit=$params['limit'];
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $sql.=" LIMIT " . $start . ",$limit";
        }
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