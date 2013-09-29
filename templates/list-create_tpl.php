<?php
if($peregrine->get->keyExists('id')){
    $id = $peregrine->get->getInt('id');
    $build = $manager->build($id);
    if($build->exists()){
        $type = $build->getType();
        $glue = $build->getGlue();
        $categories = $build->getCategories();
    } else {
        $type = $peregrine->get->getRaw('type');
        $categories = $peregrine->get->getArray('category');
        $glue = $peregrine->get->getRaw('glue');
    }
} else {
    $type = $peregrine->get->getRaw('type');
    $categories = $peregrine->get->getArray('category');
    $glue = $peregrine->get->getRaw('glue');
}
?>
<h2>Build a Mailing List!</h2>
<p>Here, you can build a mailing list of users, so you can e-mail them all at once.</p>
<p>Currently, you can build the mailing list by categories using the AND and OR logic operators.</p>
<p>AND would make sure <b>all</b> categories specified match the user, while OR makes sure at least one of them matches.</p>
<p>More functionality is coming soon.</p>
<label for="type">AND or OR logic operator?</label>
<select name="type" class="type" id="type">
    <option value="AND" <?=$type === 'AND' ? 'selected="selected"' : ''?>>Match ALL categories specified</option>
    <option value="OR" <?=$type ==='OR' ? 'selected="selected"' : ''?>>Match AT LEAST one category specified.</option>
</select>
<label for="category" class="big-label">Select Categories (or leave blank to select all users)</label>
<p><a class="js-click box-maker" data-for="category">Add new Category</a></p>
<?php if($categories){
    foreach($categories as $category){
        echo '<input type="text" name="category[]" class="category" placeholder="Student" value="'.$category.'"/>';
    }
} else {
    echo '<input type="text" id="category" name="category[]" class="category" placeholder="Student"/>';
}
?>
<label for="glue" class="big-label">Glue (for building the list of e-mails)</label>
<input type="text" id="glue" name="glue" class="glue" value="<?=$glue ? $glue : ', '?>" placeholder=", "/>
<button class="matcher">Get list!</button>
<button class="builder">Implode!</button>
<div class="result"></div>
<div class="data"></div>
