<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                
                <input type="hidden" name="q[task_id]" value="<?php echo $task_id; ?>">
                <input type="hidden" name="q[program_id]" value="<?php echo $program_id; ?>">
                
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemUpload()"><?php echo Yii::t('task', 'upload');   ?></button>
                <button class="btn btn-primary btn-sm"  onclick="back1();"><?php echo Yii::t('common', 'button_back'); ?></button>
                <!--<button class="btn btn-primary btn-sm" onclick="back()"><?php echo Yii::t('common', 'button_back');  ?></button>-->
            </label>
        </div>
    </div>
</div>