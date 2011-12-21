<?php

	class lessc_ext extends lessc {
		function lib_invert($color) {
			$color[1] = 255 - $color[1];
			$color[2] = 255 - $color[2];
			$color[3] = 255 - $color[3];
			return $color;
		}

		function lib_flip($color) {
			$hsl = $this->toHSL($color);
			$hsl[3] = 100 - $hsl[3];
			return $this->toRGB($hsl);
		}

		function lib_bw($color) {
			return $this->lib_intensity($color) < 50? array('color', 0, 0, 0) : array('color', 255, 255, 255);
		}

		function lib_intensity($color) {
			$i = ($color[1] * .299) + ($color[2] * .587) + ($color[3] * .114);
			return $i / 2.55;
		}

		function lib_opacity($args) {
			list($color, $alpha) = $this->colorArgs($args);

			if (!isset($color[4]))
				$color[] = $this->clamp($alpha);
			return $color;
		}

		public static function ccompile($in, $out) {
			if (!is_file($out) || filemtime($in) > filemtime($out)) {
				$less = new self($in);
				file_put_contents($out, $less->parse());
				return true;
			}

			return false;
		}
	}

?>