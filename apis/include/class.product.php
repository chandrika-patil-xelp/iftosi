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
                    $catsql="Select category_name from tbl_categoryid_generator where category_id=".$detls['category_id'];
                    $catres=$this->query($catsql);
                    if($catres)
                    {
                        while($catrow=$this->fetchData($catres))
                        {
                            $catname=$catrow['category_name'];
                        }
                    }
                    $sql = "INSERT INTO tbl_brandid_generator(name) VALUES('".$detls['product_brand']."')";
                    $res = $this->query($sql);
                    $bid = $this->lastInsertedId();
                    $brandid = ($bid) ? $bid : $detls['brandid'];
                }
		else
                {
                    $row     = $this->fetchData($res);
                   $brandid = ($row['id']) ? $row['id'] : $detls['brandid'];
                }
                $sql  = "SELECT product_id,product_name FROM tbl_productid_generator WHERE product_name = '".$detls['product_name']."' AND product_brand = '".$detls['product_brand']."'";
                $res  = $this->query($sql);
                $cnt2 = $this->numRows($res);
                
                if(!$cnt2)
                {
                    
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
                {
                        $sql="INSERT INTO tb_master_prd(product_id,barcode,lotref,lotno,product_name,product_display_name,
                                                     product_model,product_brand,prd_price,product_currency,product_keyword,                                                     
                                                     product_desc,prd_wt,prd_img,category_id,brand_id,product_warranty,
                                                     updatedby, updatedon, cdt)
                                                VALUES (
							".$pid.",'".$detls['barcode']."','".$detls['lotref']."',".$detls['lotno'].",
                                                      '".$detls['product_name']."','".$detls['product_display_name']."', 
                                                      '".$detls['product_model']."', '".$detls['product_brand']."', ".$detls['product_price'].",
                                                      '".$detls['product_currency']."','".$detls['product_keywords']."', 
                                                      '".$detls['product_desc']."',".$detls['product_wt'].",'".$detls['prd_img']."',".$detls['category_id'].",
                                                      '".$detls['product_warranty']."', 'CMS USER', now(), now())
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
                                                    category_id                  = ".$detls['category_id'].",  
                                                    product_warranty             ='".$detls['product_warranty']."',
                                                    updatedby 			 =   'CMS USER', 
                                                    updatedon 			 =    now(), 
                                                    cdt                          =    now()";
                    $res = $this->query($sql);
                    if(count($attr))
                    {
                        foreach($attr as $ky => $vl)
                        {
                            $vls[] = "(".$pid.", ".$vl[$ky].",".$vl[$ky+1].",'".$vl[$ky+2]."', 1, 'CMS_USER',now())";
                        }
                        if(count($vls))
                        {
                            $vlStr = implode(', ',$vls);
                            $sql = "INSERT INTO tbl_product_attributes(product_id,attribute_id,category_id,value,active_flag,updatedby,updatedon)
                                    VALUES ".$vlStr."
                                    ON DUPLICATE KEY UPDATE
                                                            value       = value, 
                                                            active_flag = active_flag, 
                                                            updatedby	= updatedby, 
                                                            updatedon	= updatedon";
                            $res = $this->query($sql);
                        }
                    }
                    $isInside = 1;
                   // $arr = array('product_id' => $pid);
                        }
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
        
        
// Product Add in another way//
        /*        
        public function addProduct($params)
        {
            $attr = urldecode($params['dt']);
            $attr = json_decode($attr,true);           
            $detls=$attr['results'];
            $pAttr=$attr['attribute'];            
            $pbarcode=$detls[0]['barcode'];
            $plotref=$detls[0]['lotref'];
            $plotno=$detls[0]['lotno'];
            $pname=$detls[0]['product_name'];
            $pDisName=$detls[0]['product_display_name'];
            $pModel=$detls[0]['product_model'];
            $pBrand=$detls[0]['product_brand'];
            $pPrice=$detls[0]['prd_price'];
            $pCurr=$detls[0]['product_currency'];
            $pKeyword=$detls[0]['product_keyword'];
            $pDesc=$detls[0]['product_desc'];
            $pWeight=$detls[0]['prd_wt'];
            $pImg="tobeUpdate.png";
            $pCatid=$detls[0]['category_id'];
            $pBrId=$detls[0]['brand_id'];
            $pWarranty=$detls[0]['product_warranty'];
            
            $sql="INSERT INTO tbl_productid_generator(product_name,product_brand) values('".$pname."','".$pBrand."')";
            $res = $this->query($sql);

            
            $ins_id =$this->lastInsertedId();
            $sql1="INSERT INTO tb_master_prd(product_id,barcode,lotref,lotno,product_name,product_display_name,product_model,product_brand,prd_price,product_currency,product_keyword,product_desc,prd_wt,prd_img,category_id,brand_id,product_warranty,updatedby,updatedon)";
            $sql1.="VALUES(".$ins_id.",'".$pbarcode."','".$plotref."',".$plotno.",'".$pname."','".$pDisName."','".$pModel."','".$pBrand."',".$pPrice.",'".$pCurr."','".$pKeyword."','".$pDesc."','".$pweight."','".$pImg."',$pCatid,$pBrId,'".$pwarranty."','CMS USER',now(),now())";
            $res1 = $this->query($sql1);
            
            $len=  sizeof($pAttr);
            $i=0;
            $insertStr;
            while($i<$len)
            {
                $attr=$pAttr[$i]['attr_id'];
                $value=$pAttr[$i]['value'];
                $insertStr.="(".$ins_id.",".$attr.",\"$value\",1,'cms_User',now()),";
                $i++;
            }
               
            $insertStr= trim($insertStr,",");
            $insertAttr ="INSERT INTO tbl_product_attributes(product_id,attr_id,value,active_flag,updatedby,updatedon) VALUES ".$insertStr;
            $res2 = $this->query($insertAttr);
            if($res2)
            {
                $err = array('errCode' => 0, 'errMsg' => 'Product added successfully!');
            }
            $result = array('results' => $results, 'error' => $err);
            return $result;
        }
*/  
        
        
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
        $page   = 1;
        $limit  = 50;

            $sql = "SELECT * FROM tb_master_prd WHERE product_name LIKE '%".$params['prname']."%' ORDER BY '".$params['prname']."' DESC ";
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
        $page   = 1;
        $limit  = 50;
        
        $sql = "SELECT * FROM tb_master_prd WHERE category_id=".$params['catid'];
        if(!empty($page))
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
                if ($row && !empty($row['product_id'])) 
                {
                    $reslt['product_id'] = $row['product_id'];
                    $reslt['product_name'] = $row['product_name'];
                    $reslt['product_display_name'] = $row['product_display_name'];
                    $reslt['product_model'] = $row['product_model'];
                    $reslt['brand_id'] = $row['brand_id'];
                    $reslt['product_warranty'] = $row['product_warranty'];
                    $reslt['product_price'] = $row['prd_price'];
                    $reslt['product_currency'] = $row['product_currency'];
                    $reslt['product_barcode'] = $row['barcode'];
                    $reslt['product_desc'] = $row['product_desc'];
                    $reslt['product_image'] = $row['prd_img'];
                    $arr[]=$reslt;
                }
            }
                $err = array('errCode'=>0,'errMsg'=>'Details fetched successfully');
        }
        else
        {
            $arr="There is no product within this category";
            $err="No records found";
        }
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }
        
        public function getPrdById($params)
        {
            $sql = "SELECT * FROM tb_master_prd WHERE product_id=".$params['prdid']." LIMIT 0,1";
            $sql2 = "SELECT * FROM tbl_product_attributes WHERE product_id=".$params['prdid'];
            $res = $this->query($sql);
            $res2 = $this->query($sql2);

            if ($res)
            {
                $row = $this->fetchData($res);
                while($row2 = $this->fetchData($res2))
                {
                    if ($row2 && !empty($row2['product_id']) && $row2['active_flag']==1 )
                    {
                        $attr_dtls_sql = "SELECT attr_id,attr_name,attr_display_name FROM tb_attribute_master WHERE attr_id=".$row2['attribute_id'];
                        $attr_dtls_res = $this->query($attr_dtls_sql);

                        $det['attr_id'] = $row2['attribute_id'];
                        
                        if($attr_dtls_res)
                        {
                            $attr_dtls_row = $this->fetchData($attr_dtls_res);
                            if($attr_dtls_row && !empty($attr_dtls_row['attr_id']))
                            {
                                $det['attr_name'] = $attr_dtls_row['attr_name'];
                                $det['attr_display_name'] = $attr_dtls_row['attr_display_name'];
                            }
                        }
                        $det['value'] = $row2['value'];
                        $details[]=$det;
                    }
                }
                if ($row && !empty($row['product_id'])) 
                {
                    $reslt['product_id'] = $row['product_id'];
                    $reslt['product_barcode'] = $row['barcode'];
                    $reslt['product_lotref'] = $row['lotref'];
                    $reslt['product_lotno'] = $row['lotno'];
                    $reslt['product_name'] = $row['product_name'];
                    $reslt['product_display_name'] = $row['product_display_name'];
                    $reslt['product_model'] = $row['product_model'];
                    $reslt['product_brand'] = $row['product_brand'];
                    
                    $reslt['attr_details'] =$details;
                    
                    $reslt['product_price'] = $row['prd_price'];
                    $reslt['product_currency'] = $row['product_currency'];
                    $reslt['product_warranty'] = $row['product_warranty'];
                    $reslt['product_description'] = $row['product_desc'];
                    $reslt['product_image'] = $row['prd_img'];

                   $cat_info_sql = "SELECT category_id,category_name FROM tbl_categoryid_generator WHERE category_id=".$row['category_id'];
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
            $page   = 1;
            $limit  = 50;
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
                        $arr[]=$row;
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
            $result = array('results' =>$arr,'total_products'=>$total_products,'error'=>$err);
            return $result;
        }
        
        public function productByCity($params)
        {
            $chksql="SELECT contact_mobile from tbl_vendor_master where city='".$params['cityname']."' and active_flag=1";
            $chkres=$this->query($chksql);
            $cnt_res1 = $this->numRows($chkres);            
            if($cnt_res1>0)
            {   $i=0;
                while($row1=$this->fetchData($chkres))
                {   
                    $arr1[$i]= $row1['contact_mobile'];
                    $i++;
                }
                $mobs=implode(',',$arr1);
                $getpiddet="SELECT product_id from tbl_vendor_product_mapping where vendormobile IN(".$mobs.")"; 
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
            $chksql="SELECT * from tb_master_prd where product_brand='".$params['bname']."'";
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
    }
?>
        