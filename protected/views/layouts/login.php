<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width,  initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0;">
		<meta content="BIMax Plus" name="keywords" />
		<meta content="BIMax Plus" name="description" />
		<title><?php echo Yii::t('login', 'Website Name'); ?></title>
		<link rel="stylesheet" type="text/css" href="css/login/bootstrap2.min.css" media="screen">
		<link rel="stylesheet" type="text/css" href="css/login/style.css">
		<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js" ></script>
		<script type="text/javascript" src="js/jquery.fullbg.min.js"></script>
	</head>
	<body style="background-color:#EAEAEA; height: 100%;width: 100%">
		<?php echo $content; ?>
	</body>
</html>

<script type="text/javascript">
$(window).load(function() {
	// $("#loginBg").fullBg();
	var w = $(window).width();
	var h = $(window).height();
	// if(w>h){
		// $("body").height(w);
		// $("body").width(h);
	// }else{
        $("body").height(h);
	// }
	var h1 = $('#login_sketch').height();
	var h2 = h-h1;
	$("#bottom").height(h2);


});
</script>