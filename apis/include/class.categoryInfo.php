<?php
include APICLUDE . 'common/db.class.php';
class categoryInfo extends DB
{
    function __construct($db) 
    {
        parent::DB($db);
        
    }
        public function getCatList()
        { 
        $sql = "SELECT * FROM tbl_categoryid_generator";
        $res = $this->query($sql);
        if($res)
        {
            while($row =$this->fetchData($res))
            {
                if($row && !empty($row['category_id']))
                    {
                        $reslt['category_id'] = $row['category_id'];
                        $reslt['category_name'] = $row['category_name'];
                        $results[] = $reslt;
                    }
                }
                $err = array('Code' => 0, 'Msg' => 'Details fetched successfully');
            }
            $result = array('results'=> $results,'error'=> $err);
            return $result;
        }
    
        public function getCatName($params)
        {
            $sql = "SELECT * FROM tbl_categoryid_generator WHERE category_id=".$params['catid'];
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
            $csql="select count(*) from tbl_categoryid_generator where category_name='".$params['catName']."'";
            $cres=$this->query($csql);
            $cnt=$this->numRows();
            if(!$cnt<1)
            {
            $isql="INSERT INTO tbl_categoryid_generator(category_name) values('".$params['catName']."')";
            $ires = $this->query($isql);
            if($ires)
            {
                $arr="New category is added";
                $err=array('Code' =>0,'Msg'=>'Category added successfully!');
            }
            else
            {
                $arr="Category already being added";
                $err=array('Code' =>0,'Msg'=>'Category not added!');
            }
            }
            else
            {
                $arr="No category is added";
                $err=array('Code' =>1,'Msg'=>'Error in adding category!');
            }
            $result = array('results'=>$arr,'error'=>$err);
            return $result;
            
        }
        
        public function deleteCat($params)
        {
            $sql = "DELETE from tbl_categoryid_generator WHERE category_id=".$params['catid'];
            $res = $this->query($sql);
            $cnt=$this->numRows($res);
            if($cnt>0)
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
            $sql = "UPDATE  tbl_categoryid_generator set category_name='".$params['catName']."' WHERE  category_id=".$params['catid'];
            $res = $this->query($sql);
            if($res)
            {   $arr=array();
                $err = array('Code' => 0, 'Msg' => 'Category name updated successfully!');
            }
            else
            {
                $results="No record found";
                $err=array('code'=>1,'msg'=>'Error in fetching data');
            }
            $result = array('results' => $arr, 'error' => $err);
            return $result;
        }
    }
?>