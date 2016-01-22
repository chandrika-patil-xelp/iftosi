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
            $subject ='account has been activated';
            $str = $this -> signUp();

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <info@iftosi.com>' . "\r\n";

                mail('shitanshu@xelpmoc.in', $subject, $str, $headers);
                $arr = '123 mail sent to shitanshu@xelpmoc.in';
                $err = array('code'=>0,'msg'=>'success in sending mail');

            $result = array('result'=>$arr,'error'=>$err);
            return $result;
        }
        public function contentDollarRate()
        {
          $str ='';

        }
        public function signUp()
        {
                      $str = "";
                      $str .= '<!DOCTYPE html>';
                      $str .='<html>';
                      $str .= '<head>';
                      $str .='<title>account has been activated</title>';
                      $str .='<meta charset="UTF-8">';
                      $str .='<meta name="viewport" content="width=device-width, initial-scale=1.0">';
                      $str .='</head>';
                      $str .='<body>';
                      $str .='<center>';
                      $str .='<div style="text-align: center; height: auto; font-size: 1em; margin:0; max-width: 500px; letter-spacing: -0.02em; color:#333; padding: 10px; -webkit-font-smoothing: antialiased;font-family: Helvetica, Roboto, verdana, sans-serif-condensed, Tahoma;background-color:#8A0044">';
                      $str .='<div style="height: auto; width:100%; padding-bottom: 15px;"><img src="'.DOMAIN.'tools/img/common/logo.svg" style="width:15%; max-width: 150px;"><span id="logoTxt" class="" style="color: rgb(255, 255, 255); left: 20px; bottom: 20px;"></div>';
                      $str .='<div style="height: auto; border-radius: 15px; padding: 15px 4%;background: #fff;">';
                      $str .='<div style="width:100%;">';
                      $str .='<center>';
                      $str .='<div style="width:auto; vertical-align: top; height: auto; display: inline-block; text-transform: capitalize; line-height: 30px; padding:10px 20px; letter-spacing: -0.03em; font-weight: normal; text-align: center; font-size: 16px; border-radius: 40px; background-color:#8A0044; color: #fff; border: 2px solid #fff"><b>Ankur Gala</b></div>';
                      $str .='<div style="padding: 0; line-height: 1.2em; padding: 10px 0 0 0 ; color:#666; font-size: 1.6em; letter-spacing: -0.03em; font-weight: 200;">Welcome to <b>IftoSi.com!</b></div>';
                      $str .='<div><span style="font-size:0.9em; color:#d00000;padding: 5px 10px;background: #8A0044; color:#fff; letter-spacing: 0em;line-height: 2em;"><b>the smartest online platform </b></span></div>';
                      $str .='<div style="padding-bottom: 15px; "><span style="font-size:0.9em; color:#d00000; padding: 5px 10px;background: #8A0044; color:#fff; letter-spacing: 0em;"><b>to exchange your Foreign Currency.</b></span></div>';
                      $str .='</center>';
                      $str .='<div style="padding:15px 0;">';
                      $str .='<div style="color:#666; font-size: 1em;  line-height: 1.2em; padding-bottom: 25px; ">Your <b>account has been activated</b> with the following credentials </div>';
                      $str .='<div style="display:inline-block; vertical-align: top; width:49%; border-right: 1px solid #ccc;">';
                      $str .='<div style="padding: 0;font-size: 0.7em;  line-height: 1em; color: #8A0044; text-transform: uppercase; font-weight: bold; ">USERNAME</div>';
                      $str .='<div style="color:#666; padding: 0 0 5px 0; font-size: 1.4em; line-height: 30px;"><b>9980051525</b></div>';
                      $str .='</div>';
                      $str .='<div style="display:inline-block; vertical-align: top; width:49%;">';
                      $str .='<div style="padding: 0;font-size: 0.7em;  line-height: 1em; color: #8A0044; text-transform: uppercase; font-weight: bold; ">PASSWORD</div>';
                      $str .='<div style="color:#666; padding: 0 0 0px 0; font-size: 1.4em; line-height: 30px;"><b>479852</b></div>';
                      $str .='</div>';
                      $str .='</div>';
                      $str .='<div style="padding:15px 0; border-top: 1px solid #e6e6e6; text-align: left;">';
                      $str .='<div style="color:#666; font-size: 1.4em; width:100%;color: #f49712; text-align: left; line-height: 1.6em; letter-spacing: -0.03em;">Quickly log into your account to:</div>';
                      $str .='<div style="width:97%; padding-left: 3%;">';
                      $str .='<div style="color:#666; font-size: 0.9em;  line-height: 1.7em;"><b>- Track your current order.</b></div>';
                      $str .='<div style="color:#666; font-size: 0.9em;  line-height: 1.7em;"><b>- Avail various deals & discounts.</b></div>';
                      $str .= '<div style="color:#666; font-size: 0.9em;  line-height: 1.7em;"><b>- View your previous transaction history.</b></div>';
                      $str .='<div style="color:#666; font-size: 0.9em;  line-height: 1.7em;"><b>- Save time while placing a new request.</b></div>';
                      $str .='</div>';
                      $str .='<center style="padding: 15px 0">';
                      $str .='<div style="display:inline-block; vertical-align: middle; height: auto; width:32%;"></div>';
                      $str .='<div style="display:inline-block; vertical-align: middle; height: auto; width:32%;"></div>';
                      $str .='<div style="display:inline-block; vertical-align: middle; height: auto; width:32%;"></div>';
                      $str .='<a href="http://www.IftoSi.com"><div style="height: auto; width:150px; padding-top: 15px;"><img src="http://www.digivac.com/wp-content/uploads/2015/01/appstore_button_google.png" style="width:100%;"></div></a>';
                      $str .='</center>';
                      $str .='</div>';
                      $str .='<div style="color:#666; font-size: 0.8em;">- The IftoSi.com Team</div>';
                      $str .='</div>';
                      $str .='</div>';
                      $str .='</div>';
                      $str .='</center>';
                      $str .='</body>';
                      return $str;

        }
}


?>
