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

<div class="card card-info card-outline">
    <!-- <div class="card-header">
        <h3 class="box-title">Upload CHecklist Form</h3>
    </div> -->
    <div class="card-body">
        <form enctype="multipart/form-data">
            <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
                <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
                <strong id='msginfo'></strong><span id='divMain'></span>
            </div>

            <div class="form-group" style="margin-top: 10px;">
                <label for="form_id" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Form Id</label>
                <div class="input-group col-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'form_id', array('id' => 'form_id', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>

            <div class="form-group" style="margin-top: 10px;">
                <label for="form_type" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Program</label>
                <div class="input-group col-6 padding-lr5" >
                    <select class="form-control input" name="QaChecklist[program_id]" id="program_id" style="width: 100%;">
                        
                        <option value=''>System</option>
                        <?php
                        $pro_model = Program::model()->findByPk($program_id);
                        $root_proid = $pro_model->root_proid;
                        $root_model = Program::model()->findByPk($root_proid);
                        $root_proid_name = $root_model->program_name;
                        echo "<option value='{$root_proid}'>{$root_proid_name}</option>";
                        ?>
                    </select>
                </div>
                <span id="valierr" class="help-block" style="color:#FF9966">*</span>
            </div>

            <div class="form-group" style="margin-top: 10px;">
                <label for="form_type" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Form Type</label>
                <div class="input-group col-6 padding-lr5" >
                    <select class="form-control input" name="QaChecklist[form_type]" id="form_type" style="width: 100%;">
                        <option value=''>---Please Select---</option>
                        <option value='A'>Site</option>
                        <option value='B'>Fitout</option>
                        <option value='C'>Carcass</option>
                        <option value='0'>Inspection</option>
                    </select>
                </div>
                <span id="valierr" class="help-block" style="color:#FF9966">*</span>
            </div>

            <div class="form-group" style="margin-top: 10px;">
                <label for="form_name" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Form Name</label>
                <div class="input-group col-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'form_name', array('id' => 'form_name', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>

            <div class="form-group" style="margin-top: 10px;">

                <label for="form_name_en" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Form Name En</label>
                <div class="input-group col-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'form_name_en', array('id' => 'form_name_en', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>

            <div class="form-group" style="margin-top: 10px;">

                <label for="type_id" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Type Id</label>
                <div class="input-group col-6 padding-lr5">
                    <select class="form-control input" name="QaChecklist[type_id]" id="type_id" >
                        <option>---Please Select---</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-top:30px;">
                <div class="col-3"></div>
                <div class="col-2 padding-lr5">
                    <a href="javascript:void(0)" onclick="downloadFile()"><input type="button" class="btn btn-primary btn" value="<?php echo Yii::t('comp_staff','Template Download');?>" /></a>
                </div>
                <div class="col-4 padding-lr5" style="text-align: left;margin-left: 15px;"><?php echo Yii::t('comp_staff', 'Prompt_Template'); ?></div>
            </div>
            <hr/>


            <div class="form-group" style="margin-top: 10px;">

                <label for="group_name" class="col-2 control-label offset-md-1 label-rignt padding-lr5"><?php echo Yii::t('comp_staff', 'Select file'); ?></label>
                <div class="input-group col-6 padding-lr5" style="margin-top: 6px;text-align:right;">
                    <input class="form-control" name="file" id="file" type="file" style="display:none" onchange="$('#uploadurl').val($(this).val())">
                    <div class="input-group ">
                        <input type="hidden" id="project_id" value="<?php echo $project_id; ?>">
                        <input type="text" name="file" class="form-control" id="uploadurl" onclick="$('#file').click();"  readonly>
                        <span class="input-group-btn">
                        <a class="btn btn-warning" style="height: 34px;" onclick="$('input[id=file]').click();">
                        <i class="fa fa-folder-open"></i> <?php echo Yii::t('common','button_browse'); ?>
                    </a>
                </span>
                    </div>
                </div>
            </div>
            <div class="row button-space-between" style="margin-top: 10px;">
                <div class="col-12" style="text-align: center">
                    <button type="button" id="sbtn" class="btn btn-primary" onclick="post();"><?php echo Yii::t('common', 'button_ok'); ?></button>
                    <button type="button" class="btn btn-default"
                            style="margin-left: 10px;" onclick="back('<?php echo $program_id ?>');"><?php echo Yii::t('common', 'button_back'); ?></button>
                </div>
            </div>

            
        </form>

        <hr>
        <div class="form-group">
            <label for="group_name" class="col-2 control-label offset-md-1 label-rignt padding-lr5"><?php echo Yii::t('comp_staff', 'upload result'); ?></label>
            <div class="col-6" id="prompt"></div>
        </div>
    </div>
</div>


<?php $this->endWidget(); ?>
<script src="js/loading.js"></script>
<script type="text/javascript">
    //初始化
    function Init(node) {
        return node.html("<option>---Please Select---</option>");
    }
    //处理work_pass_type
    $('#form_type').change(function(){
        //alert($(this).val());

        var typeObj = $("#type_id");
        var typeOpt = $("#type_id option");
        // Init(typeObj);
        var val = $(this).val() ;

        // if ($(this).val() == 0) {
        //     blockOpt.remove();
        //     regionOpt.remove();
        //     return;
        // }
        $.ajax({
            type: "POST",
            url: "index.php?r=qa/import/formtype",
            data: {form_type:$("#form_type").val()},
            dataType: "json",
            success: function(data){ //console.log(data);

                // blockOpt.remove();
                if (!data) {
                    return;
                }
                typeObj.empty();
                for (var o in data) {//console.log(o);
                    typeObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        })
    })

    //模板下载
    var downloadFile = function () {
        var url = './index.php?r=qa/import/download';
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
        window.location = "index.php?r=qa/import/list&program_id="+id;
        //window.location = "./?<?php echo Yii::app()->session['list_url']['qa/import/list']; ?>";
    }

    /*
     * 保存表单类型
     */
    var post = function(){
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=qa/import/save",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == '1'){
                    fileupload();
                }else if(data.status == '-2'){
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html('Are you sure to replace the existing Form ID?');
                    $('#msgbox').show();
                    fileupload()
//                    setTimeout(ajaxFileUpload(),1000);
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
    var per_read_cnt = 1;

    var ajaxFileUpload = function (){

        var file = document.getElementById("uploadurl").value;
        if(file == ''){
            alert('<?php echo Yii::t('comp_staff', 'Error Upload_file is null'); ?>');
            return false;
        }
        $('#prompt').html('<?php echo Yii::t('comp_staff', 'export_loding'); ?>');
        jQuery.ajaxFileUpload({
            url: './index.php?r=qa/import/upload',
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
            url: './index.php?r=qa/import/newupload',
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
            url: './index.php?r=qa/import/readdata',
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



