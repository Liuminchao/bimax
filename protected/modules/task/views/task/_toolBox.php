<div class="row" >
    <div class="col-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
<!--                <div class="col-2 padding-lr5" >-->
<!--                    <select class="form-control input-sm" name="q[template_id]" id="template_id" style="width: 100%;">-->
<!--                        <option value="">--Template Name--</option>-->
<!--                        --><?php
//                        $template_list = TaskTemplate::templateByProgram($program_id);
//                        foreach ($template_list as $template_id => $template_name) {
//                            echo "<option value='{$template_id}'>{$template_name}</option>";
//                        }
//                        ?>
<!--                    </select>-->
<!--                </div>-->
<!--                <div class="col-2 padding-lr5" >-->
<!--                    <select class="form-control input-sm" id="stage_id" name="q[stage_id]" style="width: 100%;">-->
<!--                        <option value="">--Stage Name--</option>-->
<!--                    </select>-->
<!--                </div>-->
                <input type="hidden" name="q[project_id]" id="project_id" value="<?php echo $program_id; ?>">
                <input type="hidden" name="q[template_id]" id="template_id" value="<?php echo $template_id; ?>">
                <input type="hidden" name="q[stage_id]" id="stage_id" value="<?php echo $stage_id; ?>">
<!--                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i>--><?php //echo Yii::t('common', 'search'); ?><!--</a>-->
            </form>
        </div>
    </div>
    <div class="col-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $program_id; ?>','<?php echo $template_id; ?>','<?php echo $stage_id; ?>')">Add New Task</button>
                <button class="btn btn-default btn-sm" onclick="back('<?php echo $program_id; ?>','<?php echo $template_id; ?>','<?php echo $stage_id; ?>')">Back</button>
            </label>
        </div>
    </div>
</div>
<script type="application/javascript">

    //form初始化
    function FormInit(node) {
        return node.html('<option value="">--Stage Name--</option>');
    }
    //类型和表单类型半联动
    $('#template_id').change(function(){

        var formObj = $("#stage_id");
        var formOpt = $("#stage_id option");
        FormInit(formObj);

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
                    formObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });
</script>