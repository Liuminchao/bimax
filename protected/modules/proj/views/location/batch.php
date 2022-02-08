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

<div class="container">
    <form enctype="multipart/form-data">
        <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
            <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
            <strong id='msginfo'></strong><span id='divMain'></span>
        </div>
        <div class="form-group">
            <div class="col-1"></div>
            <div class="col-3">
                <a href="javascript:void(0)" onclick="downloadFile()"><input type="button" class="btn btn-primary btn" value="<?php echo Yii::t('comp_staff','Template Download');?>" /></a>
            </div>
            <div class="col-7 control-label" style="text-align: left;line-height: 38px;"><?php echo Yii::t('comp_staff', 'Prompt_Template'); ?></div>
        </div>
        <hr/>


        <div class="form-group">
            <label for="group_name" class="col-2 control-label offset-md-1 padding-lr5" style="margin-top: 6px;"><?php echo Yii::t('comp_staff', 'Select file'); ?></label>
            <div class="input-group col-7 padding-lr5">
                <input class="form-control" name="file" id="file" type="file" style="display:none" onchange="$('#uploadurl').val($(this).val())">
                <div class="input-group ">
                    <input type="hidden" id="project_id" value="<?php echo $project_id; ?>">
                    <input type="text" name="file" class="form-control" id="uploadurl" onclick="$('#file').click();"  readonly>
                    <span class="input-group-btn">
                            <a class="btn btn-warning" onclick="$('input[id=file]').click();" style="height: 34px;padding-top: 5px;">
                                <i class="fa fa-folder-open"></i> <?php echo Yii::t('common','button_browse'); ?>
                            </a>
                        </span>
                </div>
            </div>
        </div>

        <div class="row button-space-between" style="margin-top: 10px;">
            <div class="col-12" style="text-align: center">
                <button type="button" id="sbtn" class="btn btn-primary" onclick="fileupload();"><?php echo Yii::t('common', 'button_ok'); ?></button>
                <button type="button" class="btn btn-default"
                        style="margin-left: 10px;" onclick="back('<?php echo $project_id ?>');"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>

        
    </form>
    <hr>
    <div class="form-group">
        <label for="group_name" class="col-2 control-label offset-md-1 padding-lr5" style="margin-top: 6px;"><?php echo Yii::t('comp_staff', 'upload result'); ?></label>
        <div class="col-6" id="prompt"></div>
    </div>
</div>


<?php $this->endWidget(); ?>
<script src="js/loading.js"></script>
<script type="text/javascript">

    //模板下载
    var downloadFile = function () {
        var url = './index.php?r=proj/location/download';
        window.location.href=url;
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
        window.location = "index.php?r=proj/location/locationlist&project_id=" + id;
    }


    /*
     * 上传文件
     */
    var per_read_cnt = 10;

    var ajaxFileUpload = function (){

        var file = document.getElementById("uploadurl").value;
        if(file == ''){
            alert('<?php echo Yii::t('comp_staff', 'Error Upload_file is null'); ?>');
            return false;
        }
        $('#prompt').html('<?php echo Yii::t('comp_staff', 'export_loding'); ?>');
        jQuery.ajaxFileUpload({
            url: './index.php?r=proj/location/upload',
            secureuri: false,
            fileElementId: 'file',
            dataType: 'json',
            success: function (data, status) {
                $('#prompt').append("</br><?php echo Yii::t('comp_staff', 'total'); ?> "+data.rowcnt+" <?php echo Yii::t('comp_staff', 'begin_import'); ?>").show();

                ajaxReadData(data.filename, data.rowcnt, 1);

            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                //alert(XMLHttpRequest);
                //alert(textStatus);
                //alert(errorThrown);
            },

        });
        return false;
    }
    var fileupload = function(){
        var file_list = $('#file')[0].files;
        console.log(file.value);
        $.each(file_list, function (name, file) {
            if (!/\.(xls|xlsx)$/.test(file.name)) {
                alert("Please upload document in either .xls or .xlsx format.");
                return false;
            }
            tag = file.name.lastIndexOf(".");
            length = file.name.length;
            //获取后缀名
            type=file.name.substring(tag,length);
            name = file.name.substr(0,tag);
            var video_src_file = file.name;
            containSpecial = new RegExp(/[\~\%\^\*\[\]\{\}\|\\\;\:\'\"\,\.\/\?]+/);
            status = containSpecial.test(name);
            if(status == 'true'){
                alert('File name contains special characters, please check before uploading');
                return false;
            }
            var newFileName = video_src_file.split('.');

            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
            formData.append("file1", file);

            $.ajax({
                url: "https://shell.cmstech.sg/appupload",
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,         // 告诉jQuery不要去处理发送的数据
                contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
                beforeSend: function () {
                    addcloud();
                },
                success: function (data) {
                    removecloud();//去遮罩
                    newUpload(data.data.file1);
                }
            });
        })
    }
    var newUpload = function (file_path){
        var file = document.getElementById("uploadurl").value;
        if(file == ''){
            alert('<?php echo Yii::t('comp_staff', 'Error Upload_file is null'); ?>');
            return false;
        }
        $('#prompt').html('<?php echo Yii::t('comp_staff', 'export_loding'); ?>');
        $.ajax({
            url: './index.php?r=proj/location/newupload',
            type: "POST",
            data: {file_path: file_path},
            dataType: "json",
            success: function (data, status) {
                $('#prompt').append("</br><?php echo Yii::t('comp_staff', 'total'); ?> "+data.rowcnt+" <?php echo Yii::t('comp_staff', 'begin_import'); ?>").show();

                ajaxReadData(data.filename, data.rowcnt, 1);

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
    var ajaxReadData = function (filename, rowcnt, startrow){//alert('aa');
        var id = $("#project_id").val();
        jQuery.ajax({
            data: {filename:filename, startrow: startrow, per_read_cnt:per_read_cnt, id:id},
            type: 'post',
            url: './index.php?r=proj/location/readdata',
            dataType: 'json',
            success: function (data, textStatus) {
                for (var o in data) {
                    $('#prompt').append("</br>Row "+o+" : "+data[o].msg);
                }
                if (rowcnt > startrow) {
                    ajaxReadData(filename, rowcnt, startrow+per_read_cnt);
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



