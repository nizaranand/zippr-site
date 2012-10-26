<?php

/**
 * echo the font family based on option id
 */
 
function wpv_font_family($opt_id) {
	echo wpv_get_font_family($opt_id);
}

/**
 * return the font family based on option id
 */
 
function wpv_get_font_family($opt_id) {
	global $wpv_fonts, $used_google_fonts, $used_local_fonts;
	
	$font = wpv_get_option($opt_id . '-face');
	
	// we need the google and local fonts cached, so we can generate the correct @import rules
	
	if(isset($wpv_fonts[$font]['gf'])) {
		$used_google_fonts[$font][] = wpv_get_option($opt_id . '-weight');
	} elseif(isset($wpv_fonts[$font]['local'])) {
		$used_local_fonts[]= $wpv_fonts[$font]['family'];
	}
	
	return $wpv_fonts[$font]['family'];
}

/**
 * return the font family based on option id
 */
 
function wpv_get_font_url($font, $weight='') {
	global $wpv_fonts;
	
	if(isset($wpv_fonts[$font]['gf'])) {
		// this is a google font
		return "http://fonts.googleapis.com/css?family=".urlencode($font).':'.$weight."&subset=cyrillic,greek,latin";
	} elseif(isset($wpv_fonts[$font]['local'])) {
		// this is a local @font-face font
		
		return WPV_FONTS_URI."$font/stylesheet.css";
	}
	
	return '';
}

function wpv_finalize_custom_css() {
	global $used_google_fonts, $used_local_fonts, $mocked;

	$font_imports = '';
	$font_imports_urls = array();
	
	if(is_array($used_google_fonts) && count($used_google_fonts)) {
		$param = array();
		foreach($used_google_fonts as $font => $weights) {
			$param[] = urlencode($font).':'.implode(',', array_unique($weights));
		}
		$param = implode('|', $param);

		$font_imports .= "@import url('http://fonts.googleapis.com/css?family=".$param."&subset=cyrillic,greek,latin');\n";
		$font_imports_urls['gfonts'] = "http://fonts.googleapis.com/css?family=".$param."&subset=cyrillic,greek,latin";
	}
	
	if(is_array($used_local_fonts) && count($used_local_fonts)) {
		foreach($used_local_fonts as $font) {
			$font_imports .= "@import url('".WPV_FONTS_URI."$font/stylesheet.css');\n";
			$font_imports_urls[$font] = WPV_FONTS_URI."$font/stylesheet.css";
		}
	}

	if(!isset($mocked)) {
		wpv_update_option('external-fonts', $font_imports_urls);

		wpvge('custom_css');
		
		return ob_get_clean();
	} else {
		return array(
			'styles' => ob_get_clean(),
			'imports' => $font_imports,
		);
	}
}

function wpv_hex2rgba($color, $opacity) {
	return wpv_hex2rgba_plain(wpv_get_option($color), wpv_get_option($opacity));
}

function wpv_hex2rgba_plain($color, $opacity) {
	$result = '';
	if(!empty($color) && $color[0] === '#') {
		$result .= 'rgba(';
		$result .= (string)hexdec($color[1].$color[2]) . ', ';
		$result .= (string)hexdec($color[3].$color[4]) . ', ';
		$result .= (string)hexdec($color[5].$color[6]) . ', ';
		$result .= round($opacity,2) . ')';
		
		return $result;
	}
	
	return '';
}

function wpv_grad_filter($color, $opacity = 1) {
	if($color[0] != '#') {
		return '#00000000';
	}
	
	$color = substr($color, 1);
	
	$result = '#';
	$opacity = dechex(floor($opacity*255));
	if(strlen($opacity) == 1) {
		$opacity .= $opacity;
	}
	$result .= $opacity;
	
	if(strlen($color) == 3) {
		$color .= $color;
	}
	
	$result .= $color;
	
	return $result;
}

function wpv_font($opt, $important=false) {
?>
	font: <?php wpvge($opt.'-weight')?> <?php wpvge($opt.'-size')?>px / <?php wpvge($opt.'-lheight')?>px <?php wpv_font_family($opt);?><?php echo $important ? ' !important' : ''?>;
<?php
}

