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
              //
              // $subject ='Change of password';
              // $str = $this->forgot_password1();
              // $subject ='Welcome';
              // $str = $this->vendor_cont();

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
                      $str .= '<div style=" padding: 0 0 25px 0;  line-height: 30px;">Call 91-22-41222241(42) </div>';
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
                     $str .= '<div style="padding: 10px 0;border-top: 1px solid #e6e6e6;display: inline-block;">';
                     $str .= '<div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:250px;">Vendor name</div>';
                     $str .= '<div style="text-align: left; font-weight: bold; display: inline-block; width:250px; vertical-align: middle; text-transform: capitalize; color: #8A0044;">Devilal Jewellers</div>';
                     $str .= '</div>';
                     $str .= '<div style="padding: 10px 0;border-top: 1px solid #e6e6e6;display: inline-block;">';
                     $str .= '<div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:250px;">City</div>';
                     $str .= '<div style="text-align: left; font-weight: bold; display: inline-block; width:250px; vertical-align: middle; text-transform: capitalize; color: #8A0044;">Pune</div>';
                     $str .= '</div>';
                     $str .= '<div style="padding: 10px 0;border-top: 1px solid #e6e6e6;display: inline-block;">';
                     $str .= '<div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; vertical-align: middle; width:250px;">Area1</div>';
                     $str .= '<div style="text-align: left; font-weight: bold; display: inline-block; width:250px; vertical-align: middle; text-transform: capitalize; color: #8A0044;">Dhawal Tabib</div>';
                     $str .= '</div>';
                     $str .= '<div style="border-top: 1px solid #e6e6e6;padding: 10px 0;display:inline-block">';
                     $str .= '<div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; width:250px; vertical-align: middle;">Mobile Number</div>';
                     $str .= '<div style="text-align: left; font-weight: bold; display: inline-block; width:250px; text-transform: capitalize; vertical-align: middle;"><a href="tel:+919923288125 " style="text-decoration: none; color:#8A0044;">+91 8080212121</a></div>';
                     $str .= '</div>';
                     $str .= '<div style="border-top: 1px solid #e6e6e6;padding: 10px 0;display:inline-block">';
                     $str .= '<div style="text-align: left; color:#888; text-transform: capitalize; display: inline-block; width:250px; vertical-align: middle">Landline Number</div>';
                     $str .= '<div style="text-align: left; font-weight: bold; display: inline-block; width:250px; text-transform: capitalize; vertical-align:middle;">';
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
                     $str .= '<div style="font-size:13px;width:250px;text-align: left; vertical-align: middle;padding-bottom:5px; display:inline-block;">For any Assistance call: <a href="tel:+91-22-41222241(42)" style="text-decoration: none; color:#888; font-weight: bold;">+91-22-41222241(42)</a> </div>';
                     $str .= '<div style="font-size:13px;width:250px;float:left;text-align: left; vertical-align: middle;padding-bottom:5px;padding-left:10px; display:inline-block;">Email: <a href="#" style="text-decoration: none; color:#888; font-weight: bold;">info@iftosi.com</a> </div>';
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

                      $str .= "<!DOCTYPE html>";
                      $str .= "<html>";
                      $str .= "<head>";
                      $str .= "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
                      $str .= "<meta name='viewport' content='width=device-width, user-scalable=no' >";
                      $str .= "<title>IFtoSI Sign-Up</title>";
                      $str .= "</head>";
                      $str .= "<body style='margin:0; padding: 0; background-color: #171334; '>";
                      //$str .= "<center>";
                      $str .= "<div style='text-align: center; height: auto; font-size: 1em; margin:0; max-width: 500px; letter-spacing: -0.02em; color:#666;-webkit-font-smoothing: antialiased;font-family: Open Sans, Roboto, Helvetica, Arial;'>";
                      $str .= "<a href='http:iftosi.com'><div style='vertical-align: top; height: auto; display: inline-block; padding:15px 0 15px 0; text-align: center;color: #d00000; text-transform: uppercase'><img src='http://beta.xelpmoc.in/iftosi/tools/img/common/logo.svg' style='width:100%;'></div></a>";
                      $str .= "<div style='height: auto; border-radius: 0px;box-shadow: 0 0 30px 5px rgba(0,0,0,0.4);background: #fff;'>";
                      $str .= "<div style='font-size: 20px;font-weight: bold;letter-spacing: -0.03em;padding: 40px 10px 5px 10px; color:#333;'>Welcome Ankur!</div>";
                      $str .= "<div style='font-size: 14px; color: #8a0044; font-weight: bold; padding-bottom: 30px;dislay:inline-block;'>+91 9980051525  |  ankur.gala@hotmail.com</div>";
                      $str .= "<center>";
                      $str .= "<span style='color:#fff; font-size: 25px; display: inline-block; width:auto;padding: 10px 20px;font-weight: light;background: #5E0037;letter-spacing: -0.03em;border-radius: 3px;'>Save Time, Save Money!</span>";
                      $str .= "</center>";
                      $str .= "<div style='font-size: 20px; width: 80%;padding: 30px 10% 10px 10%; color: #333;'>You discovered the <b>easiest</b> way to buy <b>solitaires, jewellery </b>and <b>bullion</b> directly from the source. </div>";
                      $str .= "<center>";
                      $str .= "<img src='http://www.iftosi.com/tools/img/common/diamond.jpg' width='90px'>";
                      $str .= "<img src='http://www.iftosi.com/tools/img/common/jewellery.jpg' width='90px'>";
                      $str .= "<img src='http://www.iftosi.com/tools/img/common/bullions.jpg' width='90px'>";
                      $str .= "</center>";
                      $str .= "<center>";
                      $str .= "<div style='padding:1px 20px; font-size: 22px; text-align: left; border-bottom: 1px dotted #ccc; max-width: 400px;'>";
                      $str .= "<div style='background: #fff; color:#2a0e34; padding: 20px 0 20px 0;'>";
                      $str .= "<span>Diamonds</span>";
                      $str .= "<span style='float:right; background: #2a0e34; color:#fff; font-size: 12px; font-weight: bold;padding: 8px 15px;border-radius:2px;'>EXPLORE1</span>";
                      $str .= "</div>";
                      $str .= "</div>";
                      $str .= "<div style='padding:1px 20px; font-size: 22px; text-align: left; border-bottom: 1px dotted #ccc; max-width: 400px;'>";
                      $str .= "<div style='background: #fff; color:#380b34; padding: 20px 0 20px 0;'>";
                      $str .= "<span>Jewellery</span>";
                      $str .= "<span style='float:right; background: #380b34; color:#fff; font-size: 12px; font-weight: bold;padding: 8px 15px;border-radius:2px;'>EXPLORE</span>";
                      $str .= "</div>";
                      $str .= "</div>";
                      $str .= "<div style='padding:1px 20px; font-size: 22px; text-align: left; border-bottom: 1px dotted #ccc; max-width: 400px;'>";
                      $str .= "<div style='background: #fff; color:#5e0037; padding: 20px 0 20px 0;'>";
                      $str .= "<span>Bullion</span>";
                      $str .= "<span style='float:right; background: #5e0037; color:#fff; font-size: 12px; font-weight: bold;padding: 8px 15px;border-radius:2px;'>EXPLORE</span>";
                      $str .= "</div>";
                      $str .= "</div>";
                      $str .= "</center>";
                      $str .= "<div style='text-decoration:none; height:auto;padding: 20px 15px 40px 15px; color:#333; font-size: 15px;'>For any assistance, <br>Call: <a href='tel:+912232623263' style='text-transform: uppercase; width:auto;display: inline-block; font-weight: bold; color:#333; text-decoration: none; letter-spacing: 0.02em;'>91-22-41222241(42)</a> | Email: <b>info@iftosi.com</b></div>";
                      $str .= "</div>";
                      $str .= "<div style='color:#fff;font-size: 18px; padding: 20px 0'>- Team <b>IF</b>to<b>SI</b>.com</div>";
                      $str .= "</div>";
                    //  $str .= "</center>";
                      $str .= "</body>";
                      $str .= "</html>";

                      return $str;
        }
}


?>
