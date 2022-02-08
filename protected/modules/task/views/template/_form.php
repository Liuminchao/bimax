<?php
/* @var $this PtwTypeController */
/* @var $model PtwType */
/* @var $form CActiveForm */
if ($msg) {
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>提示：</b>{$msg['msg']}
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
    'focus' => array($model, 'id'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
        ));
?>
<?php echo $form->activeHiddenField($model, 'type_id', array(), ''); ?>
<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('sys_operator', 'Base Info'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="type_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('type_name'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'type_name', array('id' => 'type_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="type_name_en" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('type_name_en'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'type_name_en', array('id' => 'type_name_en', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>

            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['type/list']; ?>";
    }
</script>