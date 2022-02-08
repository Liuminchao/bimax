<div class="row" style="margin-bottom: 10px;">
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <input type="hidden" name="q[program_id]" value="<?php echo $program_id; ?>">
                <input type="hidden" name="q[block]" value="<?php echo $block; ?>">
                <div class="form-group padding-lr5" style="width: 200px">
                    <input class="form-control input-sm" type="text" name="q[user_name]" placeholder="<?php echo Yii::t('comp_staff', 'User_name'); ?>" value="<?php echo array_key_exists('user_name',$args)?$args['user_name']:""; ?>"  style="width: 100%">
                </div>
      
                <a class="tool-a-search" href="javascript:itemChargeQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-3">

    </div>
</div>