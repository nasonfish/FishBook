<h2>Build a Mailing List!</h2>
<p>Here, you can build a mailing list of users, so you can e-mail them all at once.</p>
<p>Currently, you can build the mailing list by categories using the AND and OR logic operators.</p>
<p>AND would make sure <b>all</b> categories specified match the user, while OR makes sure at least one of them matches.</p>
<p>More functionality is coming soon.</p>

<select name="type" class="type">
    <option value="AND">Match ALL categories specified</option>
    <option value="OR">Match AT LEAST one category specified.</option>
</select>
<p><a class="js-click" onclick='$(this).after("<input type=\"text\" class=\"category\" name=\"category[]\">")'>Add new placeholder</a></p>
<input type="text" name="category[]" class="category" placeholder="Student"/>
<button onclick="event.preventDefault(); $AJAX('/ajax_matcher.php', 'POST', {'category':$('.category').valArray(), type:$('.type').val()}, function(data){$('.data').html(data);})">Get list!</button>
or <button onclick="event.preventDefault(); $AJAX('/ajax_builder.php', 'POST', {'category':$('.category').valArray(), type:$('.type').val(), glue:$('.glue').val()}, function(data){$('.result').html('<pre>' + data + '</pre>');});">Implode!</button> with glue: <input type="text" name="glue" class="glue" value=", " placeholder=", "/>
<div class="result"></div>
<div class="data"></div>