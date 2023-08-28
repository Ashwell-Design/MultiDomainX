<?php
ini_set('display_errors', '1'); error_reporting(E_ERROR);

define('__ROOT__',		dirname(__FILE__));
define('__PERMALINK__',	(isset($_GET['perma']))	? '/'.strtolower($_GET['perma']).'/': '/');
define('__QSFILE__',	isset($_GET['file'])	? $_GET['file'] : "doesnt");
define('__QSEXT__',		isset($_GET['ext'])		? $_GET['ext'] : "exist");
require_once('Classes.php');

$db_c = new DB('central');
$db_a = new DB('metrics');
$tools = new Tools($db_c);
if($db_c->num_rows(sprintf("SELECT * FROM `Domains` WHERE `Domain`='%s'", $_SERVER['SERVER_NAME'])) > 0) {
	$website = new Website($_SERVER['SERVER_NAME'], $db_c);
	$page = new Page($website->info['ID'], __PERMALINK__, $db_c);
	if($page->page_id) {
		$theme = new Theme($website->info['Theme'], $db_c, $website->info['ID'], $page, $page->getConfiguration('DefaultTheme'));
		$themeinfo = $theme->info;
		if(file_exists($file = __ROOT__."/".__QSFILE__.".".__QSEXT__)) {
			header("Content-Type: ".$tools->get_mime_type(__QSFILE__.".".__QSEXT__)."; charset=UTF-8;");
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
