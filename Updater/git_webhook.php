<?
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if($_POST['payload']) { // Only respond to POST requests from Github
			$payload = json_decode($_POST['payload'], true);
			define("GIT_PROT",		"https");
			define("GIT_USER",		"azurrr");
			define("GIT_PKEY",		"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");
			define("GIT_ADDR",		"github.com");
			define("ROOT_DIR",		"/kunden/homepages/36/d908228976/htdocs");
			define("HOST",			explode('/', $payload['full_name'])[0]);
			define("REPO",			explode('/', $payload['full_name'])[1]);
			define("SPUR",			explode('/', $payload['ref'])[2]);
			define("HOST_DIR",		ROOT_DIR."/".HOST);
			define("REPO_DIR",		HOST_DIR."/".REPO);
			define("SPUR_DIR",		REPO_DIR."/".SPUR);
				   
			define("REMOTE_LINK",	GIT_PROT."://".GIT_USER.":".GIT_PKEY."@". GIT_ADDR."/".HOST."/".REPO.".git");

			if(!file_exists(HOST_DIR)) shell_exec("cd ".ROOT_DIR." && mkdir ".HOST);
			if(!file_exists(REPO_DIR)) shell_exec("cd ".HOST_DIR." && mkdir ".REPO);
			if(!file_exists(SPUR_DIR)) {
				shell_exec("cd ".SPUR_DIR." && git clone ".REMOTE_LINK);
				shell_exec("cd ".SPUR_DIR." && mv ".REPO."".SPUR);
				shell_exec("cd ".REPOSITORY." && git checkout ".SPUR);
				die("Cloned. ".date(DateTime::ISO8601, strtotime('-2 hour')));
			} else {
				shell_exec("cd ".REPOSITORY." && git pull origin ".SPUR);
				die("Updated. ".date(DateTime::ISO8601, strtotime('-2 hour')));
			}
		}
	}
	header("HTTP/1.0 404 Not Found");
	die();
?>
