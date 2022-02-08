<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo Yii::t('login', 'Website Name'); ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="stylesheet" type="text/css" href="css/login/bootstrap2.min.css" media="screen">
    <link rel="stylesheet" type="text/css" href="css/login/style.css">
    <!-- bootstrap 3.0.2 -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />

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
    <aside>
        <section class="content-header">
            <h1 align="center">
                <?php echo $this->smallHeader; ?>
                <small><?php //echo $this->contentHeader; ?></small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php echo $content; ?>
        </section>
    </aside>
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
    var pedit = function () {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('dboard', 'Menu pedit'); ?>";
        modal.url = "index.php?r=sys/operator/pedit";
        modal.modal();
    }

    var download_contract = function () {
//                window.location = "index.php?r=sys/operator/download";
        var id = 'contractor_id';
        var name = 'contractor_name';
        window.location = "index.php?r=comp/info/electroniclist&id="+id+"&name="+name;
    }
    /*var itemTask = function () {
     if(flag != 1)
     return;

     $.ajax({
     data: {},
     url: "admin.php?r=workflow/workOrder/todoList",
     dataType: "json",
     type: "POST",
     success: function (data) {

     $('#todo_cnt').html(data.total_num);
     $('#todo_num').html(data.total_num);

     $("#todo_list").html('');
     $.each(data.rows, function (key, item) {
     //回调函数有两个参数,第一个是元素索引,第二个为当前值
     $("#todo_list").append("<li><a href=\"admin.php?r=workflow/workOrder/view&id=" + item.ord_id + "\" style='color: black'>" + item.ord_title + "</a></li>");
     });

     setTimeout(itemTask, 60000);
     }
     });
     }
     itemTask();
     //下载操作手册
     var download_manual = function (){
     window.location = "admin.php?r=site/downManual";
     }
     //
     //            var url = "admin.php?r=workflow/workOrder/serverTodoList";
     //            var es = null;
     //
     //            function startEventSource() {
     //                //if (es)
     //                  //  es.close();
     //                es = new EventSource(url);
     //                es.addEventListener('message', function (e) {
     //                    processOneLine(e.data);
     //                }, false);
     //                es.addEventListener('error', handleError, false);
     //            }
     //
     //            function handleError(e) {
     //                //console.log('出错了...');
     //                console.log(e);
     //            }
     //
     //            function connect() {
     //                //workflow/workOrder/list
     //                //sys/operator/list
     //
     //                if(flag != 1)
     //                    return;
     //                //alert(flag);
     //                //alert(url);
     //                if (window.EventSource)
     //                    startEventSource();
     //                //否则在这里处理向后兼容
     //                else
     //                    alert('不支持SSE！');
     //            }
     //
     //            function processOneLine(s) {
     //                try {
     //                    var d = JSON.parse(s);
     //                } catch (e) {
     //                    console.log("无效的json:" + s + "\n" + e);
     //                    return;
     //                }
     //
     //                $("#todo_cnt").html(d.total_num);
     //                $("#todo_num").html(d.total_num);
     //                $("#todo_list").html('');
     //                $.each(d.rows, function (key, item) {
     //                    $("#todo_list").append("<li><a href=\"admin.php?r=workflow/workOrder/view&id=" + item.ord_id + "\" style='color: black'>" + item.ord_title + "</a></li>");
     //                });
     //
     //            }
     //
     //           setTimeout(connect, 1000);*/
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