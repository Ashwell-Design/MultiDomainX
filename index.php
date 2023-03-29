<?php
	define('__ROOT__', dirname(__FILE__));
	define('QS_PAGE',		isset($_GET['page']) ? strtolower($_GET['page']) : 'index');
	define('QS_SUBPAGE',	isset($_GET['subpage']) ? strtolower($_GET['subpage']) : null);
	define('QS',			isset($_GET['q']) ? (strpos($_GET['q'], '/') ? explode('/', strtolower($_GET['q'])) : strtolower($_GET['q'])) : null);

	require_once('Classes.php');

	$cnf = new Config(__ROOT__.'\Configuration\config.ini');
	$config = $cnf->read();

	$db_c = new DB($config['DB.Central']['address'], $config['DB.Central']['username'], $config['DB.Central']['password'], $config['DB.Central']['database']);
	$db_a = new DB($config['DB.Analytics']['address'], $config['DB.Analytics']['username'], $config['DB.Analytics']['password'], $config['DB.Analytics']['database']);

	if($db_c->num_rows(sprintf("SELECT * FROM `Domains` WHERE `Domain`='%s'", $_SERVER['SERVER_NAME'])) > 0) {
		$site = new Website($_SERVER['SERVER_NAME'], $db_c);
		$siteinfo = $site->info();
		$siteTheme = $site->getTheme();

		$theme = new Theme($siteinfo['Theme'], $db_c);
		$themeinfo = $theme->info();

		$page = new Page(QS_PAGE, QS_SUBPAGE, QS, $db_c);
		$pageinfo = $page->info();

		print($theme->generate());
	} else {
		echo "false";
	}
?>