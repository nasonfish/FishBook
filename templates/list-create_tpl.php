<h2>Build a Mailing List!</h2>
<p>Here, you can build a mailing list of users, so you can e-mail them all at once.</p>
<p>Currently, you can build the mailing list by categories using the AND and OR logic operators.</p>
<p>AND would make sure <b>all</b> categories specified match the user, while OR makes sure at least one of them matches.</p>
<p>More functionality is coming soon.</p>

<select name="type" class="type">
    <option value="AND">Match ALL categories specified</option>
    <option value="OR">Match AT LEAST one category specified.</option>
</select>
<label for="category" class="big-label">Select Categories</label>
<p><a class="js-click box-maker" data-for="category">Add new Category</a></p>
<input type="text" id="category" name="category[]" class="category" placeholder="Student"/>
<label for="glue" class="big-label">Glue (for building the list of e-mails)</label>
<input type="text" id="glue" name="glue" class="glue" value=", " placeholder=", "/>
<button class="matcher">Get list!</button>
<button class="builder">Implode!</button>
<div class="result"></div>
<div class="data"></div>