<div class="row" style="margin-left: -20px;">
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <div class="form-group padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[condition_id]" placeholder="Condition">
                </div>
                <div class="form-group padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[condition_name]" placeholder="Condition Name">
                </div>
                <div class="form-group padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[condition_name_en]" placeholder="Condition Name En">
                </div>
                <div class="form-group padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[status]" placeholder="Status">
                </div>
                <div class="form-group padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[record_time]" placeholder="Record Time">
                </div>
      
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $type_id  ?>')"><?php echo Yii::t('license_condition', 'smallHeader New');?></button>
                <button class="btn btn-primary btn-sm" onclick="itemBack()"><?php echo Yii::t('common', 'button_back');?></button>
            </label>
        </div>
    </div>
</div>