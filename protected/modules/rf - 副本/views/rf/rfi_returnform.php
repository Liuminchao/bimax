<link href="css/select2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .content {
        padding: 0px 15px;
        background: #F2F2F2;
    }
    body{ background-color: #F2F2F2;}
</style>
<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    'autoValidation' => false,
//    "action" => "javascript:formSubmit()",
//    'enableAjaxSubmit' => false,
//    'ajaxUpdateId' => 'content-body',
//    'role' => 'form', //可省略
//    'formClass' => 'form-horizontal', //可省略 表单对齐样式

));
?>
<div class="container" style="padding-top: 8px;">
    <div class="row">
        <div class="col-1">
        </div>
        <div class="col-10" style="background-color: #F2F9FA;text-align: center">
            <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
                <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
                <strong id='msginfo'></strong><span id='divMain'></span>
            </div>
            <div class="row" >
                <div class ="col-md-4 offset-md-9">
                    <input type="hidden" id="program_id" name="rf[program_id]" value="<?php echo $program_id; ?>">
                    <input type="hidden" id="type_id" name="rf[type_id]" value="<?php echo $type; ?>">
                    <input type="hidden" id="to_user"  value="<?php echo $to_user; ?>">
                    <input type="hidden" id="cc_user"  value="<?php echo $cc_user; ?>">
                    <input type="hidden" id="check_id" name="rf[check_id]" value="<?php echo $check_id; ?>">
                    <input type="hidden" id="filebase64"/>
                </div>
            </div>
            <?php
            $program_model = Program::model()->findByPk($program_id);
            $program_name = $program_model->program_name;
            $con_id = $program_model->contractor_id;
            $con_model = Contractor::model()->findByPk($con_id);
            $con_name = $con_model->contractor_name;
            $con_adr = $con_model->company_adr;
            $con_phone = $con_model->link_phone;
            ?>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-1 offset-md-1 control-label padding-lr5" style="text-align: left">To*</label>
                <div class="col-sm-3 padding-lr5">
                    <select id="to_contractor_id" class="form-control" onchange="to_create_data()">
                        <?php
                        $contractor_list = Contractor::CompanyListByProgram($program_id);
                        $group_list = RfGroup::groupByProgram($program_id);
                        if(count($group_list)>0){
                            echo "<option value=''>--Select Group--</option>";
                            foreach ($group_list as $group_id => $group_name) {
                                $group_id = 'Group'.$group_id;
                                echo "<option value='{$group_id}'>{$group_name}</option>";
                            }
                        }
                        $apply_user = Staff::model()->findByPk($to_user);
                        $apply_contractor_id = $apply_user->contractor_id;
                        //                        echo "<option value=''>--Select Company--</option>";
                        //                        foreach ($contractor_list as $contractor_id => $contractor_name) {
                        //                            if($contractor_id == $apply_contractor_id){
                        //                                echo "<option value='{$contractor_id}' selected>{$contractor_name}</option>";
                        //                            }else{
                        //                                echo "<option value='{$contractor_id}'>{$contractor_name}</option>";
                        //                            }
                        //                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-4 padding-lr5">
                    <select id="to" name="rf[to]" class="form-control" multiple="multiple" >
                    </select>
                </div>
                <!--                <button type="button" class="btn btn-primary" style="background-color: #169BD5">Directory</button>-->
            </div>

            <?php
            $rf_model = RfList::model()->findByPk($check_id);
            $step = $rf_model->current_step;
            $detail = RfDetail::dealListByStep($check_id,$step);
            ?>
            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-1 offset-md-1 control-label padding-lr5" style="text-align: left">Cc</label>
                <div class="col-sm-3 padding-lr5">
                    <select id="cc_contractor_id" class="form-control" onchange="cc_create_data()">
                        <?php
                        $contractor_list = Contractor::CompanyListByProgram($program_id);
                        $group_list = RfGroup::groupByProgram($program_id);
                        if(count($group_list)>0){
                            echo "<option value=''>--Select Group--</option>";
                            foreach ($group_list as $group_id => $group_name) {
                                $group_id = 'Group'.$group_id;
                                echo "<option value='{$group_id}'>{$group_name}</option>";
                            }
                        }
                        //                        echo "<option value=''>--Select Company--</option>";
                        //                        foreach ($contractor_list as $contractor_id => $contractor_name) {
                        //                            echo "<option value='{$contractor_id}'>{$contractor_name}</option>";
                        //                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-4 padding-lr5">
                    <select id="cc" name="rf[cc]" class="form-control" multiple="multiple" class="form-control">
                    </select>
                </div>
                <!--                <button type="button" class="btn btn-primary" style="background-color: #169BD5">Directory</button>-->
            </div>

            <?php
            $apply_user_id = $rf_model->apply_user_id;
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            if($user[0]['user_id'] == $apply_user_id){
                ?>
                <div class="row" style="margin-top: 8px;">
                    <label for="program_name" class="col-sm-4 offset-md-1 control-label padding-lr5" style="text-align: left">Latest Date to Reply</label>
                    <div class="input-group col-sm-3 padding-lr5" style="padding-left: 5px;">
                        <?php
                        $Date = Utils::DateToEn(date('Y-m-d',strtotime('+14 day')));
                        ?>
                        <input id="valid_time" class="form-control b_date_ins" style="background: #F2F2F2" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" check-type="" name="rf[valid_time]" type="text" value="<?php echo $Date; ?>">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>

            <div class="row" style="margin-top: 8px;">
                <div class="form-group">
                    <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Attachment</label>
                    <div class="col-sm-5 padding-lr5">
                        <input id="file" multiple="multiple" class="form-control" check-type="" style="display:none" onchange="raupload(this)" name="File[file_path]" type="file" />
                        <button type="button" class="btn btn-primary" style="background-color: #169BD5" onclick="file.click()">Add</button>
                    </div>
                </div>
            </div>

            <div class="row" >
                <table id="attach" class="offset-md-1" style="width: 60%">

                </table>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-4 offset-md-1 control-label padding-lr5" style="text-align: left">Message</label>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div class="col-sm-8 offset-md-1 padding-lr5">
                    <textarea rows="10" id="message" name = "rf[message]" style="width:100%"></textarea>
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div class="col-sm-8 padding-lr5">
                    <button type="button" class="btn btn-primary" style="background-color: #20B2AA" onclick="cancel()" >Cancel</button>
                    <button type="button" class="btn btn-primary" style="background-color: #33CCCC;float: right" onclick="reply()">Send</button>
                </div>
            </div>

        </div>
        <div class="col-1">
    </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript" src="js/loading_upload.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/select2/select2.js"></script>
<script type="text/javascript" src="js/layui.js" ></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#to_contractor_id').select2();
        $('#cc_contractor_id').select2();
        to_create_data('2');
        cc_create_data('2');
    })

    function to_create_data(tag) {
        var contractor_id = $('#to_contractor_id').val();
        var check_id = $('#check_id').val();
        if(tag == '2'){
            $.ajax({
                url: "index.php?r=rf/rf/tolist",
                data: {check_id: check_id, confirm: 1},
                type: "POST",
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    // $('#cc').empty();
                    $('#to').select2({
                        data: res,  //返回的数据
                    });
                    var to_arr = [];
                    $.each(res, function (name, value) {
                        to_arr.push(value.id);
                    });
                    // var to_str = $("#to_user").val();
                    // var to_arr = to_str.split(',');
                    console.log(to_arr);
                    $("#to").val(to_arr).trigger('change');
                }
            })
        }else{
            if(contractor_id.indexOf("Group") == -1){
                $.ajax({
                    url: "index.php?r=rf/rf/stafflist",
                    data: {contractor_id: contractor_id, confirm: 1},
                    type: "POST",
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        $('#to').empty();
                        $('#to').select2({
                            data: res,  //返回的数据
                        });
                    }
                })
            }else{
                $.ajax({
                    url: "index.php?r=rf/group/userlist",
                    data: {group_id: contractor_id, confirm: 1},
                    type: "POST",
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        $('#to').empty();
                        $('#to').select2({
                            data: res,  //返回的数据
                        });
                        to_arr = [];
                        $.each(res, function (index, data) {
                            to_arr.push(data.id);
                        })
                        // $("#to").val(to_arr).trigger('change');
                    }
                })
            }
        }
    }

    function cc_create_data(tag) {
        var contractor_id = $('#cc_contractor_id').val();
        var check_id = $('#check_id').val();
        if(tag == '2'){
            $.ajax({
                url: "index.php?r=rf/rf/returncclist",
                data: {check_id: check_id, confirm: 1},
                type: "POST",
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    // $('#cc').empty();
                    $('#cc').select2({
                        data: res,  //返回的数据
                    });
                    var cc_str = $("#cc_user").val();
                    console.log(cc_str);
                    var cc_arr = cc_str.split(',');
                    console.log(cc_arr);
                    $("#cc").val(cc_arr).trigger('change');
                }
            })
        }else{
            if(contractor_id.indexOf("Group") == -1){
                $.ajax({
                    url: "index.php?r=rf/rf/stafflist",
                    data: {contractor_id: contractor_id, confirm: 1},
                    type: "POST",
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        // $('#cc').empty();
                        $('#cc').select2({
                            data: res,  //返回的数据
                        });

                    }
                })
            }else{
                $.ajax({
                    url: "index.php?r=rf/group/userlist",
                    data: {group_id: contractor_id, confirm: 1},
                    type: "POST",
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        // $('#cc').empty();
                        $('#cc').select2({
                            data: res,  //返回的数据
                        });
                        cc_arr = [];
                        $.each(res, function (index, data) {
                            cc_arr.push(data.id);
                        })
                        // $("#cc").val(cc_arr).trigger('change');
                    }
                })
            }
        }
    }

    // 删除一行
    function del_attachment(obj){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标
        $(obj).parents("tr").remove();
    }
    //预览
    function previewdoc (path) {
        path = encodeURIComponent(path);
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/previewdoc&doc_path="+path,"_blank");
        }else{
            window.open('https://shell.cmstech.sg'+path,"_blank");
        }
    }

    /**
     * 将以base64的图片url数据转换为Blob
     * @param urlData
     *            用url方式表示的base64图片数据
     */
    function convertBase64UrlToBlob(urlData){

        var bytes=window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte

        //处理异常,将ascii码小于0的转换为大于0
        var ab = new ArrayBuffer(bytes.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < bytes.length; i++) {
            ia[i] = bytes.charCodeAt(i);
        }

        return new Blob( [ab] , {type : 'image/png'});
    }

    var raupload = function(file){
        var file_list = $('#file')[0].files;
        console.log(file.value);
        $.each(file_list, function (name, file) {
            if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG|pdf|doc|docx|xls|xlsx|dwg|zip|rar)$/.test(file.name)) {
                alert("Please upload document in either .gif, .jpeg, .jpg, .png, .doc, .xls, .dwg, .zip, .rar, .xlsx, .docx or .pdf format.");
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
                    $.each(data, function (name, value) {
                        if (name == 'data') {
                            if(ra_type == '.pdf' || ra_type == '.jpg' || ra_type == '.png' || ra_type == '.jpeg'){
                                var $tr = $("<tr> <td  align='left' ><img  src='img/attach.png' ></td><td  align='left' >"+video_src_file+"</td> <td  align='center' width='15%'>"+"<a onclick='previewdoc(\""+value.file1+"\")'>Preview</a></td>"+"<td  align='center' width='15%'><a onclick='del_attachment(this)'>Delete</a><input type='hidden' name='rf[attachment][]' value='"+value.file1+"' ></td></tr>");
                            }else{
                                var $tr = $("<tr> <td  align='left'><img  src='img/attach.png' >"+video_src_file+"</td> <td  align='right'>"+"<a onclick='del_attachment(this)'>Delete</a><input type='hidden' name='rf[attachment][]' value='"+value.file1+"' ></td></tr>");
                            }
                            var $table = $("#attach");
                            $table.append($tr);
                        }
                    });
                }
            });
        })
    }

    //浏览PDF
    function preview_attachment(path){
        path = encodeURIComponent(path);
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/preview&doc_path="+path,"_blank");
        }else{
            window.open('https://shell.cmstech.sg'+path,"_blank");
        }

    }

    //添加表单其他元素
    function reply() {
        var check_id = $('#check_id').val();
        var ccDesc = $("#cc").val();
        var toDesc = $("#to").val();
        var form_data = $('#form1').serialize();
        $.ajax({
            data:form_data+"&to="+toDesc+"&cc="+ccDesc,
            url: "index.php?r=rf/rf/savereply",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud();
            },
            success: function (data) {
                removecloud();//去遮罩
                if(data.status == '-1'){
                    // $('#msgbox').addClass('alert-danger fa-ban');
                    // $('#msginfo').html(data.msg);
                    // $('#msgbox').show();
                    layui.use('layer', function(){
                        layer.msg(data.msg); //提示
                    })
                }
                if(data.status == '1'){
                    // $('#msgbox').addClass('alert-success fa-ban');
                    // $('#msginfo').html(data.msg);
                    // $('#msgbox').show();
                    // alert('success');
                    layui.use('layer', function(){
                        layer.msg('success'); //提示
                    })
                    window.location = "index.php?r=rf/rf/info&check_id=<?php echo $check_id; ?>";
                }
            },
            error: function () {
                removecloud();//去遮罩
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统超时');
                $('#msgbox').show();
            }
        });
    }


    //取消
    function cancel (program_id) {
        window.location = "index.php?r=rf/rf/info&check_id=<?php echo $check_id ?>";
    }
    // 删除一行
    function del_to_tr(obj,value){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标 未使用
        $(obj).parents("tr").remove(); //实现删除tr
        var to_cnt = $('#to_cnt').val();
        var to_str = $('#to_str').val();
        var to_str =  to_str.replace(','+value+',', ',');
        var to_cnt = to_cnt -1;
        $('#to_cnt').val(to_cnt);
        $('#to_str').val(to_str);
    }
    // 删除一行
    function del_cc_tr(obj,value){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标 未使用
        $(obj).parents("tr").remove(); //实现删除tr
        var cc_cnt = $('#cc_cnt').val();
        var cc_str = $('#cc_str').val();
        var cc_str =  cc_str.replace(','+value+',', ',');
        var cc_cnt = cc_cnt -1;
        $('#cc_cnt').val(cc_cnt);
        $('#cc_str').val(cc_str);
    }
</script>
