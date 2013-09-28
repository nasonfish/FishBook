<form method="post" action="/create.php">
    <label for="name">What is the name of the user you are adding?</label>
    <input type="text" name="name" id="name"/>
    <label for="email">Enter a <i>primary</i> e-mail that the user would like to be contacted by.</label>
    <input type="email" name="email" id="email"/>
    <label for="phone">Enter a phone number to contact the user by.</label>
    <input type="text" name="phone" id="phone"/>
    <label for="tags">Enter things you would like to tag this user by. This will allow you to filter users to build lists.</label>
    <input type="text" name="tags[]" id="tags"/>
    <a onclick='$(this).after("<input type=\"text\" name=\"tags[]\">");'>Add new tag (tag as many things as you can!)</a>
    <label for="notes">Add extra notes about this person! (use {person name} to link to a person, such as a parent or related person)</label>
    <input type="text" name="notes[]" id="notes"/>
    <a onclick='$(this).after("<input type=\"text\" name=\"notes[]\">");'>Add new note</a>
    <label for="contact">Add more contact information about this person</label>
    <input type="text" name="contact[]" id="contact"/>
    <a onclick='$(this).after("<input type=\"text\" name=\"contact[]\">");'>Add new note</a>
    <button type="submit">Submit and add this new user!</button>
</form>
