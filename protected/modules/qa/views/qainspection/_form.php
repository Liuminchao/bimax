<?php
/* @var $this ApplyBasicLogController */
/* @var $model ApplyBasicLog */
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
<div class="box-body">
    <div class="col-md-12">
        <h3 class="form-header text-blue">基本信息</h3>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="apply_id" class="col-sm-3 control-label padding-lr5">Apply</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'apply_id', array('id' => 'apply_id', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="approve_id" class="col-sm-3 control-label padding-lr5">Approve</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'approve_id', array('id' => 'approve_id', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="program_id" class="col-sm-3 control-label padding-lr5">Program</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'program_id', array('id' => 'program_id', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="program_name" class="col-sm-3 control-label padding-lr5">Program Name</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'program_name', array('id' => 'program_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="apply_date" class="col-sm-3 control-label padding-lr5">Apply Date</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'apply_date', array('id' => 'apply_date', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="contractor_id" class="col-sm-3 control-label padding-lr5">Contractor</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'contractor_id', array('id' => 'contractor_id', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="contractor_name" class="col-sm-3 control-label padding-lr5">Contractor Name</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'contractor_name', array('id' => 'contractor_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="from_time" class="col-sm-3 control-label padding-lr5">From Time</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'from_time', array('id' => 'from_time', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="to_time" class="col-sm-3 control-label padding-lr5">To Time</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'to_time', array('id' => 'to_time', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="start_time" class="col-sm-3 control-label padding-lr5">Start Time</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'start_time', array('id' => 'start_time', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="end_time" class="col-sm-3 control-label padding-lr5">End Time</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'end_time', array('id' => 'end_time', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="condition_set" class="col-sm-3 control-label padding-lr5">Condition Set</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'condition_set', array('id' => 'condition_set', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="status" class="col-sm-3 control-label padding-lr5">Status</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'status', array('id' => 'status', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="record_time" class="col-sm-3 control-label padding-lr5">Record Time</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'record_time', array('id' => 'record_time', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="work_content" class="col-sm-3 control-label padding-lr5">Work Content</label>
        <div class="col-sm-6 padding-lr5">
            <?php echo $form->activeTextField($model, 'work_content', array('id' => 'work_content', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '')); ?>
        </div>
    </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="submit" id="sbtn" class="btn btn-primary btn-lg">保存</button>
            <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();">返回</button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
