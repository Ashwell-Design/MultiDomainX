<?php
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
?>