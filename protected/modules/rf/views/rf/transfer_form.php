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
    .rows_wrap {
        position: relative;
    }
    .rows_wrap .rows_title {
        padding-left: 6px;
    }
    .rows_wrap .rows_content {
        float: right;
        padding-bottom: 30px;
        position: relative;
        width: 841px;
        padding-left: 5px;
        left:-230px;
    }
    /*.wrap>div {*/
    /*opacity: 0;*/
    /*padding: 10px;*/
    /*}*/

    @media screen and (max-width:640px) {
        button {
            width: 100%;
            margin: 2% auto;
            padding: 10px;
        }
    }
    .content{
        background:#C8D7D2
    }
</style>
<link rel="stylesheet" href="css/jquery.tag-editor.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
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
$upload_url = Yii::app()->params['upload_url'];//上传地址
?>

<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>

    <input id="check_id" name="rf[check_id]" class="form-control" style="background: #F2F2F2" type="hidden" readonly value="<?php echo $check_id; ?>">


    <?php
    if($deal_type == '2' || $deal_type == '3'){ ?>
        <div class="row">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label padding-lr5">To*</label>
                <div class="col-sm-3 padding-lr5">
                    <select name="to_select" id="to_select" class="form-control" style="background: #F2F2F2;" multiple="multiple">
                        <?php
                        $rf_model = RfList::model()->findByPk($check_id);
                        $program_id = $rf_model->program_id;
                        $contractor_id = $rf_model->contractor_id;
                        $user_list = ProgramUser::myUserList($program_id,$contractor_id);
                        foreach($user_list as $user_id => $user_name){ ?>
                            <option value="<?php echo $user_id ?>"><?php echo $user_name ?></option>
                        <?php } ?>
                    </select>
                    <input id="to" type="hidden" name="rf[to]" class="form-control" style="background: #F2F2F2"   value="">
                </div>
            </div>
        </div>

        <!--RFA页面 判断-->
        <?php $rf_model = RfList::model()->findByPk($check_id); $type = $rf_model->type; if($type == '2'){ ?>
            <div class="row"  id="for_information">
                <div class="col-md-6 offset-md-2" style="margin-bottom: 3px">
                    <div class="input-group">
                        <input type="radio"  name="rf[to_radio]" value="1" aria-label="Radio button for following text input">
                        Request for Information
                    </div><!-- /input-group -->
                </div>
            </div>

            <div class="row"  id="request_approval">
                <div class="col-md-6 offset-md-2" style="margin-bottom: 3px">
                    <div class="input-group">
                        <input type="radio" name="rf[to_radio]" value="2" aria-label="Radio button for following text input">
                        Request for Approval
                    </div><!-- /input-group -->
                </div>
            </div>

        <?php }else{//RFI 中转发页面
            if($deal_type == '3'){
            ?>
                <div class="row"  id="for_information">
                    <div class="col-md-6 offset-md-2" style="margin-bottom: 3px">
                        <div class="input-group">
                            <input type="radio"  name="rf[to_radio]" value="1" aria-label="Radio button for following text input">
                            Request for Information
                        </div><!-- /input-group -->
                    </div>
                </div>

                <div class="row"  id="request_approval">
                    <div class="col-md-6 offset-md-2" style="margin-bottom: 3px">
                        <div class="input-group">
                            <input type="radio" name="rf[to_radio]" value="2" aria-label="Radio button for following text input">
                            Request for Approval
                        </div><!-- /input-group -->
                    </div>
                </div>
        <?php }} ?>

        <div class="row">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label padding-lr5">Cc</label>
                <div class="col-sm-3 padding-lr5">
                    <select name="cc_select" id="cc_select" class="form-control" style="background: #F2F2F2" multiple="multiple">
                        <?php foreach($user_list as $user_id => $user_name){ ?>
                            <option value="<?php echo $user_id ?>"><?php echo $user_name ?></option>
                        <?php } ?>
                    </select>
                    <input id="cc" type="hidden" name="rf[cc]" class="form-control" style="background: #F2F2F2"   value="">
                </div>
            </div>
        </div>
    <?php } ?>
    <!--    <div class="row" style="display:none;" id="request_others">-->
    <!--        <div class="col-md-6 offset-md-2" style="margin-bottom: 6px">-->
    <!--            <div class="input-group">-->
    <!--                <input type="radio" name="rf[to_radio]" value="3" aria-label="Radio button for following text input">-->
    <!--                Request for Others-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->



    <div class="row"><!--  RA文件上传  -->
        <div class="form-group">
            <label for="file" class="col-sm-2 control-label padding-lr5" >RA Upload</label>
            <div class="col-sm-5 padding-lr5">
                <input id="ytra_path" type="hidden" value="" name="RaBasic[ra_path]" /><input id="ra_path" class="form-control" check-type="" style="display:none" onchange="raupload(this)" name="RaBasic[ra_path]" type="file" />                        <div class="input-group col-md-6 padding-lr5">
                    <input id="raurl" class="form-control" type="text" disabled>
                    <span class="input-group-btn">
                                    <a class="btn btn-warning" onclick="$('input[id=ra_path]').click();">
                                <i class="fa fa-folder-open"></i> Browse                            </a>
                        </span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="worker_type" class="col-sm-2 control-label padding-lr5">File</label>
        <div class="col-sm-6 padding-lr5">
            <table id="attachment">

            </table>
        </div>
    </div>

    <div class="form-group">
        <label for="worker_type" class="col-sm-2 control-label padding-lr5">Viewpoin & component</label>
        <div class="col-sm-6 padding-lr5" id="J-sd-demo">
            <table >
                <tr><td ><img  src="img/pic_min.png" ></td> <td >Viewpoint</td>  <td  ><button type="button"  onclick="add_view()">Edit</button><input type="hidden" id="view" name="rf[view]" ><input type="hidden" id="model_view_id" name="rf[model_view_id]" ></td></tr>
                <tr><td ><img  src="img/component_min.png" ></td> <td  >Component name</td> <td ><button type="button"  onclick="add_component()">Edit</button><input type="hidden" id="uuid" name="rf[uuid]" ><input type="hidden" id="entityId" name="rf[entityId]" ><input type="hidden" id="model_component_id" name="rf[model_component_id]" ></td></tr>
            </table>
        </div>
    </div>

    <?php if($deal_type != '4'){ ?>
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5">Message</label>
            <div class="col-sm-3 padding-lr5">
                <textarea rows="10" cols="40" id="message" name = "rf[message]"></textarea>
            </div>
        </div>
    <?php } ?>

    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="button" id="sbtn" class="btn btn-primary"
                        style="margin-left: 5px; background: #44ACB7" onclick="cancel('<?php echo $check_id; ?>');">Back</button>
                <?php if($deal_type == '2'){ ?>
                    <button type="button" class="btn btn-primary"
                            style="margin-left: 35px; background: #44ACB7" onclick="send('<?php echo $deal_type; ?>')">Send</button>
                <?php }else if($deal_type == '3'){ ?>
                    <button type="button" class="btn btn-primary"
                            style="margin-left: 35px; background: #44ACB7" onclick="send('<?php echo $deal_type; ?>')">Send</button>
                <?php }else if($deal_type == '6'){ ?>
                    <button type="button" class="btn btn-primary"
                            style="margin-left: 35px; background: #44ACB7" onclick="send('<?php echo $deal_type; ?>')">Reject</button>
                <?php }else if($deal_type == '4'){ ?>
                    <button type="button" class="btn btn-primary"
                            style="margin-left: 35px; background: #44ACB7" onclick="send('<?php echo $deal_type; ?>')">Approve</button>
                <?php }else if($deal_type == '5'){ ?>
                    <button type="button" class="btn btn-primary"
                            style="margin-left: 35px; background: #44ACB7" onclick="send('<?php echo $deal_type; ?>')">Approve</button>
                <?php } ?>
            </div>
        </div>
    </div>

