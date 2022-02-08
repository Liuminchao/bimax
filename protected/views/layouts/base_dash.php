<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo Yii::t('login', 'Website Name'); ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- bootstrap 3.0.2 -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <!-- X-editable -->
    <link href="css/bootstrap-editable.css" rel="stylesheet" type="text/css" />
    <link href="css/select2/select2-bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="css/select2/select2.css" rel="stylesheet" type="text/css" />
    <!-- Jquery-UI -->
    <link href="css/jQueryUI/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css" />
    <!--		<link href="css/jQueryUI/style.css" rel="stylesheet"  type="text/css" />-->
    <!--        <link href="css/jQueryUI/jquery-ui1.0.css"  rel="stylesheet" type="text/css" />-->
    <!--        <link rel="stylesheet" href="http://jqueryui.com/resources/demos/style.css" >-->
    <!--        <link rel="stylesheet" href="css/jQueryUI/style1.0.css" >-->
    <!--        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">-->
    <!-- common style -->
    <link href="css/common.css" rel="stylesheet" type="text/css" />
    <!--  Org Chart -->
    <link href="css/struct.css" rel="stylesheet" type="text/css">
    <!-- jvectormap -->
    <link href="css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery 2.1.1 -->
    <script src="js/jquery-2.1.1.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/AdminLTE/app.js" type="text/javascript"></script>
</head>
<body class="skin-blue">

<?php echo $content; ?>
<!-- Bootstrap -->
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/bootstrap3-validation.js" type="text/javascript"></script>
<!-- jquery form -->
<script src="js/jquery.form.js" type="text/javascript"></script>
<!-- my.js -->
<script src="js/my.js" type="text/javascript"></script>
<!-- view.js -->
<script src="js/view.js" type="text/javascript"></script>
<!-- WdatePicker.js-->
<script type="text/javascript" src="js/JQdate/WdatePicker.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function () {//查询表单回车提交
        $("#_query_form").bind('keyup', function (event) {
            if (event.keyCode == 13) {
                itemQuery(0);
            }
        })
    });
</script>
</body>
</html>

<!-- COMPOSE MESSAGE MODAL -->
<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="modal-close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-title"></h4>
            </div>
            <div class="modal-body" id="content-body" style="min-height:100px">
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="callout callout-info" id="loading" style='display:none;position: fixed; bottom: 0px; right: 0px; z-index: 999999; '><p><?php echo Yii::t('dboard','loading');?></p></div>