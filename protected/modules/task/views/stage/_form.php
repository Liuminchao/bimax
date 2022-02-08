<?php
/* @var $this PtwConditionController */
/* @var $model PtwCondition */
/* @var $form CActiveForm */
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'id'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
    "action" => "javascript:formSubmit1()",
        ));
?>
<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div>
        <input type="hidden" name="PtwCondition[condition_id]" value="<?php echo $condition_id ?>">
        <input type="hidden" name="PtwCondition[type_id]" value="<?php echo $type_id ?>">
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="condition_name" class="col-sm-3 control-label padding-lr5">Condition Name</label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'condition_name', array('id' => 'condition_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="condition_name_en" class="col-sm-3 control-label padding-lr5">Condition Name En</label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'condition_name_en', array('id' => 'condition_name_en', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    <?php  if($_mode_ == 'new'){  ?>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <button type="button"  id="sbtn" class="btn btn-primary btn-lg" onclick="newSubmit();"><?php echo Yii::t('common','save') ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common','back') ?></button>
            </div>
        </div>
    <?php }else{ ?>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <button type="submit" id="sbtn" class="btn btn-primary btn-lg" onclick="editSubmit();"><?php echo Yii::t('common','save') ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common','back') ?></button>
            </div>
        </div>
    <?php } ?>

</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "index.php?r=license/condition/list&id=<?php echo $type_id ?>";
    }
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
    //添加表单
    var newSubmit = function () {
        var params = $('#form1').serialize();
        $.ajax({
            url: "index.php?r=license/condition/newcondition&" + params,
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
                //window.location = "index.php?ctc/handover/recordlist&apply_id=<?php //echo $apply_id ?>//";
                location.reload();
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //编辑表单
    var editSubmit = function () {
        var params = $('#form1').serialize();
        $.ajax({
            url: "index.php?r=license/condition/editcondition&" + params,
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
                //window.location = "index.php?ctc/handover/recordlist&apply_id=<?php //echo $apply_id ?>//";
                location.reload();
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>
