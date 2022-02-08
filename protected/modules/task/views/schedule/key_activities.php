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
<div class="row">
    <div class="col-12">
        <div class="card card-info ">
            <div class="card-body" style="overflow-x: auto">
                <div class="row">
                    <div class="col-2">
                        <input type="hidden" id="project_id" name="key[project_id]" value="<?php echo $project_id; ?>">
                        <select class="form-control input-sm" name="key[template_id]" id="template_id" style="width: 100%;"  onchange="change_stage()">
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
                <?php
                $stage_list = TaskStage::queryStage($template_id);
                $total_day = 0;
                $stage_count = count($stage_list);
                $cnt = 0;
                if(count($stage_list)>0){
                    foreach($stage_list as $stage_id => $stage_name){
                        $stage_model = TaskStage::model()->findByPk($stage_id);
                        $stage_day = $stage_model->plan_day;
                        $total_day+=$stage_day;
                        $stage_color = $stage_model->stage_color;
                        $cnt++;
                        ?>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-8"><input type="text" class="form-control input-sm" value="<?php echo $stage_name; ?>" style="background-color: <?php echo $stage_color ?>;text-align: center" disabled></div>
                            <div class="col-1"><input type="text" class="form-control input-sm" name="key[stage][<?php echo $stage_id; ?>]" value="<?php echo $stage_day; ?>" onchange="change_total('<?php echo $stage_day; ?>')"></div>
                            <div class="col-1"> days</div>
                        </div>
                        <?php
                        if($cnt < $stage_count){
                            ?>
                            <div class="row">
                                <div class="col-8" style="text-align: center"><img src="img/arrow_down2.png" alt="next"></div>
                            </div>
                            <?php
                        }
                        ?>
                    <?php
                    }
                }
                ?>

                <div class="row" style="margin-top: 50px;">
                    <div class="col-8"></div>
                    <div class="col-1"><input id="total_day" type="text" class="form-control input-sm"  value="<?php echo $total_day; ?>" disabled></div>
                    <div class="col-1"> days</div>
                </div>

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
                            $begin_day = 0;
                            if(count($stage_list)>0){
                                foreach($stage_list as $stage_id => $stage_name){
                                    $stage_model = TaskStage::model()->findByPk($stage_id);
                                    $stage_color = $stage_model->stage_color;
                                    $stage_day = $stage_model->plan_day;
                        ?>
                                    <tr>
                                        <td><?php echo $stage_name; ?></td>
                                        <?php
                                            $j = 1;
                                            for($i=1;$i<=$total_day;$i++){
                                                if($i > $begin_day && $j <= $stage_day){
                                                    $begin_day++;
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
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row " style="margin-top: 10px;margin-bottom: 5px;">
    <div class="col-12" style="text-align: center">
        <button type="button" id="sbtn" class="btn btn-primary" onclick="save_key()">Save</button>
<!--        <button type="button" class="btn btn-default" style="margin-left: 10px;" onclick="back();">Back</button>-->
    </div>
</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
    //返回
    var change_stage = function () {
        var template_id = $('#template_id').val();
        var project_id = $('#project_id').val();
        window.location = "index.php?r=task/schedule/keyactivities&program_id=" + project_id+"&template_id="+template_id;
    }

    var change_total = function (day) {
        var day = parseInt(day);
    }

    //提交表单
    var save_key = function () {

        //var params = $('#form1').serialize();
        //alert("index.php?r=proj/task/tnew&" + params);
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=task/schedule/savekey",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                //alert(data);
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                change_stage();
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
