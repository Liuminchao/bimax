<div class="row" >
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <input type="hidden" id="project_id" name="q[project_id]" value="<?php echo $project_id ?>">
                <input type="hidden" id="type_1" name="q[type_1]" value="<?php echo $type_id ?>">
                <div class="form-group padding-lr5" style="width: 200px;">
                    <input type="text" class="form-control input-sm" name="q[type_2]" placeholder="Component Group" style="width: 100%" value="<?php echo $args['type_2']==''?'':$args['type_2']; ?>" >
                </div>
                <div class="form-group padding-lr5" style="width: 200px;">
                    <input type="text" class="form-control input-sm" name="q[type_3]" placeholder="Defect Category"  style="width: 100%" value="<?php echo $args['type_3']==''?'':$args['type_3']; ?>" >
                </div>
                <a class="tool-a-search" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-3">
            <label  class=" float-sm-right">
                <!--<button class="btn btn-primary btn-sm" onclick="itemExport()"><?php echo Yii::t('comp_staff', 'Batch export'); ?></button>-->
<!--                <button class="btn btn-primary btn-sm" onclick="itemAdd()">--><?php //echo Yii::t('comp_staff', 'Add Staff'); ?><!--</button>-->
                <!--                <button class="btn btn-primary btn-sm" onclick="itemTabs()">图片上传</button>-->
            </label>
        </div>
    </div>
</div>