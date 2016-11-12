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
                                                user_id =".$params['vid']." ";
                            $getvres = $this->query($getvdet);
                            $getRow = $this->fetchData($getvres);

                            $catidSql = "   SELECT 
                                                    (SELECT GROUP_CONCAT(category_id) FROM tbl_product_category_mapping WHERE product_id=".$params['pid'].") AS cids, 
                                                    (SELECT GROUP_CONCAT(catid) FROM tbl_category_master WHERE p_catid=0 AND catid IN(cids)) AS category_id 
                                            FROM 
                                                    tbl_product_category_mapping 
                                            WHERE 
                                                    product_id =".$params['pid']."
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
                            if($catid == "10000")
                            {

                                $p[0] = $pdet['pid'];
                                $p[1] = $pdet['shape'];
                                $p[2] = $pdet['certified'];
                                $p[3] = $pdet['barcode'];
                                $p[4] = $pdet['cut'];
                                $p[5] = $pdet['carat'];
                                $p[6] = $pdet['clarity'];
                                $p[7] = $pdet['color'];
                                $p[8] = $this->IND_money_format(ceil($pdet['carat']*$pdet['price']*$pdet['dollarRate']));
                                $msgng = array(0=>'Product Id',1=>'Shape',2=>'Certificate',3=>'Barcode',4=>'Cut',5=>'Carat',6=>'Clarity',7=>'Colour',8=>'Price');
                            }
                            if($catid == "10001")
                            {
                                $p[0]  = $pdet['pid'];
                                $p[1]  = $pdet['shape'];
                                $p[2]  = $pdet['metal'];
                                $p[3]  = $pdet['barcode'];
                                $p[4]  = $pdet['gold_purity'];
                                $p[5]  = $pdet['gold_weight'];
                                $p[6]  = $pdet['certified'];
                                $p[7]  = ceil($pdet['price']);
                                $msgng = array(0=>'Product Id',1=>'Type',2=>'Metal',3=>'Barcode',4=>'Purity',5=>'Gold Weight',6=>'Certificate',7=>'Price');
                                }
                            if($catid == "10002")
                            {
                                $p[0]  = $pdet['pid'];
                                $p[1]  = $pdet['type'];
                                $p[2]  = $pdet['metal'];
                                $p[3]  = $pdet['barcode'];
                                $p[4]  = $pdet['gold_purity'];
                                $p[5]  = $pdet['gold_weight'];
                                if($pdet['metal'] == 'Gold')
                                {
                                    $p[6]= $this->IND_money_format(ceil($pdet['gold_weight']*(($pdet['goldRate']/10)*($pdet['gold_purity']/995))));
                                }
                                else if($pdet['metal'] == 'Silver')
                                {
                                    $p[6]= $this->IND_money_format(ceil($pdet['gold_weight']*(($pdet['silverRate']/1000)*($pdet['gold_purity']/999))));
                                }
                                $msgng = array(0=>'Product Id',1=>'Type',2=>'Metal',3=>'Barcode',4=>'Purity',5=>'Gold Weight',6=>'Price');
                            }
                            $msg ='';
                            for($i=0;$i < count($p);$i++)
                            {
                                $msg .= $msgng[$i].' : '.$p[$i].",\r\n";
                            }
                            $tempParams = array('useremail'=>$udetail['uemail'],'user_name'=>$udetail['uname'],'user_mob'=>$udetail['umobile'],'mobile'=>$getRow['logmobile'],'email'=>$getRow['email'],'username'=>$getRow['user_name'],'pdet'=>urlencode($msg));
                            $sendMail = $this->sendEnqMailSMS($tempParams,$catid);
                            $fil = $sendMail['error']['code'];
                            if($fil == 0)
                            {
                                $arr="Log Entry is successfully completed";
                                $err=array('Code'=>0,'Msg'=>'Data inserted');
                            }
                        }
                        else
                        {
                            $arr=array();
                            $err=array('Code'=>1,'Msg'=>'Error in completing the operation');
                        }
                    }
                }
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }

        public function sendEnqMailSMS($params,$catid)
        {
                $msg = urldecode($params['pdet']);

                $msg = rtrim($msg, ",\r\n");

                global $comm;
                $smsText = '';
                $subject = '';
                $message = '';
                $headers = '';
                
                $headers .= "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: <info@iftosi.com>' . "\r\n";
                $subject .= 'Recent enquiry to IFtoSI';
                $message  = $this->sendEnqMailSMSTemplate($params,$msg,$catid);
                $smsText .= "Recent enquiry to IFtoSI";
                $smsText .= "\r\n\r\n";
                $smsText .= "Hello ".$params['username'].", ".$params['useremail']." has shown interest in";
                $smsText .= "\r\n";
                $smsText .= $msg;
                $smsText .= "\r\n\r\n";
                $smsText .= "The buyer should contact you shortly.";
                $smsText .= "\r\n\r\n";
                $smsText .= "For any assistance, call: 91-22-41222241/42. Email: info@iftosi.com";
                $smsText .= "\r\n\r\n";
                $smsText .= "Team IFtoSI";

            if(!empty($params['email']))
            {
                    mail($params['email'], $subject, $message, $headers);
            }

            $smsText = urlencode($smsText);
            $sendSMS = str_replace('_MOBILE', $params['mobile'], SMSAPI);
            $sendSMS = str_replace('_MESSAGE', $smsText, $sendSMS);
            $res = $comm->executeCurl($sendSMS, true);
            
            if($res)
            {
                $arr = array();
                $err = array('code'=>0,'msg'=>'SMS & EMAIL sent to the user');
            }
            else
            {
                $arr = array();
                $err = array('code'=>0,'msg'=>'SMS & EMAIL is not sent to the user');
            }
            $result = array('result'=>$arr,'error'=>$err);
            return $result;
        }



        public function sendEnqMailSMSTemplate($params,$msg,$catid)
        {
           $arr=explode(",",$msg);
           $len=sizeof($arr);
          $message=' <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, user-scalable=no" >
        <title>recent enquiry</title>
    </head>
    <body style="margin:0; padding: 0; background-color: #171334;">
    <center style="box-sizing: border-box;">
        <div style="text-align: center; height: auto; font-size: 1em; margin:0; max-width: 500px; color:#666;-webkit-font-smoothing: antialiased;font-family: Open Sans, Roboto, Helvetica, Arial;">
            <a href="'.DOMAIN.'"><div style="vertical-align: top; height: auto; display: inline-block; padding:15px 0 15px 0; text-align: center;color: #d00000; text-transform: uppercase"><src="'.DOMAIN.'tools/img/iftosi.png" style="width:100%;"></div></a>
            <div style="height: auto; border-radius: 0px;box-shadow: 0 0 30px 5px rgba(0,0,0,0.4);background: #fff;">
                <div  style="font-size: 20px;  padding: 40px 10px 5px 10px; color:#333;text-transform: capitalize;">Product enquiry</div>
                <a href="'.DOMAIN.'"><div style="vertical-align: top; height: auto; display: inline-block; padding:20px 0 20px 0;text-align: center;color: #d00000; text-transform: uppercase;padding: 20px 0 20px 0;"><img src="'.DOMAIN.'tools/img/common/Enquiry.png" style="width:50px;"></div></a>
                <div style="font-size: 14px; padding: 15px 10px 10px 10px; color:#8A0044;">Hello '.ucwords(strtolower($params['username'])).',</div>
                <div style="font-family: Open Sans, Roboto, Helvetica, Arial;font-size: 14px; color: #333;padding: 0px 15px 20px 15px;">Customer with following details</div>
                <center style="padding: 0px 30px 20px 30px;box-sizing:border-box;line-height:19px;">
                    <div style="width: 100%;display: inline-block;">
                        <div style="width: 30%;vertical-align: top;text-align: right;display: inline-block;font-size: 14px;text-transform: capitalize;color: #666;padding-bottom:5PX;font-family: Open Sans, Roboto, Helvetica, Arial;">Name<span style="padding-left: 20px;">:</span></div>
                        <div style="width: 50%;display: inline-block; text-align: left;font-size: 14px;text-transform: capitalize;padding-bottom:5PX;color: #8A0044;font-weight: bold;word-wrap: break-word;">'.ucwords(strtolower($params['user_name'])).'</div>                   
                    </div>
                    <div style="width: 100%;display: inline-block;">
                        <div style="width: 30%;vertical-align: top;text-align: right;display: inline-block;font-size: 14px;text-transform: capitalize;color: #666;padding-bottom:5PX;font-family: Open Sans, Roboto, Helvetica, Arial;">Contact no<span style="padding-left:20px;">:</span></div>
                        <div style="width: 50%;display:inline-block;text-align: left;font-size: 14px;text-transform: capitalize;padding-bottom:5PX;color: #8A0044;font-weight: bold;word-wrap: break-word;">'.$params['user_mob'].'</div>
                    </div>
                    <div style="width: 100%;display: inline-block;">
                        <div style="width: 30%;vertical-align: top;text-align: right;display: inline-block;font-size: 14px;text-transform: capitalize;color: #666;padding-bottom:5PX;font-family: Open Sans, Roboto, Helvetica, Arial;">Email<span style="padding-left:20px;">:</span></div>
                        <div style="width: 50%;display: inline-block;text-align: left;font-size: 14px;text-transform: capitalize;padding-bottom:5PX;color: #8A0044;font-weight: bold;word-wrap: break-word;">'.$params['useremail'].'</div>
                    </div>
                </center>
                <div style="font-family: Open Sans, Roboto, Helvetica, Arial;font-size: 14px; color: #333;padding: 0px 15px 20px 15px;">Has shown interest in following product</div>';
             
            for($i=1;$i<($len);$i++)
            {
                $tempArr=explode(":",$arr[$i]);
                if(!empty($tempArr[1]) && $tempArr[1] !== " ")
                {   
                    $message .='<div style="width: 60%;display: inline-block;border-right: 1px solid #f0f0f0;">
                              <div style="width: 35%;text-align: left;display: inline-block;font-size: 16px;text-transform: capitalize;color: #666;padding-bottom:5PX;font-family: Open Sans, Roboto, Helvetica, Arial;">'.$tempArr[0].'&nbsp;</div>
                              <span style="padding-right: 20px;">:</span>';
                                if(stristr($tempArr[0],'Price'))
                                {
                                    $message .='<img src="'.DOMAIN.'tools/img/common/Rupee15.png" style="width:15px;vertical-align:initial;height:15px;">';
                                    $tempArr[1] = $this->IND_money_format(trim($tempArr[1]));
                                   
                                }
                                $message .= '<div style="width: 35%;    display: inline-block; text-align: left;font-size: 16px;text-transform: capitalize;padding-bottom:5PX;color: #8A0044;font-weight: bold;">&nbsp;'.$tempArr[1].'</div>
                              </div>' ;
                               
                }
            }

        $message .='<div class="">The buyer should contact you shortly.</div>';
        $message .='<center style="padding-top: 50px;">';
        $message .='<img src="'.DOMAIN.'tools/img/common/diamond.jpg" width="50">';
        $message .='<img src="'.DOMAIN.'tools/img/common/jewellery.jpg" width="50">';
        $message .='<img src="'.DOMAIN.'tools/img/common/bullions.jpg" width="50">';
        $message.='</center>';
        $message.='<div style="height:auto;line-height: 22px; color:#333; font-size: 13px;padding: 25px 15px 40px 15px;">For any assistance, <br>Call: <a href="tel:91-22-41222241/42" style="text-transform: uppercase; width:auto;display: inline-block; font-weight: bold; color:#333; text-decoration: none; letter-spacing: 0.02em;">91-22-41222241 (42)</a> | Email: <b>neeraj@iftosi.com</b></div>';
        $message.='</div>';
        $message.='<div style="color:#fff;font-size:15px;padding: 20px 0">Team <b>IF</b>to<b>SI</b>.com</div>';
        $message.='</div>';
        $message.='</center>';
        $message.='</body>';
        $message.='</html>';     
      
       return $message;
}
    # view log by vendor for his product being viewed

    public function viewLog1($params)
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
                                   active_flag = 1
                    AND
                                   vendor_id=".$params['vid']."
                    AND
                                   user_id NOT IN(".$params['vid'].")
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
                $prid[$i] = $rowEnq['product_id'];
                $sqlMaster='SELECT
                                barcode,
                                product_name
                        FROM
                                tbl_product_master
                        WHERE
                                active_flag = 1
                        AND
                                product_id='.$prid[$i];
                $resMaster=$this->query($sqlMaster);
                if($this->numRows($resMaster) > 0)
                {
                    while($rowMaster = $this->fetchData($resMaster))
                    {
                        $pid = $rowEnq['product_id'];
                        $arr[$i]['enquiry'] = $rowEnq;

                    }
                }

                $sqlSearch='    SELECT
                                        ps.product_id,
                                        ps.gold_purity as purity,
                                        ps.certified,
                                        ps.price,
                                        ps.shape,
                                        ps.clarity,
                                        ps.color,
                                        ps.type,
                                        ps.gold_weight,
                                        ps.carat,
                                        ps.metal,
                                        pm.barcode
                                FROM
                                       tbl_product_search as ps,
                                       tbl_product_master as pm
                                WHERE
                                       ps.product_id = pm.product_id
                                AND
                                        ps.active_flag = 1
                                AND
                                        ps.product_id='.$prid[$i];
                $resSearch=$this->query($sqlSearch);
                if($this->numRows($resSearch) > 0)
                {
                    while($rowSearch = $this->fetchData($resSearch))
                    {
                        $arr[$i]['search'] = $rowSearch;

                    }
                }
                $PcatSql = "SELECT
                                    b.cat_name as cat_name,
                                    b.catid,
                                    product_id
                        FROM
                                    tbl_product_category_mapping as a,
                                    tbl_category_master as b
                        WHERE
                                    a.category_id = b.catid
                        AND
                                    b.p_catid = 0
                        AND
                                    a.product_id = ".$prid[$i]."
                        AND
                                    a.display_flag=1
                        ORDER BY
                                    a.date_time DESC";

                $resCat=$this->query($PcatSql);
                if($this->numRows($resCat) > 0)
                {
                    while($rowCat = $this->fetchData($resCat))
                    {
                           $cat['mcatid'] = $rowCat['catid'];
                            $cat['cat_name'] = $rowCat['cat_name'];
                            $arr[$i]['category'] = $cat;

                           if($arr[$i]['category']['mcatid'] == 10001)
                            {

                                $arr[$i]['search']['price'] = $arr[$i]['search']['price'];
                            }
                            if($arr[$i]['category']['mcatid'] == 10000)
                            {
                                $arr[$i]['search']['price'] = $arr[$i]['search']['price']*$dollarValue*$arr[$i]['search']['carat'];
                            }
                            if($arr[$i]['category']['mcatid'] == 10002)
                            {
                                if($arr[$i]['search']['metal'] == 'Gold')
                                {
                                    $metalRate= $goldRate;
                                    $finalRate= ($metalRate/10) * ($arr[$i]['search']['purity']/995);
                                    $arr[$i]['search']['price'] = $finalRate * $arr[$i]['search']['gold_weight'];
                                }
                                else if($arr[$i]['search']['metal'] == 'Silver')
                                {
                                    $metalRate=$silverRate;
                                    $finalRate=($metalRate/1000)*($arr[$i]['search']['purity']/999);
                                    $arr[$i]['search']['price']=$finalRate*$arr['search']['gold_weight'];
                                }
                            }
                            $arr[$i]['search']['price'] = ceil($arr[$i]['search']['price']);
                            $arr[$i]['search']['price'] = $this->IND_money_format($arr[$i]['search']['price']);
                    }
                }
                $i++;
            }
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

    public function viewLog($params)
    {

        $vendorSql  = " SELECT
                                    dollar_rate,
                                    silver_rate,
                                    gold_rate
                        FROM
                                    tbl_vendor_master
                        WHERE
                                    vendor_id =".$params['vid']."
                        AND
                                    active_flag= 1";
        $vendorRes = $this->query($vendorSql);

        if($vendorRes)
        {
            $vrow = $this->fetchData($vendorRes);
                if($vrow['dollar_rate'] == '0.00' || empty($vrow['dollar_rate']))
                {
                    $vrow['dollar_rate'] = dollar_rate;
                }
                if($vrow['gold_rate'] == '0.00' || empty($vrow['gold_rate']))
                {
                    $vrow['dollar_rate'] = gold_rate;
                }
                if($vrow['silver_rate'] == '0.00' || empty($vrow['silver_rate']))
                {
                    $vrow['silver_rate'] = silver_rate;
                }
            $arr['vendor_rates'] = $vrow;
        }


        $Enqsql = " SELECT
                                *
                    FROM
                                tbl_product_enquiry
                    WHERE
                                vendor_id =".$params['vid']."
                    AND
                                user_id NOT IN(".$params['vid'].")
                    AND
                                active_flag = 1
                    ORDER BY
                                date_time DESC";
        $EnqRes = $this->query($Enqsql);
        $EnqCount = $this->numRows($EnqRes);

        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);

        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $Enqsql.=" LIMIT " . $start . ",$limit";
        }

        $EnqRes = $this->query($Enqsql);
        $total_pages = ceil($EnqCount/$limit);
        if($EnqCount > 0 )
        {
            $i=0;
            while($EnqRow = $this->fetchData($EnqRes))
            {
                $pid[] = $EnqRow['product_id'];
                $arr[$i]['enquiry'] = $EnqRow;
                $i++;
            }
            $pid = implode(',',$pid);

            $PcatSql = "SELECT
                                    b.cat_name,
                                    b.catid,
                                    product_id
                        FROM
                                    tbl_product_category_mapping as a,
                                    tbl_category_master as b
                        WHERE
                                    a.category_id = b.catid
                        AND
                                    b.p_catid = 0
                        AND
                                    a.product_id IN (".$pid.")
                        AND
                                    a.display_flag=1
                        ORDER BY
                                    a.date_time DESC";

            $PcatRes = $this->query($PcatSql);

            if($PcatRes)
            {
                $j =0;
                while($catRow = $this->fetchData($PcatRes))
                {
                    $cat['mcatid'] = $catRow['catid'];
                    $cat['cat_name'] = $catRow['cat_name'];
                    $arr[$j]['category'] = $cat;
                    $j++;
                }
                 $prdSql = " SELECT
                                    ps.product_id,
                                    pc.category_id as category,
                                    ps.gold_purity,
                                    ps.certified,
                                    ps.price,
                                    ps.shape,
                                    ps.clarity,
                                    ps.color,
                                    ps.type,
                                    ps.gold_weight,
                                    ps.carat,
                                    ps.metal,
                                    pm.barcode
                            FROM
                                    tbl_product_master as pm,
                                    tbl_product_search as ps,
                                    tbl_product_category_mapping as pc
                            WHERE
                                    pm.product_id = ps.product_id
                            AND
                                    pc.product_id = ps.product_id
                            AND
                                    pm.product_id
                            IN
                                    (".$pid.")
                            AND
                                    pc.category_id
                            IN
                                    (10000,10001,10002)
                            AND
                                    ps.active_flag = 1
                            ORDER BY
                                    pc.date_time DESC";
                $prdRes = $this->query($prdSql);
                if($prdRes)
                {
                    $k = 0;
                    while($prdRow = $this->fetchData($prdRes))
                    {
                        $arr[$k]['search']=array();
                        $ar['product_id'] = $prdRow['product_id'];
                        if($prdRow['category'] == '10000')
                        {
                            $ar['certified'] = $prdRow['certified'];
                            $ar['shape'] = $prdRow['shape'];
                            $ar['color'] = $prdRow['color'];
                            $ar['carat'] = $prdRow['carat'];
                            $ar['price'] = $this->IND_money_format(ceil($prdRow['price']*$prdRow['carat']*$arr['vendor_rates']['dollar_rate']));
                            $ar['clarity'] = $prdRow['clarity'];
                            $ar['barcode'] = $prdRow['barcode'];
                        }
                        if($prdRow['category'] == '10001')
                        {
                            $ar['certified'] = $prdRow['certified'];
                            $ar['shape'] = $prdRow['shape'];
                            $ar['price'] = $this->IND_money_format(ceil($prdRow['price']));
                            $ar['metal'] = $prdRow['metal'];
                            $ar['purity'] = $prdRow['gold_purity'];
                            $ar['weight'] = $prdRow['weight'];
                            $ar['barcode'] = $prdRow['barcode'];
                        }
                        if($prdRow['category'] == '10002')
                        {
                            $ar['certified'] = $prdRow['certified'];
                            $ar['metal'] = $prdRow['metal'];
                            $ar['purity'] = $prdRow['gold_purity'];
                            $ar['weight'] = $prdRow['gold_weight'];
                            if($ar['metal'] == 'Gold')
                            {
                                $ar['price'] = $this->IND_money_format(ceil(($arr['vendor_rates']['gold_rate']/10)*($ar['purity']/995)*($ar['weight'])));
                            }
                            else if($ar['metal'] == 'Silver')
                            {
                                $ar['price'] = $this->IND_money_format(ceil(($arr['vendor_rates']['silver_rate']/1000)*($ar['purity']/999)*($ar['weight'])));

                            }
                            $ar['type'] = $prdRow['type'];
                            $ar['barcode'] = $prdRow['barcode'];
                        }

                        $arr[$k]['search']= $ar;
                        $k++;
                    }

                }
            }
                $err = array('code'=>0,'msg'=>'Enquiry data fetched');
        }
        else
        {
            $arr = array();
            $err = array('code'=>1,'msg'=>'No records found');
        }
        $result = array('results'=>$arr,'error'=>$err,'total_enqs'=>$EnqCount,'total_pages'=>$total_pages);
        return $result;
    }

}
