<?php $this->beginContent('//layouts/base_dash'); ?>
<style type="text/css">

</style>
<div id='dashboard_chart'class="wrapper row-offcanvas row-offcanvas-left">

    <!-- Left side column. contains the logo and sidebar -->
    <!--    <aside class="left-side sidebar-offcanvas">-->
    <!-- sidebar: style can be found in sidebar.less -->
    <!--        --><?php //$this->widget('SysMenu', array()); ?>
    <!-- /.sidebar -->
    <!--    </aside>-->
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side strech" >
        <!-- Content Header (Page header) -->
        <section class="content-header" >
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
        <section class="content" >
            <?php echo $content; ?>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->


</div><!-- ./wrapper -->
<?php $this->endContent(); ?>
<script type="text/javascript">

</script>


