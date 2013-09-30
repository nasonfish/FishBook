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
require '../system/PageHandler.class.php';
$temp = $peregrine->server->getRaw('REQUEST_URI');
$uri = explode('/', strpos($temp, '?') ? strstr($temp, '?', true) : $temp);
foreach($uri as $id => $dir){
    if($dir == ""){
        unset($uri[$id]);
    }
}
$page = isset($uri[1]) ? $uri[1] : 'index';
array_shift($uri);
$args = $uri === NULL ? array() : $uri;
if(strpos($page, '_') === 0){
    include $page.'.php'; // BE WEARY OF INJECTION!!!
} else {
    new PageHandler($page, $args);
}
