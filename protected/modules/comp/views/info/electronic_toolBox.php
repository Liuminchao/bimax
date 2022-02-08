<div class="row" style="margin-left: -20px;">

    <div class="col-xs-12">
        <div class="dataTables_filter" >
            <label>
                <?php if (Yii::app()->user->getState('operator_type') == '00'){ ?>
                    <button class="btn btn-primary btn-sm" onclick='upload("<?php echo $id; ?>","<?php echo $name; ?>")'><?php echo Yii::t('electronic_contract', 'upload_contract'); ?></button>
                    <button class="btn btn-primary btn-sm" onclick="back()"><?php echo Yii::t('electronic_contract', 'back'); ?></button>
                <?php  } ?>
            </label>
        </div>
    </div>
</div>