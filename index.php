<?php
	include 'config.php';
	$params = array_merge($_GET, $_POST);

	$action = (!empty($params['action'])) ? trim($params['action']) : '';
	$case = (!empty($params['case'])) ? trim($params['case']) : '';

	switch($action)
	{
		case 'ajx':
			switch ($case)
			{
				case '':
				break;
			}
			break;
		default:
			switch ($case)
			{
                                case 'signup':
                                        $page='signup';
					include 'template/signup.html';
				break;
				case 'results':
                                        $page='results';
					include 'template/results.html';
				break;
				case 'details':
                                        $page='product_details';
					include 'template/product_details.html';
				break;
				default:
                                        $page='index';
					include 'template/index.html';
                                        
				break;
			}
			break;
	}
?>
