<!DOCTYPE html>
<html>
<head>
    <title><?=$this->title()?> - <?=get('main:title');?></title>
    <?php $this->css(); ?>
    <link href="/app.css" rel="stylesheet" media="all"/>
    <link href='//fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Cabin' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="content">
    <div class="head">
        <div class="navbar">
            <table style="width: 100%;" class="primary">
                <tr class="primary p25">
                    <td><h2 class="title"><a href="/">Choir People Manager</a></h2></td>
                    <td colspan="3"></td>
                </tr>
                <tr class="secondary center p20">
                    <td><a href="/create/">Create user</a></td>
                    <td><a href="/all/">View users</a></td>
                    <td><a href="/groups/">View groups</a></td>
                    <td><a href="/list-create/">Create mailing list</a></td>
                    <td><a href="/list-recent/">Recent mailing lists</a></td>
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
        <table class="bottom">
            <tr class="p50">
                <td><a href="mailto:nasonfish@nasonfish.com" class="w-link">Have you found any bugs? Let me know!</a></td>
                <td>Source code may be released soon!</td>
            </tr>
        </table>
    </div>
</div>
<footer>
    <script src="/simpledom.js"></script>
    <script src="/app.js"></script>
    <?php $this->js(); ?>
</footer>
</body>
</html>
