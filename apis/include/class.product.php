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
            $vendSql = "Select * from tbl_vendor_master where vendor_id = ".$params['vid'];
            $vendres = $this->query($vendSql);
            if($vendres)
            {
                $vendrow = $this->fetchData($vendres);
                $display_flag = $vendrow['active_flag'];
            }
                  
            
            $detl=$params['dt'];
            $len=strlen($detl);

            
            $detls1=explode('|~|',$params['dt']);

            
            for($i=0;$i<count($detls1);$i++)
            {
                $expd = explode('|@|',$detls1[$i]);
                $detls[$expd[0]] = $expd[1];
            }
            if(($detls['shape']=='gBars')||($detls['shape']=='sBars')||($detls['shape']=='gCoins')||($detls['shape']=='sCoins'))
            {
                $type=substr($detls['shape'],1,-1);
                $temp=$detls['shape'];
                $shape=$detls['shape'];
                if(($temp=='gBars')||($temp=='gCoins'))
                {
                    $detls['metal']='Gold';
                }
                else if(($temp=='sBars')||($temp=='sCoins'))
                {
                    $detls['metal']='Silver';
                }
                
                $catname=substr($detls['shape'],1); 
                $catname=$catname;
               $catidsql="Select catid from tbl_category_master where cat_name='".$catname."'";
                $catidres=$this->query($catidsql);
               $catidrow=$this->fetchData($catidres);
                $catids1=$catidrow['catid'];
            }
            else
            {
                $shape=$detls['shape'];
                $catids1=$detls['subcatid'];
                
            }
            $maincatsql="SELECT p_catid from tbl_category_master where catid IN(\"".$catids1."\")";
            $maincatres=$this->query($maincatsql);
            $maincatrow=$this->fetchData($maincatres);
            
            $temp=count($catids);
            $catids1.=','.$maincatrow['p_catid'];
            $catids1.=','.$params['category_id'];
            
            $catids=explode(',',$catids1);
            

            $detls['measurement']=$detls['measurement1'].'*'.$detls['measurement2'].'*'.$detls['measurement3'];
                    //  Inserting the values in brand table
          
           
                    $sql = "INSERT
                            INTO 
                                        tbl_brandid_generator
                                                               (name,
                                                                category_id,
                                                                date_time,
                                                                aflg) 
                            VALUES
                                                            (\"".$detls['brand']."\",
                                                             \"".$params['catid']."\",
                                                                 now(),
                                                             \"".$display_flag."\")";

                    $res = $this->query($sql);
                    if(!empty($params['prdid']))
                {
                    $sql  = "SELECT
                                                            product_id
                             FROM
                                        tbl_product_master
                             WHERE
                                                            product_id =\"".$params['prdid']."\"
                             AND                                   
                                                            active_flag=\"".$display_flag."\"
                             ORDER BY
                                                            update_time
                             DESC
                             LIMIT 10";
                    $res  = $this->query($sql);
                    $cnt2 = $this->numRows($res);
                       $row=$this->fetchData($res);
                        $pid=$row['product_id'];
                }
                else if(empty($params['prdid']))
                {
                  //  If product not present in generator table new product insertion process starts
                  $sql = "INSERT
                          INTO 
                                  tbl_productid_generator
                                                                  (product_name,
                                                                  product_brand,
                                                                  date_time)
                          VALUES
                                                              (\"".$detls['product_name']."\",
                                                               \"".$detls['product_brand']."\",
                                                                   now())";
                  $res = $this->query($sql);
                  $pid = $this->lastInsertedId();
                }
                if(!empty($pid))
                {   // Checks for the designer name along with product id
                   $chksql="SELECT
                                                           designer_id,
                                                           desname
                            FROM 
                                   tbl_designer_product_mapping 
                            WHERE
                                                            active_flag=\"".$display_flag."\"
                            AND                                
                                                            product_id=".$pid."";
                   
                   $chkdes=$this->query($chksql);
                   $cntres=$this->numRows($chkdes);
                   if($cntres==0)
                   {
                    //  For product designer tabe insertion   
                   $dessql="INSERT
                            INTO
                            tbl_designer_product_mapping
                                                                    (product_id,
                                                                    desname,
                                                                    active_flag,
                                                                    date_time)
                            VALUES
                                                                (\"".$pid."\",
                                                                 \"".$detls['desname']."\",
                                                                 \"".$display_flag."\",
                                                                     now())";
                  
                   $desres = $this->query($dessql);
                   }
                   else
                   {
                       
                       $row = $this->fetchData($chkdes);
                       $did=$row['designer_id'];    
                   
                   //   designer product table value updating if product already present
                    $dessql="UPDATE
                                        tbl_designer_product_mapping
                            SET
                                                                        desname=\"".$detls['desname']."\"
                            WHERE 
                                                                        designer_id=\"".$did."\"";
                    $desres = $this->query($dessq);
                   
                   }
                    //  For category product mapping
                        
                   
                       $catids1 = explode(',', $catids1);
                       $catids1 = implode('","', $catids1);
                            $updtcatsql="UPDATE tbl_product_category_mapping set display_flag=1 where category_id NOT IN(\"".$catids1."\") and product_id=\"".$pid."\"";    
                            $updtcatres=$this->query($updtcatsql);
                        
                        for($i=0;$i<count($catids);$i++)
                        {
                            if(!empty($catids[$i]))
                            {
                                
                                
                                
                                $pcsql="INSERT
                                        INTO 
                                                    tbl_product_category_mapping
                                                                                   (product_id,
                                                                                    category_id,
                                                                                    price,
                                                                                    rating,
                                                                                    display_flag,
                                                                                    date_time)
                                        VALUES
                                                                               (\"".$pid."\",
                                                                                \"".$catids[$i]."\",
                                                                                \"".$detls['price']."\",
                                                                                \"".$detls['rating']."\",
                                                                                \"".$display_flag."\",
                                                                                    now())
                                ON DUPLICATE KEY UPDATE
                                                        category_id             = \"".$catids[$i]."\",
                                                        price                   = \"".$detls['price']."\",
                                                        rating                  = \"".$detls['rating']."\",
                                                    display_flag                = \"".$display_flag."\"";
                                $pcres=$this->query($pcsql);
                           }
                        }
                    //  For product values filling     
                        $sql="  INSERT
                                INTO 
                                                tbl_product_master
                                                                        (product_id,
                                                                        barcode,
                                                                        lotref,
                                                                        lotno,
                                                                        product_name,
                                                                        product_display_name,
                                                                        product_model,
                                                                        product_brand,
                                                                        prd_price,
                                                                        product_currency,
                                                                        product_keyword,                                                     
                                                                        product_desc,
                                                                        prd_wt,
                                                                        prd_img,
                                                                        product_warranty,
                                                                        desname,
                                                                        date_time,
                                                                        active_flag)
                                                VALUES
                                                                 ( \"".$pid."\",
                                                                   \"".$detls['barcode']."\",
                                                                   \"".$detls['lot_ref']."\",
                                                                   \"".$detls['lot_no']."\",
                                                                   \"".$detls['product_name']."\",
                                                                   \"".$detls['product_display_name']."\", 
                                                                   \"".$detls['product_model']."\",
                                                                   \"".$detls['product_brand']."\",
                                                                   \"".$detls['price']."\",
                                                                   \"".$detls['product_currency']."\",
                                                                   \"".$detls['product_keywords']."\", 
                                                                   \"".$detls['product_desc']."\",
                                                                   \"".$detls['product_wt']."\",
                                                                   \"".$detls['prd_img']."\",
                                                                   \"".$detls['product_warranty']."\",
                                                                   \"".$detls['desname']."\",
                                                                       now(),
                                                                   \"".$display_flag."\")
                                ON DUPLICATE KEY UPDATE
                                                    barcode                      = \"".$detls['barcode']."\", 
                                                    lotref                       = \"".$detls['lot_ref']."\", 
                                                    lotno                        = \"".$detls['lot_no']."\", 
                                                    product_display_name         = \"".$detls['product_display_name']."\", 
                                                    product_model 		 = \"".$detls['product_model']."\", 
                                                    product_brand 		 = \"".$detls['product_brand']."\", 
                                                    prd_price 		         = \"".$detls['price']."\",  
                                                    product_currency             = \"".$detls['product_currency']."\", 
                                                    product_keyword              = \"".$detls['product_keywords']."\", 
                                                    product_desc                 = \"".$detls['product_desc']."\", 
                                                    prd_wt                       = \"".$detls['product_wt']."\", 
                                                    prd_img                      = \"".$detls['prd_img']."\",  
                                                    product_warranty             = \"".$detls['product_warranty']."\",
                                                    desname                      = \"".$detls['desname']."\",
                                                    active_flag                      = \"".$display_flag."\"";
                    $res = $this->query($sql);
                    
                   //----------------------------------------------For product search table---------------------------------------------------                              
                            //  For tbl_product_search
        // Few attributes remaining-- type,metal,purity,nofd,dwt,gemwt,quality,goldwt
                    if($detls['Certficate']=='Other'){$detls['Certficate']=$detls['other_cerificate'];} 
                            $sql = "INSERT 
                                    INTO 
                                                    tbl_product_search
                                                    (product_id,
                                                     color,
                                                     carat,
                                                     shape,
                                                     certified,
                                                     cut,
                                                     clarity,
                                                     base,
                                                     tabl,
                                                     price,
                                                     p_disc,
                                                     prop,
                                                     polish,
                                                     symmetry,
                                                     fluo,
                                                     td,
                                                     measurement,
                                                     cno,
                                                     pa,
                                                     cr_hgt,
                                                     cr_ang,
                                                     girdle,
                                                     pd,
                                                     type,
                                                     metal,
                                                     gold_purity,
                                                     nofd,
                                                     dwt,
                                                     gemwt,
                                                     quality,
                                                     gold_weight,
                                                     gemstone_color,
                                                     combination,
                                                     bullion_design,
                                                     rating,
                                                     date_time,
                                                     active_flag)
                                    VALUES
                                                 (\"".$pid."\",
                                                  \"".$detls['color']."\",
                                                  \"".$detls['carat_weight']."\",
                                                  \"".$shape."\",
                                                  \"".$detls['Certficate']."\",    
                                                  \"".$detls['cut']."\",
                                                  \"".$detls['clarity']."\",
                                                  \"".$detls['base_price']."\",
                                                  \"".$detls['table']."\",
                                                  \"".$detls['price']."\",
                                                  \"".$detls['discount']."\",
                                                  \"".$detls['prop']."\",
                                                  \"".$detls['polish']."\",
                                                  \"".$detls['symmetry']."\",
                                                  \"".$detls['flourecence']."\",
                                                  \"".$detls['td']."\",
                                                  \"".$detls['measurement']."\",
                                                  \"".$detls['certno']."\",
                                                  \"".$detls['pa']."\",
                                                  \"".$detls['crown_height']."\",
                                                  \"".$detls['crown_angle']."\",
                                                  \"".$detls['girdle']."\",
                                                  \"".$detls['pd']."\",
                                                  \"".$type."\",
                                                  \"".$detls['metal']."\",
                                                  \"".$detls['gold_purity']."\",
                                                  \"".$detls['no_diamonds']."\",
                                                  \"".$detls['diamonds_weight']."\",
                                                  \"".$detls['gemstone_weight']."\",
                                                  \"".$detls['quality']."\",
                                                  \"".$detls['gold_weight']."\",
                                                  \"".$detls['gemstone_color']."\",
                                                  \"".$detls['combination']."\",
                                                  \"".$detls['design']."\",
                                                  \"".$detls['rating']."\",    
                                                      now(),
                                                  \"".$display_flag."\")
                                    ON DUPLICATE KEY UPDATE
                                                            color       = \"".$detls['color']."\",
                                                            carat       = \"".$detls['carat_weight']."\",
                                                            certified   = \"".$detls['Certficate']."\",
                                                            shape       = \"".$shape."\",
                                                            cut         = \"".$detls['cut']."\",
                                                            clarity     = \"".$detls['clarity']."\",
                                                            base        = \"".$detls['base_price']."\",
                                                            tabl        = \"".$detls['table']."\",
                                                            price       = \"".$detls['price']."\",
                                                            p_disc      = \"".$detls['discount']."\",
                                                            prop        = \"".$detls['prop']."\",
                                                            polish      = \"".$detls['polish']."\",
                                                            symmetry    = \"".$detls['symmetry']."\",
                                                            fluo        = \"".$detls['flourecence']."\",
                                                            td          = \"".$detls['td']."\",
                                                            measurement = \"".$detls['measurement']."\",
                                                            cno         = \"".$detls['certno']."\",
                                                            pa          = \"".$detls['pa']."\",
                                                            cr_hgt      = \"".$detls['cr_height']."\",
                                                            cr_ang      = \"".$detls['crown_angle']."\",
                                                            girdle      = \"".$detls['girdle']."\",
                                                            pd          = \"".$detls['pd']."\",
                                                            metal       = \"".$detls['metal']."\",
                                                            type        = \"".$type."\",
                                                            gold_purity = \"".$detls['gold_purity']."\",
                                                            nofd        = \"".$detls['no_diamonds']."\",
                                                            dwt         = \"".$detls['diamonds_weight']."\",
                                                            gemwt       = \"".$detls['gemstone_weight']."\",
                                                            quality     = \"".$detls['quality']."\",
                                                            gold_weight = \"".$detls['gold_weight']."\",
                                                            combination = \"".$detls['combination']."\",
                                                            gemstone_color=\"".$detls['gemstone_color']."\",
                                                            bullion_design=\"".$detls['design']."\",    
                                                            rating      = \"".$detls['rating']."\",
                                                            active_flag      = \"".$display_flag."\"";    
                            $res = $this->query($sql);
                        
                            $vensql="  SELECT
                                                            city
                                    FROM
                                        tbl_vendor_master
                                    WHERE
                                                            vendor_id=\"".$params['vid']."\"";
                            $venres=$this->query($vensql);
                            $venrow=$this->fetchData($venres);
                            $city=$row['city'];

//------------------------------------------------------------------------------------------------------------------------
                            $vendsql="  INSERT
                                        INTO 
                                                tbl_vendor_product_mapping
                                                                            (product_id,
                                                                            vendor_id,
                                                                            vendor_price,
                                                                            vendor_quantity,
                                                                            vendor_currency,
                                                                            vendor_remarks,
                                                                            city,
                                                                            updatedby,
                                                                            date_time,
                                                                            active_flag)";
                            $vendsql.=  "VALUES
                                                                       (\"".$pid."\",
                                                                        \"".$params['vid']."\",
                                                                        \"".$detls['price']."\",
                                                                        \"".$detls['vendor_quantity']."\",
                                                                        \"".$detls['vendor_curr']."\",
                                                                        \"".$detls['vendor_remarks']."\",
                                                                        \"".$city."\",
                                                                           'vendor',
                                                                            now(),
                                                                        \"".$display_flag."\")";
                            $vendres = $this->query($vendsql);
                            
                                $arr = array('pid'=>$pid);
                                $err=array('code'=>0,'msg'=>'Product added successfully');
//------------------------------------------------------------------------------------------------------------                            
                }
                else
                {
                    $arr=array();
                    $err = array('Code' => 1, 'Msg' => 'Product Id is not found');
                }
                    $result = array('results'=>$arr, 'error' => $err);
                    return $result;
        }
		
        public function imageUpdate($params) {

        $err = array('errCode' => 0, 'errMsg' => 'Details updated successfully');
        $pid = $params['pid'];
        $img = $params['imgpath'];

        $sql = "SELECT product_image, image_sequence FROM tbl_product_image_mapping WHERE product_id = " . $pid . " AND active_flag = 1 order by image_sequence asc";
        $res = $this->query($sql);
        $cnt = $this->numRows($res);
        $flag = true;
        if ($cnt) {
            while ($row = $this->fetchData($res)) {
                if (strtolower($row['product_image']) == strtolower($img)) {
                    $err = array('errCode' => 1, 'errMsg' => 'No results updated');
                    $flag = false;
                }
                $image_sequence = $row['image_sequence'];
            }
        }
        $sequence = ($image_sequence ? $image_sequence + 1 : 1);
        if ($flag) {
            $sql = "INSERT INTO 
							tbl_product_image_mapping 
							(
								product_id,
								product_image,
								active_flag,
								image_sequence,
								update_date
							)
							VALUES
							(
								" . $pid . ",
								\"" . $img . "\",
								1,
								" . $sequence . ",
								NOW()
							)";
            $res2 = $this->query($sql);
        }

        $arr = array();
        $result = array('results' => $arr, 'error' => $err);
        return $result;
    }

    public function imageRemove($params)
		{
			$pid = $params['pid'];
			
			$sql = "SELECT product_image FROM tbl_product_image_mapping WHERE product_id = ".$pid." AND active_flag = 1";
			$res = $this->query($sql);
			$cnt = $this->numRows($res);
			
			if($res)
			{
				while($row = $this->fetchData($res))
				{
					if(stristr($row['product_image'], $params['file']) !== FALSE)
					{
						$sql = "UPDATE 
									tbl_product_image_mapping 
								SET 
									active_flag = 0 
								WHERE 
									product_id = ".$pid." 
								AND 
									product_image = \"".$row['product_image']."\"";
						$res = $this->query($sql);
						$err = array('errCode' => 0, 'errMsg' => 'Details updated successfully');
					}
					else
					{
						$err = array('errCode' => 1, 'errMsg' => 'No results updated');
					}
				}
				$arr = array();
				$result = array('results' => $arr, 'error' => $err);
				return $result;
			}
		}
		
		public function imageDisplay($params)
		{
			
			$arr = array();
			$pid = $params['pid'];
			$sql = "SELECT product_image FROM tbl_product_image_mapping WHERE product_id = ".$pid." AND active_flag = 1 ORDER BY image_sequence ASC";
			$res = $this->query($sql);
			if($res)
			{
				while($row = $this->fetchData($res))
				{
					$arr[] = $row['product_image'];
				}
			}
			
			if(!empty($arr))
			{
				$err = array('errCode' => 0, 'errMsg' => 'Details fetched successfully');
			}
			else
			{
				$err = array('errCode' => 1, 'errMsg' => 'No results found');
			}
			$result = array('results' => $arr, 'error' => $err);
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
            
            $sql = "SELECT *,MATCH(product_name) AGAINST ('".$params['prname']."*' IN BOOLEAN MODE) as startwith FROM tbl_product_master WHERE MATCH(product_name) AGAINST ('".$params['prname']."*' IN BOOLEAN MODE) and active_flag=1 ORDER BY startwith DESC";
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
			
			if($params['uid'])
			{
				$page   = ($params['page'] ? $params['page'] : 1);
				$limit  = ($params['limit'] ? $params['limit'] : 16);
				

				$sql = "SELECT 
							group_concat(pid) as pid 
						FROM 
							tbl_wishlist 
						WHERE 
							uid = ".$params['uid']." 
						AND
							wf = 1
						ORDER BY 
							date_time DESC ";
				$res = $this->query($sql);
				
				if($res)
				{
					$row = $this->fetchData($res);
					$pid = $row['pid'];
				}
					
				if($pid)
				{
					if($params['catid'])
					{
						$sql = "SELECT 
								group_concat(product_id) as pid 
							FROM 
								tbl_product_category_mapping 
							WHERE 
								category_id = ".$params['catid']." 
							AND 
								product_id in (".$pid.")
                                                        AND     
                                                                display_flag = 1
							";
						$res = $this->query($sql);
						if($res)
						{
							$row = $this->fetchData($res);
							$pid = $row['pid'];
						}
					}
					
					$sql = "SELECT 
								count(distinct product_id) as cnt 
							FROM 
								tbl_product_search 
							WHERE 
								product_id IN(".$pid.")
							AND
								active_flag=1
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
								gold_purity,
								gold_weight,
								type,
								metal
							FROM 
								tbl_product_search 
							WHERE 
								product_id IN(".$pid.")
                                                        AND            
                                                                active_flag=1    
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
					
                                        $pimgsql = "
							SELECT
                                                                product_id,
								group_concat(product_image separator '|~|') AS images
							FROM 
								tbl_product_image_mapping 
							WHERE 
								product_id IN(".$pids.")
                                                        AND
                                                                active_flag=1
                                                        GROUP BY
                                                                product_id
							ORDER BY
								field(product_id,".$pids.");
							";
					$pimgres=$this->query($pimgsql);
                                        
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
						$arr1[$pid]=$row1;
						$arr1[$pid]['attributes'] = $attr[$pid]['attributes'];
						$arr1[$pid]['images'] = $pimg[$pid]['image'];
					}

					while($row2=$this->fetchData($pimgres))
					{
						$prid = $row2['product_id'];
						$arr1[$prid]['images'] = explode('|~|',$row2['images']);
					}

                                        $tmp_arr1 = (!empty($arr1)) ? (array_values($arr1)) : null;
					$arr1 = array('filters'=>$data,'products'=>$tmp_arr1,'total'=>$total,'getdata'=>$params,'catname'=>'Wishlist');
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
			
			$sql = "select cat_name as name from tbl_category_master where catid=\"".$params['catid']."\"";
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
					and display_flag=1";
			
			
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
					AND display_flag=1";
			
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

				$city_area = $params['ctid'];
				if(!empty($city_area))
				{
					$city_area = explode('_', $city_area);
					if($city_area[1] == 'area')
					{
						$tbl_name = 'tbl_area_master';
						$colmn_nm = 'city AS cityname, latitude AS lat, longitude AS lng';
						$whr_cond = 'id';
					}
					else
					{
						$tbl_name = 'tbl_city_master';
						$colmn_nm = 'cityname';
						$whr_cond = 'cityid';
					}
				}

				if($params['ctid'])
				{
					$sqlct = "SELECT $colmn_nm  
							FROM $tbl_name
							WHERE $whr_cond = ".$city_area[0];
					$resct = $this->query($sqlct);
					if($resct)
					{
						$rowct = $this->fetchData($resct);
						$vndrIds = array();

						if($rowct)
						{
							$lat = $rowct['lat'];
							$lng = $rowct['lng'];
							$cityname = $rowct['cityname'];
							$vndrSql = "select vendor_id, city, ( 3959 * acos( cos( radians(" . $lat . ") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(" . $lng . ") ) + sin( radians(" . $lat . ") ) * sin( radians( lat ) ) ) ) as distance ";
							$vndrSql .="from tbl_vendor_master having distance > 0 and city='" . $cityname . "'";
							$vndrRes = $this->query($vndrSql);
							if($vndrRes)
							{
								while($vndrRow = $this->fetchData($vndrRes))
								{
									$vndrIds[] = $vndrRow['vendor_id'];
								}
							}
						}

						$vndrIds = implode('","', $vndrIds);

						/*if(!empty($vndrIds))
						{*/
							$sqlpct = "
									SELECT 
										product_id as pid
									FROM 
										tbl_vendor_product_mapping 
									WHERE 
										product_id in (".$allpids.")
                                                                        AND
                                                                                active_flag=1
									AND 
										vendor_id in (\"".$vndrIds."\")";

							$respct = $this->query($sqlpct);
							if($respct)
							{
								while ($rowpct = $this->fetchData($respct))
								{
									$pids[] = $rowpct['pid'];
								}
								$allpids = $pid = implode(',', $pids);
							}
						/*}
						else
						{
							$allpids = $pid = array();
						}*/
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
								active_flag=1
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
								gold_purity,
								gold_weight,
								type,
								metal,
								bullion_design
							FROM 
								tbl_product_search 
							WHERE 
								product_id IN(".$pid.")
							AND            
								active_flag=1    
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
								active_flag=1
							ORDER BY
								field(product_id,".$pid.");
							";
					$pres=$this->query($psql);
					while($row1=$this->fetchData($pres))
					{
						$pid = $row1['pid'];
						$arr1[$pid]=$row1;
						if($params['catid']==10000 || $params['catid']==10002)
						{
							$dollarSql = "SELECT dollar_rate, gold_rate, silver_rate FROM `tbl_vendor_master` where vendor_id=(SELECT vendor_id FROM `tbl_vendor_product_mapping` where active_flag=1 AND product_id='".$pid."')";
							$dollarRes=$this->query($dollarSql);
							$dollarRow=$this->fetchData($dollarRes);
							$arr1[$pid]['dollar_rate']=$dollarRow['dollar_rate'];
							$arr1[$pid]['gold_rate']=$dollarRow['gold_rate'];
							$arr1[$pid]['silver_rate']=$dollarRow['silver_rate'];
						}
						$arr1[$pid]['attributes'] = $attr[$pid]['attributes'];
						$arr1[$pid]['images'] = $pimg[$row1['pid']]['images'];
					}

					$pimgsql = "
							SELECT
								product_id,
								group_concat(product_image separator '|~|') AS images
							FROM 
								tbl_product_image_mapping 
							WHERE 
								product_id IN(".$pids.")
							AND
								active_flag=1
							GROUP BY
								product_id
							ORDER BY
								field(product_id,".$pids.");
							";
					$pimgres=$this->query($pimgsql);
					while($row2=$this->fetchData($pimgres))
					{
						$prid                  = $row2['product_id'];
						$arr1[$prid]['images'] = explode('|~|',$row2['images']);
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
						while($row = $this->fetchData($res))
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
                                                                                                active_flag=1
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
                                                                                                active_flag=1
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
					$arr1 = array('filters'=>$data,'products'=>$tmp_arr1,'total'=>$total,'getdata'=>$params,'catname'=>$catname);
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
        
        public function getPrdById($params)
        {
          $page   = ($params['page'] ? $params['page'] : 1);
          $limit  = ($params['limit'] ? $params['limit'] : 15);
            
            $sql = "SELECT * FROM tbl_product_master WHERE product_id=".$params['prdid']." AND active_flag!=2";
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
                                                dwt,
						fluo as fluorescence,
						measurement,
						td as tab,
                                                gold_weight,
                                                gemstone_color,
                                                quality,
						cr_ang as crownangle,
						girdle,
						base as baseprice,
                   				p_disc as discount,
                                                type,
                                                bullion_design
                    FROM 
                        tbl_product_search
                    WHERE 
                        product_id=".$params['prdid']." 
                    AND        
                        active_flag!=2";
                        
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
                        AND                
                                    active_flag!=2
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
                                bdbc as diamond_certificate,
                                other_dbaw as other_Certificate,
                                vatno as Vat_Number,                            
                                landline,
                                mdbw as membership_around_world,                            
                                website,
                                banker as bankers,                            
                                pancard,
                                turnover,
                                lat as latitude,
                                lng as longitude,
                                dollar_rate,
                                gold_rate,
                                silver_rate,
								area,
								city,
								state
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
                    $details['dollar_rate']=$row4['dollar_rate'];
                    $details['silver_rate']=$row4['silver_rate'];
                    $details['gold_rate']=$row4['gold_rate'];
                }

				if(!empty($params['catid']))
				{
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
				}
				else
				{
					$reslt1['category_name'] = '';
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
                   
                
                        $sql5="SELECT category_id
                             FROM 
								tbl_product_category_mapping
                             WHERE 
								product_id =".$params['prdid']." AND display_flag!=2 AND category_id!=".$params['catid']."";
                        $res5=$this->query($sql5);
                        if ($res5) {
                            while ($row5 = $this->fetchData($res5)) {
                                $reslt[$pid]['catid'][] = $row5['category_id'];
                            }
                        }
                        
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
            $cnt_sql = "SELECT COUNT(1) as cnt FROM tbl_product_master AND active_flag=1";
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
                    WHERE
                            active_flag=1
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
                       AND             
                                active_flag=1    
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
        
     /*   public function productByCity($params)
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
       */ 
        
        public function productByCity($params)
        {
            $page   = ($params['page'] ? $params['page'] : 1);
            $limit  = ($params['limit'] ? $params['limit'] : 15);
            
            $sql="SELECT 
                                product_id,
                                vendor_id,
                                vendor_price,
                                vendor_quantity,
                                vendor_currency,
                                vendor_remarks
                  FROM  
                                tbl_vendor_product_mapping
                  WHERE 
                                city=\"".$params['cityname']."\"
                  AND
                                active_flag=1
                  ORDER BY
                                product_id ASC"; 
            
             if(!empty($page)) 
            {
                $start = ($page * $limit) - $limit;
                $sql.=" LIMIT " . $start . ",$limit";
            }
            $chkres=$this->query($sql);
            $cnt_res2 = $this->numRows($chkres);
            
            
             
            
            if($cnt_res2>0)
            {   $j=0;
                while($row2=$this->fetchData($chkres))
                {           
                    $arr1['product_id'][$j]= $row2['product_id'];
                    $arr3[$j]=$row2;
                    $j++;
                    $vid[]=$row2['vendor_id'];
                }
                
                
                $venid=implode(',',$vid);
                
                
                $sql6="SELECT 
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
                                bdbc as diamond_certificate,
                                other_bdbc as other_Certificate,
                                vatno as Vat_Number,                            
                                landline,
                                mdbw as membership_around_world,                            
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
                
                $res6=$this->query($sql6);
                
                while($row6=$this->fetchData($res6))
                {
                    $vid[]=$row6['vid'];
                    $vdetls[$row6['vid']]=$row6;
                }
                
                
                $pid=implode(',',$arr1['product_id']);
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
                                    product_id IN(".$pid.")
                            AND            
                                    active_flag=1";
                
                $chkres=$this->query($fillpiddet);
                $cnt_res3 = $this->numRows($chkres);
                if($cnt_res3>0)
                {
                    while($row3=$this->fetchData($chkres))
                    {   

                        $arr4= $row3;
                    }
                    
                    if(!empty($params['catid']))
                    {
                        $sql="SELECT
                                                attribute_id,
                                                attr_values,
                                                attr_filter_flag,
                                                attr_filter_position,
                                                attr_display_position,
                                                attr_range
                            FROM 
                                                tbl_attribute_category_mapping
                            WHERE
                                                category_id=".$params['catid']."";
                        $res=$this->query($sql);
                        
                        if($res)
                        {
                            $i=0;
                            while($row4=$this->fetchData($res))
                            {
                                $atr1['aid'][]=$row4['attribute_id'];
                                $atr['filter_flag']=$row4['attr_filter_flag'];
                                $atr['display_position']=$row4['attr_display_position'];
                                $atr['attribute_values']=$row4['attr_values'];
                                $atr['filter_position']=$row4['attr_filter_position'];
                                $fil[$row4['attribute_id']]=$atr;
                                $i++;
                            }
                            
                            $aid=implode(',',$atr1['aid']);
                            
                            $sql2="SELECT
                                                attr_display_name 
                                   FROM 
                                                tbl_attribute_master
                                   WHERE attr_id IN(".$aid.") ORDER BY field(attr_id,".$aid.")";
                            
                            $res2=$this->query($sql2);
                            $j=0;
                            while($row5=$this->fetchData($res2))
                            {
                            $aname['attributes'][]=$row5['attr_display_name'];
                            $j++;
                            
                            }

                            $aname=implode(',',$aname['attributes']);
                            
                            $prdattrs="SELECT product_id,";
                            $prdattrs.=$aname." FROM tbl_product_search WHERE product_id IN(".$pid.") AND active_flag=1 ORDER BY product_id ASC";
                        
                            $finalres=$this->query($prdattrs);
                            
                            if($finalres)
                            {$i=0;
                                while($rows=$this->fetchData($finalres))
                                {
                                    $arr['attributes']=$aname;
                                    $arr['filters']=$fil;
                                    $prid=$rows['product_id'];
                                    
                                    $arr[$prid]=$arr4;
                                    $arr[$prid]['attr_details']=$rows;
                                    $arr[$prid]['vendor_product_details']=$arr3[$i];
                                    $arr[$prid]['vendor_details']=$vdetls;
                                $i++;
                                }
                            }  
                        }

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
            $result=array('results'=>$arr,'error'=>$err);
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
                            product_brand='".$params['bname']."'
                      AND
                            active_flag=1";
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
                                    product_id IN(".$pid.")
                              AND
                                    active_flag=1";
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

		public function sendDetailsToUser($params)
		{
			$usrEmail = (!empty($params['usrEmail'])) ? trim(urldecode($params['usrEmail'])) : '';
			$usrMobile = (!empty($params['usrMobile'])) ? trim(urldecode($params['usrMobile'])) : '';
			$usrName = (!empty($params['usrName'])) ? trim(urldecode($params['usrName'])) : '';
			$prdid = (!empty($params['prdid'])) ? trim(urldecode($params['prdid'])) : '';
			$vid = (!empty($params['vid'])) ? trim(urldecode($params['vid'])) : '';
			$uid = (!empty($params['uid'])) ? trim(urldecode($params['uid'])) : '';

			if(empty($usrEmail) || empty($usrMobile) || empty($usrName) || empty($prdid))
			{
				$resp = array();
				$error = array('Code' => 1, 'Msg' => 'Some parameters are missing');
				$results = array('results' => $resp, 'error' => $error);
				return $results;
			}

			$tmp_params = array('prdid' => $prdid);
			$prdDetails = $this->getPrdById($tmp_params);
			$prdRes = $prdDetails['results'][$prdid];

			$vndrId = '';
			foreach($prdRes as $key => $value)
			{
				if(is_array($value) && $key == 'vendor_details')
				{
					foreach($value as $ky => $vl)
					{
						$vndrName = $vl['OrganisationName'];
						$vndrLL = $vl['telephones'];
						$vndrAltEmail = $vl['alt_email'];
						$vndrCity = $vl['office_city'];
						$vndrDesig = $vl['position'];
						$vndrMobile = $vl['contact_mobile'];
						$vndrEmail = $vl['email'];
						$vndrWebsite = $vl['website'];
					}
				}

				if(is_array($value) && $key == 'vendor_product_details')
				{
					foreach($value as $ky => $vl)
					{
						$prdPrice = $vl['vendor_price'];
					}
				}

				if(is_array($value) && $key == 'attr_details')
				{
					foreach($value as $ky => $vl)
					{
						if($ky == 'carat')
						{
							$prdCarat = $value['carat'];
						}
						else if($ky == 'color')
						{
							$prdColor = $value['color'];
						}
						else if($ky == 'shape')
						{
							$prdShape = $value['shape'];
						}
						else if($ky == 'polish')
						{
							$prdPolish = $value['polish'];
						}
						else if($ky == 'certified')
						{
							$prdCert = $value['certified'];
						}
					}
				}

				$prdName = $value['product_display_name'];
			}

			$emailContent = "Dear $usrName,";
			$emailContent .= "<br/><br/>";
			if(!empty($prdName))
			{
				$emailContent .= "You have enquired for $prdName, please find below the details of Product";
			}
			else
			{
				$emailContent .= "You have enquired for a product, please find below the details";
			}
			$emailContent .= "<br/><br/>";

			$emailContent .= "<table border='2'>";
				$emailContent .= "<tr>";
					$emailContent .= "<th>";
						$emailContent .= "Product Id";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Price";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Carats";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Color";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Shape";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Polish";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Certificate";
					$emailContent .= "</th>";
				$emailContent .= "</tr>";
				$emailContent .= "<td>";
					$emailContent .= $prdid;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $prdPrice;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $prdCarat;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $prdColor;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $prdShape;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $prdPolish;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $prdCert;
				$emailContent .= "</td>";
			$emailContent .= "</table>";

			$emailContent .= "<br/><br/>";

			$emailContent .= "Regards,";
			$emailContent .= "<br/>";
			$emailContent .= "IFtoSI Team";

			$mailHeaders = "Content-type:text/html;charset=UTF-8" . "\r\n";
			$mailHeaders .= "From: noreply@xelpmoc.in \r\n";

			if(mail($usrEmail, 'Product Details', $emailContent, $mailHeaders))
			{
				$resp = $prdRes;
				$error = array('Code' => 0, 'Msg' => 'SMS / Email sent');
			}
			else
			{
				$resp = array();
				$error = array('Code' => 1, 'Msg' => 'Error sending SMS / Email');
			}

			$results = array('results' => $resp, 'error' => $error);
			return $results;
		}
    }
?>