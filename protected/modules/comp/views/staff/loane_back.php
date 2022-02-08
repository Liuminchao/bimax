<div class="box box-info">

<?php
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */
if ($msg) {
    $class = Utils::getMessageType($msg['status']);
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
    'focus' => array($model, 'contractor_sn'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
        ));
echo $form->activeHiddenField($model, 'user_id');
echo $form->activeHiddenField($model, 'original_conid');
echo $form->HiddenField('type', '1');
$contractor_id = Yii::app()->user->contractor_id;
?>
<div class="box-body">

    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-11">
            <?php if($model->loaned_status == 1){ ?>
                <?php  if($model->contractor_id == $contractor_id){ ?>
                    <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'label_second_comp_return'); ?></h3>
                <?php }else{ ?>
                    <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'label_second_comp_recall'); ?></h3>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    
    <div class="row">
            <div class="form-group">
                <label for="user_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('comp_staff', 'Secondee'); ?></label>
                <div class="col-sm-5 padding-lr5">
                    <span class="help-block"><?php echo $model->user_name;   ?></span>
                </div>
            </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="original_company" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('comp_staff', 'original_company'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <span class="help-block"><?php echo $comp_model->contractor_name;   ?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="program_content" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('comp_staff', 'loane_company'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <span class="help-block"><?php echo $loane_model->contractor_name;   ?></span>
                </div>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group">
            <label for="program_content" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('comp_staff', 'loane_time'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <span class="help-block"><?php echo Utils::DateToEn(substr($model->loaned_time,0,10));   ?></span>
                </div>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('comp_staff', 'Button Apply'); ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['staff/list']; ?>";
    }
    
</script>

</div>