<?php
/* @var $this RoleController */
/* @var $model Role */
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
    'focus' => array($model, 'name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
        ));
?>
<div class="box-body">
    <div class="col-md-6">
        <div class="form-group">
            <label for="role_id" class="col-sm-4 control-label padding-lr5"><?php echo $model->getAttributeLabel('role_id'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php
                if ($_mode_ == 'insert') {
                    echo $form->activeTextField($model, 'role_id', array('id' => 'role_id', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('sys_role','role_id is not null')));
                } else {
                    echo '<label class="control-label">' . $model->role_id . '</label>';
                    echo $form->activeHiddenField($model, 'role_id');
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="role_name" class="col-sm-4 control-label padding-lr5"><?php echo $model->getAttributeLabel('role_name'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'role_name', array('id' => 'role_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('sys_role','role_name is not null'))); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="role_name_en" class="col-sm-4 control-label padding-lr5"><?php echo $model->getAttributeLabel('role_name_en'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'role_name_en', array('id' => 'role_name_en', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('sys_role','error_role_name_en_is_null'))); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="team_name" class="col-sm-4 control-label padding-lr5"><?php echo $model->getAttributeLabel('team_name'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'team_name', array('id' => 'team_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('sys_role','error_team_name_is_null'))); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="team_name_en" class="col-sm-4 control-label padding-lr5"><?php echo $model->getAttributeLabel('team_name_en'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'team_name_en', array('id' => 'team_name_en', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('sys_role','error_team_name_en_is_null'))); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="sort_id" class="col-sm-4 control-label padding-lr5"><?php echo $model->getAttributeLabel('sort_id'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'sort_id', array('id' => 'sort_id', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('sys_role','error_sort_id_is_null'))); ?>
            </div>
        </div>
    </div>
<!--    <div class="col-md-6">
        <div class="form-group">
            <label for="status" class="col-sm-4 control-label padding-lr5"><?php echo $model->getAttributeLabel('status'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php
                $status_list = Role::statusText();
                echo $form->activeDropDownList($model, 'status', $status_list, array('class' => 'form-control', 'check-type' => 'required', 'placeholder' => '', 'required-message' => ''));
                ?>
            </div>
        </div>
    </div>-->

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="reset" class="btn btn-default btn-lg" style="margin-left: 10px"><?php echo Yii::t('common', 'button_reset'); ?></button>
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
        $('#divMain').html(n + " <?php echo Yii::t('common', 'tip_close'); ?>");
        if (n == 0)
            $("#modal-close").click();
        else
            setTimeout('showTime()', 1000);
    }
</script>