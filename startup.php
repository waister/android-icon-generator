<?php

date_default_timezone_set("America/Sao_Paulo");

$uploads = realpath("uploads");
if (!$uploads || $uploads == "/") {
	@mkdir("uploads", 0777, true);
}
$uploads = realpath("uploads");

define("DS", DIRECTORY_SEPARATOR);
define("UPLOADS_PATH", realpath("uploads") . DS);

require 'vendor/autoload.php';

require "app/App.php";
require "app/Cookie.php";
