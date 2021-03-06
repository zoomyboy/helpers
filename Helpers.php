<?php

/**
 * Wandelt eine HEX-Farbe ins RGB-Format um
 *
 * return[0] int Rot-Kanal (0 < $rgb[0] < 255)
 * return[1] int Grün-Kanal (0 < $rgb[1] < 255)
 * return[2] int Blau-Kanal (0 < $rgb[2] < 255)
 *
 * @link https://phpdoc.org/docs/latest/guides/types.html  
 * @author Philipp Lang
 * @param string $hex Farbe im HEX-Format (mit oder ohne führendem '#')
 * 
 * @return int[]
 */
function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
	  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
	  $r = hexdec(substr($hex,0,2));
	  $g = hexdec(substr($hex,2,2));
	  $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

/**
 * Wandelt eine Farbe von RGB in HEX um (mit führendem "#")
 *
 * $rgb[0] int Rot-Kanal (0 < $rgb[0] < 255)
 * $rgb[1] int Grün-Kanal (0 < $rgb[1] < 255)
 * $rgb[2] int Blau-Kanal (0 < $rgb[2] < 255)
 *
 * @link https://phpdoc.org/docs/latest/guides/types.html  
 * @author Philipp Lang
 * @param int[] $rgb Farbe im RGB-Format
 *
 * @return string
 */
function rgb2hex($rgb) {
   $hex = "#";
   $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

   return $hex; // returns the hex value including the number sign (#)
}

function parseAsHtml($data) {
	$dom = \DOMDocument::loadHTML(strip_tags(str_replace("&nbsp;", "\r\n", $data), '<li></li><ul></ul><p></p>'));
	$dom = $dom->getElementsByTagName('*');
	$arr = [];
	foreach($dom as $value) {
		$arr[] = $value;
	}
	$dom = array_reduce($arr, function($carry, $item) {
		if (!in_array($item->nodeName, ['p', 'li'])) {return $carry;}
		if ($carry == null) {
			return [['node' => $item->nodeName, 'values' => [$item->textContent]]];
		} else {
			$last = array_pop($carry);
			if ($last['node'] != $item->nodeName) {
				$carry[] = $last;
				$carry[] = ['node' => $item->nodeName, 'values' => [$item->textContent]];
			} else {
				$last['values'][] = $item->textContent;
				$carry[] = $last;
			}
		}
		return $carry;
	});

	return $dom;
}

/**
 * Gets an array with all Values of the given key
 *
 * @author Kelly M
 * @link http://php.net/manual/de/function.array-map.php 
 */
if (!function_exists('array_pluck')) {
	//Make sure we dont collide with the laravel version of array_pluck
	function array_pluck($key, $input) { 
		if (is_array($key) || !is_array($input)) return array(); 
		$array = array(); 
		foreach($input as $v) { 
			if(array_key_exists($key, $v)) $array[]=$v[$key]; 
		} 
		return $array; 
	} 
} else {
	function l_array_pluck($key, $input) { 
		if (is_array($key) || !is_array($input)) return array(); 
		$array = array(); 
		foreach($input as $v) { 
			if(array_key_exists($key, $v)) $array[]=$v[$key]; 
		} 
		return $array; 
	} 
}

/**
 * Downloads an Image from an URL. Returns the saved path
 * 
 * return['src'] string The source of the saved image (=$target)
 * return['originalSrc'] string Original Source of image (=$source)
 * return['size']
 * 		return['size'][0]	X Dimentions in Pixel
 * 		return['size'][1]	Y Dimentions in Pixel
 * return['displaySize']
 * 		return['displaySize'][0] X Dimentions in mm of displayed image
 * 		return['displaySize'][1] Y Dimentions in mm of displayed image
 *
 * @param string $source
 * @param string $target Taget - without file extension
 *
 * @return array
 */
