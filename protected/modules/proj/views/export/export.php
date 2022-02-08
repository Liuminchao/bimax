<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    //'autoValidation' => false,
    "action" => "javascript:formSubmit()",
        ));
//var_dump($program_id);
//var_dump($task_id);
//exit;
//echo $form->activeHiddenField($model, 'program_id', array(), '');
?>

<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>
   <div >
        <input type="hidden" id="program_id" name="Task['program_id']" value="<?php echo "$program_id"; ?>"/>
        </div>
    <div >
        <input type="hidden" id="task_id" name="Task['task_id']" value="<?php echo "$task_id"; ?>"/>
        </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('common', 'export_month'); ?></h3>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group">
            <label for="plan_start_time" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('task', 'month'); ?></label>
            <div class="col-sm-3 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'plan_start_time', array('id' => 'plan_start_time', 'class' =>'form-control b_date', 'onclick' => "WdatePicker({lang:'en',dateFmt:' MMM yyyy '})", 'check-type' => '','required-message' => ''));
                    //echo '<a class="right" style="cursor: pointer;" id="texport"><strong onclick="formSubmit();">导出</strong></a>';
                ?>
            </div>
        </div>
    
    </div>
    
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                <!--<button type="button" id="export" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="formSubmit();"><?php echo Yii::t('common', 'button_export'); ?></button>-->
                <a class="btn btn-default btn-lg" id="export" onclick="formSubmit();"><?php echo Yii::t('common', 'button_export'); ?></a>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }
    var program_id = document.getElementById('program_id').value;
    var task_id = document.getElementById('task_id').value;
    var plan_start_time = document.getElementById('plan_start_time').value;
 //导出报表
    var formSubmit = function () {
        //alert(program_id);
        var month = document.getElementById("plan_start_time").value;
        if(month == ""){
            alert('请选择月份');
            return;
        }
        var params = $('#form1').serialize();
        //alert("./?r=proj/export/export&"+params+'&task_id='+task_id+'&program_id='+program_id);
        //var act = document.getElementById("export"); 
        window.location = "./?r=proj/export/export&"+params+'&task_id='+task_id+'&program_id='+program_id;
        //alert("index.php?r=proj/task/tnew&" + params);
//        $.ajax({
//            url: "index.php?r=proj/export/export&"+params+'&task_id='+task_id+'&program_id='+program_id,
//            type: "POST",
//            dataType: "json",
//            beforeSend: function () {
//
//            },
//            success: function (data) {
//                //alert(data);
////                $('#msgbox').addClass('alert-success');
////                $('#msginfo').html(data.msg);
////                $('#msgbox').show();
//            },
//            error: function () {
//                //alert('error');
//                //alert(data.msg);
////                $('#msgbox').addClass('alert-danger fa-ban');
////                $('#msginfo').html('系统错误');
////                $('#msgbox').show();
//            }
//        });
          
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
