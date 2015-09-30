<?php
/*
if(!stristr($_SERVER['HTTP_HOST'], '.iftosi.com') && !stristr($_SERVER['HTTP_HOST'], 'localhost'))
{
    $inival = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/iftosi_con.ini',true);
}
*/
define('DOMAIN','http://'.$_SERVER['HTTP_HOST'].'/iftosi/');
define('APIDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/iftosi/apis/');
define('WEBROOT',$_SERVER['DOCUMENT_ROOT'].'/iftosi/');
define('TEMPLATE',WEBROOT.'template/');
define('INCLUDES',WEBROOT.'include/');
define('APICLUDE',WEBROOT.'apis/include/');
define('VERSION',0.1);
/*define('SMSAPI','http://alerts.sinfini.com/api/web2sms.php?workingkey=Aacffda7db60ac1a8470709273bea3bfe&to=+91_MOBILE&sender=NAFEXC&message=_MESSAGE');
*/

$db['iftosi'] = array('localhost','root','','db_iftosi');

/*$css['home'] = DOMAIN.'tools/css/home.css?v='.VERSION;

$jvs['jqry'] = DOMAIN.'tools/js/lib/jquery.js';
*/
include INCLUDES.'class.common.php';
$comm = new Common();
$comm->clearParam($_GET);
?>
