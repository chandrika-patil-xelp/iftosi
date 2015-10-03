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
				case 'home':
					include 'template/index.html';
				break;
				case 'results':
					include 'template/results.html';
				break;
				default:
					include 'template/index.html';
				break;
			}
			break;
	}
?>
