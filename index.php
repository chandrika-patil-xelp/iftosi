<?php
	$case = $_GET['case'];
    switch ($case)
    {
        case 'home':
            include 'template/index.html';
            break;
        case 'results':
            include 'template/results.html';
            break;
	}
?>