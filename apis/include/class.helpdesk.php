<?php
include APICLUDE.'common/db.class.php';
class helpdesk extends DB
{
    function __construct($db) 
    {
            parent::DB($db);
    }
    
    public function askhelp($params)
    {
       $dt= json_decode($params['dt'],1);
       $detls  = $dt['result'];
       $proErr  = $dt['error'];
       if($proErr['errCode']== 0)
       {
       $sql="INSERT INTO tbl_contactus(cemail,uid,logmobile,cname,cquery,dflag,date_time)
                 VALUES('".$detls['cemail']."',".$detls['logmobile'].",".$detls['uid'].",'".$detls['cname']."','".$detls['cquery']."',1,now())";
           
           $res=$this->query($sql);
           if($res)
           {
               $arr='Query has been added';
               $err=array('Code'=>0,'Msg'=>'Insertion is done successfully');
           }
           else
           {
               $arr='Query has not been added';
               $err=array('Code'=>0,'Msg'=>'Insertion unsuccessful');
           }
       }
       else
        {
           $arr='Error in passing the data';
           $err=array('Code'=>0,'Msg'=>'data parameters are incomplete');
        }
        $result=array('result'=>$arr,'error'=>$err);
        return $result;
    }
    
    public function viewhelp($params)
    {
        $chksql="SELECT * from tbl_contactus where dflag=1 order by date_time DESC";
        $page=$params['page'];
        $limit=$params['limit'];
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $chksql.=" LIMIT " . $start . ",$limit";
        }
        $chkres=$this->query($chksql);
        $cntres=$this->numRows($chkres);
        if($cntres=1)
        {
            while($row=$this->fetchData($chkres))
            {
                $arr[]=$row;
            }
            $err=array('Code'=>0,'msg'=>'Update operation completed');
        }
        else
        {
            $arr="you are unsubscribed from our newsletter facility";
            $err=array('Code'=>0,'msg'=>'Update operation completed');
        }
        $result=array('result'=>$arr,'error'=>$err);
        return $result;
    }

    
    
   }
?>       
        
