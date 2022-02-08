<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo Yii::t('login', 'Website Name'); ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="css/ionicons2.1.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
<!--    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">-->
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <!-- ichecker -->
    <link href="css/iCheck/square/_all.css" rel="stylesheet" type="text/css" / >
    <!-- X-editable -->
<!--    <link href="css/bootstrap-editable.css" rel="stylesheet" type="text/css" />-->
    <link href="css/select2/select2-bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="css/select2/select2.css" rel="stylesheet" type="text/css" />
    <!-- Jquery-UI -->
    <link href="css/jQueryUI/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css" />
    <!-- common style -->
    <link href="css/common.css" rel="stylesheet" type="text/css" />
    <!--  Org Chart -->
    <link href="css/struct.css" rel="stylesheet" type="text/css">

    <link href="css/style.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->


</head>
<body class="skin-blue ">
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- date picker -->
<!--<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>-->
<!--<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.zh-CN.min.js"></script>-->
<!--<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker-en-CA.min.js"></script>-->
<?php echo $content; ?>
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- fselect -->
<script src="js/select2/fselect.js"></script>
<!-- Treeview -->
<script src="js/bootstrap-treeview.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script src="js/bootstrap3-validation.js" type="text/javascript"></script>
<!-- my.js -->
<script src="js/my.js" type="text/javascript"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<!--<script src="plugins/chart.js/Chart.min.js"></script>-->
<!-- Sparkline -->
<!--<script src="plugins/sparklines/sparkline.js"></script>-->
<!-- jQuery Knob Chart -->
<!--<script src="plugins/jquery-knob/jquery.knob.min.js"></script>-->

<!-- bootstrap color picker -->
<!--<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>-->
<!-- Summernote -->
<!--<script src="plugins/summernote/summernote-bs4.min.js"></script>-->
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

<!-- view.js -->
<script src="js/view.js" type="text/javascript"></script>

<script type="text/javascript">
    jQuery(document).ready(function () {//查询表单回车提交
        $("#_query_form").bind('keyup', function (event) {
            if (event.keyCode == 13) {
                itemQuery(0);
            }
        })
    });

    $(function(){
        $('.sidebar-menu li:not(.treeview) > a').on('click', function(){
            var $parent = $(this).parent().addClass('active');
            $parent.siblings('.treeview.active').find('> a').trigger('click');
            $parent.siblings().removeClass('active').find('li').removeClass('active');
        });

        $(window).on('load', function(){
            $('.sidebar-menu a').each(function(){
                if(this.href === window.location.href){
                    $(this).parent().addClass('active')
                        .closest('.treeview-menu').addClass('.menu-open')
                        .closest('.treeview').addClass('active');
                }
            });
        });
    });

    var pedit = function () {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('dboard', 'Menu pedit'); ?>";
        modal.url = "index.php?r=sys/operator/pedit";
        modal.modal();
    }

    var download_contract = function (id) {
        window.location = "index.php?r=comp/info/piclist&id="+id;
    }

    //操作员列表
    var itemOperator = function (id, name){
        window.location = "index.php?r=comp/info/operatorlist&id=" + id+"&name="+name;
    }

    //项目进入菜单
    var itemMenu = function(id,ptype) {
//        window.location = "index.php?r=dboard/menu&id="+id;
        window.location = "index.php?r=proj/project/list&ptype="+ptype+"&program_id="+id;
    }

    //我的项目
    var test = function(){
        window.location = "index.php?r=site/list&ptype=MC";
    }
</script>
</body>
</html>

<!-- COMPOSE MESSAGE MODAL -->
<div class="modal fade" id="compose-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button id="modal-close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="content-body" style="min-height:100px">
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="callout callout-info" id="loading" style='display:none;position: fixed; bottom: 0px; right: 0px; z-index: 999999; '><p><?php echo Yii::t('dboard','loading');?></p></div>