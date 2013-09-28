<ul>
    <?php foreach($manager->users() as $name): ?>
    <li><a href="/user/<?=$name?>"><?=$name?></a></li>
    <?php endforeach; ?>
</ul>