function wpv_background($opt, $important=false, $skipColor = false) {
	$color   = trim(wpv_get_option("$opt-color"));
	$opacity = wpv_get_option("$opt-opacity");
	$hasColor = (!empty($color) && $color != 'transparent' && ($opacity != 0 || $opacity === false));
	if (!$hasColor && wpv_get_option("$opt-image") == '') {
		echo 'background: transparent' .($important?' !important':'') . ";\n";
		return;
	}

	$img = wpv_get_option("$opt-image");
	$bg[] = $color;
	if(!empty($img)) {
		$bg[] = "url('$img')";
		$bg[] = wpv_get_option("$opt-repeat");
		$bg[] = wpv_get_option("$opt-attachment");
		$bg[] = wpv_get_option("$opt-position");
	}
	if ($important) {
		$bg[] = '!important';
	}
	echo 'background: ' . implode(' ', $bg) . ";\n";
	
	if(!$skipColor && $hasColor && $opacity != 0) {
		echo "\tbackground-color: " . wpv_hex2rgba("$opt-color", "$opt-opacity").($important?' !important':'') . ";\n";
	}
}

function wpv_background_ie8($opt, $important=false) {
	$color = wpv_get_option("$opt-color");
	$img   = wpv_get_option("$opt-image");
	
	if(!empty($color) && !empty($img)) {
		wpv_background($opt, $important, true);
		?>
	background-color: <?php wpvge("$opt-color")?>;
	<?php
		return;
	}
	
	if(empty($color))
		return;
	
	$opacity = wpv_get_option("$opt-opacity");

	if ( !empty($color) && wpv_is_hex($color) && !empty($opacity) ):
		$alpha = dechex((float)$opacity * 255);
	?>
		zoom: 1;
		background: none !important;
		-ms-filter: "progid:DXImageTransform.Microsoft.gradient( startColorstr=#<?php echo $alpha . str_replace('#', '', $color)?>, endColorstr=#<?php echo $alpha . str_replace('#', '', $color) ?>)";
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=#<?php echo $alpha . str_replace('#', '', $color)?>, endColorstr=#<?php echo $alpha . str_replace('#', '', $color) ?>);
	<?php endif;
}

/**
 * convert a rgb hex string to decimal rgb values
 */
function wpv_hex2rgb($hex) {
	if($hex[0] == '#')
		$hex = substr($hex, 1);

	if(strlen($hex) == 3) {
		$hex = $hex[0].$hex[0].
			   $hex[1].$hex[1].
			   $hex[2].$hex[2];
	}

	$r = hexdec($hex[0].$hex[1])/255;
	$g = hexdec($hex[2].$hex[3])/255;
	$b = hexdec($hex[4].$hex[5])/255;

	return array($r,$g,$b);
}

/**
 * convert decimal rgb values to a rgb hex string
 */
function wpv_rgb2hex($r, $g, $b) {
	$r = dechex(intval($r*255));
	$g = dechex(intval($g*255));
	$b = dechex(intval($b*255));

	if(strlen($r) == 1)
		$r .= $r;
	if(strlen($g) == 1)
		$g .= $g;
	if(strlen($b) == 1)
		$b .= $b;

	return '#'.$r.$g.$b;
}

/**
 * conver a rgb hex string to decimal hsl values
 */
function wpv_hex2hsl($hex) {
	list($r, $g, $b) = wpv_hex2rgb($hex);

	$max = max($r, $g, $b);
	$min = min($r, $g, $b);
    $h = $s = 0;
    $l = ($max + $min) / 2;
    
    if($max != $min){
		$d = $max - $min;
		$s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
		switch($max){
			case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
			case $g: $h = ($b - $r) / $d + 2; break;
			case $b: $h = ($r - $g) / $d + 4; break;
		}
		$h /= 6;
    }
    
    return array($h * 360, $s, $l);
}

/**
 * convert decimal hsl values to a rgb hex string
 */
