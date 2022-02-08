<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo Yii::t('login', 'Website Name'); ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="css/ionicons2.1.min.css">
</head>
<body class="skin-blue">

<?php echo $content; ?>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function () {//查询表单回车提交
        $("#_query_form").bind('keyup', function (event) {
            if (event.keyCode == 13) {
                itemQuery(0);
            }
        })
    });
    //操作员列表
    var itemOperator = function (id, name){
        window.location = "index.php?r=comp/info/operatorlist&id=" + id+"&name="+name;
    }
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
