<?
	$conf = parse_ini_file(__ROOT__.'/Configuration/config.ini', true);
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if($_POST['payload']) { // Only respond to POST requests from Github
			$payload = json_decode($_POST['payload'], true);
			define("GIT_USER",		$conf['Github']['username']);
			define("GIT_PKEY",		$conf['Github']['Private_Key']);
			define("ROOT_DIR",		"/kunden/homepages/36/d908228976/htdocs");
			define("HOST",			explode('/', $payload['repository']['full_name'])[0]);	# Ashwell-Design
			define("REPO",			explode('/', $payload['repository']['full_name'])[1]);	# MultiDomainX
			define("SPUR",			explode('/', $payload['ref'])[2]); 						# Development
			define("HOST_DIR",		ROOT_DIR."/".HOST);										# /Ashwell-Design/
			define("REPO_DIR",		HOST_DIR."/".REPO);										# /Ashwell-Design/MultiDomainX/
			define("SPUR_DIR",		REPO_DIR."/".SPUR);										# /Ashwell-Design/MultiDomainX/Development/

			define("REMOTE_LINK",	"https://".GIT_USER.":".GIT_PKEY."@github.com/".HOST."/".REPO.".git");

			if(!file_exists(HOST_DIR)) shell_exec("cd ".ROOT_DIR." && mkdir ".HOST);
			if(!file_exists(REPO_DIR)) shell_exec("cd ".HOST_DIR." && mkdir ".REPO);
			if(!file_exists(SPUR_DIR)) {
				shell_exec("cd ".SPUR_DIR." && git clone ".REMOTE_LINK);
				shell_exec("cd ".SPUR_DIR." && mv " . REPO . " " . SPUR );
				shell_exec("cd ".SPUR_DIR." && git checkout ".SPUR);
				die("Cloned. ".date(DateTime::ISO8601, strtotime('-2 hour')));
			} else {
				shell_exec("cd ".SPUR_DIR." && git pull origin ".SPUR);
				die("Updated. ".date(DateTime::ISO8601, strtotime('-2 hour')));
			}
		}
		die("Error: 002"); // payload not set, likely not github.
	}
	die("Error: 001"); // Wrong request method
?>
