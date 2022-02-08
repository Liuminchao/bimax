<div class="row" >
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <div class="form-group padding-lr5" style="width: 120px;padding-left: 0px;">
                    <input id="project_id"  name="q[project_id]" type="hidden" value="<?php echo $project_id ?>">
                    <select class="form-control input-sm" name="q[block]" style="width: 100%;">
                        <option value="">-Block-</option>
                        <?php
                            $args['project_id'] = $project_id;
                            $block_list = BlockChart::blockList($args);
                            foreach ($block_list as  $i=> $j) {
                                echo "<option value='{$j}'>{$j}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[role_name]" placeholder="<?php echo Yii::t('sys_role', 'role_name'); ?>">
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $project_id ?>')">Add</button>
                <button class="btn btn-default btn-sm"  onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </label>
        </div>
    </div>
</div>