<?php
	foreach (glob(__ROOT__.'\Libs\*.php') as $file) {
		if($file != __FILE__) {
			require_once($file);
		}
	}

	class Config {
		protected $file;
		public function __construct($file) {
			$this->file = $file;
		}

		function read() {
			return parse_ini_file($this->file, true);
		}

		function write($arr) {
			return file_put_contents($this->file, array_ini($arr));
		}
		private function array_ini($array) {
			return array_to_ini_string($array);
		}
	}
	class DB {
		protected $address, $username, $password, $database, $sql;
		public function __construct($address, $username, $password, $database) {
			$this->address = $address;
			$this->username = $username;
			$this->password = $password;
			$this->database = $database;
			$this->sql = mysqli_connect($address, $username, $password, $database);
		}
		public function close() {
			return mysqli_close($this->sql);
		}

		public function query($string) {
			return mysqli_query($this->sql, $string);
		}
		public function execute($string) {
			$this->query($string);
			if(mysqli_warning_count($this->sql)) {
				return false;
			} else {
				return true;
			}
		}
		public function array($string) {
			return mysqli_fetch_array($this->query($string));
		}
		public function assoc($string) {
			return mysqli_fetch_assoc($this->query($string));
		}
		public function row($string) {
			return mysqli_fetch_row($this->query($string));
		}
		public function num_rows($string) {
			$q = $this->query($string);
			return mysqli_num_rows($q);
		}
	}
	class Website {
		protected $website, $db;
		public function __construct($website, $db) {
			$this->db = $db;
			$this->website = $website;
		}
		public function info() {
			return $this->db->assoc(sprintf("SELECT * FROM `Domains` WHERE `Domain`='%s'", $this->website));
		}
		public function getTheme() {
			$id = $this->db->array(sprintf("SELECT `Theme` FROM `Domains` WHERE `Domain`='%s'", $this->website))[0];
			return $this->db->assoc(sprintf("SELECT * FROM `Themes` WHERE `ID`='%s' AND `Active?`=1", $id));
		}
	}
	class Theme {
		protected $id, $db;
		public function __construct($id, $db) {
			$this->db = $db;
			$this->id = $id;
			$this->name = $db->array(sprintf("SELECT `Name` FROM `Themes` WHERE `id`='%s'", $this->id))[0];
			$this->path = __ROOT__.'\\Themes\\'.$this->name;
		}
		public function info() {
			return $this->db->assoc(sprintf("SELECT * FROM `Themes` WHERE `ID`='%s'", $this->id));
		}
		public function generate() {
			return $this->convertVars("<!DOCTYPE html><html><head>{$this->generateHead()}</head><body>{$this->generateBody()}</body></html>");
		}
		// Halves
		public function generateHead($head='') {
			$head .= $this->getMeta();
			$head .= $this->getFavicon();
			$head .= $this->getTitle();
			$head .= $this->getStyles();
			return $head;
		}
		public function generateBody($body='') {
			$body .= $this->getScripts();
			if(($code = $this->db->array(sprintf("SELECT `Sections` FROM `Pages` WHERE `ID`='%s'", $this->id))[0]) != null) {
				$columns = explode("#", $code);
				$seccode = $secext = NULL;
				$body .= '<main class="container-fluid"><div class="row">';
				array_shift($columns);
				foreach($columns as $column) {
					[$width, $section_string] = explode(';', $column);
					$body .= "<div class=\"col-md-$width\">";
						$sections = explode(',', $section_string);
						foreach($sections as $section) {
							[$seccode, $secext] = explode(':', $section);
							if($this->db->num_rows("SELECT * FROM `sections` WHERE `Code`='$seccode'") == 1) {
								$row = $this->db->assoc("SELECT * FROM `sections` WHERE `Code`='$seccode'");
								$body .= file_get_contents("{$this->path}\\{$row['Type']}\\{$row['File']}.php");
								unset($secext);
							}
						}
						$body .= "</div>";
				}
				$body .= '</div></main>';
			} else {
				$body = 'Error: No page string was set';
			}
			return str_replace(PHP_EOL, '', $body);
		}
		// Segments
		public function getMeta($out='<!-- META -->') {
			$out .= '<meta charset="utf-8"><meta content="ie=edge" http-eqiv="X-UA-Compatible"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
			$out .= sprintf('<meta name="title" content="">');
			$out .= sprintf('<meta name="description" content="">');
			$out .= sprintf('<meta name="keywords" content="">');
			$out .= sprintf('<meta name="theme-color" content="">');
			return $out;
		}
		public function getTitle($out='<!-- TITLE -->') {
			$title = $this->db->array(sprintf("SELECT `Title` FROM `pages` WHERE `ID`='%s'", $this->id))[0];
			return $out .= "<title>$title</title>";
		}
		public function getStyles($out='<!-- STYLES -->') {
			$styles = $this->db->array(sprintf("SELECT `Styles` FROM `pages` WHERE `ID`='%s'", $this->id))[0];
			foreach(explode(',', $styles) as $style) {
				$x = $this->db->assoc(sprintf("SELECT `URL`, `Hash` FROM `styles` WHERE `ID`='%s' ORDER BY `Importance` ASC", $style));
				$out .= sprintf("<noscript><link rel=\"stylesheet\" href=\"%s\" integrity=\"%s\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></noscript><link rel=\"stylesheet\" href=\"%s\" integrity=\"%s\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" as=\"style\" onload=\"this.onload=null;this.rel='stylesheet'\">", $x['URL'], $x['Hash'], $x['URL'], $x['Hash']);
			}
			return $out;
		}
		public function getScripts($out='<!-- SCRIPTS -->') {
			$scripts = $this->db->array(sprintf("SELECT `Scripts` FROM `pages` WHERE `ID`='%s'", $this->id))[0];
			foreach(explode(',', $scripts) as $script) {
				$x = $this->db->assoc(sprintf("SELECT `URL`, `Hash` FROM `scripts` WHERE `ID`='%s' ORDER BY `Importance` ASC", $script));
				$out .= sprintf("<script src=\"%s\" integrity=\"%s\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>", $x['URL'], $x['Hash']);
			}
			return $out;
		}
		public function getFavicon($out = '<!-- FAVICON -->') {
			$image = $this->db->array(sprintf("SELECT `Styles` FROM `pages` WHERE `ID`='%s'", $this->id))[0];
			return $out .= "<link rel=\"icon\" href=\"$image\" type=\"image/png\">";
		}
		// Variables
		public function convertVars($string) {
			return preg_replace_callback('/<%\[([0-9a-zA-Z ]+)\]%>/', function($matches) {
				switch(strtolower($matches[1])) {
					case "company name":
						return strtoupper($matches[1]);
					default:
						return "ERROR";
				}
			}, $string);
		}
	}
	class Page {
		protected $db, $page, $subpage, $query;
		public function __construct($page, $subpage, $query, $db) {
			$this->db = $db;
			$this->page = $page;
			$this->subpage = $subpage;
			$this->query = $query;
			$this->id = $this->db->array(sprintf("SELECT `ID` FROM `Pages` WHERE `Page`='%s' AND `Subpage`='%s'", $page, $subpage))[0];
		}
		public function info() {
			return $this->db->assoc(sprintf("SELECT * FROM `Pages` WHERE `Page`='%s' AND `Subpage`='%s'", $this->page, $this->subpage));
		}
	}
?>