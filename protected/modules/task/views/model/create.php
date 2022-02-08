    <?php
        if ($msg) {
            $class = Utils::getMessageType($msg ['status']);
            echo "<div class='alert {$class[0]} alert-dismissable'>
                      <i class='fa {$class[1]}'></i>
                      <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
                      <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
                  </div>
                  <script type='text/javascript'>
                  /*{$this->gridId}.refresh();*/
                  </script>
                  ";
        }
        $form = $this->beginWidget('SimpleForm', array(
            'id' => 'create_form',
            'enableAjaxSubmit' => false,
            'ajaxUpdateId' => 'content-body',
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
            'focus' => array(
                $model,
                'name'
            ),
            'role' => 'form', // 可省略
            'formClass' => 'form-horizontal', // 可省略 表单对齐样式
            'autoValidation' => true
        ));
    ?>
    <div class="box-body">

        <div class="row" style="margin-top: 10px;">
            <div class="form-group">
                <label for="role_name_en" class="col-sm-3 control-label padding-lr5">Modules Type Name</label>
                <div class="col-sm-3 padding-lr5">
                    <input type="hidden" class="form-control" name="Create[project_id]"  value="<?php echo $program_id; ?>" >
                    <input type="text" class="form-control" name="Create[modules_type_name]"   >
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 10px;">
            <div class="form-group">
                <label for="team_name" class="col-sm-3 control-label padding-lr5">Quantity</label>
                <div class="col-sm-3 padding-lr5">
                    <input type="text" class="form-control" name="Create[quantity]"   >
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 10px;">
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-10">
                    <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="saveinfo()"><?php echo Yii::t('common', 'button_save'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>
    <script type="text/javascript">
        //启用
        var saveinfo = function () {
            $.ajax({
                data:$('#create_form').serialize(),
                url: "index.php?r=task/model/savecreate",
                dataType: "json",
                type: "POST",
                success: function (data) {
                    alert(data.msg);
                    $("#modal-close").click();
                    itemQuery();
                }
            });
        }
    </script>

