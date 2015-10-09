<?php
include APICLUDE . 'common/db.class.php';
class attribute extends DB
{
    function __construct($db) 
    {
        parent::DB($db);
        
    }
    
    public function get_attrList($params)
    {
        $sql = "SELECT attr_id,attr_name,attr_display_name,attr_unit,attr_type_flag,attr_unit_pos,attr_values,attr_range
                FROM tbl_attribute_master";
        $page   = ($params['page'] ? $params['page'] : 1);
        $limit  = ($params['limit'] ? $params['limit'] : 15);
        if (!empty($page))
        {
            $start = ($page * $limit) - $limit;
            $sql.=" LIMIT " . $start . ",$limit";
        }
        
        $res = $this->query($sql); 
	if($res)
        {
            while($row1=$this->mysqlFetchArr($res)) 
            {
                $arr[] = $row1;
            }
            $err=array('Code'=>0,'Msg'=>'Data Fetched Successfully');
        }
        $result= array('result'=>$arr,'error'=>$err);
	return $result;
    }
    
//  name,dname,unit,flag,upos,vals,range,list_values    
    public function set_attributes_details($params)
    {
	$name 	= $params['name'];  
	$dname 	= $params['dname']; 
	$unit 	= $params['unit'];  //  gb,mb,kb
	$flag 	= $params['flag'];  // type flag 1,2,3,4,5
	$upos 	= $params['upos'];   //   pre / post
	$vals 	= $params['vals'];
	$range 	= $params['range'];

        # INSERTING REQUIRED DATA #
        $sql = "INSERT INTO tbl_attribute_master SET attr_name='".$name."',attr_display_name='".$dname."',attr_unit='".$unit."',attr_type_flag='".$flag."',attr_unit_pos='".$upos."',attr_values='".$vals."',attr_range=".$range;
	$res = $this->query($sql);
	if($res)
        { 
            
            $isql="ALTER TABLE `tbl_product_search` ADD ".$name.""; 
            
            if($flag==1 || $flag==6 || $flag==8 )
            {
                $isql.=" VARCHAR(50) NOT NULL DEFAULT '' ";
            }
            else if($flag==2)
            {
                $isql.=" TEXT NOT NULL DEFAULT ''";
            }
            else if($flag==3)
            {
                $isql.=" BIGINT(20) NOT NULL DEFAULT '0'";
            }
            else if($flag==4)
            {
                $isql.=" DECIMAL(10,2) NOT NULL DEFAULT '0.0'";
            }
           else if($flag==6)
            {
                $isql.=" DATE NULL DEFAULT '0000-00-00 00:00:00'";
            }
           else if($flag==5)
            {
                $crtSql="CREATE TABLE $use_list (`id` BIGINT(20) NOT NULL AUTO_INCREMENT,`name` varchar(64) NOT NULL,`isActive` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Not Active', `date_time` datetime NOT NULL COMMENT 'date and time on which it was added', `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'timestamp on which it was updated',`updated_by` varchar(255) NOT NULL COMMENT 'the record was updated by whom',PRIMARY KEY (`id`),KEY `idx_name` (`name`),KEY `idx_isActive` (`isActive`),KEY `idx_date_time` (`date_time`),KEY `idx_update_time` (`update_time`),KEY `idx_updated_by` (`updated_by`))ENGINE=MYISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";             
                $crtres = $obj->query($crtSql);
                $list=$params['list_values'];
                $listvalue = explode(',', $list);
                $len=  sizeof($listvalue);
                $i=0;
                $insertStr;
                while($i<$len)
                { 
                    $insertStr.="(".$listvalue[$i].",1,NOW(),'CMS Team'),";
                    $i++;
                }
               
                $insertStr= trim($insertStr,",");
                $insertSql="INSERT INTO $use_list (name,isActive,date_time,updated_by) VALUES $insertStr";
                $insertres = $this->query($insertSql);
                
                $addsql="INSERT INTO tbl_available_autosuggest_lists (name,display_name,isActive,date_time,updated_by)VALUES(\"$use_list\",\"$tbldname\",1,NOW(),'CMS Team')";
                $addres = $this->query($addsql);

            }
            
            $ires=$this->query($isql);

            if($ires)
            {
                $err=array('Code'=>0,'Msg'=>'Data Insertion Successfull');
                $arr="New Attribute Inserted";
            }
            else
            {
                $err=array('Code'=>0,'Msg'=>'Data Insertion failed');
                $arr="No Attribute Inserted";
            }
        }
        else
        {
            $err=array('Code'=>0,'Msg'=>'Data Insertion failed');
            $arr="No Attribute Inserted";
        }
        $result=array('result'=>$arr,'error'=>$err);
	return $result;
    }
    
    
    public function fetch_attributes_details($params)
    {
        $sql = "SELECT  attr_id,attr_name,attr_display_name,attr_unit,attr_type_flag,attr_unit_pos,attr_values,attr_range
                FROM tbl_attribute_master WHERE attr_id=".$params['attribid'];
	$res    = $this->query($sql);
        $chkres=$this->numRows($res);
        if($chkres>0)
        {
            while($row1 = $this->mysqlFetchArr($res))
            {
                $arr[] = $row1;
            }
            $err=array('Code'=>0,'Msg'=>'Values are fetched successfully');
        }
        else
        {
            $arr="There is no attribute with this id";
            $err=array('Code'=>1,'Msg'=>'Error in fetching data');
        }
        $result=array('result'=> $arr,'error'=>$err);
	return $result;
    }

