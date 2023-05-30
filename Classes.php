<?php
	class Tools {
		protected $db;
		public function __construct($db) {
			$this->db = $db;
		}

		function ParseShortcodes($string, $file, $dom_id, $str) {
			return preg_replace_callback('/<%\[([0-9a-zA-Z:_\- ]+)\]%>/', function($matches) use ($file, $dom_id, $str) {
				if(!str_contains($matches[1], ':')) {
					switch(strtolower($matches[1])) {
						case "extension":
							return $str;
						default:
							return "ERROR";
					}
				} else {
					[$type, $string] = explode(':', strtolower($matches[1]), 2);
					switch(strtolower($type)) {
						case "g": // GLOBAL VARIABLE
							return $this->db->array("SELECT `Value` FROM `Global vars` WHERE `Name`='$string'")[0];
						case "s": // STRING
							return strtoupper($string);
						case "l": // LIST
							if($string == 'nav') {
								$links = '';
								$q = $this->db->query("SELECT `Name`, `Url`, `Page`, `Subpage` FROM `Pages` WHERE `Domain`=$dom_id AND `Menu?`=1");
								while($item = $this->db->array($q)) {
									[$name, $url, $page, $subpage] = $item;
									$active = null;
									if($page == QS_PAGE && $subpage == QS_SUBPAGE) {
										$active = 'active';
									}
									$links .= "<li class=\"nav-item\"><a href=\"$url\" class=\"nav-link text-auto $active\" style=\"color: inherit;\" aria-current=\"page\">$name</a></li>";
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
	class DB {
		protected $db;
		public function __construct($file) {
			$this->db = new Sqlite3(__ROOT__."/$file.sqlite");
		}

		public function query($string) {
			return $this->db->query($string);
		}
		public function execute($string) {
			if ($string instanceof SQLite3Result) {
				if ($string == FALSE) {
					return false;
				} else {
					return true;
				}
			} else {
				$result = $this->db->query($string);
				if($result == FALSE) {
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
				return $this->array($string, SQLITE3_ASSOC);
			} else {
				return $this->array($this->query($string), SQLITE3_ASSOC);
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
		public $info;
		public function __construct($website, $db) {
			$this->db = $db;
			$this->website = $website;
			$this->info = $db->assoc(sprintf("SELECT * FROM `Domains` WHERE `Domain`='%s'", $this->website));
		}

		public function getTheme() {
			$id = $this->db->array(sprintf("SELECT `Theme` FROM `Domains` WHERE `Domain`='%s'", $this->website))[0];
			return $this->db->assoc(sprintf("SELECT * FROM `Themes` WHERE `ID`='%s' AND `Active?`=1", $id));
		}
	}
	class Theme {
		protected $id, $db, $dom_id;
		public $info, $theme_id, $domain_id, $page, $default_theme, $theme_root, $theme_path, $theme_path_default;
		public function __construct($theme_id, $db, $dom_id, $page, $d_theme) {
			$this->db = $db;
			$this->info = $db->assoc(sprintf("SELECT * FROM `Themes` WHERE `id`='%s'", $theme_id));
			$this->theme_id = $theme_id;
			$this->domain_id = $dom_id;
			$this->page = $page;
			$this->default_theme = $d_theme;
			$this->theme_root = __ROOT__.'/Themes/';
			$this->theme_path = $this->theme_root.$this->info['Location'];
			$this->theme_path_default = $this->theme_root.$this->default_theme;
		}

		public function generate() {
			return "<!DOCTYPE html><html lang=\"en\"><head>{$this->generateHead()}</head><body id=\"googtrans\">{$this->generateBody()}</body></html>";
		}
		public function generateSectionRow($section) {
			$out = NULL; $cnt=0;
			if(strpos($section, '%') !== false) {
				$row = explode('%', $section);	array_shift($row);
				foreach ($row as $section) {
					[$width, $string] = explode('|', $section, 2);
					if(strpos($string, ',') !== false) {
						$row = explode(',', $string); 
						$out .= '<div class="col-md-'.$width.'"><div class="row">';
						foreach ($row as $string) {
							$cnt++;
							[$width, $string] = explode(';', $string);
							$out .= '<div class="col-md-'.$width.'" id="'.$cnt.'"><div class="row">';
							[$seccode, $string] = explode(':', $string);
							$tools = new Tools($this->db);
							if ($this->db->num_rows($sql = sprintf("SELECT `Type`, `File` FROM `Sections` WHERE `Code`='%s'", $seccode)) == 1) {
								[$type, $file] = $this->db->array($sql);
								if(file_exists("{$this->theme_path}/sections/$type/$file.html")) {
									$out .= $tools->ParseShortcodes(
										file_get_contents("{$this->theme_path}/sections/$type/$file.html"),
										"{$this->theme_path}/sections/$type/$file.ini",
										$this->domain_id,
										$string
									);
								} else {
									$out .= $tools->ParseShortcodes(
										file_get_contents("{$this->theme_path_default}/sections/$type/$file.html"),
										"{$this->theme_path_default}/sections/$type/$file.ini",
										$this->domain_id,
										$string
									);
								}
							}
							$out .= '</div></div>';
						}
						$out .= '</div></div>';
					} else {
						$out .= '<div class="col-md-'.$width.'"><div class="row">';
						if(strpos($string, ';')!==false) {
							$cnt++;
							[$width, $string] = explode(';', $string);
							$out .= '<div class="col-md-'.$width.'" id="'.$cnt.'"><div class="row">';
							if(strpos($string, ':')!==false) {
								[$seccode, $string] = explode(':', $string);
								$tools = new Tools($this->db);
								if ($this->db->num_rows($sql = sprintf("SELECT `Type`, `File` FROM `Sections` WHERE `Code`='%s'", $seccode)) == 1) {
									[$type, $file] = $this->db->array($sql);
									if(file_exists("{$this->theme_path}/sections/$type/$file.html")) {
										$out .= $tools->ParseShortcodes(
											file_get_contents("{$this->theme_path}/sections/$type/$file.html"),
											"{$this->theme_path}/sections/$type/$file.ini",
											$this->domain_id,
											$string
										);
									} else {
										$out .= $tools->ParseShortcodes(
											file_get_contents("{$this->theme_path_default}/sections/$type/$file.html"),
											"{$this->theme_path_default}/sections/$type/$file.ini",
											$this->domain_id,
											$string
										);
									}
								}
							}
						}
						$out .= '</div></div></div></div>';
					}
				}
			} else {

			}
			return $out;
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
			$body .= $this->getScripts();
			if(($code = $this->page->info['Sections']) != null) {
				$sections = explode("$", $code);	array_shift($sections);	$cnt=NULL;
				$body .= '<main class="container-fluid row">';
				foreach ($sections as $section) {
					preg_match_all('/([0-9]+)\[([A-Za-z0-9\|\%\#\;\:\,\-\=\+\/]+)\]/', preg_replace('/\s+/', '', preg_replace('/\s+/', '', $section, 2)), $matches);
					$row_string = $matches[2][0];	$cnt++;
					$body .= $this->generateSectionRow($row_string);
				}
				$body .= '</main>';
			} else {
				$body = 'Error: No page string was set';
			}
			return str_replace(PHP_EOL, '', $body);
		}
		// Segments
		public function getMeta($out='<!-- META -->') {
			$out .= sprintf('<meta charset="utf-8">');
			$out .= sprintf('<meta content="ie=edge" http-eqiv="X-UA-Compatible">');
			$out .= sprintf('<meta name="viewport" content="width=device-width, initial-scale=1.0">');
			$out .= sprintf('<meta name="title" content="%s">', $this->page->info['Title']);
			$out .= sprintf('<meta name="description" content="%s">', $this->page->info['Description']);
			$out .= sprintf('<meta name="author" content="%s">', "Ashwell Design");
			$out .= sprintf('<meta name="keywords" content="%s">', $this->page->info['Keywords']);
			return $out;
		}
		public function getTitle($out='<!-- TITLE -->') {
			return $out .= "<title>{$this->page->info['Title']}</title>";
		}
		public function getStyles($out = '<!-- STYLES -->') {
			$themePaths = [$this->theme_path_default, $this->theme_path];
			foreach ($themePaths as $themePath) {
				$infoPath = "{$themePath}/info.json";
		
				if(file_exists($infoPath)) {
					$styles = json_decode(file_get_contents($infoPath), true)['Styles'];
					foreach ($styles as $style) {
						$url = isset($style['URL']) ? $style['URL'] : '';
		
						if (filter_var($url, FILTER_VALIDATE_URL)) {
							$url = $style['URL'];
						} else {
							$url = "{$themePath}/css/{$url['URL']}"
						}
		
						$integrity = $style['Hash'] !== null ? sprintf(" integrity=\"%s\"", $style['Hash']) : '';
						$out .= sprintf("<noscript><link rel=\"stylesheet\" href=\"%s\"%s crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></noscript><link rel=\"stylesheet\" href=\"%s\"%s crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" as=\"style\" onload=\"this.onload=null;this.rel='stylesheet'\">", $url, $integrity, $url, $integrity);
					}
				} else {
					// TODO: Print error, Missing theme styles
				}
			}
			return $out;
		}
		public function getScripts($out='<!-- SCRIPTS -->') {
			$themePaths = [$this->theme_path_default, $this->theme_path];
			foreach ($themePaths as $themePath) {
				$infoPath = "{$themePath}/info.json";
		
				if(file_exists($infoPath)) {
					$scripts = json_decode(file_get_contents($infoPath), true)['Scripts'];
					foreach ($scripts as $script) {
						$url = isset($script['URL']) ? $script['URL'] : '';
		
						if (filter_var($url, FILTER_VALIDATE_URL)) {
							$url = $script['URL'];
						} else {
							$url = $themePath."/js/".$url['URL'];
						}
		
						$integrity = $script['Hash'] !== null ? sprintf(" integrity=\"%s\"", $script['Hash']) : '';
						$out .= sprintf("<script src=\"%s\" integrity=\"%s\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>", $url, $integrity);
					}
				} else {
					// TODO: Print error, Missing theme scripts
				}
			}
			return $out;
		}
		public function getCustomHead($out='<!-- CUSTOM HEAD -->') {
			return $out .= $this->page->info['Head'];
		}
		public function getFavicon($out = '<!-- FAVICON -->') {
			$image = $this->db->array(sprintf("SELECT `Favicon` FROM `Pages` WHERE `ID`='%s'", $this->theme_id))[0];
			$out .= sprintf('<link rel="apple-touch-icon" sizes="57x57" href="/MD_Static/Favicons/%s/apple-icon-57x57.png">', $image);
			$out .= sprintf('<link rel="apple-touch-icon" sizes="60x60" href="/MD_Static/Favicons/%s/apple-icon-60x60.png">', $image);
			$out .= sprintf('<link rel="apple-touch-icon" sizes="72x72" href="/MD_Static/Favicons/%s/apple-icon-72x72.png">', $image);
			$out .= sprintf('<link rel="apple-touch-icon" sizes="76x76" href="/MD_Static/Favicons/%s/apple-icon-76x76.png">', $image);
			$out .= sprintf('<link rel="apple-touch-icon" sizes="114x114" href="/MD_Static/Favicons/%s/apple-icon-114x114.png">', $image);
			$out .= sprintf('<link rel="apple-touch-icon" sizes="120x120" href="/MD_Static/Favicons/%s/apple-icon-120x120.png">', $image);
			$out .= sprintf('<link rel="apple-touch-icon" sizes="144x144" href="/MD_Static/Favicons/%s/apple-icon-144x144.png">', $image);
			$out .= sprintf('<link rel="apple-touch-icon" sizes="152x152" href="/MD_Static/Favicons/%s/apple-icon-152x152.png">', $image);
			$out .= sprintf('<link rel="apple-touch-icon" sizes="180x180" href="/MD_Static/Favicons/%s/apple-icon-180x180.png">', $image);
			$out .= sprintf('<link rel="icon" type="image/png" sizes="192x192"  href="/MD_Static/Favicons/%s/android-icon-192x192.png">', $image);
			$out .= sprintf('<link rel="icon" type="image/png" sizes="32x32" href="/MD_Static/Favicons/%s/favicon-32x32.png">', $image);
			$out .= sprintf('<link rel="icon" type="image/png" sizes="96x96" href="/MD_Static/Favicons/%s/favicon-96x96.png">', $image);
			$out .= sprintf('<link rel="icon" type="image/png" sizes="16x16" href="/MD_Static/Favicons/%s/favicon-16x16.png">', $image);
			$out .= sprintf('<meta name="msapplication-TileImage" content="/MD_Static/Favicons/%s/ms-icon-144x144.png">', $image);
			return $out .= '<meta name="msapplication-TileColor" content="#ffffff">';
		}
	}
	class Page {
		protected $db, $page, $subpage, $query;
		public $page_id, $info;
		public function __construct($dom_id, $page, $subpage, $query, $db) {
			$this->db = $db;
			$this->page = $page;
			$this->subpage = $subpage;
			$this->query = $query;
			if($this->db->array(sprintf("SELECT `ID` FROM `Pages` WHERE `Domain`='%s' AND `Page`='%s' AND `Subpage`='%s'", $dom_id, $page, $subpage)) != "") {
				$this->page_id = $this->db->array(sprintf("SELECT `ID` FROM `Pages` WHERE `Domain`='%s' AND `Page`='%s' AND `Subpage`='%s'", $dom_id, $page, $subpage))[0];
			} else {
				$this->page_id = false;
			}
			if($this->page_id) {
				$this->info = $this->db->assoc(sprintf("SELECT * FROM `Pages` WHERE `ID`='%s'", $this->page_id));
			} else {
				$this->info = false;
			}
		}

		public function getConfiguration($cnf) {
			return $this->db->row("SELECT `Field` FROM `Configuration` WHERE `Variable`='$cnf'")[0];
		}
	}
?>
