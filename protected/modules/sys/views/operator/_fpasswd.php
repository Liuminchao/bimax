<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form3',
    'focus' => array($rs, 'old_pwd'),
    'autoValidation' => false,
    "action" => "javascript:formSubmit3()",
        ));

echo $form->activeHiddenField($rs, 'operator_id', array(), ''); 
echo $form->activeHiddenField($rs, 'operator_type', array(), '');
?>
<div class="box-body">
    <div class="form-group">
        <label for="operator_id" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('operator_id'); ?></label>
        <div style="padding-top: 6px">
        <?php
            echo $rs->operator_id;
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
        <label for="pw3" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('passwd'); ?></label>
        <?php
       echo '<div class="col-sm-6 padding-lr5"><input id="pw3" class="form-control" type="password" maxlength="32" name="Operator[passwd]" required-message="' . Yii::t('sys_operator', 'Error password is null') . '" check-type="required"></div>';
        ?>
    </div>
    <div class="form-group">
        <label for="pw4" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('passwd_c'); ?></label>
        <div class="col-sm-6 padding-lr5">
            <?php
            echo '<input id="pw4" class="form-control" type="password" maxlength="32" name="Operator[password_c]" required-message="' . Yii::t('sys_operator', 'Error password_ic is null') . '" check-type="required">';
            
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="reset" class="btn btn-default btn-lg" style="margin-left: 10px"><?php echo Yii::t('common', 'button_reset'); ?></button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $("#form3").validation(function (obj, params) {
            //alert(111111);
            if (obj.id == 'pw4' && $("#pw4").val() != $("#pw3").val()) {
                params.err = '<?php echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>';
                params.msg = "<?php echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>";
            }
        });
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
    var formSubmit3 = function () {
        var params = $('#form3').serialize();
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