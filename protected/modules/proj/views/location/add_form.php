<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($model, 'name'),
    'autoValidation' => true,
    "action" => "javascript:formSubmit1()",
));

?>
<div class="container">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div>
        <input type="hidden" id="block" name="location[block]" value="<?php echo $block ?>">
        <input type="hidden" id="drawing_id" name="location[drawing_id]" value="">
        <input type="hidden" id="project_id" name="location[project_id]" value="<?php echo $project_id ?>">
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Block</label>
        <div class="input-group col-7 padding-lr5">
            <input type="text" class="form-control " name="location[block]"
                   value="<?php echo $block ?>"  readonly/>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Level</label>
        <div class="input-group col-7 padding-lr5">
            <input type="text" class="form-control " name="location[level]"
                   value=""    placeholder="Level"/>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Unit</label>
        <div class="input-group col-7 padding-lr5">
            <input type="text" class="form-control " name="location[unit]"
                   value=""    placeholder="Unit"/>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Floor Type</label>
        <div class="input-group col-7 padding-lr5">
            <select class="form-control input-sm" name="location[type]" id="contractor_type" style="width: 100%;">
<!--                <option value=''>---Please Select---</option>-->
                <option value='2'>NA</option>
                <option value='1'>Typical Level</option>
                <option value='0'>Non-typical Level</option>
            </select>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="group_name" class="col-2 control-label offset-md-1  label-rignt">Drawings</label>
        <!-- <div class="input-group col-8"> -->
        <div class="input-group col-7 padding-lr5">
            <input class="form-control" name="file" id="file" type="file" style="display:none" onchange="raupload(this)">
            <div class="input-group ">
                <input type="text" name="file" class="form-control" id="uploadurl" onclick="$('#file').click();"  readonly>
                <span class="input-group-btn">
                    <a class="btn btn-warning" style="height: 34px;" onclick="$('input[id=file]').click();">
                    <i class="fa fa-folder-open"></i> <?php echo Yii::t('common','button_browse'); ?></a>
                </span>
            </div>
        </div>
        <!-- </div> -->
    </div>

    <div class="form-group" style="margin-top: 10px;text-align: center">
        <div class="col-12">
            <button type="submit" id="sbtn" class="btn btn-primary"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back('<?php echo $block ?>','<?php echo $project_id ?>');"><?php echo Yii::t('common', 'back'); ?></button>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
<script type="text/javascript" src="js/loading_upload.js"></script>
<script type="text/javascript">

    var n = 4;
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

    var back = function (block,project_id) {
        window.location = "index.php?r=proj/location/locationlist&program_id="+project_id+"&block="+block;
    }

    //提交表单
    var formSubmit1 = function () {
        var params = $('#form1').serialize();
        $.ajax({
            url: "index.php?r=proj/location/savelocation&" + params,
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                // showTime(data.refresh);
                //window.location = "index.php?ctc/handover/recordlist&apply_id=<?php //echo $apply_id ?>//";
                // location.reload();
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }


    var raupload = function(file,rowLength){
        var A=[90,92,93,94,95,96,98];
        var file_list = $('#file')[0].files;
        console.log(file.value);
        $.each(file_list, function (name, file) {
            if (!/\.(jpg|jpeg|png|JPG|PNG|pdf)$/.test(file.name)) {
                alert("Please upload document in either .jpeg, .jpg, .png or .pdf format.");
                return false;
            }
            ra_tag = file.name.lastIndexOf(".");
            ra_length = file.name.length;
            //获取后缀名
            ra_type=file.name.substring(ra_tag,ra_length);
            ra_name = file.name.substr(0,ra_tag);
            var video_src_file = file.name;
            containSpecial = new RegExp(/[\~\%\^\*\[\]\{\}\|\\\;\:\'\"\,\.\/\?]+/);
            status = containSpecial.test(ra_name);
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
                    // drawing_num =  parseInt(drawing_num)+1;
                    $.each(data, function (name, value) {
                        if (name == 'data') {
                            var draw_id = add_file(value.file1);
                            var index = Math.ceil(Math.random()*6);
                            // $('#drawing_id').val(A[index]);
                            $('#uploadurl').val(file.name);
                            $('#drawing_id').val(draw_id);
                        }
                    });
                }
            });
        })
    }

    function add_file(file_path){
        var project_id = $('#project_id').val();
        var draw_id;
        $.ajax({
            data: {file_path: file_path,project_id:project_id},
            url: "index.php?r=proj/project/adddraw",
            dataType: "json",
            async: false,
            type: "POST",
            success: function (data) {
                draw_id =  data.drawing_id;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
        return draw_id;
    }
</script>