<?php
include APICLUDE . 'common/db.class.php';
class location extends DB
{
    function __construct($db) 
    {
        parent::DB($db);
        
    }
    
    public function checkArea($params)
    {
        $vsql=" SELECT
                            *
                FROM
                            tbl_area_master
                WHERE
                            area=\"".$params['area']."\"
                AND
                            city=\"".$params['city']."\" 
                AND
                            state=\"".$params['state']."\"";
        $vres=$this->query($vsql);
        $cres=$this->numRows($vres);
        if($cres > 0)
        {    
               $arr['status'] = 'Success';
               while($row = $this->fetchData($vres))
               {
                   $arr['lat']  =   $row['latitude'];
                   $arr['lng']  =   $row['longitude'];
                   
               }
               $err=  array('code' =>0,'msg'=>'Area values matched');
        }
        else
        {
            $place = urlencode($params['fulladd']).','.urlencode($params['area']).','.urlencode($params['city']).','.urlencode($params['state']);
            $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$place."&key=AIzaSyCM1Oa91TLMg7Ol7VP-DiTBBOFqiEPDBI0";
            global $comm;
            $resp = $comm->executeCurl($url, false, false, false, false, false, true);
            if($resp)
            {
                $arr['fadd']=$resp['results'][0]['formatted_address'];
                $arr['lat']=(string)$resp['results'][0]['geometry']['location']['lat'];
                $arr['lng']=(string)$resp['results'][0]['geometry']['location']['lng'];
                if(!empty($arr['fadd']) && !empty($arr['lat']) && !empty($arr['lng']))
                {
                    
                    $areaIns    = " INSERT
                                    INTO 
                                                tbl_area_master
                                                (area,
                                                pincode,
                                                state,
                                                city,
                                                dcity,
                                                latitude,
                                                longitude,
                                                created_date)
                                    VALUES
                                            (\"".$params['area']."\",
                                             \"".$params['pincode']."\",
                                             \"".$params['state']."\",
                                             \"".$params['city']."\",
                                             \"".ucwords(strtolower($params['city']))."\",
                                             \"".$arr['lat']."\",
                                             \"".$arr['lng']."\",
                                                 now()
                                            )";
                    $areaRes    =   $this->query($areaIns);
                    if($areaRes)
                    {
                        $arr['status']    =   'Success';
                    }
                    else
                    {
                        $arr['status']    =   'Failure';
                    }
                }
                else
                {
                    $arr['status']    =   'Success';
                }
            }
            else 
            {
                $arr['status']    =   'Failure';
            }
        }
        $result = array('results'=>$arr);
        return $result;
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
        if(!empty($params['city']))
        {
            $vsql .= " AND city ='".$params['city']."'";
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
            $pincode   = urlencode($params['code']);
            if(!empty($pincode))
            {
              $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$pincode."&sensor=true&components=country:IN&key=AIzaSyCM1Oa91TLMg7Ol7VP-DiTBBOFqiEPDBI0";
            }
            global $comm;
            $resp = $comm->executeCurl($url, false, false, false, false, false, true);
            if($resp)
            {   
                
                $address = $resp['results'][0]['address_components'];
                for($i=0;$i<count($address);$i++)
                {
                    if($address[$i]['long_name'] == 'India')
                    {
                        if($address[$i]['types'][0] == 'postal_code')
                        {
                            $arr[0]['pincode']=$address[$i]['long_name'];
                        }

                        if($address[$i]['types'][0] == 'administrative_area_level_2')
                        {
                            $arr[0]['area']=$address[$i]['long_name'];
                            $arr[0]['city']=$address[$i]['long_name'];
                        }
                            if($address[$i]['types'][0] == 'administrative_area_level_1')
                            {
                                $arr[0]['state']=$address[$i]['long_name'];

                            }
                    }
                }
                    if(!empty($arr))
                    {
                        $err=array('code'=>0,'msg'=>'Details fetched successfully');
                    }
                    else
                    {
                        $err=array('code'=>0,'msg'=>'No match found');
                    }
            }
            $err=array('code'=>1,'msg'=>'no records found');
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
    public function viewbyAreaPincode($params)
    {
        $vsql="SELECT area,city,state,country,latitude,longitude from tbl_area_master where area ='".$params['area']."' AND pincode='".$params['code']."'";
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
            $pincode   = urlencode($params['code']);
            $place     = urlencode($params['area']);
            if(!empty($pincode))
            {
                $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$place."&components=postal_code:".$pincode."&sensor=false&key=AIzaSyCM1Oa91TLMg7Ol7VP-DiTBBOFqiEPDBI0";
            }
            else
            {
                $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$place."&components=postal_code&sensor=false&key=AIzaSyCM1Oa91TLMg7Ol7VP-DiTBBOFqiEPDBI0";
            }
            global $comm;
            $resp = $comm->executeCurl($url, false, false, false, false, false, true);
            if($resp)
            {   

                $address = $resp['results'][0]['address_components'];
                for($i=0;$i<count($address);$i++)
                {
                        if($address[$i]['types'][0] == 'postal_code')
                        {
                            $arr['pincode']=$address[$i]['long_name'];
                        }
                        if($address[$i]['types'][0] == 'locality')
                        {
                            
                            $arr['city']=$address[$i]['long_name'];
                        }
                        if($address[$i]['types'][0] == 'administrative_area_level_1')
                        {
                            $arr['state']=$address[$i]['long_name'];

                        }
                }
                if(!empty($arr))
                {
                    $err=array('code'=>0,'msg'=>'Details fetched successfully');
                }
                else
                {
                    $err=array('code'=>0,'msg'=>'No match found');
                }
            }
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
    
    public function suggestCity($params)
    {
        $sql="SELECT DISTINCT city as n, state AS s FROM tbl_area_master WHERE city LIKE '".$params['name']."%'";
        $res=$this->query($sql);
        if($res)
        {
            while ($row=  $this->fetchData($res)) {
                $arr[]=$row;
            }
            $err=array('code'=>0,'msg'=>'Value fetched successfully');
        }
        else
        {
            $err=array('code'=>1,'msg'=>'No records found');
            $arr=array();
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
    public function cityName($params)
    {
        $sql="SELECT DISTINCT(cityname) as n FROM tbl_city_master WHERE cityname LIKE '".$params['name']."%' LIMIT 4";
        $res=$this->query($sql);
        
        if($res)
        {
            while ($row2 =  $this->fetchData($res)) {
                $arr[]=$row2;
            }
            $err=array('code'=>0,'msg'=>'Value fetched successfully');
        }
        else
        {
            $err=array('code'=>1,'msg'=>'No records found');
            $arr=array();
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
    public function suggestState($params)
    {
        $sql="SELECT DISTINCT state as n FROM tbl_area_master WHERE state LIKE '".$params['name']."%'";
        $res=$this->query($sql);
        if($res)
        {
            while ($row=  $this->fetchData($res)) {
                $arr[]=$row;
            }
            $err=array('code'=>0,'msg'=>'Value fetched successfully');
        }
        else
        {
            $err=array('code'=>1,'msg'=>'No records found');
            $arr=array();
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
    public function suggestArea($params)
    {
        if(empty($params['pincode']))
        {
            $sql="SELECT DISTINCT area as n,city,state,pincode FROM tbl_area_master WHERE area LIKE '".$params['area']."%' LIMIT 0, 20";
        }
        else
        {
            $sql="SELECT DISTINCT area as n,city,state,pincode FROM tbl_area_master WHERE area LIKE '".$params['area']."%' AND  pincode = ".$params['pincode']." LIMIT 0, 20";
        }
        $res=$this->query($sql);
        $cntres =   $this->numRows($res);
        if($cntres > 0)
        {
            $i=0;
            while ($row =  $this->fetchData($res))
            {
                $arr[$i]['city'] =$row['city'];
                $arr[$i]['pincode']=$row['pincode'];
                $arr[$i]['n']=$row['n'];
                $arr[$i]['state']=$row['state'];
                $i++;
            }
            $err=array('code'=>0,'msg'=>'Value fetched successfully');
        }
        else
        {
            $pincode   = urlencode($params['code']);
            $place     = urlencode($params['area']);
            if(!empty($pincode))
            {
                $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$place."&components=postal_code:".$pincode."&sensor=false&key=AIzaSyCM1Oa91TLMg7Ol7VP-DiTBBOFqiEPDBI0";
            }
            else
            {
                $url="https://maps.googleapis.com/maps/api/geocode/json?address=".$place."&sensor=false&key=AIzaSyCM1Oa91TLMg7Ol7VP-DiTBBOFqiEPDBI0";
            }
            global $comm;
            $resp = $comm->executeCurl($url, false, false, false, false, false, true);
            if($resp)
            {
				$isBreak = false;
                $address = $resp['results'];
                for($i=0;$i<count($address);$i++)
                {
                    for($j=0;$j< count($address[$i]);$j++)
                    {
                        if($address[$i]['address_components'][$j]['types'][0] == 'postal_code')
                        {
                            $arr[$i]['pincode']=$address[$i]['address_components'][$j]['long_name'];
                        }
                        if($address[$i]['address_components'][$j]['types'][0] == 'administrative_area_level_2')
                        {
                            $arr[$i]['city']=$address[$i]['address_components'][$j]['long_name'];
                        }
                        if($address[$i]['address_components'][$j]['types'][0] == 'locality')
                        {
                            $arr[$i]['n']=$address[$i]['address_components'][$j]['long_name'];
                        }
                        if($address[$i]['address_components'][$j]['types'][0] == 'administrative_area_level_1')
                        {
                            $arr[$i]['state']=$address[$i]['address_components'][$j]['long_name'];
                        }
						if($j >= 20)
						{
							$isBreak = true;
							break;
						}
                    }

					if($isBreak)
					{
						break;
					}
                }
                if(!empty($arr))
                {
                    $err=array('code'=>0,'msg'=>'Details fetched successfully');
                }
                else
                {
                    $err=array('code'=>0,'msg'=>'No match found');
                }
            }
            $err=array('code'=>1,'msg'=>'No records found');
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }

	public function getLatLngByArea($params)
	{
		$resp = array();
		$area = (!empty($params['area'])) ? trim(urldecode($params['area'])) : '';
		$city = (!empty($params['city'])) ? trim(urldecode($params['city'])) : '';

		if(!empty($area))
		{
			$sql = "SELECT latitude, longitude FROM tbl_area_master WHERE area='$area' AND city='$city' LIMIT 1";
			$res = $this->query($sql);

			if($res)
			{
				$row = $this->fetchData($res);
				if(!empty($row) && !empty($row['latitude']) && !empty($row['longitude']))
				{
					$resp = array('latitude' => $row['latitude'], 'longitude' => $row['longitude']);
					$error = array('errCode' => 0, 'errMsg' => 'Details fetched successfully');
				}
				else
				{
					$error = array('errCode' => 0, 'errMsg' => 'No results found');
				}
			}
			else
			{
				$error = array('errCode' => 1, 'errMsg' => 'Error fetching details');
			}
		}
		else
		{
			$error = array('errCode' => 1, 'errMsg' => 'Area is mandatory');
		}

		$results = array('results' => $resp, 'error' => $error);
		return $results;
	}
        
}
?>
