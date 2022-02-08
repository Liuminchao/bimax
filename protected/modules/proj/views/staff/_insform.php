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
    'autoValidation' => false
  ));
?>
<div class="row" >
    <div class="col-12">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=proj/staff/tabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>" ><?php echo Yii::t('comp_staff','Base Info');?></a></li>
                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=proj/staff/pertabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>"><?php echo Yii::t('comp_staff','Personal Info');?></a></li>
                    <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=proj/staff/instabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>"><?php echo Yii::t('comp_staff','ins');?></a></li>
                </ul>
            </div>
            <div class="card-body">
                <div >
                    <input type="hidden" id="user_id" name="StaffInfo[user_id]" value="<?php echo "$user_id"; ?>"/>
                    <input type="hidden" id="tag_id" name="Tag[tag_id]" value="ins"/>
                </div>
                <div class="row" style="padding-left: 50px;">
                    <div class="col-12">
                        <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Ins_scy'); ?></h3>
                    </div>
                </div>
                <div class="form-group group-space-between">
                    <label for="ins_scy_no" class="col-2 control-label offset-md-1 label-rignt" ><?php echo $infomodel->getAttributeLabel('ins_scy_no'); ?></label>
                    <div class="col-6 padding-lr5">
                        <?php echo $form->activeTextField($infomodel, 'ins_scy_no', array('id' => 'ins_scy_no', 'class' => 'form-control', 'check-type' => '')); ?>
                    </div>
                </div>
                <div class="form-group group-space-between">
                    <label for="ins_scy_issue_date" class="col-2 control-label offset-md-1  label-rignt" ><?php echo $infomodel->getAttributeLabel('issue_date'); ?></label>
                    <div class="col-6 padding-lr5 input-group date" data-target-input="nearest">
                        <input type="text" id="ins_scy_issue_date" class="form-control datetimepicker-input" data-target="#ins_scy_issue_date" value="<?php echo $infomodel->ins_scy_issue_date ?>" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" check-type="" name="StaffInfo[ins_scy_expire_date]" type="text">
                        <div class="input-group-append" data-target="#ins_scy_issue_date" data-toggle="datetimepicker">
                            <div class="input-group-text" style="height:34px;"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    
                </div>
                <div class="form-group group-space-between">
                    <label for="ins_scy_expire_date" class="col-2 control-label offset-md-1  label-rignt" ><?php echo $infomodel->getAttributeLabel('expire_date'); ?></label>
                    <div class="col-6 padding-lr5 input-group date" data-target-input="nearest">
                        <input type="text" id="ins_scy_expire_date" class="form-control datetimepicker-input" data-target="#ins_scy_expire_date" value="<?php echo $infomodel->ins_scy_expire_date ?>" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" check-type="" name="StaffInfo[ins_scy_expire_date]" type="text">
                        <div class="input-group-append" data-target="#ins_scy_expire_date" data-toggle="datetimepicker">
                            <div class="input-group-text" style="height:34px;"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-left: 50px;">
                    <div class="col-12">
                        <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Ins_med'); ?></h3>
                    </div>
                </div>
                <div class="form-group group-space-between">
                    <label for="ins_med_no" class="col-2 control-label offset-md-1  label-rignt" ><?php echo $infomodel->getAttributeLabel('ins_med_no'); ?></label>
                    <div class="col-6 padding-lr5">
                        <?php echo $form->activeTextField($infomodel, 'ins_med_no', array('id' => 'ins_med_no', 'class' => 'form-control', 'check-type' => '')); ?>
                    </div>
                </div>
                <div class="form-group group-space-between">
                    <label for="ins_med_issue_date" class="col-2 control-label offset-md-1  label-rignt" ><?php echo $infomodel->getAttributeLabel('issue_date'); ?></label>
                    <div class="col-6 padding-lr5 input-group date" data-target-input="nearest">
                        <input type="text" id="ins_med_issue_date" class="form-control datetimepicker-input" data-target="#ins_scy_issue_date" value="<?php echo $infomodel->ins_med_issue_date ?>" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" check-type="" name="StaffInfo[ins_med_issue_date]" type="text">
                        <div class="input-group-append" data-target="#ins_med_issue_date" data-toggle="datetimepicker">
                            <div class="input-group-text" style="height:34px;"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group group-space-between">
                    <label for="ins_med_expire_date" class="col-2 control-label offset-md-1  label-rignt" ><?php echo $infomodel->getAttributeLabel('expire_date'); ?></label>
                    <div class="col-6 padding-lr5 input-group date" data-target-input="nearest">
                        <input type="text" id="ins_med_expire_date" class="form-control datetimepicker-input" data-target="#ins_med_expire_date" value="<?php echo $infomodel->ins_med_expire_date ?>" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" check-type="" name="StaffInfo[ins_med_expire_date]" type="text">
                        <div class="input-group-append" data-target="#ins_med_expire_date" data-toggle="datetimepicker">
                            <div class="input-group-text" style="height:34px;"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-left: 50px;">
                    <div class="col-12">
                        <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Ins_adt'); ?></h3>
                    </div>
                </div>
                <div class="form-group group-space-between">
                    <label for="ins_adt_no" class="col-2 control-label offset-md-1  label-rignt" ><?php echo $infomodel->getAttributeLabel('ins_adt_no'); ?></label>
                    <div class="col-6 padding-lr5">
                        <?php echo $form->activeTextField($infomodel, 'ins_adt_no', array('id' => 'ins_adt_no', 'class' => 'form-control', 'check-type' => '')); ?>
                    </div>
                </div>
                <div class="form-group group-space-between">
                    <label for="ins_adt_issue_date" class="col-2 control-label offset-md-1  label-rignt" ><?php echo $infomodel->getAttributeLabel('issue_date'); ?></label>
                    <div class="col-6 padding-lr5 input-group date" data-target-input="nearest">
                        <input type="text" id="ins_adt_issue_date" class="form-control datetimepicker-input" data-target="#ins_adt_issue_date" value="<?php echo $infomodel->ins_adt_issue_date ?>" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" check-type="" name="StaffInfo[ins_adt_issue_date]" type="text">
                        <div class="input-group-append" data-target="#ins_adt_issue_date" data-toggle="datetimepicker">
                            <div class="input-group-text" style="height:34px;"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    
                </div>
                <div class="form-group group-space-between">
                    <label for="ins_adt_expire_date" class="col-2 control-label offset-md-1  label-rignt" ><?php echo $infomodel->getAttributeLabel('expire_date'); ?></label>
                    <div class="col-6 padding-lr5 input-group date" data-target-input="nearest">
                        <input type="text" id="ins_adt_expire_date" class="form-control datetimepicker-input" data-target="#ins_adt_expire_date" value="<?php echo $infomodel->ins_adt_expire_date ?>" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" check-type="" name="StaffInfo[ins_adt_expire_date]" type="text">
                        <div class="input-group-append" data-target="#ins_adt_expire_date" data-toggle="datetimepicker">
                            <div class="input-group-text" style="height:34px;"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="row button-space-between">
                    <div class="col-12" style="text-align: center">
                        <button type="submit" id="sbtn" class="btn btn-primary"><?php echo Yii::t('common', 'button_save'); ?></button>
                        <button type="button" class="btn btn-default"
                                style="margin-left: 10px" onclick="back('<?php echo $program_id ?>');"><?php echo Yii::t('common', 'button_back'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>  
<script type="text/javascript">
   
    jQuery(document).ready(function () {
        $('#ins_scy_issue_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('#ins_scy_expire_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('#ins_med_issue_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('#ins_med_expire_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('#ins_adt_issue_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('#ins_adt_expire_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('.b_date_ins').each(function(){
            a1 = $(this).val();
            a2 = datetocn(a1);
            if(a2!=' undefined'){
                $(this).val(a2);
            }
        });

    });
    ////返回
    //var back = function () {
    //    window.location = "./?<?php //echo Yii::app()->session['list_url']['staff/list']; ?>//";
    //    //window.location = "index.php?r=comp/usersubcomp/list";
    //}
    //返回
    var back = function (id) {
        //window.location = "index.php?r=proj/assignuser/authoritylist&ptype=<?php //echo Yii::app()->session['project_type'];?>//&id=" + id;
        window.location = "./?<?php echo Yii::app()->session['list_url']['assignuser/authoritylist']; ?>";
    }
</script>




