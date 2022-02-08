<?php
if ($msg) {
    $class = Utils::getMessageType($msg ['status']);
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
    'focus' => array(
        $model,
        'name'
    ),
    'role' => 'form', // 可省略
    'formClass' => 'form-horizontal', // 可省略 表单对齐样式
    'autoValidation' => false
        ));
?>
<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_user', 'Base Info'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="worker_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('worker_name'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'worker_name', array('id' => 'worker_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_worker','Error Worker_name is null'))); ?>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="form-group">
            <label for="wp_no" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('wp_no'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'wp_no', array('id' => 'wp_no', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_worker','Error wp_no is null'))); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="worker_phone" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('worker_phone'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'worker_phone', array('id' => 'worker_phone', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_worker','Error phone is null'))); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="primary_email"
                   class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('primary_email'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'primary_email', array('id' => 'primary_email', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <button type="button" class="btn btn-default btn-lg"
                        style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>


    <script type="text/javascript">
        jQuery(document).ready(function () {
            $("#form1").validation(function (obj, params) {
                if (obj.id == 'pw2' && $("#pw2").val() != $("#pw1").val()) {
                    params.err = '<?php echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>';
                    params.msg = "<?php echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>";
                }
            });
        });
        //返回
        var back = function () {
            window.location = "./?<?php echo Yii::app()->session['list_url']['workersubcomp/list']; ?>";
            //window.location = "index.php?r=comp/workersubcomp/list";
        }
    </script>