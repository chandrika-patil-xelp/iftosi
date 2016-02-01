<?php

include APICLUDE.'common/db.class.php';
class manager extends DB
{

    function __construct($db)
    {
        parent::DB($db);
    }

        public function manageVendors($params)
        {
            global $comm;

            $allVendors = " SELECT
                                    vendor_id,
                                    profile_active_date as active_date,
                                    profile_expiry_date as expire_date,
                                    DATEDIFF(profile_expiry_date,now()) as diff
                            FROM
                                    tbl_vendor_master
                            WHERE
                                    is_complete=2
                            AND
                                    active_flag=1
                            ORDER BY
                                    date_time DESC";
            
            $allVendorsRes = $this->query($allVendors);
            $cntAllActVendors = $this->numRows($allVendorsRes);
            
            if($cntAllActVendors)
            {
                $i = 0;
                while($vendorRow = $this->fetchData($allVendorsRes))
                {
                    $vendorId[$i] = $vendorRow['vendor_id'];
                    $vActDate[$i] = $vendorRow['active_date'];
                    $vDiff[$i] = $vendorRow['diff'];
                    if($vDiff[$i] >= 0 && $vActDate[$i] !== '0000-00-00 00:00:00')
                    {
                        $updtSql = "UPDATE tbl_vendor_master set active_flag = 0,expire_flag = 1 WHERE vendor_id =".$vendorId[$i];
                        $udtRes = $this->query($updtSql);
                        
                        $uDetSql = "SELECT
                                            user_name,
                                            logmobile,
                                            is_vendor,
                                            email
                                    FROM
                                            tbl_registration
                                    WHERE
                                            is_active = 1
                                    AND
                                            user_id =".$vendorId[$i];
                        $uDetRes =  $this->query($uDetSql);
                        if($uDetRes)
                        {
                            $uDetRow = $this->fetchData($uDetRes);
                            $uDet['user_name'] = $uDetRow['user_name'];
                            $uDet['email'] = $uDetRow['email'];
                            $uDet['logmobile'] = $uDetRow['logmobile'];
                            $uDet['is_vendor'] = $uDetRow['is_vendor'];
                            
                        }
                        $url = APIDOMAIN."index.php?action=sendDeactMailSms&username=".urlencode($uDet['user_name'])."&email=".urlencode($uDet['email'])."&mobile=".$uDet['logmobile']."&isVendor=1";
                        $res  = $comm->executeCurl($url);
                        $i++;
                    }
                    $arr[] = $vendorRow;
                }
                $err = array('code'=>0,'msg'=>'Details fetched successfully');
            }
            else
            {
                $arr = array();
                $err = array('code'=>1,'No records Found');
            }
            $result = array('result'=>$arr,'error'=>$err);
            return $result;
        }
}
?>