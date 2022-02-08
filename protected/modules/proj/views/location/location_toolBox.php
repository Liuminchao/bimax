<div class="row" >
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <input type="hidden" id="project_id" name="q[block]" value="<?php echo $block ?>">
                <input type="hidden" id="project_id" name="q[project_id]" value="<?php echo $project_id ?>">
                <div class="form-group " style="width: 160px;">
                    <input type="text" class="form-control input-sm" name="q[level]" placeholder="Level" style="width: 100%;" value=<?php echo $args['level']==''?'':$args['level']; ?>>
                </div>
                <div class="form-group padding-lr5" style="width: 160px;">
                    <input type="text" class="form-control input-sm" name="q[unit]" placeholder="Unit" style="width: 100%;" value=<?php echo $args['unit']==''?'':$args['unit']; ?>>
                </div>

                <a class="tool-a-search" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-3">
        <div class="padding-lr5 float-sm-right">
            <button class="btn btn-primary btn-sm" onclick="itemUnit('<?php echo $project_id ?>','<?php echo $block ?>')">Unit No</button>
            <button class="btn btn-primary btn-sm" onclick="itemSync('<?php echo $project_id ?>','<?php echo $block ?>')">Floor Type</button>
            <button class="btn btn-primary btn-sm" onclick="itemUpload('<?php echo $project_id ?>','<?php echo $block ?>')">Drawings</button>
            <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $project_id ?>','<?php echo $block ?>')">Add</button>
        </div>
    </div>
</div>