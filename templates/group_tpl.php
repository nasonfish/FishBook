<?php if(!isset($pass[0])): ?>
    No group specified.
<?php else: ?>
    <ul>
    <?php foreach($manager->byGroup($pass[0]) as $name): ?>
        <li><a href="/user/<?=$name?>"><?=$name?></a></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>