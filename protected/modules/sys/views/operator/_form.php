<?php
/* @var $this OperatorController */
/* @var $model Operator */
/* @var $form CActiveForm */

if ($msg) {

    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          <script type='text/javascript'>
          {$this->gridId}.refresh();
          </script>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
        ));
?>
<?php echo $form->activeHiddenField($model, 'operator_id', array(), ''); ?>
<div class="box-body">
    <div class="col-md-12">
        <h3 class="form-header text-blue"><?php echo Yii::t('sys_operator', 'Base Info'); ?></h3>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('name'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'name', array('id' => 'name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('sys_operator', 'Error operator_name is null'))); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('phone'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'phone', array('id' => 'phone', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('email'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'email', array('id' => 'email', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($_mode_ == 'insert') { ?>
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('sys_operator', 'Login Info'); ?></h3>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="operator_id" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('operator_id'); ?></label>
                    <div class="col-sm-6 padding-lr5">
                        <?php echo $form->activeTextField($model, 'operator_id', array('id' => 'operator_id', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('sys_operator', 'Error operator_id is null'))); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="pw1" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('sys_operator', 'Init Passwd'); ?></label>
                    <div class="col-sm-6 padding-lr5 help-block"><?php echo Operator::INITIAL_PASSWORD; ?></div>
                </div>
            </div>

        </div>
    <?php } ?>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['operator/list']; ?>";
    }
    var operator_type = '<?php echo $model->operator_type; ?>';
    switchType(operator_type);

    //切换显示承包商
    function switchType(type) {
        if (type == 'mcmanager') {
            $("#div_maincomp").show();
            $("#div_subcomp").hide();
        } else if (type == 'scmanager') {
            $("#div_subcomp").show();
            $("#div_maincomp").hide();
        } else {
            $("#div_maincomp").hide();
            $("#div_subcomp").hide();
        }
    }
</script>