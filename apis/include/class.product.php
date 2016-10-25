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
            $vendSql = "SELECT
                                *
                        FROM
                                tbl_vendor_master
                        WHERE
                                vendor_id = ".$params['vid'];
            $vendres = $this->query($vendSql);
           // echo'<pre>';print_r($params);die;
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

            if(!empty($detls['prdcprice']))
            {
                $detls['prdPrice'] = $detls['prdcprice'];
            }

            if(($detls['shape']=='gBars') || ($detls['shape']=='sBars') || ($detls['shape']=='gCoins') || ($detls['shape']=='sCoins') || ($detls['shape']=='pBars') || ($detls['shape']=='pCoins'))
            {
                $type   = substr($detls['shape'],1,-1);
                $temp   = $detls['shape'];
                $shape  = $detls['shape'];

                if(($temp=='gBars')||($temp=='gCoins'))
                {
                    $detls['metal'] = 'Gold';
                }

                else if(($temp=='sBars')||($temp=='sCoins'))
                {
                    $detls['metal'] = 'Silver';
                }

                else if(($temp=='pBars')||($temp=='pCoins'))
                {
                    $detls['metal'] = 'Platinum';
                }

                $catname  = substr($detls['shape'],1);
                $catname  = $catname;
                $catidsql="SELECT
                                    catid
                           FROM
                                    tbl_category_master
                           WHERE
                                    cat_name='".$catname."'";
                $catidres=$this->query($catidsql);
                $catidrow=$this->fetchData($catidres);
                $catids1=$catidrow['catid'];
            }
            else
            {
                $shape    = $detls['shape'];
                $catids1  = $detls['subcatid'];
            }

            $maincatsql="SELECT
                                  p_catid
                         FROM
                                  tbl_category_master
                         WHERE
                                  catid IN(\"".$catids1."\")";
            $maincatres=$this->query($maincatsql);
            $maincatrow=$this->fetchData($maincatres);

            $temp    = count($catids);
            $catids1.=','.$maincatrow['p_catid'];
            $catids1.=','.$params['category_id'];

            $catids = explode(',',$catids1);

            $detls['measurement']=$detls['measurement1'].'*'.$detls['measurement2'].'*'.$detls['measurement3'];

                    $sql = "INSERT
                            INTO
                                         tbl_brandid_generator
                                         (name,
                                          category_id,
                                          date_time,
                                          aflg)
                            VALUES
                                      (\"".$detls['brand']."\",
                                       \"".$params['category_id']."\",
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
                        $row  = $this->fetchData($res);
                        $pid  = $row['product_id'];
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
                           $chkdes = $this->query($chksql);
                           $cntres = $this->numRows($chkdes);
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
                               $did = $row['designer_id'];
                               //   designer product table value updating if product already present
                                $dessql="UPDATE
                                                  tbl_designer_product_mapping
                                        SET
                                                  desname=\"".$detls['desname']."\"
                                        WHERE
                                                  designer_id=\"".$did."\"";
                                $desres = $this->query($dessql);

                            }
                        //  For category product mapping
                           $catids = explode(',',$catids1);
                           foreach($catids as $key=>$val)
                           {
                               if(!empty($val))
                               {
                                    $catidies[] = trim($val);
                               }
                            }
                            if(!empty($catidies))
                            {
                                $catids1 = implode('","', array_unique($catidies));
                            }
                            $updtcatsql="UPDATE
                                                  tbl_product_category_mapping
                                         SET
                                                  display_flag=0
                                         WHERE
                                                  category_id NOT IN(\"".$catids1."\")
                                         AND
                                                  product_id=\"".$pid."\"";

                            $updtcatres = $this->query($updtcatsql);
                            $detls['discount'] = str_replace('-', '', $detls['discount']);

                            for($i=0;$i<count($catidies);$i++)
                            {
                                if(!empty($catidies[$i]))
                                {
                                    $detls['rating'] = 0.00;
                                    if(empty($detls['priceb2b']))
                                    {
                                        $detls['priceb2b'] = 0.00;
                                    }
                                    $pcsql="INSERT
                                            INTO
                                                    tbl_product_category_mapping
                                                   (product_id,
                                                    category_id,
                                                    price,
                                                    rating,
                                                    b2bprice,
                                                    display_flag,
                                                    date_time)
                                            VALUES
                                               (\"".$pid."\",
                                                \"".$catidies[$i]."\",
                                                \"".$detls['price']."\",
                                                \"".$detls['rating']."\",
                                                \"".$detls['priceb2b']."\",
                                                \"".$display_flag."\",
                                                    now())
                                    ON DUPLICATE KEY UPDATE
                                            category_id             = \"".$catidies[$i]."\",
                                            price                   = \"".$detls['price']."\",
                                            rating                  = \"".$detls['rating']."\",
                                            b2bprice                = \"".$detls['priceb2b']."\",
                                            display_flag            = \"".$display_flag."\"";
                                    $pcres=$this->query($pcsql);
                               }
                            }
                            if(empty($detls['lot_no']))
                            {
                                $detls['lot_no'] = 0;
                            }
                            if(empty($detls['product_wt']))
                            {
                                $detls['product_wt'] = 0.00;
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
                                            b2bprice,
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
                                         \"".$detls['priceb2b']."\",
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
                                            product_model 	             = \"".$detls['product_model']."\",
                                            product_brand            		 = \"".$detls['product_brand']."\",
                                            prd_price        		         = \"".$detls['price']."\",
                                            b2bprice                     = \"".$detls['priceb2b']."\",
                                            product_currency             = \"".$detls['product_currency']."\",
                                            product_keyword              = \"".$detls['product_keywords']."\",
                                            product_desc                 = \"".$detls['product_desc']."\",
                                            prd_wt                       = \"".$detls['product_wt']."\",
                                            prd_img                      = \"".$detls['prd_img']."\",
                                            product_warranty             = \"".$detls['product_warranty']."\",
                                            desname                      = \"".$detls['desname']."\",
                                            active_flag                  = \"".$display_flag."\"";

                          $res = $this->query($sql);
    //----------------------------------------------For product search table For tbl_product_search --------------------

                        // Few attributes remaining-- type,metal,purity,nofd,dwt,gemwt,quality,goldwt

                          if($detls['Certficate'] == 'Other')
                          {
                              $detls['other_certificate'] = $detls['other_certificate'];
                          }
                          if($detls['design'] == 'Other')
                          {
                              $detls['design'] = $detls['bullion_design'];
                          }
                          if($shape == 'sCoins' || $shape == 'sBars')
                          {
                              if(!empty($detls['silver_purity']))
                              {
                                  $detls['gold_purity'] = $detls['silver_purity'];
                              }
                              if(!empty($detls['silver_weight']))
                              {
                                  $detls['gold_weight'] = $detls['silver_weight'];
                              }
                          }
                          if($shape == 'pCoins' || $shape == 'pBars')
                          {
                              if(!empty($detls['platinumpurity']))
                              {
                                  $detls['gold_purity'] = $detls['platinumpurity'];
                              }
                              if(!empty($detls['platinumweight']))
                              {
                                  $detls['gold_weight'] = $detls['platinumweight'];
                              }
                          }
                          if(!empty($detls['color']))
                          {
                              $detls['color'] = str_replace(' ','-',$detls['color']);
                          }
                          if(!empty($detls['clarity']))
                          {
                              $detls['clarity'] = str_replace(' ','-',$detls['clarity']);
                          }


                          if(empty($detls['carat_weight']))
                          {
                              $detls['carat_weight'] = 0.00;
                          }
                          if(empty($detls['base_price']))
                          {
                              $detls['base_price'] = 0;
                          }
                          if(empty($detls['base_price']))
                          {
                              $detls['table'] = 0.00;
                          }
                          if(empty($detls['discount']))
                          {
                              $detls['discount'] = 0;
                          }
                          if(empty($detls['discountb2b']))
                          {
                              $detls['discountb2b'] = 0;
                          }
                          if(empty($detls['td']))
                          {
                              $detls['td'] = 0.00;
                          }
                          if(empty($detls['pa']))
                          {
                              $detls['pa'] = 0.00;
                          }
                          if(empty($detls['crown_height']))
                          {
                              $detls['crown_height'] = 0.00;
                          }
                          if(empty($detls['crown_angle']))
                          {
                              $detls['crown_angle'] = 0;
                          }
                          if(empty($detls['girdle']))
                          {
                              $detls['girdle'] = '';
                          }
                          if(empty($detls['pd']))
                          {
                              $detls['pd'] = 0.00;
                          }
                          if(empty($detls['no_diamonds']))
                          {
                              $detls['no_diamonds'] = 0;
                          }
                          if(empty($detls['diamonds_weight']))
                          {
                              $detls['diamonds_weight'] = 0;
                          }
                          if(empty($detls['gemstone_weight']))
                          {
                              $detls['gemstone_weight'] = 0;
                          }
                          if(empty($detls['num_gemstones']))
                          {
                              $detls['num_gemstones'] = 0;
                          }
                          if(empty($detls['polki_weight']))
                          {
                              $detls['polki_weight'] = 0;
                          }
                          if(empty($detls['polkino']))
                          {
                              $detls['polkino'] = 0;
                          }
                          if(empty($detls['polki_price_per_carat']))
                          {
                              $detls['polki_price_per_carat'] = 0;
                          }
                          if(empty($detls['polki_value']))
                          {
                              $detls['polki_value'] = 0;
                          }

                          /* added new */
                           if(empty($detls['baguette_weight']))
                          {
                              $detls['baguette_weight'] = 0;
                          }
                          if(empty($detls['baguetteno']))
                          {
                              $detls['baguetteno'] = 0;
                          }
                          if(empty($detls['baguette_price_per_carat']))
                          {
                              $detls['baguette_price_per_carat'] = 0;
                          }
                          if(empty($detls['baguette_value']))
                          {
                              $detls['baguette_value'] = 0;
                          }
                          /////////////////************************///////////////////



                          if(empty($detls['cr_height']))
                          {
                              $detls['cr_height'] = 0;
                          }


                              $sql = "  INSERT
                                        INTO
                                                tbl_product_search
                                                (product_id,
                                                 diamond_shape,
                                                 color,
                                                 carat,
                                                 shape,
                                                 certified,
                                                 other_certificate,
                                                 cut,
                                                 clarity,
                                                 base,
                                                 tabl,
                                                 price,
                                                 p_disc,
                                                 p_discb2b,
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
                                                 num_gemstones,
                                                 gemstone_type,
                                                 combination,
                                                 bullion_design,
                                                 rating,
                                                 budget,


                                        baguette_color,
                                        baguette_quality,
                                        baguette_weight,
                                        baguette_no,
                                        baguette_price_per_carat,
                                        baguette_value,

                                                 b2b_price,
                                                 isBugget,
                                                 price_per_carat,
                                                 othermaterial,
                                                 labour_charge,
                                                 grossweight,
                                                 gprice_per_carat,
                                                 diamondsvalue,
                                                 gemstonevalue,
                                                 gold_value,
                                                 polki_color,
                                                 polki_quality,
                                                 polki_weight,
                                                 polkino,
                                                 polki_price_per_carat,
                                                 polki_value,
                                                 gold_type,
                                                 active_flag)
                                          VALUES
                                             (\"".$pid."\",
                                              \"".$detls['diamondShape']."\",
                                              \"".$detls['color']."\",
                                              \"".$detls['carat_weight']."\",
                                              \"".$shape."\",
                                              \"".$detls['Certficate']."\",
                                              \"".$detls['other_certificate']."\",
                                              \"".$detls['cut']."\",
                                              \"".$detls['clarity']."\",
                                              \"".$detls['base_price']."\",
                                              \"".$detls['table']."\",
                                              \"".$detls['price']."\",
                                              \"".$detls['discount']."\",
                                              \"".$detls['discountb2b']."\",
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
                                              \"".$detls['num_gemstones']."\",
                                              \"".$detls['gemstone_type']."\",
                                              \"".$detls['combination']."\",
                                              \"".$detls['design']."\",
                                              \"".$detls['rating']."\",
                                              \"".$detls['price']."\",

                                    \"".$detls['baguette_color']."\",
                                    \"".$detls['baguette_quality']."\",
                                    \"".$detls['baguette_weight']."\",
                                    \"".$detls['baguetteno']."\",
                                    \"".$detls['baguette_price_per_carat']."\",
                                    \"".$detls['baguette_value']."\",

                                              \"".$detls['priceb2b']."\",
                                              \"".$detls['isBugget']."\",
                                              \"".$detls['price_per_carat']."\",
                                              \"".$detls['othermaterial']."\",
                                              \"".$detls['labour_charge']."\",
                                              \"".$detls['grossweight']."\",
                                              \"".$detls['gprice_per_carat']."\",
                                              \"".$detls['diamondsvalue']."\",
                                              \"".$detls['gemstonevalue']."\",
                                              \"".$detls['gold_value']."\",
                                              \"".$detls['polki_color']."\",
                                              \"".$detls['polki_quality']."\",
                                              \"".$detls['polki_weight']."\",
                                              \"".$detls['polkino']."\",
                                              \"".$detls['polki_price_per_carat']."\",
                                              \"".$detls['polki_value']."\",
                                              \"".$detls['gold_type']."\",
                                              \"".$display_flag."\")
                                          ON DUPLICATE KEY UPDATE
                                                diamond_shape           = \"".$detls['diamondShape']."\",
                                                color                   = \"".$detls['color']."\",
                                                carat                   = \"".$detls['carat_weight']."\",
                                                certified               = \"".$detls['Certficate']."\",
                                                other_certificate       = \"".$detls['other_certificate']."\",
                                                shape                   = \"".$shape."\",
                                                cut                     = \"".$detls['cut']."\",
                                                clarity                 = \"".$detls['clarity']."\",
                                                base                    = \"".$detls['base_price']."\",
                                                tabl                    = \"".$detls['table']."\",
                                                price                   = \"".$detls['price']."\",
                                                p_disc                  = \"".$detls['discount']."\",
                                                p_discb2b               = \"".$detls['discountb2b']."\",
                                                prop                    = \"".$detls['prop']."\",
                                                polish                  = \"".$detls['polish']."\",
                                                symmetry                = \"".$detls['symmetry']."\",
                                                fluo                    = \"".$detls['flourecence']."\",
                                                td                      = \"".$detls['td']."\",
                                                measurement             = \"".$detls['measurement']."\",
                                                cno                     = \"".$detls['certno']."\",
                                                pa                      = \"".$detls['pa']."\",
                                                cr_hgt                  = \"".$detls['cr_height']."\",
                                                cr_ang                  = \"".$detls['crown_angle']."\",
                                                girdle                  = \"".$detls['girdle']."\",
                                                pd                      = \"".$detls['pd']."\",
                                                metal                   = \"".$detls['metal']."\",
                                                type                    = \"".$type."\",
                                                gold_purity             = \"".$detls['gold_purity']."\",
                                                nofd                    = \"".$detls['no_diamonds']."\",
                                                dwt                     = \"".$detls['diamonds_weight']."\",
                                                gemwt                   = \"".$detls['gemstone_weight']."\",
                                                quality                 = \"".$detls['quality']."\",
                                                gold_weight             = \"".$detls['gold_weight']."\",
                                                combination             = \"".$detls['combination']."\",
                                                gemstone_color          = \"".$detls['gemstone_color']."\",
                                                num_gemstones           = \"".$detls['num_gemstones']."\",
                                                gemstone_type           = \"".$detls['gemstone_type']."\",
                                                bullion_design          = \"".$detls['design']."\",
                                                rating                  = \"".$detls['rating']."\",
                                                budget                  = \"".$detls['price']."\",

                                        baguette_color                  = \"".$detls['baguette_color']."\",
                                        baguette_quality                = \"".$detls['baguette_quality']."\",
                                        baguette_weight                 = \"".$detls['baguette_weight']."\",
                                        baguette_no                     = \"".$detls['baguette_no']."\",
                                        baguette_price_per_carat        = \"".$detls['baguette_price_per_carat']."\",
                                        baguette_value                  = \"".$detls['baguette_value']."\",

                                                b2b_price               = \"".$detls['priceb2b']."\",
                                                isBugget                = \"".$detls['isBugget']."\",
                                                price_per_carat         = \"".$detls['price_per_carat']."\",
                                                othermaterial           = \"".$detls['othermaterial']."\",
                                                labour_charge           = \"".$detls['labour_charge']."\",
                                                grossweight             = \"".$detls['grossweight']."\",
                                                gprice_per_carat        = \"".$detls['gprice_per_carat']."\",
                                                diamondsvalue           = \"".$detls['diamondsvalue']."\",
                                                gemstonevalue           = \"".$detls['gemstonevalue']."\",
                                                gold_value              = \"".$detls['gold_value']."\",
                                                polki_color             = \"".$detls['polki_color']."\",
                                                polki_quality           = \"".$detls['polki_quality']."\",
                                                polki_weight            = \"".$detls['polki_weight']."\",
                                                polkino                 = \"".$detls['polkino']."\",
                                                polki_price_per_carat   = \"".$detls['polki_price_per_carat']."\",
                                                polki_value             = \"".$detls['polki_value']."\",
                                                gold_type               = \"".$detls['gold_type']."\",
                                                complete_flag           = 1,
                                                active_flag             = \"".$display_flag."\"";
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
                                $vendsql="  INSERT
                                            INTO
                                                        tbl_vendor_product_mapping
                                                        (product_id,
                                                        vendor_id,
                                                        vendor_price,
                                                        b2bprice,
                                                        vendor_quantity,
                                                        vendor_currency,
                                                        vendor_remarks,
                                                        city,
                                                        updatedby,
                                                        date_time,
                                                        active_flag)
                                           VALUES
                                                   (\"".$pid."\",
                                                    \"".$params['vid']."\",
                                                    \"".$detls['price']."\",
                                                    \"".$detls['priceb2b']."\",
                                                    \"".$detls['vendor_quantity']."\",
                                                    \"".$detls['vendor_curr']."\",
                                                    \"".$detls['vendor_remarks']."\",
                                                    \"".$city."\",
                                                       'vendor',
                                                        now(),
                                                    \"".$display_flag."\")
                                            ON DUPLICATE KEY UPDATE
                                                    vendor_price = \"".$detls['price']."\",
                                                    b2bprice     = \"".$detls['priceb2b']."\"";

                                $vendres = $this->query($vendsql);

                                    $arr = array('pid'=>$pid);
                                    $err=array('code'=>0,'msg'=>'Product added successfully');
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

        $sql = "SELECT product_image, image_sequence FROM tbl_product_image_mapping WHERE product_id = " . $pid . " AND active_flag = 0 order by image_sequence asc";
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
								upload_date,
                                                                update_date
                                                        )
							VALUES
							(
								" . $pid . ",
								\"" . $img . "\",
								0,
								" . $sequence . ",
								NOW(),
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

			$sql = "SELECT product_image FROM tbl_product_image_mapping WHERE product_id = ".$pid." AND active_flag in (0, 1)";
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
									active_flag = 2
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
		{//print_r($params);die;
			$af = (!empty($params['af'])) ? trim(urldecode($params['af'])) : 1;
                        $vid = (!empty($params['vid'])) ? trim(urldecode($params['vid'])) : '';
                        $status = $params['status'];
			$arr = array();
			$pid = $params['pid'];
                        $prdSql = " SELECT
                                            vendor_id
                                    FROM
                                            tbl_vendor_product_mapping
                                    WHERE
                                            product_id = ".$pid."";
                        $prdRes = $this->query($prdSql);
                        $Vcnt = $this->numRows($prdRes);
                          $extn = " AND active_flag in (".$af.") ";
                        if($Vcnt>0)
                        {
                            $row = $this->fetchData($prdRes);
                            if($row['vendor_id'] == $vid)
                            {    
                                $extn = " AND active_flag not in (2) ";
                            }
                        }
                     


                        $sql = "SELECT product_image,active_flag FROM tbl_product_image_mapping WHERE product_id = ".$pid." ".$extn." ORDER BY image_sequence ASC";
                        $res = $this->query($sql);
                        if($res)
                        {       if($status==2)
                                {  
                                    while($row = $this->fetchData($res))
                                    {
                                        
                                        
                                           $temp['status'] = $row['active_flag'];
                                           $temp['product_image']=$row['product_image'];
                                           $arr[] = $temp;
                                    }
                                }
                                else 
                                {
                                    while($row = $this->fetchData($res))
                                    {
                                        $arr[] = $row['product_image'];
                                    }
     
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
                                      display_flag = 1";
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
      								          active_flag=1";
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
                                    b2b_price as b2bprice,
                    								cno,
                    								gold_purity,
                    								CEIL(gold_weight) AS gold_weight,
                    								type,
                    								metal
      						        FROM
      								              tbl_product_search
      							      WHERE
      								              product_id IN(".$pid.")
                          AND
                                    active_flag=1
                          AND
                                    complete_flag=1
      							                ".$extn."
      							      ORDER BY
      								              field(product_id,".$pid.")";

      					if (!empty($page))
      					{
        						$start = ($page * $limit) - $limit;
        						$patsql.=" LIMIT " . $start . ",$limit";
      					}

      					$patres=$this->query($patsql);
      					$i=0;
      					while($row2=$this->fetchData($patres))
      					{
                    $prodid[] = $row2['product_id'];
                    $pid = $row2['product_id'];
                    unset($row2['product_id']);
                    $attr[$pid]['attributes']=$row2;

                    if(!empty($prodid))
                    {
                        $pid = $pids = implode(',',$prodid);
                    }
                    else
                    {
                        $pid = $pids = '';
                    }

                    $sqlV = "SELECT
                                      DISTINCT(vendor_id)
                             FROM
                                      tbl_vendor_product_mapping
                             WHERE
                                      product_id = \"".$prodid[$i]."\"
                             AND
                                      active_flag=1";

                    $resV = $this->query($sqlV);
                    $resCnt = $this->numRows($resV);

                    if($resV > 0)
                    {
                        while($rowV = $this->fetchData($resV))
                        {
                            $Vrate = "SELECT
                                              vendor_id,
                                              dollar_rate,
                                              silver_rate,
                                              gold_rate
                                      FROM
                                              tbl_vendor_master
                                      WHERE
                                              vendor_id = ".$rowV['vendor_id']."
                                      AND
                                              active_flag=1";
                            $Vres = $this->query($Vrate);
                            $vratecnt = $this->numRows($Vres);
                            if($vratecnt > 0)
                            {
                                while($rateV = $this->fetchData($Vres))
                                {
                                    $rates[$prodid[$i]]= $rateV;
                                }
                            }
                        }
                    }
                    $i++;
                }

                $pimgsql = "SELECT
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
      								              field(product_id,".$pids.")";
      					$pimgres=$this->query($pimgsql);

      					$psql = "SELECT
                								product_id AS pid,
                								barcode AS pcode,
                								product_name AS pname,
                								product_display_name AS pdname,
                								product_model AS pmodel,
                								product_brand AS pbrand,
                								prd_price AS pprice,
                                b2bprice,
                								product_currency AS pcur,
                								prd_img AS pimg
      							    FROM
      								          tbl_product_master
      							    WHERE
      								          product_id IN(".$pid.")
                        AND
                                active_flag=1
      							    ORDER BY
      								          field(product_id,".$pid.")";
      					$pres=$this->query($psql);
      					while($row1=$this->fetchData($pres))
      					{
        						$pid = $row1['pid'];
        						$arr1[$pid]=$row1;
        						$arr1[$pid]['attributes'] = $attr[$pid]['attributes'];
                    $arr1[$pid]['images'] = $pimg[$pid]['image'];
                    $arr1[$pid]['vdetail'] = $rates[$pid];
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

      			$sql = "SELECT
                              cat_name as name
                    FROM
                              tbl_category_master
                    WHERE
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
                                            product_id AS pid,
                                            (SELECT complete_flag FROM tbl_product_search WHERE product_id = pid) AS complete_status
      		                FROM
      				            tbl_product_category_mapping
      					            ".$where."
                    AND
                            display_flag=1
                    HAVING
                            complete_status=1";
      			$res = $this->query($sql);
      			if($res)
      			{
        				$total = $this->numRows($res);
      			}

      			$sql = "SELECT
      						          *,
                            product_id as pid,
      						        price,
                                                        b2bprice,
                            (SELECT complete_flag FROM tbl_product_search WHERE product_id = pid) AS complete_status
      					    FROM
      						          tbl_product_category_mapping
      					    ".$where."
      					    AND
                            display_flag=1
                    HAVING
                            complete_status=1";
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
                                                $extnhv = '';
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
              							$expCarat = explode('|~|',$sarr[0]);
                                                                $exd1 = explode(';',$expCarat[1]);

                                                                    if($params['b2bsort'])
                                                                       $extnhv = " HAVING b2b_dollar_price between ".$exd[0]." AND ".$exd[1]." ";
                                                                    else
                                                                       $extnhv = " HAVING dollar_price between ".$exd[0]." AND ".$exd[1]."";
          						}
                                                        else
                                                                 $extn .= " AND ".str_replace('Range','',$expd[0])." between ".$exd[0]." AND ".$exd[1]." ";
        					}
      				}

      				if(!empty($params['clist']))
      				{
        					$sarr = explode('|$|',$params['clist']);
        					foreach($sarr as $key => $val)
        					{
          						$expd = explode('|~|',$val);
          						$exd = explode('|@|',$expd[1]);
                      if(stristr($exd[0],'combination'))
                      {
                          $gig = str_replace('00','&',$expd[1]);
                          $gig = str_replace('11',',',$gig);
                          $gig = str_replace('_',' ',$gig);
                          $gig = str_replace('combination ',' ',$gig);
                          $gig = str_replace(',',' ',$gig);
                          $gig = str_replace(' ','* ',trim(preg_replace('!\s+!', ' ',str_replace('&','',$gig))));
                          $exd = str_replace('00','&',$exd);
                          $exd = str_replace('11',',',$exd);
                          $exd = str_replace('_',' ',$exd);
                          $exd = str_replace('combination ','',$exd);
                          $combField = 'combination_';

                          if($exd)
                            $otherGem .= " match(gemstone_type) against('".$gig."*' in boolean mode) ";
                      }
                      $inarr = array();
                      foreach($exd as $ky => $vl)
                      {
                          if(!empty($combField))
                          {
                             $vl = $combField.$vl;
                          }
                          $ex = explode('_',$vl);
                          $re='^[0-9]+$';
                          if(stripos($ex[count($ex)-1],'KT') !== false)
                          {
                             $inarr[] = preg_replace("/[^0-9]/","",$ex[count($ex)-1]);
                          }
                          else
                          {
                             $inarr[] = $ex[count($ex)-1];
                          }
                          unset($ex[count($ex)-1]);
          		  $field = implode('_',$ex);
          	      }
                      if($field == 'combination')
                          $extn .= " AND (".$otherGem." OR ".$field." in ('".implode("','",$inarr)."')) ";
                      else
                      {
                          if(count($inarr) == 1)
                          {
                            if($field == 'gold_purity' && ($inarr[0] == 'Platinum' || $inarr[0] == 'Silver'))
                            {
                                $extn .= " AND metal in ('".implode("','",$inarr)."') ";
                            }
                            else
                            {

                                $extn .= " AND ".$field." in ('".implode("','",$inarr)."') ";
                            }
                          }
                          else
                          {
                              $extn .= " AND ".$field." in ('".implode("','",$inarr)."') ";
                          }

                      }
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
                                            else if($city_area[1] == 'vendor')
                                            {
                                                                    $tbl_name = 'tbl_vendor_master';
                                                                    $colmn_nm = 'orgName';
                                                                    $whr_cond = 'vendor_id';
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
        					$sqlct = "SELECT
                                    $colmn_nm
        							      FROM
                                    $tbl_name
        							      WHERE
                                    $whr_cond = ".$city_area[0];
        					$resct = $this->query($sqlct);
        					if($resct)
        					{
          						$rowct = $this->fetchData($resct);
          						$vndrIds = array();

          						if($rowct)
          						{
                          if($city_area[1] == 'vendor')
                          {
                              $vndrIds = $city_area[0];
                          }
                          else
                          {
                              $lat = $rowct['lat'];
                              $lng = $rowct['lng'];
                              $cityname = $rowct['cityname'];
                              $vndrSql = "SELECT
                                                  vendor_id,
                                                  city,
                                                  ( 3959 * acos( cos( radians(" . $lat . ") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(" . $lng . ") ) + sin( radians(" . $lat . ") ) * sin( radians( lat ) ) ) ) AS distance ";
                              $vndrSql .="FROM
                                                  tbl_vendor_master
                                          HAVING
                                                  distance > 0
                                          AND
                                                  city='" . $cityname . "'";
                              $vndrRes = $this->query($vndrSql);
                              if($vndrRes)
                              {
                                  while($vndrRow = $this->fetchData($vndrRes))
                                  {
                                      $vndrIds[] = $vndrRow['vendor_id'];
                                  }
                              }
                              $vndrIds = implode('","', $vndrIds);
                          }

          						}
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
        					}
        			}
      				$page   = ($params['page'] ? $params['page'] : 1);
      				$limit  = ($params['limit'] ? $params['limit'] : 15);
      				if($pid)
      				{

                  $sql = "SELECT
                                  count(*) as cnt,
                                  dollar_price
                          FROM (
                                  SELECT
                                          product_id AS pid,
                                          price,
                                          b2b_price,
                                          shape,
                                          carat,
                                          (SELECT vendor_id FROM `tbl_vendor_product_mapping` WHERE active_flag=1 AND product_id = pid LIMIT 1) AS vid,
                                          (SELECT dollar_rate FROM tbl_vendor_master WHERE vendor_id = vid) AS dollarval,
                                          carat*b2b_price*1*(SELECT dollar_rate FROM `tbl_vendor_master` where vendor_id=(SELECT vendor_id FROM `tbl_vendor_product_mapping` where active_flag=1 AND product_id=pid limit 1)) as b2b_dollar_price,
                                          carat*price*1*(SELECT dollar_rate FROM `tbl_vendor_master` where vendor_id=(SELECT vendor_id FROM `tbl_vendor_product_mapping` where active_flag=1 AND product_id=pid limit 1)) as dollar_price,
                                          active_flag,
                                          color,
                                          clarity,
                                          metal,
                                          polish,
                                          symmetry,
                                          gold_purity,
                                          gold_weight,
                                          bullion_design,
                                          cut,
                                          fluo,
                                          combination,
                                          gemstone_color,
                                          gemstone_type
                                    FROM
                                          tbl_product_search
                                    WHERE
                                          product_id IN(".$pid.")
                                    AND
                                          complete_flag=1
                            ) t
                        WHERE
                              active_flag=1
                            ".$extn."
                            ".$extnhv."";
        					$res = $this->query($sql);
        					if($res)
        					{
                                   $resrow = $this->fetchData($resrow);
        						  $total = $resrow['cnt'];
        					}

        					// $patsql="
        					// 		       SELECT
                  //     								distinct product_id,
                  //                     product_id as pid,
                  //                     b2b_price*carat as b2btotalprice,
                  //                     price*carat as totalprice,
                  //                     price as jprice,
                  //                     carat*b2b_price*1*(SELECT dollar_rate FROM `tbl_vendor_master` where vendor_id=(SELECT vendor_id FROM `tbl_vendor_product_mapping` where active_flag=1 AND product_id=pid limit 1)) as b2b_dollar_price,
                  //                     carat*price*1*(SELECT dollar_rate FROM `tbl_vendor_master` where vendor_id=(SELECT vendor_id FROM `tbl_vendor_product_mapping` where active_flag=1 AND product_id=pid limit 1)) as dollar_price,
                  //                     1*(SELECT dollar_rate FROM `tbl_vendor_master` where vendor_id=(SELECT vendor_id FROM `tbl_vendor_product_mapping` where active_flag=1 AND product_id=pid limit 1)) as dollar_rate,
                  //                     1*(SELECT gold_rate FROM `tbl_vendor_master` where vendor_id=(SELECT vendor_id FROM `tbl_vendor_product_mapping` where active_flag=1 AND product_id=pid limit 1)) as goldrate,
                  //                     if(metal='Gold',(((((SELECT gold_rate FROM `tbl_vendor_master` where vendor_id=(SELECT vendor_id FROM `tbl_vendor_product_mapping` where active_flag=1 AND product_id=pid limit 1))/995)/10)*gold_purity)*gold_weight),(((((SELECT silver_rate FROM `tbl_vendor_master` where vendor_id=(SELECT vendor_id FROM `tbl_vendor_product_mapping` where active_flag=1 AND product_id=pid limit 1))/999)/1000)*gold_purity)*gold_weight)) as bprice,
                  //     								carat,
                  //     								color,
                  //     								certified,
                  //     								shape,
                  //     								clarity,
                  //     								price,
                  //                     b2b_price as b2bprice,
                  //     								polish,
                  //     								symmetry,
                  //     								cno,
                  //     								gold_purity,
                  //     								gold_weight as gold_weight,
                  //     								type,
                  //     								metal,
                  //     								bullion_design
        					// 		       FROM
        					// 			              tbl_product_search
        					// 		       WHERE
        					// 			              product_id IN(".$pid.")
        					// 		      AND
        					// 			              active_flag=1
        					// 		      ".$extn."
                  //           ".$extnhv."";

                      $patsql = "SELECT
                                        *,
                                        b2b_price*IF(carat,carat,1)*IF(dollarval,dollarval,".dollarValue.") as b2b_dollar_price,
                                        price*IF(carat,carat,1)*IF(dollarval,dollarval,".dollarValue.") as dollar_price
                                 FROM
                                        (
                                          SELECT
                                                  distinct product_id,
                                                  product_id as pid,
                                                  b2b_price*carat as b2btotalprice,
                                                  price*carat as totalprice,
                                                  price as jprice,
                                                  price,
                                                  b2b_price,
                                                  (SELECT vendor_id FROM tbl_vendor_product_mapping WHERE active_flag=1 AND product_id = pid LIMIT 1) AS vid,
                                                  (SELECT dollar_rate FROM tbl_vendor_master WHERE vendor_id = vid) AS dollarval,
                                                  (SELECT dollar_rate FROM tbl_vendor_master WHERE vendor_id = vid) AS dollar_rate,
                                                  (SELECT gold_rate FROM tbl_vendor_master where vendor_id = vid) AS goldrate,
                                                  (SELECT silver_rate FROM `tbl_vendor_master` where vendor_id = vid) AS silverate,
                                                  if(metal='Gold',(((((SELECT gold_rate FROM tbl_vendor_master where vendor_id = vid)/995)/10)*gold_purity)*gold_weight),(((((SELECT silver_rate FROM `tbl_vendor_master` where vendor_id = vid)/999)/1000)*gold_purity)*gold_weight)) AS bprice,
                                                  active_flag,
                                                  carat,
                                  								color,
                                                  cut,
                                                  fluo,
                                                  combination,
                                                  gemstone_color,
                                  								certified,
                                  								shape,
                                  								clarity,
                                                  b2b_price as b2bprice,
                                  								polish,
                                  								symmetry,
                                  								cno,
                                  								gold_purity,
                                  								gold_weight as gold_weight,
                                  								type,
                                  								metal,
                                  								bullion_design,
                                                  date_time,
                                                  gemstone_type
                                          FROM
                                                  tbl_product_search
                                          WHERE
                                                  product_id IN(".$pid.")
                                          AND
                                                  complete_flag=1
                                      ) t
                                WHERE
                                active_flag=1
                                ".$extn."
                                ".$extnhv."";
                  switch($params['sortby'])
                  {
                      case 'pasc':
                              if($params['catid'] == 10000)
                              {
                                  ($params['b2bsort'] ? $patsql.=" ORDER BY b2btotalprice ASC " : $patsql.=" ORDER BY totalprice ASC ");
                              }
                              else if($params['catid'] == 10002)
                              {
                                  $patsql.=" ORDER BY bprice ASC ";
                              }
                              else
                              {
                                  $patsql.=" ORDER BY jprice ASC ";
                              }
                              break;

                      case 'pdesc':
                              if($params['catid'] == 10000)
                              {
                                  ($params['b2bsort'] ? $patsql.=" ORDER BY b2btotalprice DESC " : $patsql.=" ORDER BY totalprice DESC ");
                              }
                              else if($params['catid'] == 10002)
                              {
                                  $patsql.=" ORDER BY bprice DESC ";
                              }

                              else
                              {
                                  $patsql.=" ORDER BY jprice DESC ";
                              }
                              break;
                      case 'rate':
                              $patsql.=" ORDER BY rating DESC ";
                      break;

                      default:
                              $patsql.=" ORDER BY field(product_id,".$pid."), date_time DESC ";
                      break;
                  }

                    $patres=$this->query($patsql);
                    $numpatres=$this->numRows($patres);
                    if($numpatres < $total)
                    {
                        $total = $numpatres;
                    }
                    if($numpatres>0)
                    {
                          $total = $numpatres;
                    }

        					if (!empty($page))
        					{
        						$start = ($page * $limit) - $limit;
        						$patsql.=" LIMIT " . $start . ",$limit";
        					}
                            $patres=$this->query($patsql);
                            $numpatres=$this->numRows($patres);

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
                                      b2bprice,
                      								product_currency as pcur,
                      								prd_img as pimg
        							       FROM
        								              tbl_product_master
        							       WHERE
        								              product_id IN(".$pid.")
        							       AND
        								              active_flag=1
        							       ORDER BY
        								              field(product_id,".$pid.");";
        					$pres=$this->query($psql);
        					while($row1=$this->fetchData($pres))
        					{
          						$pid = $row1['pid'];
          						$arr1[$pid]=$row1;
          						if($params['catid']==10000 || $params['catid']==10002)
          						{
            							$dollarSql = "SELECT
                                                dollar_rate,
                                                gold_rate,
                                                silver_rate,
                                                platinum_rate
                                        FROM
                                                `tbl_vendor_master`
                                        WHERE
                                                vendor_id=(SELECT vendor_id FROM `tbl_vendor_product_mapping` where active_flag=1 AND product_id='".$pid."')";
            							$dollarRes=$this->query($dollarSql);
            							$dollarRow=$this->fetchData($dollarRes);

            							$arr1[$pid]['dollar_rate']=$dollarRow['dollar_rate'];
            							$arr1[$pid]['gold_rate']=$dollarRow['gold_rate'];
            							$arr1[$pid]['silver_rate']=$dollarRow['silver_rate'];
                                        $arr1[$pid]['platinum_rate']=$dollarRow['platinum_rate'];
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
        								                field(product_id,".$pids.");";
        					$pimgres=$this->query($pimgsql);
        					while($row2=$this->fetchData($pimgres))
        					{
          						$prid                  = $row2['product_id'];
          						$arr1[$prid]['images'] = explode('|~|',$row2['images']);
        					}
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
                                                                                                                        active_flag=1";
              									$res1 = $this->query($qry);
              									if($res1)
              									{
                										$row1 = $this->fetchData($res1);
                										$data[$i]['range']['id'] 	= $row['attr_id'];
                										$data[$i]['range']['name'] 	= $row['attr_name'];
                										$data[$i]['range']['dname'] 	= $row['attr_display_name'];
                										$data[$i]['range']['value'] 	= $row1['minval'].';'.$row1['maxval'];
                										$data[$i]['range']['ovalue'] 	= $row1['minval'].';'.$row1['maxval'];
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
                                                                                                                           active_flag=1";
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
                    										$data[$i]['checkbox']['id'] 	  = $row['attr_id'];
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

            $sql = "SELECT * FROM tbl_product_master WHERE product_id=".$params['prdid']." AND active_flag <> 2";
            if (!empty($page))
            {
                $start = ($page * $limit) - $limit;
                $sql.=" LIMIT " . $start . ",$limit";
            }
            $sql2 = "SELECT
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
                    FROM
                        tbl_product_search
                    WHERE
                        product_id=".$params['prdid']."
                    AND
                        active_flag <> 2";

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
                        $shapers = explode('|!|',$row2['diamond_shape']);
                        if($shapers[0] == 'Emerald' && $row2['isBugget'] == 'True')
                        {
                            $shapers[0] = 'Baguette';
                        }
                        $row2['diamond_shape'] = implode('|!|',$shapers);
                        $details=$row2;
                    }
                }
                $pid=implode(',',$prid);

                $sql3="SELECT
							product_id,
							vendor_id,
							vendor_price,
                                                        b2bprice,
							vendor_quantity,
							vendor_currency,
							vendor_remarks
                        FROM
							tbl_vendor_product_mapping
                        WHERE
							product_id=".$pid."
                        AND

							active_flag <> 2
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
						state,
                                                postal_code
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
                    $reslt[$pid]['b2bprice'] = $row['b2bprice'];
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
								product_id =".$params['prdid']." AND display_flag = 1 AND category_id!=".$params['catid']."";
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
                            b2bprice,
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
                                b2b_price,
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
                                b2bprice,
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
                                    b2bprice,
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

        public function suggestProducts($params)
        {
            $page   = ($params['page'] ? $params['page'] : 1);
            $limit  = ($params['limit'] ? $params['limit'] : 4);

            if(!empty($params['purity']))
            {
                $params['purity'] = preg_replace("/[^0-9]/","",$params['purity']);
            }

            $fetchPids = "  SELECT
                                        product_id
                            FROM
                                        tbl_product_category_mapping
                            WHERE
                                        category_id = \"".$params['catid']."\"
                            AND
                                        product_id NOT IN(\"".$params['pid']."\")
                            AND
                                        display_flag = 1";
            $fetchPidsRes = $this->query($fetchPids);
            $cntprds      = $this->numRows($fetchPidsRes);

            if($cntprds > 0)
            {
                while($rows = $this->fetchData($fetchPidsRes))
                {
                    $pids[] = $rows['product_id'];
                }
                $cnt = count($pids);
                $pid  = implode(',',$pids);



                if($cnt > 0)
                {
                    $patsql="   SELECT
                                    distinct a.product_id,
                                    if(a.shape = \"".urldecode($params['shape'])."\",1,0) as sameshape,
                                    if(a.clarity = \"".urldecode($params['clarity'])."\",1,0) as sameclarity,
                                    if(a.color = \"".urldecode($params['color'])."\",1,0) as samecolor,
                                    if(a.cut = \"".urldecode($params['cut'])."\",1,0) as samecut,
                                    if(a.carat = \"".$params['carat']."\",1,0) as samecarat,
                                    if(a.gold_purity = \"".$params['purity']."\",1,0) as samepurity,
                                    if(a.metal = \"".urldecode($params['metal'])."\",1,0) as samemetal,
                                    if(a.gold_weight = \"".$params['gwt']."\",1,0) as sameweight,
                                    if(a.type = \"".urldecode($params['type'])."\",1,0) as sametype,
                                    if(a.certified = \"".urldecode($params['certified'])."\",1,0) as samecertified,
                                    a.product_id as pid,
                                    a.price as jprice,
                                    a.carat,
                                    a.color,
                                    a.cut,
                                    a.certified,
                                    a.shape,
                                    a.clarity,
                                    a.price,
                                    a.polish,
                                    a.symmetry,
                                    a.cno,
                                    a.gold_purity,
                                    a.gold_weight as gold_weight,
                                    a.type,
                                    a.metal,
                                    a.bullion_design
                            FROM
                                    tbl_product_search as a
                            WHERE
                                    a.product_id IN(".$pid.")
                            AND
                                    a.active_flag=1
                            ORDER BY
                                    sameshape desc,
                                    sameclarity desc,
                                    samecolor desc,
                                    samecut desc,
                                    samecarat desc,
                                    samepurity desc,
                                    samemetal desc,
                                    sameweight desc,
                                    samemetal desc,
                                    sametype desc,
                                    pid ASC";
                    if(!empty($page))
                    {
                        $start = ($page * $limit) - $limit;
                        $patsql.=" LIMIT " . $start . ",$limit";
                    }
                    $resSql = $this->query($patsql);
                    $totalPrd = $this->numRows($resSql);
                    if($totalPrd > 0)
                    {
                        while($row = $this->fetchData($resSql))
                        {
                            $pid  = $row['pid'];
                            $arr[$pid] = $row;

                            $masterSql = "  SELECT
                                                    product_id,
                                                    barcode,
                                                    product_name as pname,
                                                    product_display_name as pdname
                                            FROM
                                                    tbl_product_master
                                            WHERE
                                                    product_id = \"".$pid."\"
                                            AND
                                                    active_flag = 1
                                            ORDER BY
                                                    product_id ASC";
                        $masterRes = $this->query($masterSql);
                        $mastercnt = $this->numRows($masterRes);

                        if($mastercnt > 0)
                        {
                            while($masterRows = $this->fetchData($masterRes))
                            {
                                $arr[$pid]['product_main'] = $masterRows;
                            }

                            $vRateSql = "   SELECT
                                                    vendor_id,
                                                    dollar_rate,
                                                    silver_rate,
                                                    gold_rate
                                            FROM
                                                    tbl_vendor_master
                                            WHERE
                                                    vendor_id =(SELECT vendor_id FROM tbl_vendor_product_mapping as b WHERE b.product_id = \"".$pid."\")
                                            AND
                                                    active_flag=1
                                            ";
                            $vRateRes = $this->query($vRateSql);
                            $vRateCnt = $this->numRows($vRateRes);
                            if($vRateCnt > 0)
                            {
                                while($vRateRow = $this->fetchData($vRateRes))
                                {
                                    $arr[$pid]['vendor_rates'] = $vRateRow;
                                }
                            }
                        }
                        else
                        {
                            $arr = array();
                            $err = array('code'=>1,'msg'=>'No products for suggestion');
                        }
                    }
                }
                else
                {
                    $arr = array();
                    $err = array('code'=>1,'msg'=>'No products for suggestion');
                }
            }
            else
            {
                $arr = array();
                $err = array('code'=>1,'msg'=>'Errors in fetching data');
            }
            $result = array('results'=>$arr,'error'=>$err,'total'=>$totalPrd);
            return $result;
        }
        }

        public function showDescription($params)
        {
            $page   = ($params['page'] ? $params['page'] : 1);
            $limit  = ($params['limit'] ? $params['limit'] : 25);
            if($params['catid'] == 10000)
            {
                $needed = "'diamond certificate',\"".urldecode($params['color'])."\",\"".urldecode($params['carat'])."\",\"".urldecode($params['clarity'])."\",\"".urldecode($params['cut'])."\",\"".urldecode($params['shape'])."\"";
            }
            else if($params['catid'] == 10001)
            {
                $params['shape'] = str_replace(',', '","', urldecode($params['shape']));
                $params['clarity'] = str_replace(',', '","', urldecode($params['clarity']));
                $params['colour'] = str_replace(',', '","', urldecode($params['colour']));
                $params['gemstone_type'] = str_replace(',', '","', urldecode($params['gemstone_type']));

                $needed = "'jewellery cert','hallmark',\"".$params['shape']."\",\"".$params['colour']."\",\"".urldecode($params['gemstone_type'])."\",\"".urldecode($params['clarity'])."\",\"".urldecode($params['cut'])."\"";
            }
            else if($params['catid'] == 10002)
            {
                $needed = "'hallmark','bullion'";
            }

                $patsql="   SELECT
                                    *
                            FROM
                                    tbl_attribute_describe
                            WHERE
                                    describe_name IN(".$needed.")
                            AND
                                    active_flag=1
                            ORDER BY
                                    display_position ASC";
                    if(!empty($page))
                    {
                        $start = ($page * $limit) - $limit;
                        $patsql.=" LIMIT " . $start . ",$limit";
                    }
                    $resSql = $this->query($patsql);
                    $totalDes = $this->numRows($resSql);
                    if($totalDes > 0)
                    {
                        while($row = $this->fetchData($resSql))
                        {
                            $desname = $row['describe_name'];
                            $arr[$desname] = $row;
                        }
                        $err = array('code'=>0,'msg'=>'Description fetched');
                    }
                    else
                    {
                        $arr = array();
                        $err = array('code'=>1,'msg'=>'No Description available');
                    }
            $result = array('results'=>$arr,'error'=>$err,'total'=>$totalDes);
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
			global $comm;
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
                                                $vndrLL = str_replace('~', ',', $vndrLL);
						$vndrAltEmail = $vl['alt_email'];
						$vndrCity = $vl['office_city'];
						$address = $vl['fulladdress'];
						$area = $vl['area'];
						$state = $vl['state'];
						$city = $vl['city'];
						$pincode = $vl['postal_code'];
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

			$emailContent = "Hello $usrName, Thank you for showing interest in";
			$emailContent .= "<br/><br/>";
			if(!empty($prdName))
			{
				$emailContent .= "the $prdName you have enquired, The contact details of the vendor are";
			}
			else
			{
				$emailContent .= " the product you have enquired. The contact details of the vendor are";
			}
			$emailContent .= "<br/><br/>";

			$emailContent .= "<table border='2'>";
				$emailContent .= "<tr>";
					$emailContent .= "<th>";
						$emailContent .= "Vendor Name";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Address";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Area";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "City";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "State";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Pincode";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Landline";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Mobile";
					$emailContent .= "</th>";
					$emailContent .= "<th>";
						$emailContent .= "Vendor Email";
					$emailContent .= "</th>";
				$emailContent .= "</tr>";
				$emailContent .= "<td>";
					$emailContent .= $vndrName;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $address;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $area;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $city;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $state;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $pincode;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $vndrLL;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $vndrMobile;
				$emailContent .= "</td>";
				$emailContent .= "<td>";
					$emailContent .= $vndrEmail;
				$emailContent .= "</td>";
			$emailContent .= "</table>";

			$emailContent .= "<br/><br/>";

			$emailContent .= "Regards,";
			$emailContent .= "<br/>";
			$emailContent .= "IFtoSI Team";
			$emailContent .= "<br/>";
			$emailContent .= "For any assistance,call: 91-22-41222241(42). Email: info@iftosi.com";

			$mailHeaders = "Content-type:text/html;charset=UTF-8" . "\r\n";
			$mailHeaders .= "From: info@iftosi.com \r\n";

			$smsText = "Dear $usrName, Thank you for showing interest in the product you have enquired. The contact details of the vendor are:";
			$smsText .= "\r\n\r\n";
			$smsText .= "Vendor Details:";
			$smsText .= "\r\n\r\n";
			$smsText .= "Vendor Name: ";
			$smsText .= $vndrName;
//			$smsText .= "\r\n";
//			$smsText .= "Address: ";
//			$smsText .= $address;
			$smsText .= "\r\n";
			$smsText .= "Area: ";
			$smsText .= $area;
			$smsText .= "\r\n";
			$smsText .= "City: ";
			$smsText .= $city;
			$smsText .= "\r\n";
//			$smsText .= "State: ";
//			$smsText .= $state;
//			$smsText .= "\r\n";
//			$smsText .= "Pincode: ";
//			$smsText .= $pincode;
//			$smsText .= "\r\n";
			$smsText .= "Landline: ";
			$smsText .= $vndrLL;
			$smsText .= "\r\n";
			$smsText .= "Mobile: ";
			$smsText .= $vndrMobile;
			$smsText .= "\r\n";
			$smsText .= "Email: ";
			$smsText .= $vndrEmail;
			$smsText .= "\r\n\r\n";
			$smsText .= "For any assistance, call: 91-22-41222241(42). Email: info@iftosi.com";

			$smsText = urlencode($smsText);
			$sendSMS = str_replace('_MOBILE', $usrMobile, SMSAPI);
			$sendSMS = str_replace('_MESSAGE', $smsText, $sendSMS);
			$res = $comm->executeCurl($sendSMS, true);
            /*if (!empty($res))
            {
				if(stristr($res, 'messageid'))
				{
					$result = array('result'=>'','code'=>1);
					return $result;
				}
				else
				{
					$result = array('result'=>'','code'=>0);
					return $result;
				}
            }
            else
            {
                $result = array('result'=>'','code'=>0);
                return $result;
            }*/

			if(mail($usrEmail, 'Product Details', $emailContent, $mailHeaders) && stristr($res, 'gid'))
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





		public function uploadCertificate($params)
		{
			$files = (!empty($params['file'])) ? $params['file'] : '';
			$product_id = (!empty($params['prdid'])) ? $params['prdid'] : '';

			if(empty($files))
			{
				$resp = array();
				$error = array('code' => 1, 'msg' => 'Certificate file not found');
				$results = array('results' => $resp, 'error' => $error);
				return $results;
			}

			if(empty($product_id))
			{
				$resp = array();
				$error = array('code' => 1, 'msg' => 'Product id not found');
				$results = array('results' => $resp, 'error' => $error);
				return $results;
			}

      if(!is_writeable(WEBROOT))
			{
				@chmod(WEBROOT, 0777);
			}

      if(!file_exists(WEBROOT . 'image-upload'))
			{
				@mkdir(WEBROOT . 'image-upload', 0777, true);
			}

			if(!is_writeable(WEBROOT . 'image-upload'))
			{
				@chmod(WEBROOT . 'image-upload', 0777);
			}

      if(!file_exists(WEBROOT . 'image-upload/uploads'))
			{
				@mkdir(WEBROOT . 'image-upload/uploads', 0777, true);
			}

			if(!is_writeable(WEBROOT . 'image-upload/uploads'))
			{
				@chmod(WEBROOT . 'image-upload/uploads', 0777);
			}

			if(!file_exists(WEBROOT . 'image-upload/uploads/' . $product_id))
			{
				@mkdir(WEBROOT . 'image-upload/uploads/' . $product_id, 0777, true);
			}

			if(!is_writeable(WEBROOT . 'image-upload/uploads/' . $product_id))
			{
				@chmod(WEBROOT . 'image-upload/uploads/' . $product_id, 0777);
			}

      /*if(!is_writeable(WEBROOT))
      {
        echo "Root Not Writeable";
        echo "<br/>";
      }

      if(!is_writeable(WEBROOT . 'image-upload'))
      {
        echo "IMG-UPLOAD Not Writeable";
        echo "<br/>";
      }

      if(!is_writeable(WEBROOT . 'image-upload/uploads'))
      {
        echo "UPLOADS Not Writeable";
        echo "<br/>";
      }

      if(!is_writeable(WEBROOT . 'image-upload/uploads/' . $product_id))
      {
        echo "PRD ID Not Writeable";
        echo "<br/>";
      }*/

			$tmp_name = $params['file']["tmp_name"];
			$name = $params['file']["name"];

			$name = preg_replace("/[^a-zA-Z\.]+/", "", $name);

			if(move_uploaded_file($tmp_name, WEBROOT . 'image-upload/uploads/' . $product_id . '/' . $name))
			{
				$sql = "UPDATE tbl_product_search SET certificate_url='image-upload/uploads/" . $product_id . "/" . $name."' WHERE product_id='".$product_id."'";
				$res = $this->query($sql);

				if($res)
				{
					$resp = array();
					$error = array('code' => 0, 'msg' => 'Data added / updated successfully');
				}
				else
				{
					$resp = array();
					$error = array('code' => 1, 'msg' => 'Error adding / updating information');
				}
			}
			else
			{
				$resp = array();
				$error = array('code' => 1, 'msg' => 'Error uploading certificate');
			}

			$results = array('results' => $resp, 'error' => $error);
			return $results;
		}

		public function getGemstoneTypes()
		{
			$resp = array();
			$sql = "SELECT gemstone_id, gemstone_name AS name, gemstone_display_name AS display_name, active_flag FROM tbl_gemstone_master";
			$res = $this->query($sql);

			if($res)
			{
				while($row = $this->fetchData($res))
				{
					if(!empty($row) && !empty($row['gemstone_id']))
					{
						$resp[] = $row;
					}
				}

				if(empty($resp))
				{
					$error = array('code' => 0, 'msg' => 'No result found');
				}
				else
				{
					$error = array('code' => 1, 'msg' => 'Error fetching gemstone types');
				}
			}
			else
			{
				$error = array('code' => 1, 'msg' => 'Error fetching gemstone types');
			}

			$results = array('results' => $resp, 'error' => $error);
			return $results;
		}

		public function getPrdImgsByIds($params)
		{
			$prdIds= (!empty($params['prdIds']) && !stristr($params['prdIds'], 'undefined') && !stristr($params['prdIds'], 'null')) ? trim(urldecode($params['prdIds'])) : '';
                        if(empty($prdIds))
			{
				$resp = array();
				$error = array('code' => 0, 'msg' => 'No Product IDs found');
				$results = array('results' => $resp, 'error' => $error);
				return $results;
			}

			$expPrdIds = explode(',', $prdIds);
			$expActPrdIds = '';

			foreach($expPrdIds as $key => $value)
			{
				if(!empty($value) && !stristr($value, 'undefined') && !stristr($value, 'null'))
				{
					if(!empty($expActPrdIds))
					{
						$expActPrdIds .= ',' . $value;
					}
					else
					{
						$expActPrdIds .= $value;
					}
				}
			}

			$imgArr = array();
			if(!empty($expActPrdIds))
			{
				$vsql = "SELECT product_id AS pid, vendor_id AS vid FROM tbl_vendor_product_mapping WHERE product_id IN (".$expActPrdIds.")";
				$vres = $this->query($vsql);

				if($vres)
				{
					while($vrow = $this->fetchData($vres))
					{
						if(!empty($vrow) && !empty($vrow['pid']) && !empty($vrow['vid']))
						{
							$vids[$vrow['pid']] = $vrow['vid'];
						}
					}
				}

				$sql = "SELECT product_id, product_image, active_flag FROM tbl_product_image_mapping WHERE product_id IN (".$expActPrdIds.") AND active_flag NOT IN (2,3);";
				$res = $this->query($sql);

				if($res)
				{
					while($row = $this->fetchData($res))
					{
						if(!empty($row) && !empty($row['product_image']))
						{
							if(!empty($vids[$row['product_id']]))
							{
								 $pvid = $vids[$row['product_id']];
							}
							else
							{
								$pvid = '';
							}

							$imgArr[$row['product_id']]['images'][] = array('image' => $row['product_image'], 'active_flag' => $row['active_flag']);
							$imgArr[$row['product_id']]['vid'] = $pvid;
						}
					}

					if(!empty($imgArr))
					{
						$error = array('code' => 0, 'msg' => 'Details fetched successfully');
					}
					else
					{
						$error = array('code' => 0, 'msg' => 'No images found');
					}
				}
				else
				{
					$error = array('code' => 1, 'msg' => 'Error fetching product images');
				}
			}
			else
			{
				$error = array('code' => 0, 'msg' => 'No Product IDs found');
			}

			$results = array('results' => $imgArr, 'error' => $error);
			return $results;
		}
    }
?>
