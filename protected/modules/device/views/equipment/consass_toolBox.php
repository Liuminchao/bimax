<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
<!--                <div class="col-xs-3 padding-lr5" >-->
<!--                    <input type="text" class="form-control input-sm" name="q[title]" placeholder="--><?php //echo Yii::t('tbm_meeting', 'title'); ?><!--">-->
<!--                </div>-->
                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[program_id]" id="form_program_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?>--</option>
                        <?php
                        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                        $program_list = Program::McProgramList($args);
                        if($program_list) {
                            foreach ($program_list as $k => $name) {
                                echo "<option value='{$k}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
<!--                <div class="col-xs-2 padding-lr5" style="width: 180px;">-->
<!--                    <select class="form-control input-sm" name="q[contractor_id]" id="form_con_id" style="width: 100%;">-->
<!--<!--                        <option value="">----><?php ////echo Yii::t('comp_routine', 'company'); ?><!--<!----</option>-->
<!--                        --><?php
//                        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//                        $contractor_list = Contractor::Mc_scCompList($args);
//                        if($contractor_list) {
//                            foreach ($contractor_list as $k => $name) {
//                                echo "<option value='{$k}'>{$name}</option>";
//                            }
//                        }
//                        ?>
<!--                    </select>-->
<!--                </div>-->
<!--                --><?php
//                    $month = Utils::MonthToEn(date('Y-m',strtotime('-1 month')));
//                ?>
<!--                <div class="col-xs-2 padding-lr5" style="width: 145px;">-->
<!--                    <div class="input-group has-error">-->
<!--                        <div class="input-group">-->
<!--                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>-->
<!--                            <input type="text" class="form-control input-sm tool-a-search" name="q[plan_date]"-->
<!--                                   value="--><?php //echo $month ?><!--"   id="plan_date" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="--><?php //echo Yii::t('common', 'date_of_application'); ?><!--"/>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd()"><?php echo Yii::t('tbm_meeting', 'add_plan'); ?></button>
            </label>
        </div>
    </div>
</div>