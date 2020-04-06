<?php

class App {

    public static function log($msg, $array = []) {
        $append = '';

        if ($array && is_array($array)) {
            $append = ' - ARRAY: ' . json_encode($array);
        }

        $msg = "[" . date("d-m-Y, H:i:s") . "] " . $msg . $append . "\r\n";
        $fp = fopen("events.log", "a");
        fwrite($fp, $msg);
        fclose($fp);
    }

    public static function generatedPath() {
        return App::outputPath() . "generated" . DS;
    }


    public static function outputPath() {
        $uniqueId = Cookie::get(Cookie::UNIQUE_ID, uniqid());
        return UPLOADS_PATH . $uniqueId . DS;
    }

    public static function zipfile() {
        $zipname = 'android_icons.zip';

        // Get real path for our folder
        $rootPath = App::generatedPath();
        self::log("rootPath: {$rootPath}");

        $zipPath = $rootPath . $zipname;
        self::log("zipPath: {$zipPath}");

        if (file_exists($rootPath)) {
            if (!file_exists($zipPath)) {
                // Initialize archive object
                $zip = new ZipArchive();
                $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

                // Create recursive directory iterator
                /** @var SplFileInfo[] $files */
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($rootPath),
                    RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file) {
                    // Skip directories (they would be added automatically)
                    if (!$file->isDir()) {
                        // Get real and relative path for current file
                        $filePath = $file->getRealPath();
                        self::log("filePath: {$filePath}");

                        $relativePath = substr($filePath, strlen($rootPath));
                        self::log("relativePath: {$relativePath}");

                        // Add current file to archive
                        $zip->addFile($filePath, $relativePath);
                    }
                }

                // Zip archive will be created only after closing object
                $zip->close();
            }

            // Then download the zipped file.
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
    }

    public static function clearFolder($dirnamePath) {
        self::deleteDirectory($dirnamePath);
        @mkdir($dirnamePath, 0777, true);
    }

    public static function deleteDirectory($dirname) {
        $dir_handle = is_dir($dirname) ? opendir($dirname) : null;
        if (!$dir_handle) return false;
        while($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                $path = $dirname . "/" . $file;
                if (!is_dir($path))
                    @unlink($path);
                else
                    self::deleteDirectory($path);
            }
        }
        @closedir($dir_handle);
        @rmdir($dirname);
        return true;
    }

}
