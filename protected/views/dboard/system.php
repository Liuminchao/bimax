<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3> <?php echo $main_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Project MC');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-gear-b"></i>
            </div>
            <a class="small-box-footer" href="./?r=sys/main/list">
                <?php echo Yii::t('dboard','Enter');?>
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3> <?php echo $total_staff_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Platform');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
            <div class="small-box-footer" >
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3> <?php echo $oper_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Operator');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a class="small-box-footer" href="./?r=sys/operator/list">
                <?php echo Yii::t('dboard','Enter');?>
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3> <?php echo $comp_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Comp');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a class="small-box-footer" href="?r=comp/info/list">
                <?php echo Yii::t('dboard','Enter');?>
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!--<div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3> <?php echo $log_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Optlog');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-gear-b"></i>
            </div>
            <a class="small-box-footer" href="?r=sys/optlog/list">
                <?php echo Yii::t('dboard','Enter');?>
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>-->
</div>
</div><!-- row -->