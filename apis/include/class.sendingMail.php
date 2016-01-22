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

            $subject ='Email testing IFtoSI';

            $message = urldecode($params['str']);

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <info@iftosi.com>' . "\r\n";
            if(!empty($params['to']))
            {
                mail(urldecode($params['to']), $subject, $message, $headers);
                $arr = 'mail sent to '.urldecode($params['to']);
                $err = array('code'=>0,'msg'=>'success in sending mail');
            }
            else
            {
                $arr = '';
                $err = array('code'=>1,'msg'=>'Failed in sending mail');
            }
            $result = array('result'=>$arr,'error'=>$err);
            return $result;
        }
}


?>

