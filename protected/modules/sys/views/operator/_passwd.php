<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form2',
    'focus' => array($model, 'old_pwd'),
    'autoValidation' => false,
    "action" => "javascript:formSubmit2()",
        ));

echo $form->activeHiddenField($model, 'operator_id', array(), '');
echo $form->activeHiddenField($model, 'operator_type', array(), '');
?>
<div class="container">
    <div class="form-group" style="margin-top: 10px;">
        <label for="id" class="col-sm-3 control-label label-rignt padding-lr5"><?php echo $model->getAttributeLabel('operator_id'); ?></label>
        <div style="padding-top: 6px">
        <?php
            echo $id;
        ?>
        </div>
    </div>
    <div class="form-group" style="margin-top: 10px;">
        <label for="old_pwd" class="col-sm-3 control-label label-rignt padding-lr5"><?php echo $model->getAttributeLabel('old_pwd'); ?></label>
        <?php
        echo '<div class="col-sm-6 padding-lr5"><input id="old_pwd" class="form-control" type="password" maxlength="32" name="Operator[old_pwd]" required-message="' . Yii::t('sys_operator', 'Error old password is null') . '" check-type="required"></div>';
        ?>
    </div>
    <div class="form-group" style="margin-top: 10px;">
        <label for="pw1" class="col-sm-3 control-label label-rignt padding-lr5"><?php echo $model->getAttributeLabel('passwd'); ?></label>
        <?php
        echo '<div class="col-sm-6 padding-lr5"><input id="pw1" class="form-control" type="password" maxlength="32" name="Operator[passwd]" required-message="' . Yii::t('sys_operator', 'Error password is null') . '" check-type="required"></div>';
        ?>
    </div>
    <div class="form-group" style="margin-top: 10px;">
        <label for="pw2" class="col-sm-3 control-label label-rignt padding-lr5"><?php echo $model->getAttributeLabel('passwd_c'); ?></label>
        <div class="col-sm-6 padding-lr5">
            <?php
            echo '<input id="pw2" class="form-control" type="password" maxlength="32" name="Operator[password_c]"  required-message="' . Yii::t('sys_operator', 'Error password_ic is null') . '" check-type="required">';
            ?>
        </div>
    </div>
    <div class="form-group" style="margin-top: 10px;text-align: center">
        <div class="col-12">
            <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="reset" class="btn btn-default btn-lg" style="margin-left: 10px"><?php echo Yii::t('common', 'button_reset'); ?></button>
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

    
    $("#pw2").blur(function () {    
        if ($("#pw2").val() != $("#pw1").val()) {
            $('#msgbox').addClass('alert-danger fa-ban');
            $('#msginfo').html('密码不一致');
            $('#msgbox').show();
        }
    });
    var n = 4;
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
    //提交表单
    var formSubmit2 = function () {
        var params = $('#form2').serialize();
        $.ajax({
            url: "index.php?r=sys/operator/pwd&" + params,
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass(data.class);
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>