<?php
        include APICLUDE.'common/db.class.php';
	class product extends DB
    {
        function __construct($db) 
        {
                parent::DB($db);
        }

        public function addNewproduct($params)
        {  
            $isInside= 0;
            $dt= json_decode($params['dt'],1);
            $detls  = $dt['result'];
            $des    = $dt['design'];
            $attr  = $dt['attributes'];
            $proErr  = $dt['error'];
            if($proErr['errCode']== 0)
           {
           $csql = "SELECT id,name FROM tbl_brandid_generator WHERE name='".$detls['product_brand']."'";
            $cres = $this->query($csql);
	    if($cres)
            {
                $cnt1 = $this->numRows($cres);
		if(!$cnt1)
		{
                    // Obtaining the category name from the category table 
                    $catsql="Select category_name from tbl_categoryid_generator where category_id=".$detls['category_id'];
                    $catres=$this->query($catsql);
                    if($catres)
                    {
                        while($catrow=$this->fetchData($catres))
                        {
                            $catname=$catrow['category_name'];
                        }
                    }
                    
                    //  Inserting the values in brand table
                  $sql = "INSERT INTO tbl_brandid_generator(name,category_name,cdt,udt,aflg) VALUES('".$detls['product_brand']."','".$catname."',now(),now(),1)";
                    $res = $this->query($sql);
                    $bid = $this->lastInsertedId();
                    $brandid = ($bid) ? $bid : $detls['brandid'];
                }
		else
                {
                   $row     = $this->fetchData($cres);
                   $brandid = ($row['id']) ? $row['id'] : $detls['brandid'];
                }
                
                //  Obtaining the product id key from the product generator table
                $sql  = "SELECT product_id,product_name FROM tbl_productid_generator WHERE product_name = '".$detls['product_name']."' AND product_brand = '".$detls['product_brand']."'";
                $res  = $this->query($sql);
                $cnt2 = $this->numRows($res);
                
                if(!$cnt2)
                {
                    //  If product not present in generator table new product insertion process starts
                  $sql = "INSERT INTO tbl_productid_generator(product_name, product_brand) VALUES('".$detls['product_name']."','".$detls['product_brand']."')";
                  $res = $this->query($sql);
                  $pid = $this->lastInsertedId();
                }
                else
                {
                    
                    $row = $this->fetchData($res);
                   $pid = $row['product_id'];
                }
		if($pid)
                {   // Checks for the designer name along with product id
                   $chksql="SELECT designer_id,desname from tbl_designer_product_mapping where product_id=".$pid."";
                   $chkdes=$this->query($chksql);
                   $cntres=$this->numRows($chkdes);
                   if($cntres==0)
                   {
                    //  For product designer tabe insertion   
                   $dessql="insert into tbl_designer_product_mapping(product_id,desname,active_flag,cdt,udt)
                            VALUES(".$pid.",'".$des['desname']."',1,now(),now())";
                    $desres = $this->query($dessql);
                   }
                   else
                   {
                        $row = $this->fetchData($chkdes);
                   $did=$row['designer_id'];    
                   
                   //   designer product table value updating if product already present
                    $dessql="UPDATE tbl_designer_product_mapping set desname='".$des['desname']."' where designer_id=".$did."";
                    $desres = $this->query($dessql);
                   
                   }
                    if($desres)
                    {
                    //  For category product mapping
                        $pcsql="INSERT INTO tbl_prd_cat_mapping(product_id,category_id,pflag,cdt,udt) VALUES(".$pid.",".$catname.",1,now(),now())";
                        $pcres=$this->query($pcres);
                        
                    //  For product values filling     
                        $sql="INSERT INTO tb_master_prd(product_id,barcode,lotref,lotno,product_name,product_display_name,
                                                     product_model,product_brand,prd_price,product_currency,product_keyword,                                                     
                                                     product_desc,prd_wt,prd_img,product_warranty,desname,
                                                     updatedby, updatedon, cdt)
                                                VALUES (
							".$pid.",'".$detls['barcode']."','".$detls['lotref']."',".$detls['lotno'].",
                                                      '".$detls['product_name']."','".$detls['product_display_name']."', 
                                                      '".$detls['product_model']."', '".$detls['product_brand']."', ".$detls['product_price'].",
                                                      '".$detls['product_currency']."','".$detls['product_keywords']."', 
                                                      '".$detls['product_desc']."',".$detls['product_wt'].",'".$detls['prd_img']."',
                                                      '".$detls['product_warranty']."','".$des['desname']."','CMS USER', now(), now())
			  ON DUPLICATE KEY UPDATE
                                                    barcode                      = '".$detls['barcode']."', 
                                                    lotref                       = '".$detls['lotref']."', 
                                                    lotno                        =  ".$detls['lotno'].", 
                                                    product_display_name         = '".$detls['product_display_name']."', 
                                                    product_model 		 = '".$detls['product_model']."', 
                                                    product_brand 		 = '".$detls['product_brand']."', 
                                                    prd_price 		         =  ".$detls['product_price'].",  
                                                    product_currency             = '".$detls['product_currency']."', 
                                                    product_keyword              = '".$detls['product_keywords']."', 
                                                    product_desc                 = '".$detls['product_desc']."', 
                                                    prd_wt                       = ".$detls['product_wt'].", 
                                                    prd_img                      = '".$detls['prd_img']."',  
                                                    product_warranty             ='".$detls['product_warranty']."',
                                                    updatedby 			 =   'CMS USER', 
                                                    updatedon 			 =    now(),
                                                    desname                      ='".$des['desname']."',
                                                    cdt                          =    now()";
                    $res = $this->query($sql);
                   //----------------------------------------------For product search table--------------------------------------------------- 
                    
                    if(count($attr))
                    {
                            //  For tbl_product_srch
        // Few attributes remaining-- type,metal,purity,nofd,dwt,gemwt,quality,goldwt
                            $sql = "INSERT INTO tbl_product_srch(product_id,color,cert,cut,cla,base,tabl,val,p_disc,prop,pol,sym,fluo,td,measurement,cert1_no,pa,cr_hgt,cr_ang,girdle,pd) VALUES
                                    (".$pid.",'".$attr['color']."','".$attr['cert']."','".$attr['cut']."','".$attr['cla']."',".$attr['base'].",".$attr['tabl'].",".$attr['val'].",".$attr['p_disc'].",
                                     '".$attr['prop']."','".$attr['pol']."','".$attr['sym']."','".$attr['fluo']."',".$attr['td'].",'".$attr['measurement']."','".$attr['cert1no']."',".$attr['pa'].",".$attr['cr_hgt'].",
                                     ".$attr['cr_ang'].",".$attr['girdle'].",".$attr['pd'].")
                                    ON DUPLICATE KEY UPDATE
                                                            color       = '".$attr['color']."', 
                                                            cert        = '".$attr['cert']."',
                                                            cut         = '".$attr['cut']."',
                                                            cla         = '".$attr['cla']."',
                                                            base        =  ".$attr['base'].",
                                                            tabl        =  ".$attr['tabl'].",
                                                            val         =  ".$attr['val'].",
                                                            p_disc      =  ".$attr['p_disc'].",
                                                            prop        = '".$attr['prop']."',
                                                            pol         = '".$attr['pol']."',
                                                            sym         = '".$attr['sym']."',
                                                            fluo        = '".$attr['fluo']."',
                                                            td          =  ".$attr['td'].",
                                                            measurement = '".$attr['measurement']."',
                                                            cert1_no    = '".$attr['cert1no']."',
                                                            pa          =  ".$attr['pa'].",
                                                            cr_hgt      =  ".$attr['cr_hgt'].",
                                                            cr_ang      =  ".$attr['cr_ang'].",
                                                            girdle      =  ".$attr['girdle'].",
                                                            pd          =  ".$attr['pd']."";
                            $res = $this->query($sql);
                        
                    }
                    
                 //-----------------------------------------------------------------------------------------------------------------------------   
                    }
                }
                    $isInside = 1;
                   // $arr = array('product_id' => $pid);
            }
            }
          
            else
            {
                $arr="Some error in passing json array";
                $err = array('Code' => 1, 'Msg' => 'Something Went Wrong.');
            }
            if($isInside=1)
            {   
                $arr="product inserted";
                $err = array('Code' => 0, 'Msg' => 'Product added successfully'); 

            }
            else
            {
                $arr="Product Not Inserted";
                $err = array('Code' => 1, 'Msg' => 'Something Went Wrong.');
            }

                    $result = array('results'=>$arr, 'error' => $err);
                    return $result;
        }
        
