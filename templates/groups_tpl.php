<?php $groups = $manager->groups(); if(sizeof($groups) > 0):?>
    <h2>All groups</h2>
    <table class="p100 border-gray">
        <tr class="p33">
            <th>Name</th>
            <th>People</th>
            <th>Total users</th>
        </tr>
        <?php foreach($groups as $name): ?>
            <tr class="p33">
                <td><a href="/group/<?=str_replace(' ', '-', $name)?>/"><?=$name?></a></td>
                <td><?=str_replace('-', ' ', implode(', ', array_slice($manager->byGroup($name), 0, 3)));?></td>
                <td><?=$manager->inGroup($name);?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <h2>No Groups were found.</h2>
    <p>Add some <a href="/create/"> users over here!</a> :-)</p>
<?php endif; ?>
