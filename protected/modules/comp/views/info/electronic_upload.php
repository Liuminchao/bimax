<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    'autoValidation' => false,
    //"action" => "javascript:formSubmit()",
));
//echo $form->activeHiddenField($model, 'program_id', array(), '');
?>
<?php
$upload_url = Yii::app()->params['upload_url'];//上传地址
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
        <input type="hidden" id="contractor_id" name="ElectronicContract[contractor_id]" value="<?php echo "$contractor_id"; ?>"/>
        <input type="hidden" id="contractor_name" name="ElectronicContract[contractor_name]" value="<?php echo "$contractor_name"; ?>"/>
        <input type="hidden" id="upload_url" name="File[contract_src]" />
        <input type="hidden" id="url" value="<?php echo "$upload_url" ?>"/>
    </div>
    <!--<div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('task', 'upload'); ?></h3>
        </div>
    </div>-->
    <div  class="row">
        <div class="col-md-6" >
            <div class="form-group"><!--  电子合约起始日期  -->
                <label for="start_date" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('start_date'); ?></label>
                <div class="input-group col-sm-6 ">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($model, 'start_date', array('id' => 'start_date', 'class' =>'form-control b_date_permit', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6" >
            <div class="form-group"><!--  电子合约截止日期  -->
                <label for="end_date" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('end_date'); ?></label>
                <div class="input-group col-sm-6 ">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($model, 'end_date', array('id' => 'end_date', 'class' =>'form-control b_date_permit', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    <div  class="row">
        <div class="col-md-6" >
            <div class="form-group"><!--  标题  -->
                <label for="content" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('title'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'title', array('id' => 'title', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group"><!--  备注  -->
                <label for="content" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('content'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php
                        echo $form->activeTextArea($model, 'content', array('id' => 'content', 'class' =>'form-control ','check-type' => ''));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  文件上传  -->
            <div class="form-group">
                <label for="file" class="col-sm-3 control-label padding-lr5" ><?php echo $model->getAttributeLabel('file');?></label>
                <?php echo $form->activeFileField($model, 'file_path', array('id' => 'file_path', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "$('#uploadurl').val($(this).val());")); ?>
                <div class="input-group col-md-6 padding-lr5">
                    <input id="uploadurl" class="form-control" type="text" disabled>
                        <span class="input-group-btn">
                            <a class="btn btn-warning" onclick="$('input[id=file_path]').click();">
                                <i class="fa fa-folder-open"></i> <?php echo Yii::t('common','button_browse'); ?>
                            </a>
                        </span>
                </div>
            </div>
        </div>
    </div>

<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-12">
            <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="btnsubmit();"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>

</div>
<?php $this->endWidget(); ?>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript">
    //返回
    var back = function () {
        var id = document.getElementById('contractor_id').value;
        var name = document.getElementById('contractor_name').value;
        window.location = "index.php?r=comp/info/electroniclist&name="+name+"&id="+id;
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
    var btnsubmit = function () {
        var id = document.getElementById('contractor_id').value;
        var name = document.getElementById('contractor_name').value;
        var upload_url = $("#url").val();
        var form = document.forms[0];
        var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
        formData.append("file1", $('#file_path')[0].files[0]);
        //alert(params);
        $.ajax({
            url: upload_url,
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,         // 告诉jQuery不要去处理发送的数据
            contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
            success: function (data) {
                $.each(data, function (name, value) {
                    if (name == 'data') {
                        $("#upload_url").val(value.file1);
//                        $('#file_path').attr("disabled","disabled");
//                        $('#sbtn').attr("disabled","disabled");
//                        addcloud(); //为页面添加遮罩
//                        document.onreadystatechange = subSomething; //监听加载状态改变
//                        $("#form1").submit();
                          formsubmit();
                    }
                });
            },
        });
    }
    function formsubmit() {
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=comp/info/upload",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success fa-ban');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>
