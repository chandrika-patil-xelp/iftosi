<?php
/*
if(!stristr($_SERVER['HTTP_HOST'], '.iftosi.com') && !stristr($_SERVER['HTTP_HOST'], 'localhost'))
{
    $inival = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/iftosi_con.ini',true);
}
*/
error_reporting(0);
define('REQURI', $_SERVER['REQUEST_URI']);
if(stristr($_SERVER['HTTP_HOST'],'demo.iftosi.com'))
{

	define('DOMAIN','http://'.$_SERVER['HTTP_HOST'].'/');
	define('APIDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/apis/');
	define('WEBROOT',$_SERVER['DOCUMENT_ROOT'].'/');
	define('TEMPLATE',WEBROOT.'template/');
	define('INCLUDES',WEBROOT.'include/');
	define('APICLUDE',WEBROOT.'apis/include/');
	define('IMGDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/image-upload/');
	define('IMGUPLOAD','http://'.$_SERVER['HTTP_HOST'].'/upload-image/');
	define('IMGPATH','image-upload/');
	define('MINIFIED','.min');
}
else if(stristr($_SERVER['HTTP_HOST'],'iftosi.com') && stristr(REQURI, 'beta'))
{
	define('DOMAIN','http://'.$_SERVER['HTTP_HOST'].'/beta/');
	define('APIDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/beta/apis/');
	define('WEBROOT',$_SERVER['DOCUMENT_ROOT'].'/beta/');
	define('TEMPLATE',WEBROOT.'template/');
	define('INCLUDES',WEBROOT.'include/');
	define('APICLUDE',WEBROOT.'apis/include/');
	define('IMGDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/beta/image-upload/');
	define('IMGUPLOAD','http://'.$_SERVER['HTTP_HOST'].'/beta/upload-image/');
	define('IMGPATH','image-upload/');
	define('MINIFIED','.min');
}
else if(stristr($_SERVER['HTTP_HOST'],'iftosi.com'))
{

	define('DOMAIN','http://'.$_SERVER['HTTP_HOST'].'/');
	define('APIDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/apis/');
	define('WEBROOT',$_SERVER['DOCUMENT_ROOT'].'/');
	define('TEMPLATE',WEBROOT.'template/');
	define('INCLUDES',WEBROOT.'include/');
	define('APICLUDE',WEBROOT.'apis/include/');
	define('IMGDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/image-upload/');
	define('IMGUPLOAD','http://'.$_SERVER['HTTP_HOST'].'/upload-image/');
	define('IMGPATH','image-upload/');
	define('MINIFIED','.min');
}
else if(stristr($_SERVER['HTTP_HOST'], '52.77.214.223'))
{
	define('DOMAIN','http://'.$_SERVER['HTTP_HOST'].'/');
	define('APIDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/apis/');
	define('WEBROOT',$_SERVER['DOCUMENT_ROOT'].'/');
	define('TEMPLATE',WEBROOT.'template/');
	define('INCLUDES',WEBROOT.'include/');
	define('APICLUDE',WEBROOT.'apis/include/');
	define('IMGDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/image-upload/');
	define('IMGUPLOAD','http://'.$_SERVER['HTTP_HOST'].'/upload-image/');
	define('IMGPATH','image-upload/');
	define('MINIFIED','.min');
}
else
{
	define('DOMAIN','http://'.$_SERVER['HTTP_HOST'].'/iftosi/');
	define('APIDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/iftosi/apis/');
	define('WEBROOT',$_SERVER['DOCUMENT_ROOT'].'/iftosi/');
	define('TEMPLATE',WEBROOT.'template/');
	define('INCLUDES',WEBROOT.'include/');
	define('APICLUDE',WEBROOT.'apis/include/');
	//define('IMGDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/image-upload/');
    //define('IMGDOMAIN','http://'.$_SERVER['HTTP_HOST'].'/iftosi/image-upload/');
    define('IMGDOMAIN','http://www.iftosi.com/image-upload/');
    define('IMGUPLOAD','http://'.$_SERVER['HTTP_HOST'].'/iftosi/upload-image/');
	define('IMGPATH','image-upload/');
	define('MINIFIED','');
}
define('SMSAPI','http://alerts.sinfini.com/api/web2sms.php?workingkey=Ae24f0c241588043b00e0449d6e2a5664&to=+91_MOBILE&sender=IFTOSI&message=_MESSAGE');

define('VERSION',20.17);

$dollarValue = 66.03;

define('dollarValue',$dollarValue);

$goldRate =  24050;

define('goldRate',$goldRate);

$silverRate = 35918.00;
define('silverRate',$silverRate);

$platinumRate = 12000.00;
define('platinumRate',$platinumRate);

define('FAVICON', DOMAIN . 'tools/img/common/favicon.ico');

