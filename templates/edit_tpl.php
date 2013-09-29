<?php if(isset($pass[0]) && ($user = $manager->user($pass[0])) && $user->exists()): ?>
<form method="post" action="/edit.php" class="hr-gray">
    <label for="name">What is the name of the user you are editing?</label>
    <input type="text" name="name" id="name" value="<?=$pass[0]?>" readonly/>
    <hr/>
    <label for="email">Enter a <i>primary</i> e-mail that the user would like to be contacted by.</label>
    <input type="email" name="email" id="email" value="<?=$user->getEmail()?>"/>
    <hr/>
    <label for="phone">Enter a phone number to contact the user by.</label>
    <input type="text" name="phone" id="phone" value="<?=$user->getPhone()?>"/>
    <hr/>
    <label for="tags">Enter things you would like to tag this user by. This will allow you to filter users to build lists.</label> <a class="js-click box-maker" data-for="tags">(Add new tag (tag as many things as you can!))</a>
    <?php foreach($user->getTags() as $tag): ?>
    <input type="text" name="tags[]" id="tags" value="<?=$tag?>"/>
    <?php endforeach; ?>
    <hr/>
    <label for="notes">Add extra notes about this person! (use {person name} to link to a person, such as a parent or related person)</label> <a class="js-click box-maker" data-for="notes">(Add new note)</a>
    <?php foreach($user->getNotes() as $note): ?>
    <input type="text" name="notes[]" id="notes" value="<?=$note?>"/>
    <?php endforeach; ?>
    <hr/>
    <label for="contact">Add more contact information about this person</label> <a class="js-click box-maker" data-for="contact">(Add new contact note)</a>
    <?php foreach($user->getContact() as $contact): ?>
    <input type="text" name="contact[]" id="contact" value="<?=$contact?>"/>
    <?php endforeach; ?>
    <hr/>
    <button type="submit">Submit and add this new user!</button>
</form>
<?php else: ?>
<p>User not found.</p>
<?php endif; ?>
