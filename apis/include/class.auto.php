<?php
include APICLUDE.'common/db.class.php';
class auto extends DB
{
        function __construct($db)
        {
                parent::DB($db);
        }

    public function searchbox($params)
    {
	      $sql="SELECT
                      *,
                      product_name,
                      MATCH(product_name) AGAINST ('" . $params['srch'] . "*' IN BOOLEAN MODE) AS startwith
              FROM
                      tbl_product_master
              WHERE
                      MATCH(product_name) AGAINST ('".$params['srch']."*' IN BOOLEAN MODE)
              ORDER BY startwith ASC";

        $page   = $params['page'];
        $limit  = $params['limit'];
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $sql.=" LIMIT " . $start . ",$limit";
        }
        $res=$this->query($sql);
        if($this->numRows($res))
        {
            while($row=$this->fetchData($res))
            {
                $arrval['id']       = $row['product_id'];
                $arrval['name']     = $row['product_display_name'];
                $arrval['model']    = $row['product_model'];
                $arrval['brand']    = $row['product_brand'];
                $arrval['price']  = $row['prd_price'];
                $arrval['description']  = $row['product_desc'];
                $arrval['designer']  = $row['desname'];
                $arr[]              =$arrval;
            }
            $err=array('Code'=> 0,'Msg'=>'Values are fetched');
        }
        else
        {
                $arr = array();
                $err = array('Code'=> 1,'Msg'=>'Search Query Failed');
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }

    public function suggestCity($params)
    {
		    $sql="SELECT
          				cityid as id,
          				if(cityname = '".$params['str']."',1,0) AS exact,
          				if(cityname like '".$params['str']."%',1,0) AS startwith,
          				MATCH(cityname) AGAINST ('" . $params['str'] . "*' IN BOOLEAN MODE) AS score,
          				cityname AS name,
          				'' AS city,
          				cityid AS cid
			    FROM
				          tbl_city_master
			    WHERE
				          MATCH(cityname) AGAINST ('".$params['str']."*' IN BOOLEAN MODE)
			    ORDER BY
				          exact DESC,
                  startwith DESC,
                  score DESC";

        $limit=$params['limit'];
        $sql.=" LIMIT $limit";

        $res=$this->query($sql);
        if($this->numRows($res)>0)
        {
            while($row=$this->fetchData($res))
            {
                $arr[]= $row;
            }
        }

		$sql="SELECT
          				a.id,
          				if(a.area = '".$params['str']."',1,0) AS exact,
          				if(a.area like '".$params['str']."%',1,0) AS startwith,
          				MATCH(a.area) AGAINST ('" . $params['str'] . "*' IN BOOLEAN MODE) AS score,
          				a.area AS name,
          				a.dcity AS city,
          				b.cityid AS cid
			    FROM
				          tbl_area_master a
			    JOIN
				          tbl_city_master b
			    ON
				          a.dcity = b.cityname
			    WHERE
				          MATCH(a.area) AGAINST ('".$params['str']."*' IN BOOLEAN MODE)
		      AND
				          display_flag = 0
			    ORDER BY
				          exact DESC,
                  startwith DESC,
                  score DESC";

        $limit = $params['limit']-count($arr);
        $sql.=" LIMIT $limit";

        $res=$this->query($sql);
        if($this->numRows($res)>0)
        {
            while($row=$this->fetchData($res))
            {
                $arr[]= $row;
            }
        }

		if(count($arr))
		{
			 $err=array('Code'=> 0,'Msg'=>'Values are fetched');
		}
        else
        {
                $arr = array();
                $err = array('Code'=> 1,'Msg'=>'Search Query Failed');
        }
    $result=array('results'=>$arr,'error'=>$err);
    return $result;
    }

    public function suggestBrand($params)
    {
	      $sql="SELECT
                      name,
                      MATCH(name) AGAINST ('" . $params['str'] . "*' IN BOOLEAN MODE) as startwith
              FROM
                      tbl_brandid_generator
              WHERE
                      MATCH(name) AGAINST ('".$params['str']."*' IN BOOLEAN MODE)
              ORDER BY startwith";

        $page=$params['page'];
        $limit=$params['limit'];
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $sql.=" LIMIT " . $start . ",$limit";
        }

