<h2>Recent Mailing Lists Used</h2>
<?php $builds = $manager->getBuilds(); if(!empty($builds)): ?>
<table class="p100 border-gray">
    <tr class="p20">
        <th>ID</th>
        <th>Categories</th>
        <th>Data</th>
        <th>Type (AND/OR)</th>
        <th>Glue</th>
    </tr>
    <?php foreach($builds as $build): $build = $manager->build($build); ?>
    <tr class="p20">
        <td><a href="/list-create/?id=<?=$build->getID();?>"><?=$build->getID();?></td>
        <td><?=implode(', ', $build->getCategories());?></td>
        <td><?=ucwords($build->getData());?></td>
        <td><code><?=$build->getType();?></code></td>
        <td><code class="pre"><?=$build->getGlue();?></code></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>No mailing lists have been built yet. Try creating one <a href="/list-create/">over here</a>!</p>
<?php endif; ?>
