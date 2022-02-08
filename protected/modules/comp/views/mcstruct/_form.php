<?php
/* @var $this ContractorStructController */
/* @var $model ContractorStruct */
/* @var $form CActiveForm */
if ($msg) {
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}<span id='divMain'></span>
          </div>  
           <script type='text/javascript'>showTime({$msg['refresh']});{$this->gridId}.refresh();</script>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => true,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'team_id'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
        ));
?>
<div class="box-body">
   
    <div class="row">
        <div class="form-group">
            <label for="team_id" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('team_id'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'team_id', array('id' => 'team_id', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="team_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('team_name'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'team_name', array('id' => 'team_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="link_people" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('link_people'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'link_people', array('id' => 'link_people', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="link_phone" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('link_phone'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'link_phone', array('id' => 'link_phone', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
            </div>
        </div>
    </div>
     <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <button type="reset" class="btn btn-default btn-lg" style="margin-left: 10px"><?php echo Yii::t('common', 'button_reset'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //关闭
    var modal_close = function () {
        $("#modal-close").click();
    }
    var n = 4;
    function showTime(flag) {
        if (flag == false)
            return;
        n--;
        $('#divMain').html(n + " <?php echo Yii::t('common', 'tip_close'); ?>");
        if (n == 0)
            $("#modal-close").click();
        else
            setTimeout('showTime()', 1000);
    }
</script>