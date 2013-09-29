<?php if(!isset($pass[0])): ?>
    No group specified.
<?php else: ?>
    <ul>
    <?php foreach($manager->byGroup(ucwords(str_replace('-', ' ', $pass[0]))) as $name): ?>
        <li><a href="/user/<?=$name?>"><?=str_replace('-', ' ', $name)?></a></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>