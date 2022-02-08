<?php
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
    'autoValidation' => false,
        ));
?>
<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_contractor', 'Base Info'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="contractor_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('contractor_name'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'contractor_name', array('id' => 'contractor_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_contractor', 'Error contractor_name is null'))); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="company_adr" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('company_adr'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'company_adr', array('id' => 'company_adr', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_contractor', 'Error Address is null'))); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="link_tel" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('link_person'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'link_person', array('id' => 'link_person', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="link_phone" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('link_phone'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'link_phone', array('id' => 'link_phone', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
            </div>
        </div>
    </div>

    <?php if ($_mode_ == 'insert') { ?>
        <div class="row">
            <div class="col-md-12">
                <h3 class="form-header text-blue"><?php echo Yii::t('comp_contractor', 'Login Info'); ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label for="login_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('operator_name'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'operator_name', array('id' => 'operator_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('sys_operator', 'Error operator_name is null'))); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="login_phone" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('operator_phone'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'operator_phone', array('id' => 'operator_phone', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
                </div>
            </div>
        </div>
    <?php } ?>
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
        window.location = "./?<?php echo Yii::app()->session['list_url']['maincomp/list']; ?>";
        // window.location = "index.php?r=comp/maincomp/list";
    }
</script>