// Uploading the image method.        
       /* public function imageUpdate($params)
        {
            $results = array();
            $dt      = json_decode($params['dt'],1);
            for($i=0;$i<count($dt['results']);$i++)
            {
                $imgPatharr[] = $dt['results'][$i]['imagepath'];
            }
            if(count($imgPatharr))
            {
                $imgPath = implode('|~|',$imgPatharr);
                $pid	 = $dt['pid'];
                $upImg   = '';
                $sql  	 = "SELECT prd_img FROM tb_master_prd WHERE product_id = ".$pid."";
                $res     = $this->query($sql);
                if($res)
                {
                    $row = $this->fetchData($res);
                    if(empty($row['prd_img']))
                    {
                            $upImg = $imgPath;
                    }
                    else
                    {
                        $imgExp = explode('|~|',$row['prd_img']);
                        $upImgExp = explode('|~|',$imgPath);
                        for($y=0;$y<count($upImgExp);$y++)
                        {
                            if(!in_array($upImgExp[$y],$imgExp))
                            $upImgarr[] = $upImgExp[$y];
                        }
                        if(count($upImgarr))
                        {
                            $imgPath = implode('|~|',$upImgarr);
                            $upImg .= $row['prd_img']."|~|".$imgPath;
                        }
                    }

                $sql = "UPDATE tb_master_prd SET prd_img='".$upImg."' WHERE product_id = ".$pid."";
                $res = $this->query($sql);
                $err = array('Code' => 0, 'Msg' => 'Product Image Added Successfully');
                }
            }
            $result = array('results' => $results, 'error' => $err);
            return $result;
        } */
        
        public function getPrdByName($params)
        { 
            $page   = $params['page'];
            $limit  = $params['limit'];
            $sql = "SELECT *,MATCH(product_name) AGAINST ('".$params['prname']."*' IN BOOLEAN MODE) as startwith FROM tb_master_prd WHERE MATCH(product_name) AGAINST ('".$params['prname']."*' IN BOOLEAN MODE) ORDER BY startwith DESC";
            if (!empty($page))
            {
                $start = ($page * $limit) - $limit;
                $sql.=" LIMIT " . $start . ",$limit";
            }
            $res = $this->query($sql);        
            if($res)
            {
                while( $row = $this->fetchData($res))
                {
                    if ($row && !empty($row['product_id'])) 
                    {
                        $reslt['product_id'] = $row['product_id'];
                        $reslt['product_name'] = $row['product_name'];
                        $reslt['product_display_name'] = $row['product_display_name'];
                        $reslt['product_model'] = $row['product_model'];
                        $reslt['product_brand'] = $row['product_brand'];
                        $reslt['barcode'] = $row['barcode'];
                        $reslt['prd_price'] = $row['prd_price'];
                        $reslt['product_currency'] = $row['product_currency'];
                        $reslt['product_warranty'] = $row['product_warranty'];
                        $reslt['product_desc'] = $row['product_desc'];
                        $reslt['prd_img'] = $row['prd_img'];
                        $reslt['desname'] = $row['desname'];
                        $arr[] = $reslt;
                    }
                }

                if(!empty($arr))
                {
                    $err = array('errCode' => 0, 'errMsg' => 'Details fetched successfully');
                }
                else
                {   $arr="There is no such record that matches ur string";
                    $err = array('errCode' => 1, 'errMsg' => 'No results found');
                }
                $result = array('results' => $arr, 'error' => $err);
                return $result;
            }
        }
        
        public function getPrdByCatid($params)
        {
            
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 20);
        
        $sql = "SELECT product_id FROM tbl_prd_cat_mapping WHERE category_id=".$params['catid'];
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $sql.=" LIMIT " . $start . ",$limit";
        }
        $res = $this->query($sql);
        $cres=$this->numRows($res);
        if($cres>0)
        {
            while ($row = $this->fetchData($res)) 
            {
                    $pid[] = $row['product_id'];
            }
            
            $pid=implode(',',$pid);
            
            $psql = "SELECT * FROM tb_master_prd WHERE product_id IN(".$pid.")";
            $pres=$this->query($psql);
            while($row1=$this->fetchData($pres))
            {
                $arr1[$row1['product_id']]=$row1;
            }
            
            $patsql="SELECT * from tbl_product_srch where product_id IN(".$pid.")";
            $patres=$this->query($patsql);
            
            while($row2=$this->fetchData($patres))
            {
                $arr1[$row2['product_id']]['attributes']=$row2;
            }
            $err = array('errCode'=>0,'errMsg'=>'Details fetched successfully');
        }
        else
        {
            $arr="There is no product within this category";
            $err="No records found";
        }
            $result = array('products'=>$arr1,'error'=>$err);
            return $result;
        }
        
        public function getPrdById($params)
        {
            $page   = $params['page'];
            $limit  = $params['limit'];
            
            $sql = "SELECT * FROM tb_master_prd WHERE product_id=".$params['prdid']."";
            if (!empty($page))
            {
                $start = ($page * $limit) - $limit;
                $sql.=" LIMIT " . $start . ",$limit";
            }
            $sql2 = "SELECT * FROM tbl_product_srch WHERE product_id=".$params['prdid']."";
            $res = $this->query($sql);
            $res2 = $this->query($sql2);
            if ($res)
            {
                $row = $this->fetchData($res);
                while($row2 = $this->fetchData($res2))
                {
                    if ($row2 && !empty($row2['product_id']))
                    {
                        $details[]=$row2;
                    }
                }
                if($row && !empty($row['product_id'])) 
                {
                    $reslt['product_id'] = $row['product_id'];
                    $reslt['product_barcode'] = $row['barcode'];
                    $reslt['product_lotref'] = $row['lotref'];
                    $reslt['product_lotno'] = $row['lotno'];
                    $reslt['product_name'] = $row['product_name'];
                    $reslt['product_display_name'] = $row['product_display_name'];
                    $reslt['product_model'] = $row['product_model'];
                    $reslt['product_brand'] = $row['product_brand'];
                    $reslt['product_price'] = $row['prd_price'];
                    $reslt['product_currency'] = $row['product_currency'];
                    $reslt['product_warranty'] = $row['product_warranty'];
                    $reslt['product_description'] = $row['product_desc'];
                    $reslt['product_image'] = $row['prd_img'];
                    
                    $reslt['attr_details'] =$details;
                    
                   $cat_info_sql = "SELECT category_id,category_name FROM tbl_categoryid_generator WHERE category_id=".$params['catid']."";
                   $cat_info_res = $this->query($cat_info_sql);
                    if($cat_info_res)
                    {
                        $cat_info_row = $this->fetchData($cat_info_res);
                        if($cat_info_row && $cat_info_row['category_id'])
                        {
                            $reslt['category_name'] = $cat_info_row['category_name'];
                        }
                    }
                    $arr[] = $reslt;
                    $err = array('errCode' => 0, 'errMsg' => 'Details fetched successfully');
                }
                else
                {
                    $arr="There is no such Id yet assigned.";
                    $err=array('Code'=>0,'Msg'=>'Error in fetching the data');
                }
            }
            $result = array('results' => $arr, 'error' => $err);
            return $result;
        }
        
        public function getList($params)
        {
            $total_products = 0;
            $cnt_sql = "SELECT COUNT(*) as cnt FROM tb_master_prd";
            $cnt_res = $this->query($cnt_sql);
            
            $page   = $params['page'];
            $limit  = $params['limit'];
            
            $sql = "SELECT * FROM tb_master_prd";
            
            if(!empty($page)) 
            {
                $start = ($page * $limit) - $limit;
                $sql.=" LIMIT " . $start . ",$limit";
            }
            $res = $this->query($sql);
            if($res) 
            {
                while ($row = $this->fetchData($res)) 
                {
                    $pid[]=$row['product_id'];
                    $arr1[]=$row;
                }
                
                $pid=implode(',',$pid);
                
                $psql="SELECT * from tbl_product_srch where product_id IN (".$pid.")";
                $pres=$this->query($psql);
                while($row2=$this->fetchData($pres))
                {
                    $arr2[]=$row2;
                }
                $err = array('errCode' => 0, 'errMsg' => 'Details fetched successfully');
                if($cnt_res)
                {
                    $cnt_row = $this->fetchData($cnt_res);
                    if($cnt_row && !empty($cnt_row['cnt']))
                    {
                        $total_products = $cnt_row['cnt'];
                    }
                }
            }
            else
            {
                $arr='There is no product in table';
                $err=array('Code'=>1,'Msg'=>'No record found ');
            }
            $result = array('product' =>$arr1,'prdAttributes' =>$arr2,'totalProducts'=>$total_products,'error'=>$err);
            return $result;
        }
        
        public function productByCity($params)
        {   
             $page   = $params['page'];
             $limit  = $params['limit'];
            
            $chksql="SELECT vendor_id from tbl_vendor_master where city='".$params['cityname']."'";
            $chkres=$this->query($chksql);
            $cnt_res1 = $this->numRows($chkres);            
            if($cnt_res1>0)
            {   $i=0;
                while($row1=$this->fetchData($chkres))
                {   
                    $arr1[$i]= $row1['vendor_id'];
                    $i++;
                }
                $mobs=implode(',',$arr1);
                $getpiddet="SELECT product_id from tbl_vendor_product_mapping where vendor_id IN(".$mobs.")"; 
                $chkres=$this->query($getpiddet);
                $cnt_res2 = $this->numRows($chkres);
                if($cnt_res2>0)
                {   $j=0;
                    while($row2=$this->fetchData($chkres))
                    {           
                        $arr2[$j]= $row2['product_id'];
                        $j++;
                    }
                    $pid=implode(',',$arr2);
                    $fillpiddet="SELECT * from tb_master_prd where product_id IN(".$pid.")";
                    if(!empty($page)) 
                    {
                        $start = ($page * $limit) - $limit;
                        $fillpiddet.=" LIMIT " . $start . ",$limit";
                    }
                     
                    $chkres=$this->query($fillpiddet);
                    $cnt_res3 = $this->numRows($chkres);
                    if($cnt_res3>0)
                    {
                        while($row3=$this->fetchData($chkres))
                        {   
                            $arr[]= $row3;
                            $i++;
                        }
                        $err=array('Code'=>0,'Msg'=>'Data fetched successfully');
                    }
                else
                    {
                    $arr="There is no product yet available with in this city";
                    $err=array('Code'=>1,'Msg'=>'Data not found');
                    }
                }
                else
                {
                    $arr="There is no product yet available with vendors in this city";
                    $err=array('Code'=>1,'Msg'=>'Data not found');
                }
            }
            else
            {
                $arr="There is no product yet available with in this city";
                $err=array('Code'=>1,'Msg'=>'Data not found');
            }
            $result=array('result'=>$arr,'error'=>$err);
            return $result;
        }
        
        public function productByBrand($params)
        {
            $page   = $params['page'];
            $limit  = $params['limit'];
            $chksql="SELECT * from tb_master_prd where product_brand='".$params['bname']."'";
            if(!empty($page)) 
            {
                $start = ($page * $limit) - $limit;
                $chksql.=" LIMIT " . $start . ",$limit";
            }
            $chkres=$this->query($chksql);
            $cnt_res1 = $this->numRows($chkres);            
            if($cnt_res1>0)
            {   
                while($row1=$this->fetchData($chkres))
                {   
                    $arr[]= $row1;
                }
                $err=array('Code'=>0,'Msg'=>'Products are successfully fetched from database');
            }    
            else
            {
                $arr="There is no product yet available with vendors in this city";
                $err=array('Code'=>1,'Msg'=>'Data not found');
            }
            $result=array('result'=>$arr,'error'=>$err);
            return $result;
        }
        
        public function productByDesigner($params)
        {
            $chksql="SELECT product_id from tbl_designer_product_mapping where desname='".$params['desname']."' and active_flag=1";
            $chkres=$this->query($chksql);
            
             $page   = $params['page'];
             $limit  = $params['limit'];
            
            $cnt_res1 = $this->numRows($chkres);            
            if($cnt_res1>0)
            {   $i=0;
                while($row1=$this->fetchData($chkres))
                {   
                    $arr1[$i]= $row1['product_id'];
                    $i++;
                }
                $pid=implode(',',$arr1);
                $fillpiddet="SELECT * from tb_master_product where product_id IN(".$pid.")";
                if(!empty($page)) 
                {
                    $start = ($page * $limit) - $limit;
                    $fillpiddet.=" LIMIT " . $start . ",$limit";
                }
                $chkres=$this->query($fillpiddet);
                $cnt_res3 = $this->numRows($chkres);
                if($cnt_res3>0)
                {
                    while($row3=$this->fetchData($chkres))
                    {   
                        $arr[]= $row3;
                        $i++;
                    }
                    $err=array('Code'=>0,'Msg'=>'Data fetched successfully');
                }
                else
                {
                    $arr="There is no active product in records";
                    $err[]=array('Code'=>1,'Msg'=>'Error in fetching detail');
                }
            }
            else
            {
                $arr="There is no product having such kind of design";
                $err=array('Code'=>1,'Msg'=>'Error in fetching detail');
            
            }
            $result=array('Result'=>$arr,'error'=>$err);
            return $result;
        }
       
        /*public function getSuggestions($params)
        {
        $str=$params['str'];
        $tblname=$params['tname'];
        if(!empty($str)) 
        {
            $sql = "SELECT *,IF(product_name LIKE '".$str."%',1,0) as startwith FROM $tblname WHERE MATCH(product_name) AGAINST (\"'" . $str . "*'\" IN BOOLEAN MODE) ORDER BY startwith DESC LIMIT 0,15";
            $res=$this->query($sql);
        }
        else
            {
        echo  $sql = "SELECT * FROM $tblname LIMIT 0,15";
              $res=$this->query($sql);
            }
        
        if($res)
        {
            while($row = $this->fetchData($res))
            {  
                if ($row && !empty($row['product_id'])) 
                {
                    
                    $reslt['id'] = $row['product_id'];
                    $reslt['name'] = $row['product_name'];
                    $arr[] = $reslt;
                }
            }
            if(!empty($arr))
            {
                $err = array('Code'=>0,'Msg'=>'Details fetched successfully');
            }
            else
            {
                $err = array('errCode'=>0,'errMsg'=>'No results found');
            }
        }
        $result = array('results' =>$arr,'error'=>$err);
        return $result;
    }
        */
    }
?>
        