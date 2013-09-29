<?php if(isset($pass[0]) && ($user = $manager->user($pass[0])) && $user->exists()): ?>
    <h3>User <?= $user->getName(); ?>:</h3>
    <ul>
        <li>E-mail Address: <?= $user->getEmail();?></li>
        <li>Phone Number: <?= $user->getPhone(); ?></li>
        <li>Tags:<ul><li><?=implode('</li><li>', $user->getTags());?></li></ul></li>
        <li>Notes:<ul><li><?=implode('</li><li>', $user->getNotes());?></li></ul></li>
        <li>Contact Information:<ul><li><?=implode('</li><li>', $user->getContact());?></li></ul></li>
    </ul>
    <p>Is this information wrong? <a href="/edit/<?=$pass[0]?>/">Change it!</a></p>
<?php else: ?>
    <p>User not found.</p>
<?php endif; ?>
