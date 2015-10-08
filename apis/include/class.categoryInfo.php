<?php
include APICLUDE . 'common/db.class.php';
class categoryInfo extends DB
{
    function __construct($db) 
    {
        parent::DB($db);
        
    }
        public function getCatList($params)
        { 
			$sql = "SELECT category_id, category_name FROM tbl_categoryid_generator order by category_id ASC";
			$page=$params['page'];
			$limit=$params['limit'];
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
					$reslt['category_id'] = $row['category_id'];
					$reslt['category_name'] = $row['category_name'];
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
            $sql = "SELECT * FROM tbl_categoryid_generator WHERE category_id=".$params['catid'];
            $page   = $params['page'];
            $limit  = $params['limit'];
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
                    if($row && !empty($row['category_id']))
                        {
                            $reslt['category_name'] = $row['category_name'];
                            $results[] = $reslt;
                        }
                }
                $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
            }
            else
            {
                $results="No record found";
                $err=array('code'=>1,'msg'=>'Error in fetching data');
            }
            $result = array('results' => $results, 'error' => $err);
            return $result;
        }
        
        public function getCatId($params)
        {
            $sql = "SELECT * FROM tbl_categoryid_generator WHERE category_name='".$params['catName']."'";
            $res = $this->query($sql);
            $chkres=$this->numRows($res);
            if($chkres>0)
            {
                $row=$this->fetchData($res);
                if($row && !empty($row['category_id']))
                    {
                        $reslt['category_id'] = $row['category_id'];
                        $results[] = $reslt;
                    }
            
                $err = array('errCode' => 0, 'errMsg' => 'Details fetched successfully');
            }
            else
            {
                $results="No record found";
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
               $isql="INSERT INTO tbl_categoryid_generator(category_name,cdt,udt,aflg) values('".$params['catName']."',now(),now(),1)";
                $ires = $this->query($isql);
                if($ires)
                {
                    $arr="New category is added";
                    $err=array('Code' =>0,'Msg'=>'Category added successfully!');
                }
                else
                {
                    $arr="No category is added";
                    $err=array('Code' =>1,'Msg'=>'Error in adding category!');
                }
            }
            else
            {
                 $arr="Category already being added";
                 $err=array('Code' =>0,'Msg'=>'Category not added!');
                
            }
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
            
        }
        
        public function deleteCat($params)
        {
            $sql = "UPDATE tbl_categoryid_generator set aflg=0,udt=now() WHERE category_id=".$params['catid'];
            $res = $this->query($sql);
            if($res)
            {
                $arr=array();
                $err = array('Code' => 0, 'Msg' => 'Category deleted successfully!');
            }
            else
            {
                $arr="No record found";
                $err=array('code'=>1,'msg'=>'Error in fetching data');
            }
            
            $result = array('results'=>$arr,'error' => $err);
            return $result;
        }
        
        public function updateCat($params)
        {
         echo $sql = "UPDATE  tbl_categoryid_generator set category_name='".$params['catName']."',udt=now(),aflg=1 WHERE  category_id=".$params['catid'];
            $res = $this->query($sql);
            if($res)
            {   $arr=array();
                $err = array('Code' => 0, 'Msg' => 'Category name updated successfully!');
            }
            else
            {
                $arr="No record found";
                $err=array('code'=>1,'msg'=>'Error in fetching data');
            }
            $result = array('results' => $arr, 'error' => $err);
            return $result;
        }
    }
?>