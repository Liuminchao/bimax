<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
<!--                <div class="col-xs-2 padding-lr5" style="width: 120px;">-->
<!--                    <select class="form-control input-sm" name="q[project_id]" style="width: 100%;">-->
<!--                        <option value="">System</option>-->
<!--                        --><?php
//                            $pro_model = Program::model()->findByPk($program_id);
//                            $root_proid = $pro_model->root_proid;
//                            $root_model = Program::model()->findByPk($root_proid);
//                            $root_proid_name = $root_model->program_name;
//                            echo "<option value='{$root_proid}'>{$root_proid_name}</option>";
//                        ?>
<!--                    </select>-->
<!--                </div>-->
<!--                <div class="col-xs-2 padding-lr5" style="width: 120px;">-->
<!--                    <select class="form-control input-sm" name="q[type_id]" style="width: 100%;">-->
<!--                        <option value="">Select Type</option>-->
<!--                        --><?php
//                            $type_list = QaCheckType::AllType();
//                            foreach ($type_list as $val => $value){
//                        ?>
<!--                                <option value='--><?php //echo $val; ?><!--'>--><?php //echo $value; ?><!--</option>-->
<!--                        --><?php
//                            }
//                        ?>
<!--                    </select>-->
<!--                </div>-->
                <div class="col-xs-2 padding-lr5" >
                    <input type="hidden" id="program_id" name="q[project_id]" value="<?php echo $program_id; ?>">
                    <input type="text" class="form-control input-sm" name="q[version_name]" placeholder="Form Name">
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $program_id; ?>')">Add</button>
            </label>
        </div>
    </div>
</div>