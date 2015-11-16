<?php
        include APICLUDE.'common/db.class.php';
	class prdInsert extends DB
    {
        function __construct($db) 
        {
                parent::DB($db);
        }

        public function productInsert($params)
        { 
//--------------------Attribute according to category------------------------------------------            
            $mapsql="SELECT 
                                    attribute_id,
                                    attr_unit,
                                    attr_unit_pos,
                                    attr_values,
                                    attr_range
                            FROM 
                                    tbl_attribute_category_mapping 
                            WHERE 
                                    category_id=".$params['category_id']." 
                            AND 
                                    attr_display_flag = 1 
                            AND 
                                    attr_filter_flag = 1 
                            ORDER BY 
                                    attr_filter_position ASC ;";
        $mapres=$this->query($mapsql);
        $cres=$this->numRows($mapres);
        if($cres>0)
        {
            $i=0;
            while($row1=$this->fetchData($mapres)) 
            {
				$attrid[] = $row1['attribute_id'];
				$attributeMap[$row1['attribute_id']] = $row1;
				$i++;
            }
            $atribs=implode(',',$attrid);
            //echo "<pre>";print_r($attributeMap);die;
            $attrsql="SELECT  
                                attr_id,
                                attr_name,
                                attr_display_name,
                                attr_type_flag,
                                use_list
                      FROM 
                                tbl_attribute_master 
                      WHERE 
                                attr_id IN(".$atribs.")
                      ORDER BY 
                                field(attr_id,".$atribs.")";
            $attrres = $this->query($attrsql); 
            if($attrres)
            {   
                while($row2=$this->fetchData($attrres)) 
                {
                    $attrs['atrribute_id']		= $row2['attr_id'];
                    $attrs['attribute_name']		= $row2['attr_name'];
                    $attrs['attribute_disp_name']	= $row2['attr_display_name'];
                    $attrs['attribute_unit']		= $attributeMap[$row2['attr_id']]['attr_unit'];
                    $attrs['attribtue_unit_pos']	= $attributeMap[$row2['attr_id']]['attr_unit_pos'];
                    $attrs['attribute_values']		= $attributeMap[$row2['attr_id']]['attr_values'];
                    $attrs['attribute_range']		= $attributeMap[$row2['attr_id']]['attr_range'];
                    $attribute[]			= $attrs;
                    
                }
            }
        }
      

//--------------------------------------------------------------------------------------------------------------------
        
        $catsql = "SELECT
                                                    cat_name,
                                                    catid
                    FROM
                                tbl_category_master
                    WHERE
                                                    catid=".$params['category_id'];
            
            $page   = ($params['page'] ? $params['page'] : 1);
            $limit  = ($params['limit'] ? $params['limit'] : 15);
            
            if (!empty($page))
            {
                $start = ($page * $limit) - $limit;
                $sql.=" LIMIT " . $start . ",$limit";
            }
            
            $catres = $this->query($catsql);
            $chkres=$this->numRows($catres);
            if($chkres>0)
            {
               $row3 = $this->fetchData($catres);
                
                    if($row3 && !empty($row3['catid']))
                        {   
                            $cat['catid']=$row3['catid'];
                            $cat['category_name'] = $row3['cat_name'];
                        }
                
               $pkey=  strtolower($row3['cat_name']);
               $pkey=  str_replace(' ','_', $pkey);
                
                $pcatsql = "SELECT
                                                            catid,
                                                            cat_name
                            FROM 
                                    tbl_category_master
                            WHERE
                                                            p_catid=".$params['category_id']." 
                            Order BY
                                                            catid
                            ASC";
			$page   = ($params['page'] ? $params['page'] : 1);
                        $limit  = ($params['limit'] ? $params['limit'] : 10);
		        if (!empty($page))
			{
				$start = ($page * $limit) - $limit;
				$pcatsql.=" LIMIT " . $start . ",$limit";
			}
			$pcatres = $this->query($pcatsql);
			if($pcatres)
			{
                            while($subrow=$this->fetchData($pcatres))
                            {
                                $subcatid['subcatid'][]=$subrow['catid'];
                                $reslt[]=$subrow['cat_name'];
                                $categorynames[]=$subrow['cat_name'];
                            }
                                $subid=implode(',',$subcatid['subcatid']);
                        }
                         
            }
//----------------------------------------------------------------------------------------------------
       		$subcatsql = "SELECT p_catid, group_concat(cat_name) as subcatlist FROM tbl_category_master where p_catid IN(".$subid.") group by p_catid;";
	         
		$subcatres = $this->query($subcatsql);
                
		if($subcatres)
		{
                    $i=0;
                    while($row4 = $this->fetchData($res))
			{
                            $pcatid=$row4['p_catid'];
                            $subcat[$reslt[$i]]=explode(',', $row4['subcatlist']);
                            $i++;
                        }
                }
        $arr=array('attribute'=>$attribute,'categorynames'=>$categorynames,'subcat'=>$subcat);
        $err=array('code'=>0,'msg'=>'data fetched');
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
        }
        
        
	public function getSubCat($catid,$arr=array())
	{
		if($catid)
		{
			$sql = "SELECT p_catid, catid, cat_name FROM tbl_category_master where p_catid=".$catid." order by catid ASC";
		}
		else
		{
			$sql = "SELECT p_catid, catid, cat_name FROM tbl_category_master where p_catid=0 order by catid ASC";
		}
		$res = $this->query($sql);
		if($res)
		{
			while($row = $this->fetchData($res))
			{
				if(!empty($arr) && $row['p_catid'] !=0)
					$arr['subcat'][] = $this->getSubCat($row['catid'],$row);
				else
					$arr['root'][] = $this->getSubCat($row['catid'],$row);
			}
		}
		return $arr;
	}
        
    
        public function categoryHeir($params)
        {
            
            $arr = array();
            $mainSql="  SELECT
                            catid,
                            cat_name
                        FROM
                            tbl_category_master
                        WHERE
                            catid=\"".$params['catid']."\"
                        AND
                            p_catid=0
                    ";
            $mainres=$this->query($mainSql);
            
            if($mainres)
            {

                $mainrow=$this->fetchData($mainres);
                
                $arr['catid']   = $mainrow['catid'];
                $arr['catname'] = $mainrow['cat_name'];
                $sql = "SELECT catid, cat_name FROM tbl_category_master WHERE p_catid='".$mainrow['catid']."'";
                $res = $this->query($sql);
                $i=0;
                while($row = $this->fetchData($res))
                {
                    $arr['subcat'][$i]['catid']     = $row['catid'];
                    $arr['subcat'][$i]['catname']   = $row['cat_name'];
                    
                    $sbCatSql="SELECT catid,cat_name FROM tbl_category_master WHERE p_catid=".$row['catid']."";
                    $sbCatRes=$this->query($sbCatSql);
                    $x=0;
                    $crr=array();
                    while($sbCatRow= $this->fetchData($sbCatRes))
                    {
                        $crr[$x]['subcatid']        = $sbCatRow['catid'];
                        $crr[$x]['subcatname']      = $sbCatRow['cat_name'];
                        $x++;
                        $arr['subcat'][$i]['subcats']=$crr;
                    }
                    
//-----------------------bajpai over-------------------------------------------------------------------------------------------------------------------------------------                    
                    $sbAttrSql = ""
                            . "SELECT attribute_id, attr_display_flag, attr_display_position, attr_values, attr_range, attr_filter_flag "
                            . "FROM tbl_attribute_category_mapping WHERE category_id = '".$row['catid']."'";
                    $sbAttrRes = $this->query($sbAttrSql);
                    $y=0;
                    while($sbAttrRow = $this->fetchData($sbAttrRes))
                    {
                        $attrMstrSql = ""
                                . "SELECT attr_display_name, attr_name "
                                . "FROM tbl_attribute_master "
                                . "WHERE attr_id='".$sbAttrRow['attribute_id']."'";
                        $attrMstrRes = $this->query($attrMstrSql);
                        $attrMstrRow = $this->fetchData($attrMstrRes);
                        $barr[$y]['attr_display_name'] = $attrMstrRow['attr_display_name'];
                        $barr[$y]['attr_name'] = $attrMstrRow['attr_name'];
                        $barr[$y]['attribute_id'] = $sbAttrRow['attribute_id'];
                        $barr[$y]['attr_values'] = $sbAttrRow['attr_values'];
                        $barr[$y]['attr_range'] = $sbAttrRow['attr_range'];
                        $y++;
                        $arr['subcat'][$i]['attr'] = $barr;
                    }
                    $i++;
                }
                $err=array('Code'=>0,'msg'=>'Data fetched successfully');
                
            }
            $result=array('result'=>$arr,'error'=>$err);
            return $result;
                    
        }
        public function product_category_mapping()
        {
                $sql="select product_id from tbl_product_master where product_id Between 12241 AND 12270";
                $res=$this->query($sql);
                if($res)
                {
                    while($row=$this->fetchData($res))
                    {
                        $prid[]=$row['product_id'];
                    }
                    $pid=implode(',',$prid);
                    $p=explode(',',$pid);
                    for($i=0;$i=count($p);$i++)
                    {
                        $sql2=" INSERT
                                    INTO
                                                tbl_product_category_mapping
                                                (category_id,
                                                 product_id,
                                                 display_flag)
                                    VALUES
                                    (10007,".$p[$i].",1)";                        
                            $res=$this->query($sql2);            
                    }
                    $arr=array();
                    $err=array('code'=>0,'msg'=>'filled');
                }
                $result=array('result'=>$arr,'error'=>$err);
                return $result;    
        }
                    
                    
    }
        
    
?>