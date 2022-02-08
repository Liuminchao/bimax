<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>
<?php
    $task_model = TaskList::model()->findByPk($task_id);
?>
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" style="overflow-x: auto">
                <form id="form1" >
                    <div >
                        <input type="hidden" id="task_id"  name="task[task_id]"  value="<?php echo $task_id ?>"/>
                        <input type="hidden" id="project_id"  name="task[project_id]"  value="<?php echo $program_id ?>"/>
                        <input type="hidden" id="template_id"  name="task[template_id]"  value="<?php echo $template_id ?>"/>
                        <input type="hidden" id="stage_id"  name="task[stage_id]"  value="<?php echo "$stage_id" ?>"/>
                    </div>

                    <div class="form-group group-space-between">
                    
                        <label for="task_name" class="col-2 control-label offset-md-1  label-rignt">Task Name</label>
                        <div class="input-group col-8">
                            <div class="col-9 padding-lr5">
                            <?php if($task_id == ''){    ?>
                                    <input id="template_name" class="form-control" name="task[task_name]" type="text" value="">
                                <?php }else{ ?>
                                    <input id="template_name" class="form-control" name="task[task_name]" type="text" value="<?php echo $task_model->task_name; ?>">
                                <?php } ?>
                                <input id="program_id" name="task[program_id]"  type="hidden" value="<?php echo $program_id; ?>">
                            </div>
                        </div>
                    </div>

                    <?php if($task_id == ''){    ?>
                        <!--        <div class="row" style="margin-left: 10%;margin-top: 10px;">-->
                        <!--            <div class="form-group group-space-between">-->
                        <!--                <label for="template_name" class="col-2 control-label offset-md-1  label-rignt">Template Name</label>-->
                        <!--                <div class="input-group col-8">
                                <div class="col-9 padding-lr5">-->
                        <!--                    <select class="form-control input-sm" name="task[template_id]" id="template_id" style="width: 100%;">-->
                        <!--                        <option value="">--Template Name--</option>-->
                        <!--                        --><?php
//                        $template_list = TaskTemplate::templateByProgram($program_id);
//                        foreach ($template_list as $template_id => $template_name) {
//                            echo "<option value='{$template_id}'>{$template_name}</option>";
//                        }
//                        ?>
                        <!--                    </select>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!---->
                        <!--        <div class="row" style="margin-left: 10%;margin-top: 10px;">-->
                        <!--            <div class="form-group group-space-between">-->
                        <!--                <label for="template_name" class="col-2 control-label offset-md-1  label-rignt">Stage Name</label>-->
                        <!--                <div class="input-group col-8">
                                <div class="col-9 padding-lr5">-->
                        <!--                    <select class="form-control input-sm" id="stage_id" name="task[stage_id]" style="width: 100%;">-->
                        <!--                        <option value="">--Stage Name--</option>-->
                        <!--                    </select>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1  label-rignt">Trade</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <select class="form-control input-sm" name="q[type_id]" id="type_id" style="width: 100%;">
                                        <option value="">--<?php echo Yii::t('comp_qa', 'check_type'); ?>--</option>
                                        <?php
                                        $type_list = QaCheckType::typeByContractor($program_id);
                                        $form_type_list = QaCheckType::getFormType();
                                        $form_type = '';
                                        foreach ($type_list as $k => $type) {
                                            $model = QaCheckType::model()->findByPk($k);
                                            if($form_type != $model->form_type){
                                                $form_type = $model->form_type;
                                                echo "<optgroup label='{$form_type_list[$model->form_type]}'>";
                                            }
                                            echo "<option value='{$k}'>{$type}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1  label-rignt">Checklist Type</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <select class="form-control input-sm" id="form_id" name="q[form_id]" style="width: 100%;" onchange="show_sub(this.options[this.options.selectedIndex])">
                                        <option value="">--<?php echo Yii::t('comp_qa', 'form_type'); ?>--</option>
                                    </select>
                                    <input id="checklist_cnt" type="hidden"  class="form-control"  value="0">
                                    <input id="checklist_str" type="hidden"  class="form-control" value="">
                                </div>
                            </div>
                        </div>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                            <label for="template_name" class="col-2 control-label offset-md-1  label-rignt"></label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <table id="table"  >
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <!--        <div class="row" style="margin-left: 10%;margin-top: 10px;">-->
                        <!--            <div class="form-group group-space-between">-->
                        <!--                <label for="template_name" class="col-2 control-label offset-md-1  label-rignt">Template Name</label>-->
                        <!--                <div class="input-group col-8">
                                <div class="col-9 padding-lr5">-->
                        <!--                    <select class="form-control input-sm" name="task[template_id]" id="template_id" style="width: 100%;">-->
                        <!--                        <option value="">--Template Name--</option>-->
                        <!--                        --><?php
//                        $template_list = TaskTemplate::templateByProgram($program_id);
//                        foreach ($template_list as $template_id => $template_name) {
//                            if($task_model->template_id == $template_id){
//                                echo "<option value='{$template_id}' selected>{$template_name}</option>";
//                            }else{
//                                echo "<option value='{$template_id}'>{$template_name}</option>";
//                            }
//                        }
//                        ?>
                        <!--                    </select>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!---->
                        <!--        <div class="row" style="margin-left: 10%;margin-top: 10px;">-->
                        <!--            <div class="form-group group-space-between">-->
                        <!--                <label for="template_name" class="col-2 control-label offset-md-1  label-rignt">Stage Name</label>-->
                        <!--                <div class="input-group col-8">
                                <div class="col-9 padding-lr5">-->
                        <!--                    <select class="form-control input-sm" id="stage_id" name="task[stage_id]" style="width: 100%;">-->
                        <!--                        <option value="">--Stage Name--</option>-->
                        <!--                        --><?php
//                        $stage_list = TaskStage::stageByProgram($program_id);
//                        foreach ($stage_list as $stage_id => $stage_name) {
//                            if($task_model->stage_id == $stage_id){
//                                echo "<option value='{$stage_id}' selected>{$stage_name}</option>";
//                            }else{
//                                echo "<option value='{$stage_id}'>{$stage_name}</option>";
//                            }
//                        }
//                        ?>
                        <!---->
                        <!--                    </select>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1  label-rignt">Trade</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <select class="form-control input-sm" name="q[type_id]" id="type_id" style="width: 100%;">
                                        <option value="">--<?php echo Yii::t('comp_qa', 'check_type'); ?>--</option>
                                        <?php
                                        $type_list = QaCheckType::typeByContractor($program_id);
                                        $form_type_list = QaCheckType::getFormType();
                                        $form_type = '';
                                        foreach ($type_list as $k => $type) {
                                            $model = QaCheckType::model()->findByPk($k);
                                            if($form_type != $model->form_type){
                                                $form_type = $model->form_type;
                                                echo "<optgroup label='{$form_type_list[$model->form_type]}'>";
                                            }
                                            echo "<option value='{$k}'>{$type}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php
                        $checklist_cnt = 0;
                        $checklist_str = '';
                        $checklist_id = $task_model->checklist_id;
                        $checklist = explode(",",$checklist_id);
                        if(!empty($checklist)) {
                            foreach ($checklist as $i => $j) {
                                $checklist_cnt++;
                                $checklist_str.=$j.',';
                            }
                        }
                        $checklist_str = substr($checklist_str, 0, strlen($checklist_str) - 1);
                        ?>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1  label-rignt">Checklist Type</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <select class="form-control input-sm" id="form_id" name="q[form_id]" style="width: 100%;" onchange="show_sub(this.options[this.options.selectedIndex])">
                                        <option value="">--<?php echo Yii::t('comp_qa', 'form_type'); ?>--</option>
                                    </select>
                                    <input id="checklist_cnt" type="hidden"  class="form-control"  value="<?php echo $checklist_cnt ?>">
                                    <input id="checklist_str" type="hidden"  class="form-control" value="<?php echo $checklist_str ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                            <label for="template_name" class="col-2 control-label offset-md-1  label-rignt"></label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <table  id="table"  >
                                        <?php
                                        $checklist_cnt = 0;
                                        $checklist_str = '';
                                        $checklist_id = $task_model->checklist_id;
                                        if($checklist_id != ''){
                                            $checklist = explode(",",$checklist_id);
                                            if(!empty($checklist)) {
                                                foreach ($checklist as $i => $j) {
                                                    $qa_model = QaChecklist::model()->findByPk($j);
                                                    $form_name = $qa_model->form_name;
                                                    $form_id = $qa_model->form_id;
                                                    ?>
                                                    <tr><td  align='left' ><input type='hidden' name='task[checklist][]'  value='<?php echo $form_id; ?>' ><?php echo $form_name; ?></td><td onclick='del_tr(this)' ><img src='img/delete_rf.png' ></td></tr>
                                                    <?php
                                                }
                                            }
                                        }

                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </form>

                <div class="row button-space-between">
                    <div class="col-12" style="text-align: center">
                        <button type="button" class="btn btn-primary" style="margin-left: 10px" onclick="btnsubmit()">Save</button>
                        <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back('<?php echo $program_id ?>','<?php echo $template_id ?>','<?php echo $stage_id ?>')">Back</button>
                    </div>
                </div>
                <!-- <div class="form-group group-space-between" style="margin-top: 10px;text-align: center">
                    <div class="col-sm-10">
                        <button type="button" class="btn btn-primary" style="margin-left: 10px" onclick="btnsubmit()">Save</button>
                        <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back('<?php //echo $program_id ?>','<?php //echo $template_id ?>','<?php //echo $stage_id ?>')">Back</button>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>


<script type="text/JavaScript">
    //form初始化
    function FormInit(node) {
        return node.html('<option value="">--<?php echo Yii::t('comp_qa', 'form_type'); ?>--</option>');
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

    //类型和表单类型半联动
    $('#type_id').change(function(){

        var formObj = $("#form_id");
        var formOpt = $("#form_id option");
        FormInit(formObj);

        $.ajax({
            type: "POST",
            url: "index.php?r=qa/qainspection/queryform",
            data: {type_id:$("#type_id").val(),program_id:'<?php echo $program_id ?>'},
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

    //返回
    var back = function (program_id,template_id,stage_id) {
        window.location = "index.php?r=task/task/list&template_id="+template_id+"&stage_id="+stage_id+"&project_id="+program_id;
    }

    function show_sub(v){
        var checklist_cnt = $('#checklist_cnt').val();
        var checklist_str = $('#checklist_str').val();
        var form = document.getElementById("table");//获取对应
        if(checklist_str.indexOf(v.value) == -1 && v.value != '0'){
            checklist_cnt++;
            checklist_str = checklist_str + v.value + ',';
//            cc_str = cc_str.substr(0, cc_str.length - 1);//去掉末尾的逗号
            $('#checklist_cnt').val(checklist_cnt);
            $('#checklist_str').val(checklist_str);
            $(form).append("<tr><td  align='left' ><input type='hidden' name='task[checklist][]'  value='"+v.value+"' >"+v.text+"</td><td onclick='del_tr(this)' ><img src='img/delete_rf.png' ></td></tr>");
        }
    }

    // 删除一行
    function del_tr(obj){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标 未使用
        $(obj).parents("tr").remove(); //实现删除tr
    }

    //提交表单
    var btnsubmit = function () {
        var task_id = $("#task_id").val();

        if(task_id == '') {
            insertsubmit();
        }else{
            updatesubmit();
        }
    }

    var n = 4;
    //定时关闭弹窗
    function showTime(flag) {
        if (flag == false)
            return;
        n--;
        $('#divMain').html(n + ' <?php echo Yii::t('common', 'tip_close'); ?>');
        if (n == 0)
            $("#modal-close").click();
        else
            setTimeout('showTime()', 1000);
    }
    //添加表单其他元素
    function insertsubmit() {
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=task/task/insert",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == 1) {
                    $('#msgbox').addClass('alert-success fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                    back('<?php echo $program_id; ?>','<?php echo $template_id ?>','<?php echo $stage_id ?>');
                }else{
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //编辑表单其他元素
    function updatesubmit() {

        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=task/task/update",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == 1) {
                    $('#msgbox').addClass('alert-success fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }else{
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>