<?php $users = $manager->users(); if(sizeof($users) > 0):?>
<h2>All students</h2>
<label for="all-search">Filter through users:</label>
<input type="text" id="all-search"/>
<table class="p100 border-gray">
    <tr class="p25">
        <th>Name</th>
        <th>E-Mail</th>
        <th>Phone Number</th>
        <th>Tags</th>
    </tr>
    <?php foreach($users as $name): $user = $manager->user($name); ?>
    <tr class="p25 search" data-search-for="<?=str_replace('-', ' ', $name)?>">
        <td><a href="/user/<?=$name?>/"><?=str_replace('-', ' ', $name)?></a></td>
        <td><?=$user->getEmail();?></td>
        <td><?=$user->getPhone();?></td>
        <td><?=implode(', ', $user->getTags());?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <h2>No Users were found.</h2>
    <p>Add them <a href="/create/">over here!</a> :-)</p>
<?php endif; ?>
