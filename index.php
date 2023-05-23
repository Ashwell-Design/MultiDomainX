<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

	define('__ROOT__',		dirname(__FILE__));
	define('SERVER_NAME',	$_SERVER['SERVER_NAME']);
	define('QS_PAGE',		isset($_GET['page'])	? strtolower($_GET['page']) : 'index');
	define('QS_SUBPAGE',	isset($_GET['subpage'])	? strtolower($_GET['subpage']) : '');
	define('QS',			isset($_GET['q'])		? (strpos($_GET['q'], '/') ? explode('/', strtolower($_GET['q'])) : strtolower($_GET['q'])) : '');
	define('QS_FILE',		isset($_GET['file'])	? $_GET['file'] : "doesnt");
	define('QS_EXT',		isset($_GET['ext'])		? $_GET['ext'] : "exist");
	require_once('Classes.php');

	$db_c = new DB('central');
	$db_a = new DB('metrics');
	$tools = new Tools($db_c);
	if($db_c->num_rows(sprintf("SELECT * FROM `Domains` WHERE `Domain`='%s'", SERVER_NAME)) > 0) {
		$website = new Website(SERVER_NAME, $db_c);
		$page = new Page($website->info['ID'], QS_PAGE, QS_SUBPAGE, QS, $db_c);
		$page->getConfiguration('DefaultTheme');
		if($page->page_id) {
			$theme = new Theme($website->info['Theme'], $db_c, $website->info['ID'], $page, 'voxo');
			$themeinfo = $theme->info;
			if(file_exists($file = __ROOT__."/".QS_FILE.".".QS_EXT)) {
				header("Content-Type: " . $tools->get_mime_type(QS_FILE.".".QS_EXT) . "; charset=UTF-8;");
				require_once($file);
			} else {
				print($theme->generate());
			}
		} else {
			header('HTTP/1.0 404 Not Found');
		}
	} else {
		header('HTTP/1.0 404 Not Found');
	}
?>
