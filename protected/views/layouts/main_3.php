<?php $this->beginContent('//layouts/base_dash'); ?>
<style type="text/css">

</style>
<div id='dashboard_chart'class="wrapper row-offcanvas row-offcanvas-left" style="min-height: inherit;">

    <!-- Left side column. contains the logo and sidebar -->
    <!--    <aside class="left-side sidebar-offcanvas">-->
    <!-- sidebar: style can be found in sidebar.less -->
    <!--        --><?php //$this->widget('SysMenu', array()); ?>
    <!-- /.sidebar -->
    <!--    </aside>-->
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side strech" style="min-height: inherit;">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content" style="min-height: inherit;">
            <?php echo $content; ?>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->


</div><!-- ./wrapper -->
<?php $this->endContent(); ?>
<script type="text/javascript">

</script>


