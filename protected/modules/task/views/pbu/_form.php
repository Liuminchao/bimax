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
    <div class="form-group group-space-between">
        <label for="role_name" class="col-2 control-label padding-lr5 offset-md-1">Block</label>
        <div class="col-7 padding-lr5">
            <input id="project_id"  name="BlockChart[project_id]" type="hidden" value="<?php echo $project_id ?>">
            <select id="block" class="form-control" check-type="required" name="BlockChart[block]">
                <?php
                    $args['project_id'] = $project_id;
                    $block_list = BlockChart::blockList($args);
                    echo "<option value=''>--Block--</option>";
                    foreach($block_list as $i => $j){
                        echo "<option value='{$j}'>{$j}</option>";
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group group-space-between">
        <label for="role_name_en" class="col-2 control-label padding-lr5 offset-md-1">S_Val</label>
        <div class="col-7 padding-lr5">
            <select id="s_val" class="form-control" check-type="required" name="BlockChart[s_val]">
            </select>
        </div>
    </div>
    <div class="form-group group-space-between">
        <label for="team_name" class="col-2 control-label padding-lr5 offset-md-1">T_Val</label>
        <div class="col-7 padding-lr5">
            <select id="t_val" class="form-control" check-type="required" name="BlockChart[t_val]">
            </select>
        </div>
    </div>
    

    <div class="row button-space-between">
        <div class="col-12" style="text-align: center">
            <button type="submit" id="sbtn" class="btn btn-primary"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="reset" class="btn btn-default" style="margin-left: 10px"><?php echo Yii::t('common', 'button_reset'); ?></button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {

        $('#block').change(function(){
            //alert($(this).val());

            var s_valObj = $("#s_val");
            var s_valOpt = $("#s_val option");
            var t_valObj = $("#t_val");
            var t_valOpt = $("#t_val option");

            if ($(this).val() == 0) {
                s_valOpt.remove();
                t_valOpt.remove();
                return;
            }
            $.ajax({
                type: "POST",
                url: "index.php?r=task/blockchart/querypbutype",
                data: {block:$("#block").val(),project_id:$("#project_id").val()},
                dataType: "json",
                success: function(data){ //console.log(data);

                    s_valOpt.remove();
                    t_valOpt.remove();
                    if (!data) {
                        return;
                    }
                    for (var o in data) {//console.log(o);
                        s_valObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                        t_valObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                    }
                },
            });
        });
    });
</script>