function wpv_hsl2hex($h, $s, $l) {
	$r = $g = $b = 0;
	if ($s === 0) {
      $r = $g = $b = $l; // achromatic
    } else {
		$h = $h / 360;

		$q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
		$p = 2 * $l - $q;
		$r = wpv_hue2rgb($p, $q, $h + 1/3);
		$g = wpv_hue2rgb($p, $q, $h);
      	$b = wpv_hue2rgb($p, $q, $h - 1/3);
    }
	return wpv_rgb2hex($r, $g, $b);
}

function wpv_hue2rgb($p, $q, $t) {
	if($t < 0)
     	$t += 1;
    if($t > 1) 
  		$t -= 1;
    if($t < 1/6) 
		return $p + ($q - $p) * 6 * $t;
    if($t < 1/2) 
    	return $q;
    if($t < 2/3) 
    	return $p + ($q - $p) * (2/3 - $t) * 6;
    return $p;
}

function wpv_is_hex($hex) {
	return preg_match('/^#?([a-f0-9]{3}){1,2}$/i', $hex);
}

// modify hsl values in a hex string
function wpv_hex_set_hue($hex, $new_hue) {
	if(wpv_is_hex($hex)) {
		list($h,$s,$l) = wpv_hex2hsl($hex);
		return wpv_hsl2hex($new_hue, $s, $l);
	}
	return $hex;
}

function wpv_hex_set_saturation($hex, $new_saturation) {
	if(wpv_is_hex($hex)) {
		list($h,$s,$l) = wpv_hex2hsl($hex);
		return wpv_hsl2hex($h, $new_saturation, $l);
	}
	return $hex;
}

function wpv_hex_set_luminance($hex, $new_luminance) {
	if(wpv_is_hex($hex)) {
		list($h,$s,$l) = wpv_hex2hsl($hex);
		return wpv_hsl2hex($h, $s, $new_luminance);
	}
	return $hex;
}

function wpv_darkenColor($baseColor, $contrast = 0.2, $alpha = null) {
	$color = $baseColor;
	if ( wpv_is_hex($baseColor) ) {
		list($r,$g,$b) = wpv_hex2rgb($baseColor);
		$r *= 255; $g *= 255; $b *= 255;
		$r2 = max(0, $r - $r * $contrast);
		$g2 = max(0, $g - $g * $contrast);
		$b2 = max(0, $b - $b * $contrast);
		$color = array(floor($r2), floor($g2), floor($b2));
		if ( !is_null($alpha) ) {
			$color[] = (float)$alpha;
			$color = 'rgba(' . implode(', ', $color) . ')';
		}
		else {
			$color = 'rgb(' . implode(', ', $color) . ')';
		}
	}
	return $color;
}

function wpv_lightenColor($baseColor, $contrast = 0.2, $alpha = null) {
	$color = $baseColor;
	if ( wpv_is_hex($baseColor) ) {
		list($r,$g,$b) = wpv_hex2rgb($baseColor);
		$r *= 255; $g *= 255; $b *= 255;
		$r1 = min(255, $r + (255 - $r) * $contrast);
		$g1 = min(255, $g + (255 - $g) * $contrast);
		$b1 = min(255, $b + (255 - $b) * $contrast);
		$color = array(floor($r1), floor($g1), floor($b1));
		if ( !is_null($alpha) ) {
			$color[] = (float)$alpha;
			$color = 'rgba(' . implode(', ', $color) . ')';
		}
		else {
			$color = 'rgb(' . implode(', ', $color) . ')';
		}
	}
	return $color;
}

function wpv_outsetBorderColor($baseColor, $contrast = 0.2, $alpha = null) {
	$light = wpv_lightenColor($baseColor, $contrast, $alpha);
	$dark  = wpv_darkenColor($baseColor, $contrast, $alpha);
	return "border-color: $light $dark $dark $light !important;\n";
}

function wpv_insetBorderColor($baseColor, $contrast = 0.2, $alpha = null) {
	$light = wpv_lightenColor($baseColor, $contrast, $alpha);
	$dark  = wpv_darkenColor($baseColor, $contrast, $alpha);
	return "border-color: $dark $light $light $dark !important;\n";
}

