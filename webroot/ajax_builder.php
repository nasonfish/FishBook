<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header("WWW-Authenticate: Basic realm=\"Basalt Middle School Choir Management Program Authentication.\"");
    header("HTTP/1.0 401 Unauthorized");
    echo '401 Unauthorized - No username/password supplied. Sorry.';
    exit;
} else {
    if($_SERVER['PHP_AUTH_PW'] != file_get_contents('../pass.txt')){
        header("WWW-Authenticate: Basic realm=\"Basalt Middle School Choir Management Program Authentication.\"");
        header("HTTP/1.0 401 Unauthorized");
        echo "401 Unauthorized - Incorrect username/password. Sorry.";
        exit;
    }
}
include('../system/Config.php');
$type = $peregrine->post->getRaw('type');
$categories = explode(',', $peregrine->post->getRaw('category'));
$users = $manager->getUsers($categories, $type);
if(sizeof($users) > 0){
    $emails = array();
        foreach($users as $name){
            $emails[] = $manager->user($name)->getEmail();
        }
} else {
    echo "No Users were found.";
}
$glue = ($glue = $peregrine->post->getRaw('glue')) ? $glue : ', ';
echo implode($glue, $emails);
