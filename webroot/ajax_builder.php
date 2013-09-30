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
$data = $peregrine->post->getRaw('data');
$categories = ($c = $peregrine->post->getRaw('category')) === "" ? array() : explode(',', $c);
if(!in_array($data, array('email', 'phone', 'name', 'contact', 'notes', 'tags'))){
    die('Unknown Data Type.');
}
foreach($categories as $id=>$category){
    if(empty($category)){
        unset($categories[$id]);
    }
}

$users = $manager->getUsers($categories, $type);
if(sizeof($users) > 0){
    $emails = array();
        foreach($users as $name){
            $emails[] = $manager->user($name)->data($data);
        }
} else {
    echo "No Users were found.";
}
$glue = ($glue = $peregrine->post->getRaw('glue')) ? $glue : ', ';
if(is_array($emails[0])){
    foreach($emails as $id=>$email){
        if(empty($email)){
            unset($emails[$id]);
        } else {
            $emails[$id] = implode($glue, $email);
        }
    }
}
echo implode($glue, $emails);
$manager->saveBuild($type, $categories, $glue, $data);
