<!DOCTYPE html>
<html>
<head>
    <title><?=$this->title()?> - <?=get('main:title');?></title>
    <?php $this->css(); ?>
</head>
<body>
<div class="content">
    <div class="head">
        <?php $this->head(); ?>
    </div>
    <div class="body">
         <?php $this->page(); ?>
    </div>
    <div class="foot">
         <?php $this->foot(); ?>
    </div>
</div>
<footer>
    <?php $this->js(); ?>
</footer>
</body>
</html>
