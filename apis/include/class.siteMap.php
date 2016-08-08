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
                  $url = DOMAIN;
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
          $filename = "sitemap/jewellery.xml";
          $myfile = fopen(WEBROOT.$filename, "w") or die("Unable to open file!");
          fwrite($myfile, $data);
          fclose($myfile);
          $datasitemap .= '<sitemap>';
          $datasitemap .= '<loc>http://www.iftosi.com/'.$filename.'</loc>';
          $datasitemap .= '<lastmod>'.date('c').'</lastmod>';
          $datasitemap .= '</sitemap>';
        }
        echo "Success";
    }
}
