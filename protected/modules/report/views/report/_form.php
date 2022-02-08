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
            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  项目  -->
            <div class="form-group">
                <label for="type_no" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('tbm_meeting','program_name'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <select class="form-control input-sm" id="program_id" name="TrainSafetyPromote[program_id]" style="width: 100%;" >
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
        <div class="col-md-6" >
            <div class="form-group"><!--  月份  -->
                <label for="certificate_startdate" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','report_date'); ?></label>
                <div class="input-group col-sm-6 ">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($model, 'report_date', array('id' => 'report_date', 'class' =>'form-control b_date_permit', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                    <i class="input-group-addon" style="color:#FF9966">*</i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','executive_summary'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'executive_summary', array('id' => 'executive_summary', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','accident_statistics'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'accident_statistics', array('id' => 'accident_statistics', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','safety_policy'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'safety_policy', array('id' => 'safety_policy', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','external_training_scheduled'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'external_training_scheduled', array('id' => 'external_training_scheduled', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','group_meetings'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'group_meetings', array('id' => 'group_meetings', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','ehs_station'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'ehs_station', array('id' => 'ehs_station', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','security_station'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'security_station', array('id' => 'security_station', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','ehs_tunnel'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'ehs_tunnel', array('id' => 'ehs_tunnel', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','signboards_posters'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'signboards_posters', array('id' => 'signboards_posters', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','safety_conscious_workers'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'safety_conscious_workers', array('id' => 'safety_conscious_workers', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','newspaper_articles'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'newspaper_articles', array('id' => 'newspaper_articles', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','safety_committee_inspection'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'safety_committee_inspection', array('id' => 'safety_committee_inspection', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','lta_planned_inspection'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'lta_planned_inspection', array('id' => 'lta_planned_inspection', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','safety_personnel_inspection'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'safety_personnel_inspection', array('id' => 'safety_personnel_inspection', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','electrical_inspection'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'electrical_inspection', array('id' => 'electrical_inspection', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','ra_description'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'ra_description', array('id' => 'ra_description', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('report','occupational_health'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'occupational_health', array('id' => 'occupational_health', 'class' => 'form-control', 'check-type' => '')); ?>
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
        window.location = "index.php?r=report/report/list";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }


</script>






