<?php
	foreach (glob(__ROOT__.'\Libs\*.php') as $file) {
		if($file != __FILE__) {
			require_once($file);
		}
	}
	class Tools {
		protected $db;
		public function __construct($db) {
			$this->db = $db;
		}

		function ParseShortcodes($string, $file='', $dom_id='') {
			return preg_replace_callback('/<%\[([0-9a-zA-Z:_\- ]+)\]%>/', function($matches) use ($file, $dom_id) {
				[$type, $string] = explode(':', strtolower($matches[1]), 2);
				switch(strtolower($type)) {
					case "g": // GLOBAL VARIABLE
						return $this->db->array("SELECT `Value` FROM `Global vars` WHERE `Name`='$string'")[0];
					case "s": // STRING
						return strtoupper($string);
					case "l": // LIST
						if($string == 'nav') {
							$links = '';
							$q = $this->db->query("SELECT `Name`, `Url` FROM `Pages` WHERE `Domain`=$dom_id AND `Menu?`=1");
							while($item = $this->db->array($q)) {
								[$name, $url] = $item;
								$links .= "<li class=\"nav-item\"><a href=\"$url\" class=\"nav-link\" style=\"color: inherit;\" aria-current=\"page\">$name</a></li>";
							}
							return $links;
						} else {
							$items = explode(';', $this->db->array("SELECT `Value` FROM `Global lists` WHERE `Name`='$string'")[0]);
							$links = '';
							foreach($items as $item) {
								if($item != '') {
									[$name, $link] = explode(':', $item, 2);
									$links .= "<li class=\"nav-item\"><a href=\"$link\" class=\"nav-link\" style=\"color: inherit;\" aria-current=\"page\">$name</a></li>";
								}
							}
							return $links;
						}
					case "t": // PREDETERMINED THEME
						$ini_array = parse_ini_file($file, true);
						return $ini_array[$string];
					default:
						return "ERROR";
				}
			}, $string);
		}
		function array_to_ini_string($array) {
			$output = '';
			foreach ($array as $section => $values) {
				$output .= "[$section]\n";
				foreach ($values as $key => $value) {
					$output .= "$key = $value\n";
				}
				$output .= "\n";
			}
			return $output;
		}
		function get_mime_type($filename) {
			$idx = explode( '.', $filename );
			$count_explode = count($idx);
			$idx = strtolower($idx[$count_explode-1]);
			$types = array(
				'txt' => 'text/plain',
				'htm' => 'text/html',
				'html' => 'text/html',
				'php' => 'text/html',
				'css' => 'text/css',
				'js' => 'application/javascript',
				'json' => 'application/json',
				'xml' => 'application/xml',
				'swf' => 'application/x-shockwave-flash',
				'flv' => 'video/x-flv',
		
				// images
				'png' => 'image/png',
				'jpe' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp',
				'ico' => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif' => 'image/tiff',
				'svg' => 'image/svg+xml',
				'svgz' => 'image/svg+xml',
		
				// archives
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed',
		
				// audio/video
				'mp3' => 'audio/mpeg',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime',
		
				// adobe
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript',
		
				// ms office
				'doc' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint',
				'docx' => 'application/msword',
				'xlsx' => 'application/vnd.ms-excel',
				'pptx' => 'application/vnd.ms-powerpoint',

				// open office
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			);
			if(isset($types[$idx])) {
				return $types[$idx];
			} else {
				return 'application/octet-stream';
			}
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
			$tools = new Tools($this->db);
			return file_put_contents($this->file, $tools->array_to_ini_string($arr));
		}
	}
	class DB {
		protected $db;
		public function __construct($file) {
			$this->db = new Sqlite3(__ROOT__."\\$file.sqlite");
		}

		public function query($string) {
			return $this->db->query($string);
		}
		public function execute($string) {
			if ($string instanceof SQLite3Result) {
				if ($this->lastErrorMsg() !== 'not an error') {
					return false;
				} else {
					return true;
				}
			} else {
				$result = $this->query($string);
				if ($this->lastErrorMsg() !== 'not an error') {
					return false;
				} else {
					return true;
				}
			}
		}		
		public function array($string) {
			if($string instanceof SQLite3Result) {
				return ($string)->fetchArray();
			} else {
				return ($this->query($string))->fetchArray();
			}
		}
		public function assoc($string) {
			if($string instanceof SQLite3Result) {
				return ($string)->fetchArray(SQLITE3_ASSOC);
			} else {
				return ($this->query($string))->fetchArray(SQLITE3_ASSOC);
			}
		}
		public function row($string) {
			if($string instanceof SQLite3Result) {
				return sqlite3_fetch_row($string);
			} else {
				return sqlite3_fetch_row($this->query($string));
			}
		}
		public function num_rows($string) {
			$q = $this->query($string);
			$count = 0;
			while ($row = $this->array($q)) {
				$count++;
			}
			return $count;
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
		protected $id, $db, $dom_id;
		public function __construct($id, $db, $dom_id) {
			$this->db = $db;
			$this->domain_id = $dom_id;
			$this->id = $id;
			$this->name = $db->array(sprintf("SELECT `Name` FROM `Themes` WHERE `id`='%s'", $this->id))[0];
			$this->path = __ROOT__.'\\Themes\\'.$this->name;
		}
		public function info() {
			return $this->db->assoc(sprintf("SELECT * FROM `Themes` WHERE `ID`='%s'", $this->id));
		}
		public function generate() {
			return "<!DOCTYPE html><html lang=\"en-GB\"><head>{$this->generateHead()}</head><body>{$this->generateBody()}</body></html>";
		}
		// Halves
		public function generateHead($head='') {
			$head .= $this->getMeta();
			$head .= $this->getFavicon();
			$head .= $this->getTitle();
			$head .= $this->getStyles();
			$head .= $this->getCustomHead();
			return $head;
		}
		public function generateBody($body='') {
			$tools = new Tools($this->db);
			$body .= $this->getScripts();
			if(($code = $this->db->array(sprintf("SELECT `Sections` FROM `Pages` WHERE `ID`='%s'", $this->domain_id))[0]) != null) {
				$columns = explode("#", $code);
				$seccode = $secext = NULL;
				$cnt = 1;
				$body .= '<main class="container-fluid"><div class="row">';
				array_shift($columns);
				foreach($columns as $column) {
					[$width, $section_string] = explode(';', $column);
					$body .= "<div class=\"col-md-$width section-col\" id=\"$cnt\">";
					$sections = explode(',', $section_string);
					foreach($sections as $section) {
						[$seccode, $secext] = explode(':', $section);
						if($this->db->num_rows($sql = "SELECT `Type`, `File` FROM `Sections` WHERE `Code`='$seccode'") == 1) {
							[$type, $file] = $this->db->array($sql);
							$body .= $tools->ParseShortcodes(
								file_get_contents("{$this->path}\\$type\\$file.php"),
								"{$this->path}\\$type\\$file.ini",
								$this->domain_id);
							unset($secext);
						}
					}
					$body .= "</div>";
					$cnt++;
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
			return $out .= "<title>{$this->db->array(sprintf("SELECT `Title` FROM `Pages` WHERE `ID`='%s'", $this->id))[0]}</title>";
		}
		public function getStyles($out='<!-- STYLES -->') {
			foreach(json_decode(file_get_contents("{$this->path}\\styles.json"), true) as $style) {
				$out .= sprintf("<noscript><link rel=\"stylesheet\" href=\"%s\" integrity=\"%s\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></noscript><link rel=\"stylesheet\" href=\"%s\" integrity=\"%s\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" as=\"style\" onload=\"this.onload=null;this.rel='stylesheet'\">", $style['URL'], $style['Hash'], $style['URL'], $style['Hash']);
			}
			return $out;
		}
		public function getScripts($out='<!-- SCRIPTS -->') {
			foreach(json_decode(file_get_contents("{$this->path}\\scripts.json"), true) as $script) {
				$out .= sprintf("<script src=\"%s\" integrity=\"%s\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>", $script['URL'], $script['Hash']);
			}
			return $out;
		}
		public function getCustomHead($out='<!-- CUSTOM HEAD -->') {
			return $out .= $this->db->array(sprintf("SELECT `Head` FROM `Pages` WHERE `ID`='%s'", $this->id))[0];
		}
		public function getFavicon($out = '<!-- FAVICON -->') {
			$image = '#';//$this->db->array(sprintf("SELECT `Styles` FROM `Pages` WHERE `ID`='%s'", $this->id))[0];
			return $out .= "<link rel=\"icon\" href=\"$image\" type=\"image/png\">";
		}
	}
	class Page {
		protected $db, $page, $subpage, $query;
		public function __construct($page, $subpage, $query, $db) {
			$this->db = $db;
			$this->page = $page;
			$this->subpage = $subpage;
			$this->query = $query;
			if($this->db->array(sprintf("SELECT `ID` FROM `Pages` WHERE `Page`='%s' AND `Subpage`='%s'", $page, $subpage)) != "") {
				$this->id = $this->db->array(sprintf("SELECT `ID` FROM `Pages` WHERE `Page`='%s' AND `Subpage`='%s'", $page, $subpage))[0];
			} else {
				$this->id = false;
			}
		}
		public function info() {
			return $this->db->assoc(sprintf("SELECT * FROM `Pages` WHERE `Page`='%s' AND `Subpage`='%s'", $this->page, $this->subpage));
		}
	}
?>