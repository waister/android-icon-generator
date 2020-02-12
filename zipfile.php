<?php

require 'startup.php';


$zipname = 'android_icons.zip';

$rootPath = generatedPath();
logfy("rootPath: {$rootPath}");

$zipPath = $rootPath . $zipname;
logfy("zipPath: {$zipPath}");

if (file_exists($rootPath)) {
    if (!file_exists($zipPath)) {
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                logfy("filePath: {$filePath}");

                $relativePath = substr($filePath, strlen($rootPath));
                logfy("relativePath: {$relativePath}");

                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }

    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename=' . $zipname);
    header('Content-Length: ' . filesize($zipPath));
    readfile($zipPath);
} else {
    header("Refresh: 1");

    echo '<div style="padding:20px;text-align:center;font-family:Arial;font-size:16px;color:#777;">';
        echo 'Aguardando compressão dos arquivos...<br>';
        echo '<a href="/androidicons" style="display:block;margin-top:10px;font-size:14px;color:blue;">Cancelar</a><br>';
    echo '</div>';
}