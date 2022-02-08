<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <input type="hidden" name="q[user_id]" value="<?php echo $user_id; ?>">
                <div class="col-xs-2 padding-lr5">
                    <input type="text" class="form-control input-sm" name="q[aptitude_content]" placeholder="<?php echo Yii::t('comp_document', 'document_name'); ?>">
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemUpload()"><?php echo Yii::t('proj_project_user', 'smallHeader Upload');   ?></button>
                <button class="btn btn-primary btn-sm"  onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
                <!--<button class="btn btn-primary btn-sm" onclick="back()"><?php echo Yii::t('common', 'button_back');  ?></button>-->
            </label>
        </div>
    </div>
</div>