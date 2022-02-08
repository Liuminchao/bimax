<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form2',
    'enableAjaxSubmit' => true,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'name'),
    'autoValidation' => false,
        ));
?>
<div class="box-body">
    <div class="row">
        <div class="form-group">
            <label class="col-sm-3 control-label padding-lr5" for="flow_name"><?php echo Yii::t('sys_workflow', 'step_type'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php
                $type = WorkflowDetail::typeText();
                if (Yii::app()->user->getState('operator_type') == Operator::TYPE_SYSTEM) {
                    unset($type[1]);
                }
                foreach ($type as $k => $v) {
                    echo "<label class=\"radio-inline\"><input type='radio' name='step_type' id='step_type_{$k}' value='{$k}' onclick='changeType(this.value)'";
                    if ($k == 0)
                        echo "checked";
                    echo "> {$v}</label>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div id="div_role" class="row">
    <div class="form-group">
        <label class="col-sm-3 control-label padding-lr5" for="role_id"><?php echo Yii::t('sys_workflow', 'role'); ?></label>
        <div class="col-sm-6 padding-lr5">
            <?php
            $role_list = Role::roleList();
            $role_list = array('' => '-' . Yii::t('sys_workflow', 'select_role') . '-') + $role_list;
            echo "<select id='role_id' class='form-control'>";
            if (!empty($role_list)) {
                foreach ($role_list as $id => $name) {
                    echo "<option value='" . $id . "'>" . $name . "</option>";
                }
            }
            echo "</select>"
            ?>       
        </div>
    </div>
</div>
<div id="div_people" class="row" style="display: none;">
    <div class="form-group">
        <label class="col-sm-3 control-label padding-lr5" for="people_id"><?php echo Yii::t('sys_workflow', 'people'); ?></label>
        <div class="col-sm-6 padding-lr5">
            <!-- 人员选择 -->
            <?php
            $userList = User::userList(Yii::app()->user->getState('contractor_id'));
            if (!empty($userList)) {
                foreach ($userList as $uid => $uname) {
                    echo '<label class=""><input type="checkbox" name="people" value="' . $uid . '"> ' . $uname . ' </label>&nbsp;';
                }
            } else {
                echo '<div class="col-sm-6 padding-lr5 help-block">' . Yii::t('sys_workflow', 'error_people_is_null') . '</div>';
            }
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-12">
            <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="formSubmit();"><?php echo Yii::t('common', 'button_ok'); ?></button>
            <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="modal_close();"><?php echo Yii::t('common', 'button_close'); ?></button>
        </div>
    </div>
</div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //关闭
    var modal_close = function () {
        $("#modal-close").click();
    }
    //添加节点
    var AddStep = function () {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('sys_workflow', 'Add Step'); ?>";
        modal.url = "index.php?r=sys/workflow/step";
        modal.modal();
    }
    //选择节点类型
    function changeType(type) {
        //角色
        if (type == 0) {
            $("#div_role").show();
            $("#div_people").hide();
        }
        //人员
        else if (type == 1) {
            $("#div_people").show();
            $("#div_role").hide();
        }
        $("#type").val(type);
    }
    //根据公司显示人员
    function changeComp(contractor_id) {

        $.ajax({
            type: "post",
            url: "./?r=sys/workflow/userlist",
            data: "&confirm=1&contractor_id=" + contractor_id,
            datatype: 'json',
            timeout: 5000,
            error: function () {
                alert('<?php echo Yii::t('common', 'Error load'); ?>');
            },
            success: function (m) {

                var dataobj = eval("(" + m + ")");
                $("#people_list").empty();
                if (dataobj == null) {
                    $("#people_list").append('<label class=""><?php echo Yii::t('sys_workflow', 'error_people_is_null'); ?></label>');
                    return;
                }
                $.each(dataobj, function (id, name) {
                    //回调函数有两个参数,第一个是元素索引,第二个为当前值
                    $("#people_list").append('<label class=""><input type="checkbox" name="people" value="' + id + '"> ' + name + ' </label>&nbsp;');
                });
            }
        });

    }

    //提交表单
    function formSubmit() {

        var obj_id, obj_name;

        var T = $("input[name='step_type']:checked").val();
        if (T == 0) {
            obj_id = $("#role_id").val();
            obj_name = $("#role_id").find("option:selected").text();
        }
        else if (T == 1) {
            var ids = "";
            $('input[name="people"]:checked').each(function () {
                ids += $(this).val() + ',';
            });//得到选中复选框的value  
            var texts = "";
            $('input[name="people"]:checked').each(function () {
                texts += $(this).parent().text() + ',';
            });//得到选中复选框的text  
            //去掉最后一个“，”号  
            obj_id = ids.substring(0, ids.lastIndexOf(","));
            obj_name = texts.substring(0, texts.lastIndexOf(","));
        }
        if (obj_id == '' || obj_name == '') {
            alert('<?php echo Yii::t('sys_workflow', 'error_step_is_null'); ?>');
            return;
        }

        var step_cnt = $("#step_cnt").val();

        var html = '<div id="div_step_' + (++step_cnt) + '" class="row"><div class="form-group">';
        html += '<label class="col-sm-3 control-label padding-lr5" for="flow_name"><?php echo Yii::t('sys_workflow', 'step'); ?></label>';
        html += '<div class="col-sm-6 padding-lr5"><input class="form-control" type="text" name="Workflow[object_name][]" readonly value="' + obj_name + '" title="' + obj_name + '"></div>';
        html += '<div class="col-sm-3"><a onclick="DelStep(' + step_cnt + ')"><?php echo Yii::t('sys_workflow', 'Delete Step'); ?></a></div>';
        html += '<input type="hidden" name="Workflow[type][]" value="' + T + '"/><input type="hidden" name="Workflow[object_id][]" value="' + obj_id + '"/></div></div>';
        $("#step_cnt").val(step_cnt);
        $("#div_step_list").append(html);
        $("#modal-close").click();

    }
</script>