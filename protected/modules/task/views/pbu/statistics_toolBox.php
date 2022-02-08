<div class="row" >
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <div class="row" >
                    <input type="hidden" id="pbu_tag" name="q[pbu_tag]"  value="<?php echo $pbu_tag; ?>">
                    <input type="hidden" id="project_id" name="q[project_id]"  value="<?php echo $program_id; ?>">
                    <input type="hidden" id="block" name="q[block]"  value="<?php echo $block; ?>">
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                        <select class="form-control input-sm" name="q[template_id]"  id="template_id" style="width: 100%;">
                            <option value="">--Template Name--</option>
                            <?php
                            $template_list = TaskTemplate::templateByProgram($program_id);
                            $stage_model = TaskStage::model()->findByPk($select_stage_id);
                            $select_template_id = $stage_model->template_id;
                            if(count($template_list)>0){
                                foreach ($template_list as $template_id => $template_name) {
                                    $selected = '';
                                    if($select_template_id == $template_id){
                                        $selected = 'selected';
                                    }
                                    echo "<option value='{$template_id}'  $selected>{$template_name}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                        <select class="form-control input-sm" id="stage_id" name="q[stage_id]" style="width: 100%;" >
                            <option value="">--Stage Name--</option>
                            <?php
                            if($select_template_id){
                                $stage_list = TaskStage::queryStage($select_template_id);
                                if(count($stage_list)>0){
                                    foreach ($stage_list as $stage_id => $stage_name) {
                                        $selected = '';
                                        if($select_stage_id == $stage_id){
                                            $selected = 'selected';
                                        }
                                        echo "<option value='{$stage_id}'  $selected>{$stage_name}</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <a class="tool-a-search" style="" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-3">

    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        //模版 阶段  任务半联动
        $('#template_id').change(function(){

            var stageObj = $("#stage_id");
            var stageOpt = $("#stage_id option");
            StageInit(stageObj);

            $.ajax({
                type: "POST",
                url: "index.php?r=task/task/querystage",
                data: {template_id:$("#template_id").val()},
                dataType: "json",
                success: function(data){ //console.log(data);
                    if (!data) {
                        return;
                    }
                    for (var o in data) {//console.log(o);
                        stageObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                    }
                },
            });
        });
    })
    //Stage初始化
    function StageInit(stage) {
        return stage.html('<option value="">--Stage Name--</option>');
    }
</script>