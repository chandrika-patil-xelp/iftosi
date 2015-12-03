<?php
include APICLUDE . 'common/db.class.php';
class location extends DB
{
    function __construct($db) 
    {
        parent::DB($db);
        
    }
//...................City..........................    
    
    public function addCity($params)
    {
        $chksql="select count(1) from tbl_city_master where cityname='".$params['cityname']."' and state_name='".$params['sname']."' and country_name='".$params['cname']."'";
        $chkres=$this->query($chksql);
        $res=$this->numRows($chkres);
        if($res<1)
        {
        $isql="INSERT INTO tbl_city_master(cityname,state_name,country_name,date_time) VALUES('".$params['cityname']."','".$params['sname']."','".$params['cname']."',now())";
        $ires=$this->query($isql);
        if($ires)
        {
            $arr="City data is Inserted";
            $err=array('code'=>0,'msg' =>'Entry done successfully in City table');
        }
        else
        {
            $arr=array();
            $err=array('code'=>1,'msg'=>'error in insert operation');
        }
        }
        else
        {
            $arr="This city is alreay available in records";
            $err=array('code'=>1,'msg'=>'error in insert operation');
        }
        $result = array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
    public function viewbyCity($params)
    {
        $vsql="SELECT state_name,cityname,country_name FROM tbl_city_master WHERE cityname='".$params['cityname']."'";
        $vres=$this->query($vsql);
        $cres=$this->numRows($vres);
        if($cres!=0)
           {    
               while($row=$this->fetchData($vres))
               {
                   $arr[]=$row;
                   
               }
               $err= array('code' =>0,'msg'=>'City value has been retreived');
           }
        else
        {
            $arr=array();
            $err= array('code'=>1,'msg'=>'error in fetching data');
        }
        $result = array('results'=>$arr,'error'=>$err);
        return $result;
    }    
    
//...................State........................        
    
    public function viewbyState($params)
    {
        $vsql="SELECT state_name,country_name,lat,lng FROM tbl_city_master WHERE country_name='".$params['cname']."' AND state_name='".$params['sname']."' order by cityid ASC";
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $vsql.=" LIMIT " . $start . ",$limit";
        }
        
        $vres=$this->query($vsql);
        $chkres=$this->numRows($vres);
        if($chkres>0)
        {
            while($row=$this->fetchData($vres))
            {
                $arr[]=$row;
                
            }
            $err=array('code'=>0,'msg'=>'Values fetched successfully');
        }
        else
        {
            $arr="Can't Fetch the values";
            $err=array('code'=>1,'msg'=>'error in fetching data');
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
//...................Country.......................
    
    public function viewbyCountry($params)
    {
        $vsql="SELECT state_name,cityname,lat,lng from tbl_city_master where country_name='".$params['cname']."'";
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $vsql.=" LIMIT " . $start . ",$limit";
        }
        $vres=$this->query($vsql);
        $cres=$this->numRows($vres);
        if($cres>0)
        {
            while($row=$this->fetchData($vres))
            {
                $arr[]=$row;
            }
            $err=array('code'=>0,'msg'=>'Value fetched successfully');
        }
        else
        {
            $arr=array();
            $err=array('code'=>1,'msg'=>'error in fetching data');
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
    
    public function viewbyPincode($params)
    {
        $vsql="SELECT area,city,state,country,latitude,longitude from tbl_area_master where pincode='".$params['code']."'";
        $vres=$this->query($vsql);
        $cres=$this->numRows($vres);
        if($cres>0)
        {
            while($row=$this->fetchData($vres))
            {
                $arr[]=$row;
            }
            $err=array('code'=>0,'msg'=>'Value fetched successfully');
        }
        else
        {
            $arr=array();
            $err=array('code'=>1,'msg'=>'no records found');
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
    public function updatecity($params)
    {
        $vsql="UPDATE tbl_city_master SET country_name='".$params['cname']."',state_name='".$params['sname']."',cityname='".$params['newcityname']."' where cityname='".$params['oldcityname']."'";
        $vres=$this->query($vsql);
        if($vres)
        {
            $err=array('code'=>0,'msg'=>'Value fetched successfully');
            $arr="City has been updated";
        }
        else
        {
            $err=array('code'=>0,'msg'=>'Value fetched successfully');
            $arr=array();
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
        
}
?>
