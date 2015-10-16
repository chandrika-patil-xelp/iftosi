<?php
include APICLUDE . 'common/db.class.php';
class categoryInfo extends DB
{
    function __construct($db) 
    {
        parent::DB($db);
        
    }
	
	public function getSubCat($catid,$arr=array())
	{
		if($catid)
		{
			$sql = "SELECT p_catid, catid, cat_name FROM tbl_category_master where p_catid=".$catid." order by catid ASC";
		}
		else
		{
			$sql = "SELECT p_catid, catid, cat_name FROM tbl_category_master where p_catid=0 order by catid ASC";
		}
		$res = $this->query($sql);
		if($res)
		{
			while($row = $this->fetchData($res))
			{
				if(!empty($arr) && $row['p_catid'] !=0)
					$arr['subcat'][] = $this->getSubCat($row['catid'],$row);
				else
					$arr['root'][] = $this->getSubCat($row['catid'],$row);
			}
		}
		return $arr;
	}
	
        public function getCatList($params)
        { 
			$sql = "SELECT catid,cat_name FROM tbl_category_master where p_catid=0 order by catid ASC";
			$page   = ($params['page'] ? $params['page'] : 1);
                        $limit  = ($params['limit'] ? $params['limit'] : 3);
			
                        if (!empty($page))
			{
				$start = ($page * $limit) - $limit;
				$sql.=" LIMIT " . $start . ",$limit";
			}
			$res = $this->query($sql);
			if($res)
			{
				$i=0;
				while($row =$this->fetchData($res))
				{
					if($i==0)
						$dpt = "0.7";
					if($i==1)
						$dpt = "0.6";
					if($i==2)
						$dpt = "0.8";
					$reslt['category_id'] = $row['catid'];
					$reslt['category_name'] = $row['cat_name'];
					$reslt['depth'] = $dpt;
					$results[] = $reslt;
					$i++;
				}
					$err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
			}
            $result = array('results'=> $results,'error'=> $err);
            return $result;
        }
    
        public function getCatName($params)
        {
            $sql = "SELECT cat_name FROM tbl_category_master WHERE catid=".$params['catid'];
            
            $page   = ($params['page'] ? $params['page'] : 1);
            $limit  = ($params['limit'] ? $params['limit'] : 15);
            
            if (!empty($page))
            {
                $start = ($page * $limit) - $limit;
                $sql.=" LIMIT " . $start . ",$limit";
            }
            
            $res = $this->query($sql);
            $chkres=$this->numRows($res);
            if($chkres>0)
            {
               $row = $this->fetchData($res);
                {
                    if($row && !empty($row['catid']))
                        {
                            $reslt['category_name'] = $row['cat_name'];
                            $results[] = $reslt;
                        }
                }
                $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
            }
            else
            {
                $results=array();
                $err=array('code'=>1,'msg'=>'Error in fetching data');
            }
            $result = array('results' => $results, 'error' => $err);
            return $result;
        }
        
        public function getCatId($params)
        {
            $sql = "SELECT category_id FROM tbl_categoryid_master WHERE cat_name='".$params['catName']."'";
            $res = $this->query($sql);
            $chkres=$this->numRows($res);
            if($chkres>0)
            {
                $row=$this->fetchData($res);
                if($row && !empty($row['category_id']))
                    {
                        $reslt['category_id'] = $row['catid'];
                        $results[] = $reslt;
                    }
            
                $err = array('errCode' => 0, 'errMsg' => 'Details fetched successfully');
            }
            else
            {
                $results=array();
                $err=array('code'=>1,'msg'=>'Error in fetching data');
            }
            $result = array('results' => $results, 'error' => $err);
            return $result;
        }
    
        public function addCat($params)
        {   
          $csql="select category_name from tbl_categoryid_generator where category_name='".$params['catName']."'";
          $cres=$this->query($csql);
            
          $cnt=$this->numRows($cres);
            if($cnt==0)
            {
                $isql="INSERT INTO tbl_categoryid_generator(category_name,date_time,aflg) VALUES(\"".$params['catName']."\",now(),1)";
                $ires=$this->query($isql);
                $catid=$this->lastInsertedId();
                if($ires)
                {
                    $csql="INSERT INTO tbl_category_master(catid,cat_name,p_catid,cat_lvl,lineage,date_time,updatedby) values(\"".$catid."\",\"".$params['catName']."\",\"".$params['pcatid']."\",\"".$params['lvl']."\",\"".$params['lineage']."\",now(),'CMS_USER')";
                    $cres = $this->query($csql);
                
                    if($cres)
                    {
                        $arr="New category is added";
                        $err=array('Code' =>0,'Msg'=>'Category added successfully!');
                    }
                    else
                    {
                        $arr=array();
                        $err=array('Code' =>1,'Msg'=>'Error in adding category!');
                    }
                }
            }
            else
            {
                 $arr=array();
                 $err=array('Code' =>0,'Msg'=>'Category not added!');
            }
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
        }
        
        public function deleteCat($params)
        {
            $sql = "UPDATE tbl_categoryid_generator set aflg=2 WHERE category_id=".$params['catid'];
            $res = $this->query($sql);
            if($res)
            {
                $arr=array();
                $err = array('Code' => 0, 'Msg' => 'Category deleted successfully!');
            }
            else
            {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Error in fetching data');
            }
            
            $result = array('results'=>$arr,'error' => $err);
            return $result;
        }
        
        public function updateCat($params)
        {
          $sql = "UPDATE  tbl_categoryid_generator set category_name='".$params['catName']."',aflg=1 WHERE  category_id=".$params['catid'];
          $res = $this->query($sql);
          if($res)
          {   
              $csql="UPDATE tbl_category_master SET cat_name='".$params['catName']."',cat_lvl=".$params['lvl'].",p_catid=".$params['pcatid'].",lineage='".$params['lineage']."' WHERE catid=".$params['catid']."";
              $cres=$this->query($csql);
              if($cres)
              {
                    $arr=array();
                    $err = array('Code' => 0, 'Msg' => 'Category name updated successfully!');
              }
          } 
          else
          {
                $arr=array();
                $err=array('code'=>1,'msg'=>'Error in fetching data');
          }
            $result = array('results' => $arr, 'error' => $err);
            return $result;
        }
    }
?>
