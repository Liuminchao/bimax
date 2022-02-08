<?php $this->beginContent('//layouts/base'); ?>

<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="img/bimax.png" alt="BimaxLogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #3ABDC7;height: 48px;">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a class="nav-link" href="#" onclick="itemMenu('<?php echo Yii::app()->session['program_id'] ?>')" class="logo" style="white-space:nowrap;background-color: #3ABDC7;font-family:Microsoft YaHei;font-size:16px; padding-left: 0px">
                    <!-- Add the class icon to your logo image or logo icon to add the margining -->
                    <?php
                    $program_id = Yii::app()->session['program_id'];
                    $p_model = Program::model()->findByPk($program_id);
                    $program_name = $p_model->program_name;
                    //if(strlen($program_name) > 45){
                    //    $program_name = substr($program_name,0,45).'...';
                    //}
                    echo $program_name;
                    ?>
                </a>
            </li>
        </ul>


        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fa fa-envelope"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                    <span class="dropdown-item dropdown-header">demo</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer" onclick="javascript:test();"><span>My Project</span></a>
                </div>
            </li>
            <li class="nav-item" style="margin-left: 25px;">
                <img src="dist/img/avatar5.png" width="33px" class="img-circle elevation-3" alt="User Image">
            </li>
            <li class="dropdown user user-menu" style="padding-top: 8px;margin-left: 16px;">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #0a001f;">
                    <i class="glyphicon glyphicon-user"></i>
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

    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-white-primary elevation-4">
        <!-- Brand Logo -->
        <a href="./" class="brand-link" style="background-color: #3ABDC7;height: 47.5px;padding-top: 7px;">
            <img src="img/bimax_show.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" width="30px" style="opacity: .8">
            <span class="brand-text font-weight-light">BIMax Plus</span>
        </a>
        <?php $this->widget('SysMenu', array()); ?>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
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
        <aside class="control-sidebar control-sidebar-light" style="height:100%;width: 400px;">
            <!-- Control sidebar content goes here -->
            <div id="info" class="p-3 control-sidebar-content control-sidebar-scroll" >

            </div>
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- /.content-wrapper -->


</div>
<!-- ./wrapper -->
<?php $this->endContent(); ?>
<script type="text/javascript">
    function dms() {
        $.ajax({
            url: "index.php?r=rf/dms/login",
            type: "GET",
            dataType: "json",
            success: function(data) {
                window.open(data.path,"_blank");
            }
        });

    }
</script>

