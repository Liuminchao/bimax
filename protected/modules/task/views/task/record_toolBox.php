<div class="row" >
    <div class="col-12">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <input type="hidden" name="q[project_id]" id="project_id" value="<?php echo $program_id; ?>">
                <input type="hidden" name="q[clt_type]" id="clt_type" value="<?php echo $clt_type; ?>">
                <div class="form-group padding-lr5" style="width: 160px;padding-left: 0px;padding-bottom:5px;">
                    <select class="form-control input-sm" name="q[template_id]" id="template_id" style="width: 100%;">
                        <option value="">--Template--</option>
                        <?php
                        $template_list = TaskTemplate::templateByProgram($program_id);
                        if(count($template_list)>0){
                            foreach ($template_list as $template_id => $template_name) {
                                echo "<option value='{$template_id}'>{$template_name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group padding-lr5" style="width: 150px">
                    <select class="form-control input-sm" id="stage_id" name="q[stage_id]" style="width: 100%;">
                        <option value="">--Stage--</option>
                    </select>
                </div>
                <div class="form-group padding-lr5" style="width: 150px">
                    <select class="form-control input-sm" id="task_id" name="q[task_id]" style="width: 100%;">
                        <option value="">--Task--</option>
                    </select>
                </div>
                

                <?php
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                //                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
                <div class="form-group padding-lr5" style="width: 40px;">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div class="form-group padding-lr5" style="width: 190px;">
                    <div class="input-group date"  data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_start_date" name="q[start_date]"
                               id="q_start_date"  placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        <div class="input-group-append" data-target="#q_start_date" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group padding-lr5" style="width: 20px;">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div class="form-group padding-lr5" style="width: 190px;">
                    <div class="input-group date"  data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_end_date" name="q[end_date]"
                               id="q_end_date"  placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        <div class="input-group-append" data-target="#q_end_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>


                <div class="form-group padding-lr5" style="width: 160px;">
                    <input type="text" class="form-control input-sm" name="q[pbu_name]" placeholder="Element Name" style="width: 100%">
                </div>
                <div class="form-group padding-lr5" style="width:100px;padding-bottom:5px;">
                    <a class="tool-a-search" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="application/javascript">

    //Stage初始化
    function StageInit(stage) {
        return stage.html('<option value="">--Stage Name--</option>');
    }
    //Task初始化
    function TaskInit(task) {
        return task.html('<option value="">--Task Name--</option>');
    }
    //模版 阶段  任务半联动
    $('#template_id').change(function(){

        var stageObj = $("#stage_id");
        var stageOpt = $("#stage_id option");
        StageInit(stageObj);
        var taskObj = $("#task_id");
        var taskOpt = $("#task_id option");
        TaskInit(taskObj);

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
    //模版 阶段  任务半联动
    $('#stage_id').change(function(){

        var taskObj = $("#task_id");
        var taskOpt = $("#task_id option");
        TaskInit(taskObj);

        $.ajax({
            type: "POST",
            url: "index.php?r=task/task/querytask",
            data: {template_id:$("#template_id").val(),stage_id:$("#stage_id").val()},
            dataType: "json",
            success: function(data){ //console.log(data);
                if (!data) {
                    return;
                }
                for (var o in data) {//console.log(o);
                    taskObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });

    $(function () {
        $('#q_start_date').datetimepicker({
            format: 'DD MMM yyyy',

        });
        $('#q_end_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
    })
</script>