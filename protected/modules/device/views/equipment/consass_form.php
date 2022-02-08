<?php
if ($msg) {
    $class = Utils::getMessageType($msg ['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          <script type='text/javascript'>
          /*{$this->gridId}.refresh();*/
          </script>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'focus' => array(
        $model,
        'name'
    ),
    'role' => 'form', // 可省略
    'formClass' => 'form-horizontal', // 可省略 表单对齐样式
    'autoValidation' => true
));
$upload_url = Yii::app()->params['upload_url'];
?>
<div class="box-body">
    <div >
        <?php if($_mode_ == 'edit'){ ?>
            <input type="hidden" id="plan_id" name="plan_id" value="<?php echo $plan_id; ?>" />
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  项目  -->
            <div class="form-group">
                <label for="type_no" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('tbm_meeting','program_name'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <select class="form-control input-sm" id="program_id" name="ConsassAudit[program_id]" style="width: 100%;" >
                        <?php
                        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                        $program_list = Program::McProgramList($args);
                        $program_id = $model->program_id;
                        foreach ($program_list as $id => $name) {
                            if($id == $program_id){
                                echo "<option value='{$id}' selected='selected'>{$name}</option>";
                            }else{
                                echo "<option value='{$id}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>

                </div>
                <i class="help-block" style="color:#FF9966">*</i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  actual_date  -->
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('device', 'Actual Audit Date'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'proposed_date', array('id' => 'proposed_date', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6"><!--  actual_date  -->
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('device', 'Actual Audit Date'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'actual_date', array('id' => 'actual_date', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  记录详情  -->
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('device', 'Remarks'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'audit_company', array('id' => 'audit_company', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6"><!--  记录详情  -->
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('device', 'Remarks'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'remarks', array('id' => 'remarks', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

</div>


<!--<?php //$this->renderpartial('_infoform', array( 'infomodel' => $infomodel, '_mode_' => 'insert')); ?>-->
<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="btnsubmit();"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default btn-lg"
                    style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>


<?php $this->endWidget(); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {

    });
    //提交
    var btnsubmit = function() {
        $("#form1").submit();
    }
    //返回
    var back = function () {
        window.location = "index.php?r=device/equipment/consasslist";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }


</script>