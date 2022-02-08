<div class="row">
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <div class="row" >
                    <input type="hidden" name="q[user_id]" value="<?php echo $user_id; ?>">
                    <?php
                        if(isset($type)) {
                    ?>
                            <input type="hidden" name="q[type]" value="<?php echo $type; ?>">
                    <?php
                        }
                    ?>
                    <div class="form-group padding-lr5" style="width:200px">
                        <input type="text" class="form-control input-sm" name="q[aptitude_name]" style="width: 100%" placeholder="<?php echo Yii::t('comp_document', 'document_name'); ?>">
                    </div>
                    <a class="tool-a-search padding-lr5" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-3">
        <div class="dataTables_filter" >
            <label>
                <?php
                if($type == 'mc') {
                ?>
                    <button class="btn btn-primary btn-sm" onclick="itemUpload()"><?php echo Yii::t('proj_project_user', 'smallHeader Upload');   ?></button>
                <?php }
                ?>
                <button class="btn btn-default btn-sm"  onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
                <!--<button class="btn btn-primary btn-sm" onclick="back()"><?php echo Yii::t('common', 'button_back');  ?></button>-->
            </label>
        </div>
    </div>
</div>