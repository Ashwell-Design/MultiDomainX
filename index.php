<?php
	define('__ROOT__',		dirname(__FILE__));
	define('QS_PAGE',		isset($_GET['page'])	? strtolower($_GET['page']) : 'index');
	define('QS_SUBPAGE',	isset($_GET['subpage'])	? strtolower($_GET['subpage']) : null);
	define('QS',			isset($_GET['q'])		? (strpos($_GET['q'], '/') ? explode('/', strtolower($_GET['q'])) : strtolower($_GET['q'])) : null);
	define('QS_FILE',		isset($_GET['file'])	? strtolower($_GET['file']) : "doesnt");
	define('QS_EXT',		isset($_GET['ext'])		? strtolower($_GET['ext']) : "exist");
	print_r("${QS_FILE}.${QS_EXT}");
	print_r($_GET['file'].".".$_GET['ext']);

	require_once('Classes.php');
	$cnf = new Config(__ROOT__.'/Configuration/config.ini');
	$config = $cnf->read();
	$db_c = new DB($config['Databases']['Central']);
	$db_a = new DB($config['Databases']['Metrics']);
	$tools = new Tools($db_c);
	if($db_c->num_rows(sprintf("SELECT * FROM `Domains` WHERE `Domain`='%s'", $_SERVER['SERVER_NAME'])) > 0) {
		$website = new Website($_SERVER['SERVER_NAME'], $db_c);
		$page = new Page(QS_PAGE, QS_SUBPAGE, QS, $db_c);
		if($page->page_id) {
			$theme = new Theme($website->info['Theme'], $db_c, $website->info['ID'], $page);
			$themeinfo = $theme->info;
			if(file_exists($file = __ROOT__."/Themes/".$themeinfo['Location']."/assets/".QS_FILE.".".QS_EXT)) {
				header("Content-Type: " . $tools->get_mime_type(QS_FILE.".".QS_EXT) . "; charset=UTF-8;");
				print(file_get_contents($file));
			} elseif(file_exists($file = __ROOT__."/Updater/".QS_FILE.".".QS_EXT)) {
				include_once($file);
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
