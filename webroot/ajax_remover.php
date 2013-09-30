<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header("WWW-Authenticate: Basic realm=\"Basalt Middle School Choir Management Program Authentication.\"");
    header("HTTP/1.0 401 Unauthorized");
    echo '401 Unauthorized - No username/password supplied. Sorry.';
    exit;
} else {
    if($_SERVER['PHP_AUTH_PW'] != file_get_contents('../pass.txt') && $_SERVER['PHP_AUTH_PW'] != file_get_contents('../kpass.txt')){
        header("WWW-Authenticate: Basic realm=\"Basalt Middle School Choir Management Program Authentication.\"");
        header("HTTP/1.0 401 Unauthorized");
        echo "401 Unauthorized - Incorrect username/password. Sorry.";
        exit;
    }
}
include('../system/Config.php');
$id = $peregrine->post->getInt('id');
$manager->deleteBuild($id);
