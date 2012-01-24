<?php

	@set_time_limit(120);

	require 'lessc.inc.php';
	require 'lessc_ext.inc.php';

	define('NL', "\n");

	function license($name) {
		return "/*\n"
			. " * ".ucfirst($name)." jQueryUI theme\n"
			. " * Copyright ".date('Y')." Martijn W. van der Lee\n"
			. " * Licensed under Creative Commons Attribution-Share Alike 3.0\n"
			. " * Build ".date('Y-m-d H:i:s')."\n"
			. " */\n";
	}

	$less = new lessc_ext();
	$less->importDir = dirname(__FILE__);

	foreach (scandir(dirname(__FILE__).'/themes') as $file) {
		$path = dirname(__FILE__).'/themes/'.$file;
		if (is_file($path)) {
			generate($less, $path, 'theme');
		}
	}

	function generate($less, $path, $scope = '') {
		try {
			$filename = pathinfo($path, PATHINFO_FILENAME);

			$license = license($filename);

			$theme = file_get_contents($path) . NL . '@import "core/after-global.less";';

			$css_jquery = $less->parse(wrap($theme . NL . '@import "core/after-jqueryui.less";', $scope));
			file_put_contents(dirname(__FILE__)."/$filename-jqueryui.css", $license . $css_jquery);

			$css_html = $less->parse(wrap($theme . NL . '@import "core/after-html.less";', $scope));
			file_put_contents(dirname(__FILE__)."/$filename-html.css", $license . $css_html);

			$css_form = $less->parse(wrap($theme	. NL . '@import "core/after-form.less";', $scope));
			file_put_contents(dirname(__FILE__)."/$filename-form.css", $license . $css_form);

			$css_extra = $less->parse(wrap($theme . NL . '@import "core/after-extra.less";', $scope));
			file_put_contents(dirname(__FILE__)."/$filename-extra.css", $license . $css_extra);

			file_put_contents(dirname(__FILE__)."/$filename.css", $license . $css_jquery . $css_html . $css_form. $css_extra);

			//lessc_ext::ccompile($path, $out);
		} catch (exception $ex) {
			exit('lessc fatal error:<br />'.$ex->getMessage());
		}
	}

	function wrap($less, $scope = '') {
		return empty($scope)? $less : "\n.{$scope}{\n{$less}\n}";
	}

	readfile(dirname(__FILE__).'/index.html');
?>