<h2>Create a new user!</h2>
<form method="post" action="/create.php" class="hr-gray">
    <label for="name">What is the name of the user you are adding?</label>
    <input type="text" name="name" id="name" placeholder="Your Name"/>
    <hr/>
    <label for="email">Enter a <i>primary</i> e-mail that the user would like to be contacted by.</label>
    <input type="email" name="email" id="email" placeholder="user@example.com"/>
    <hr/>
    <label for="phone">Enter a phone number to contact the user by.</label>
    <input type="text" name="phone" id="phone" placeholder="+1 (970) 123-4567"/>
    <hr/>
    <label for="tags">Enter things you would like to tag this user by. This will allow you to filter users to build lists.</label> <a class="js-click box-maker" data-for="tags">(Add new tag (tag as many things as you can!))</a>
    <input type="text" name="tags[]" id="tags" placeholder="Student"/>
    <hr/>
    <label for="notes">Add extra notes about this person! (use {person name} to link to a person, such as a parent or related person)</label> <a class="js-click box-maker" data-for="notes">(Add new note)</a>
    <input type="text" name="notes[]" id="notes" placeholder="Really Cool Person"/>
    <hr/>
    <label for="contact">Add more contact information about this person</label> <a class="js-click box-maker" data-for="contact">(Add new contact note)</a>
    <input type="text" name="contact[]" id="contact" placeholder="Alternate E-Mail: user@example.net"/>
    <hr/>
    <button type="submit">Submit and add this new user!</button>
</form>
