<?php $this->beginContent('//layouts/base'); ?>
<style>
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</style>
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white" style="margin-left: 0px;background-color: #3ABDC7;height: 48px;">
        <div class="container">
            <a href="./" class="brand-link" style="background-color: #3ABDC7;height: 47.5px;padding-top: 7px;">
                <img src="img/bimax_show.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">BIMax Plus</span>
            </a>

        </div>
        <div class="navbar-right" style="margin-right: 0">
            <ul class="nav navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-cogs"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                        <a href="#" class="dropdown-item dropdown-footer" onclick="javascript:test();"><span>My Project</span></a>
                        <div class="dropdown-divider"></div>
                        <?php if (Yii::app()->user->getState('operator_role') == '00'){
                            $contractor_name =  Yii::app()->user->contractor_name;
                            $contractor_id = Yii::app()->user->contractor_id;?>
                            <a href="#" class="dropdown-item dropdown-footer" onclick="javascript:itemOperator('<?php echo $contractor_id; ?>','<?php echo $contractor_name; ?>');">
                                <span><?php  echo Yii::t('sys_operator','smallHeader List') ?></span>
                            </a>
                        <?php } ?>
                    </div>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #0a001f">
                        <span><?echo Yii::app()->user->contractor_name?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <img src="dist/img/avatar5.png" width="33px" class="img-circle elevation-3" alt="User Image" style="margin-bottom: 5px">&nbsp;
                        <span><?echo Yii::app()->user->name?><i class="caret"></i></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                        <a href="javascript:pedit();" class="dropdown-item dropdown-footer"><i class="fa fa-edit mr-2"></i> Edit</a>
                        <div class="dropdown-divider"></div>
                        <a href="./?r=site/logout" class="dropdown-item dropdown-footer" style="float: right"><i class="fa fa-sign-out-alt mr-2"></i> Log Off</a>
                        <div class="dropdown-divider"></div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-left: 0px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="m-0"><?php echo $this->smallHeader; ?></h1>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row (main row) -->
                <?php echo $content; ?>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->

    </div>
    <!-- ./wrapper -->
    <?php $this->endContent(); ?>
    <script type="text/javascript">
        function dms(id) {
            $.ajax({
                url: "index.php?r=rf/dms/login&id="+id,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    window.open(data.path,"_blank");
                }
            });

        }
    </script>

