<?php if(!isset($pass[0])): ?>
    <h2>View Groups</h2>
    <p>No group specified.</p>
<?php else: ?>
    <h2>Users in group <code><?=htmlspecialchars($pass[0]);?></code></h2>
    <ul>
    <?php foreach($manager->byGroup(ucwords(str_replace('-', ' ', $pass[0]))) as $name): ?>
        <li><a href="/user/<?=$name?>/"><?=str_replace('-', ' ', $name)?></a></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>