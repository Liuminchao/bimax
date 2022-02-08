<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3> <?php echo $user_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Main User');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a class="small-box-footer" href="?r=comp/usercomp/list">
                <?php echo Yii::t('dboard','Enter');?>
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3> <?php echo $proj_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Project List');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-settings"></i>
            </div>
            <a class="small-box-footer" href="?r=proj/project/list">
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
                <h3> <?php echo $meeting_cnt;?> </h3>
                <p><?php echo Yii::t('dboard','Menu Meeting');?></p>
            </div>
            <div class="icon">
                <i class="ion ion-gear-b"></i>
            </div>
            <a class="small-box-footer" href="?r=tbm/meeting/list">
                <?php echo Yii::t('dboard','Enter');?>
                <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
</div><!-- row -->