<?php
?>
<div class="card card-info card-outline">
    <div class="card-body">

        <div class="form-group group-space-between">
            <label for="group_name" class="col-2 control-label offset-md-1  label-rignt"><?php echo Yii::t('comp_staff', 'Select file'); ?></label>
            <!-- <div class="input-group col-8"> -->
            <div class="input-group col-6 padding-lr5">
                <input id="drawing_id" type="hidden" value="<?php echo $draw_id ?>">
                <?php
                    if($draw_id != '') {
                        $draw_list = explode('|', $draw_id);
                        $draw_num =count($draw_list);
                    }else{
                        $draw_num = 0;
                    }
                ?>
                <input id="drawing_num" type="hidden" value="<?php echo $draw_num ?>">
                <input id="project_id" type="hidden" value="<?php echo $project_id ?>">
                <input id="index" type="hidden" value="<?php echo $index ?>">
                <input id="son_index" type="hidden" value="<?php echo $son_index ?>">
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

        <div class="row" style="padding-left: 10px;">
            <div class="col-sm-12 padding-lr5">
                <div class="row offset-md-2" id="attach">
                    <?php
                        if($draw_id != ''){
                            $draw_list = explode('|',$draw_id);
                            foreach ($draw_list as $i => $j){
                                $drawing_model = ProgramDrawing::model()->findByPk($j);
                                $drawing_path = $drawing_model->drawing_path;
                                $drawing_name = $drawing_model->drawing_name;
                                $drawing_name = substr($drawing_name,8);
                                echo "<div class='row col-12 ' style='margin-top: 8px;'><div class='col-sm-6 padding-lr5' style='text-align: left;padding-left: 0px;'>$drawing_name</div><div class='col-sm-2 padding-lr5' style='text-align: left'><a class='a_logo' onclick='previewdraw(".$j.")'  style='cursor:pointer;'  title='Preview'><i class='fa fa-fw fa-eye'></i></a><a class='a_logo' onclick='del_attachment(this,$j)'   style='cursor:pointer;'  title='Delete'><i class='fa fa-fw fa-times'></i></a></div></div>";
                            }
                        }
                    ?>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 10px;margin-bottom: 10px">
            <div class='col-12' style="text-align: center">
                <button type="button" class="btn btn-primary"  onclick="save();">Save</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/loading_upload.js"></script>
<script type="text/javascript">
    // ????????????
    function del_attachment(obj,draw_id){
        var drawing_id = $('#drawing_id').val();
        var drawing_list = drawing_id.split('|');
        var drawing_str = '';
        for(var i=0, item; i < drawing_list.length; i++) {
            item = drawing_list[i];
            if(item != draw_id){
                if(drawing_str == ''){
                    drawing_str+=item;
                    $('#drawing_id').val(drawing_str);
                }else{
                    drawing_str+='|';
                    drawing_str+=item;
                    $('#drawing_id').val(drawing_str);
                }
            }
        }
        var index = $(obj).parents("tr").index(); //?????????????????????tr?????????
        $(obj).parent().parent().remove();
        var drawing_num = $('#drawing_num').val();
        drawing_num =  parseInt(drawing_num)-1;
        $('#drawing_num').val(drawing_num);
    }
    function save(){
        var index = $('#index').val();
        var son_index = $('#son_index').val();
        var drawing_id = $('#drawing_id').val();
        window.parent.document.getElementById("level"+index+'_'+son_index+'_hfile').value = drawing_id;
        if(drawing_id == ''){
            window.parent.document.getElementById("upload_"+index+'_'+son_index).className = 'a_logo';
        }else{
            window.parent.document.getElementById("upload_"+index+'_'+son_index).className = 'c_logo';
        }
        $("#modal-close").click();
    }
    //??????
    function previewdraw (draw_id) {
        // path = encodeURIComponent(path);
        // var tag = path.slice(-3);
        window.open("index.php?r=proj/project/previewdraw&draw_id="+draw_id,"_blank");
    }

    /**
     * ??????base64?????????url???????????????Blob
     * @param urlData
     *            ???url???????????????base64????????????
     */
    function convertBase64UrlToBlob(urlData){

        var bytes=window.atob(urlData.split(',')[1]);        //??????url?????????????????????byte

        //????????????,???ascii?????????0??????????????????0
        var ab = new ArrayBuffer(bytes.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < bytes.length; i++) {
            ia[i] = bytes.charCodeAt(i);
        }

        return new Blob( [ab] , {type : 'image/png'});
    }

    var raupload = function(file){
        var file_list = $('#file')[0].files;
        var drawing_num = $('#drawing_num').val();
        // if(drawing_num >= 1){
        //     alert('Only one drawing can be selected');
        //     return false;
        // }
        console.log(file.value);
        $.each(file_list, function (name, file) {
            if (!/\.(jpg|jpeg|png|JPG|PNG|pdf)$/.test(file.name)) {
                alert("Please upload document in either .jpeg, .jpg, .png or .pdf format.");
                return false;
            }
            ra_tag = file.name.lastIndexOf(".");
            ra_length = file.name.length;
            //???????????????
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

            var formData = new FormData();   //????????????form????????????????????????????????????,?????????????????????????????????????????????FormData????????????????????????
            formData.append("file1", file);

            $.ajax({
                url: "https://shell.cmstech.sg/appupload",
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,         // ??????jQuery??????????????????????????????
                contentType: false,        // ??????jQuery???????????????Content-Type?????????
                beforeSend: function () {
                    addcloud();
                },
                success: function (data) {
                    removecloud();//?????????
                    drawing_num =  parseInt(drawing_num)+1;
                    $.each(data, function (name, value) {
                        $('#drawing_num').val(drawing_num);
                        if (name == 'data') {
                            var draw_id = add_file(value.file1);
                            if(ra_type == '.pdf' || ra_type == '.jpg' || ra_type == '.png' || ra_type == '.jpeg'){
                                var $tr = $("<div class='row col-12 ' style='margin-top: 8px;'><div class='col-sm-6 padding-lr5' style='text-align: left;padding-left: 0px;'>"+video_src_file+"</div><div class='col-sm-2 padding-lr5' style='text-align: left'>"+"<a class='a_logo' onclick='previewdraw(\""+draw_id+"\")'  style='cursor:pointer;'  title='Preview'><i class='fa fa-fw fa-eye'></i></a>"+"<a class='a_logo' onclick='del_attachment(this,\""+draw_id+"\")'   style='cursor:pointer;'  title='Delete'><i class='fa fa-fw fa-times'></i></a></div></div>");
                            }else{
                                var $tr = $("<div class='row col-12 ' style='margin-top: 8px;'><div class='col-sm-6 padding-lr5' style='text-align: left;padding-left: 0px;'>"+video_src_file+"</div> "+"<div class='col-sm-2 padding-lr5' style='text-align: left'><a class='a_logo' onclick='del_attachment(this,\""+draw_id+"\")'  style='cursor:pointer;'  title='Delete'><i class='fa fa-fw fa-times'></i></a><input type='hidden' name='rf[attachment][]' value='"+value.file1+"' ></div></div>");
                            }
                            var $table = $("#attach");
                            $table.append($tr);
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
                var drawing_id = $('#drawing_id').val();
                if(drawing_id == ''){
                    drawing_id+=data.drawing_id;
                    $('#drawing_id').val(drawing_id);
                }else{
                    drawing_id+='|';
                    drawing_id+=data.drawing_id;
                    $('#drawing_id').val(drawing_id);
                }
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
