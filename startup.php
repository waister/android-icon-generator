<?php

date_default_timezone_set("America/Sao_Paulo");

if (!file_exists("uploads/"))
	if (!@mkdir("uploads/", 0777))
		die('Could not create folder "uploads"');

define("DS", DIRECTORY_SEPARATOR);
define("UPLOADS_PATH", realpath("uploads") . DS);

require "vendor/autoload.php";

require "app/Helpers.php";
require "app/Cookie.php";
