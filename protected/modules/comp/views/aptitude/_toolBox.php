<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                <select class="form-control input-sm" id="info" name="q[info]" style="width: 100%;">
                    <option value="">-<?php echo Yii::t('comp_aptitude', 'List_qualifications'); ?>-</option>
                    <option value="bca"><?php echo Yii::t('comp_staff', 'bca'); ?></option>
                    <option value="pass"><?php echo Yii::t('comp_aptitude', 'Passport'); ?></option>
                    <option value="csoc"><?php echo Yii::t('comp_staff', 'csoc'); ?></option>
                    <option value="ins_scy"><?php echo Yii::t('comp_staff', 'Ins_scy'); ?></option>
                    <option value="ins_med"><?php echo Yii::t('comp_staff', 'Ins_med'); ?></option>
                    <option value="ins_adt"><?php echo Yii::t('comp_staff', 'Ins_adt'); ?></option>
                </select>
                </div>
                <div class="col-xs-2 padding-lr5">
                    <input type="text" id="days" class="form-control input-sm"  name="q[days]" placeholder="<?php echo Yii::t('comp_aptitude', 'days'); ?>">
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">

</script>