<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<style type="text/css">

    input.task_attach{
        position:absolute;
        right:0px;
        top:0px;
        opacity:0;
        filter:alpha(opacity=0);
        cursor:pointer;
        width:276px;
        height:36px;
        overflow: hidden;
    }

</style>

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

<div class="row">
    <p style="color:red;font-size:16px;">Document name cannot contain special characters and symbols such as . / and etc.</p>
</div>
<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>
   <div >
        <input type="hidden" id="program_id" name="TaskAttach[program_id]" value="<?php echo "$program_id"; ?>"/>
        </div>
    <div >
        <input type="hidden" id="task_id" name="TaskAttach[task_id]" value="<?php echo "$task_id"; ?>"/>
        </div>
    <!--<div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('task', 'upload'); ?></h3>
        </div>
    </div>-->
    
    <div class="row">
        <div class="col-md-6"><!--  照片  -->
            <div class="form-group">
                 <label for="face_img" class="col-sm-3 control-label padding-lr5" ><?php echo $model->getAttributeLabel('task_attach');?></label>
                 <?php echo $form->activeFileField($model, 'task_attach', array('id' => 'task_attach', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "$('#uploadurl').val($(this).val());")); ?>
                    <div class="input-group col-md-9 padding-lr5">
                        <input id="uploadurl" class="form-control" type="text" disabled>
                        <span class="input-group-btn">
                            <a class="btn btn-warning" onclick="$('input[id=task_attach]').click();">
                            <i class="fa fa-folder-open"></i> <?php echo Yii::t('common','button_browse'); ?>
                            </a>
                        </span>
                    </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="attach_content" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('attach_content'); ?></label>
                <div class="col-sm-9 padding-lr5">
                    <?php
                        echo $form->activeTextArea($model, 'attach_content', array('id' => 'attach_content', 'class' =>'form-control ','check-type' => ''));
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                <!--<button type="button" id="export" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="formSubmit();"><?php echo Yii::t('common', 'button_export'); ?></button>-->
                <!--<a class="btn btn-default btn-lg" id="export" onclick="formSubmit();"><?php echo Yii::t('common', 'button_ok'); ?></a>-->
                <a class="btn btn-default btn-lg" id="submit" onclick="ajaxFileUpload();"><?php echo Yii::t('common', 'button_ok'); ?></a>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
    
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    //返回
    var back = function () {
       var program_id = document.getElementById('program_id').value;
       var task_id = document.getElementById('task_id').value;
       //alert(task_id);
       window.location = "index.php?r=proj/upload/attachlist&program_id="+program_id+'&task_id='+task_id;
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
    
    /*
     * 上传图片
     */
    function ajaxFileUpload() {
        var program_id = document.getElementById('program_id').value;
        var task_id = document.getElementById('task_id').value;
        var attach_content = document.getElementById('attach_content').value;
//        if(attach_content==''){
//            alert('内容不能为空');
//            return;
//        }
        //alert(attach_content);
        var params = $('#form1').serialize();
        var data = { program_id: program_id, task_id: task_id,attach_content:attach_content };
        jQuery.ajaxFileUpload
                (
                    {
                        url: './index.php?r=proj/upload/new',
                        data: data,
                        secureuri: false,
                        fileElementId: 'task_attach',
                        dataType: 'json',
                        success: function (data,status) {
                            //alert(data);
                            //var data = eval(data);
                            $('#msgbox').addClass('alert-success');
                            $('#msginfo').html(data.msg);
                            $('#msgbox').show();
                            showTime(data.refresh);
                        },
                        error: function (data,status,e) {
                            //alert(e);
                            //alert(data.msg);
                            $('#msgbox').addClass('alert-danger fa-ban');
                            $('#msginfo').html('系统错误');
                            $('#msgbox').show();
                            //alert(data['success']);
                        },
                        complete: function (xhr, ts) {

                            $("#smgp").html("");
                            $("#btnSubmit").show();
                        }
                    }
                );
        return false;
    }
    
    //提交表单（添加）
    var formSubmit = function () {
           
        var params = $('#form1').serialize();
        //alert(params);
        $.ajax({
            //data: {program_id: program_id,task_name: task_name,plan_start_time:plan_start_time,plan_end_time:plan_end_time,plan_work_hour:plan_work_hour,plan_amount:plan_amount,amount_unit:amount_unit,task_content:task_content},
            //url: "index.php?r=proj/task/tnew&"+params ,
            data:$('#form1').serialize(),
            url: "index.php?r=proj/upload/new",
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
                <?php echo $this->gridId; ?>.refresh();
            },
            error: function () {
                
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    
    jQuery(document).ready(function () {
        
    
    });
</script>




