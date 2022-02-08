<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
               
                <div class="col-xs-4 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[contractor_name]" placeholder="<?php echo Yii::t('sub_contractor', 'Contractor_name'); ?>">
                </div>

<!--                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                    <select class="form-control input-sm" name="q[status]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('comp_contractor', 'Status'); ?>-</option>
                        <?php
                        $status_list = Contractor::statusText();
                        foreach ($status_list as $k => $source) {
                            echo "<option value='{$k}'>{$source}</option>";
                        }
                        ?>
                    </select>
                </div>-->
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd()"><?php echo Yii::t('sub_contractor', 'Add Contractor'); ?></button>
            </label>
        </div>
    </div>
</div>