<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3> <?php echo $user_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Subcomp User');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a class="small-box-footer" href="?r=comp/usersubcomp/list">
                <?php echo Yii::t('dboard','Enter');?>
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3> <?php echo $staff_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Subcomp Staff');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a class="small-box-footer" href="?r=comp/staff/list">
                <?php echo Yii::t('dboard','Enter');?>
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3> <?php echo $lice_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Licedown');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-android-download"></i>
            </div>
            <a class="small-box-footer" href="?r=license/licensepdf/list">
                <?php echo Yii::t('dboard','Enter');?>
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3> <?php echo $assign_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Assign User');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-gear-b"></i>
            </div>
            <a class="small-box-footer" href="?r=proj/assignuser/list">
                <?php echo Yii::t('dboard','Enter');?>
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
</div><!-- row -->