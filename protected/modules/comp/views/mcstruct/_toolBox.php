<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <div class="col-xs-2 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[team_id]" placeholder="<?php echo Yii::t('comp_mcstruct','team_id');?>">
                </div>
                <div class="col-xs-2 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[team_name]" placeholder="<?php echo Yii::t('comp_mcstruct','team_name');?>">
                </div>
                <div class="col-xs-2 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[link_people]" placeholder="<?php echo Yii::t('comp_mcstruct','link_people');?>">
                </div>
                <div class="col-xs-2 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[link_phone]" placeholder="<?php echo Yii::t('comp_mcstruct','link_phone');?>">
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common','search');?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd()"><?php echo Yii::t('comp_mcstruct','Add MS');?></button>
            </label>
        </div>
    </div>
</div>