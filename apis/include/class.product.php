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
                    $catsql="Select cat_name from tbl_category_master where catid=".$detls['category_id'];
                    $catres=$this->query($catsql);
                    if($catres)
                    {
                        while($catrow=$this->fetchData($catres))
                        {
                            $catname=$catrow['cat_name'];
                        }
                    }
                    
                    //  Inserting the values in brand table
                    $sql = "INSERT INTO tbl_brandid_generator(name,category_name,date_time,aflg) VALUES('".$detls['product_brand']."','".$catname."',now(),1)";
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
                  $sql = "INSERT INTO tbl_productid_generator(product_name,product_brand, date_time) VALUES('".$detls['product_name']."','".$detls['product_brand']."', now())";
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
                   $dessql="insert into tbl_designer_product_mapping(product_id,desname,active_flag,date_time)
                            VALUES(".$pid.",'".$des['desname']."',1,now())";
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
                        $pcsql="INSERT INTO tbl_product_category_mapping(product_id,category_id,price,rating,display_flag,date_time)
                                VALUES(\"".$pid."\",\"".$detls['category_id']."\",\"".$detls['product_price']."\",\"".$detls['rating']."\",1,now())
                                ON DUPLICATE KEY UPDATE
                                                        category_id             = \"".$detls['category_id']."\",
                                                        price                   = \"".$detls['price']."\",
                                                        rating                  = \"".$detls['rating']."\",
                                                        display_flag            = \"".$detls['dflag']."\"";
                        $pcres=$this->query($pcsql);
                        
                    //  For product values filling     
                        $sql="INSERT INTO tbl_product_master(product_id,barcode,lotref,lotno,product_name,product_display_name,
                                                     product_model,product_brand,prd_price,product_currency,product_keyword,                                                     
                                                     product_desc,prd_wt,prd_img,product_warranty,desname,updatedby, date_time)
                                                VALUES (
						       ".$pid.",'".$detls['barcode']."','".$detls['lotref']."',".$detls['lotno'].",
                                                      '".$detls['product_name']."','".$detls['product_display_name']."', 
                                                      '".$detls['product_model']."', '".$detls['product_brand']."', ".$detls['product_price'].",
                                                      '".$detls['product_currency']."','".$detls['product_keywords']."', 
                                                      '".$detls['product_desc']."',".$detls['product_wt'].",'".$detls['prd_img']."',
                                                      '".$detls['product_warranty']."','".$des['desname']."','CMS USER', now())
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
                                                    desname                      ='".$des['desname']."'";
                    $res = $this->query($sql);
                   //----------------------------------------------For product search table--------------------------------------------------- 
                    
                    if(count($attr))
                    {
                            //  For tbl_product_search
        // Few attributes remaining-- type,metal,purity,nofd,dwt,gemwt,quality,goldwt
                            $sql = "INSERT INTO tbl_product_search(product_id,color,cert,cut,cla,base,tabl,val,p_disc,prop,pol,sym,fluo,td,measurement,cert1_no,pa,cr_hgt,cr_ang,girdle,pd, date_time) VALUES
                                    (\"".$pid."\",\"".$attr['color']."\",\"".$attr['cert']."\",\"".$attr['cut']."\",\"".$attr['cla']."\",\"".$attr['base']."\",\"".$attr['tabl']."\",\"".$attr['val']."\",\"".$attr['p_disc']."\",
                                     \"".$attr['prop']."\",\"".$attr['pol']."\",\"".$attr['sym']."\",\"".$attr['fluo']."\",\"".$attr['td']."\",\"".$attr['measurement']."\",\"".$attr['cert1no']."\",\"".$attr['pa']."\",\"".$attr['cr_hgt']."\",
                                     \"".$attr['cr_ang']."\",\"".$attr['girdle']."\",\"".$attr['pd']."\", now())
                                    ON DUPLICATE KEY UPDATE
                                                            color       = \"".$attr['color']."\", 
                                                            cert        = \"".$attr['cert']."\",
                                                            cut         = \"".$attr['cut']."\",
                                                            cla         = \"".$attr['cla']."\",
                                                            base        = \"".$attr['base']."\",
                                                            tabl        = \"".$attr['tabl']."\",
                                                            val         = \"".$attr['val']."\",
                                                            p_disc      = \"".$attr['p_disc']."\",
                                                            prop        = \"".$attr['prop']."\",
                                                            pol         = \"".$attr['pol']."\",
                                                            sym         = \"".$attr['sym']."\",
                                                            fluo        = \"".$attr['fluo']."\",
                                                            td          = \"".$attr['td']."\",
                                                            measurement = \"".$attr['measurement']."\",
                                                            cert1_no    = \"".$attr['cert1no']."\",
                                                            pa          = \"".$attr['pa']."\",
                                                            cr_hgt      = \"".$attr['cr_hgt']."\",
                                                            cr_ang      = \"".$attr['cr_ang']."\",
                                                            girdle      = \"".$attr['girdle']."\",
                                                            pd          = \"".$attr['pd']."\"";
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
                $arr=array();
                $err = array('Code' => 1, 'Msg' => 'Something Went Wrong.');
            }
            if($isInside=1)
            {   
                $arr="product inserted";
                $err = array('Code' => 0, 'Msg' => 'Product added successfully'); 

            }
            else
            {
                $arr=array();
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
                $sql  	 = "SELECT prd_img FROM tbl_product_master WHERE product_id = ".$pid."";
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

                $sql = "UPDATE tbl_product_master SET prd_img='".$upImg."' WHERE product_id = ".$pid."";
                $res = $this->query($sql);
                $err = array('Code' => 0, 'Msg' => 'Product Image Added Successfully');
                }
            }
            $result = array('results' => $results, 'error' => $err);
            return $result;
        } */
        
        public function getPrdByName($params)
        { 
            $page   = ($params['page'] ? $params['page'] : 1);
            $limit  = ($params['limit'] ? $params['limit'] : 15);
            
            $sql = "SELECT *,MATCH(product_name) AGAINST ('".$params['prname']."*' IN BOOLEAN MODE) as startwith FROM tbl_product_master WHERE MATCH(product_name) AGAINST ('".$params['prname']."*' IN BOOLEAN MODE) ORDER BY startwith DESC";
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
			$limit  = ($params['limit'] ? $params['limit'] : 15);
			
			$sql = "SELECT 
						count(1) as cnt 
					FROM 
						tbl_product_category_mapping 
					WHERE 
						category_id=".$params['catid'];
			$res = $this->query($sql);
			if($res)
			{
				$row = $this->fetchData($res);
				$total = $row['cnt'];
			}
			
			$sql = "SELECT 
						product_id as pid,
						price						
					FROM 
						tbl_product_category_mapping 
					WHERE 
						category_id=".$params['catid'];
			
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
							$field = $ex[0];
							$inarr[] = $ex[1];
						}
						$extn .= " AND ".$field." in ('".implode("','",$inarr)."') ";
					}
				}
				
				$allpids = $pid = implode(',',$pid);
				
				$page   = ($params['page'] ? $params['page'] : 1);
				$limit  = ($params['limit'] ? $params['limit'] : 15);
				
				$sql = "SELECT 
							count(1) as cnt 
						FROM 
							tbl_product_search 
						WHERE 
							product_id IN(".$pid.")
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
							product_id,
							carat,
							color,
							certified,
							shape,
							clarity,
							price,
							polish,
							symmetry,
							cno
						FROM 
							tbl_product_search 
						WHERE 
							product_id IN(".$pid.")
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
				
				$pid = $pids = implode(',',$prodid);
				
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
						group_concat(attribute_id) AS ids
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
					$row 		= $this->fetchData($res);
					$attrids 	= $row['ids'];
				}
				
				$sql="
					SELECT
						attr_id, 
						attr_name, 
						attr_display_name, 
						attr_unit, 
						attr_unit_pos,
						attr_type_flag,
						attr_values					
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
										";
								$res1 = $this->query($qry);
								if($res1)
								{
									$row1 = $this->fetchData($res1);
									$data[$i]['range']['id'] 		= $row['attr_id'];
									$data[$i]['range']['name'] 		= $row['attr_name'];
									$data[$i]['range']['dname'] 	= $row['attr_display_name'];
									$data[$i]['range']['value'] 	= $row1['minval'].';'.$row1['maxval'];
									$data[$i]['range']['ovalue'] 	= $row1['attr_values'];
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
										";
								$res1 = $this->query($qry);
								if($res1)
								{
									$arr = array();
									$row1 = $this->fetchData($res1);
									$expd = explode(',',$row['attr_values']);
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
									$data[$i]['checkbox']['ovalue'] = $row['attr_values'];
									$i++;
								}
							break;
							
							case 8:
							break;
						}
					}
				}
				
				/* *********** */
				
				$arr1 = array('filters'=>$data,'products'=>array_values($arr1),'total'=>$total,'getdata'=>$params);
				$err = array('errCode'=>0,'errMsg'=>'Details fetched successfully');
			}
			else
			{
				$arr1 = array();
				$err = array('errCode'=>1,'errMsg'=>'No records found');
			}
            $result = array('results'=>$arr1,'error'=>$err);
            return $result;
        }
        
        public function getPrdById($params)
        {
          $page   = ($params['page'] ? $params['page'] : 1);
          $limit  = ($params['limit'] ? $params['limit'] : 15);
            
            $sql = "SELECT * FROM tbl_product_master WHERE product_id=".$params['prdid']."";
            if (!empty($page))
            {
                $start = ($page * $limit) - $limit;
                $sql.=" LIMIT " . $start . ",$limit";
            }
            $sql2 = "SELECT 
						product_id,
						carat,
						color,
						certified,
						shape,
						clarity,
						price,
						polish,
						symmetry,
						cno, 
						cut,
						fluo as fluorescence,
						measurement,
						td as tab,
						cr_ang as crownangle,
						girdle,
						base as baseprice,
						p_disc as discount
                    FROM 
                        tbl_product_search
                    WHERE 
                        product_id=".$params['prdid']."";
            $res = $this->query($sql);
            $res2 = $this->query($sql2);
            
            
            if ($res)
            {
                $row = $this->fetchData($res);
                while($row2 = $this->fetchData($res2))
                {
                    if ($row2 && !empty($row2['product_id']))
                    {
                        $prid[]=$row['product_id'];
                        $details=$row2;
                    }
                }
                $pid=implode(',',$prid);
                
                $sql3="SELECT 
                                    product_id,
                                    vendor_id,
                                    vendor_price,
                                    vendor_quantity,
                                    vendor_currency,
                                    vendor_remarks 
                        FROM 
                                    tbl_vendor_product_mapping
                        WHERE 
                                    product_id=".$pid."
                        ORDER BY
                                    vendor_id ASC";
                
                $res3=$this->query($sql3);
                
                while($row3=$this->fetchData($res3))
                {
                    $vid[]=$row3['vendor_id'];
                    $vpdetls[$row3['vendor_id']]=$row3;
                }
                
                $venid=implode(',',$vid);
                
                
                $sql4="SELECT 
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
                                        vendor_id IN(".$venid.")
                            ORDER BY
                                        field(vendor_id,".$venid.")";
                
                $res4=$this->query($sql4);
                
                while($row4=$this->fetchData($res4))
                {
                    $vid[]=$row4['vid'];
                    $vdetls[$row4['vid']]=$row4;
                }
                
                
                $cat_info_sql = "SELECT catid,cat_name,cat_lvl,p_catid,lineage FROM tbl_category_master WHERE catid=".$params['catid']."";
                $cat_info_res = $this->query($cat_info_sql);
                if($cat_info_res)
                {
                    $cat_info_row = $this->fetchData($cat_info_res);
                    if($cat_info_row && $cat_info_row['catid'])
                    {
                        $reslt1['category_name'] = $cat_info_row['cat_name'];
                    }
                }
           
                
                if($row && !empty($row['product_id'])) 
                {
                    $pid = $row['product_id'];
                    
                    $reslt[$pid]['product_barcode'] = $row['barcode'];
                    $reslt[$pid]['product_lotref'] = $row['lotref'];
                    $reslt[$pid]['product_lotno'] = $row['lotno'];
                    $reslt[$pid]['product_name'] = $row['product_name'];
                    $reslt[$pid]['product_display_name'] = $row['product_display_name'];
                    $reslt[$pid]['product_model'] = $row['product_model'];
                    $reslt[$pid]['product_brand'] = $row['product_brand'];
                    $reslt[$pid]['product_price'] = $row['prd_price'];
                    $reslt[$pid]['product_currency'] = $row['product_currency'];
                    $reslt[$pid]['product_warranty'] = $row['product_warranty'];
                    $reslt[$pid]['product_description'] = $row['product_desc'];
                    $reslt[$pid]['product_image'] = $row['prd_img'];

                    $reslt[$pid]['category_name'] =$reslt1['category_name'];

                    $reslt[$pid]['attr_details'] =$details;

                    $reslt[$pid]['vendor_product_details']=$vpdetls;
                    
                    $reslt[$pid]['vendor_details']=$vdetls;
                   
                   
                    $arr = $reslt;
                    $err = array('errCode' => 0, 'errMsg' => 'Details fetched successfully');
                }
                else
                {
                    $arr=array();
                    $err=array('Code'=>0,'Msg'=>'Error in fetching the data');
                }
            }
            $result = array('results' => $arr, 'error' => $err);
            return $result;
        }
        
        public function getList($params)
        {
            $total_products = 0;
            $cnt_sql = "SELECT COUNT(1) as cnt FROM tbl_product_master";
            $cnt_res = $this->query($cnt_sql);
            
            $page   = ($params['page'] ? $params['page'] : 1);
            $limit  = ($params['limit'] ? $params['limit'] : 15);
            
            $sql = "SELECT 
                            product_id,
                            barcode as pcode,
                            product_name as pname,
                            product_display_name as pdname,
                            product_model as pmodel,
                            product_brand as pbrand,
                            prd_price as pprice,
                            product_currency as pcur,
                            desname as product_designer,
                            prd_img as pimg
                    FROM 
                            tbl_product_master 
                    ORDER BY 
                            product_id ASC";
            
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
                    $arr1[$row['product_id']]=$row;
                }
                
                $pid=implode(',',$pid);
                
                $psql="SELECT 
                                product_id,
                                carat,
                                color,
                                certified,
                                shape,
                                clarity,
                                price,
                                polish,
                                symmetry,
                                cno
                       FROM 
                       tbl_product_search 
                       WHERE 
                                product_id IN (".$pid.")
                       ORDER BY 
                                product_id ASC";
                $pres=$this->query($psql);
                while($row2=$this->fetchData($pres))
                {
                    $pid = $row2['product_id'];
                    unset($row2['product_id']);
                    $arr1[$pid]['attributes']=$row2;
                }

                if($cnt_res)
                {
                    $cnt_row = $this->fetchData($cnt_res);
                    if($cnt_row && !empty($cnt_row['cnt']))
                    {
                        $total = $cnt_row['cnt'];
                    }
                }
                $arr1 = array('products'=>array_values($arr1),'total'=>$total);
                $err = array('errCode' => 0, 'errMsg' => 'Details fetched successfully');
            }
            else
            {
                $arr1=array();
                $err=array('Code'=>1,'Msg'=>'No record found ');
            }
            $result = array('results'=>$arr1,'error'=>$err);
            return $result;
        }
        
        public function productByCity($params)
        {   
            $page   = ($params['page'] ? $params['page'] : 1);
            $limit  = ($params['limit'] ? $params['limit'] : 15);
            
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
                    $fillpiddet="SELECT 
                                        product_id,
                                        barcode as code,
                                        product_name as pname,
                                        product_display_name as dname,
                                        product_model as model,
                                        product_brand as brand,
                                        prd_price as price,
                                        product_currency as cur,
                                        desname as product_designer,
                                        prd_img as pimg 
                                FROM 
                                        tbl_product_master
                                WHERE
                                        product_id IN(".$pid.")";
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
                    $arr=array();
                    $err=array('Code'=>1,'Msg'=>'Data not found');
                    }
                }
                else
                {
                    $arr=array();
                    $err=array('Code'=>1,'Msg'=>'Data not found');
                }
            }
            else
            {
                $arr=array();
                $err=array('Code'=>1,'Msg'=>'Data not found');
            }
            $result=array('result'=>$arr,'error'=>$err);
            return $result;
        }
        
        public function productByBrand($params)
        {
            $page   = ($params['page'] ? $params['page'] : 1);
            $limit  = ($params['limit'] ? $params['limit'] : 15);
            
            $chksql="SELECT 
                            product_id,
                            barcode,
                            lotref,
                            lotno,
                            product_name as pname,
                            product_display_name as pdname,
                            product_model as model,
                            product_brand as brand,
                            prd_price as price,
                            product_currency as cur,
                            desname as product_designer,
                            prd_img as pimg 
                      FROM
                            tbl_product_master 
                      WHERE 
                            product_brand='".$params['bname']."'";
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
                $arr=array();
                $err=array('Code'=>1,'Msg'=>'Data not found');
            }
            $result=array('result'=>$arr,'error'=>$err);
            return $result;
        }
        
        public function productByDesigner($params)
        {
            $chksql="SELECT product_id from tbl_designer_product_mapping where desname=\"".$params['desname']."\" and active_flag=1";
            $chkres=$this->query($chksql);
            
            $page   = ($params['page'] ? $params['page'] : 1);
            $limit  = ($params['limit'] ? $params['limit'] : 15);
            
            $cnt_res1 = $this->numRows($chkres);            
            if($cnt_res1>0)
            {   $i=0;
                while($row1=$this->fetchData($chkres))
                {   
                    $arr1[$i]= $row1['product_id'];
                    $i++;
                }
                $pid=implode(',',$arr1);
                $fillpiddet="SELECT 
                                    product_id,
                                    barcode as bcode,
                                    lotref,
                                    lotno,
                                    product_name as pname,
                                    product_display_name as pdname,
                                    product_model as pmodel,
                                    product_brand as pbrand,
                                    prd_price as pprice,
                                    product_currency as pcur,
                                    desname as product_designer,
                                    prd_img as pimg 
                              FROM 
                                    tbl_product_master 
                              WHERE
                                    product_id IN(".$pid.")";
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
                    $arr=array();
                    $err[]=array('Code'=>1,'Msg'=>'Error in fetching detail');
                }
            }
            else
            {
                $arr=array();
                $err=array('Code'=>1,'Msg'=>'Error in fetching detail');
            
            }
            $result=array('Result'=>$arr,'error'=>$err);
            return $result;
        }
        
        /* public function getSuggestions($params)
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
        
