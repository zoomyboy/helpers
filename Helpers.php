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
function array_pluck($key, $input) { 
    if (is_array($key) || !is_array($input)) return array(); 
    $array = array(); 
    foreach($input as $v) { 
        if(array_key_exists($key, $v)) $array[]=$v[$key]; 
    } 
    return $array; 
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

function getImageHeight($width, $svh) {
	return $width / $svh;
}
function getImageWidth($height, $svh) {
	return $height * $svh;
}
