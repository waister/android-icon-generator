<?php

require 'startup.php';

use WideImage\WideImage;


clearUploads();

$outputFolder = outputPath();
$outputGenerated = generatedPath();

$inputFile = $outputFolder . basename($_FILES["file"]["name"]);

if (!file_exists($outputFolder)) {
    @mkdir($outputFolder);

    logfy("Output folder created: " . $outputFolder);
}

if (move_uploaded_file($_FILES["file"]["tmp_name"], $inputFile)) {
    logfy("File sent to: " . $outputFolder);

    $getFolter = !empty(@$_GET['folder']) ? @$_GET['folder'] : "drawable";
    logfy("getFolter: " . $getFolter);

    $removesufix = !empty(@$_GET["removesufix"]) || @$_GET["removesufix"] == "1" ? true : false;
    logfy("removesufix: " . $removesufix);

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

    foreach ($densitiesFilter as $density) {
        foreach ($proportions as $folder => $proportion) {
            if (!file_exists($outputGenerated)) {
                @mkdir($outputGenerated);
            }

            $subfolder = $outputGenerated . $getFolter . "-" . $folder;
 
            if (!file_exists($subfolder) || !is_dir($subfolder)) {
                @mkdir($subfolder);
            }
        }
    }

    logfy("densities", $densities);
    logfy("densitiesFilter", $densitiesFilter);

    foreach ($densitiesFilter as $density) {
        logfy("density: " . $density);

        foreach ($proportions as $folder => $proportion) {
            $subfolder = $outputGenerated . $getFolter . "-" . $folder;
            logfy("subfolder: " . $subfolder);

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

                logfy("extesion: " . $extesion);
                $quality = $extesion == 'png' ? 9 : 85;
                logfy("quality: " . $quality);

                $result = WideImage::load($inputFile)->resize($resizeWidth, $resizeHeight)->saveToFile($output, $quality);
                logfy("WideImage result: $result");
            } else {
                $status .= "SKIPPED ";
            }

            logfy($status . ' | Density: "' . $density . 'dp" | Folder: "' . $getFolter . '-' . $folder . '" | Output: "' . $output . '"');
        }
    }

} else {

    @unlink($outputFolder);

    logfy("Possible file upload attack!");

}