function wpv_colorNegative($color) { // Not currently used
	if(wpv_is_hex($color)) {
		list($h, $s, $l) = wpv_hex2hsl($color);
		$color = wpv_hsl2hex((180 + $h) % 360, $s, .5 + (.5 - $l));
	}
	return $color;
}

class WpvColor {
	
	protected $_red;
	protected $_green;
	protected $_blue;
	protected $_alpha;
	
	public static function createFromString($str) {
		$str = strtolower(trim((string) $str));
		
		// Create from empty string or "transparent"
		if (!$str || $str == 'transparent') {
			return new self(0, 0, 0, 0);
		}
		
		// Create from hex
		if($str[0] == '#') {
			
			// Create from 3X hex (#RGB)
			if (strlen($str) == 4) {
				return new self(
					hexdec($str[1].$str[1]),
					hexdec($str[2].$str[2]),
					hexdec($str[3].$str[3])
				);
			}
			
			// Create from 8X hex (#AARRGGBB)
			if (strlen($str) == 9) {
				return new self(
					hexdec($str[3].$str[4])/255,
					hexdec($str[5].$str[6]),
					hexdec($str[7].$str[8]),
					hexdec($str[1].$str[2])
				);
			}
			
			// Create from 6X hex (#RRGGBB)
			if (strlen($str) == 7) {
				return new self(
					hexdec($str[1].$str[2]),
					hexdec($str[3].$str[4]),
					hexdec($str[5].$str[6])
				);
			}
		} elseif(preg_match('/rgba?\(([\d\s,]+)\)/i', $str, $matches) && ($channels = preg_split('/\s*,\s*/', $matches[1])) && count($channels) >=3) {
			$a = 1;
			if(count($channels) == 4)
				$a = (int)$channels[3];

			return new self((int)$channels[0], (int)$channels[1], (int)$channels[2], $a);
		}

		// error
		throw new Exception('Invalid color string.');
	}
	
	public static function createFromWpOption($option) {
		return self::createFromString(wpv_get_option($option));
	}
	
	public function __construct($r = 0, $g = 0, $b = 0, $a = 1) {
		$this->setRed($r);
		$this->setGreen($g);
		$this->setBlue($b);
		$this->setAlpha($a);
	}
	
	public function __set($name, $value) {
		$method = array($this, 'set' . strtoupper($name));
		if (is_callable($method)) {
			call_user_func($method, $value);
		}
		return $this;
	}
	
	public function setRed($value) {
		$this->_red = min(max((int) $value, 0), 255);
	}
	
	public function setGreen($value) {
		$this->_green = min(max((int) $value, 0), 255);
	}
	
	public function setBlue($value) {
		$this->_blue = min(max((int) $value, 0), 255);
	}
	
	public function setAlpha($value) {
		$this->_alpha = min(max((float) $value, 0), 1);
	}
	
	public function toCssString() {
		if ($this->_alpha == 0) {
			return 'transparent';
		}
		
		if ($this->_alpha == 1) {
			return $this->toHex();
		}
		
		return $this->toRgba();
	}
	
	public function toHex($includeAlpha = false) {
		$r = dechex(intval($this->_red));
		$g = dechex(intval($this->_green));
		$b = dechex(intval($this->_blue));
		
		if(strlen($r) == 1)	$r = "0$r";
		if(strlen($g) == 1)	$g = "0$g";
		if(strlen($b) == 1)	$b = "0$b";
		
		if (!!$includeAlpha) {
			$a = dechex(round($this->_alpha * 255, 2));
			if(strlen($a) == 1)	$a .= $a;
			return '#'.$a.$r.$g.$b;
		}
		
		return '#'.$r.$g.$b;
	}
	
	public function toRgb() {
		return 'rgb(' . floor($this->_red) . ', ' .
						floor($this->_green) . ', ' .
						floor($this->_blue) . ')';
	}
	
	public function toRgba() {
		return 'rgba('. floor($this->_red) . ', ' .
						floor($this->_green) . ', ' .
						floor($this->_blue) . ', ' .
						round($this->_alpha, 2) . ')';
	}
	