</div>

<?php $this->endWidget(); ?>

<!--<iframe id="attendIframe" style="margin: 0 auto;" name="attendIframe"  frameborder="0" class="iframe_r" src="http://localhost/ctmgr/index.php?r=rf/rfi/demo" style="height:200px;;width:100%; background-color:#fff;"></iframe>-->

<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript" src="js/loading.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.caret.min.js"></script>
<script src="js/jquery.tag-editor.js"></script>
<script type="text/javascript" src="js/zDrag.js"></script>
<script type="text/javascript" src="js/zDialog.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<!--<script type="text/javascript" src="js/mSlider.min.js"></script>-->
<!--<script type="text/javascript" src="js/WIND.js"></script>-->
<!--<script type="text/javascript"z src="js/easytest.js"></script>-->
<script type="text/javascript">
    $(function() {

    });
    window.onload = function () {
        $("#to_select").select2({
            language : "zh-CN",
            minimumInputLength : 0,
            placeholder:"To..",//默认值
            allowClear: true,
        });
        $("#cc_select").select2({
            language : "zh-CN",
            minimumInputLength : 0,
            placeholder:"Cc..",//默认值
            allowClear: true,
        });
//        var select_id = $("#cc").val();
//        arr = select_id.split(",");//注意：arr为select的id值组成的数组
//        var arr = ['859','860','861'];
//        $('#to_select').val(arr).trigger('change');
    }
    $('#to_select').change(function(){
        var o=document.getElementById('to_select').getElementsByTagName('option');
        var all_to="";
        console.log(o[1]);
        for(var i=0;i<o.length;i++){
            if(o[i].selected){
                all_to+=o[i].value+",";
            }
        }

        all_to = all_to.substr(0, all_to.length - 1);//去掉末尾的逗号
        $("#to").val(all_to);//赋值给隐藏的文本框
    });
    $('#cc_select').change(function(){
        var t=document.getElementById('cc_select').getElementsByTagName('option');
        var all_cc="";
        console.log(t[1]);
        for(var i=0;i<t.length;i++){
            if(t[i].selected){
                all_cc+=t[i].value+",";
            }
        }

        all_cc = all_cc.substr(0, all_cc.length - 1);//去掉末尾的逗号
        $("#cc").val(all_cc);//赋值给隐藏的文本框
    });
    var SelectToChange = function (index) {
        if(index == 'RFI'){
            document.getElementById('for_information').style.display="none";
            document.getElementById('request_approval').style.display="none";
//            document.getElementById('request_others').style.display="none";
        }else{
            document.getElementById('for_information').style.display="block";
            document.getElementById('request_approval').style.display="block";
//            document.getElementById('request_others').style.display="block";
        }
    }

    function add_component() {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 770;
        diag.Title = "Model Component";
        diag.URL = "addcomponent";
        diag.show();
    }
    function add_view() {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 770;
        diag.Title = "Model View";
        diag.URL = "addview";
        diag.show();
    }
    
    //取消
    function cancel (check_id) {
        window.location = "index.php?r=rf/rf/info&check_id="+check_id;
    }

    var raupload = function(file){
        if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG|pdf|doc)$/.test(file.value)) {
            alert("Please upload document in either .gif, .jpeg, .jpg, .png or .pdf format.");
            return false;
        }
        ra_tag = $('#ra_path')[0].files[0].name.lastIndexOf(".");
        ra_name = $('#ra_path')[0].files[0].name.substr(0,ra_tag);
        var video_src_file = $("#ra_path").val();
        document.getElementById('raurl').value=file.value;
        containSpecial = new RegExp(/[(\~)(\%)(\^)(\&)(\*)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\?)(\)]+/);
        status = containSpecial.test(ra_name);
        if(status == 'true'){
            alert('File name contains special characters, please check before uploading');
            return false;
        }
        var newFileName = video_src_file.split('.');

        var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
        formData.append("file1", $('#ra_path')[0].files[0]);

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
                        //<tr><td width="20%"><img  src="img/attach.png" ></td> <td  width="30%">drawing</td> <td  width="20%">Preview</td> <td  width="20%">Delete</td></tr>
