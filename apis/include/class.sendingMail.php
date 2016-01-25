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
            // $subject ='account has been activated';
            //  $str = $this -> signUp();
              //
              // $subject ='Change of password';
              // $str = $this->forgot_password1();
              $subject ='Welcome';
              $str = $this->vendor_cont();

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
        public function forgot_password1()
        {
              $str = "";
                      $str .= '<!DOCTYPE html>';
                      $str .= '<html>';
                      $str .= ' <head>';
                      $str .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
                      $str .= '<meta name="viewport" content="width=device-width, user-scalable=no" >';
                      $str .= '<title>Our partner will call you</title>';
                      $str .= '</head>';
                      $str .= '<body style="margin:0; padding: 0; background-color: #8A0044;">';
                      $str .= '<center>';
                      $str .= '<div style="text-align: center; height: auto; font-size: 1em; margin:0; max-width: 500px; letter-spacing: -0.02em; color:#333; padding: 15px; -webkit-font-smoothing: antialiased;font-family: Helvetica, verdana, sans-serif-condensed, Tahoma;">';
                      $str .= '<div style="height: auto; width:100%; padding-bottom: 15px;"><img src="http://beta.xelpmoc.in/iftosi/tools/img/common/logo2.png" style="width:30%; max-width: 150px;height:110px"></div>';
                      $str .= '<div style="height: auto; border-radius: 15px; border: 1px solid #e6e6e6; box-shadow: 0 0 30px 5px rgba(0,0,0,0.4); padding: 25px 6%;background: #fff;">';
                      $str .= '<div style="width:100%;">';
                      $str .= '<center>';
                      $str .= ' <div style="width:auto; vertical-align: top; height: 30px; display: inline-block; text-transform: uppercase; line-height: 30px; padding:10px 20px; letter-spacing: -0.03em; font-weight: bold; text-align: center; font-size: 20px; border-radius: 40px; background: #91bf34;; color: #fff; box-shadow: 0 0 20px 0px rgba(0,0,0,0.3); border: 2px solid #fff">Dear Rishab E</div>';
                      $str .= '</center>';
                      $str .= '<div style="padding:25px 0; line-height: 22px;">';
                      $str .= '<span>The link to change your password is as follows</span>';
                      $str .= '<div style="font-size: 1.4em; color:#8A0044; line-height: 30px;"><a style="color:#8A0044;"href="http://iftosi.com/FP-345678">IftoSi</a></div>';
                      $str .= '</div>';
                      $str .= '<div style="padding: 25px 0 0 0; line-height: 22px; border-top: 1px solid #e6e6e6;">For any assistance</div>';
                      $str .= '<div style=" padding: 0 0 25px 0;  line-height: 30px;">Call 022 - 32623263 </div>';
                      $str .= '<div style=" padding: 0 0 25px 0;  line-height: 30px;">Email info@iftosi.com</div>';
                      $str .= '<div style="color:#666; font-size: 0.8em;">- The IftoSi.com Team</div>';
                      $str .= '</div>';
                      $str .= '</div>';
                      $str .= '</div>';
                      $str .= '</center>';
                      $str .= '</body>';
                      $str .= '</html>';
                      return $str;
        }
        function vendor_cont()
        {
          $str = "";
           $str .= '<!DOCTYPE html>';
           $str .= '<html>';
           $str .= '<head>';
            $str .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
            $str .= '<meta name="viewport" content="width=device-width, user-scalable=no" >';
            $str .= '<title></title>';
            $str .='</head>';
            $str .='<body style="margin:0; padding: 0; background-color: #8A0044;">';
            $str .= '<center>';
            $str .= '<div style="text-align: center; height: auto; font-size: 1em; margin:0; max-width: 600px; letter-spacing: -0.02em; color:#333; padding: 15px; -webkit-font-smoothing: antialiased;font-family: Helvetica, verdana, sans-serif-condensed, Tahoma;">';
            $str .= '<div style="height: auto; width:100%; padding-bottom: 15px;"><img src="http://beta.xelpmoc.in/iftosi/tools/img/common/logo2.png" style="width:55%; max-width: 250px;"></div>';
            $str .= '<div style="height: auto; border-radius: 15px; border: 1px solid #e6e6e6; box-shadow: 0 0 30px 5px rgba(0,0,0,0.4); padding: 25px 6%;background: #fff;">';
            $str .= '<div style="width:100%;">';
            $str .= '<div style="padding:25px 0 15px 0; line-height: 22px;">';
            $str .= '<span style="font-family: Helvetica,verdana,sans-serif-condensed,Tahoma;color: #888;">Dear Rishab , Thank you for showing interest in the product you have enquired. The contact details of the vendor are:</span>';
            $str .= '</div>';
            $str .= '<div style="color:#666; letter-spacing:0em; font-size: 0.9em;">';
            $str .= '<div style="padding: 10px 0;border-top: 1px solid #e6e6e6;">';
            $str .= '<div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:250px;">Vendor name</div>';
            $str .= '<div style="text-align: left; font-weight: bold; display: inline-block; width:250px; vertical-align: middle; text-transform: capitalize; color: #8A0044;">Devilal Jewellers</div>';
            $str .= '</div>';
            $str .= '<div style="padding: 10px 0;border-top: 1px solid #e6e6e6;">';
            $str .= '<div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:250px;">City</div>';
            $str .= '<div style="text-align: left; font-weight: bold; display: inline-block; width:250px; vertical-align: middle; text-transform: capitalize; color: #8A0044;">Pune</div>';
            $str .= '</div>';
            $str .= '<div style="padding: 10px 0;border-top: 1px solid #e6e6e6;display: inline-block;">';
            $str .= '<div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:250px;">Area1</div>';
            $str .= '<div style="text-align: left; font-weight: bold; display: inline-block; width:250px; vertical-align: middle; text-transform: capitalize; color: #8A0044;">Dhawal Tabib</div>';
            $str .= '</div>';
            $str .= '<div style="border-top: 1px solid #e6e6e6;padding: 10px 0">';
            $str .= '<div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; width:250px; vertical-align: middle">Mobile Number</div>';
            $str .= '<div style="text-align: left; font-weight: bold; display: inline-block; width:250px; text-transform: capitalize; vertical-align: middle"><a href="tel:+919923288125 " style="text-decoration: none; color:#8A0044;">+91 8080212121</a></div>';
            $str .= '</div>';
            $str .= '<div style="border-top: 1px solid #e6e6e6;padding: 10px 0">';
            $str .= '<div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; width:250px; vertical-align: middle">Landline Number</div>';
            $str .= '<div style="text-align: left; font-weight: bold; display: inline-block; width:250px; text-transform: capitalize; vertical-align: middle">';
            $str .= '<a href="tel:+0222-32623263 " style="text-decoration: none; color:#8A0044;">+0222-32623263</a>';
            $str .= '<a href="tel:+0222-32623263 " style="text-decoration: none; color:#8A0044;">+0222-32623263</a>';
            $str .= '<a href="tel:+0222-32623263 " style="text-decoration: none; color:#8A0044;">+0222-32623263</a>';
            $str .= '<a href="tel:+0222-32623263 " style="text-decoration: none; color:#8A0044;">+0222-32623263</a>';
            $str .= '</div>';
            $str .= '</div>';
            $str .= '<div style="padding: 10px 0;border-top: 1px solid #e6e6e6;">';
            $str .= '<div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:250px;">Email</div>';
            $str .= '<div style="text-decoration:none;text-align: left; font-weight: bold; display: inline-block; width:250px; vertical-align: middle;  color: #8A0044;">silentmajority[at]xelpmoc[dot]in</div>';
            $str .= '</div>';
            $str .= '</div>';
            $str .= '</div>';
            $str .= '<div style="border-top: 1px solid #e6e6e6;padding: 20px 0 10px 0; color:#888; line-height: 22px;">';
            $str .= '<div style="font-size:13px;width:250px;text-align: left; vertical-align: middle;padding-bottom:5px;">For any Assistance call: <a href="tel:+022-32623263" style="text-decoration: none; color:#888; font-weight: bold;">+022-32623263</a> </div>';
            $str .= '<div style="font-size:13px;width:250px;float:left;text-align: left; vertical-align: middle;padding-bottom:5px;padding-left:10px;">Email: <a href="" style="text-decoration: none; color:#888; font-weight: bold;">info@iftosi.com</a> </div>';
            $str .= '</div>';
            $str .= '<div style="color:#666; font-size: 0.8em;">- The IftoSi.com Team</div>';
            $str .= '</div>';
            $str .= '</div>';
            $str .= '</div>';
            $str .= '</div>';
            $str .= '</center>';
            $str .= '</body>';
            $str .= '</html>';

          return $str;
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
                      $str .='</center>';
                      $str .='<div style="padding:15px 0;">';
                      $str .='<div style="display:inline-block; vertical-align: top; width:49%;">';
                      $str .='</div>';
                      $str .='</div>';
                      $str .='<div style="padding:15px 0; border-top: 1px solid #e6e6e6; text-align: left;">';
                      $str .='<div style="color:#666; font-size: 1.4em; width:100%;color: #f49712; text-align: left; line-height: 1.6em; letter-spacing: -0.03em;">Quickly log into your account to:</div>';
                      $str .='<div style="width:97%; padding-left: 3%;">';
                      $str .='<div style="font-family: Helvetica, Roboto, verdana, sans-serif-condensed, Tahoma;color:#666; font-size: 0.9em;  line-height: 1.7em;"><b>- Increase your reach to a wider range of customers.</b></div>';
                      $str .='<div style="font-family: Helvetica, Roboto, verdana, sans-serif-condensed, Tahoma;color:#666; font-size: 0.9em;  line-height: 1.7em;"><b>-Kindly log on to <a href="iftosi.com" style="text-decoration:none;color:#8A0044">iftosi.com</a> to upload your products.</b></div>';                      $str .='</div>';
                      $str .='<center style="padding: 15px 0">';
                      $str .='<div style="display:inline-block; vertical-align: middle; height: auto; width:32%;"></div>';
                      $str .='<div style="display:inline-block; vertical-align: middle; height: auto; width:32%;"></div>';
                      $str .='<div style="display:inline-block; vertical-align: middle; height: auto; width:32%;"></div>';
                      $str .='</center>';
                      $str .='</div>';
                      $str .='<div style="border-top: 1px solid #e6e6e6;padding: 20px 0 10px 0; color:#888; line-height: 22px;">';
                      $str .= '<div style="font-size:13px;width:48%;float:left;text-align: left; vertical-align: middle;padding-bottom:5px;">For any Assistance call: <a href="tel:+022-32623263" style="text-decoration: none; color:#888; font-weight: bold;">+022-32623263</a> </div>';
                      $str .= '<div style="font-size:13px;width:48%;float:left;text-align: left; vertical-align: middle;padding-bottom:5px;padding-left:10px;">Email: <a href="" style="text-decoration: none; color:#888; font-weight: bold;">info@iftosi.com</a> </div>';
                      $str .= '</div>';
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
