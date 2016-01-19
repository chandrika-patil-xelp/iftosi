<?php
include APICLUDE.'common/db.class.php';
class enquiry extends DB
{
        function __construct($db) 
        {
            parent::DB($db);
        }

        
        ## For fetching user details of customer
        
        /* 
         * Fetch Details of user from tbl_registration
         * fetch details of clicked Product according to product_id passed while clicking
         * 
         * Fill details using two arrays. 1-- udetail  2-- prod
         * 
         *  */

    public function filLog($params)
    {  
        $uid=$params['uid'];
        $udsql="SELECT 
                                logmobile,
                                user_name,
                                is_vendor,
                                email
                FROM 
                                tbl_registration
                WHERE
                                user_id=".$uid."";
        $udres=$this->query($udsql);
        $chkres=$this->numRows($udres);
        if($chkres == 1)
        {
            while($row1=$this->fetchData($udres)) 
            {
                $udetail['umobile']=$row1['logmobile'];
                $udetail['uname']=$row1['user_name'];
                $udetail['uemail']=$row1['email'];
                $isV['isV']       = $row1['is_vendor'];
            }
            
            $chksql="SELECT user_id FROM tbl_product_enquiry where user_id=".$uid." AND product_id=".$params['pid']." and vendor_id=".$params['pid'].""; 
            $chkres=$this->query($chksql);
            $numchk=$this->numRows($chkres);
                if($numchk == 0)
                {
                    if($isV['isV'] == 2)
                    {
                        $arr= array();
                        $err=array('Code'=>0,'Msg'=>'Data Hidden');
                    }
                    else
                    {

                        $isql=" INSERT
                                INTO 
                                            tbl_product_enquiry
                                           (user_id,
                                           user_name,
                                           user_mobile,
                                           user_email,
                                           product_id,
                                           vendor_id,
                                           type_flag,
                                           updatedby,
                                           date_time)
                                VALUES
                                        (".$uid.",
                                        \"".$udetail['uname']."\",
                                        \"".$udetail['umobile']."\",    
                                        \"".$udetail['uemail']."\",
                                        \"".$params['pid']."\",
                                        \"".$params['vid']."\",
                                            1,
                                            'customer',
                                            now())";
                        $ires=$this->query($isql);
                        
                        if($ires)
                        {
                            
                            global $comm;
                            
                            $getvdet = "SELECT 
                                                logmobile,
                                                email,
                                                user_name 
                                        FROM
                                                tbl_registration 
                                        WHERE 
                                                user_id =".$params['vid'];
                            $getvres = $this->query($getvdet);
                            $getRow = $this->fetchData($getvres);
                            
                            $catidSql = "  SELECT 
                                                category_id
                                        FROM
                                                tbl_product_category_mapping
                                        WHERE 
                                                product_id =\"".$params['pid']."\" 
                                        
                                        AND 
                                                display_flag=1";
                            $catidRes = $this->query($catidSql);
                            $catidRow = $this->fetchData($catidRes);
                            $catid = $catidRow['category_id'];

                            $pidDetSql = "  SELECT
                                                    *
                                            FROM 
                                                    tbl_product_search as a,
                                                    tbl_product_master as b
                                            WHERE
                                                    a.product_id=\"".$params['pid']."\"
                                            AND
                                                    a.product_id = b.product_id
                                            AND
                                                    a.active_flag=1";

                            $pidDetRes = $this->query($pidDetSql);

                            if($pidDetRes)
                            {
                                while($row = $this->fetchData($pidDetRes))
                                {
                                    $pdet['pid'] = $row['product_id'];
                                    $pdet['barcode']= $row['barcode'];
                                    $pdet['shape']= $row['shape'];
                                    $pdet['metal']= $row['metal'];
                                    $pdet['gold_purity'] = $row['gold_purity'];
                                    $pdet['gold_weight'] = $row['gold_weight'];
                                    $pdet['type'] = $row['type'];
                                    $pdet['certified'] = $row['certified'];
                                    $pdet['carat'] = $row['carat'];
                                    $pdet['cut'] = $row['cut'];
                                    $pdet['clarity'] = $row['clarity'];
                                    $pdet['color'] = $row['color'];
                                    $pdet['price'] = $row['price'];
                                }
                                $vSql = "   SELECT 
                                                    gold_rate,
                                                    dollar_rate,
                                                    silver_rate 
                                            FROM 
                                                    tbl_vendor_master
                                            WHERE 
                                                    vendor_id =".$params['vid'];
                                $vRes = $this->query($vSql);
                                $vRow = $this->fetchData($vRes);
                                $pdet['goldRate'] = $vRow['gold_rate'];
                                $pdet['silverRate'] = $vRow['silver_rate'];
                                $pdet['dollarRate'] = $vRow['dollar_rate'];
                            }
                            
                            
                            $url = APIDOMAIN . 'index.php?action=sendEnqMailSMS&useremail='.$udetail['uemail'].'&mobile='.$getRow['logmobile'].'&email='.$getRow['email'].'&username='.$getRow['user_name'].'&pdet='.urlencode(serialize($pdet)).'&catid='.$catid;
                            $res = $comm->executeCurl($url);
                            $fil = $res['error']['code'];
                            if($fil == 0)
                            {
                                $arr="Log Entry is successfully completed";
                                $err=array('Code'=>0,'Msg'=>'Data inserted');
                            }
                        }
                        else
                        {
                            echo $url = APIDOMAIN . 'index.php?action=sendEnqMailSMS&useremail='.$udetail['uemail'].'&mobile='.$getRow['logmobile'].'&email='.$getRow['email'].'&username='.$getRow['user_name'];
                            
                            $arr=array();
                            $err=array('Code'=>1,'Msg'=>'Error in completing the operation');
                        }
                    }
                }
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
    # view log by vendor for his product being viewed
    
    public function viewLog($params)
    {
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);
        
        $sqlVd="  SELECT 
                            silver_rate,
                            gold_rate,
                            dollar_rate
                FROM 
                            tbl_vendor_master
                WHERE 
                            vendor_id='".$params['vid']."'";
        
        $resVd=$this->query($sqlVd);
        
        if ($resVd)
        {
            $rates = $this->fetchData($resVd);
            
            if(!empty($rates['dollar_rate']) && $rates['dollar_rate'] !== '0.00')
            {
                $dollarValue = $rates['dollar_rate'];
            }
            else 
            {
                $dollarValue=dollarValue;
            }
            
            if(!empty($rates['gold_rate']) && $rates['gold_rate'] !== '0.00') 
            {
                $goldRate = $rates['gold_rate'];
            }
            else
            {
                $goldRate=goldRate;
            }
            
            if(!empty($rates['silver_rate']) && $rates['silver_rate'] !== '0.00') 
            {
                $silverRate = $rates['silver_rate'];
            }
            else
            {
                $silverRate=silverRate;
            }
        }
        # check the products under the requested vendor
        
        $viewEnq=" SELECT
                                   user_id,
                                   user_name,
                                   user_mobile,
                                   user_email,
                                   product_id,
                                   vendor_id,
                                   user_ip_address,
                                   type_flag,
                                   updatedby,
                                   update_time
                    FROM
                                   tbl_product_enquiry
                    WHERE
                                   active_flag <> 2
                    AND                
                                   vendor_id=".$params['vid']."
                    ORDER BY
                                   date_time
                    DESC
                ";
        
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $viewEnq.=" LIMIT " . $start . ",$limit";
        }
        
        $resEnq=$this->query($viewEnq);
        $totalEnqs=$this->numRows($resEnq);
        $total_pages = ceil($totalEnqs/$limit);
        
        if($totalEnqs > 0)
        {   
            $arr = array();
            $i = 0;
            while($rowEnq=$this->fetchData($resEnq))
            {   
                $sqlMaster='SELECT
                                product_id,
                                barcode,
                                product_name
                        FROM
                                tbl_product_master
                        WHERE 
                                active_flag <> 2 
                        AND 
                                product_id='.$rowEnq['product_id'];
                $resMaster=$this->query($sqlMaster);
                if($this->numRows($resMaster) > 0)
                {
                    while($rowMaster = $this->fetchData($resMaster))
                    {
                        $pid = $rowEnq['product_id'];
                        $arr[$i]['enquiry'] = $rowEnq; 
                        $arr[$i]['pro_dtls']=$rowMaster;
                    }
                }

                $sqlSearch=' SELECT 
                                product_id,
                                gold_purity,
                                certified,
                                price,
                                shape,
                                clarity,
                                color,
                                type,
                                gold_weight,
                                carat,
                                metal
                        FROM
                               tbl_product_search
                        WHERE
                                active_flag <> 2
                        AND 
                                product_id='.$pid;
                $resSearch=$this->query($sqlSearch);
                if($this->numRows($resSearch) > 0)
                {
                    while($rowSearch = $this->fetchData($resSearch))
                    {
                        $arr[$i]['search'] = $rowSearch;
                    }
                }
                $sqlCat='SELECT 
                                    b.cat_name,
                                    b.catid,
                                    b.p_catid
                        FROM 
                                    tbl_product_category_mapping AS a,
                                    tbl_category_master AS b
                        WHERE 
                                    a.product_id='.$pid.' 
                        AND 
                                    b.catid=a.category_id 
                        /* AND 
                                    b.p_catid in (0,10000,10001,10002) */';
                $resCat=$this->query($sqlCat);
                if($this->numRows($resCat) > 0)
                {
                    while($rowCat = $this->fetchData($resCat))
                    {
                           $arr[$i]['categories']['cat_name']=$rowCat['cat_name'];
                           $arr[$i]['categories']['mCatid']=$rowCat['catid'];
                           $arr[$i]['categories']['pcatid']=($rowCat['p_catid'] == 0 ? $rowCat['catid'] : $rowCat['p_catid']);
                            
                           if($arr[$i]['categories']['pcatid'] == 10001)
                            {

                                $arr[$i]['search']['price'] = $arr[$i]['search']['price'];
                            }
                            if($arr[$i]['categories']['pcatid'] == 10000)
                            {
                                $arr[$i]['search']['price'] = $arr[$i]['search']['price']*$dollarValue*$arr[$i]['search']['carat'];
                            }
                            if($arr[$i]['categories']['pcatid'] == 10002)
                            {
                                if($arr[$i]['search']['metal'] == 'Gold')
                                {
                                    $metalRate= $goldRate;
                                    $finalRate= ($metalRate/10) * ($arr[$i]['search']['gold_purity']/995);
                                    $arr[$i]['search']['price'] = $finalRate * $arr[$i]['search']['gold_weight'];
                                }
                                else if($arr[$i]['search']['metal'] == 'Silver')
                                {
                                    $metalRate=$silverRate;
                                    $finalRate=($metalRate/1000)*($arr[$i]['search']['gold_purity']/999);
                                    $arr[$i]['search']['price']=$finalRate*$arr['search']['gold_weight'];
                                }
                            }
                            $arr[$i]['search']['price'] = ceil($arr[$i]['search']['price']);
                            $arr[$i]['search']['price'] = $this->IND_money_format($arr[$i]['search']['price']);
                    }
                }
                $i++;
            }
            $arr['enqs'][] = $arr;
            $err=array('Code'=>0,'Msg'=>'Values fetched successfully');
        }
        else
        {
            $arr=array('total_enqs' => $totalEnqs, 'total_pages' => $total_pages);
            $err=array('Code'=>0,'Msg'=>'No Values fetched');
        }
        $result=array('results'=>$arr,'error'=>$err,'total_enqs'=>$totalEnqs,'total_pages'=>$total_pages);
        return $result;
    }

    public function IND_money_format($money)
    {
        $len = strlen($money);
        $m = '';
        $money = strrev($money);
        
        for($i=0;$i<$len;$i++)
        {
            if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i != $len)
            {
                $m .=',';
                
            }
            $m .=$money[$i];
            
        }

        return strrev($m);
    }
}