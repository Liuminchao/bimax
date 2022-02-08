<?php
/* @var $this RoleController */
/* @var $model Role */
/* @var $form CActiveForm */
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => true,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
));
?>
<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>
<div class="card card-primary card-outline">

    <div class="card-body" style="overflow-x: auto">
        <div class="row">
            <div class="col-5 col-sm-3">
                <input type="hidden" id="project_id" name="sub[project_id]" value="<?php echo $project_id; ?>">
                <select class="form-control input-sm" name="sub[template_id]" id="template_id" style="width: 100%;" onchange="change_stage()">
                    <?php
                    $template_list = TaskTemplate::templateByProgram($project_id);
                    if(count($template_list)>0){
                        foreach ($template_list as $select_template_id => $template_name) {
                            if(!$template_id){
                                $template_id = $select_template_id;
                            }
                            if($template_id && $template_id == $select_template_id){
                                echo "<option value='{$select_template_id}' selected>{$template_name}</option>";
                            }else{
                                echo "<option value='{$select_template_id}'>{$template_name}</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-5 col-sm-3">
                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                    <?php
                        $stage_list = TaskStage::queryStage($template_id);
                        $total_day = 0;
                        $index = 0;
                        if(count($stage_list)>0){
                            foreach($stage_list as $stage_id => $stage_name){
                                $stage_model = TaskStage::model()->findByPk($stage_id);
                                $stage_day = $stage_model->plan_day;
                                $total_day+=$stage_day;
                                $stage_color = $stage_model->stage_color;
                                $index++;
                    ?>
                                <?php if($index == 1){ ?>
                                    <a class="nav-link active" id="vert-tabs-<?php echo $stage_id; ?>-tab" data-toggle="pill" href="#vert-tabs-<?php echo $stage_id; ?>" role="tab" aria-controls="vert-tabs-<?php echo $stage_id; ?>" aria-selected="false"><?php echo $stage_name; ?></a>
                                <?php }else{ ?>
                                    <a class="nav-link " id="vert-tabs-<?php echo $stage_id; ?>-tab" data-toggle="pill" href="#vert-tabs-<?php echo $stage_id; ?>" role="tab" aria-controls="vert-tabs-<?php echo $stage_id; ?>" aria-selected="false"><?php echo $stage_name; ?></a>
                                <?php } ?>
                    <?php
                            }
                        }
                    ?>

                </div>
            </div>
            <div class="col-7 col-sm-9">
                <div class="tab-content" id="vert-tabs-tabContent">
                    <?php
                        $stage_list = TaskStage::queryStage($template_id);
                        $total_day = 0;
                        $index = 0;
                        if(count($stage_list)>0){
                            foreach($stage_list as $stage_id => $stage_name) {
                                $stage_model = TaskStage::model()->findByPk($stage_id);
                                $stage_day = $stage_model->plan_day;
                                $total_day += $stage_day;
                            }
                            foreach($stage_list as $select_stage_id => $stage_name){
                                $stage_model = TaskStage::model()->findByPk($select_stage_id);
                                $stage_day = $stage_model->plan_day;
                                $stage_color = $stage_model->stage_color;
                                $index++;
                        ?>
                                <?php
                                    if($index == 1){
                                ?>
                                        <div class="tab-pane text-left fade active show" id="vert-tabs-<?php echo $select_stage_id ?>" role="tabpanel" aria-labelledby="vert-tabs-<?php echo $select_stage_id ?>-tab">
                                <?php
                                    }else{
                                ?>
                                        <div class="tab-pane fade" id="vert-tabs-<?php echo $select_stage_id ?>" role="tabpanel" aria-labelledby="vert-tabs-<?php echo $select_stage_id ?>-tab">
                                <?php
                                    }
                                ?>
                                    <div class="row">
                                        <table class="table table-bordered dataTable" id="projlist" aria-describedby="example2_info">
                                            <tr>
                                                <td>Day</td>
                                                <?php
                                                for($i=1;$i<=$total_day;$i++){
                                                    echo "<td >$i</td>";
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                            $begin_stage_day = 0;
                                            $begin_task_day = 0;
                                            foreach($stage_list as $stage_id => $stage_name){
                                                $stage_model = TaskStage::model()->findByPk($stage_id);
                                                $stage_color = $stage_model->stage_color;
                                                $stage_day = $stage_model->plan_day;
                                                $task_list = TaskList::taskByStage($select_stage_id);
                                                ?>
                                                <tr>
                                                    <td><?php echo $stage_name; ?></td>
                                                    <?php
                                                    $j = 1;
                                                    for($i=1;$i<=$total_day;$i++){
                                                        if($i > $begin_stage_day && $j <= $stage_day){
                                                            $begin_stage_day++;
                                                            $j++;
                                                            echo "<td bgcolor='$stage_color'></td>";
                                                        }else{
                                                            echo "<td ></td>";
                                                        }
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                                if(count($task_list)>0 && $select_stage_id == $stage_id){
                                                    foreach ($task_list as $task_id => $task_name){
                                                        $task_model = TaskList::model()->findByPk($task_id);
                                                        $task_day = $task_model->plan_day;
                                                        $task_lag = $task_model->lag_day;
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $task_name; ?></td>
                                                            <?php
                                                            $j = 1;
                                                            $begin_task_day = $begin_task_day+$task_lag;
                                                            for($i=1;$i<=$total_day;$i++){
                                                                if($i > $begin_task_day && $j <= $task_day){
                                                                    $begin_task_day++;
                                                                    $j++;
                                                                    echo "<td bgcolor='$stage_color'></td>";
                                                                }else{
                                                                    echo "<td ></td>";
                                                                }
                                                            }
                                                            ?>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <?php
                                                $begin_task_day=$begin_task_day+$stage_day;
                                            }
                                            ?>
                                        </table>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-8">Sub Activities</div>
                                        <div class="col-2">Duration</div>
                                        <div class="col-2">Lag</div>
                                    </div>
                                    <?php
                                    $task_list = TaskList::taskByStage($select_stage_id);
                                    $task_index = 0;
                                    $sub_total_day = 0;
                                    if(count($task_list)>0){
                                        foreach($task_list as $task_id => $task_name){
                                            $task_model = TaskList::model()->findByPk($task_id);
                                            $task_day = $task_model->plan_day;
                                            $sub_total_day = $sub_total_day+$task_day;
                                            $task_lag = $task_model->lag_day;
                                            $task_index++;
                                            ?>
                                            <?php
                                                if($task_index == 1){
                                            ?>
                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col-8"><?php echo $task_name; ?></div>
                                                        <div class="col-1"><input type="text"  name="task[plan_day][<?php echo $task_id; ?>]"  class="form-control input-sm" value="<?php echo $task_day; ?>"></div>
                                                        <div class="col-1"> days</div>
                                                    </div>
                                            <?php
                                                }else{
                                            ?>
                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col-8"><?php echo $task_name; ?></div>
                                                        <div class="col-1"><input type="text" name="task[plan_day][<?php echo $task_id; ?>]" class="form-control input-sm" value="<?php echo $task_day; ?>"></div>
                                                        <div class="col-1"> days</div>
                                                        <div class="col-1" style="margin-top: -20px;"><input type="text" name="task[lag_day][<?php echo $task_id; ?>]" class="form-control input-sm"  value="<?php echo $task_lag; ?>"></div>
                                                        <div class="col-1" style="margin-top: -20px;"> days</div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-8"></div>
                                        <div class="col-1"><input type="text" id="sub_total_day" class="form-control input-sm" value="<?php echo $sub_total_day; ?>" disabled></div>
                                        <div class="col-1"> days</div>
                                    </div>
                                </div>
                    <?php   }
                        }
                        ?>
                </div>
            </div>
        </div>
        <div class="row " style="margin-top: 10px;margin-bottom: 5px;">
            <div class="col-12" style="text-align: center">
                <button type="button" id="sbtn" class="btn btn-primary" onclick="save_sub()">Save</button>
<!--                <button type="button" class="btn btn-default" style="margin-left: 10px;" onclick="back();">Back</button>-->
            </div>
        </div>
    </div>
    <!-- /.card -->
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //返回
    var change_stage = function () {
        var template_id = $('#template_id').val();
        var stage_id = $('#stage_id').val();
        var project_id = $('#project_id').val();
        window.location = "index.php?r=task/schedule/subactivities&program_id=" + project_id+"&template_id="+template_id+"&stage_id=<?php echo $select_stage_id ?>";
    }


    //提交表单
    var save_sub = function () {

        //var params = $('#form1').serialize();
        //alert("index.php?r=proj/task/tnew&" + params);
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=task/schedule/savesub",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                //alert(data);
                if(data.status == '-1'){
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }else{
                    $('#msgbox').addClass('alert-success');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                    change_stage();
                }

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>