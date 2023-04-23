<?php
	define('__ROOT__',		dirname(__FILE__));
	define('SERVER_NAME',	's908228974.websitehome.co.uk');//$_SERVER['SERVER_NAME']);
	define('QS_PAGE',		isset($_GET['page'])	? strtolower($_GET['page']) : 'index');
	define('QS_SUBPAGE',	isset($_GET['subpage'])	? strtolower($_GET['subpage']) : null);
	define('QS',			isset($_GET['q'])		? (strpos($_GET['q'], '/') ? explode('/', strtolower($_GET['q'])) : strtolower($_GET['q'])) : null);
	define('QS_FILE',		isset($_GET['file'])	? strtolower($_GET['file']) : "doesnt");
	define('QS_EXT',		isset($_GET['ext'])		? strtolower($_GET['ext']) : "exist");
	require_once('Classes.php');

	$cnf = new Config(__ROOT__.'/Configuration/config.ini');
	$config = $cnf->read();
	$db_c = new DB($config['Databases']['Central']);
	$db_a = new DB($config['Databases']['Metrics']);
	$tools = new Tools($db_c);
	print("Here: 1 <br />");
	print("Current domain: ".SERVER_NAME. "<br />");
	$q = $db_c->query("SELECT `Domain` FROM `Domains`");
	while($item = $db_c->array($q)) {
		if($item['Domain'] == SERVER_NAME) {
			$doms[] = "<b>".$item['Domain']."</b>";
		} else {
			$doms[] = $item['Domain'];
		}
	}
	print("Allowed domains: ".implode(", ", $doms). "<br />");
	if($db_c->num_rows(sprintf("SELECT * FROM `Domains` WHERE `Domain`='%s'", SERVER_NAME)) > 0) {
		print("Here: 2 <br />");
		$website = new Website(SERVER_NAME, $db_c);
		$page = new Page(QS_PAGE, QS_SUBPAGE, QS, $db_c);
		if($page->page_id) {
			print("Here: 3 <br />");
			$theme = new Theme($website->info['Theme'], $db_c, $website->info['ID'], $page);
			$themeinfo = $theme->info;
			if(file_exists($file = __ROOT__."/Themes/".$themeinfo['Location']."/assets/".QS_FILE.".".QS_EXT)) {
				print("Here: 4 <br />");
				header("Content-Type: " . $tools->get_mime_type(QS_FILE.".".QS_EXT) . "; charset=UTF-8;");
				print(file_get_contents($file));
			} elseif(file_exists($file = __ROOT__."/".QS_FILE.".".QS_EXT)) {
				print("Here: 5 <br />");
				require_once($file);
			} else {
				print("Here: 6 <br />");
				print($theme->generate());
			}
		} else {
			header('HTTP/1.0 404 Not Found');
		}
	} else {
		header('HTTP/1.0 404 Not Found');
	}
?>
