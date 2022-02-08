<div class="row" >
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <div class="form-group padding-lr5" style="width: 200px;padding-left: 0px;">
                    <input type="text" class="form-control input-sm" name="q[template_name]" placeholder="Template" style="width: 100%;">
                </div>
                <input type="hidden" name="q[program_id]" id="program_id" value="<?php echo $program_id; ?>">
                <div class="form-group padding-lr5" style="width:100px">
                    <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-3">
        <label  class="padding-lr5 float-sm-right">
            <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $program_id; ?>')">New Template</button>
        </label>
    </div>
</div>