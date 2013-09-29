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
$categories = ($c = $peregrine->post->getRaw('category')) === "" ? array() : explode(',', $c);
$users = $manager->getUsers($categories, $type);
if(sizeof($users) > 0): ?>
    <table class="p100 border-gray">
        <tr class="p25">
            <th>Name</th>
            <th>E-Mail</th>
            <th>Phone Number</th>
            <th>Tags</th>
        </tr>
        <?php foreach($users as $name): $user = $manager->user($name); ?>
            <tr class="p25">
                <td><a href="/user/<?=$name?>/"><?=str_replace('-', ' ', $name)?></a></td>
                <td><?=$user->getEmail();?></td>
                <td><?=$user->getPhone();?></td>
                <td><?=implode(', ', $user->getTags());?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No Users were found.</p>
<?php endif; ?>
