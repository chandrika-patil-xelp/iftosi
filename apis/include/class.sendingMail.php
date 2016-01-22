<?php

include APICLUDE.'common/db.class.php';
class sendingMail extends DB
{

    function __construct($db)
    {
        parent::DB($db);
    }

        public function sendMail($params)
        {
          global $comm;
            error_reporting(E_ALL);
            $subject ='Email testing IFtoSI';

            $str = "";
            $str .="<html>";
            $str .="<head>";
            $str .="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
            $str .="<meta name='viewport' content='width=device-width, user-scalable=no' >";
            $str .="<title>Dispute</title>";
            $str .="</head>";
            $str .="<body style='margin:0; padding: 0; background-color: #0090a5;'>";
            $str .="<center>";
            $str .="<div style='text-align: center; height: auto; font-size: 1em; margin:0; max-width: 600px; letter-spacing: -0.02em; color:#333; padding: 15px; -webkit-font-smoothing: antialiased;font-family: Helvetica, verdana, sans-serif-condensed, Tahoma;'>";
            $str .="<div style='height: auto; width:100%; padding-bottom: 15px;'><img src='""assets/images/emailimg/nafex.png' style='width:55%; max-width: 250px;'></div>";
            $str .="<div style='height: auto; border-radius: 15px; border: 1px solid #e6e6e6; box-shadow: 0 0 30px 5px rgba(0,0,0,0.4); padding: 25px 6%;background: #fff;'>";
            $str .="<div style='width:100%;'>";
            $str .="<center>";
            $str .="</center>";
            $str .="<div style='padding:25px 0 15px 0; line-height: 22px;'>";
            $str .="<div style='font-size:1.3em; color:#91bf34; line-height: 30px;'>Dispute No.:DIS607</div>";
            $str .="<span style='font-weight: bold;'>Investoptima Equity Consultanta Pvt. Ltd</span><br>";
            $str .="<span>Pune - Dhawal Tabib</span>";
            $str .="</div>";
            $str .="<div style='padding: 15px 0;color:#666; letter-spacing:0em; font-size: 0.9em;border-top: 1px solid #e6e6e6;'>";
            $str .="<div style='padding: 10px 0; border-radius: 5px; background: #e6e6e6;'>Date: <b>January 18, 2015 - 22:03</b></div>";
            $str .="<div style='padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>customer name</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: capitalize; color: #0090a5;'>DHAWAL TABIB </div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; width:48%;'>Mobile Number</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; text-transform: capitalize;'><a href='tel:+919923288125 ' style='text-decoration: none; color:#0090a5;'>+91 9980051525</a></div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>Area/City</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; vertical-align: middle; width:49%; text-transform: capitalize; color: #0090a5;'>";
            $str .="<div>Dhawal Tabib,</div>";
            $str .="<div>Pune</div>";
            $str .="</div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>transaction type</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: capitalize; color: #0090a5;'>retail</div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>Transaction No: (NBC OR NNP)</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: uppercase; color: #0090a5;'>NBC001</div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>Buy/Sell (CUSTOMER BUYING/SELLING)</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: capitalize; color: #0090a5;'>Buy</div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>currency type</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: uppercase; color: #0090a5;'>JPY</div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>quantity:(AS PER PORTAL)</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: capitalize; color: #0090a5;'>18400</div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>quantity:(AS PER Deal close)</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: capitalize; color: #0090a5;'>18400</div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>rate closed with customer</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: uppercase; color: #0090a5;'>0.5425 / JPY</div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>nafex commission charge (ps.)</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: uppercase; color: #0090a5;'>0.10 / JPY</div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>Dispute of quantity</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: uppercase; color: #0090a5;'>-2468</div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 10px 0;'>";
            $str .="<div style='text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;'>Dispute Registered By</div>";
            $str .="<div style='text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: capitalize; color: #0090a5;'>Irshad Vali</div>";
            $str .="</div>";
            $str .="<div style='padding: 15px 0;background: #91bf34; border-radius: 5px; color:#fff;'>";
            $str .="<div style='text-align: left; font-weight: bold;text-transform: capitalize; display: inline-block; vertical-align: middle;'>total commission refunded INR <span>60</span></div>";
            $str .="</div>";
            $str .="<div style='border-top: 1px solid #e6e6e6;padding: 20px 0 10px 0; color:#888; line-height: 22px;'>";
            $str .="<div style='text-align: left; text-transform: capitalize; vertical-align: middle;'>Note:</div>";
            $str .="<div style='text-align: left; vertical-align: middle;'>P.S: Kindly check your e-account Balance and recharge in order to constantly receive Leads. If any issues with the above, please raise a Dispute through our 'Dispute Resolution Module' or Call on <a href='tel:+919111111119' style='text-decoration: none; color:#888; font-weight: bold;'>+91 91 111 111 19</a> </div>";
            $str .="</div>";
            $str .="</div>";
            $str .="<div style='color:#666; font-size: 0.8em;'>- The Nafex.com Team</div>";
            $str .="</div>";
            $str .="</div>";
            $str .="</div>";
            $str .="</center>";
            $str .="</body>";
            $str .="</html>";

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <info@iftosi.com>' . "\r\n";

                mail('shitanshu@xelpmoc.in', $subject, $str, $headers);
                $arr = '123 mail sent to shitanshu@xelpmoc.in');
                $err = array('code'=>0,'msg'=>'success in sending mail');

            $result = array('result'=>$arr,'error'=>$err);
            return $result;
        }
}


?>
