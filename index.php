<?php

require 'startup.php';

$uniqid = uniqid();
logfy("UNIQUE_ID: {$uniqid}");
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
    <title>Android Icons</title>
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
    <style>#forkongithub a{background:#000;color:#fff;text-decoration:none;font-family:arial,sans-serif;text-align:center;font-weight:bold;padding:5px 40px;font-size:1rem;line-height:2rem;position:relative;transition:0.5s;}#forkongithub a:hover{background:#c11;color:#fff;}#forkongithub a::before,#forkongithub a::after{content:"";width:100%;display:block;position:absolute;top:1px;left:0;height:1px;background:#fff;}#forkongithub a::after{bottom:1px;top:auto;}@media screen and (min-width:1024px){#forkongithub{position:fixed;display:block;top:0;right:0;width:200px;overflow:hidden;height:200px;z-index:9999;}#forkongithub a{width:200px;position:absolute;top:40px;right:-40px;transform:rotate(45deg);-webkit-transform:rotate(45deg);-ms-transform:rotate(45deg);-moz-transform:rotate(45deg);-o-transform:rotate(45deg);box-shadow:4px 4px 10px rgba(0,0,0,0.8);}}</style><span id="forkongithub"><a href="https://github.com/waister/android-icon-generator">Fork me on GitHub</a></span>
</body>
</html>