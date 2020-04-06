<?php

require 'startup.php';

$uniqid = uniqid();
App::log("UNIQUE_ID: {$uniqid}");
Cookie::set(Cookie::UNIQUE_ID, $uniqid);
$allDensities = [18, 24, 36, 48, 72, 96, 128, 176, 224, 256, 384, 512];

$getDensities = [18, 24, 36, 48];
if (!empty($_GET["densities"])) {

    $getDensities = $_GET["densities"];

}
$isDrawable = empty($_GET["folder"]) || $_GET["folder"] == "drawable";
$haveMany = count($getDensities) > 1;
$sufixAttr = "";

$sufixAttr .= $haveMany ? " disabled" : "";

$sufixAttr .= !$haveMany &&(!empty($_GET["removesufix"]) || @$_GET["removesufix"] == "1") ? " checked" : "";
?><!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <title>Android Icons Online Generator</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="vendor/enyo/dropzone/dist/dropzone.css">
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <script>
    var ziplink = "zipfile.php";
    var uploadlink = "upload.php";
    </script>
    <script src="js/jquery.min.js"></script>
    <script src="vendor/enyo/dropzone/dist/dropzone.js"></script>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <header>
                        <h1>Android Icons</h1>
                </header>
                <form id="options">
                    <div class="form-group">
                        <label for="folder">Folder name</label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="folder" class="folder" value="drawable"<?php echo $isDrawable ? " checked" : ""; ?>>
                                drawable
                            </label>
                            &nbsp;
                            <label>
                                <input type="radio" name="folder" class="folder" value="mipmap"<?php echo $isDrawable ? "" : " checked"; ?>>
                                mipmap
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div><label>Densities</label></div>
                        <div class="checkbox">
                            <?php
                            foreach ($allDensities as $density) {
                                $checked = in_array($density, $getDensities) ? " checked" : "";
                                    echo '<label>';
                                    echo '<input type="checkbox" class="densities" name="densities[]" value="' . $density . '"' . $checked . '>';
                                    echo $density . 'dp';
                                echo '</label>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="removesufix" name="removesufix" value="1"<?php echo $sufixAttr; ?>>
                                Remove density sufix
                            </label>
                        </div>
                    </div>
                </form>
                <form id="upload-widget" action="/" class="dropzone" ></form>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
</body>
</html>