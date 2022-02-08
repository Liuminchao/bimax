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
<div class="box-body">
    <div >
        <input type="hidden" id="user_id" name="StaffInfo[user_id]" value="<?php echo "$user_id"; ?>"/>
        <input type="hidden" id="tag_id" name="Tag[tag_id]" value="ins"/>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Ins_scy'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  护照号  -->
            <div class="form-group">
                <label for="ins_scy_no" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('ins_scy_no'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'ins_scy_no', array('id' => 'ins_scy_no', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">  </div>
    <div class="row">
        <div class="col-md-6" >
            <div class="form-group">
                <label for="ins_scy_issue_date" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('issue_date'); ?></label>
                <div class="input-group col-sm-6">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'ins_scy_issue_date', array('id' => 'ins_scy_issue_date', 'class' =>'form-control b_date_ins','onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6" >
            <div class="form-group">
                <label for="ins_scy_expire_date" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('expire_date'); ?></label>
                <div class="input-group col-sm-6">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'ins_scy_expire_date', array('id' => 'ins_scy_expire_date', 'class' =>'form-control b_date_ins', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Ins_med'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  护照号  -->
            <div class="form-group">
                <label for="ins_med_no" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('ins_med_no'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'ins_med_no', array('id' => 'ins_med_no', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        </div>
    <div class="row">  </div>
    <div class="row">
        <div class="col-md-6" >
            <div class="form-group">
                <label for="ins_med_issue_date" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('issue_date'); ?></label>
                <div class="input-group col-sm-6">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'ins_med_issue_date', array('id' => 'ins_med_issue_date', 'class' =>'form-control b_date_ins', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6" >
            <div class="form-group">
                <label for="ins_med_expire_date" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('expire_date'); ?></label>
                <div class="input-group col-sm-6">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'ins_med_expire_date', array('id' => 'ins_med_expire_date', 'class' =>'form-control b_date_ins', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Ins_adt'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  护照号  -->
            <div class="form-group">
                <label for="ins_adt_no" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('ins_adt_no'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'ins_adt_no', array('id' => 'ins_adt_no', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        </div>
    <div class="row">  </div>
    <div class="row">
        <div class="col-md-6" >
            <div class="form-group">
                <label for="ins_adt_issue_date" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('issue_date'); ?></label>
                <div class="input-group col-sm-6">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'ins_adt_issue_date', array('id' => 'ins_adt_issue_date', 'class' =>'form-control b_date_ins', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6" >
            <div class="form-group">
                <label for="ins_adt_expire_date" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('expire_date'); ?></label>
                <div class="input-group col-sm-6">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'ins_adt_expire_date', array('id' => 'ins_adt_expire_date', 'class' =>'form-control b_date_ins', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
            </div>
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
        
        $('.b_date_ins').each(function(){
            a1 = $(this).val();
            a2 = datetocn(a1);
            if(a2!=' undefined'){
                $(this).val(a2);
            }
        });

    });
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['staff/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
</script>




