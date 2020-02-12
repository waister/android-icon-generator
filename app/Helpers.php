<?php

function logfy($msg, $array = []) {
    $append = '';

    if ($array && is_array($array)) {
        $append = ' - ARRAY: ' . json_encode($array);
    }

    $msg = "[" . date("d-m-Y, H:i:s") . "] " . $msg . $append . "\r\n";
    $fp = fopen("events.log", "a");
    fwrite($fp, $msg);
    fclose($fp);
}

function outputPath() {
    $uniqueId = Cookie::get(Cookie::UNIQUE_ID, uniqid());
    return UPLOADS_PATH . $uniqueId . DS;
}

function generatedPath() {
    return outputPath() . "generated" . DS;
}

function clearUploads() {
    deleteDirectory(UPLOADS_PATH);
    mkdir(UPLOADS_PATH, 0777, true);
}

function deleteDirectory($dirname) {
    if (!file_exists($dirname)) return;
    $dir_handle = is_dir($dirname) ? opendir($dirname) : null;
    if (!$dir_handle) return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            $path = $dirname . "/" . $file;
            if (!is_dir($path))
                @unlink($path);
            else
                deleteDirectory($path);
        }
    }
    @closedir($dir_handle);
    @rmdir($dirname);
    return true;
}
