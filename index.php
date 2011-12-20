<?php
	require 'lessc.inc.php';

	foreach (scandir(dirname(__FILE__).'/themes') as $file) {
		$path = dirname(__FILE__).'/themes/'.$file;
		if (is_file($path)) {
			generate($path);
		}
	}

	function generate($path) {
		try {
			$filename = pathinfo($path, PATHINFO_FILENAME);

			$out = dirname(__FILE__)."/$filename.css";
			@unlink($out);

			lessc::ccompile($path, $out);

			$header = "/*\n"
					. " * ".ucfirst($filename)." jQueryUI theme\n"
					. " * Copyright ".date('Y')." Martijn W. van der Lee\n"
					. " * Licensed under Creative Commons Attribution-Share Alike 3.0\n"
					. " * Build ".date('Y-m-d H:i:s')."\n"
					. " */\n"
					;
			file_put_contents($out, $header . file_get_contents($out));
		} catch (exception $ex) {
			exit('lessc fatal error:<br />'.$ex->getMessage());
		}
	}

	readfile(dirname(__FILE__).'/index.html');
?>