        $res=$this->query($sql);
        if($this->numRows($res))
        {
            while($row=$this->fetchData($res))
            {
                $arr[]= $row;
            }
            $err=array('Code'=> 0,'Msg'=>'Values are fetched');
        }
        else
        {
                $arr = "No records matched";
                $err = array('Code'=> 1,'Msg'=>'Search Query Failed');
        }
    $result=array('results'=>$arr,'error'=>$err);
    return $result;
    }

    public function suggestCat($params)
    {
	      $sql="SELECT
                      cat_name,
                      MATCH(cat_name) AGAINST ('" . $params['str'] . "*' IN BOOLEAN MODE) AS startwith
              FROM
                      tbl_category_master
              WHERE
                      MATCH(cat_name) AGAINST ('".$params['str']."*' IN BOOLEAN MODE)
              ORDER BY
                      startwith ASC";
        $page=$params['page'];
        $limit=$params['limit'];
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
           $sql.=" LIMIT " . $start . ",$limit";
        }


        $res=$this->query($sql);
        $chkres=$this->numRows($res);
        if($chkres>0)
        {$i=0;
            while($row=$this->mysqlFetchArr($res))
            {
                $arr[]= $row;
                $i++;
            }
            $err=array('Code'=> 0,'Msg'=>'Values are fetched');
        }
        else
        {
                $arr = "No records matched";
                $err = array('Code'=> 1,'Msg'=>'Search Query Failed');
        }
    $result=array('results'=>$arr,'error'=>$err);
    return $result;
    }

    public function girdleSuggest($params)
    {
	      $sql="SELECT
                      name AS nm,
                      display_name AS n
              FROM
                      tbl_available_autosuggest_lists
              WHERE
                      active_flag=1
              ORDER BY
                      id ASC";
        $page=$params['page'];
        $limit=$params['limit'];
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
           $sql.=" LIMIT " . $start . ",$limit";
        }
        $res=$this->query($sql);
        $chkres=$this->numRows($res);
        if($chkres>0)
        {
            $i=0;
            while($row=$this->fetchData($res))
            {
                $arr[]= $row;
                $i++;
            }
            $err=array('code'=> 0,'msg'=>'Values are fetched');
        }
        else
        {
                $arr = "No records matched";
                $err = array('code'=> 1,'msg'=>'Search Query Failed');
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }

    public function bankSuggest($params)
    {
	      $sql="SELECT
                      DISTINCT(bank_name) as n
              FROM
                      tbl_bank_master
              WHERE
                      active_flag=1
              ORDER BY
                      id ASC";
        $page=$params['page'];
        $limit=$params['limit'];
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
           $sql.=" LIMIT " . $start . ",$limit";
        }
        $res=$this->query($sql);
        $chkres=$this->numRows($res);
        if($chkres>0)
        {
            $i=0;
            while($row=$this->fetchData($res))
            {
                $arr[]= $row;
                $i++;
            }
            $err=array('code'=> 0,'msg'=>'Values are fetched');
        }
        else
        {
                $arr = "No records matched";
                $err = array('code'=> 1,'msg'=>'Search Query Failed');
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }

    public function suggestVendor($params)
    {
	      $sql="SELECT
                      vendor_name,
                      MATCH(vendor_name) AGAINST ('" . $params['str'] . "*' IN BOOLEAN MODE) as startwith
              FROM
                      tbl_vendor_master
              WHERE
                      MATCH(vendor_name) AGAINST ('".$params['str']."*' IN BOOLEAN MODE)
              ORDER BY
                      startwith DESC";
        $page=$params['page'];
        $limit=$params['limit'];
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $sql.=" LIMIT " . $start . ",$limit";
        }
        $res=$this->query($sql);
        if($this->numRows($res)>0)
        {
            while($row=$this->fetchData($res))
            {
                $arr[]= $row;
            }
            $err=array('Code'=> 0,'Msg'=>'Values are fetched');
        }
        else
        {
                $arr = "No records matched";
                $err = array('Code'=> 1,'Msg'=>'Search Query Failed');
        }
    $result=array('results'=>$arr,'error'=>$err);
    return $result;
    }

    public function suggestAreaCity($params)
    {
        $arr = $arr1 = array();
        $page=(!empty($params['page'])) ? trim(urldecode($params['page'])) : 1;
        $limit=(!empty($params['limit'])) ? trim(urldecode($params['limit'])) : 7;


        $sql ="SELECT
                      vendor_id as id,
                      if(orgName = '".$params['str']."',1,0) AS exact,
                      if(orgName like '".$params['str']."%',1,0) AS startwith,
                      MATCH(orgName) AGAINST ('" . $params['str'] . "*' IN BOOLEAN MODE) AS score,
                      orgName AS name,
                      city,
                      0 AS isArea
              FROM
                      tbl_vendor_master
              WHERE
                      MATCH(orgName) AGAINST ('".$params['str']."*' IN BOOLEAN MODE)
              AND
                      active_flag = 1
              ORDER BY
                      exact DESC,
                      startwith DESC,
                      score DESC";

       $page = $params['page'];
       $limit = $params['limit'];
       if (!empty($page)) {
           $start = ($page * $limit) - $limit;
           $sql.=" LIMIT " . $start . ",$limit";
       }

       $res = $this->query($sql);
       if ($this->numRows($res) > 0) {

           $limit = $limit - $this->numRows($res);

           while ($row = $this->fetchData($res)) {
               $arr[] = $row;
           }
           $err = array('Code' => 0, 'Msg' => 'Values are fetched');
       } else {
           $arr = "No records matched";
           $err = array('Code' => 1, 'Msg' => 'Search Query Failed');
       }



         $sql1 ="SELECT
                				id,
                				if(area = '".$params['str']."',1,0) AS exact,
                				if(area like '".$params['str']."%',1,0) AS startwith,
                				MATCH(area) AGAINST ('" . $params['str'] . "*' IN BOOLEAN MODE) AS score,
                				area AS name,
                				dcity AS city,
                        1 AS isArea
			          FROM
				                tbl_area_master
			          WHERE
				                MATCH(area) AGAINST ('".$params['str']."*' IN BOOLEAN MODE)
			          AND
				                display_flag = 0
			          ORDER BY
				                exact DESC,
                        startwith DESC,
                        score DESC";
        $page = $params['page'];
        $limit = $params['limit'];
        if (!empty($page)) {
            $start = ($page * $limit) - $limit;
            $sql1.=" LIMIT " . $start . ",$limit";
        }
        $res1 = $this->query($sql1);
        if ($this->numRows($res1) > 0) {
            while($row1 = $this->fetchData($res1))
            {
                $arr1[] = $row1;
            }
            $err = array('Code' => 0, 'Msg' => 'Values are fetched');
        } else {
            $arr = "No records matched";
            $err = array('Code' => 1, 'Msg' => 'Search Query Failed');
        }

        if(is_array($arr) && is_array($arr1))
            $arr2 = array_merge($arr,$arr1);
        else if(is_array($arr))
            $arr2 = $arr;
        else
            $arr2 = $arr1;

        $result = array('results' => $arr2, 'error' => $err);
        return $result;
    }
}
?>
