<div class="row" >
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <div class="form-group " style="width: 200px;">
                    <input type="text" class="form-control input-sm" name="q[group_name]" placeholder="Group Name" style="width: 100%" >
                </div>
                <input type="hidden" name="q[program_id]" id="program_id" value="<?php echo $program_id; ?>">
                <div class="form-group padding-lr5" style="width:100px">
                    <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $program_id; ?>')">New Group</button>
            </label>
        </div>
    </div>
</div>
