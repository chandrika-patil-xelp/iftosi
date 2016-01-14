<?php
    include APICLUDE.'common/db.class.php';
    class urlmaster extends DB
    {
        function __construct($db) 
        {
                parent::DB($db);
        }

        public function changePassUrl($params)
        {
            $urlmaker = $this->generateURL(6);
                
            if($cntRes == 0)
            {
                $isql = "   INSERT
                            INTO
                                    tbl_url_master
                                   (urlkey,
                                    user_id,
                                    logmobile,
                                    email,
                                    cPass_url,
                                    active_flag,
                                    created_date)
                            VALUES
                                    (\"".$urlmaker."\",
                                    \"".$params['uid']."\",    
                                    \"".$params['mobile']."\",
                                    \"".$params['email']."\",
                                    \"".$params['url']."\",
                                        1,
                                        now()
                                    )";
                $res = $this->query($isql);
                if($res)
                {
                    $sql = "    SELECT
                                        *
                                FROM 
                                        tbl_url_master
                                WHERE 
                                        urlkey =\"".$urlmaker."\"
                                AND
                                        active_flag=1";
                    $urlgetRes = $this->query($sql);
                    if($urlgetRes)
                    {
                        while($urlgetRow = $this->fetchData($urlgetRes))
                        {
                            $arr[] = $urlgetRow;
                        }
                    }
                    $err = array('code'=>0,'msg'=>'url is created');
                }
                else
                {
                    $arr = array();
                    $err = array('code'=>0,'msg'=>'Error in url creation');
                }
            }
            else
            {
                $row = $this->fetchData($res);
                $arr = $row['urlkey'];
                $err = array('code'=>0,'msg'=>'url is created');
            }
            $result = array('result'=>$arr,'error'=>$err);
            return $result;
        }
        
        public function getUserDet($params)
        {

            $sql = "SELECT
                            user_id
                    FROM 
                            tbl_url_master
                    WHERE
                            urlkey = \"".$params['key']."\"
                    AND
                            DATE_SUB(`update_date`,INTERVAL - 30 MINUTE) > now()
                    AND
                            active_flag=1";
            $res = $this->query($sql);
            $cntRes = $this->numRows($res);
            
            if($cntRes == 1)
            {
                $row = $this->fetchData($res);
                $uid = $row['user_id'];
                
                global $comm;
                $url = APIDOMAIN."index.php?action=viewAll&uid=".$uid;
                $res  = $comm->executeCurl($url);
                $data = $res;
                $arr = $data;
            }
            else
            {
                $arr['error'] = array('code'=>1,'msg'=>'Error in fetching data');
            }
            $result = $arr;
            return $result;
        }
        
        
        private function generateURL($strLength)
        {
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for($i=0;$i<=$strLength;$i++)
            {
                $string = substr($chars,rand(6,strlen($chars)),6);
            }
            $chkSql = " SELECT
                                urlkey
                        FROM
                                tbl_url_master
                        WHERE
                                urlkey = \"".$string."\"
                        AND 
                                active_flag=1";
            $chkRes = $this->query($chkSql);
            $cntRes = $this->numRows($chkRes);
            if($cntRes > 0)
            {
                $string = $this->generateURL(6);
            }
            return $string;
        }
                
                
    }
?>