    public function set_category_mapping($params)
    {
	$aid 		= $params['aid'];
	$dflag 		= $params['dflag'];
	$dpos 		= $params['dpos'];
	$fil_flag 	= $params['fil_flag'];
	$fil_pos 	= $params['fil_pos'];
	$aflag	 	= $params['aflag'];
	$catid 		= $params['catid'];
        $chksql="SELECT count(1) from tbl_attribute_mapping where attribute_id=".$aid." and category_id=".$catid."";
        $ckres=$this->query($chksql);
        $chkres=$this->numRows($ckres);
        if($chkres==0)
        {
        $sql = "INSERT INTO tbl_attribute_mapping SET attribute_id=".$aid.",attr_display_flag =".$dflag.",attr_display_position=".$dpos.",attr_filter_flag = ".$fil_flag.",attr_filter_position=".$fil_pos.",active_flag=".$aflag.",category_id=".$catid;
        $res = $this->query($sql);
            if($res)
            { 
                $arr="Mapping completion successfull";
                $err=array('Code'=>0,'Msg'=>'Data Insert Successfully');
            }
            else
            {
                $arr="Mapping can't be done";
                $err=array('Code'=>1,'Msg'=>'Problem in Insertion');
            }
        }
        else
        {
                $arr="Mapping is done already";
                $err=array('Code'=>1,'Msg'=>'Insert operation not commited');
        }
        $result=array('result'=>$arr,'error'=>$err);
	return $result;
    }
    
