<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                    <select class="form-control input-sm" name="q[type_no]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('device', 'device_type'); ?>-</option>
                        <?php
                        $devicelist = DeviceType::deviceList();
                        //var_dump($teamlist);
                        foreach ($devicelist as $type_no => $device_type) {
                            echo "<option value='{$type_no}'>{$device_type}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-xs-2 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[device_id]" placeholder="<?php echo Yii::t('device', 'device_id'); ?>">
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="javascript:history.back(-1);"><?php echo Yii::t('proj_project_user', 'back_program'); ?></button>
                <!--<button class="btn btn-primary btn-sm" onclick="itemExport()"><?php echo Yii::t('comp_staff', 'Batch export'); ?></button>-->
<!--                <button class="btn btn-primary btn-sm" onclick="itemAdd()">--><?php //echo Yii::t('device', 'Add Equipment'); ?><!--</button>-->
            </label>
        </div>
    </div>
    <!--    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd()"><?php echo Yii::t('sys_role', 'AddRole'); ?></button>
            </label>
        </div>
    </div>-->
</div>