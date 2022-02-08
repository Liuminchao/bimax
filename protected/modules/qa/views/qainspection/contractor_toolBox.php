<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <div class="col-xs-3 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[check_id]" placeholder="<?php echo Yii::t('comp_safety', 'check_id'); ?>">
                </div>
<!--                <div class="col-xs-3 padding-lr5" >-->
<!--                    <input type="text" class="form-control input-sm" name="q[root_proname]" placeholder="--><?php //echo Yii::t('comp_safety', 'root_proname'); ?><!--">-->
<!--                </div>-->
                <a class="tool-a-search" href="javascript:detailQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <?php  $contractor_id = Yii::app()->user->contractor_id;   ?>
                <select class="form-control input-sm" name="q[loane_type]" style="width: 100%;" onchange="SelectModelChange(this.value);">
                    <option value="1" ><?php echo Yii::t('comp_routine','program');  ?></option>
                    <option value="2" selected="selected"><?php echo Yii::t('comp_routine','company');  ?></option>
                </select>
<!--                <button class="btn btn-primary btn-sm" onclick="itemPdf(--><?php //echo $contractor_id; ?><?php //echo Yii::t('comp_safety', 'export'); ?><!--</button>-->
            </label>
        </div>
    </div>
</div>
<script type="text/javascript">
    var SelectModelChange = function (index) {
        window.location = "index.php?r=routine/routineinspection/list&index="+index;
    }
</script>