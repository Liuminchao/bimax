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
            <table class="table">
                <tr>
                    <th style="text-align:center">Name</th>
                    <th style="text-align:center">View (Web)</th>
                    <th style="text-align:center">View & Edit (Web)</th>
                    <th style="text-align:center">Notification (App)</th>
                </tr>
                <?php
                    $contractor_id = Yii::app()->user->getState('contractor_id');
                    $user_list = ProgramUser::UserListByMcProgram($contractor_id,$project_id);
                    foreach($user_list as $user_id => $user_name){
                        echo "<tr>";
                        echo "<td align='center'>$user_name</td>";
                        echo "<td align='center'><input type=\"checkbox\" name=\"Person[$user_id][web_view]\"></td>";
                        echo "<td align='center'><input type=\"checkbox\" name=\"Person[$user_id][web_edit]\"></td>";
                        echo "<td align='center'><input type=\"checkbox\" name=\"Person[$user_id][app]\"></td>";
                        echo "</tr>";
                    }
                ?>
            </table>
        </div>
        <div class="row " style="margin-top: 10px;margin-bottom: 5px;">
            <div class="col-12" style="text-align: center">
                <button type="button" id="sbtn" class="btn btn-primary" onclick="save_person()">Save</button>
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
        var save_person = function () {

            //var params = $('#form1').serialize();
            //alert("index.php?r=proj/task/tnew&" + params);
            $.ajax({
                data:$('#form1').serialize(),
                url: "index.php?r=task/schedule/savepersonincharge",
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