	public function darken($contrast = 0.2, $modify = true) {
		if($contrast < 0)
			return $this->lighten(-$contrast, $modify);

		$r = $this->_red   - $this->_red   * $contrast;
		$g = $this->_green - $this->_green * $contrast;
		$b = $this->_blue  - $this->_blue  * $contrast;

		if($modify) {
			$this->setRed  ($r);
			$this->setGreen($g);
			$this->setBlue ($b);
			return $this;
		}

		return new WpvColor($r, $g, $b, $this->_alpha);
	}
	
	public function lighten($contrast = 0.2, $modify = true) {
		if($contrast < 0)
			return $this->darken(-$contrast, $modify);

		$r = $this->_red   + ( 255 - $this->_red   ) * $contrast;
		$g = $this->_green + ( 255 - $this->_green ) * $contrast;
		$b = $this->_blue  + ( 255 - $this->_blue  ) * $contrast;

		if($modify) {
			$this->setRed  ($r);
			$this->setGreen($g);
			$this->setBlue ($b);
			return $this;
		}

		return new WpvColor($r, $g, $b, $this->_alpha);
	}

	public function grayscale() {
		$gc = $this->_red*0.299 + $this->_green*0.587 + $this->_blue*0.114;
		//$gc = ($this->_red + $this->_green + $this->_blue)/3;
		return new WpvColor($gc, $gc, $gc, $this->_alpha);	
	}

	public function isDark() {
		$gray = $this->grayscale();
		return $gray->_red < 180;
	}

	public function isDarker($color) {
		$color = $color->grayscale();
		$gray = $this->grayscale();
		return $gray->_red < $color->_red;
	}

	// returns a color that if used for text over the current color would be easily readable
	public function readable() {
		$contrast = 1;
		if($this->isDark())
			$contrast = -$contrast;

		$darkest = new WpvColor(68, 68, 68, $this->_alpha);
		$lightest = new WpvColor(255, 255, 255, $this->_alpha);

		$text = $this->darken($contrast, false);

		if($text->isDarker($darkest))
			return $darkest;

		if($lightest->isDarker($text))
			return $lightest;

		return $text;
	}

	// returns a color that is suitable for text-shadow when the current color is used as background
	public function textShadow() {
		$contrast = 0.25;
		if($this->isDark())
			$contrast = -$contrast;

		return $this->lighten($contrast, false);
	}

	// returns a hover background color
	public function hover() {
		if($this->isDark())
			return $this->darken(0.1, false);

		return $this->lighten(0.6, false);
	}

	// returns a border color
	public function border() {
		if($this->isDark())
			return $this->lighten(0.1, false);

		return $this->darken(0.1, false);
	}
}

function wpv_linear_gradient(WpvColor $startColor, WpvColor $endColor, $orientation='vertical') {
	
	$start  = $startColor->toCssString();
	$start8 = $startColor->toHex(true);
	$end    = $endColor->toCssString();
	$end8   = $endColor->toHex(true);

	$deg = 0;
	if($orientation == 'horizontal') {
		$deg = 0;
	} elseif($orientation == 'vertical') {
		$deg = 90;
	} else {
		$deg = (int)$orientation;
	}

	$iedeg = (int)((abs($deg)%90) <= 45);

	$css = '';

	$css .= "background-image: -webkit-linear-gradient({$deg}deg, $start, $end);\n\t";
	$css .= "background-image:    -moz-linear-gradient({$deg}deg, $start, $end);\n\t";
	$css .= "background-image:     -ms-linear-gradient({$deg}deg, $start, $end);\n\t";
	$css .= "background-image:      -o-linear-gradient({$deg}deg, $start, $end);\n\t";
	$css .= "background-image:         linear-gradient({$deg}deg, $start, $end);\n\t";
	$css .= "filter:       progid:DXImageTransform.Microsoft.gradient(startColorStr='$start8', EndColorStr='$end8',GradientType=" . $iedeg . ");\n\t";
	$css .= "-ms-filter: \"progid:DXImageTransform.Microsoft.gradient(startColorStr='$start8', EndColorStr='$end8',GradientType=" . $iedeg . ")\";\n";
	return $css;
}
