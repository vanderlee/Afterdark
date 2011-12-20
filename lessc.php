<?php

header('Content-Type: text/css');

require 'lessc.inc.php';

try {
	$out = basename(str_replace('.less', '', $_SERVER['QUERY_STRING'])).'.css';
	@unlink($out);
    lessc::ccompile($_SERVER['QUERY_STRING'], $out);

	$header = "/*\n"
			. " * AfterDark jQueryUI theme\n"
			. " * Copyright ".date('Y')." Martijn W. van der Lee\n"
			. " * Licensed under Creative Commons Attribution-Share Alike 3.0\n"
			. " * Build ".date('Y-m-d H:i:s')."\n"
			. " */\n"
			;
	file_put_contents($out, $header . file_get_contents($out));
} catch (exception $ex) {
    exit('lessc fatal error:<br />'.$ex->getMessage());
}

readfile($out);

?>