//                        var $tr = $("<tr>"+
//                            "<td>"+name+"</td>"+
//                            "<td>"+email+"</td>"+
//                            "<td>"+tel+"</td>"+
//                            "<td><a href='deleteEmp?id="+name+"'>删除</a></td>"
//                            +"</tr>");
//                        alert(value.file1);
                        var $tr = $("<tr><td ><img  src='img/attach.png' ></td> <td  >drawing</td> <td >"+"<button type='button' onclick='preview_attachment(\""+value.file1+"\")'>Preview</button>"+"</td> <td ><button type='button' onclick='del_attachment(this)'>Delete</button><input type='hidden' name='rf[attachment][]' value='"+value.file1+"' ></td></tr>");
                        var $table = $("#attachment");
                        $table.append($tr);
                    }
                });
            }
        });
    }
    // 删除一行
    function del_attachment(obj){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标
        $(obj).parents("tr").remove();
    }
    //浏览PDF
    function preview_attachment(path){
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/preview&doc_path="+path,"_blank");
        }else{
            window.open("index.php?r=rf/rf/previewdoc&doc_path="+path,"_blank");
        }
    }
    //添加表单其他元素
    function send(deal_type) {
        var send_url = '';
        if(deal_type == '3'){
            send_url = 'index.php?r=rf/rf/saveforward';
        }else if(deal_type == '6'){
            send_url = 'index.php?r=rf/rf/savereject';
        }else if(deal_type == '4'){
            send_url = 'index.php?r=rf/rf/saveapprove';
        }else if(deal_type == '5'){
            send_url = 'index.php?r=rf/rf/saveapprovecomment';
        }
        var view = sessionStorage.getItem("view");
        $('#view').val(view);
        var model_view_id = sessionStorage.getItem("model_view_id");
        $('#model_view_id').val(model_view_id);
        var uuid = sessionStorage.getItem("uuid");
        $('#uuid').val(uuid);
        var entityId = sessionStorage.getItem("entityId");
        $('#entityId').val(entityId);
        var model_component_id = sessionStorage.getItem("model_component_id");
        $('#model_component_id').val(model_component_id);
        $.ajax({
            data:$('#form1').serialize(),
            url: send_url,
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success fa-ban');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                alert('success');
                sessionStorage.clear();
                window.location = "index.php?r=rf/rf/info&check_id="+<?php echo $check_id; ?>;
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统超时');
                $('#msgbox').show();
            }
        });
    }

</script>