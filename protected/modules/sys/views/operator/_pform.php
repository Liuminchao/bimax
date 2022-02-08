 <?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($model, 'name'),
    'autoValidation' => true,
    "action" => "javascript:formSubmit1()",
        ));
echo $form->activeHiddenField($model, 'operator_id', array(), ''); 
echo $form->activeHiddenField($model, 'operator_type', array(), ''); 
echo $form->activeHiddenField($model, 'role_id', array(), ''); 
?>
<div class="container">
    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-sm-3 control-label label-rignt padding-lr5"><?php echo Yii::t('comp_contractor', 'Contractor_name'); ?></label>
        <div class="col-sm-6 padding-lr5 " style="padding-top: 7px;">
            <?php $coninfo = Yii::app()->user->getState('contractor_info');
            echo $coninfo['contractor_name']; ?>
        </div>
    </div>
    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-sm-3 control-label label-rignt padding-lr5"><?php echo $model->getAttributeLabel('name'); ?></label>
        <div class="col-sm-5 padding-lr5">
            <?php echo $form->activeTextField($model, 'name', array('id' => 'name','readonly' => true, 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('sys_operator', 'Error operator_name is null'))); ?>
        </div>
    </div>
    <div class="form-group" style="margin-top: 10px;">
        <label for="phone" class="col-sm-3 control-label label-rignt padding-lr5"><?php echo $model->getAttributeLabel('phone'); ?></label>
        <div class="col-sm-5 padding-lr5">
            <?php echo $form->activeTextField($model, 'phone', array('id' => 'phone','readonly' => true, 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
        </div>
    </div>
    <div class="form-group" style="margin-top: 10px;">
        <label for="email" class="col-sm-3 control-label label-rignt padding-lr5"><?php echo $model->getAttributeLabel('email'); ?></label>
        <div class="col-sm-5 padding-lr5">
            <?php echo $form->activeTextField($model, 'email', array('id' => 'email','readonly' => true, 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <div class="col-sm-offset-4 col-sm-10">
<!--            <button type="submit" id="sbtn" class="btn btn-primary btn-lg">--><?php //echo Yii::t('common', 'button_save'); ?><!--</button>-->
<!--            <button type="reset" class="btn btn-default btn-lg" style="margin-left: 10px">--><?php //echo Yii::t('common', 'button_reset'); ?><!--</button>-->
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">

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
    var formSubmit1 = function () {
        var params = $('#form1').serialize();
        $.ajax({
            url: "index.php?r=sys/operator/pupdate&" + params,
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
                window.location = "index.php?r=dboard";
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>