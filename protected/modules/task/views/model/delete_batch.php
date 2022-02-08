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
    <!--    <div class="card-header">-->
    <!--        <h3 class="box-title">Upload Component</h3>-->
    <!--    </div>-->
    <div class="card-body">
        <form enctype="multipart/form-data">
            <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
                <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
                <strong id='msginfo'></strong><span id='divMain'></span>
            </div>

            <div class="form-group group-space-between">
                <label for="group_name" class="col-2 control-label offset-md-1  label-rignt"><?php echo Yii::t('comp_staff', 'Select file'); ?></label>
                <!-- <div class="input-group col-8"> -->
                <div class="input-group col-6 padding-lr5">
                    <input class="form-control" name="file" id="file" type="file" style="display:none" onchange="$('#uploadurl').val($(this).val())">
                    <div class="input-group ">
                        <input type="hidden" id="project_id" value="<?php echo $program_id; ?>">
                        <input type="text" name="file" class="form-control" id="uploadurl" onclick="$('#file').click();"  readonly>
                        <span class="input-group-btn">
                                <a class="btn btn-warning" style="height: 34px;" onclick="$('input[id=file]').click();">
                                <i class="fa fa-folder-open"></i> <?php echo Yii::t('common','button_browse'); ?></a>
                            </span>
                    </div>
                </div>
                <!-- </div> -->
            </div>

            <div class="row button-space-between" style="margin-top: 10px;">
                <div class="col-12" style="text-align: center">
                    <button type="button" id="sbtn" class="btn btn-primary" onclick="fileupload();"><?php echo Yii::t('common', 'button_ok'); ?></button>
                    <button type="button" class="btn btn-default"
                            style="margin-left: 10px;" onclick="back('<?php echo $program_id ?>');"><?php echo Yii::t('common', 'button_back'); ?></button>
                </div>
            </div>

        </form>

        <hr>
        <div class="form-group group-space-between">
            <label for="group_name" class="col-2 control-label offset-md-1  label-rignt"><?php echo Yii::t('comp_staff', 'upload result'); ?></label>
            <div class="col-9 padding-lr5" id="prompt">

            </div>
        </div>
    </div>
    <div class="card-footer">

    </div>

</div>


<?php $this->endWidget(); ?>

<script src="js/loading.js"></script>
<script type="text/javascript">
    //     jQuery(document).ready(function () {
    //         var program_id = $('#project_id').val();
    //         var formData = new FormData();
    //         let modeldata = new Map();
    //         formData.append("project_id",program_id);
    //         $.ajax({
    //             url: "index.php?r=rf/rf/modellist",
    //             type: "POST",
    //             data: formData,
    //             dataType: "json",
    //             processData: false,         // 告诉jQuery不要去处理发送的数据
    //             contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
    //             beforeSend: function () {
    //
    //             },
    //             success: function(data){
    //                 $.each(data, function (name, value) {
    //                     let temp = {};
    //                     temp._id = value['model_id'];
    //                     temp._version = value['version'];
    //                     temp._name = value['model_name'];
    //                     modeldata.set(value['model_name'], temp);
    //                 })
    //                 // let modelarray = await data.getWINDDataQuerier().getAllModelParameterS();
    //                 let model_name = $('#model_name').val();
    //                 console.log(modeldata);
    //                 let modellistUI = document.getElementById("modellist");
    //                 modeldata.forEach((model, name) => {
    // //                    console.log(model);
    //                     //new Option(text,value)
    //                     id_version = model._id+'_'+model._version;
    //                     modellistUI.add(new Option(name,id_version));
    //                 });
    //                 modellistUI.options[0].selected = true;//默认选中第一个
    //             },
    //             error: function(XMLHttpRequest, textStatus, errorThrown) {
    //                 //alert(XMLHttpRequest.status);
    //                 //alert(XMLHttpRequest.readyState);
    //                 //alert(textStatus);
    //             },
    //         });
    //     })
    //初始化
    function Init(node) {
        return node.html("<option>---Please Select---</option>");
    }
    //处理work_pass_type
    $('#form_type').change(function(){
        //alert($(this).val());

        var typeObj = $("#type_id");
        var typeOpt = $("#type_id option");
        Init(typeObj);
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
                for (var o in data) {//console.log(o);
                    typeObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        })
    })

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
        window.location = "index.php?r=task/model/pbulist&program_id="+id;
        //window.location = "./?<?php //echo Yii::app()->session['list_url']['task/model/list']; ?>//";
    }

    /*
     * 保存表单类型
     */
    var post = function(){
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=task/model/save",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == '1'){
                    ajaxFileUpload();
                }else if(data.status == '-2'){
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html('Are you sure to replace the existing Form ID?');
                    $('#msgbox').show();
                    ajaxFileUpload()
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
    var per_read_cnt = 5;

    var ajaxFileUpload = function (){

        var file = document.getElementById("uploadurl").value;
        // var obj = document.getElementById("modellist"); //定位id
        // var index = obj.selectedIndex; // 选中索引
        // var type = obj.options[index].value; // 选中值
        if(file == ''){
            alert('<?php echo Yii::t('comp_staff', 'Error Upload_file is null'); ?>');
            return false;
        }
        $('#prompt').html('<?php echo Yii::t('comp_staff', 'export_loding'); ?>');
        jQuery.ajaxFileUpload({
            url: './index.php?r=task/model/upload',
            secureuri: false,
            fileElementId: 'file',
            dataType: 'json',
            success: function (data, status) {
//                alert('进来了');
                $('#prompt').append("</br><?php echo Yii::t('comp_staff', 'total'); ?> "+data.rowcnt+" <?php echo Yii::t('comp_staff', 'begin_import'); ?>").show();

                ajaxReadData(data.filename, data.rowcnt, 5);

            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log(XMLHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
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
            url: './index.php?r=task/model/newupload',
            type: "POST",
            data: {file_path: file_path},
            dataType: "json",
            success: function (data, status) {
                $('#prompt').append("</br><?php echo Yii::t('comp_staff', 'total'); ?> "+data.rowcnt+" <?php echo Yii::t('comp_staff', 'begin_import'); ?>").show();

                ajaxReadData(data.filename, data.rowcnt, 5);

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
        var blockchart = 'A';
        if($("#blockchart_a").prop("checked")){
            blockchart = 'A';
        }
        if($("#blockchart_b").prop("checked")){
            blockchart = 'B';
        }
        // var obj = document.getElementById("modellist"); //定位id
        // var index = obj.selectedIndex; // 选中索引
        // var id_version = obj.options[index].value; // 选中值
        jQuery.ajax({
            data: {filename:filename, startrow: startrow, per_read_cnt:per_read_cnt, id:id, blockchart:blockchart},
            type: 'post',
            url: './index.php?r=task/model/deletedata',
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