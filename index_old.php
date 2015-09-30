<?php
    $params = array_merge($_GET, $_POST);
    $case = (!empty($params['case']) && !stristr($params['case'], 'null') && !stristr($params['case'], 'null')) ? trim(urldecode($params['case'])) : '';

    $db = array();
    include 'db.class.php';
    if(stristr($_SERVER['HTTP_HOST'], 'localhost') || stristr($_SERVER['HTTP_HOST'], '.xelpmoc.com'))
    {
        $db['localhost'] = array('localhost', 'root', '', 'db_karan');
    }
    else
    {
        $inival = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/b2e_con.ini',true);
        $server	= $inival['db_product'];
        $db['localhost'] = array($server['ip'],$server['user'],$server['pwd'],'db_karan');
    }

    $cfrm = (!empty($params['cfrm']) && !stristr($params['cfrm'], 'null') && !stristr($params['cfrm'], 'undefined')) ? trim($params['cfrm']) : '';
    $cto = (!empty($params['cto']) && !stristr($params['cto'], 'null') && !stristr($params['cto'], 'undefined')) ? trim($params['cto']) : '';
    $pfrm = (!empty($params['pfrm']) && !stristr($params['pfrm'], 'null') && !stristr($params['pfrm'], 'undefined')) ? trim($params['pfrm']) : '';
    $pto = (!empty($params['pto']) && !stristr($params['pto'], 'null') && !stristr($params['pto'], 'undefined')) ? trim($params['pto']) : '';
    $ccla = (!empty($params['ccla']) && !stristr($params['ccla'], 'null') && !stristr($params['ccla'], 'undefined')) ? trim($params['ccla']) : '';
    $shp = (!empty($params['shp']) && !stristr($params['shp'], 'null') && !stristr($params['shp'], 'undefined')) ? trim($params['shp']) : '';

    $con = new DB($db['localhost']);
    switch ($case)
    {
        case 'home':
            include 'view/index.html';
            break;
        case 'results':
            include 'view/results.html';
            break;
        
        case 'shape':
            $shp = $params['shp'];
            $shp = str_replace(',', "','", $shp);
            $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data WHERE cut in ('$shp')";
            $res = $con->query($sql);
            $resp = array();
            if($res)
            {
                $row = $con->fetchData($res);
                if($row)
                {
                    $cfrm = $row['Min_carats'];
                    $cto = $row['Max_carats'];
                    $pfrm = $row['min_price'];
                    $pto = $row['max_price'];
                }
            }
            $part = $part1 = $part2 = "";
            if($pfrm && $pto)
                $part = " and price >= $pfrm AND price <= $pto ";

            if($cfrm && $cto)
                $part1 = " and carats >= $cfrm AND carats <= $cto ";

            if($ccla)
                $part2 = " and cla in ('".str_replace(",","','",trim($ccla,','))."') ";

            if($shp)
                $sql_rng = "SELECT * FROM tbl_sort_data WHERE cut in ('$shp') $part $part1 $part2 order by barcode";
            else
                $sql_rng = "SELECT * FROM tbl_sort_data WHERE 1 $part $part1 $part2 order by barcode";

            $sql_rng .= " LIMIT 0, 50;";

            $res_rng = $con->query($sql_rng);

            if($res_rng)
            {
                $i=0;
                while($row_res = $con->fetchData($res_rng))
                {
                    $barcode[] = $row_res['barcode'];
                    //if($i<50)
                        $resp[] = $row_res;
                    $i++;
                }

                $inbar = "'".implode("','",$barcode)."'";
                $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data where barcode in ($inbar);";
                $res = $con->query($sql);
                if($res)
                {
                    $row = $con->fetchData($res);
                    if($row)
                    {
                        $min_carats = $row['Min_carats'];
                        $max_carats = $row['Max_carats'];
                        $min_price = $row['min_price'];
                        $max_price = $row['max_price'];
                    }
                }

                $sql = "SELECT UPPER(cla) as cla, count(*) as cnt FROM `tbl_sort_data` where cla is not null and barcode in ($inbar) group by cla order by cnt desc";
                $res = $con->query($sql);

                if($res)
                {
                    while($row = $con->fetchData($res))
                    {
                        $cla[] = $row['cla'].'@'.$row['cnt'];
                    }
                }
                if(count($cla))
                    $cla = implode('|',$cla);
            }
            $result = array('results' => $resp, 'min_carats' => $min_carats, 'max_carats' => $max_carats, 'min_price' => $min_price, 'max_price' => $max_price, 'cla '=> $cla, 'cut '=> $shp, 'error' => 0, 'message' => 'success');
            break;
        case 'clarity':
            $ccla = $params['ccla'];
            $ccla = str_replace(',', "','", $ccla);
            $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data WHERE cla in ('$ccla')";
            $res = $con->query($sql);
            $resp = array();
            if($res)
            {
                $row = $con->fetchData($res);
                if($row)
                {
                    $cfrm = $row['Min_carats'];
                    $cto = $row['Max_carats'];
                    $pfrm = $row['min_price'];
                    $pto = $row['max_price'];
                }
            }
            
            $part = $part1 = $part2 = "";
            if($pfrm && $pto)
                $part = " and price >= $pfrm AND price <= $pto ";

            if($cfrm && $cto)
                $part1 = " and carats >= $cfrm AND carats <= $cto ";

            if($ccla)
                $part2 = " and cla in ('$ccla') ";
                $sql_rng = "SELECT * FROM tbl_sort_data WHERE 1 $part $part1 $part2 order by barcode";
                
            $sql_rng .= " LIMIT 0, 50;";
            $res_rng = $con->query($sql_rng);
            if($res_rng)
            {
                $i=0;
                while($row_res = $con->fetchData($res_rng))
                {
                    $barcode[] = $row_res['barcode'];
                    //if($i<50)
                        $resp[] = $row_res;
                    $i++;
                }

                $inbar = "'".implode("','",$barcode)."'";
                $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data where barcode in ($inbar);";
                $res = $con->query($sql);
                if($res)
                {
                    $row = $con->fetchData($res);
                    if($row)
                    {
                        $min_carats = $row['Min_carats'];
                        $max_carats = $row['Max_carats'];
                        $min_price = $row['min_price'];
                        $max_price = $row['max_price'];
                    }
                }

                $sql = "SELECT UPPER(cla) as cla, count(*) as cnt FROM `tbl_sort_data` where cla is not null and barcode in ($inbar) group by cla order by cnt desc";
                $res = $con->query($sql);

                if($res)
                {
                    while($row = $con->fetchData($res))
                    {
                        $cla[] = $row['cla'].'@'.$row['cnt'];
                    }
                }
                if(count($cla))
                    $cla = implode('|',$cla);
            }
            $result = array('results' => $resp, 'min_carats' => $min_carats, 'max_carats' => $max_carats, 'min_price' => $min_price, 'max_price' => $max_price, 'cla '=> $cla, 'cut '=> $shp, 'error' => 0, 'message' => 'success');
            break;
        case 'colour':
            $col = trim($params['ccol']);
            $col = str_replace(',', "','", $col);
            $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data where col in ('$col')";
            $res = $con->query($sql);
            if($res)
            {
                $row = $con->fetchData($res);
                $numrows = $con->numRows($res);
                if(!empty($row['Min_carats']))
                {
                    $cfrm = $row['Min_carats'];
                    $cto = $row['Max_carats'];
                    $pfrm = $row['min_price'];
                    $pto = $row['max_price'];
                }
            }

            $part = $part1 = $part2 = "";
            if($pfrm && $pto)
                $part = " and price >= $pfrm AND price <= $pto ";

            if($cfrm && $cto)
                $part1 = " and carats >= $cfrm AND carats <= $cto ";

            if($ccla)
                $part2 = " and cla in ('".str_replace(",","','",trim($ccla,','))."') ";

            if($col)
                $sql_rng = "SELECT * FROM tbl_sort_data WHERE col in ('$col') $part $part1 $part2 order by barcode";
            else
                $sql_rng = "SELECT * FROM tbl_sort_data WHERE 1 $part $part1 $part2 order by barcode";

            $sql_rng .= " LIMIT 0, 5;";

            $res_rng = $con->query($sql_rng);

            if($res_rng)
            {
                $i=0;
                while($row_res = $con->fetchData($res_rng))
                {
                    $barcode[] = $row_res['barcode'];
                    //if($i<50)
                        $resp[] = $row_res;
                    $i++;
                }

                $inbar = "'".implode("','",$barcode)."'";
                $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data where barcode in ($inbar);";
                $res = $con->query($sql);
                if($res)
                {
                    $row = $con->fetchData($res);
                    if($row)
                    {
                        $min_carats = $row['Min_carats'];
                        $max_carats = $row['Max_carats'];
                        $min_price = $row['min_price'];
                        $max_price = $row['max_price'];
                    }
                }

                $sql = "SELECT UPPER(cla) as cla, count(*) as cnt FROM `tbl_sort_data` where cla is not null and barcode in ($inbar) group by cla order by cnt desc";
                $res = $con->query($sql);

                if($res)
                {
                    while($row = $con->fetchData($res))
                    {
                        $cla[] = $row['cla'].'@'.$row['cnt'];
                    }
                }
                if(count($cla))
                    $cla = implode('|',$cla);
            }
            $result = array('results' => $resp, 'min_carats' => $min_carats, 'max_carats' => $max_carats, 'min_price' => $min_price, 'max_price' => $max_price, 'cla '=> $cla, 'col '=> $col, 'error' => 0, 'message' => 'success');
            break;
        case 'carats':
            $add = ($cfrm*10)/100;
            $cfrm = $cfrm-$add;
            $cto = $cfrm+$add;

            $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data where carats >= $cfrm AND carats <= $cto";
            $res = $con->query($sql);
            if($res)
            {
                $row = $con->fetchData($res);
                $numrows = $con->numRows($res);
                if(!empty($row['Min_carats']))
                {
                    $cfrm = $row['Min_carats'];
                    $cto = $row['Max_carats'];
                    $pfrm = $row['min_price'];
                    $pto = $row['max_price'];
                }
            }

            $part = $part1 = $part2 = '';
            if($pfrm && $pto)
                $part = " and price >= $pfrm AND price <= $pto ";

            if($_GET['ccol'])
                $part1 = " and col in ('".str_replace(",","','",trim($_GET['ccol'],','))."') ";

            if($_GET['ccla'])
                $part2 = " and cla in ('".str_replace(",","','",trim($_GET['ccla'],','))."') ";

            $sql_rng = "SELECT * FROM tbl_sort_data WHERE carats >= $cfrm AND carats <= $cto $part $part1 $part2 and lotno is not null order by barcode";
            $res_rng = $con->query($sql_rng);

            if($res_rng)
            {
                $i=0;
                $resp = $barcode = array();
                while($row_res = $con->fetchData($res_rng))
                {
                    $barcode[] = $row_res['barcode'];
                    //if($i<50)
                        $resp[] = $row_res;
                    $i++;
                }

                $inbar = '';
                if(count($barcode))
                    $inbar = "'".implode("','",$barcode)."'";

                $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data where barcode in ($inbar);";
                $res = $con->query($sql);
                $min_carats = $max_carats = $min_price = $max_price = 0;
                if($res)
                {
                    $row = $con->fetchData($res);
                    if($row)
                    {
                        $min_carats = $row['Min_carats'];
                        $max_carats = $row['Max_carats'];
                        $min_price = $row['min_price'];
                        $max_price = $row['max_price'];
                    }
                }

                $sql = "SELECT UPPER(cla) as cla, count(*) as cnt FROM `tbl_sort_data` where cla is not null and barcode in ($inbar) group by cla order by cnt desc";
                $res = $con->query($sql);

                $cla = array();
                if($res)
                {
                    while($row = $con->fetchData($res))
                    {
                        $cla[] = $row['cla'].'@'.$row['cnt'];
                    }
                }
                if(count($cla))
                    $cla = implode('|',$cla);

                $sql = "SELECT UPPER(col) as col, count(*) as cnt FROM `tbl_sort_data` where col is not null and barcode in ($inbar) group by col order by cnt desc";
                $res = $con->query($sql);

                $col = array();
                if($res)
                {
                    while($row = $con->fetchData($res))
                    {
                        $col[] = $row['col'].'@'.$row['cnt'];
                    }
                }
                if(count($col))
                    $col = implode('|',$col);
            }

            $result = array('results' => $resp, 'min_carats' => $min_carats, 'max_carats' => $max_carats, 'min_price' => $min_price, 'max_price' => $max_price, 'cla' => $cla, 'col' => $col, 'error' => 0, 'message' => 'success');
            break;
        case 'price':
            $add = ($cfrm*10)/100;
            $cfrmadd = $cfrm-$add;
            $ctoadd = $cfrm+$add;

            $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data WHERE price >= $pfrm AND price <= $pto";
            $res = $con->query($sql);
            if($res)
            {
                $row = $con->fetchData($res);
                if($row)
                {
                    $min_carats = $cfrm = $row['Min_carats'];
                    $max_carats = $cto = $row['Max_carats'];
                    $min_price = $pfrm = $row['min_price'];
                    $max_price = $pto = $row['max_price'];
                }
            }

            $part = $part1 = $part2 = '';
            if($cfrmadd && $ctoadd)
                $part = " and carats >= $cfrmadd AND carats <= $ctoadd ";

            if($_GET['ccol'])
                $part1 = " and col in ('".str_replace(",","','",trim($_GET['ccol'],','))."') ";

            if($_GET['ccla'])
                $part2 = " and cla in ('".str_replace(",","','",trim($_GET['ccla'],','))."') ";

            $sql_rng = "SELECT * FROM tbl_sort_data WHERE price >= $pfrm AND price <= $pto $part $part1 $part2 and lotno is not null order by barcode";
            $sql_rng .= ' LIMIT 0, 50;';
            $res_rng = $con->query($sql_rng);

            if($res_rng)
            {
                $i=0;
                $barcode = $resp = array();
                while($row_res = $con->fetchData($res_rng))
                {
                    $barcode[] = $row_res['barcode'];
                    //if($i<50)
                    $row_res['price_format'] = number_format($row_res['price'], 0, '.', ',');
                    $resp[] = $row_res;
                    $i++;
                }

                $inbar = "'".implode("','",$barcode)."'";
                $sql = "SELECT UPPER(cla) as cla, count(*) as cnt FROM `tbl_sort_data` where cla is not null and barcode in ($inbar) group by cla order by cnt desc";
                $res = $con->query($sql);

                $cla = array();
                if($res)
                {
                    while($row = $con->fetchData($res))
                    {
                        $cla[] = $row['cla'].'@'.$row['cnt'];
                    }
                }
                if(count($cla))
                    $cla = implode('|',$cla);

                $sql = "SELECT UPPER(col) as col, count(*) as cnt FROM `tbl_sort_data` where col is not null and barcode in ($inbar) group by col order by cnt desc";
                $res = $con->query($sql);

                $col = array();
                if($res)
                {
                    while($row = $con->fetchData($res))
                    {
                        $col[] = $row['col'].'@'.$row['cnt'];
                    }
                }
                if(count($col))
                    $col = implode('|',$col);
            }

            $result = array('results' => $resp, 'min_carats' => $min_carats, 'max_carats' => $max_carats, 'min_price' => $min_price, 'max_price' => $max_price, 'cla' => $cla, 'col' => $col, 'error' => 0, 'message' => 'success');

            break;
        case 'cut':            
            break;
        case 'polish':
            $pol = $params['pol'];
            $pol = str_replace(',', "','", $pol);
            $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data WHERE pol in ('$pol')";
           // echo $sql;die;
            $res = $con->query($sql);
            $resp = array();
            if($res)
            {
                $row = $con->fetchData($res);
                if($row)
                {
                    $cfrm = $row['Min_carats'];
                    $cto = $row['Max_carats'];
                    $pfrm = $row['min_price'];
                    $pto = $row['max_price'];
                }
            }
            $part = $part1 = $part2 = "";
            if($pfrm && $pto)
                $part = " and price >= $pfrm AND price <= $pto ";

            if($cfrm && $cto)
                $part1 = " and carats >= $cfrm AND carats <= $cto ";

            if($pol)
                $part2 = " and pol in ('$pol') ";
                $sql_rng = "SELECT * FROM tbl_sort_data WHERE 1 $part $part1 $part2 order by barcode";
                
            $sql_rng .= " LIMIT 0, 50;";
            //echo $sql_rng;die;
            $res_rng = $con->query($sql_rng);
            if($res_rng)
            {
                $i=0;
                while($row_res = $con->fetchData($res_rng))
                {
                    $barcode[] = $row_res['barcode'];
                    //if($i<50)
                        $resp[] = $row_res;
                    $i++;
                }

                $inbar = "'".implode("','",$barcode)."'";
                $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data where barcode in ($inbar);";
                $res = $con->query($sql);
                if($res)
                {
                    $row = $con->fetchData($res);
                    if($row)
                    {
                        $min_carats = $row['Min_carats'];
                        $max_carats = $row['Max_carats'];
                        $min_price = $row['min_price'];
                        $max_price = $row['max_price'];
                    }
                }

                $sql = "SELECT UPPER(pol) as pol, count(*) as cnt FROM `tbl_sort_data` where pol is not null and barcode in ($inbar) group by pol order by cnt desc";
                $res = $con->query($sql);

                if($res)
                {
                    while($row = $con->fetchData($res))
                    {
                        $pols[] = $row['pol'].'@'.$row['cnt'];
                    }
                }
                if(count($pols))
                    $pols = implode('|',$pols);
            }
            $result = array('results' => $resp, 'min_carats' => $min_carats, 'max_carats' => $max_carats, 'min_price' => $min_price, 'max_price' => $max_price,'pol '=> $pols, 'cut '=> $shp, 'error' => 0, 'message' => 'success');
            break;
        case 'symmetry':
            $sym = $params['sym'];
            $sym = str_replace(',', "','", $sym);
            $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data WHERE sym in ('$sym')";
            $res = $con->query($sql);
            $resp = array();
            if($res)
            {
                $row = $con->fetchData($res);
                if($row)
                {
                    $cfrm = $row['Min_carats'];
                    $cto = $row['Max_carats'];
                    $pfrm = $row['min_price'];
                    $pto = $row['max_price'];
                }
            }
            $part = $part1 = $part2 = "";
            if($pfrm && $pto)
                $part = " and price >= $pfrm AND price <= $pto ";

            if($cfrm && $cto)
                $part1 = " and carats >= $cfrm AND carats <= $cto ";

            if($sym)
                $part2 = " and pol in ('$sym') ";
                $sql_rng = "SELECT * FROM tbl_sort_data WHERE 1 $part $part1 $part2 order by barcode";
                
            $sql_rng .= " LIMIT 0, 50;";
            //echo $sql_rng;die;
            $res_rng = $con->query($sql_rng);
            if($res_rng)
            {
                $i=0;
                while($row_res = $con->fetchData($res_rng))
                {
                    $barcode[] = $row_res['barcode'];
                    //if($i<50)
                        $resp[] = $row_res;
                    $i++;
                }

                $inbar = "'".implode("','",$barcode)."'";
                $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data where barcode in ($inbar);";
                $res = $con->query($sql);
                if($res)
                {
                    $row = $con->fetchData($res);
                    if($row)
                    {
                        $min_carats = $row['Min_carats'];
                        $max_carats = $row['Max_carats'];
                        $min_price = $row['min_price'];
                        $max_price = $row['max_price'];
                    }
                }

                $sql = "SELECT UPPER(sym) as sym, count(*) as cnt FROM `tbl_sort_data` where sym is not null and barcode in ($inbar) group by sym order by cnt desc";
                $res = $con->query($sql);

                if($res)
                {
                    while($row = $con->fetchData($res))
                    {
                        $syms[] = $row['sym'].'@'.$row['cnt'];
                    }
                }
                if(count($syms))
                    $syms = implode('|',$syms);
            }
            $result = array('results' => $resp, 'min_carats' => $min_carats, 'max_carats' => $max_carats, 'min_price' => $min_price, 'max_price' => $max_price,'sym '=> $syms, 'cut '=> $shp, 'error' => 0, 'message' => 'success');
            break;

            default:
            $sql = "SELECT MIN(carats) as 'Min_carats', MAX(carats) as 'Max_carats', Min(price) as min_price, MAX(price) as max_price FROM tbl_sort_data;";
            $res = $con->query($sql);
            $resp = array();
            if($res)
            {
                $row = $con->fetchData($res);
                if($row)
                {
                    $min_carats = $row['Min_carats'];
                    $max_carats = $row['Max_carats'];
                    $min_price = $row['min_price'];
                    $max_price = $row['max_price'];
                }
            }
            include 'view/home.html';
            break;
    }
    $con->close();
    unset($con);

    if(!empty($case))
    {
       // echo json_encode($result);
        exit;
    }
?>