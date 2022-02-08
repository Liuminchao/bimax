<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="关键字" name="keywords" />
    <meta content="描述" name="description" />
    <title><?php echo Yii::t('login', 'Website Name'); ?></title>
    <link rel="stylesheet" type="text/css" href="css/login/bootstrap2.min.css" media="screen">
    <link rel="stylesheet" type="text/css" href="css/login/style.css">
    <script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js" ></script>
    <script type="text/javascript" src="js/jquery.fullbg.min.js"></script>
</head>
<body style="background-color:#EAEAEA; height: 100%">
<?php echo $content; ?>
</body>
</html>

<script type="text/javascript">
    $(window).load(function() {
//	$("#loginBg").fullBg();
        var h = $(window).height();
        var h1 = $('#login_sketch').height();
        $("body").height(h);
        var h2 = h-h1;
        $("#bottom").height(h2);
    });
</script>