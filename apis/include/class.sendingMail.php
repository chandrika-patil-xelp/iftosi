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
            //$subject ='account has been activated';
            // $str = $this -> signUp();

              $subject ='Change of password';
              $str = $this->forgot_password1();

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
                      $str .= '<div style="height: auto; width:100%; padding-bottom: 15px;"><img src="http://admin.mailigen.com/upload/user/76251/images/depositphotos_15838001_original_2.jpg" style="width:55%; max-width: 150px;"></div>';
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
            $str .='<!DOCTYPE html>';
                    '<html>';
                    '<head>';
                     '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
        '<meta name="viewport" content="width=device-width, user-scalable=no" >';
        '<title>Dispute</title>'
    '</head>'
    '<body style="margin:0; padding: 0; background-color: #0090a5;">'
    <center>
        <div style="text-align: center; height: auto; font-size: 1em; margin:0; max-width: 600px; letter-spacing: -0.02em; color:#333; padding: 15px; -webkit-font-smoothing: antialiased;font-family: Helvetica, verdana, sans-serif-condensed, Tahoma;">
            <div style="height: auto; width:100%; padding-bottom: 15px;"><img src="http://www.nafex.co/assets/images/icons/logo_nafex.svg" style="width:55%; max-width: 250px;"></div>
            <div style="height: auto; border-radius: 15px; border: 1px solid #e6e6e6; box-shadow: 0 0 30px 5px rgba(0,0,0,0.4); padding: 25px 6%;background: #fff;">
                <div style="width:100%;">
                    <center>
                        <div style="width:auto; vertical-align: top; height: 30px; display: inline-block; text-transform: uppercase; line-height: 30px; padding:10px 20px; letter-spacing: -0.03em; font-weight: bold; text-align: center; font-size: 20px; border-radius: 40px; background: #91bf34; color: #fff; box-shadow: 0 0 20px 0px rgba(0,0,0,0.3); border: 2px solid #fff"><img src="http://www.nafex.co/assets/images/icons/thumbs3.svg" style="height:30px;"> STATUS</div>
                    </center>
                    <div style="padding:25px 0 15px 0; line-height: 22px;">

                        <div style="font-size:1.3em; color:#91bf34; line-height: 30px;">Dispute No.:DIS607</div>
                        <span style="font-weight: bold;">Investoptima Equity Consultanta Pvt. Ltd</span><br>
                        <span>Pune - Dhawal Tabib</span>
                    </div>
                    <div style="padding: 15px 0;color:#666; letter-spacing:0em; font-size: 0.9em;border-top: 1px solid #e6e6e6;">
                        <div style="padding: 10px 0; border-radius: 5px; background: #e6e6e6;">Date: <b>January 18, 2015 - 22:03</b></div>
                        <div style="padding: 10px 0;">
                            <div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;">Vendor name</div>
                            <div style="text-align: left; font-weight: bold; display: inline-block; width:49%; vertical-align: middle; text-transform: capitalize; color: #0090a5;">Devilal Jewellers</div>
                        </div>
                        <div style="border-top: 1px solid #e6e6e6;padding: 10px 0">
                            <div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; width:48%;">Mobile Number</div>
                            <div style="text-align: left; font-weight: bold; display: inline-block; width:49%; text-transform: capitalize;"><a href="tel:+919923288125 " style="text-decoration: none; color:#0090a5;">+91 8080212121</a></div>
                        </div>
                        <div style="border-top: 1px solid #e6e6e6;padding: 10px 0;">
                            <div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:48%;">Area/City</div>
                            <div style="text-align: left; font-weight: bold; display: inline-block; vertical-align: middle; width:49%; text-transform: capitalize; color: #0090a5;">
                                <div>Dhawal Tabib,</div>
                                <div>Pune</div>
                            </div>
                        </div>
                        <div style="border-top: 1px solid #e6e6e6;padding: 20px 0 10px 0; color:#888; line-height: 22px;">
                            <div style="text-align: left; vertical-align: middle;">P.S: Kindly check your e-account Balance and recharge in order to constantly receive Leads. If any issues with the above, please raise a Dispute through our 'Dispute Resolution Module' or Call on <a href="tel:+919111111119" style="text-decoration: none; color:#888; font-weight: bold;">+91 91 111 111 19</a> </div>
                        </div>
                    </div>
                    <div style="color:#666; font-size: 0.8em;">- The IftoSi.com Team</div>
                </div>
            </div>
        </div>
    </center>
</body>
</html>



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
