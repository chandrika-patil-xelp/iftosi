<?php

include APICLUDE.'common/db.class.php';
class site extends DB
{

    function __construct($db)
    {
        parent::DB($db);
    }

    public function sitemapper()
    {
        $sql = "SELECT
                        metal,
                        shape,
                        color,
                        clarity,
                        type,
                        gold_purity,
                        gold_weight,
                        certified,
                        product_id
                 FROM
                        tbl_product_search
                 WHERE
                        active_flag = 1
                 AND
                        complete_flag=1
                 ORDER BY
                        product_id ASC";

        $res = $this->query($sql);
        if($res)
        {
            $datasitemap = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            $data = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
            while($row = $this->fetchData($res))
            {
                  $pid = $row['product_id'];
                  $qry = "SELECT
                                  cat_name
                          FROM
                                  tbl_category_master
                          WHERE
                                  p_catid = 0
                          AND
                                  catid IN (SELECT
                                                          group_concat(category_id)
                                                  FROM
                                                          tbl_product_category_mapping
                                                  WHERE
                                                          product_id = ".$pid."
                                                  )";
                  $resqry = $this->query($qry);
                  $rowCname = $this->fetchData($resqry);
                  $catName = $rowCname['cat_name'];
                  $url = 'http://www.iftosi.com/';
                  if($catName == "Jewellery")
                  {
                    if(!empty($row['metal']))
                    {
                        $url .= $row['metal'].'-';
                    }
                    if(!empty($row['shape']))
                    {
                      $url .= $row['shape'].'-';
                    }
                    if(!empty($row['gold_purity']))
                    {
                      $url .= $row['gold_purity'].'-Carat-';
                    }
                    if(!empty($row['gold_weight']))
                    {
                      $url .= floor($row['gold_weight']).'-Grams-';
                    }
                    if(!empty($row['certified']))
                    {
                      $url .= $row['certified'];
                    }
                    $url .= "/jid-".$row['product_id'];
                  }

                  if($catName == "Bullion")
                  {
                    if(!empty($row['metal']))
                    {
                        $url .= $row['metal'].'-';
                    }
                    if(!empty($row['type']))
                    {
                        $url .= $row['type'].'-';
                    }
                    if(!empty($row['gold_purity']))
                    {
                      $url .= $row['gold_purity'].'-';
                    }
                    if(!empty($row['gold_weight']))
                    {
                      $url .= $row['gold_weight'].'Grams';
                    }
                    $url .= "/bid-".$row['product_id'];
                  }

                  if($catName == "diamond")
                  {
                    if(!empty($row['shape']))
                    {
                      $url .= $row['shape'].'-';
                    }
                    if(!empty($row['color']))
                    {
                        $url .= 'colour-'.$row['color'].'-';
                    }
                    if(!empty($row['clarity']))
                    {
                      $url .= 'clarity-'.$row['clarity'].'-';
                    }
                    if(!empty($row['certified']))
                    {
                      $url .= 'certified-'.$row['certified'];
                    }
                    $url .= "/did-".$row['product_id'];
                  }
                  $data .= '<url>';
                  $data .= '<loc>';
                  $data .= $url;
                  $data .= '</loc>';
                  $data .= '<changefreq>weekly</changefreq>';
                  $data .= '<priority>1.0</priority>';
                  $data .= '</url>';
          } // while ends here
          $data .= '</urlset>';
          $filename = WEBROOT."jewellery.xml";

          //echo $filename;

          $myfile = fopen($filename, "w") or die("Unable to open file");
          fwrite($myfile, $data);
          fclose($myfile);
          // $datasitemap .= '<sitemap>';
          // $datasitemap .= '<loc>http://www.iftosi.com/'.$fname.'</loc>';
          // $datasitemap .= '<lastmod>'.date('c').'</lastmod>';
          // $datasitemap .= '</sitemap>';
        }
        echo "Success";
    }

    public function generalMap()
    {
        global $comm;
        $url = 'http://www.iftosi.com/';
        $links = '';
        $arr = array();

        $urls = APIDOMAIN."index.php?action=getSubCat";
        $res1  = $comm->executeCurl($urls);
        $res2   = $this->subUrls($res1);

        //echo "<pre>";print_r($res1);die;

        $datasitemap = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $data = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        $arr = array(
                      0=>'About-Us',
                      1=>'Team',
                      2=>'Education',
                      3=>'Round',
                      4=>'Princess',
                      5=>'Oval',
                      6=>'Marquise',
                      7=>'Pear',
                      8=>'Cushion',
                      9=>'Emerald',
                      10=>'Asscher',
                      11=>'Heart',
                      12=>'Carat-Weight',
                      13=>'Anatomy',
                      14=>'Jewellery-Tips',
                      15=>'Cut',
                      16=>'Girdle-Size',
                      17=>'Culet-Size',
                      18=>'Polish',
                      19=>'Symmetry',
                      20=>'Heart-And-Arrows',
                      21=>'Color',
                      22=>'Fluorescence',
                      23=>'Clarity',
                      24=>'Certification-And-Grading',
                      25=>'How-GIA-Grades-A-Diamond',
                      26=>'GIA-vs-EGL',
                      27=>'FAQ',
                      28=>'Terms-Of-Service',
                      29=>'Terms-Of-Listing',
                      30=>'Privacy-Policy',
                      31=>'Contact-Us',
                      32=>'Sign-Up',
                      33=>'Vendor-Sign-Up',
                      34=>'Terms-Of-Listing',
                      35=>'jewelley/ct-10001',
                      36=>'diamonds/ct-10000',
                      37=>'bullion/ct-10002',
                      38=>'bullion/coins/ct-10037',
                      39=>'bullion/bars/ct-10002'
                  );
            $resultArr = array_merge($arr,$res2);
                
            //echo "<pre>";print_r($resultArr);die;

            foreach($resultArr as $ky=>$vl)
            {
              $data .= '<url>';
              $data .= '<loc>';
              $data .= $url.$vl;
              $data .= '</loc>';
              $data .= '<changefreq>weekly</changefreq>';
              $data .= '<priority>1.0</priority>';
              $data .= '</url>';
              $data .= "\n";
              
            }
        $data .= '</urlset>';
        $filename = WEBROOT."generalUrl.xml";
        $myfile = fopen($filename, "w") or die("Unable to open file");
        fwrite($myfile, $data);
        fclose($myfile);
        echo "Success";
    }

    public function subUrls($res)
    {
        $jewel = $res['results']['root'][1]['cat_name'];
        $i = 0;
        foreach($res['results']['root'][1]['subcat'] as $key => $val)
        { 
          $dname = preg_replace('/[^a-zA-Z0-9]+/', ' ', $val['cat_name']);
          $dname = ereg_replace("[ \t\n\r]+", " ", $dname);
          $dname = strtolower(str_replace(" ", "-", $dname));
          $link[$i] = strtolower($jewel).'/'.$dname.'/ct-'.$val['catid'];
          $i++;
          foreach($val['subcat'] as $ky => $vl)
          {
            $cname = preg_replace('/[^a-zA-Z0-9]+/', ' ', $vl['cat_name']);
            $cname = ereg_replace("[ \t\n\r]+", " ", $cname);
            $cname = strtolower(str_replace(" ", "-", $cname));
            $link[$i] = strtolower($jewel).'/'.$dname.'/'.$cname.'/ct-'.$vl['catid'];
            $i++;
          }
        }
        return $link;
       
        
    }
    
    
}
