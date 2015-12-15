<?php
/*
if(!stristr($_SERVER['HTTP_HOST'], '.iftosi.com') && !stristr($_SERVER['HTTP_HOST'], 'localhost'))
{
    $inival = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/iftosi_con.ini',true);
}
*/
error_reporting(0);
if(stristr($_SERVER['HTTP_HOST'],'iftosi.com'))
{

	define('DOMAIN','http://'.$_SERVER['HTTP_HOST'].'/');
	define('APIDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/apis/');
	define('WEBROOT',$_SERVER['DOCUMENT_ROOT'].'/');
	define('TEMPLATE',WEBROOT.'template/');
	define('INCLUDES',WEBROOT.'include/');
	define('APICLUDE',WEBROOT.'apis/include/');
	define('IMGDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/image-upload/');
	define('IMGUPLOAD','http://'.$_SERVER['HTTP_HOST'].'/upload-image/');
	define('VERSION',2.0);
	define('IMGPATH','image-upload/');
}
else
{
	define('DOMAIN','http://'.$_SERVER['HTTP_HOST'].'/iftosi/');
	define('APIDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/iftosi/apis/');
	define('WEBROOT',$_SERVER['DOCUMENT_ROOT'].'/iftosi/');
	define('TEMPLATE',WEBROOT.'template/');
	define('INCLUDES',WEBROOT.'include/');
	define('APICLUDE',WEBROOT.'apis/include/');
	define('IMGDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/iftosi/image-upload/');
	define('IMGUPLOAD','http://'.$_SERVER['HTTP_HOST'].'/iftosi/upload-image/');
	define('VERSION',2.0);
	define('IMGPATH','image-upload/');        
}
/*define('SMSAPI','http://alerts.sinfini.com/api/web2sms.php?workingkey=Aacffda7db60ac1a8470709273bea3bfe&to=+91_MOBILE&sender=NAFEXC&message=_MESSAGE');*/

$dollarValue = 66.03;
define('dollarValue',$dollarValue);
$goldRate =  24050;
define('goldRate',$goldRate);
$silverRate = 35918.00;
define('silverRate',$silverRate);

define('FAVICON', DOMAIN . 'tools/img/common/favicon.ico');


if(stristr($_SERVER['HTTP_HOST'],'iftosi.com'))
{
	//$db['iftosi'] = array('54.213.106.43','nafexdb','nafexdb','db_iftosi');
	//$db['iftosi'] = array('10.123.0.91','xelpmoc_iftosi','iftosi','xelpmoc_iftosi');
	$db['iftosi'] = array('localhost','xelpmoc_dbiftosi','1234567890','xelpmoc_dbiftosi');
}
else
{
	$db['iftosi'] = array('localhost','root','','db_iftosi');
}

$css = array();
$jvs = array();

// CSS Libraries Start
$css['ripple'] = DOMAIN.'tools/css/ripple.css?v='.VERSION;
$css['rngsld'] = DOMAIN.'tools/css/rangeslider/ion.rangeSlider.css?v='.VERSION;
$css['rngsldskin'] = DOMAIN.'tools/css/rangeslider/ion.rangeSlider.skinHTML5.css?v='.VERSION;
$css['normalize'] = DOMAIN.'tools/css/rangeslider/normalize.css?v='.VERSION;
$css['jquery_toast'] = DOMAIN.'tools/css/jquery.toast.min.css?v='.VERSION;

// CSS Libraries End


// Custom CSS Start

$css['home'] = DOMAIN.'tools/css/home.css?v='.VERSION;
$css['diamond'] = DOMAIN.'tools/css/diamond.css?v='.VERSION;

// Custom CSS End


// JS Libraries Start
$jvs['jqry'] 		= DOMAIN.'tools/js/lib/jquery.js?v='.VERSION;
$jvs['jqryUi'] 		= DOMAIN.'tools/js/lib/jquery-ui.js?v='.VERSION;
$jvs['parlx'] 		= DOMAIN.'tools/js/lib/parallax.js?v='.VERSION;
$jvs['velocity'] 	= DOMAIN.'tools/js/lib/velocity.js?v='.VERSION;
$jvs['ripple'] 		= DOMAIN.'tools/js/lib/ripple.js?v='.VERSION;
$jvs['rngsld'] 		= DOMAIN.'tools/js/lib/ion.rangeSlider.js?v='.VERSION;
$jvs['jqrynum'] 	= DOMAIN.'tools/js/lib/jquery-numerator.js?v='.VERSION;
$jvs['hammer'] 		= DOMAIN.'tools/js/lib/hammer.js?v='.VERSION;
$jvs['auto'] 		= DOMAIN.'tools/js/lib/autosuggest.js?v='.VERSION;
$jvs['customStorage'] 		= DOMAIN.'tools/js/lib/CustomStorage.js?v='.VERSION;
$jvs['jquery_toast'] 		= DOMAIN.'tools/js/lib/jquery.toast.min.js?v='.VERSION;

// JS Libraries End


// Custom JS Start

$jvs['comm'] 		= DOMAIN.'tools/js/Common.js?v='.VERSION;
$jvs['home'] 		= DOMAIN.'tools/js/home.js?v='.VERSION;
$jvs['result'] 		= DOMAIN.'tools/js/result.js?v='.VERSION;
$jvs['details'] 	= DOMAIN.'tools/js/details.js?v='.VERSION;
$jvs['head'] 		= DOMAIN.'tools/js/header.js?v='.VERSION;
$jvs['signup'] 		= DOMAIN.'tools/js/signup.js?v='.VERSION;
$jvs['filter'] 		= DOMAIN.'tools/js/filters.js?v='.VERSION;
$jvs['dmap'] 		= DOMAIN.'tools/js/detailsMap.js?v='.VERSION;

// Custom JS End



include INCLUDES.'class.common.php';
$comm = new Common();
$comm->clearParam($_GET);
?>
