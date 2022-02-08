<?php $this->beginContent('//layouts/base'); ?>
<!-- header logo: style can be found in header.less -->
<header class="header">

    <a href="./" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        <?php echo Yii::t('login', 'Website Name'); ?>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <a href="#" onclick="itemMenu('<?php echo Yii::app()->session['program_id'] ?>')" class="logo" style="white-space:nowrap;background-color: #3ABDC7;font-family:Microsoft YaHei;">
            <!-- Add the class icon to your logo image or logo icon to add the margining -->
            <?php
                $program_id = Yii::app()->session['program_id'];
                $p_model = Program::model()->findByPk($program_id);
                $program_name = $p_model->program_name;
                if(strlen($program_name) > 45){
                    $program_name = substr($program_name,0,45).'...';
                }
                echo $program_name;
            ?>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">

                <?php if (Yii::app()->user->getState('operator_type') == '01' ){
                    $contractor_id = $contractor_id = Yii::app()->user->contractor_id;?>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-gears"></i>
                                <b class="caret"></b>
                            </a>

                            <ul class="dropdown-menu">

<!--                                --><?php //if (Yii::app()->user->getState('operator_type') != '00'){ ?>
<!--                                    <li>-->
<!--                                        <a href="#" onclick="javascript:download_contract();">-->
<!--<!--                                            <i class="fa fa-file"></i>-->
<!--                                            <span>--><?php //echo Yii::t('electronic_contract', 'electronic_contract'); ?><!--</span>-->
<!--                                        </a>-->
<!--                                    </li>-->
<!--                                --><?php //} ?>
<!--                                --><?php // if (Yii::app()->user->checkAccess("103")){ ?>
<!--                                    <li>-->
<!--                                        <a href="?r=comp/staff/list" >-->
<!--                                            <!--                                            <!--                                        <i class="fa fa-file"></i>-->
<!--                                            <span>--><?php //echo Yii::t('dboard', 'Menu Staff'); ?><!--</span>-->
<!--                                        </a>-->
<!--                                    </li>-->
<!--                                --><?php //} ?>
                                <li>
                                    <a href="#" onclick="javascript:test();">
                                        <!--                                        <i class="fa fa-file"></i>-->
                                        <span><?php echo Yii::t('dboard','Menu My Project');  ?></span>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <?php if (Yii::app()->user->getState('operator_role') == '00'){
                                    $contractor_name =  Yii::app()->user->contractor_id;
                                    $contractor_id = Yii::app()->user->contractor_id;?>
                                    <li>
                                        <a href="#" onclick="javascript:itemOperator('<?php echo $contractor_id; ?>','<?php echo $contractor_name; ?>');">
                                            <!--                                            <i class="fa fa-file"></i>-->
                                            <span><?php  echo Yii::t('sys_operator','smallHeader List') ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                <?php } ?>

                <!--                 Tasks: style can be found in dropdown.less-->
                <!--                <li class="dropdown tasks-menu">-->
                <!--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
                <!--                        <i class="fa fa-tasks"></i>-->
                <!--                        <span class="label label-danger"><span id="todo_cnt">0</span></span>-->
                <!--                    </a>-->
                <!--                    <ul class="dropdown-menu">-->
                <!--                        <li class="header">您有<span id="todo_num" class="text-red">0</span>条待执行的工单</li>-->
                <!--                        <li>-->
                <!--                             inner menu: contains the actual data-->
                <!--                            <ul id="todo_list" class="menu">-->
                <!---->
                <!--                            </ul>-->
                <!--                        </li>-->
                <!--                        <li class="footer">-->
                <!--                            <a href="admin.php?r=workflow/workOrder/list&active=todo">显示全部</a>-->
                <!--                        </li>-->
                <!--                    </ul>-->
                <!---->
                <!--                </li>-->
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?php echo Yii::app()->user->getState('name');?><i class="caret"></i></span>
                    </a>

                    <ul class="dropdown-menu">
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="javascript:pedit();" class="btn btn-default btn-flat"><?php echo Yii::t('dboard', 'Menu pedit'); ?></a>
                            </div>
                            <div class="pull-right">
                                <a href="./?r=site/logout" class="btn btn-default btn-flat"><?php echo Yii::t('dboard', 'Menu logout'); ?></a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar.less -->
        <?php $this->widget('SysMenu', array()); ?>
        <!-- /.sidebar -->
    </aside>
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section id="content-header" class="content-header">
            <h1>
                <?php echo $this->smallHeader; ?>
                <small><?php //echo $this->contentHeader; ?></small>
            </h1>
<!--            <ol class="breadcrumb">-->
<!--                <li><a href="#"><i class="fa fa-dashboard"></i>--><?php //echo $this->bigMenu; ?><!--</a></li>-->
<!--                <li class="active">--><?php //echo $this->contentHeader; ?><!--</li>-->
<!--            </ol>-->
        </section>

        <!-- Main content -->
        <section class="content">
            <?php echo $content; ?>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->


</div><!-- ./wrapper -->
<?php $this->endContent(); ?>

<script type="text/javascript">
    //点击二级菜单展开/收起三级菜单
    $(".fa-plus-square-o,.fa-minus-square-o").parent("a").click(
            function () {
                var iobj = $(this).find("i");
                if (iobj.hasClass("fa-plus-square-o")) {
                    iobj.removeClass("fa-plus-square-o");
                    iobj.addClass("fa-minus-square-o");
                } else if (iobj.hasClass("fa-minus-square-o")) {
                    iobj.removeClass("fa-minus-square-o");
                    iobj.addClass("fa-plus-square-o");
                }
            }
    );
    function dms(id,tag) {
        $.ajax({
            url: "index.php?r=rf/dms/login&id="+id+"&tag="+tag,
            type: "GET",
            dataType: "json",
            success: function(data) {
                window.open(data.path,"_blank");
            }
        });

    }
</script>

