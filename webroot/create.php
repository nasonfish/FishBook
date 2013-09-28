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
$manager->create(
    $peregrine->post->getName('name'),
    $peregrine->post->getEmail('email'),
    $peregrine->post->getPhone('phone'),
    $peregrine->post->getArray('tags'),
    $peregrine->post->getArray('notes'),
    $peregrine->post->getArray('contact')
);
header(sprintf('Location: /user/%s/', str_replace(' ', '-', $peregrine->post->getName('name'))));
