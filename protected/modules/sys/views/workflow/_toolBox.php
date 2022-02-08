<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
               <div class="col-xs-2 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[flow_name]" placeholder="<?php echo Yii::t('sys_workflow','Flow Name');?>">
                </div>
               <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common','search');?></a>
              <!-- <a> <font color = "red"> <?php echo Yii::t('sys_workflow','Plesae Stop This Workflow');?> </font></a> -->
            </form>
        </div>
    </div>
    <?php if (Yii::app()->user->checkAccess("sys/workflow/new")){?>
<!--    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd()"><?php echo Yii::t('sys_workflow','Add Flow');?></button>
            </label>
        </div>
    </div>-->
    <?php }?>
</div>