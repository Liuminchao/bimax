<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
   //'autoValidation' => false,
    "action" => "javascript:formSubmit()",
//    'enableAjaxSubmit' => false,
//    'ajaxUpdateId' => 'content-body',
//    'role' => 'form', //可省略
//    'formClass' => 'form-horizontal', //可省略 表单对齐样式
//    'autoValidation' => false,

        ));
//echo $form->activeHiddenField($model, 'program_id', array(), '');
?>

<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>
    <div >
        <input type="hidden" id="program_id" name="Task[program_id]" value="<?php echo "$program_id"; ?>"/>
        </div>
    <div >
        <input type="hidden" id="task_id" name="Task[task_id]" value="<?php echo "$task_id"; ?>"/>
        </div>
    <!--<div >
        <input type="hidden" id="father_taskid" name="Task[father_taskid]" value="<?php echo "$father_taskid"; ?>"/>
        </div>-->
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('common', 'Base Info'); ?></h3>
        </div>
    </div>
    <div class="row">
       
    
        <div class="form-group">
            <label for="task_name" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('task_name'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'task_name', array('id' => 'task_name', 'class' => 'form-control', 'check-type' => '', 'required-message' => ''));
                    //echo '<div class="col-sm-6 padding-lr5"><input id="task_name" class="form-control" type="text" maxlength="32" name="Task[task_name]" required-message="' . Yii::t('task', 'error_task_name_is_null') . '" check-type="required"></div>';
                ?>
            </div>
        </div>
    </div>
   
        <div class="row">
        <div class="form-group">
            <label for="plan_start_time" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('plan_start_time'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'plan_start_time', array('id' => 'plan_start_time', 'class' =>'form-control b_date', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '','required-message' => ''));
                ?>
            </div>
        </div>
    
        <div class="form-group">
            <label for="plan_end_time" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('plan_end_time'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'plan_end_time', array('id' => 'plan_end_time', 'class' =>'form-control b_date', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '','required-message' => ''));
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="plan_work_hour" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('plan_work_hour'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'plan_work_hour', array('id' => 'plan_work_hour', 'class' => 'form-control', 'check-type' => ''));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="plan_amount" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('plan_amount'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'plan_amount', array('id' => 'plan_amount', 'class' =>'form-control ','check-type' => ''));
                ?>
            </div>
        </div>
        </div>
    <div class="row">
         <div class="form-group">
            <label for="amount_unit" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('amount_unit'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'amount_unit', array('id' => 'amount_unit', 'class' =>'form-control ','check-type' => ''));
                ?>
            </div>
        </div>
    <div class="form-group">
            <label for="task_content" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('task_content'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <?php
                    echo $form->activeTextArea($model, 'task_content', array('id' => 'task_content', 'class' =>'form-control ','check-type' => ''));
                ?>
            </div>
        </div>
    </div>
    <!--
    //<?php 
//        $tasklist = Task::primaryTask($program_id);
////        var_dump($tasklist);
////        exit;
//    ?>
    
    //<?php if($_mode_ == 'insert'){    ?>
    <div class="row">
        <div class="form-group">
            
            <label for="task_id" class="col-sm-2 control-label padding-lr5">//<?php echo $model->getAttributeLabel('upper_level_task'); ?></label>
            <div class="col-sm-5 padding-lr5">
                
               
                <select id = "taskid" class="form-control input-sm" name="Task[task_id]" style="width: 100%;">
                        <option value="">-//<?php echo Yii::t('task', 'no_task'); ?>-</option>
                        //<?php
//                            $tasklist = Task::primaryTask($program_id);
//                          
//                            if($tasklist){
//                                foreach ($tasklist as $task_id => $task_name) {
//                                    echo "<option value='{$task_id}'>{$task_name}</option>";
//                                }
//                            }
//                            
//                        ?>
                    </select>
            </div>
        </div>
    </div>    
    //<?php }?>
    -->
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                <?php if($_mode_ == 'insert'){    ?>
                    <button type="button" onclick="formSubmit()"  id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <?php }else{ ?>
                    <button type="button" onclick="formSubmit1()" id="sbtn1" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <?php }?>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    
    jQuery(document).ready(function () {
        $("#form1").validation(function (obj, params) {
            //alert($("#task_name").val());
            if (obj.id == 'task_name' && $("#task_name").val() =='') {
                params.err = '<?php echo Yii::t('task', 'error_task_name_is_null'); ?>';
                params.msg = "<?php echo Yii::t('task', 'error_task_name_is_null'); ?>";
            }
            //alert($("#plan_start_time").val());
//            if (obj.id == 'plan_start_time' && $("#plan_start_time").val() =='') {
//                params.err = '<?php echo Yii::t('task', 'error_work_date_is_null'); ?>';
//                params.msg = "<?php echo Yii::t('task', 'error_work_date_is_null'); ?>";
//            }
//            if (obj.id == 'plan_end_time' && $("#plan_end_time").val() =='') {
//                params.err = '<?php echo Yii::t('task', 'error_work_date_is_null'); ?>';
//                params.msg = "<?php echo Yii::t('task', 'error_work_date_is_null'); ?>";
//            }
        });
    });
    
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }
    var n = 4;
    //定时关闭弹窗
    function showTime(flag) {
        if (flag == false)
            return;
        n--;
        $('#divMain').html(n + ' <?php echo Yii::t('common', 'tip_close'); ?>');
        if (n == 0)
            $("#modal-close").click();
        else
            setTimeout('showTime()', 1000);
    }
 //提交表单（添加）
    var formSubmit = function () {
           
        //var obj=document.getElementById('task_id');
        //var text=obj.options[obj.selectedIndex].text;//获取文本
        var program_id = document.getElementById('program_id').value;
   
        var params = $('#form1').serialize();
  
        //alert("index.php?r=proj/task/tnew&" + params);
        $.ajax({
            //data: {program_id: program_id,task_name: task_name,plan_start_time:plan_start_time,plan_end_time:plan_end_time,plan_work_hour:plan_work_hour,plan_amount:plan_amount,amount_unit:amount_unit,task_content:task_content},
            //url: "index.php?r=proj/task/tnew&"+params ,
            data:$('#form1').serialize(),
            url: "index.php?r=proj/task/snew",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
            
            },
            success: function (data) {
                //alert(data);
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
                itemQuery();
            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //提交表单（修改）
    var formSubmit1 = function () {
        //alert(program_id);
        //var task_id = document.getElementById('task_id').value;
        //var father_taskid = document.getElementById('father_taskid').value;
        var params = $('#form1').serialize();
        //alert("index.php?r=proj/task/tnew&" + params);
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=proj/task/sedit",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
      
            },
            success: function (data) {
                //alert(data);
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
                itemQuery();
            },
            error: function () {
                //alert('error');
                alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    
    document.getElementById("plan_work_hour").onkeyup = function() {
        var str=(this.value).replace(/[^\d]/g, "");
        this.value=str;
    }
 
    jQuery(document).ready(function () {
        
        $('.b_date').each(function(){
            a1 = $(this).val();
            a2 = datetocn(a1);
            if(a2!=' undefined'){
                $(this).val(a2);
            }
        });

    });
</script>
