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
    'focus' => array($model, 'id'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
        ));

echo $form->activeHiddenField($model, 'flow_id');
?>
<input type="hidden" name="step_cnt" id="step_cnt" value="<?php echo $step_cnt ?>"/>
<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('common', 'Base Info'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="flow_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('flow_name'); ?></label>
            <div class="col-sm-6 padding-lr5 help-block">
                <?php echo $model->flow_name; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="contractor_id" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('contractor_id'); ?></label>
            <div class="col-sm-6 padding-lr5 help-block">
                <?php
                $obj_list = Workflow::UseObjectText();
                echo $obj_list[$model->contractor_id];
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('sys_workflow', 'Audit Step'); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="flow_name" class="col-sm-3 control-label padding-lr5"><a href="#" onclick="AddStep()"><?php echo Yii::t('sys_workflow', 'Add Step'); ?></a></label>
        </div>
    </div>
    <div id="div_step_list">
        <?php
        if (!empty($step_list)) {
            foreach ($step_list as $id => $row) {
                echo '<div id="div_step_' . $id . '" class="row">
            <div class="form-group">
                <label class="col-sm-3 control-label padding-lr5" for="flow_name">' . Yii::t('sys_workflow', 'step') . '</label>
                <div class="col-sm-6 padding-lr5">
                    <input class="form-control" type="text" name="Workflow[object_name][]" readonly value="'.$row['obj_name'].'" title="'.$row['obj_name'].'">
                </div>
                <div class="col-sm-3"><a onclick="DelStep(' . $id . ')">' . Yii::t('sys_workflow', 'Delete Step') . '</a></div>
                <input type="hidden" name="Workflow[object_id][]" value="'.$row['obj_id'].'"/>
                <input type="hidden" name="Workflow[type][]" value="'.$row['type'].'"/>
            </div>
        </div>';
            }
        }
        ?>
    </div>
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
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['workflow/list']; ?>";
    }
    //添加节点
    var AddStep = function () {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('sys_workflow', 'Add Step'); ?>";
        modal.url = "index.php?r=sys/workflow/step";
        modal.modal();
    }
    //删除节点
    var DelStep = function (id) {
        $("#div_step_" + id).remove();
    }
</script>