<section class="content-header">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <ul class="timeline">
                    <li class="time-label">
                        <span class="bg-green"><?php echo Yii::t('sys_workflow', 'Audit Step'); ?></span>
                    </li>
                    <?php
                    if (!empty($step_list)) {
                        foreach ($step_list as $id => $row) {
                            echo '<li>
                        <i class="fa fa-user bg-aqua"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header no-border">
                                '.Yii::t('sys_workflow','the').$row['step'].Yii::t('sys_workflow','step_1').$row['obj_name'].'
                            </h3>
                        </div>
                    </li>';
                        }
                    }else{
                         echo '<li>
                        <i class="fa fa-bug bg-red"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header no-border">您还没有配置流程审批步骤！
                            </h3>
                        </div>
                    </li>';
                    }
                    ?>
                    <li>
                        <i class="fa fa-clock-o"></i>
                    </li>
                </ul>
            </div><!-- col-md-12 end -->
        </div><!-- row end -->
    </section>
</section>