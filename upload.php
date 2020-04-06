<?php

require 'startup.php';

use WideImage\WideImage;


App::clearFolder(UPLOADS_PATH);

$outputFolder = App::outputPath();
$outputGenerated = App::generatedPath();

$inputFile = $outputFolder . basename($_FILES["file"]["name"]);

if (!file_exists($outputFolder)) {
    @mkdir($outputFolder, 0777, true);

    App::log("Output folder created: " . $outputFolder);
}

if (move_uploaded_file($_FILES["file"]["tmp_name"], $inputFile)) {
    App::log("Arquivo enviado para: " . $outputFolder);

    $getFolter = !empty(@$_GET['folder']) ? @$_GET['folder'] : "drawable";
    App::log("getFolter: " . $getFolter);

    $removesufix = !empty(@$_GET["removesufix"]) || @$_GET["removesufix"] == "1" ? true : false;
    App::log("removesufix: " . $removesufix);

    $densities = $_GET["densities"];

    $proportions = [
        "mdpi" => 1,
        "hdpi" => 1.5,
        "xhdpi" => 2,
        "xxhdpi" => 3,
        "xxxhdpi" => 4,
    ];

    $outputFileName = trim(basename($inputFile) . PHP_EOL);
    $outputFolderName = pathinfo($inputFile, PATHINFO_FILENAME);

    if ($removesufix) {
        $densitiesFilter[] = 48;
    } else {
        foreach ($densities as $key => $value) {
            $densitiesFilter[$key] = $value;
        }
    }

    // Generate the folders
    foreach ($densitiesFilter as $density) {
        foreach ($proportions as $folder => $proportion) {
            if (!file_exists($outputGenerated)) {
                @mkdir($outputGenerated, 0777, true);
            }

            $subfolder = $outputGenerated . $getFolter . "-" . $folder;
 
            if (!file_exists($subfolder) || !is_dir($subfolder)) {
                @mkdir($subfolder, 0777, true);
            }
        }
    }

    App::log("densities", $densities);
    App::log("densitiesFilter", $densitiesFilter);

    // Generate the resized files
    foreach ($densitiesFilter as $density) {
        App::log("density: " . $density);

        foreach ($proportions as $folder => $proportion) {
            $subfolder = $outputGenerated . $getFolter . "-" . $folder;
            App::log("subfolder: " . $subfolder);

            $resizeWidth = $density * $proportion;
            $extesion = pathinfo($inputFile, PATHINFO_EXTENSION);

            $prefix = !$removesufix ? "_" . $density . "dp" : "";
            $output = $subfolder . DS . $outputFolderName . $prefix . "." . $extesion;
            $status = "";

            if (!file_exists($output)) {
                $status .= "GENERATED ";

                $imagesize = getimagesize($inputFile);
                $ratio = $imagesize[0] / $imagesize[1];
                $resizeHeight= $resizeWidth / $ratio;

                App::log("extesion: " . $extesion);
                $quality = $extesion == 'png' ? 9 : 85;
                App::log("quality: " . $quality);

                $result = WideImage::load($inputFile)->resize($resizeWidth, $resizeHeight)->saveToFile($output, $quality);
                App::log("WideImage result: $result");
            } else {
                $status .= "SKIPPED ";
            }

            App::log($status . ' | Density: "' . $density . 'dp" | Folder: "' . $getFolter . '-' . $folder . '" | Output: "' . $output . '"');
        }
    }

} else {

    @unlink($outputFolder);

    App::log("Possível ataque de upload de arquivo!");

}
