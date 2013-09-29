<!DOCTYPE html>
<html>
<head>
    <title><?=$this->title()?> - <?=get('main:title');?></title>
    <?php $this->css(); ?>
    <link href="/app.css" rel="stylesheet" media="all"/>
</head>
<body>
<div class="content">
    <div class="head">
        <div class="navbar">
            <table style="width: 100%;" class="primary">
                <tr class="primary p25">
                    <td><h2><a href="/">Choir People Manager</a></h2></td>
                    <td colspan="3"></td>
                </tr>
                <tr class="secondary center p25">
                    <td><a href="/create/">Create a user!</a></td>
                    <td><a href="/all/">View all users!</a></td>
                    <td><a href="/groups/">View all groups!</a></td>
                    <td><a href="/list-create/">Create a mailing list!</a></td>
                </tr>
            </table>
        </div>
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
    <script src="/simpledom.js"></script>
    <script src="/app.js"></script>
    <?php $this->js(); ?>
</footer>
</body>
</html>
