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
    'id' => 'form2',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'focus' => array($model, 'old_pwd'),
    'role' => 'form', // 可省略
    'formClass' => 'form-horizontal', // 可省略 表单对齐样式
    'autoValidation' => false
));
echo $form->activeHiddenField($model, 'operator_id', array(), '');
echo $form->activeHiddenField($model, 'operator_type', array(), '');
?>
<div class="box-body">
    <div class="form-group">
        <div style="white-space:nowrap" class="col-sm-3"><h3 ><font color="#FF0000">
            <?php
            echo Yii::t('dboard', 'Tip Password');
            ?>
                </font></h3>
        </div>
    </div>
    <div class="form-group">
        <label for="id" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('operator_id'); ?></label>
        <div style="padding-top: 6px">
            <?php
            echo $id;
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="old_pwd" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('old_pwd'); ?></label>
        <?php
        echo '<div class="col-sm-6 padding-lr5"><input id="old_pwd" class="form-control" type="password" maxlength="32" name="Operator[old_pwd]" required-message="' . Yii::t('sys_operator', 'Error old password is null') . '" check-type="required"></div>';
        ?>
    </div>
    <div class="form-group">
        <label for="pw1" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('passwd'); ?></label>
        <?php
        echo '<div class="col-sm-6 padding-lr5"><input id="pw1" class="form-control" type="password" maxlength="32" name="Operator[passwd]" required-message="' . Yii::t('sys_operator', 'Error password is null') . '" check-type="required"></div>';
        ?>
    </div>
    <div class="form-group">
        <label for="pw2" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('passwd_c'); ?></label>
        <div class="col-sm-6 padding-lr5">
            <?php
            echo '<input id="pw2" class="form-control" type="password" maxlength="32" name="Operator[password_c]"  required-message="' . Yii::t('sys_operator', 'Error password_ic is null') . '" check-type="required">';
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default btn-lg"
                    style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_done'); ?></button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $("#form2").validation(function (obj, params) {

            if (obj.id == 'pw2' && $("#pw2").val() != $("#pw1").val()) {
                params.err = '<?php echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>';
                params.msg = "<?php echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>";
            }
        });
    });
    function checkEq() {
        alert('checkEq');
        alert($("#pw1").val());
        alert($("#pw2").val());
    }

    //返回
    var back = function () {
//        window.location = "./?<?php //echo Yii::app()->session['list_url']['staff/list']; ?>//";
        window.location = "index.php?r=dboard";
    }

    $("#pw2").blur(function () {
        if ($("#pw2").val() != $("#pw1").val()) {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('密码不一致');
            $('#msgbox').show();
        }
    });

</script>