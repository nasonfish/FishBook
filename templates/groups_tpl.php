<ul>
<?php foreach($manager->groups() as $group): ?>
    <li><a href="/group/<?=$group?>"><?=$group?></a></li>
<?php endforeach; ?>
</ul>