    public function fetch_category_mapping($params)
    {
        $mapsql="SELECT 
					*,
					attribute_id 
				FROM 
					tbl_attribute_mapping 
				WHERE 
					category_id=".$params['catid']." 
				AND 
					attr_display_flag = 1 
				AND 
					attr_filter_flag = 1 
				ORDER BY 
					attr_filter_position ASC ";
        $mapres=$this->query($mapsql);
        $cres=$this->numRows($mapres);
        if($cres>0)
        {
            $i=0;
            while($row=$this->fetchData($mapres)) 
            {
				$attributeMap['attrid'][$i]=$row['attribute_id'];
				$i++;
            }
            $atribs=implode(',',$attributeMap['attrid']);
            
            $attrsql="SELECT  
                                attr_id,
                                attr_name,
                                attr_display_name,
                                attr_unit,
                                attr_type_flag,
                                attr_unit_pos,
                                attr_values,
                                attr_range
                      FROM 
                                tbl_attribute_master 
                      WHERE 
                                attr_id IN(".$atribs.")
                      ORDER BY 
                                field(attr_id,".$atribs.") DESC";
            $res = $this->query($attrsql); 
            if($res)
            {   
                while($row1=$this->fetchData($res)) 
                {
                    $attrs['atrribute_id']=$row1['attr_id'];
                    $attrs['attribute_name']=$row1['attr_name'];
                    $attrs['attribute_disp_name']=$row1['attr_display_name'];
                    $attrs['attribute_unit']=$row1['attr_unit'];
                   
                    $flag=$row1['attr_type_flag'];       // FOR GIVING THE NAME OF TYPE FILTER
                 /*   switch($flag)
                    {
                        case 1:
                               $attrs['attribute_num_flag']="textbox";
                               $sql = "select ".$attrs['attribute_name']." from tbl_product_search group by ".$attrs['attribute_name']."";
                               $res = $this->query($sql);
                               break;
                        case 2:
                               $attrs['attribute_num_flag']="textarea";
                               $sql = "select ".$attrs['attribute_name']." from tbl_product_search group by ".$attrs['attribute_name']."";
                               $res = $this->query($sql,1);
                               break;
                        case 3:
                               $attrs['attribute_num_flag']="Numeric";
                               $sql = "select ".$attrs['attribute_name']." from tbl_product_search group by ".$attrs['attribute_name']."";
                               $res = $this->query($sql,1);
                               break;
                        case 4:
                               $attrs['attribute_num_flag']="Decimal";
                               $sql = "select ".$attrs['attribute_name']." from tbl_product_search group by ".$attrs['attribute_name']."";
                               $res = $this->query($sql,1);
                               break;
                        case 5:
                               $attrs['attribute_num_flag']="Dropdown";
                               $sql = "select ".$attrs['attribute_name']." from tbl_product_search group by ".$attrs['attribute_name']."";
                               $res = $this->query($sql,1);
                               break;
                        case 6:
                               $attrs['attribute_num_flag']="Range";
                               $sql = "select min(".$attrs['attribute_name'].") as minval, max(".$attrs['attribute_name'].") as maxval from tbl_product_search";
                               $res = $this->query($sql,1);
                               break;
                        case 7:
                               $attrs['attribute_num_flag']="checkbox";
                                $sql = "select ".$attrs['attribute_name']." from tbl_product_search group by ".$attrs['attribute_name']."";
                                $res = $this->query($sql,1);
                               break;
                        case 8:
                               $attrs['attribute_num_flag']="Radiobutton";
                               $sql = "select ".$attrs['attribute_name']." from tbl_product_search group by ".$attrs['attribute_name']."";
                               $res = $this->query($sql,1);
                               break;
                        case 9:
                               $attrs['attribute_num_flag']="Pre-Defined List";
                               break;
                        default:
                               break;
                    }
                   */ 
                    $attrs['attribtue_unit_pos']=$row1['attr_unit_pos'];
                    
                    $attrs['attribute_values']=$row1['attr_values'];
                    
                    $attrs['attribute_range']=$row1['attr_range'];
                    
                    $attribute[]=$attrs;
                
      
                }
                    $sql="SELECT * from tbl_prodct_search where ";
                    
                    
                    
                    $res=$this->query($sql);
                    
                    
                
                
                
                $arr=array('attributes'=>$attribute);
                $err=array('Code'=>'0','Msg'=>'Values are Fetched');
            }
            else
            {
                $arr="There is no attribute detail availed in attribute table";
                $err=array('Code'=>0,'Msg'=>'Values not found');
            }
        }
        else
        {
            $arr="There is no attribute mapped with category to show result";
            $err=array('code'=>1,'Msg'=>'Error in fetching data');
        }
        $result=array('results'=>$arr,'error'=>$err);
        return $result;
    }
    
    function unset_category_mapping($params) 
    {   
        $sql = "UPDATE tb_attribute_mapping SET active_flag=2 WHERE category_id=".$params['catid']." AND attr_id=".$params['aid'];
	$res = $this->query($sql);
	if($res) 
        { 
	$arr="Value has been cleared from attributes";
        $err=array('code'=>0, 'msg'=>'Data Deleted Successfully');
	}
        $result=array('result'=>$arr,'error'=>$err);
	return $result;
    }

    
}
?>