function downloadImage($source, $target) {
	//Guess extension of target file
	if (pathinfo($source, PATHINFO_EXTENSION)) {
		$target .= '.' . pathinfo($source, PATHINFO_EXTENSION);
	}

	if (!file_exists($target)) {
		$file = file_get_contents($source);
		file_put_contents($target, $file);
	}

	$i++;
	$size = getimagesize($target);
	return [
		'originalSrc' => $source,
		'src' => $target,
		'size' => [$size[0], $size[1]],
		'displaySize' => [$size[0] / 300 * 2.54 * 10, $size[1] / 300 * 2.54 * 10]
	];
}

function mkdirRec($dir) {
	if (!is_dir($dir)) {
		mkdir ($dir, 0777, true);
	}
}

/**
 * Parses a string as CSS and returns key-value pairs
 * 
 * @param string $css The css string (e.g. "family: Helvetica; color: #ff0000")
 *
 * @return array
 */
function parseCss($css) {
	$css = trim(trim($css), ';');
	$rules = explode(';', $css);
	$ret = [];
	foreach($rules as $rule) {
		$rule = trim($rule);
		$ruleAndValue = explode(':', $rule);
		$ret[trim($ruleAndValue[0])] = trim($ruleAndValue[1]);
	};

	return $ret;
}

/**
 * Returns Array of given css or the array itself
 *
 * @param string|array $cssOrArray
 *
 * @return array
 */
function cssOrArray($cssOrArray) {
	return (is_array($cssOrArray)) ? $cssOrArray : parseCss($cssOrArray);
}

function getImageHeight($width, $svh) {
	return $width / $svh;
}
function getImageWidth($height, $svh) {
	return $height * $svh;
}

/**
 * Zählt Werte auf wie 1, 2, 3, 4 und gibt diese Aufzählung als String zurück
 *
 * @param array $values Die Werte
 *
 * @return string
 */
function enum($values) {
	if (count($values) == 0) {return '';}
	if(count($values) == 1) {
		return $values[ 0 ];
	} else {
		$end = array_pop($values);
		
		return implode(', ', $values) . ' und ' . $end;
	}
} 

/**
 * Build path out of a givven filename, path and extension
 *
 * @param string $path     The path to use (with or without trailing '/')
 * @param string $filename The filename to use (with or without extension)
 * @param string $ext      The extension to use instead of the file extension or to concat to the filename
 */
function buildPath($path = null, $filename = null, $ext = null) {
	$ret = '';

	$path = (!is_null($path) && $path) ? rtrim($path, '/') : null;
	$filename = (!is_null($filename) && $filename) ? trim($filename, '/') : null;
	$ext = (!is_null($ext) && $ext) ? trim($ext, '.') : null;

	if (!$ext && !$filename) {return $path.'/';}

	//set extension and filename (or return just the path)
	if ($filename && (!strpos('.', $filename) || $ext)) {
		if (!$ext) {
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
		}
		$filename = pathinfo($filename, PATHINFO_FILENAME);
	}

	if ($path) {
		if ($filename && $ext) {
			return $path.'/'.$filename.'.'.$ext;
		} else {
			trigger_error("WRONG");        //this should never happen!
		}
	} else {
		return $filename.'.'.$ext;
	}
}

function buildFilename($filename, $extension) {
	if (strpos(0, $extension) != '.') {$extension = pathinfo($extension, PATHINFO_EXTENSION);}
	return buildPath(false, $filename, $extension);
}

/**
 * Baut einen HTML--Attribut-Sstring der Form
 * {schlüssel}="{Wert}" von einem PHP-Array
 *
 * @param array $attributes Das eingabeArray - assoziativ
 *
 * @return string Die Attribut-Zeichenkette
 */
function arrToAttr($attributes) {
	$output = '';

	foreach($attributes as $key => $value){
		$output .= $key.'="'.$value.'" ';
	}

	return $output;
}

if (!function_exists ('dir_empty')) {
	function dirEmpty($dir) {
		return count(glob($dir."/*")) === 0;
	}
} else {
	function r_dirEmpty($dir) {
		return count(glob($dir."/*")) === 0;
	}
}
