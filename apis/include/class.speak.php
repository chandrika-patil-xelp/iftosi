<?php
include APICLUDE.'common/db.class.php';
class speak extends DB
{
    function __construct($db) 
    {
            parent::DB($db);
    }
                
    public function viewCom()
    {   
        $sql="select name,city,email,mobile,pimage,opinion,final_opinion from tbl_speak_master where active_flag=1 order by cdt DESC";
        $res =$this->query($sql);            
        $chkres=$this->numRows($res);
        if($chkres>0)
        {
            while( $row =$this->fetchData($res))
            { 
                $arr[]=$row;
            }
            $err = array('errCode' => 0, 'errMsg' => 'Values shown successfully!');            
        }
       else
       {
            $arr='No record available';
            $err=array('code'=>1,'msg'=>'No record found');
       }
        $result = array('results' =>$arr,'error'=>$err);
        return $result;
    }
    
    public function addCom($params)
    {   
        $dt= json_decode($params['dt'],1);
       $detls  = $dt['result'];
       $proErr  = $dt['error'];
       if($proErr['errCode']== 0)
       { 
        $vsql="INSERT INTO tbl_speak(name,city,mobile,email,pimage,opinion,final_opinion,active_flag,upload_time,uploaded_on)
               VALUES('".$detls['name']."','".$detls['city']."',".$detls['mobile'].",'".$detls['email']."','".$detls['pimage']."','".$detls['opinion']."','".$detls['final_opinion']."',1,now(),now())";
        $vres=$this->query($vsql);
        $chksql=$this->numRows($vres);
            if($chkres>0)
            {
                $arr="User Comment is submited";
                $err=array('Code'=>0,'Msg'=>'Data is Inserted successfully');
            }
            else
            {
                $arr='Comment is not submited';
                $err=array('Code'=>1,'Msg'=>'Insert operation is not done');
            }
       }
       else
       {
           $arr='parameters are not passed properly';
           $err=array('Code'=>1,'Msg'=>'Insert operation is not done');
       }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
        
}
?>       
        