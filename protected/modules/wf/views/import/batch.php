<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<?php
if ($msg) {
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
	<i class='fa {$class[1]}'></i>
	<button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
	<b>提示：</b>{$msg['msg']}
	</div>
	";
//    echo "<script type='text/javascript'>
//     	{$this->gridId}.refresh();
//     	</script>";
//        var_dump($msg['success']);
}

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => true,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'position_name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
    'autoValidation' => true,
));

?>

<div class="box-body">
    <form enctype="multipart/form-data">
        <div class="box-body">
            <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
                <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
                <strong id='msginfo'></strong><span id='divMain'></span>
            </div>

            <div class="row"><!--  form_name  -->
                <div class="form-group" style="margin-left: -4px">
                    <label for="form_name" class="col-sm-3 control-label padding-lr5">Version Nmae</label>
                    <div class="col-sm-3 padding-lr5">
                        <?php echo $form->activeTextField($model, 'version_name', array('id' => 'version_name', 'class' => 'form-control', 'check-type' => '')); ?>
                    </div>
                </div>
            </div>

            <hr/>


            <div class="form-group">
                <label for="group_name" class="col-sm-2 control-label"><?php echo Yii::t('comp_staff', 'Select file'); ?></label>
                <div class="col-sm-6">
                    <input class="form-control" name="file" id="file" type="file" style="display:none" onchange="$('#uploadurl').val($(this).val())">
                    <div class="input-group ">
                        <input type="hidden" id="project_id" name="ProgressPlanVersion[project_id]" value="<?php echo $program_id; ?>">
                        <input type="text" name="file" class="form-control" id="uploadurl" onclick="$('#file').click();"  readonly>
                        <span class="input-group-btn">
                    <a class="btn btn-warning" onclick="$('input[id=file]').click();">
                        <i class="fa fa-folder-open"></i> <?php echo Yii::t('common','button_browse'); ?>
                    </a>
                </span>
                    </div>
                </div>
            </div>


            <div class="box-footer">
                <a class="btn btn-primary btn-lg col-sm-offset-2" id="submit" onclick="post();"><?php echo Yii::t('common', 'button_ok'); ?></a>
                <button type="button" class="btn btn-primary btn-lg col-sm-offset-2" style="margin-left: 10px" onclick="back('<?php echo $program_id ?>');"><?php echo Yii::t('common', 'button_back'); ?></button>

            </div>
    </form>

    <hr>
    <div class="form-group">
        <label for="group_name" class="col-sm-2 control-label"><?php echo Yii::t('comp_staff', 'upload result'); ?></label>
        <div class="col-sm-6" id="prompt"></div>
    </div>
</div>


<?php $this->endWidget(); ?>

<script type="text/javascript">
    //初始化
    function Init(node) {
        return node.html("<option>---Please Select---</option>");
    }

    //上传是否为空
    var validate = function () {
        //var file = $("#file").attr("value");
        var file = document.getElementById("uploadurl").value;
        if(file == ''){
            alert('<?php echo Yii::t('comp_staff', 'Error Upload_file is null'); ?>');
            return false;
        }
    }
    var back = function (id) {
        window.location = "index.php?r=wf/import/list&program_id="+id;
    }

    /*
     * 保存表单类型
     */
    var post = function(){
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=wf/import/save",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == '1'){
                    ajaxFileUpload(data.version_id);
                }else{
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html('系统错误');
                    $('#msgbox').show();
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }

    /*
     * 上传文件
     */
    var per_read_cnt = 20;

    var ajaxFileUpload = function (version_id){
        var project_id = $("#project_id").val();
        var file = document.getElementById("uploadurl").value;
        if(file == ''){
            alert('<?php echo Yii::t('comp_staff', 'Error Upload_file is null'); ?>');
            return false;
        }
        $('#prompt').html('<?php echo Yii::t('comp_staff', 'export_loding'); ?>');
        jQuery.ajaxFileUpload({
            url: './index.php?r=wf/import/upload',
            secureuri: false,
            fileElementId: 'file',
            dataType: 'json',
            success: function (data, status) {
                $('#prompt').append("</br><?php echo Yii::t('comp_staff', 'total'); ?> "+data.rowcnt+" <?php echo Yii::t('comp_staff', 'begin_import'); ?>").show();

                ajaxReadData(data.filename, data.rowcnt, 0, version_id, project_id);

            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                //alert(XMLHttpRequest);
                //alert(textStatus);
                //alert(errorThrown);
            },

        });
        return false;
    }

    /*
     * 数据导入
     */
    var ajaxReadData = function (filename, rowcnt, startrow,version_id,project_id){//alert('aa');

        jQuery.ajax({
            data: {filename:filename, startrow: startrow, per_read_cnt:per_read_cnt, id:version_id, project_id:project_id},
            type: 'post',
            url: './index.php?r=wf/import/readdata',
            dataType: 'json',
            success: function (data, textStatus) {
                for (var o in data) {
                    $('#prompt').append("</br>Row "+o+" : "+data[o].msg);
                }
                if (rowcnt > startrow) {
                    ajaxReadData(filename, rowcnt, startrow+per_read_cnt,version_id,project_id);
                }else{
                    $('#prompt').append("</br><?php echo Yii::t('comp_staff', 'end_import'); ?>");
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                //alert(XMLHttpRequest);
                //alert(textStatus);
                //alert(errorThrown);
            },
        });
        return false;
    }

</script>



