<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <input type="hidden" name="q[project_id]" value="<?php echo $project_id; ?>">
                <input type="hidden" name="q[company_id]" value="<?php echo $company_id; ?>">
                <input type="hidden" name="q[type]" value="<?php echo $type; ?>">
                <div style="padding-left: 5px">
                    <input type="text" name="q[user_name]" style="width: 120px;" placeholder="<?php echo Yii::t('comp_staff', 'User_name'); ?>">
                </div>
                <div style="margin-top: -30px;margin-left: 145px">
                    <select class="form-control input-sm" name="q[status]" >
                        <option value="">-<?php echo Yii::t('proj_project_user','status');?>-</option>
                        <?php
                        $status_list = ProgramUser::statusText(); //状态text
                        //var_dump($teamlist);
                        foreach ($status_list as $type_no => $status) {
                            echo "<option value='{$type_no}'>{$status}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div style="margin-top: -30px;margin-left: 310px;width:110px">
                    <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd()"><?php echo Yii::t('sys_role', 'AddRole'); ?></button>
            </label>
        </div>
    </div>
</div>