if(stristr($_SERVER['HTTP_HOST'], 'demo.iftosi.com'))
{
	$db['iftosi'] = array('localhost','xelpmoc_dmiftosi','1234567890','xelpmoc_dmiftosi');
}
else if(stristr($_SERVER['HTTP_HOST'],'iftosi.com') && stristr(REQURI, 'beta'))
{
	$db['iftosi'] = array('localhost','root','developer@iftosi','db_iftosi_beta');
}
else if(stristr($_SERVER['HTTP_HOST'],'iftosi.com'))
{
	//$db['iftosi'] = array('54.213.106.43','nafexdb','nafexdb','db_iftosi');
	//$db['iftosi'] = array('10.123.0.91','xelpmoc_iftosi','iftosi','xelpmoc_iftosi');
	$db['iftosi'] = array('localhost','root','developer@iftosi','db_iftosi');
}
else if(stristr($_SERVER['HTTP_HOST'], '52.77.214.223'))
{
	$db['iftosi'] = array('localhost','iftosi','iftosi@live','db_iftosi');
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
$css['toastr'] = DOMAIN.'tools/css/lib/toastr.css?v='.VERSION;

// CSS Libraries End


// Custom CSS Start

$css['home'] = DOMAIN.'tools/css/home.css?v='.VERSION;
$css['diamond'] = DOMAIN.'tools/css/diamond.css?v='.VERSION;

// Custom CSS End


// JS Libraries Start
$jvs['jqry'] 		= DOMAIN.'tools/js/lib/jquery'.MINIFIED.'.js?v='.VERSION;
$jvs['nicescroll']     = DOMAIN.'tools/js/lib/jquery.nicescroll'.MINIFIED.'.js?v='.VERSION;
$jvs['jqryUi'] 		= DOMAIN.'tools/js/lib/jquery-ui'.MINIFIED.'.js?v='.VERSION;
$jvs['parlx'] 		= DOMAIN.'tools/js/lib/parallax'.MINIFIED.'.js?v='.VERSION;
$jvs['velocity'] 	= DOMAIN.'tools/js/lib/velocity'.MINIFIED.'.js?v='.VERSION;
$jvs['ripple'] 		= DOMAIN.'tools/js/lib/ripple'.MINIFIED.'.js?v='.VERSION;
$jvs['rngsld'] 		= DOMAIN.'tools/js/lib/ion.rangeSlider'.MINIFIED.'.js?v='.VERSION;
$jvs['jqrynum'] 	= DOMAIN.'tools/js/lib/jquery-numerator'.MINIFIED.'.js?v='.VERSION;
$jvs['hammer'] 		= DOMAIN.'tools/js/lib/hammer'.MINIFIED.'.js?v='.VERSION;
$jvs['auto'] 		= DOMAIN.'tools/js/lib/autosuggest'.MINIFIED.'.js?v='.VERSION;
$jvs['customStorage']   = DOMAIN.'tools/js/lib/CustomStorage'.MINIFIED.'.js?v='.VERSION;
$jvs['jquery_toast']	= DOMAIN.'tools/js/lib/jquery.toast'.MINIFIED.'.js?v='.VERSION;
$jvs['toastr']	= DOMAIN.'tools/js/lib/toastr'.MINIFIED.'.js?v='.VERSION;
$jvs['cms_jquery_toast']	= DOMAIN.'tools/js/cms/lib/jquery.toast'.MINIFIED.'.js?v='.VERSION;

// JS Libraries End


// Custom JS Start

$jvs['comm'] 		= DOMAIN.'tools/js/Common'.MINIFIED.'.js?v='.VERSION;
$jvs['home'] 		= DOMAIN.'tools/js/home'.MINIFIED.'.js?v='.VERSION;
$jvs['result'] 		= DOMAIN.'tools/js/result'.MINIFIED.'.js?v='.VERSION;
$jvs['details'] 	= DOMAIN.'tools/js/details'.MINIFIED.'.js?v='.VERSION;
$jvs['head'] 		= DOMAIN.'tools/js/header'.MINIFIED.'.js?v='.VERSION;
$jvs['signup'] 		= DOMAIN.'tools/js/signup'.MINIFIED.'.js?v='.VERSION;
$jvs['filter'] 		= DOMAIN.'tools/js/filters'.MINIFIED.'.js?v='.VERSION;
$jvs['dmap'] 		= DOMAIN.'tools/js/detailsMap.js?v='.VERSION;
$jvs['vendorDetails'] 	= DOMAIN.'tools/js/cms/page/vendorDetails'.MINIFIED.'.js?v='.VERSION;
$jvs['vendorLanding'] 	= DOMAIN.'tools/js/cms/vendor_landing.js?v='.VERSION;
$jvs['pForm']    	= DOMAIN.'tools/js/pForm'.MINIFIED.'.js?v='.VERSION;
$jvs['cmsCommon'] 	= DOMAIN.'tools/js/cms/Common'.MINIFIED.'.js?v='.VERSION;
$jvs['vendor_Product'] 	= DOMAIN.'tools/js/cms/page/vendorProductListings'.MINIFIED.'.js?v='.VERSION;
$jvs['search'] 	= DOMAIN.'tools/js/cms/page/searchjs'.MINIFIED.'.js?v='.VERSION;
$jvs['vendor_Pending_Product'] 	= DOMAIN.'tools/js/cms/page/vendor_Pending_products'.MINIFIED.'.js?v='.VERSION;
$jvs['clarity'] 	= DOMAIN.'tools/js/clarity'.MINIFIED.'.js?v='.VERSION;
$jvs['anatomy'] 	= DOMAIN.'tools/js/anatomy'.MINIFIED.'.js?v='.VERSION;
$jvs['enquiry'] 	= DOMAIN.'tools/js/enquiry'.MINIFIED.'.js?v='.VERSION;
$jvs['vendorList'] 	= DOMAIN.'tools/js/vendorList'.MINIFIED.'.js?v='.VERSION;
$jvs['customerList'] 	= DOMAIN.'tools/js/customerList'.MINIFIED.'.js?v='.VERSION;
// Custom JS End


include INCLUDES.'class.common.php';
$comm = new Common();
$comm->clearParam($_GET);
?>
